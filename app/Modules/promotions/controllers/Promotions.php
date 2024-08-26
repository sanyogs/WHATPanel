<?php
 /*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

namespace app\Modules\promotions\controllers;

use App\Libraries\AppLib;
use App\Models\App;
use App\Models\Client;
use App\Models\User;
use App\Models\Promotions_model;
use App\ThirdParty\MX\WhatPanel;


class Promotions extends WhatPanel
{
    protected $dbName;
    protected $promotions_model;

    function __construct()
    {
        // parent::__construct();	
        // User::logged_in();    
        // $this->load->module('layouts');
        // $this->load->library('template');

        $session = \Config\Services::session();

        // Connect to the database
        $this->dbName = \Config\Database::connect();

        $this->promotions_model = new Promotions_model();
    }



    function index($id = null)
    {
        // $promotions = $this->db->get('promotions')->result();
        // $promotions = $this->dbName->table('hd_promotions')->get()->getResult();
        // $this->template->title(lang('hd_lang.promotions'));

        $request = \Config\Services::request();

        // Pagination Configuration
        $page = $request->getGet('page') ? $request->getGet('page') : 1;

        $perPage = $request->getGet('recordsPerPage', FILTER_SANITIZE_NUMBER_INT) ? $request->getGet('recordsPerPage', FILTER_SANITIZE_NUMBER_INT) : 10;

        // Search Filter
        $search = $request->getGet('search');
        $data['search'] = $search;

        $query = $this->promotions_model->listItems([], $search, $perPage, $page);

        // Get items for the current page
        $data['promotions'] = array_map(function ($item) {
            return (object) $item;
        }, $query['items']);

        // $data['promotions'] = $promotions['items'];
        $data['page'] = lang('hd_lang.promotions');
        $data['form'] = TRUE;
        $data['datepicker'] = TRUE;
        $data['datatables'] = TRUE;

        $data['pager'] = $query['pager'];

        $data['message'] = $query['message'];

        $data['perPage'] = $perPage;

        // echo "<pre>";print_r($data['pager']->links());die;
        // $this->template
        // ->set_layout('users')
        // ->build('index',isset($data) ? $data : NULL);   
        return view('modules/promotions/index', $data);
    }



    function add_promotion()
    {
        $request = \Config\Services::request();
        if ($request->getPost()) {
            // Applib::is_demo();

            $session = \Config\Services::session();

            // Connect to the database
            $dbName = \Config\Database::connect();

            $_POST['apply_to'] = $request->getPost('apply_to');
            $_POST['required'] = $request->getPost('required');
            $_POST['billing_cycle'] =  $request->getPost('billing_cycle');
            if ($dbName->table('hd_promotions')->insert($request->getPost())) {
                //$this->session->set_flashdata('response_status', 'success');
                //$this->session->set_flashdata('message', lang('hd_lang.server_added'));
                // redirect($_SERVER['HTTP_REFERER']);      
                // $redirectUrl = $request->getPost('r_url');
                return redirect()->to('promotions');
            }
        } else {
            $data['form'] = TRUE;
            $data['datepicker'] = TRUE;
            $data['intervals'] = array('monthly' => 30, 'quarterly' => 90, 'semi_annually' => 180, 'annually' => 365, 'biennially' => 730, 'triennially' => 1095);
            // $this->load->view('modal/add_promotion');
            echo view('modules/promotions/modal/add_promotion', $data);
        }
    }



    function edit($id = null)
    {
        $request = \Config\Services::request();

        $session = \Config\Services::session();
        // Connect to the database
        $dbName = \Config\Database::connect();

        if ($request->getPost()) {
            // Applib::is_demo();

            $_POST['apply_to'] = serialize($request->getPost('apply_to'));
            $_POST['required'] = serialize($request->getPost('required'));
            $_POST['billing_cycle'] =  serialize($request->getPost('billing_cycle'));

            if ($dbName->table('hd_promotions')->where('id', $request->getPost('id'))->update($_POST)) {
                // $this->session->set_flashdata('response_status', 'success');
                // $this->session->set_flashdata('message', lang('hd_lang.promotion_edited'));
                return redirect()->to('promotions');
            }
        } else {
            $data['form'] = TRUE;
            $data['datepicker'] = TRUE;
            $data['promo'] = $dbName->table('hd_promotions')->where(array('id' => $id))->get()->getResult();
            // $this->load->view('modal/edit_promotion', $data);
            echo view('modules/promotions/modal/edit_promotion', $data);
        }
    }



    function delete($id = NULL)
    {
        $request = \Config\Services::request();

        $session = \Config\Services::session();



        // Modify the 'default' property

        // Connect to the database
        $dbName = \Config\Database::connect();

        if ($request->getPost()) {
            //Applib::is_demo(); 
            if ($dbName->table('hd_promotions')->where('id', $request->getPost('id'))->delete()) {
                return redirect()->to('promotions');
            }
            // App::delete('promotions',array('id' => $this->input->post('id', TRUE))); 
            // $this->session->set_flashdata('response_status', 'success');
            // $this->session->set_flashdata('message', lang('hd_lang.promotion_deleted_successfully'));
            // redirect($_SERVER['HTTP_REFERER']);  
        } else {
            $data['id'] = $id;
            // $this->load->view('modal/delete_promotion',$data);
            echo view('modules/promotions/modal/delete_promotion', $data);
        }
    }

    function validate_code()
    {
        $db = \Config\Database::connect();

        $request = \Config\Services::request();

        $promoCode = $request->getPost('promo_code');

        $promotion = $db->table('hd_promotions')->where('code', $promoCode)->get()->getRow();
		
		if (empty($promotion)) {
            return json_encode(
                array(
                    'msg' => 'No Promotion Found'
                )
            );
        }
		
		// Get current date
    	$currentDate = date('Y-m-d', time());
		
		$start = $promotion->start_date;
		$end = $promotion->end_date;
		
		if ($currentDate >= $start && $currentDate <= $end) {
			if($promotion->type == 1)
			{
				//Type: Amount
				$discountAmount = $promotion->value;
				$message = "Promo code applied! You get a {$discountAmount} amount discount.";

				return json_encode(
					array(
						'success'=> true,
						'msg' => $message,
						'disc_amount' => $discountAmount,
						'disc_type' => 'Amount',
						'promo_code' => $promoCode
					)
				);
			}
			elseif($promotion->type == 2)
			{
				//Type: Percentage
				$discountAmount = $promotion->value;
				$message = "Promo code applied! You get a {$discountAmount} % discount.";

				return json_encode(
					array(
						'success'=> true,
						'msg' => $message,
						'disc_percentage' => $discountAmount,
						'disc_type' => 'Percentage',
						'promo_code' => $promoCode
					)
				);
			}
		}
		else {
			$message = "Promo code is Expired";
			
			return json_encode(
					array(
						'success'=> false,
						'msg' => $message
					)
				);
		}
    }

    function update_promotion_code()
    {
        $db = \Config\Database::connect();

        $request = \Config\Services::request();

        $newTotal = $request->getPost('newTotal');
        $disc_type = $request->getPost('disc_type');
        $cart_id = $request->getPost('cart_id');
        $disc_percentage = $request->getPost('disc_percentage') ? $request->getPost('disc_percentage') : 0.00;

        if(!User::logged_in())
        {
            $cart = session()->get('cart');

            // foreach ($cart as $item) {
            //     $item->discount_price = $newTotal;
            //     $item->discount_type = $disc_type;
            //     $item->discount_percentage = $disc_percentage;
            //     break;
            // }
            $cart[0]->discount_price = 0.00;
            $cart[0]->discount_type = '';
            $cart[0]->discount_percentage = '';

            $cart[1]->discount_price = $newTotal;
            $cart[1]->discount_type = $disc_type;
            $cart[1]->discount_percentage = $disc_percentage;
			
			session()->set('cart', $cart);
        }
		else
		{
			
			$cart = session()->get('cart');

            // foreach ($cart as $item) {
            //     $item->discount_price = $newTotal;
            //     $item->discount_type = $disc_type;
            //     $item->discount_percentage = $disc_percentage;
            //     break;
            // }
            $cart[0]->discount_price = 0.00;
            $cart[0]->discount_type = '';
            $cart[0]->discount_percentage = 0;

            $cart[1]->discount_price = $newTotal;
            $cart[1]->discount_type = $disc_type;
            $cart[1]->discount_percentage = $disc_percentage;
			
			session()->set('cart', $cart);
		}


        $db->table('hd_carts')->where('cart_id', $cart_id)->update(['discount_price' => $newTotal, 'discount_type' => $disc_type, 'discount_percentage' => $disc_percentage]);
    }
}

/* End of file Servers.php */