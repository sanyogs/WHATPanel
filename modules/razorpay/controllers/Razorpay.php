<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */
/* Module Name: Razorpay
 * Module URI: https://whatpanel.com
 * Version: 1.0
 * Category: Payment Gateways
 * Description: Razorpay Payment Gateway.
 * Author:  WHAT PANEL 
 * Author URI: https://whatpanel.com
 */

namespace Modules\razorpay\controllers;

use App\ThirdParty\MX\WhatPanel;

use App\Models\Invoice;
use App\Models\App;
use App\Models\Client;
use App\Modules\invoices\controllers\Invoices;
use App\Libraries\AppLib;

use App\Modules\Layouts\Libraries\Template;

use App\Helpers\custom_name_helper;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;

class Razorpay extends WhatPanel
{

    private $api_key;

    function __construct()
    {
        // 	parent::__construct();		
        //     User::logged_in();

        //     $this->config = get_settings('razorpay');
        //     if(!empty($this->config))
        //     {
        //         $this->api_key = $this->config['api_key']; 
        //    }
    }


    public function razorpay_config($values = null, $config = null)
    {
        $config = array(
			'form_id' => 'razorpayForm',
			'fields' => array(
				array(
                    'id' => 'id',
                    'type' => 'hidden',
                    'value' => $config->plugin_id
                ),
                array(
                    'id' => 'system_name',
                    'type' => 'hidden',
                    'value' => $config->system_name
                ),
				array(
					'label' => lang('razorpay_api_key'),
					'id' => 'api_key',
					'value' => !empty($values['api_key']) ? $values['api_key'] : '',
					'type' => '',
					'placeholder' => 'Enter Razorpay API Key'
				),
				array(
					'label' => lang('razorpay_api_secret'),
					'id' => 'secret_key',
					'value' => !empty($values['secret_key']) ? $values['secret_key'] : '',
					'type' => '',
					'placeholder' => 'Enter Razorpay Secret Key'
				),
				array(
					'label' => lang('razorpay_mode'),
					'id' => 'razorpay_mode',
					'value' => !empty($values['razorpay_mode']) ? $values['razorpay_mode'] : '',
					'type' => 'radio',
					'radio_options' => array(
						array(
							'id' => 'live',
							'label' => 'Live',
							'value' => 'live',
							'class' => 'Live',
							'checked' => false, // Set to true if this option should be initially checked
						),
						array(
							'id' => 'test',
							'label' => 'Test',
							'value' => 'test',
							'class' => 'Test',
							'checked' => false,
						)
					)
				),
				array(
					'id' => 'submit',
					'type' => 'submit',
					'label' => 'Save',
					'class' => 'common-button'
                )
			)
		);

        return $config;
		//return $data;
    }
	
	public function razorpay_config_no_data($config = null)
    {
        $config = array(
			'form_id' => 'razorpayForm',
			'fields' => array(
				array(
                    'id' => 'id',
                    'type' => 'hidden',
                    'value' => $config->plugin_id
                ),
                array(
                    'id' => 'system_name',
                    'type' => 'hidden',
                    'value' => $config->system_name
                ),
				array(
					'label' => lang('razorpay_api_key'),
					'id' => 'api_key',
					'value' => '',
					'type' => '',
					'placeholder' => 'Enter Razorpay API Key'
				),
				array(
					'label' => lang('razorpay_api_secret'),
					'id' => 'secret_key',
					'value' => '',
					'type' => '',
					'placeholder' => 'Enter Razorpay Secret Key'
				),
				array(
					'label' => lang('razorpay_mode'),
					'id' => 'razorpay_mode',
					'value' => '',
					'type' => 'radio',
					'radio_options' => array(
						array(
							'id' => 'live',
							'label' => 'Live',
							'value' => 'live',
							'class' => 'Live',
							'checked' => false, // Set to true if this option should be initially checked
						),
						array(
							'id' => 'test',
							'label' => 'Test',
							'value' => 'test',
							'class' => 'Test',
							'checked' => false,
						)
					)
				)
			)
        );

        return $config;
		//return $data;
    }


    function index()
    {
        redirect('invoices');
    }


    function pay($invoice = NULL)
    {
        error_reporting(E_ALL);
        ini_set("display_errors", "1");

        $template = new Template();

        $helpers = new custom_name_helper();

        $inv = Invoice::view_by_id($invoice);
        $client_cur = 'INR';

        // echo Invoice::get_invoice_due_amount($invoice);die;

        // $invoice_due = Applib::client_currency($client_cur, Invoice::get_invoice_due_amount($invoice));

        $invoice_due = Invoice::get_invoice_due_amount($invoice);
		
		$invoice_tax_total = Invoice::get_invoice_tax_total($invoice);

        // echo $invoice_tax_total;die;

        $data['symbol'] = App::currencies($client_cur)->symbol;
        $data['currency'] = $client_cur;


        if ($invoice_due <= 0) {
            $invoice_due = 0.00;
        }


        $data['info'] = array(
            'allow_stripe'    => true,
            'item_name'        => $inv->reference_no,
            'item_number'     => $invoice,
            'currency'         => $inv->currency,
            'amount'        => $invoice_due,
			'tax_total'        => $invoice_tax_total
        );

        $template->title('Razorpay - ' . $helpers->getconfig_item('company_name'));

        $data['page'] = 'Razorpay';
        // $data['api_key'] = $this->api_key;
        // $this->template
        //     ->set_layout('users')
        //     ->build('form', isset($data) ? $data : NULL);

        // echo "<pre>";print_r($data);die;
        echo view('modules/razorpay/form', $data);
    }




    function processed_ipn()
    {
        $request = \Config\Services::request();

        $helpers = new custom_name_helper();

        $paid_amount = $request->getPost('paid');
        $invoice = $request->getPost('invoice');
        $txn_id = $request->getPost('payment_id');

        $invoice_due = Invoice::get_invoice_due_amount($invoice);
        $paid_amount = Applib::format_deci($invoice_due);
        $inv = Invoice::view_by_id($invoice);
        $client = Client::view_by_id($inv->client);

        helper('text');

        $data = array(
            'invoice' => $invoice,
            'paid_by' => $inv->client,
            'currency' => strtoupper($inv->currency),
            'payer_email' => $client->company_email,
            'payment_method' => '1',
            'notes' => 'Razorpay: ' . $txn_id,
            'amount' => $paid_amount,
            'trans_id' => random_string('nozero', 6),
            'month_paid' => date('m'),
            'year_paid' => date('Y'),
            'payment_date' => date('Y-m-d')
        );

        // Store the payment in the database.
        if ($payment_id = App::save_data('hd_payments', $data)) {
            $cur_i = App::currencies(strtoupper($inv->currency));
            $data = array(
                'module' => 'invoices',
                'module_field_id' => $invoice,
                'user' => $client->primary_contact,
                'activity' => 'activity_payment_of',
                'icon' => 'fa-usd',
                'value1' => $inv->currency . '' . $paid_amount,
                'value2' => $inv->reference_no
            );

            App::Log($data);

            //$this->_send_payment_email($invoice, $paid_amount); // Send email to client

            if (config_item('notify_payment_received') == 'TRUE') {
                //$this->_notify_admin($invoice, $paid_amount, $cur_i->code); // Send email to admin
            }

            $invoice_due = Invoice::get_invoice_due_amount($invoice);
            if ($invoice_due <= 0) {
                Invoice::update($invoice, array('status' => 'Paid'));
                modules::run('orders/process', $invoice);
            }
        }

        $result = array(
            'status' => 'success',
            'message' => lang('payment_added_successfully'),
            'invoice_id' => $invoice
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }




    function cancel()
    {
        $this->session->set_flashdata('response_status', 'error');
        $this->session->set_flashdata('message', 'Payfast Payment Cancelled!');
        redirect('clients');
    }


    function success($id = null)
    {
        $this->session->set_flashdata('response_status', 'success');
        $this->session->set_flashdata('message', lang('payment_added_successfully'));
        redirect('invoices/view/' . $id);
    }


    public function activate($data)
    {
        return true;
    }


    public function install()
    {
        return true;
    }


    public function uninstall()
    {
        return true;
    }

    public function process_payment()
    {
        error_reporting(E_ALL);
        ini_set("display_errors", "1");

        $session = \Config\Services::session();

        $db = \Config\Database::connect();

        $request = \Config\Services::request();

        $result = $db->table('hd_plugins')->select('config')->where('system_name', 'razorpay')->get()->getRow()->config;

        $invoice_details = $db->table('hd_invoices')->where('inv_id', $request->getPost('invoice_id'))->get()->getRow();

        $company_details = $db->table('hd_companies')->where('co_id', $invoice_details->client)->get()->getRow();

        $user_details = $db->table('hd_users')->where('id', $company_details->primary_contact)->get()->getRow();

        $razorpay_details = json_decode($result);
		
		// echo "<pre>";print_r($razorpay_details);die;

        $authAPIkey = base64_encode($razorpay_details->api_key . ":" . $razorpay_details->secret_key);
		
		// echo $authAPIkey;die;

        // Set transaction details
        $order_id = uniqid();

        $note = "Payment of amount Rs. " . $request->getPost('amount');

        $postdata = array(
            "amount" => $request->getPost('amount') * 100,
            "currency" => "INR",
            //"currency" => $request->getPost('currency'),
            "receipt" => $note,
            "notes" => array(
                "notes_key_1" => $note
            ),
            // 'payment_capture' => 1
        );
		
		// echo "<pre>";print_r($postdata);die;

        // $curl = curl_init();

        // curl_setopt_array($curl, array(
        //     CURLOPT_URL => 'https://api.razorpay.com/v1/orders',
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_ENCODING => '',
        //     CURLOPT_MAXREDIRS => 10,
        //     CURLOPT_TIMEOUT => 0,
        //     CURLOPT_FOLLOWLOCATION => true,
        //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //     CURLOPT_CUSTOMREQUEST => 'POST',
        //     CURLOPT_POSTFIELDS => json_encode($postdata),
        //     CURLOPT_HTTPHEADER => array(
        //         'Content-Type: application/json',
        //         'Authorization: ' . $authAPIkey
        //     ),
        // ));

        // $response = curl_exec($curl);

        // curl_close($curl);
        // $orderRes = json_decode($response);

        $client = new GuzzleClient();

        try {
			
            $response = $client->post('https://api.razorpay.com/v1/orders', [
                'json' => $postdata,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Basic ' . $authAPIkey
                ]
            ]);
			
			//echo "<pre>";print_r($response);die;

            $orderRes = json_decode($response->getBody(), true);
			
			//  echo "<pre>";print_r($orderRes);die;

            if (isset($orderRes['id'])) {

                $rpay_order_id = $orderRes['id'];

                $dataArr = array(
                    'amount' => $request->getPost('amount'),
                    'description' => "Pay bill of Rs. " . $request->getPost('amount'),
                    'rpay_order_id' => $rpay_order_id,
                    'name' => $company_details->first_name . ' ' . $company_details->last_name,
                    'email' => $company_details->company_email,
                    'mobile' => $company_details->company_phone
                );

                echo json_encode(['res' => 'success', 'order_number' => $order_id, 'razorpay_order_id' => $rpay_order_id, 'invoice_id' => $request->getPost('invoice_id'), 'userData' => $dataArr, 'razorpay_key' => $razorpay_details->api_key, 'amount' => $request->getPost('amount')]);
            } else {
                echo json_encode(['res' => 'error', 'order_id' => $order_id, 'info' => 'Error with payment']);
                exit;
            }

            // Further processing based on $orderRes
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $errorResponse = $e->getResponse();
                $errorBody = json_decode($errorResponse->getBody(), true);
                // Log or handle the error accordingly
                return $errorBody;
            }
            // Handle other errors (network issues, etc.)
            return ['error' => $e->getMessage()];
        }
    }

    public function verify_payment()
    {
        error_reporting(E_ALL);
        ini_set("display_errors", "1");
        $session = \Config\Services::session();

        $db = \Config\Database::connect();

        $request = \Config\Services::request();

        $helper = new custom_name_helper();

        $invoice_details = $db->table('hd_invoices')->where('inv_id', $request->getPost('invoice_id'))->get()->getRow();

        $company_details = $db->table('hd_companies')->where('co_id', $invoice_details->client)->get()->getRow();

        $user_details = $db->table('hd_users')->where('id', $company_details->primary_contact)->get()->getRow();

        $payment_data = array(
            'invoice' => $request->getPost('invoice_id'),
            'paid_by' => $invoice_details->client,
            'currency' => $invoice_details->currency,
            'amount' => $request->getPost('amount'),
            'razorpay_order_id' => $request->getPost('razorpay_order_id'),
            'razorpay_payment_id' => $request->getPost('payment_id'),
            'payment_date' => date('Y-m-d H:i:s', time())
        );

        $db->table('hd_payments')->insert($payment_data);

        $order_details = $db->table('hd_orders')->where('invoice_id', $request->getPost('invoice_id'))->get()->getRow();
        //print_r($order_details);die;
        if ($order_details->type == "domain") {
            if ($order_details->status_id == 9) {
                $results = $helper->auto_unsuspend($request->getPost('invoice_id'));
            } else {
                //echo 67;die;
                $result = $helper->auto_activate($request->getPost('invoice_id'));
            }
        }

        $db->table('hd_invoices')->where('inv_id', $request->getPost('invoice_id'))->update(['notes' => json_encode($request->getPost()), 'status' => 'Paid']);

        //echo "reached till register in razorpay api but in verify_payment()";die;

        if ($order_details->type == "domain_only") {
            //echo 89;die;	
            $controller = new Invoices();
            // $register_domain = $controller->check_customer($order_details->type);
        }
        echo json_encode(array('success' => true, 'result' => 'done', 'url' => base_url('invoices/view/' . $request->getPost('invoice_id'))));
    }
}
