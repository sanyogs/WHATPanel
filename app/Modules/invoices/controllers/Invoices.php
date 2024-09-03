<?php
 /*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

namespace App\Modules\invoices\controllers;
//this is ketan comment

use App\Controllers\APIController;
use App\Helpers\app_helper;
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
use App\Modules\Layouts\Libraries\Template;
use DateInterval;
use DateTime;
use App\Helpers\AuthHelper;
use App\Helpers\whatpanel_helper;
use App\Helpers\custom_name_helper;
use App\Libraries\Tank_auth;
use App\Modules\accounts\controllers\Accounts;
use Modules\cyberpanel\controllers\Cyberpanel;
use Modules\bitcoin\controllers\Bitcoin;
use Modules\cwp\controllers\CWP;
use Modules\cpanel\controllers\Cpanel;
use Modules\cpanel\controllers\new_Cpanel;
use Modules\coinpayments\controllers\Coinpayments;
use Modules\checkout\controllers\Checkout;
use Modules\directadmin\controllers\Directadmin;
use Modules\ispconfig\controllers\Ispconfig;
use Modules\plesk\controllers\Plesk;
use Modules\plesk\controllers\Plesk_WHMCS;
use Modules\whoisxmlapi\controllers\Whoisxmlapi;
use Modules\razorpay\controllers\Razorpay;
use Modules\stripepay\controllers\Stripepay;
use Modules\paypal\controllers\Paypal;
use Modules\payfast\controllers\Payfast;
use App\Modules\cart\controllers\Cart;
use CodeIgniter\View\Exceptions\ViewException;
use App\Modules\fomailer\controllers\Fomailer;
use App\Modules\fopdf\controllers\Fopdf;
use DOMDocument;

class Invoices extends WhatPanel
{
    protected $filter_by;

    public function __construct()
    {
        error_reporting(E_ALL);
        ini_set("display_errors", "1");
        $helper = new custom_name_helper();
        $helper->settimezone();

        // parent::__construct();
        // User::logged_in();

        // $this->load->module('layouts');
        // $this->load->helper('string');
        // $this->load->library(array('template', 'form_validation'));
        // $this->template->title(lang('hd_lang.invoices').' - '.$custom_name_helper->getconfig_item('company_name'));

        // if (isset($_GET['login'])) {
        //     $this->tank_auth->remote_login($_GET['login']);
        // }

        // App::module_access('menu_invoices');

        $this->filter_by = $this->_filter_by();

        // $this->applib->set_locale();

        $session = \Config\Services::session();
        // Connect to the database
        $dbName = \Config\Database::connect();
        $this->userModel = new User($dbName);
    }


    public function index($type = null)
    {
        //echo 1;die;
        error_reporting(E_ALL);
        ini_set("display_errors", "1");
        $template = new Template();
		
		$request = \Config\Services::request();

        $custom_name_helper = new custom_name_helper();
		
		$session = \Config\Services::session();

        $template->title(lang('hd_lang.invoices') . ' - ' . $custom_name_helper->getconfig_item('company_name'));

        $data['page'] = lang('hd_lang.invoices');
        $data['datatables'] = true;
		$inv = new Invoice();
       // $data['invoices'] = $this->_show_invoices();
		
        //echo"<pre>";print_r($data['invoices']);die;
        //echo $data['invoice'][0]->status;die;
        //$result = $data['invoice']->0->status;
        //print_r($result);die;
        //     $this->template
        // ->set_layout('users')
        // ->build('invoices', isset($data) ? $data : null);
		// Pagination Configuration
		$page = $request->getGet('page') ? $request->getGet('page') : 1;

        $perPage = $request->getGet('recordsPerPage', FILTER_SANITIZE_NUMBER_INT) ? $request->getGet('recordsPerPage', FILTER_SANITIZE_NUMBER_INT) : 10;

		// Search Filter
		$search = $request->getGet('search');
		$data['search'] = $search;

        // $query = $inv->listItems([], $search, $perPage, $page);
		if(User::is_admin()){
			// $data['accounts'] = Domain::by_where($array, $type);
			$query = $inv->listItems([], $search, $perPage, $page);
		}else{
			$user_id = $session->get('userdata')['user_id'];
			$array['client'] = User::profile_info($user_id)->company;
			// $data['accounts'] = Domain::by_where($array, $type);
			$query = $inv->listItems($array, $search, $perPage, $page);
		}

        // Get items for the current page
		$data['invoices'] = array_map(function($item) {
			return (object) $item;
		}, $query['items']);

        $data['pager'] = $query['pager'];

		$data['message'] = $query['message'];

		$data['perPage'] = $perPage;
		
        return view('modules/invoices/invoices', $data);
    }


    public function view($invoice_id = null)
    {
        //if (!User::can_view_invoice(User::get_id(), $invoice_id)) {
        // App::access_denied('invoices');
        //}

        $inv = new Invoice();

        $custom_name_helper = new custom_name_helper();

        //$this->template->title(lang('hd_lang.invoices').' - '.$custom_name_helper->getconfig_item('company_name'));
        $data['page'] = lang('hd_lang.invoice');
        $data['stripe'] = true;
        $data['twocheckout'] = true;
        $data['sortable'] = true;
        $data['typeahead'] = true;
        $data['invoices'] = $this->_show_invoices(); // GET a list of the Invoices
        $data['id'] = $invoice_id;
        $inv->evaluate_invoice($invoice_id);
        //print_r($data);die();
        echo view('modules/invoices/view', $data);
    }


    public function autoitems()
    {	
		$db = \Config\Database::connect();
        $query = 'SELECT * FROM (
		SELECT item_name FROM hd_items
		UNION ALL
		SELECT item_name FROM hd_items_saved
        ) a
        GROUP BY item_name
        ORDER BY item_name ASC';
        $names = $db->query($query)->result();
        $name = array();
        foreach ($names as $n) {
            $name[] = $n->item_name;
        }
        $data['json'] = $name;
        //$this->load->view('json', isset($data) ? $data : null);
		echo view('modules/invoices/json', $data);
    }


    public function autoitem()
    {
        $name = $_POST['name'];
        $query = "SELECT * FROM (
		SELECT item_name, item_desc, quantity, unit_cost FROM hd_items
		UNION ALL
		SELECT item_name, item_desc, quantity, unit_cost FROM hd_items_saved
        ) a
        WHERE a.item_name = '" . $name . "'";
        $names = $this->db->query($query)->result();

        $name = $names[0];
        $data['json'] = $name;
        $this->load->view('json', isset($data) ? $data : null);
    }



    public function add()
    {
        $request = \Config\Services::request();

        $custom_name_helper = new custom_name_helper();

        if ($request->getPost()) {
            $validation = \Config\Services::validation();

            // Set the validation rules
            $validation->setRules([
                'reference_no' => 'required',
                'client' => 'required'
            ]);
            if (!$validation->withRequest($this->request)->run()) {
                return redirect()->to('invoices/add');
            } else {
                if ($custom_name_helper->getconfig_item('increment_invoice_number') == 'TRUE') {
                    $_POST['reference_no'] = $custom_name_helper->getconfig_item('invoice_prefix') . Invoice::generate_invoice_number();
                }

                $_POST['currency'] = $custom_name_helper->getconfig_item('default_currency');

                //$_POST['due_date'] = AppLib::date_formatter($_POST['due_date']);
                unset($_POST['files']);

                // Get the POST data
                $postData = $request->getPost();

                // Unset the 'files' key
                unset($postData['files']);

                if ($invoice_id = App::save_data('hd_invoices', $postData)) {

                    $activity = array(
                        'user' => User::get_id(),
                        'module' => 'invoices',
                        'module_field_id' => $invoice_id,
                        'activity' => 'activity_invoice_created',
                        'icon' => 'fa-plus',
                        'value1' => $_POST['reference_no'],
                    );

                    App::Log($activity);
                    // Applib::go_to('invoices/view/'.$invoice_id, 'success', lang('hd_lang.invoice_created_successfully'));
                    return redirect()->to('invoices/view/' . $invoice_id);
                }
            }
        } else {
            $data['page'] = lang('hd_lang.create_invoice');
            $data['editor'] = true;
            $data['datepicker'] = true;
            $data['form'] = true;
            $data['invoices'] = $this->_show_invoices();

            // $this->template
            // ->set_layout('users')
            // ->build('create_invoice', isset($data) ? $data : null);
            return view('modules/invoices/create_invoice', $data);
        }
    }


	    public function create($id = null)
		{
			try {
				$session = \Config\Services::session();
				$custom_name_helper = new custom_name_helper();
				$dbName = \Config\Database::connect();
				
				$model = new Client($dbName);

				if (session()->get('process') && !empty(session()->get('order')->order)) {

					if ($id && $id != 0) {
						$co_id = $id;
					} else {
						$client = new Client();
						$user_id = $session->get('userid');
						$client = $client->get_by_user($user_id);
						$co_id = $client->co_id;
					}
					if(session()->get('co_id')) {
						$co_id = session()->get('co_id');
					}

					$client = Client::view_by_id($co_id);
					$currency = $custom_name_helper->getconfig_item('default_currency');
					$reference = $custom_name_helper->getconfig_item('invoice_prefix') . Invoice::generate_invoice_number();

					$whatpanel_helper = new whatpanel_helper();

					$interval = $whatpanel_helper->intervals();

					$intervals_days = $whatpanel_helper->intervals_days();

					$renewals = array();
					foreach ($interval as $k => $int) {
						$renewals[] = $k;
					}

					if (null == $co_id) {
						session()->setFlashdata('response_status', 'warning');
						session()->setFlashdata('message', lang('hd_lang.primary_contact_required'));
						redirect($_SERVER['HTTP_REFERER']);
					}

					$data = array(
						'reference_no' => $reference,
						'currency' => (isset($currency)) ? $currency : 'USD',
						'due_date' => $this->get_date_due(date('Y-m-d')),
						'client' => $co_id,
						'tax' => ($custom_name_helper->getconfig_item('order_tax') == 'TRUE') ? $custom_name_helper->getconfig_item('default_tax') : '0.00',
						'notes' => $custom_name_helper->getconfig_item('default_terms'),
						'date_saved' => date('Y-m-d H:i:s')
					);

					$model = new App();
					if ($invoice_id = $model->save_data('hd_invoices', $data)) {

						$count = 1;
						$order_id = time();
						$type = "service";
						$username = "";
						$password = "";

						$date = date('Y-m-d');

						$order = session()->get('order')->order;
						//echo"<pre>";print_r(session()->get());die;
						$new_cust = session()->get('new_cust');

						if (count($new_cust) > 0) {
							$orders = $dbName->table('hd_orders')->where('client_id', $co_id)->get()->getResult();

							if (count($orders) > 0) {
								foreach ($new_cust as $new) {
									foreach ($order as $key => $item) {
										if ($new == $item->cart_id) {
											unset($order[$key]);
										}
									}
								}
							}
						}

						$i = null;

						//echo "<pre>";print_r($order);die;    
						foreach ($order as $item) {
							if (isset($item->domain_only) && $item->domain_only == 1) {
								$domain_contains = explode(".", $item->domain);
								$i = Item::view_item_domain_tlds($domain_contains[1]);
							} else {
								// echo 12;die;
								$i = Item::view_item($item->item);
								if (empty($i)) {
									// echo 11;die;
									$i = Item::view_item_domain($item->item);
								}
							}

							//echo "<pre>";print_r($order);die;

							if (property_exists($item, 'discount_price')) {
								if (!empty($item->discount_price > 0.00)) {

									//$dbName->table('hd_invoices')->where('inv_id', $invoice_id)->update(['discount' => $item->discount_price,                                       'discount_type' => $item->discount_type, 'discount_percentage' => $item->discount_percentage]);
									
									$dbName->table('hd_invoices')->where('inv_id', $invoice_id)->update(['discount' => $item->discount_percentage,                                       'discount_type' => $item->discount_type, 'discount_percentage' => $item->discount_percentage]);
								}
							}
							//$registration = 
							//echo"<pre>";print_r($intervals_days[$item->year]);die;
							if (
								$item->renewal != 'once_off'
								&& $item->renewal != 'one_time_payment'
								&& $item->renewal != 'total_cost'
							) {
								$days = $interval[$item->renewal];
								if ((isset($item->nameservers) && $item->nameservers !== '') || isset($item->domain_only)) {
									//$years = $item->price / $i->registration;
									//$days = $years * 365;
									if (isset($item->year)) {

										$days = $intervals_days[$item->year];
										//echo $days;die;
										$renewal_date = Invoice::add_days_to_date($date, $days);
									} else {
										$days = $interval[$item->renewal];

										$renewal_date = Invoice::add_days_to_date($date, $days);
									}
								} else {
									$years = 1;
									$renewal_date = Invoice::add_days_to_date($date, $days);
								}
							}

							$description = lang('hd_lang.one_time_payment');

							if (in_array($item->renewal, $renewals)) {
								$description = "[" . $item->domain . "] " . $date . " - " . $renewal_date;
							}

							if (isset($i) && $i->parent == 10 && $item->renewal == 'total_cost') {
								$description = lang($item->renewal);
							}

							if ($item->domain == 'promo') {
								$description = $item->item;
							}

							//echo "<pre>";print_r($item);die;
							$total_cost = $item->price;
							$tax_rate = 0;
							//echo"<pre>";print_r($i);die;
							if ($item->tax > 0) {
								$total_cost = $item->price;
								//$tax_rate = isset($i->item_tax_rate) ? $i->item_tax_rate : $i->tax_rate;
								$tax_rate = isset($i) && isset($i->item_tax_rate)? $i->item_tax_rate : 0; // or some default value
							}

							$items = array(
								'invoice_id'     => $invoice_id,
								'item_name'        => $item->name,
								'item_desc'        => $description,
								'unit_cost'        => $item->price,
								'item_order'    => 1,
								'item_tax_rate'    => $tax_rate,
								'item_tax_total' => $item->tax,
								'quantity'        => 1,
								'total_cost'    => $total_cost
							);
							// echo"<pre>";print_r($i);die;
							$model = new App();
							if ($item_id = $model->save_data('hd_items', $items)) {

								// echo "<pre>";var_dump($i);die;

								//if ($i->addon == 0  || isset($item->parent) || isset($item->nameservers) || isset($item->domain_only)) {
								if (isset($item->parent) || isset($item->nameservers) || isset($item->domain_only)) {

									if (
										$item->renewal != 'once_off'

										&& $item->renewal != 'one_time_payment'
									) {

										$username = '-';
										if (isset($i)) {

											$type = "hosting";
											$username = str_replace(".", "", $item->domain);

											if (strlen($username) > 8) {
												$username = substr($username, 0, 8);
											}

											$session = \Config\Services::session();

											// Connect to the database
											$dbName = \Config\Database::connect();

											$builder = $dbName->table('hd_config');

											$account = $dbName->table('hd_orders')->where('username', $username)->get()->getRow();
											// echo $dbName->getLastQuery(); die;
											// print_r($account);die;

											if (isset($account)) {
												$end = 0;
												$session = \Config\Services::session();
												// Connect to the database
												$dbName = \Config\Database::connect();

												$builder = $dbName->table('hd_config');
												while (0 < $dbName->table('hd_orders')->where('username', $username)->countAllResults()) {
													$end++;
													$trimlength = 8 - strlen($end);
													$username = substr($username, 0, $trimlength) . $end;
												}
											}

											$whatpanel_helper = new whatpanel_helper();

											$password = $whatpanel_helper->create_password();
										}
										if (isset($item->nameservers)) {
											$type = "domain";
										}

										if (isset($item->domain_only) && $item->domain_only == 1) {
											$type = "domain_only";
										}

										$discounts = session()->get('discounted');
										//print_r($discounts);die;
										if (isset($item->item)) {

											$discounted = false;

											foreach ($discounts as $key => $discount) {
												if ($discount['item'] == $item->item) {
													$total_cost = $total_cost - $discount['amount'];

											$promotion = $dbName->table('hd_promotions')->where('code', $discount['code'])->get()->getRow();
													if ($promotion->payment == 2) {
														$discounted = true;
													}
												}
											}
										}

										//echo"<pre>";print_r($item);die;
										$order = array(
											'client_id'         => $co_id,
											'invoice_id'        => $invoice_id,
											'date'              => date('Y-m-d H:i:s'),
											'nameservers'        => (isset($item->nameservers)) ? $item->nameservers : "",
											'item'                => $item_id,
											//'domain'            => $item->domain ? $item->authcode : '',
											'domain'            => $item->domain,
											'registrar'         => (isset($i->default_registrar)) ? $i->default_registrar : 0,
											'item_parent'       => (isset($item->item)) ? $item->item : 0,
											'type'                => $type,
											'process_id'        => (in_array($item->renewal, $renewals)) ? time() + $count : $order_id,
											'order_id'          => $order_id,
											'fee'               => $total_cost,
											'processed'         => $date,
											// 'years'             => $years,
											'username'          => $username,
											'password'          => ($username != '-') ? $password : '-',
											'renewal_date'      => ($item->renewal == 'total_cost') ? date('Y-m-d') : $renewal_date,
											'renewal'           => $item->renewal,
											'additional_fields' => $item->cart_id,
											'authcode'          => (isset($item->authcode)) ? $item->authcode : '',
											//'promo'             => ($discounted) ? 1 : 0,
											'parent'            => (isset($item->parent)) ? $item->parent : 0,
											'server'            => (isset($i->server)) ? $i->server : 0
										);

										$inv_id = array(
											'inv_id'        => $invoice_id
										);
										$query = $dbName->table('hd_carts')->where('domain', $item->domain)->update($inv_id);

										$c = (isset($i->create_account)) ? $i->create_account : "No";
										// echo"<pre>";print_r($item);die;
										if ($item->renewal == 'total_cost' || $c == 'No') {
											//echo 789;die;
											$order['status_id'] = 2;
										}
										$order_id = $dbName->table('hd_orders')->insert($order);
										//print_r($order_id);die;
										// $model = new App();
										// echo "<pre>";print_r($order);die;
										//$order_id = $model->save_data('hd_orders', $order);
										// $order_id = $dbName->table('hd_orders')->insert($order);
										$username = "";
										$password = "";
										$count++;
									}
								}
							}
						}
					}

				}

				session()->remove('cart');
				session()->remove('order');
				session()->remove('process');
				session()->remove('co_id');
				session()->remove('codes');
				session()->remove('new_cust');
				session()->remove('discounted');

				$activity = array(
					'user' => User::get_id(),
					'module' => 'orders',
					'module_field_id' => $invoice_id,
					'activity' => 'activity_order_created',
					'icon' => 'fa-plus',
					'value1' => $reference,
				);

				$invoice_due = Invoice::get_invoice_due_amount($invoice_id);
				$inv = Invoice::view_by_id($invoice_id);
				helper('text');

				$request = \Config\Services::request();
				if ($invoice_due <= 0) {
					$data = array(
						'invoice' => $invoice_id,
						'paid_by' => Invoice::view_by_id($invoice_id)->client,
						'payment_method' => 1,
						'currency' => $request->getPost('currency'),
						'amount' => 0,
						'payment_date' => Applib::date_formatter(date('Y-m-d')),
						'trans_id' => random_string('nozero', 6),
						'notes' => $item->name,
						'month_paid' => date('m'),
						'year_paid' => date('Y'),
					);

					if ($payment_id = Payment::save_pay($data)) {
						$model = new Invoice();
						$model->update($invoice_id, array('status' => 'Paid'));
						Modules::run('orders/process', $invoice_id);
					}
				} else {

					if ($custom_name_helper->getconfig_item('apply_credit') == 'TRUE' && $client->transaction_value > 0) {
						$client->transaction_value = -1 * $client->transaction_value;
						$payment = 0;
						$balance = $client->transaction_value + $invoice_due;

						if (filter_var($balance, FILTER_VALIDATE_FLOAT) && $balance > 0) {
							$payment = abs($client->transaction_value);
							$funds = 0;
						}

						if (filter_var($balance, FILTER_VALIDATE_FLOAT) && $balance <= 0) {
							$payment = $invoice_due;
							$funds = abs($balance);
						}


						$data = array(
							'invoice' => $invoice_id,
							'paid_by' => $co_id,
							'currency' => strtoupper($inv->currency),
							'payer_email' => $client->company_email,
							'payment_method' => '6',
							'notes' => $payment . strtoupper($inv->currency) . 'Deducted from Account Funds',
							'amount' => $payment,
							'trans_id' => random_string('nozero', 6),
							'month_paid' => date('m'),
							'year_paid' => date('Y'),
							'payment_date' => date('Y-m-d')
						);

						if ($payment_id = App::save_data('payments', $data)) {
							$cur_i = App::currencies(strtoupper($inv->currency));
							$data = array(
								'module' => 'invoices',
								'module_field_id' => $invoice_id,
								'user' => $client->primary_contact,
								'activity' => 'activity_payment_of',
								'icon' => 'fa-usd',
								'value1' => $inv->currency . '' . $payment,
								'value2' => $inv->reference_no
							);

							App::Log($data);
						}

						$balance = array(
							'transaction_value' => $funds
						);
						$db = \Config\Database::connect();
						$db->table('hd_companies')->where('co_id', $client->co_id)->update($balance);
						$invoice_due = Invoice::get_invoice_due_amount($invoice_id);
						if ($invoice_due <= 0) 
						{
							$model = new Invoice();
							$model->update($invoice_id, array('status' => 'Paid'));
							modules::run('orders/process', $invoice_id);
						}
					}
				}

				$this->send_invoice($invoice_id);  
				return redirect()->to('invoices/view/' . $invoice_id);
			} catch (ViewException $e) {
				// Handle the ViewException
				$errorMessage = $e->getMessage();
				// Log the error or display it to the user
				session()->setFlashdata('response_status', 'error');
				session()->setFlashdata('message', $errorMessage);
				return redirect()->back(); // Redirect back to the previous page
			}
		}




    public function upgrade()
    {
        $custom_name_helper = new custom_name_helper();

        if ($this->input->post() && $this->input->post('upgrade') == "true") {

            $data = $this->session->userdata('upgrade');

            $this->db->select('*');
            $this->db->from('orders');
            $this->db->where('id', $data['account']);
            $order = $this->db->get()->row();
            $current_item = Item::view_item($order->item_parent);

            $co_id = $order->client_id;
            $item = Item::view_item($data['item']);
            $currency = $custom_name_helper->getconfig_item('default_currency');
            $reference = $custom_name_helper->getconfig_item('invoice_prefix') . Invoice::generate_invoice_number();

            $invoice = array(
                'reference_no' => $reference,
                'currency' =>  $currency,
                'due_date' => $this->get_date_due(date('Y-m-d')),
                'client' => $co_id
            );

            $item_tax_total = 0;
            $total_cost = $data['payable'];
            $tax_rate = 0;

            if ($item->item_tax_rate && $item->item_tax_rate > 0) {
                $item_tax_total = Applib::format_deci(($item->item_tax_rate / 100) *  $data['payable']);
                $total_cost =  Applib::format_deci($data['payable'] + $item_tax_total);
                $tax_rate = $item->item_tax_rate;
            }

            if ($invoice_id = App::save_data('invoices', $invoice)) {

                $items = array(
                    'invoice_id'     => $invoice_id,
                    'item_name'        => lang('hd_lang.upgrade_downgrade'),
                    'item_desc'        => $current_item->item_name . " - " . $item->item_name,
                    'unit_cost'        => $data['payable'],
                    'item_order'    => 1,
                    'item_tax_rate'    => $tax_rate,
                    'item_tax_total' => $item_tax_total,
                    'quantity'        => 1,
                    'total_cost'    => $total_cost
                );

                if ($item_id = App::save_data('items', $items)) {
                    $order = (array) $order;

                    $order['invoice_id'] = $invoice_id;
                    $order['item'] = $item_id;
                    $order['item_parent']  = $data['item'];
                    $order['renewal_date'] = $data['renewal_date'];
                    $order['renewal'] = $data['renewal'];
                    $order['fee'] = $data['amount'];
                    $order['order_id'] = time();
                    $order['o_id'] = $order['id'];
                    $order['date'] = date('Y-m-d H:i:s');

                    unset($order['id']);
                    unset($order['status_id']);

                    App::save_data('orders', $order);
                }


                $invoice_due = Invoice::get_invoice_due_amount($invoice_id);
                if ($invoice_due <= 0) {
                    modules::run('orders/process', $invoice_id);
                }
            }


            $this->session->unset_userdata('item_id');
            $this->session->unset_userdata('account_id');
            $this->session->unset_userdata('upgrade');

            $activity = array(
                'user' => User::get_id(),
                'module' => 'orders',
                'module_field_id' => $invoice_id,
                'activity' => 'activity_upgrade',
                'icon' => 'fa-plus',
                'value1' => $reference,
            );

            App::Log($activity);

            redirect('invoices/view/' . $invoice_id);
        }
    }




    public function edit($invoice_id = null)
    {
        $invoiceModel = new Invoice();
		$request = \Config\Services::request();

        if (User::is_admin() || User::perm_allowed(User::get_id(), 'edit_all_invoices')) {          

            if ($request->getPost()) { 
                $invoice_id = $request->getPost('inv_id');

                $validation = \Config\Services::validation();

                // Set the validation rules
                $validation->setRules([
                    'client' => 'required'
                ]);
                if (!$validation->withRequest($this->request)->run()) {
                    return redirect()->to('invoices/edit');
                } else { 

                    // $date = new DateTime($_POST['date_saved']);
                    //$_POST['date_saved'] = Applib::date_formatter($_POST['date_saved']).' 00:00:00';

                    unset($_POST['files']);
                    // Get the POST data
                    $postData = $request->getPost();
					$postData['discount_percentage'] = $request->getPost('discount');

                    // Unset the 'files' key
                    unset($postData['files']);
                    if ($invoiceModel->update($invoice_id, $postData)) { 
                        if ($request->getPost('r_freq') != 'none') { 
                            Invoice::recur($invoice_id, $request->getPost());
                        }
                        // Log Activity
                        $activity = array(
                            'user' => User::get_id(),
                            'module' => 'invoices',
                            'module_field_id' => $invoice_id,
                            'activity' => 'activity_invoice_edited',
                            'icon' => 'fa-pencil',
                            'value1' => $_POST['reference_no'],
                        );
                        App::Log($activity); // Log activity
                        //print_r($activity['value1']);die;
                        return redirect()->to('invoices/view/' . $invoice_id);

                        // Applib::go_to('invoices/view/'.$invoice_id, 'success', lang('hd_lang.invoice_edited_successfully'));
                    }
                }
            } else {
                $payment = new Payment();
                $data['page'] = lang('hd_lang.edit_invoice');
                $data['datepicker'] = true;
                $data['form'] = true;
                $data['editor'] = true;
                $data['clients'] = Client::get_all_clients();
                $data['invoices'] = $this->_show_invoices();
                $data['currencies'] = App::currencies();
                $data['id'] = $invoice_id;
                $data['payments'] = $payment->payment_mode($invoice_id);
                return view('modules/invoices/edit_invoice', $data);
            }
        } else {
            App::access_denied('invoices');
        }
    }


    public function _show_invoices()
    {	
        $session = \Config\Services::session();
        //if (AuthHelper::is_admin() || User::perm_allowed(User::get_id(), 'view_all_invoices')) {
        $userdata = $session->get('userdata');
        if ($userdata['role_id'] == 1) {
            return $this->all_invoices($this->filter_by);
        } else {
            //print_r($this->userModel::profile_info($this->userModel::get_id_client()));die;
            return $this->client_invoices($this->userModel::profile_info($this->userModel::get_id_client()), $this->filter_by);
        }
    }

    public function all_invoices($filter_by = null)
    {
        switch ($filter_by) {
            case 'paid':
                return Invoice::paid_invoices();
                break;

            case 'unpaid':
                return Invoice::unpaid_invoices();
                break;

            case 'partially_paid':
                return Invoice::partially_paid_invoices();
                break;

            case 'recurring':
                return Invoice::recurring_invoices();
                break;

            default:
                return Invoice::get_invoices();
                break;
        }
    }

    public function client_invoices($company, $filter_by)
    {
        switch ($filter_by) {

            case 'paid':
                return Invoice::paid_invoices($company->co_id);
                break;

            case 'unpaid':
                return Invoice::unpaid_invoices($company->co_id);
                break;

            case 'partially_paid':
                return Invoice::partially_paid_invoices($company->co_id);
                break;

            case 'recurring':
                return Invoice::recurring_invoices($company->co_id);
                break;

            default:
                return Invoice::get_client_invoices($company->co_id);
                break;
        }
    }



    public function apply_credit($invoice_id = null)
    {
        $custom_name_helper = new custom_name_helper();

        if ($this->input->post()) {
            $invoice_id = $this->input->post('invoice');
            $client = Client::view_by_id(Invoice::view_by_id($invoice_id)->client);
            $credit = $client->transaction_value;
            $inv = Invoice::view_by_id($invoice_id);
            $cur = $custom_name_helper->getconfig_item('default_currency');

            $due = Invoice::get_invoice_due_amount($invoice_id);
            if ($credit > $due) {
                $bal = $credit - $due;
                $paid_amount = $due;
            } else {
                $bal = 0;
                $paid_amount = $credit;
            }


            $data = array(
                'invoice' => $invoice_id,
                'paid_by' => Invoice::view_by_id($invoice_id)->client,
                'payment_method' => 6,
                'currency' => $cur,
                'amount' => $paid_amount,
                'payment_date' => strftime($custom_name_helper->getconfig_item('date_format'), time()),
                'trans_id' => random_string('nozero', 6),
                'notes' => lang('hd_lang.credit_balance') . ' = ' . $bal,
                'month_paid' => date('m'),
                'year_paid' => date('Y'),
            );


            if ($payment_id = App::save_data('payments', $data)) {

                $data = array(
                    'module' => 'invoices',
                    'module_field_id' => $invoice_id,
                    'user' => $client->primary_contact,
                    'activity' => 'activity_payment_of',
                    'icon' => 'fa-usd',
                    'value1' => $paid_amount . ' ' . $cur,
                    'value2' => $inv->reference_no
                );

                App::Log($data);

                $invoice_due = Invoice::get_invoice_due_amount($invoice_id);
                if ($invoice_due <= 0) {
                    Invoice::update($invoice_id, array('status' => 'Paid'));
                    modules::run('orders/process', $invoice_id);
                }

                send_payment_email($invoice_id, $paid_amount); // Send email to client               
                notify_admin($trans->invoice, $paid, $cur); // Send email to admin 

                $balance = array(
                    'transaction_value' => Applib::format_deci($bal)
                );

                App::update('companies', array('co_id' => $client->co_id), $balance);
                Applib::go_to('invoices/view/' . $invoice_id, 'success', lang('hd_lang.payment_added_successfully'));
            }
        } else {
            $data['invoice'] = $invoice_id;
            $this->load->view('modal/apply_credit', $data);
        }
    }



    public function pay($invoice = null)
    {
        // if (!User::can_pay_invoice()) {
        //     App::access_denied('invoices');
        // }  

        $db = \Config\Database::connect();

        $request = \Config\Services::request();

        $session = \Config\Services::session();

        $custom_name_helper = new custom_name_helper();

        if ($request->getPost()) {

            $invoice_id = $request->getPost('invoice');

            $paid_amount = Applib::format_deci($request->getPost('amount'));

            $validation = \Config\Services::validation();

            // Set the validation rules
            $validation->setRules([
                'amount' => 'required'
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                $session->setFlashdata('error', lang('hd_lang.payment_failed'));
                return redirect()->to('invoices/view/' . $invoice_id);
            } else {
                $due = Invoice::get_invoice_due_amount($invoice_id);

                if ($paid_amount > $due) {
                    $session->setFlashdata('error', lang('hd_lang.overpaid_amount'));
                    return redirect()->to('invoices/view/' . $invoice_id);
                }

                if ($request->getPost('attach_slip') == 'on') {
                    if (file_exists($_FILES['payment_slip']['tmp_name']) || is_uploaded_file($_FILES['payment_slip']['tmp_name'])) {
                        $upload_response = $this->upload_slip($request->getPost());
                        if ($upload_response) {
                            $attached_file = $upload_response;
                        } else {
                            $attached_file = null;
                            $session->setFlashdata('error', lang('hd_lang.file_upload_failed'));
                            return redirect()->to('invoices/view/' . $invoice_id);
                        }
                    }
                }

                $data = array(
                    'invoice' => $invoice_id,
                    'paid_by' => Invoice::view_by_id($invoice_id)->client,
                    'payment_method' => $request->getPost('payment_method'),
                    'currency' => $request->getPost('currency'),
                    'amount' => $paid_amount,
                    'payment_date' => $request->getPost('payment_date'),
                    'trans_id' => $request->getPost('trans_id'),
                    'notes' => $request->getPost('notes'),
                    'month_paid' => date('m'),
                    'year_paid' => date('Y'),
                );

                $payment_id = App::save_data('hd_payments', $data);
                //print_r($payment_id);die;
                if (isset($payment_id)) {
                    if ($request->getPost('payment_method') == 6) {
                        $client = Client::view_by_id(Invoice::view_by_id($invoice_id)->client);
                        $credit = $client->transaction_value;
                        $bal = $credit - $paid_amount;

                        $balance = array(
                            'transaction_value' => Applib::format_deci($bal)
                        );
                        App::update('hd_companies', array('co_id' => $client->co_id), $balance);
                    }


                    if (isset($attached_file)) {
                        $data = array('attached_file' => $attached_file);
                        Payment::update_pay($payment_id, $data);
                    }

                    if (Invoice::get_invoice_due_amount($invoice_id) <= 0.00) {
                        $db->table('hd_invoices')->where('inv_id', $invoice_id)->update(array('status' => 'Paid'));
                        //Invoice::update($invoice_id, array('status' => 'Paid'));
                        //modules::run('orders/process', $invoice_id);
                    }


                    $cur = Invoice::view_by_id($invoice_id)->currency;
                    $cur_i = App::currencies($cur);

                    //$register_domain = $this->check_customer();

                    $data = array(
                        'user' => User::get_id(),
                        'module' => 'invoices',
                        'module_field_id' => $invoice_id,
                        'activity' => 'activity_payment_of',
                        'icon' => 'fa-usd',
                        'value1' => $cur_i->symbol . '' . $paid_amount,
                        'value2' => Invoice::view_by_id($invoice_id)->reference_no,
                    );
                    App::Log($data);

                    //send_payment_email($invoice_id, $paid_amount);

                    $helper = new custom_name_helper();
                    $result = $helper->auto_activate($invoice_id);
                    $results = $helper->auto_unsuspend($invoice_id);
                    $order_details = $db->table('hd_orders')->where('invoice_id', $invoice_id)->get()->getRow();
                    //print_r($order_details->status_id);die;
                    if ($order_details->status_id == 9) {
                        $results = $helper->auto_unsuspend($invoice_id);
                    } else {
                        //echo 67;die;
                        $result = $helper->auto_activate($invoice_id);
                    }
                    //echo "<pre>";print_r($result);die;

                    if ($custom_name_helper->getconfig_item('notify_payment_received') == 'TRUE') {
                        notify_admin($invoice_id, $paid_amount, $cur);
                    }

                    // Applib::go_to('invoices/view/'.$invoice_id, 'success', lang('hd_lang.payment_added_successfully'));
                    $session->setFlashdata('error', lang('hd_lang.payment_added_successfully'));
                    return redirect()->to('invoices/view/' . $invoice_id);
                }
            }
        } else {
            $data['page'] = lang('hd_lang.invoices');
            $data['id'] = $invoice;
            $data['datepicker'] = true;
            $data['attach_slip'] = true;
            $data['invoices'] = $this->_show_invoices();

            //     $this->template
            // ->set_layout('users')
            // ->build('pay_invoice', isset($data) ? $data : null);
            return view('modules/invoices/pay_invoice', $data);
        }
    }

    public function show($invoice_id = null)
    {
        $db = \Config\Database::connect();

        $session = \Config\Services::session();

        $data = array('show_client' => 'Yes');
        //Invoice::update($invoice_id, $data);
        $db->table('hd_invoices')->where('inv_id', $invoice_id)->update($data);
        // Applib::go_to($_SERVER['HTTP_REFERER'], 'success', lang('hd_lang.invoice_visible'));

        $session->setFlashdata('success', lang('hd_lang.invoice_visible'));
        return redirect()->to('invoices/view/' . $invoice_id);
    }


    public function hide($invoice_id = null)
    {
        $invoice = new Invoice();

        $session = \Config\Services::session();

        $data = array('show_client' => 'No');
        $invoice->update($invoice_id, $data);
        //Applib::go_to($_SERVER['HTTP_REFERER'], 'success', lang('hd_lang.invoice_not_visible'));
        $session->setFlashdata('success', lang('hd_lang.invoice_not_visible'));
        return redirect()->to('invoices');
    }


    public function cancel($invoice = null)
    {
        $request = \Config\Services::request();

        $session = \Config\Services::session();

        // Connect to the database  
        $db = \Config\Database::connect();

        if ($request->getPost()) {
            $invoice_id = $request->getPost('id');
            $info = Invoice::view_by_id($invoice_id);

            $due = Invoice::get_invoice_due_amount($invoice_id);

            $data = array('status' => 'Cancelled');
            //App::update('hd_invoices', array('inv_id' => $invoice_id), $data);
            $db->table('hd_invoices')->where(array('inv_id' => $invoice_id))->update($data);

            $inv_cur = $info->currency;
            $cur_i = App::currencies($inv_cur);

            // Log activity
            $data = array(
                'module' => 'invoices',
                'module_field_id' => $invoice_id,
                'user' => User::get_id(),
                'activity' => 'activity_invoice_cancelled',
                'icon' => 'fa-usd',
                'value1' => $info->reference_no,
                'value2' => $cur_i->symbol . '' . $due,
            );
            App::Log($data);

            //Applib::go_to('invoices/view/'.$invoice_id, 'success', lang('hd_lang.invoice_cancelled_successfully'));

            $session->setFlashdata('success', lang('hd_lang.invoice_cancelled_successfully'));
            return redirect()->to('invoices/view/' . $invoice_id);
        } else {
            $data = array('id' => $invoice);
            //$this->load->view('modal/cancel', $data);
            echo view('modules/invoices/modal/cancel', $data);
        }
    }
	
	function payment_status($id = null)
	{	
		$request = \Config\Services::request();

        $session = \Config\Services::session(); 
        // Connect to the database  
        $db = \Config\Database::connect();

		if ($request->getPost()) {
			$invoice = $request->getPost('invoice');
			
			// In your controller constructor
			$validator = \Config\Services::validation();

			// Replace 'required' with 'required' rule, you can add other rules as needed
			$validator->setRules([
				'invoice' => 'required',
				'payment_status' => 'required'
			]);

			if (!$validator->withRequest($this->request)->run()) {
				return redirect()->to('invoices/view/' . $invoice)->with('error', lang('hd_lang.error_in_form'));
			}else{	

			$item = $request->getPost('payment_status');
				
            $items = Invoice::has_items($invoice);

			//print_r($form_data);die;
			if($db->table('hd_invoices')->where('inv_id', $invoice)->update(['status' => $item])){ 
					//Applib::go_to('invoices/view/'.$invoice,'success',lang('hd_lang.item_added_successfully'));
					$session->setFlashdata('success', lang('hd_lang.item_added_successfully'));
					return redirect()->to('invoices/view/'.$invoice);
				}
			}
		}else{
			$data['invoice'] = $id;
			// $this->load->view('modal/quickadd',$data);
			return view('modules/invoices/modal/payment_status', $data);
		}
	}

    //Done
    public function mark_as_paid($invoice = null)
    {	
        $request = \Config\Services::request();

        $session = \Config\Services::session();

        $db = \Config\Database::connect();

        helper('text');
        if ($request->getPost()) {
            $invoice_id = $request->getPost('invoice');

            $info = Invoice::view_by_id($invoice_id);

            $due = Invoice::get_invoice_due_amount($invoice_id);

            $transaction = array(
                'invoice' => $invoice_id,
                'paid_by' => $info->client,
                'payment_method' => '1',
                'currency' => $info->currency,
                'amount' => Applib::format_deci($due),
                'payment_date' => date('Y-m-d'),
                'trans_id' => random_string('nozero', 6),
                'month_paid' => date('m'),
                'year_paid' => date('Y'),
            );

            $db->table('hd_payments')->insert($transaction);
            $data = array('status' => 'Paid');
            $db->table('hd_invoices')->where('inv_id', $invoice_id)->update($data);

            $helper = new custom_name_helper();
            //$helper->auto_activate($invoice_id);
            //$helper->auto_unsuspend($invoice_id);
            $order_details = $db->table('hd_orders')->where('invoice_id', $invoice_id)->get()->getRow();
            //print_r($order_details->status_id);die;
            //if ($order_details->status_id == 9) {
               // $results = $helper->auto_unsuspend($invoice_id);
            //} else {
                //echo 67;die;
             //   $result = $helper->auto_activate($invoice_id);
            //}

            $inv_cur = $info->currency;
            $cur_i = App::currencies($inv_cur);

            $existing_domain = $request->getPost('domain_type');
            //print_r($existing_domain);die;
            //if (!$existing_domain) {
            //	echo 89;die;
            //$register_domain = $this->check_customer();
            //}

            // Log activity
            $data = array(
                'module' => 'invoices',
                'module_field_id' => $invoice_id,
                'user' => User::get_id(),
                'activity' => 'activity_payment_of',
                'icon' => 'fa-usd',
                'value1' => $cur_i->symbol . ' ' . $due,
                'value2' => $info->reference_no,
            );
            App::Log($data);

            //Applib::go_to('invoices/view/'.$invoice_id, 'success', lang('hd_lang.payment_added_successfully'));
            $session->setFlashdata('success', lang('hd_lang.payment_added_successfully'));
            return redirect()->to('invoices/view/' . $invoice_id);
        } else {
            $data = array('invoice' => $invoice);
            //$this->load->view('modal/mark_as_paid', $data);
            echo view('modules/invoices/modal/mark_as_paid', $data);
        }
    }


    public function stop_recur($invoice_id = null)
    {
        if (User::is_client()) {
            Applib::go_to('invoices', 'error', lang('hd_lang.access_denied'));
        }

        if ($this->input->post()) {
            $invoice = $this->input->post('invoice', true);
            $this->load->model('invoices/invoices_recurring');

            if ($this->invoices_recurring->stop($invoice)) {
                // Log activity
                $data = array(
                    'module' => 'invoices',
                    'module_field_id' => $invoice,
                    'user' => User::get_id(),
                    'activity' => 'activity_recurring_stopped',
                    'icon' => 'fa-plus',
                    'value1' => Invoice::view_by_id($invoice)->reference_no,
                    'value2' => '',
                );
                App::Log($data);
                Applib::go_to('invoices/view/' . $invoice, 'success', lang('hd_lang.recurring_invoice_stopped'));
            }
        } else {
            $data['invoice'] = $invoice_id;
            $this->load->view('modal/stop_recur', $data);
        }
    }



    public function get_date_due($invoice_date_created)
    {
        $custom_name_helper = new custom_name_helper();
        //echo $custom_name_helper->getconfig_item('invoices_due_after');die;
        $invoice_date_due = new DateTime($invoice_date_created);
        $invoice_date_due->add(new DateInterval('P' . $custom_name_helper->getconfig_item('invoices_due_after') . 'D'));
        //echo"<pre>";print_r($result);die;
        return $invoice_date_due->format('Y-m-d');
    }


    public function transactions($invoice_id = null)
    {

        //$this->template->title(lang('hd_lang.payments'));
        $data['page'] = lang('hd_lang.payments');

        $data['invoices'] = $this->_show_invoices();
        $data['datatables'] = true;
        $data['payments'] = Payment::by_invoice($invoice_id);
        $data['id'] = $invoice_id;
        // $this->template
        //     ->set_layout('users')
        //     ->build('invoice_payments', isset($data) ? $data : null);
        echo view('modules/invoices/invoice_payments', $data);
    }


    public function delete($invoice_id = null)
    {
        $invoiceModel = new Invoice();

        $request = \Config\Services::request();

        $session = \Config\Services::session();

        // Connect to the database  
        $db = \Config\Database::connect();

        if ($request->getPost()) {
            $invoice = $request->getPost('invoice');

            $result = $db->table('hd_orders')
                ->where('invoice_id', $invoice)
                ->get()
                ->getNumRows();

            if ($result > 0) {
                $invoiceModel->update($invoice, array('inv_deleted' => 'Yes'));
            } else {
                $invoiceModel->deleteInvoice($invoice);
            }

            //Applib::go_to('invoices', 'success', lang('hd_lang.invoice_deleted_successfully'));
            $session->setFlashdata('success', lang('hd_lang.invoice_deleted_successfully'));
            return redirect()->to('invoices');
        } else {
            $data['invoice'] = $invoice_id;
            //$this->load->view('modal/delete_invoice', $data);
            return view('modules/invoices/modal/delete_invoice', $data);
        }
    }




    public function add_funds_invoice($company = null)
    {

        $custom_name_helper = new custom_name_helper();

        $request = \Config\Services::request();

        $session = \Config\Services::session();

        // Connect to the database  
        $db = \Config\Database::connect();

        if ($request->getPost() && null != $request->getPost('create_invoice')) {
            $user = User::get_id_client();
            $user_company = User::profile_info($user)->company;

            $data = array(
                'reference_no' => $custom_name_helper->getconfig_item('invoice_prefix') . Invoice::generate_invoice_number(),
                'currency' => $custom_name_helper->getconfig_item('default_currency'),
                'due_date' => date('Y-m-d'),
                'client' => $user_company,
                'notes' => $custom_name_helper->getconfig_item('default_terms')
            );

            if ($invoice_id = $db->table('hd_invoices')->insert($data)) {
                $item = array(
                    'invoice_id'     => $invoice_id,
                    'item_name'        => lang('hd_lang.add_funds'),
                    'item_desc'        => $custom_name_helper->getconfig_item('company_name') . " " . lang('hd_lang.credit'),
                    'unit_cost'        => Applib::format_deci($request->getPost('amount')),
                    'item_order'    => 1,
                    'quantity'        => 1,
                    'total_cost'    => Applib::format_deci($request->getPost('amount'))
                );

                if ($item_id = App::save_data('hd_items', $item)) {
                    //redirect('invoices/view/'.$invoice_id); 
                    return redirect()->to('invoices/view/' . $invoice_id);
                }
            }
        } else {
            redirect('clients');
        }
    }




    public function add_funds($company)
    {
        $data['company'] = $company;
        //$this->load->view('modal/add_funds', $data);
        echo view('modules/invoices/modal/add_funds', $data);
    }



    public function remind($invoice = null)
    {
        $request = \Config\Services::request();

        $session = \Config\Services::session();

        $custom_name_helper = new custom_name_helper();

        // Connect to the database  
        $db = \Config\Database::connect();

        if ($request->getPost()) {
            $invoice = $request->getPost('invoice_id');
            $message = $request->getPost('message');

            $cur = Invoice::view_by_id($invoice)->currency;
            $reference = Invoice::view_by_id($invoice)->reference_no;

            $subject = $request->getPost('subject');
            $signature = App::email_template('email_signature', 'template_body');

            $logo_link = create_email_logo();

            $logo = str_replace('{INVOICE_LOGO}', $logo_link, $message);
            $ref = str_replace('{REF}', $reference, $logo);

            $client = str_replace('{CLIENT}', $this->input->post('client_name'), $ref);
            $amount = str_replace('{AMOUNT}', $this->input->post('amount'), $client);
            $currency = str_replace('{CURRENCY}', App::currencies($cur)->symbol, $amount);
            $link = str_replace('{INVOICE_LINK}', base_url() . 'invoices/view/' . $invoice, $currency);
            $signature = str_replace('{SIGNATURE}', $signature, $link);
            $message = str_replace('{SITE_NAME}', $custom_name_helper->getconfig_item('company_name'), $signature);

            $this->_email_invoice($invoice, $message, $subject, $cc = 'off');

            if ($custom_name_helper->getconfig_item('sms_gateway') == 'TRUE' && $custom_name_helper->getconfig_item('sms_invoice_reminder') == 'TRUE') {
                send_message($invoice, 'invoice_reminder');
            }

            // Log Activity
            $activity = array(
                'user' => User::get_id(),
                'module' => 'invoices',
                'module_field_id' => $invoice,
                'activity' => 'activity_invoice_reminder_sent',
                'icon' => 'fa-shopping-cart',
                'value1' => $reference,
            );
            App::Log($activity); // Log activity

            // Applib::go_to('invoices/view/'.$invoice, 'success', lang('hd_lang.reminder_sent_successfully'));
            $session->setFlashdata('success', lang('hd_lang.reminder_sent_successfully'));
            return redirect()->to('invoices/view/' . $invoice);
        } else {
            $data['id'] = $invoice;
            // $this->load->view('modal/invoice_reminder', $data);
            echo view('modules/invoices/modal/invoice_reminder', $data);
        }
    }


    public function send_invoice($invoice_id = null)
    {

        $tank_auth = new Tank_auth();

        $request = \Config\Services::request();

        $session = \Config\Services::session();

        $custom_name_helper = new custom_name_helper();

        // Connect to the database  
        $db = \Config\Database::connect();

        if ($request->getPost()) {
            $id = $request->getPost('invoice');
            $invoice = Invoice::view_by_id($id);

            $client = Client::view_by_id($invoice->client);
            $due = Invoice::get_invoice_due_amount($id);
            $cur = App::currencies($invoice->currency);

            if ($client->primary_contact > 0) {
                $login = '?login=' . $tank_auth->create_remote_login($client->primary_contact);
            } else {
                $login = '';
            }

            $subject = $request->getPost('subject');
            $message = $request->getPost('message');
            $signature = App::email_template('email_signature', 'template_body');

            $helper = new app_helper();
            $logo_link = $helper->create_email_logo();

            $logo = str_replace('{INVOICE_LOGO}', $logo_link, $message);

            $client_name = str_replace('{CLIENT}', $client->company_name, $logo);
            $ref = str_replace('{REF}', $invoice->reference_no, $client_name);
            $amount = str_replace('{AMOUNT}', Applib::format_quantity($due), $ref);
            $currency = str_replace('{CURRENCY}', $cur->symbol, $amount);
            $link = str_replace('{INVOICE_LINK}', base_url() . 'invoices/view/' . $id . $login, $currency);
            $signature = str_replace('{SIGNATURE}', $signature, $link);
            $message = str_replace('{SITE_NAME}', $custom_name_helper->getconfig_item('company_name'), $signature);

            $this->_email_invoice($id, $message, $subject, $request->getPost('cc_self')); // Email Invoice

            $data = array('emailed' => 'Yes', 'date_sent' => date('Y-m-d H:i:s', time()));
            //Invoice::update($id, $data);
            $db->table('hd_invoices')->where('inv_id', $id)->update($data);


            // Log Activity
            $activity = array(
                'user' => User::get_id(),
                'module' => 'invoices',
                'module_field_id' => $id,
                'activity' => 'activity_invoice_sent',
                'icon' => 'fa-envelope',
                'value1' => $invoice->reference_no,
            );
            App::Log($activity);

            //Applib::go_to('invoices/view/'.$id, 'success', lang('hd_lang.invoice_sent_successfully'));
            $session->setFlashdata('success', lang('hd_lang.invoice_sent_successfully'));
            return redirect()->to('invoices/view/' . $id);
        } else {
            $data['id'] = $invoice_id;
            //$this->load->view('modal/email_invoice', $data);
            echo view('modules/invoices/modal/email_invoice', $data);
        }
    }


	public function _email_invoice($invoice_id, $message, $subject, $cc)
	{	
		
		$custom_name_helper = new custom_name_helper();

		$data['message'] = $message;
		$invoice = Invoice::view_by_id($invoice_id);

        // $message = view('email_template', $data);

		$dom = new DOMDocument();
        $dom->loadHTML(view('email_template', $data));
        $message = $dom->getElementsByTagName('body')->item(0)->nodeValue;

		$params = array(
			'recipient' => Client::view_by_id($invoice->client)->company_email,
			'subject' => $subject,
			'message' => $message,
		);

		if (empty($params['recipient'])) {
			// Log error: Client email address is empty
			return;
		}

		$attach['inv_id'] = $invoice_id;
		if ($custom_name_helper->getconfig_item('pdf_engine') == 'invoicr') { 
			$fopdf = new Fopdf();
            $invoicehtml = $fopdf->attach_invoice($attach);
		}

		if ($custom_name_helper->getconfig_item('pdf_engine') == 'mpdf') {	 
			$invoicehtml = $this->attach_pdf($invoice_id);
		}

		$params['attached_file'] = './tmp/' . lang('hd_lang.invoice') . ' ' . $invoice->reference_no . '.pdf';
		$params['attachment_url'] = base_url() . 'tmp/' . lang('hd_lang.invoice') . ' ' . $invoice->reference_no . '.pdf';

		if (strtolower($cc) == 'on') {
			$params['cc'] = User::login_info(User::get_id())->email;
		}

		$mailer = new Fomailer();
        $send = $mailer->send_email($params);
		//Delete invoice in tmp folder
		if (is_file('./tmp/' . lang('hd_lang.invoice') . ' ' . $invoice->reference_no . '.pdf')) {
			unlink('./tmp/' . lang('hd_lang.invoice') . ' ' . $invoice->reference_no . '.pdf');
		}
	}



    public function pdf($invoice_id = null)
    {
        $custom_name_helper = new custom_name_helper();

        // if (!User::can_view_invoice(User::get_id(), $invoice_id)) {
        //     App::access_denied('invoices');
        // }

        $applib = new AppLib();

        $data['page'] = lang('hd_lang.invoices');
        $data['stripe'] = true;
        $data['twocheckout'] = true;
        $data['sortable'] = true;
        $data['typeahead'] = true;
        $data['rates'] = Invoice::get_tax_rates();
        $data['id'] = $invoice_id;

        //$html = $this->load->view('invoice_pdf', $data, true);

        $html = view('modules/invoices/invoice_pdf', $data);

        $pdf = array(
            'html' => $html,
            'title' => lang('hd_lang.invoice') . ' ' . Invoice::view_by_id($invoice_id)->reference_no,
            'author' => $custom_name_helper->getconfig_item('company_name'),
            'creator' => $custom_name_helper->getconfig_item('company_name'),
            'filename' => lang('hd_lang.invoice') . ' ' . Invoice::view_by_id($invoice_id)->reference_no . '.pdf',
            'badge' => $custom_name_helper->getconfig_item('display_invoice_badge'),
        );

        $applib->create_pdf($pdf);
    }


    public function attach_pdf($invoice_id)
    {
        $custom_name_helper = new custom_name_helper();

        if (!User::can_view_invoice(User::get_id(), $invoice_id)) {
            App::access_denied('invoices');
        }

        $data['page'] = lang('hd_lang.invoices');
        $data['stripe'] = true;
        $data['twocheckout'] = true;
        $data['sortable'] = true;
        $data['typeahead'] = true;
        $data['rates'] = Invoice::get_tax_rates();
        $data['id'] = $invoice_id;

        $html = $this->load->view('invoice_pdf', $data, true);

        $pdf = array(
            'html' => $html,
            'title' => lang('hd_lang.invoice') . ' ' . Invoice::view_by_id($invoice_id)->reference_no,
            'author' => $custom_name_helper->getconfig_item('company_name'),
            'creator' => $custom_name_helper->getconfig_item('company_name'),
            'attach' => true,
            'filename' => lang('hd_lang.invoice') . ' ' . Invoice::view_by_id($invoice_id)->reference_no . '.pdf',
            'badge' => $custom_name_helper->getconfig_item('display_invoice_badge'),
        );

        $invoice = $this->applib->create_pdf($pdf);

        return $invoice;
    }



    public function _get_clients()
    {
        $sort = array('order_by' => 'date_added', 'order' => 'desc');

        return Applib::retrieve(Applib::$companies_table, array('co_id !=' => '0'));
    }


    public function upload_slip($data)
    {
        Applib::is_demo();

        if ($data) {
            $config['upload_path'] = './resource/uploads/';
            $config['allowed_types'] = 'jpg|jpeg|png|pdf|docx|doc';
            $config['remove_spaces'] = true;
            $config['overwrite'] = false;
            $this->load->library('upload', $config);

            if ($this->upload->do_upload('payment_slip')) {
                $filedata = $this->upload->data();

                return $filedata['file_name'];
            } else {
                return false;
            }
        } else {
            return false;
        }
    }


    public function _filter_by()
    {
        $filter = isset($_GET['view']) ? $_GET['view'] : '';

        switch ($filter) {

            case 'paid':
                return 'paid';
                break;

            case 'unpaid':
                return 'unpaid';
                break;

            case 'partially_paid':
                return 'partially_paid';

                break;
            case 'recurring':
                return 'recurring';
                break;

            default:
                return null;
                break;
        }
    }

    public function check_customer($type = null)
    {    //echo $type;die;
        $db = \Config\Database::connect();
        $controller = new APIController();
        $session = \Config\Services::session();
        $co_id = session()->get('select_client.co_id');
        //echo"<pre>";print_r($co_id);die;
        $query = $db->table('hd_invoices')->where('client', $co_id)->get()->getRow();
        $getorders = $db->table('hd_orders')->where('invoice_id', $query->inv_id)->where('type', $type)->get()->getRow();
        $invId = $getorders->invoice_id;
        $registerdetails = '';
        if ($query->status == 'Paid') {
            $resellerclub_customer_details = $controller->cust_details_by_username($co_id);    //  get customer id by username from api
            // echo"<pre>";print_r($resellerclub_customer_details);die;
            //print_r($resellerclub_domain_register);die;
            $message = json_decode($resellerclub_customer_details);
            //print_r($message->status);die;
            if ($message->status == "success") {
                // echo 99;die;
                $resellerclub_contact_details = $controller->get_contact_details($co_id);
                // echo"<pre>";print_r($resellerclub_contact_details);die;
                $controller->register($co_id, $query->inv_id);
                //return redirect()->to('invoices/view/'.$invId);
            } else {
                echo 89;
                die;
                $co_id = session()->get('select_client.co_id');
                //print_r($co_id);die;
                $resellerclub_customer_register = $controller->cust_sign_up($co_id);
                $resellerclub_contact_details = $controller->get_contact_details($co_id);
                echo 90;
                die;
                $controller->register($co_id, $query->inv_id);
                //return redirect()->to('invoices/view/'.$invId);
            }
        } else {
            echo "Please do full payment to register your domain on Resellerclub!";
        }
    }
}
