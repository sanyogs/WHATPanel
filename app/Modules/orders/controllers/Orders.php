<?php
 /*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

namespace App\Modules\orders\controllers;

use App\Libraries\AppLib;
use App\Models\App;
//use App\Modules\auth\controllers\Auth;
use App\Models\Client;
use App\Models\FAQS;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Modules\Layouts\Libraries\Template;
use App\ThirdParty\MX\WhatPanel;
use App\ThirdParty\MX\Modules;
use Config\Database;
use Config\Session;
use DateInterval;
use DateTime;
use App\Helpers\custom_name_helper;

use Modules\cyberpanel\controllers\Cyberpanel;
use Modules\cwp\controllers\CWP;
use Modules\cpanel\controllers\Cpanel;
use Modules\directadmin\controllers\Directadmin;
use Modules\ispconfig\controllers\Ispconfig;
use Modules\plesk\controllers\Plesk;


class Orders extends WhatPanel
{
	protected $server;
	protected $filter_by;
	protected $orderModel;
	protected $db_con;
	protected $custom_helper;

	function __construct()
	{
		error_reporting(E_ALL);
		ini_set("display_errors", "1");
		$helper = new custom_name_helper();
		$helper->settimezone();
		// parent::__construct(); 
		// User::logged_in();

		// $this->load->module('layouts');      
		// $this->load->library('template');
		$this->filter_by = $this->_filter_by();
		//$authcon = new Auth();
		// $server = $this->db->where(array('selected'=> 1))->get('servers')->row();	

		// if($server) {
		// 	$this->server = $server->id;
		// }	

		// $lang = $this->custom_helper->getconfig_item('default_language');
		// if (isset($_COOKIE['fo_lang'])) { $lang = $_COOKIE['fo_lang']; }
		// if ($this->session->userdata('lang')) { $lang = $this->session->userdata('lang'); }
		// $this->lang->load('hd', $lang);

		$session = \Config\Services::session();
		
		//$session = Services::session();

        // Check if the user session exists
        if (!$session->has('logged_in')) {
            // Redirect to login page
            header('Location: ' . base_url('login'));
            exit();
        }
		
		//if(empty($session->get('userdata')))
		//{
		//	return $authcon->login_page();
		//}

		// print_r($db_name);die;
		$config = new Database();
		// Connect to the database	
		$this->db_con = Database::connect($config->default);

		$this->orderModel = new Order();

		//$this->custom_helper = new custom_name_helper();
	}
	
	public function view($order_id = null)
    {
        $orderModel = new Order();

        $custom_name_helper = new custom_name_helper();
		
		$request = \Config\Services::request();

		// Pagination Configuration
		$page = $request->getGet('page') ? $request->getGet('page') : 1;

		$perPage = $request->getGet('recordsPerPage', FILTER_SANITIZE_NUMBER_INT) ? $request->getGet('recordsPerPage', FILTER_SANITIZE_NUMBER_INT) : 10;

		// Search Filter
		$search = $request->getGet('search');
		$data['search'] = $search;

        //$this->template->title(lang('hd_lang.invoices').' - '.$custom_name_helper->getconfig_item('company_name'));
        $data['page'] = lang('hd_lang.invoice');
        $data['stripe'] = true;
        $data['twocheckout'] = true;
        $data['sortable'] = true;
        $data['typeahead'] = true;
		
		$query = $this->orderModel->listOrders([], $search, $perPage, $page);
		
        $data['orders'] = array_map(function ($item) {
			return (object) $item;
		}, $query['items']);
		
        $data['id'] = $order_id;
		
        //print_r($data);die();
        echo view('modules/orders/view', $data);
    }



	function index()
	{
		$custom_helper = new custom_name_helper();

		$request = \Config\Services::request();

		// Pagination Configuration
		$page = $request->getGet('page') ? $request->getGet('page') : 1;

		$perPage = $request->getGet('recordsPerPage', FILTER_SANITIZE_NUMBER_INT) ? $request->getGet('recordsPerPage', FILTER_SANITIZE_NUMBER_INT) : 10;

		// Search Filter
		$search = $request->getGet('search');
		$data['search'] = $search;

		$app = new App();
		$app->module_access('menu_orders');
		$template = new Template();
		$template->title(lang('hd_lang.orders') . ' - ' . $custom_helper->getconfig_item('company_name'));
		$data['page'] = lang('hd_lang.orders');
		$data['datatables'] = TRUE;
		$data['form'] = true;
		$array = $this->filter_by($this->filter_by);
		//$data['orders'] = $this->orderModel->listOrders($array);

		$query = $this->orderModel->listOrders([], $search, $perPage, $page);
		// Get items for the current page
		$data['orders'] = array_map(function ($item) {
			return (object) $item;
		}, $query['items']);
		//print_r($data['orders']);die;
		$data['pager'] = $query['pager'];

		$data['message'] = $query['message'];

		$data['perPage'] = $perPage;

		$data['filter'] = "";
		echo view('modules/orders/orders', $data);
	}



	function _filter_by()
	{
		$filter = isset($_GET['view']) ? $_GET['view'] : '';
		return $filter;
	}



	function filter_by($filter_by)
	{
		switch ($filter_by) {
			case 'unpaid':
				return array('hd_invoices.status' => 'Unpaid', 'status_id <>' => 8, 'status_id <>' => 2);
				break;

			case 'paid':
				return array('hd_invoices.status' => "Paid", 'status_id <>' => 8, 'status_id <>' => 2);
				break;

			default:
				return array('status_id <>' => 8, 'status_id <>' => 2);
				break;
		}
	}


	function activate($id = null)
    {
        $request = \Config\Services::request();

        $session = \Config\Services::session();

        // Modify the 'default' property    
        $custom_helper = new custom_name_helper();

        // Connect to the database  
        $db = \Config\Database::connect();

        $domain_servers = array();

        // App::module_access('menu_orders');

        if ($request->getPost()) {

            $result = "";
            if ($request->getPost('hosting')) {

                $client = Client::view_by_id($request->getPost('client_id'));
                $accounts = $request->getPost('username');
                $domain = $request->getPost('hosting_domain');
                $passwords = $request->getPost('password');
                $hosting = $request->getPost('hosting');
                $service = $request->getPost('service');
                $servers = $request->getPost('server');
                $user = User::view_user($client->primary_contact);
                $profile = User::profile_info($client->primary_contact);

				$data = array('inv_deleted' => "No");
                $db->table('hd_invoices')->where('inv_id', $request->getPost('inv_id'))->update($data);

                foreach ($accounts as $key => $account) {
                    $item = $request->getPost('item_id');

                    $domain_servers[] = array($domain[$key] => $servers[$key]);

                    $update = array(
                        "status_id" => 6,
                        "username" => $accounts[$key],
                        "password" => $passwords[$key],
                        "server" => $servers[$key]
                    );


                    if ($db->table('hd_orders')->where('id', $hosting[$key])->update($update)) {
                        $result .= $service[$key] . " for " . $domain[$key] . " activated.<br>";
                    }

                    $username = $accounts[$key];

                    if ($username && $username . '_send_details' == 'on') {
                        Order::send_account_details($hosting[$key]);
                    }

                    $acc = Order::get_order($hosting[$key]);

                    if ($custom_helper->getconfig_item('demo_mode') != 'TRUE') {
                        if ($request->getPost($acc->username . '_controlpanel')  == 'on') {
                            $package = $db->table('hd_items_saved')->where(array('item_id' => $acc->item_parent))->get()->getRow();
                            $server = $db->table('hd_servers')->where(array('id' => $servers[$key]))->get()->getRow();

                            $details = (object) array(
                                'user' => $user,
                                'profile' => $profile,
                                'client' => $client,
                                'account' => $acc,
                                'package' => $package,
                                'server' => $server
                            );

                            //$result .= modules::run($server->type . '/create_account', $details);
                            switch ($server->type) {
								case 'plesk':
									$plesk = new Plesk();
									$result = $plesk->create_account($details);
								break;
								case 'ispconfig':
									$ispconfig = new Ispconfig();
									$result = $ispconfig->create_account($details);
								break;
								case 'directadmin':
									$directadmin = new Directadmin();
									$result = $directadmin->create_account($details);
								break;
								case 'cyberpanel':
									$cyberpanel = new Cyberpanel();
									$result = $cyberpanel->create_account($details);
								break;
								case 'cwp':
									$cwp = new Cwp();
									$result = $cwp->create_account($details);
								break;
								case 'cpanel':
									$cpanel = new Cpanel();
									$result = $cpanel->create_account($details);
								break;
							}
                        }
                    }
                }
            }
            if ($request->getPost('domain')) {
                $domains = $request->getPost('domain');

                foreach ($domains as $key => $account) {

                    $acc = Order::get_order($domains[$key]);

                    $update = array(
                        "status_id" => 6,
                        "authcode" => $request->getPost('authcode')[$key],
                        'registrar' => $request->getPost('registrar')[$key]
                    );

                    if ($db->table('hd_orders')->where('id', $domains[$key])->update($update)) {

                        $domain = explode('.', $acc->domain, 2);

                        if ($request->getPost($domain[0] . '_activate') == 'on') {

                            if ($request->getPost('registrar')[$key] != '') {

                                $registrar = $request->getPost('registrar')[$key];


                                $action = '/register_domain';

                                $nameservers = Order::get_nameservers($domains[$key], $domain_servers);

                                if ($nameservers != '') {
                                    $nameservers = explode(",", $nameservers);
                                } else {
                                    $nameservers = array();
                                    if ($custom_helper->getconfig_item('nameserver_one') != '') {
                                        $nameservers[] = $custom_helper->getconfig_item('nameserver_one');
                                    }
                                    if ($custom_helper->getconfig_item('nameserver_two') != '') {
                                        $nameservers[] = $custom_helper->getconfig_item('nameserver_two');
                                    }
                                    if ($custom_helper->getconfig_item('nameserver_three') != '') {
                                        $nameservers[] = $custom_helper->getconfig_item('nameserver_three');
                                    }
                                    if ($custom_helper->getconfig_item('nameserver_four') != '') {
                                        $nameservers[] = $custom_helper->getconfig_item('nameserver_four');
                                    }
                                    if ($custom_helper->getconfig_item('nameserver_five') != '') {
                                        $nameservers[] = $custom_helper->getconfig_item('nameserver_five');
                                    }
                                }

                                if ($acc->item_name == lang('hd_lang.domain_renewal')) {
                                    $action = '/renew_domain';
                                }

                                if ($acc->item_name == lang('hd_lang.domain_transfer')) {
                                    $action = '/transfer_domain';
                                }

								echo $registrar . '<br>' . $action;die;

                                $result .= modules::run($registrar . $action, $domains[$key], $nameservers);
                            }

                            $data = array(
                                'user' => User::get_id(),
                                'module' => 'accounts',
                                'module_field_id' => $domains[$key],
                                'activity' => $result,
                                'icon' => 'fa-usd',
                                'value1' =>  $acc->domain,
                                'value2' => ''
                            );
                            App::Log($data);

                            $result .= "<p>" . $acc->domain . " activated! </p>";
                        }
                    }
                }
            }

            session()->setFlashdata('response_status', 'warning');
            session()->setFlashdata('message', $result);


            //redirect($_SERVER['HTTP_REFERER']);  
            return redirect()->to('orders');
        } else {
            $data['order'] = $this->get_order($id);
            $data['servers'] = $db->table('hd_servers')->get()->getResult();
            // $this->load->view('modal/activate', $data);
            return view('modules/orders/modal/activate', $data);
        }
    }


	function cancel($id = null)
	{
		// App::module_access('menu_orders');

		$request = \Config\Services::request();

		$session = \Config\Services::session();

		$custom_helper = new custom_name_helper();
		// Modify the 'default' property    

		// Connect to the database  
		$db = \Config\Database::connect();

		if ($request->getPost()) {

			if ($request->getPost('credit_account') == 'on') {
				Invoice::credit_client($request->getPost('invoice_id'));
			}

			$result = "";
			if ($request->getPost('hosting')) {
				$accounts = $request->getPost('username');
				$hosting = $request->getPost('hosting');
				$service = $request->getPost('service');
				$domain = $request->getPost('account');

				$db->table('hd_invoices')
					->set('inv_deleted', 'Yes')
					->where('inv_id', $request->getPost('invoice_id'))
					->update();

				foreach ($accounts as $key => $a) {

					$db->table('hd_orders')
						->set('status_id', 7)
						->where('id', $orderId)
						->update();

					if ($db->affectedRows() > 0) {
						// return $service . " for " . $domain . " cancelled.";
						$result .=  $service[$key] . " for " . $domain[$key] . " cancelled.<br>";
					}

					if ($custom_helper->getconfig_item('demo_mode') != 'TRUE') {

						$account = Order::get_order($hosting[$key]);

						if ($request->getPost($account->username . '_delete_controlpanel') == 'on') {
							$server = Order::get_server($account->server);
							$client = Client::view_by_id($account->client_id);
							$user = User::view_user($client->primary_contact);
							$details = (object) array('account' => $account, 'server' => $server, 'package' => $package, 'client' => $client, 'user' => $user);
							// $result .= modules::run($server->type.'/terminate_account', $details);			 

						}
					}
				}
			}



			if ($request->getPost('domain')) {
				$domains = $request->getPost('domain');
				$domain = $request->getPost('domain_name');

				foreach ($domains as $key => $account) {

					// $this->db->set('status_id', 7); 
					// $this->db->where('id', $domains[$key]);  
					// if($this->db->update('orders')){
					// 	$result .= "Domain: " .$domain[$key]." cancelled!<br>";
					// }

					$db->table('hd_orders')
						->set('status_id', 7)
						->where('id', $domains[$key])
						->update();

					if ($db->affectedRows() > 0) {
						// return $service . " for " . $domain . " cancelled.";
						$result .= "Domain: " . $domain[$key] . " cancelled!<br>";
					}
				}
			}

			$session->setFlashdata('response_status', 'warning');
			$session->setFlashdata('message', $result);
			// redirect($_SERVER['HTTP_REFERER']);
			return redirect()->to('orders');
		} else {
			$data['order'] = $this->get_order($id);
			$data['servers'] = $db->table('hd_servers')->get()->getResult();
			//$this->load->view('modal/cancel', $data);
			return view('modules/orders/modal/cancel', $data);
		}
	}


	function delete($id = null)
	{
		// App::module_access('menu_orders');

		$request = \Config\Services::request();

		$session = \Config\Services::session();

		$custom = new custom_name_helper();

		// Connect to the database  
		$db = \Config\Database::connect();

		if ($request->getPost()) {

			if ($request->getPost('credit_account') == 'on') {
				Invoice::credit_client($request->getPost('invoice_id'));
			}

			$result = "";
			if ($request->getPost('hosting')) {
				$accounts = $request->getPost('username');
				$hosting = $request->getPost('hosting');
				$service = $request->getPost('service');
				$domain = $request->getPost('account');

				foreach ($accounts as $key => $a) {

					// $this->db->where('id', $hosting[$key]);  
					// if($this->db->delete('orders')) {
					// 	$result .=  $service[$key]." for ". $domain[$key] ." deleted.<br>";
					// }

					$result = $db->table('hd_orders')
						->where('id', $hosting[$key])
						->delete();

					// Check if the delete was successful
					if ($db->affectedRows() > 0) {
						$result .=  $service[$key] . " for " . $domain[$key] . " deleted.<br>";
					}

					if ($custom->getconfig_item('demo_mode') != 'TRUE') {

						$account = Order::get_order($hosting[$key]);
						if (!empty($account)) {
							if ($request->getPost($account->username . '_delete_controlpanel') == 'on') {
								$server = Order::get_server($account->server);
								$client = Client::view_by_id($account->client_id);
								$user = User::view_user($client->primary_contact);
								$details = (object) array('account' => $account, 'server' => $server, 'package' => $package, 'client' => $client, 'user' => $user);
								//$result .= modules::run($server->type.'/terminate_account', $details); 
							}
						}
					}
				}
			}


			if ($request->getPost('domain')) {

				$domains = $request->getPost('domain');
				$domain = $request->getPost('domain_name');

				foreach ($domains as $key => $account) {

					$result = $db->table('hd_orders')
						->where('id', $domains[$key])
						->delete();

					if ($result) {
						$result .= "Domain: " . $domain[$key] . " deleted!<br>";
					}
				}
			}

			$invoice = $request->getPost('invoice_id');
			Invoice::deleteInvoice($invoice);

			$session->setFlashdata('response_status', 'warning');
			$session->setFlashdata('message', $result);
			// redirect($_SERVER['HTTP_REFERER']);
			return redirect()->to('orders');
		} else {
			$data['order'] = $this->get_order($id);
			$data['servers'] = $db->table('hd_servers')->get()->getResult();
			echo view('modules/orders/modal/delete', $data);
		}
	}

	function select_client()
	{	
		$request = \Config\Services::request();
		$template = new Template();
		$custom_helper = new custom_name_helper();
		
		if($request->getPost()) {
			session()->set(array('co_id' => $request->getPost('co_id'), 'select_client' => $request->getPost('co_id')));
			return redirect()->to('orders/add_order');
		}
		else
		{
			$template->title(lang('hd_lang.orders') . ' - ' . $custom_helper->getconfig_item('company_name'));
			$data['page'] = lang('hd_lang.new_order'); 
			$data['form'] = true;
			return view('modules/orders/select_client', $data);
		}
	}
	
	function get_order($id)
	{
		$session = \Config\Services::session();

		// Modify the 'default' property    


		// Connect to the database  
		$dbName = \Config\Database::connect();

		$result = $dbName->table('hd_orders')->select('*')->join('items', 'orders.item = items.item_id', 'LEFT')->where('order_id', $id)->get()->getResult();

		return $result;
	}



	function add_order()
	{	
		$custom_helper = new custom_name_helper();
		$request = \Config\Services::request();
		$template = new Template();
		$session = \Config\Services::session();
		
		$db = \Config\Database::connect();

		$result = '';
		if ($request->getPost()) {
			$session->set('response_status', 'warning');
			$session->set('message', $result);
			redirect($_SERVER['HTTP_REFERER']);
		} else {
			//echo 10;die;
			$template->title(lang('hd_lang.orders') . ' - ' . $custom_helper->getconfig_item('company_name'));
			$data['page'] = lang('hd_lang.orders');
			$data['datepicker'] = true;
			$data['form'] = true;
			// $this->template
			// ->set_layout('users')
			// ->build($this->custom_helper->getconfig_item('active_theme').'/views/pages/add_order',isset($data) ? $data : NULL);
			if (User::is_admin()) { 
				$user = User::view_user($session->get('userid'));

				$client = $db->table('hd_companies')->where('primary_contact', $session->get('userid'))->get()->getRow();

				$user_data = [
					'user_id' => $user->id,
					'username' => $user->username,
					'role_id' => $user->role_id,
					'status' => ($user->activated == 1) ? STATUS_ACTIVATED : STATUS_NOT_ACTIVATED,
					'client_id' => $client->co_id
				];

				$session->set('userdata', $user_data);
				return view('modules/orders/add_order', $data);
			} else {
				//echo 12;die;
				$user = User::view_user($session->get('userdata.user_id'));

				$client = $db->table('hd_companies')->where('primary_contact', $session->get('userdata.user_id'))->get()->getRow();

				$user_data = [
					'user_id' => $user->id,
					'username' => $user->username,
					'role_id' => $user->role_id,
					'status' => ($user->activated == 1) ? STATUS_ACTIVATED : STATUS_NOT_ACTIVATED,
					'client_id' => $client->co_id
				];

				$session->set('userdata', $user_data);
				return view('modules/orders/add_order', $data);
			}
		}
	}


	private function process_upgrade($o_id)
	{
		$custom_helper = new custom_name_helper();
		$order =  $this->db->select('*')->from('orders')->join('items_saved', 'orders.item_parent = items_saved.item_id', 'inner')->where('o_id', $o_id)->get()->row();
		$domain =  $this->db->select('*')->from('orders')->where('id', $o_id)->get()->row();
		$package = $this->db->where(array('item_id' => $order->item_parent))->get('items_saved')->row();

		if ($order->renewal == 'annually') {
			$process_id = $domain->process_id;
		} else {
			$process_id = time();
		}

		$update = array(
			"status_id" => 6,
			"order_id" => $domain->order_id,
			"process_id" => $process_id,
			"o_id" => 0
		);

		$this->db->where('o_id', $o_id);
		if ($this->db->update('orders', $update)) {
			$result = "Order updated. <br>";

			$activity = array(
				'user' => User::get_id(),
				'module' => 'accounts',
				'module_field_id' => $order,
				'activity' => 'activity_activate_upgrade',
				'icon' => 'fa-plus',
				'value1' => $order->invoice_id
			);

			App::Log($activity);

			$update_item = array(
				"item_name" => $order->item_name
			);

			$this->db->where('item_id', $order->item);
			$this->db->update('items', $update_item);

			$this->db->where('id', $o_id);
			$this->db->delete('orders');

			if ($order->server != null && $custom_helper->getconfig_item('demo_mode') != 'TRUE') {

				$client = Client::view_by_id($order->client_id);
				$user = User::view_user($client->primary_contact);
				$profile = User::profile_info($client->primary_contact);
				$server = Order::get_server($order->server);

				$details = (object) array(
					'user' => $user,
					'profile' => $profile,
					'client' => $client,
					'account' => $account,
					'package' => $package,
					'server' => $server
				);

				$details = array('server' => $server, 'account' => $order);
				$result = modules::run($server->type . '/change_package', $details);

				$activity = array(
					'user' => User::get_id(),
					'module' => 'accounts',
					'module_field_id' => $order,
					'activity' => $result,
					'icon' => 'fa-plus',
					'value1' => $order->invoice_id
				);

				App::Log($activity);
			}

			$this->session->set_flashdata('response_status', 'warning');
			$this->session->set_flashdata('message', $result);

			$from = $_SERVER['HTTP_REFERER'];
			$segments = explode('/', $from);

			if ($segments[3] == 'invoices') {
				redirect('accounts');
			} else {
				redirect($_SERVER['HTTP_REFERER']);
			}
		}
	}



	static function process($id)
	{
		$custom_helper = new custom_name_helper();
		$ci = &get_instance();
		$item = $ci->db->where('invoice_id', $id)->get('items')->result();
		if ($item[0]->item_name == lang('hd_lang.add_funds')) {
			$payment = Payment::by_invoice($id);
			$amount = $payment[0]->amount;

			$client = Client::view_by_id(Invoice::view_by_id($id)->client);
			$credit = $client->transaction_value;
			$bal = $credit + $amount;

			$balance = array(
				'transaction_value' => Applib::format_deci($bal)
			);

			$ci->db->where('co_id', $client->co_id)->update('companies', $balance);
			return true;
		}

		$ci->db->select('*');
		$ci->db->from('orders');
		$ci->db->join('items_saved', 'orders.item_parent = items_saved.item_id', 'LEFT');
		$ci->db->join('invoices', 'orders.invoice_id = invoices.inv_id', 'inner');
		$ci->db->where('inv_id', $id);
		$accounts = $ci->db->get()->result();

		foreach ($accounts as $acc) {
			$referral = $ci->db->where('order_id', $acc->id)->get('referrals')->row();
			if (is_object($referral)) {
				$affiliate = $ci->db->where('affiliate_id', $referral->affiliate_id)->get('companies')->row();
				$balance = $affiliate->affiliate_balance + $referral->commission;

				$aff_data = array(
					'affiliate_balance' => $balance
				);
				$ci->db->where('affiliate_id', $referral->affiliate_id);
				$ci->db->update('companies', $aff_data);
			}
		}

		if (
			$custom_helper->getconfig_item('automatic_activation') == 'TRUE' &&
			$ci->db->where('invoice_id', $id)->where('status_id', 5)->get('orders')->num_rows() > 0
		) {

			if (count($accounts) == 1 && $accounts[0]->o_id > 0) {
				$order = $accounts[0];
				$o_id = $accounts[0]->o_id;

				$domain =  $ci->db->select('*')->from('orders')->where('id', $o_id)->get()->row();
				$package = $ci->db->where(array('item_id' => $order->item_parent))->get('items_saved')->row();

				if ($order->renewal == 'annually') {
					$process_id = $domain->process_id;
				} else {
					$process_id = time();
				}

				$update = array(
					"status_id" => 6,
					"order_id" => $domain->order_id,
					"process_id" => $process_id,
					"o_id" => 0
				);

				$ci->db->where('o_id', $o_id);
				if ($ci->db->update('orders', $update)) {
					$result = "Order updated. <br>";

					$activity = array(
						'user' => User::get_id(),
						'module' => 'accounts',
						'module_field_id' => $order->id,
						'activity' => 'activity_activate_upgrade',
						'icon' => 'fa-plus',
						'value1' => $order->invoice_id
					);

					App::Log($activity);

					$update_item = array(
						"item_name" => $order->item_name
					);

					$ci->db->where('item_id', $order->item);
					if ($ci->db->update('items', $update_item)) {
						App::delete('orders', array('id' => $o_id));
						if ($order->server != null && $custom_helper->getconfig_item('demo_mode') != 'TRUE') {

							$client = Client::view_by_id($order->client_id);
							$user = User::view_user($client->primary_contact);
							$profile = User::profile_info($client->primary_contact);
							$server = Order::get_server($order->server);

							$details = (object) array(
								'user' => $user,
								'profile' => $profile,
								'client' => $client,
								'account' => $order,
								'package' => $package,
								'server' => $server
							);

							$result = modules::run($server->type . '/change_package', $details);

							$activity = array(
								'user' => User::get_id(),
								'module' => 'accounts',
								'module_field_id' => $order->id,
								'activity' => $result,
								'icon' => 'fa-plus',
								'value1' => $order->invoice_id
							);

							App::Log($activity);
						}
					}
				}
			} else {

				foreach ($accounts as $account) {

					$client =  Client::view_by_id($account->client_id);
					$user = User::view_user($client->primary_contact);
					$profile = User::profile_info($client->primary_contact);

					if ($account->type == 'hosting') {

						$update = array(
							"status_id" => 6,
							"server" => (null != $account->server && $account->server > 0 && $account->server != '') ? $account->server : $ci->server
						);

						$ci->db->where('id', $account->id);
						if ($ci->db->update('orders', $update)) {

							$data = array(
								'user' => $account->client_id,
								'module' => 'accounts',
								'module_field_id' => $account->id,
								'activity' => 'activity_account_activated',
								'icon' => 'fa-usd',
								'value1' => $account->reference_no,
								'value2' => $account->inv_id
							);
							App::Log($data);
						}

						Order::send_account_details($account->id);

						$server = $ci->db->where('id', $account->server)->get('servers')->row();

						if (!$server && !empty($ci->server)) {
							$server = $ci->db->where('id', $ci->server)->get('servers')->row();
						}

						if ($server && $custom_helper->getconfig_item('demo_mode') != 'TRUE') {
							$package = $ci->db->where(array('item_id' => $account->item_parent))->get('items_saved')->row();

							$details = (object) array(
								'user' => $user,
								'profile' => $profile,
								'client' => $client,
								'account' => $account,
								'package' => $package,
								'server' => $server
							);

							modules::run($server->type . '/create_account', $details);

							$data = array(
								'user' => $account->client_id,
								'module' => 'accounts',
								'module_field_id' => $account->id,
								'activity' => 'activity_cpanel_creation',
								'icon' => 'fa-usd',
								'value1' => $result,
								'value2' => $account->inv_id
							);
						}

						App::Log($data);
					}


					if ($account->type == 'domain' || $account->type == 'domain_only') {
						$registrar = '';

						if (empty($account->registrar)) {
							$item = $ci->db->where('item_id', $account->item_parent)->get('items_saved')->row();
							$item->default_registrar;

							$ci->db->set('status_id', 6);
							$ci->db->set('registrar', $registrar);
							$ci->db->where('id', $account->id);
							$ci->db->update('orders');
						} else {
							$registrar = $account->registrar;
						}

						if (!empty($registrar)) {

							$process = $account->domain . " activated!";

							if (Plugin::get_plugin($registrar)) {

								$action = '/register_domain';

								$nameservers = Order::get_nameservers($account->id);

								if ($nameservers != '') {
									$nameservers = explode(",", $nameservers);
								} else {
									$nameservers = array();
									if ($custom_helper->getconfig_item('nameserver_one') != '') {
										$nameservers[] = $custom_helper->getconfig_item('nameserver_one');
									}
									if ($custom_helper->getconfig_item('nameserver_two') != '') {
										$nameservers[] = $custom_helper->getconfig_item('nameserver_two');
									}
									if ($custom_helper->getconfig_item('nameserver_three') != '') {
										$nameservers[] = $custom_helper->getconfig_item('nameserver_three');
									}
									if ($custom_helper->getconfig_item('nameserver_four') != '') {
										$nameservers[] = $custom_helper->getconfig_item('nameserver_four');
									}
									if ($custom_helper->getconfig_item('nameserver_five') != '') {
										$nameservers[] = $custom_helper->getconfig_item('nameserver_five');
									}
								}


								if ($account->item_name == lang('hd_lang.domain_renewal')) {
									$action = '/renew_domain';
								}
								if ($account->item_name == lang('hd_lang.domain_transfer')) {
									$action = '/transfer_domain';
								}
								$process .= modules::run($registrar . $action, $account->id, $nameservers);
							}

							$data = array(
								'user' => $account->client_id,
								'module' => 'accounts',
								'module_field_id' => $account->id,
								'activity' => $process,
								'icon' => 'fa-usd',
								'value1' =>  $account->domain,
								'value2' => ''
							);
							App::Log($data); // Log activity
						}
					}
				}
			}
		} else {
			$ci->db->join('items', 'items.item_id = orders.item');
			$account = $ci->db->where(array('status_id' => 9, 'items.invoice_id' => $id))->get('orders')->row();
			if (isset($account)) {
				$ci->db->where('id', $account->id);
				if ($ci->db->update('orders', array("status_id" => 6))) {
					if ($custom_helper->getconfig_item('automatic_email_on_recur') == 'TRUE') {
						send_email($id, 'service_unsuspended', $account);
					}

					if (
						$custom_helper->getconfig_item('sms_gateway') == 'TRUE' &&
						$custom_helper->getconfig_item('sms_service_unsuspended') == 'TRUE'
					) {
						send_message($id, 'service_unsuspended');
					}
				}
			}
		}
	}
	
	function status($order_id = NULL, $status = NULL)
	{
		$db = \Config\Database::connect();
		
		if (isset($status)) {
			
			$order_details = $db->table('hd_orders')->where('order_id', $order_id)->get()->getRow();
			
			$data = array('order_status_id' => $status);
			$db->table('hd_orders')->where('order_id', $order_id)->update($data);


			$data = array(
				'module' => 'orders',
				'module_field_id' => $order_details->order_id,
				'user' => User::get_id(),
				'activity' => 'activity_order_status_changed',
				'icon' => 'fa-ticket',
				'value1' => $order_details->order_id,
				'value2' => ''
			);
			App::Log($data);
			// Applib::go_to('tickets/view/' . $ticket, 'success', lang('hd_lang.ticket_status_changed'));
			return redirect()->to('orders/view/' . $order_id);

		}
	}
}
 

/* End of file orders.php */