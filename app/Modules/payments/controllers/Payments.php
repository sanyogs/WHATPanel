<?php
 /*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

namespace app\Modules\payments\controllers;
use App\Libraries\AppLib;
use App\Models\App;
use App\Models\Client;
use App\Models\FAQS;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\ThirdParty\MX\WhatPanel;
use App\ThirdParty\MX\Modules;
use DateInterval;
use DateTime;

use App\Helpers\custom_name_helper;

class Payments extends WhatPanel 
{
    function __construct()
    {
        // parent::__construct();
        // User::logged_in();

        // $this->load->module('layouts');
        // $this->load->library(array('template','form_validation')); 

        // App::module_access('menu_payments');

        // $this->applib->set_locale();

    }

    function index()
    {	
		$request = \Config\Services::request();
        // $this->template->title(lang('hd_lang.payments'));
        $data['page'] = lang('hd_lang.payments');
        $data['datatables'] = TRUE;
        $data['payments'] = $this->_payments_list();
		
		$payments = new Payment();
		
		 // Pagination Configuration
		$page = $request->getGet('page') ? $request->getGet('page') : 1;

        $perPage = $request->getGet('recordsPerPage', FILTER_SANITIZE_NUMBER_INT) ? $request->getGet('recordsPerPage', FILTER_SANITIZE_NUMBER_INT) : 10;

		// Search Filter
		$search = $request->getGet('search');
		$data['search'] = $search;

        $query = $payments->listItems([], $search, $perPage, $page);

        // Get items for the current page
		$data['servers'] = array_map(function($item) {
			return (object) $item;
		}, $query['items']);

        $data['pager'] = $query['pager'];

		$data['message'] = $query['message'];

		$data['perPage'] = $perPage;
        // $this->template
        //     ->set_layout('users')
        //     ->build('payments',isset($data) ? $data : NULL);
        return view('modules/payments/payments', $data);
    }



    function edit($transaction = NULL)
    {
        $request = \Config\Services::request();

        $session = \Config\Services::session();

        if ($request->getPost()) {
            $id = $request->getPost('p_id');

            $validation = \Config\Services::validation();

            // Set the validation rules
            $validation->setRules([
                'payment_date' => 'required',
                'amount' => 'required',
            ]);

            if (!$validation->withRequest($this->request)->run())
            {
                $_POST = '';
                // Applib::go_to('payments/edit/'.$id,'error',lang('hd_lang.error_in_form'));
                $session->setFlashdata('error', lang('hd_lang.error_in_form'));
                return redirect()->to('payments/edit/'.$id);
            }else{

                //$_POST['payment_date'] = Applib::date_formatter($_POST['payment_date']);

                $_POST['month_paid'] = date("m",strtotime($_POST['payment_date']));
                $_POST['year_paid'] = date("Y",strtotime($_POST['payment_date']));

                Payment::update_pay($id,$request->getPost());

                $payment = Payment::view_by_id($id);

                $data = array(
                    'module' => 'invoices',
                    'module_field_id' => $payment->invoice,
                    'user' => User::get_id(),
                    'activity' => 'activity_edited_payment',
                    'icon' => 'fa-pencil',
                    'value1' => $payment->trans_id,
                    'value2' => $payment->currency.''.$payment->amount
                );
                App::Log($data);

                //Applib::go_to('payments/edit/'.$id,'success',lang('hd_lang.payment_edited_successfully'));
                $session->setFlashdata('success', lang('hd_lang.payment_edited_successfully'));
				return redirect()->to('payments/edit/'.$id);

            }
        }else{
            //$this->template->title(lang('hd_lang.payments'));
            $data['page'] = lang('hd_lang.edit_payment');
            $data['datepicker'] = TRUE;
            $data['payments'] = $this->_payments_list();
            $data['id'] = $transaction;

            // $this->template
            //     ->set_layout('users')
            //     ->build('edit_payment',isset($data) ? $data : NULL);
            return view('modules/payments/edit_payment', $data);

        }
    }

    function view($id =NULL)
    {
        //$this->template->title(lang('hd_lang.payments'));
        $data['page'] = lang('hd_lang.payment');
        $data['payments'] = $this->_payments_list();
        $data['id'] = $id;
        // $this->template
        //     ->set_layout('users')
        //     ->build('view',isset($data) ? $data : NULL);
        echo view('modules/payments/view', $data);
    }


    function pdf($payment_id = NULL)
    {
        $applib = new AppLib();

        $custom = new custom_name_helper();

        $data['page'] = lang('hd_lang.payments');
        $data['id'] = $payment_id;

        // $html = $this->load->view('receipt_pdf', $data, true);
        $html = view('modules/payments/receipt_pdf', $data);

        $pdf = array(
            "html"      => $html,
            "title"     => lang('hd_lang.receipt')." ".Payment::view_by_id($payment_id)->trans_id,
            "author"    => $custom->getconfig_item('company_name'),
            "creator"   => $custom->getconfig_item('company_name'),
            "filename"  => lang('hd_lang.receipt')."-".Payment::view_by_id($payment_id)->trans_id.'.pdf',
            "badge"     => $custom->getconfig_item('display_invoice_badge')
        );

        $applib->create_pdf($pdf);

    }



    function delete($id = NULL)
    {
        $request = \Config\Services::request();

        $session = \Config\Services::session();

        $invoiceModel = new Invoice();

        if ($request->getPost()) {
            $id = $request->getPost('id');
            $payment = Payment::view_by_id($id);

            Payment::deletePayment($id); //delete transaction

            $invoiceModel->update($payment->invoice,array('status'=>'Unpaid'));

            $data = array(
                'module' => 'invoices',
                'module_field_id' => $payment->invoice,
                'user' => User::get_id(),
                'activity' => 'activity_delete_payment',
                'icon' => 'fa-times',
                'value1' => $payment->trans_id,
                'value2' => $payment->currency .''. $payment->amount
            );
            App::Log($data);

            //Applib::go_to('payments','success',lang('hd_lang.payment_deleted_successfully'));
            $session->setFlashdata('success', lang('hd_lang.payment_deleted_successfully'));
			return redirect()->to('payments');

        }else{
            $data['id'] = $id;
            //$this->load->view('modal/delete_payment',$data);
            return view('modules/payments/modal/delete_payment', $data);

        }
    }

    function refund($id = NULL){
        $request = \Config\Services::request();

        $session = \Config\Services::session();

        if($request->getPost()){
            $id = $request->getPost('id');
            $refund = Payment::view_by_id($id)->refunded;
            if($refund == 'Yes') Payment::update_pay($id,array('refunded'=>'No'));
            if($refund == 'No') Payment::update_pay($id,array('refunded'=>'Yes'));
            //Applib::go_to('payments/view/'.$id,'success',lang('hd_lang.payment_edited_successfully'));
            $session->setFlashdata('success', lang('hd_lang.payment_edited_successfully'));
			return redirect()->to('payments/view/'.$id);
        }else{
            $data['id'] = $id;
            // $this->load->view('modal/refund',$data);
            return view('modules/payments/modal/refund', $data);
        }
    }

    function _payments_list(){
        if(User::is_admin()){
            return Payment::all();
        }else{
            return Payment::by_client(User::profile_info(User::get_id_client())->company);
        }
    }




}

/* End of file payments.php */