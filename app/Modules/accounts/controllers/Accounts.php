<?php
 /*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

namespace App\Modules\accounts\Controllers;

use App\Libraries\AppLib;
use App\Models\App;
use App\Models\AccountDetail;
use App\Models\Client;
use App\Models\Domain;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\Order;
use App\Models\Orders;
use App\Models\User;
use App\Modules\Layouts\Libraries\Template;
use App\ThirdParty\MX\WhatPanel;
use App\ThirdParty\MX\Modules;
use App\Helpers\AuthHelper;
use Exception;
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
use PHPExcel_IOFactory;
use PhpOffice\PhpSpreadsheet\IOFactory;


use App\Helpers\custom_name_helper;


class Accounts extends WhatPanel{

	protected $filter_by;
	protected $userModel;
	protected $domainModel;

	function __construct()
		{
			// parent::__construct();
			// User::logged_in();

			// $this->load->module('layouts');
			// $this->load->library('template'); 
			$this->filter_by = $this->_filter_by();
			
			$session = \Config\Services::session();
            
            // Connect to the database
            $dbName = \Config\Database::connect();

			$this->userModel = new User($dbName);
			$this->domainModel = new Domain($dbName);
		}
 


	function index() 
	{
		$request = \Config\Services::request();
		
		$template = new Template();
		
		$custom_helper = new custom_name_helper();

		$type = "hd_orders.type ='hosting'";

		//$array = $this->filter_by($this->filter_by);

		$session = \Config\Services::session();
		
		$domain = new AccountDetail();
		$accountsModel = new Orders();

		$user_id = $session->get('userid');
		
		//if(AuthHelper::is_admin() || $this->userModel::perm_allowed(User::get_id(),'manage_accounts')){
		/* if(User::is_admin()){
			$data['accounts'] = Domain::by_where($array, $type);
		}else{
			$array['client_id'] = User::profile_info($user_id)->company;
			$data['accounts'] = Domain::by_where($array, $type);
		} */
		
		$template->title(lang('hd_lang.accounts').' - '.$custom_helper->getconfig_item('company_name'));
		$data['page'] = lang('hd_lang.accounts');
		$data['datatables'] = TRUE;
		
		// Pagination Configuration
		$page = $request->getGet('page') ? $request->getGet('page') : 1;

        $perPage = $request->getGet('recordsPerPage', FILTER_SANITIZE_NUMBER_INT) ? $request->getGet('recordsPerPage', FILTER_SANITIZE_NUMBER_INT) : 10;

		// Search Filter
		$search = $request->getGet('search');
		$data['search'] = $search;

        // $query = $domain->listItems([], $search, $perPage, $page);
		if(User::is_admin()){
			// $data['accounts'] = Domain::by_where($array, $type);
			$query = $accountsModel->listItems([], $search, $perPage, $page, $type);
		}else{
			$user_id = $session->get('userdata')['user_id'];
			$array['client_id'] = User::profile_info($user_id)->company;
			// $data['accounts'] = Domain::by_where($array, $type);
			$query = $accountsModel->listItems($array, $search, $perPage, $page, $type);
		}
		
		//echo "<pre>";print_r($query);die;

        // Get items for the current page
		$data['accounts'] = array_map(function($item) {
			return (object) $item;
		}, $query['items']);

        $data['pager'] = $query['pager'];

		$data['message'] = $query['message'];

		$data['perPage'] = $perPage;
			
		//print_r(session()->get());die;
		return view('modules/accounts/accounts', $data);
	}



	function _filter_by()
	{
		$filter = isset($_GET['view']) ? $_GET['view'] : '';
		return $filter;
	}



	function filter_by($filter_by) 
	{
		switch ($filter_by) {
            
			case 'pending':
			return array('status_id' => 5);
			break;

			case 'active':
			return array('status_id' => 6);
			break;

			case 'cancelled':
			return array('status_id' => 7);
			break;

			case 'suspended':
			return array('status_id' => 9);
			break;
			
			default:
			return array('status_id <>' => 8, 'status_id <>' => 2);
			break;
		}
	}


	public function activate($id = null)
	{	
		$session = \Config\Services::session(); 
            
        // Connect to the database  
        $db = \Config\Database::connect();

		$model = new App();

		//$model->module_access('menu_accounts');
		$request = \Config\Services::request(); 
		if ($request->getPost()) {
			// echo 1;die;
			
				$result = ""; 

				$id = $request->getPost('id'); 		
				$account = Order::get_order($id); 		
		 
				$client = Client::view_by_id($account->client_id); 
				$user = User::view_user($client->primary_contact);
				$profile = User::profile_info($client->primary_contact);
 
				if ($request->getPost('server') != '') {
					$server = $db->table('hd_servers')->where(array('id'=> $request->getPost('server')))->get()->getRow();
				} 

				else{				
					$server = $db->table('hd_servers')->where(array('selected'=> 1))->get()->getRow();			
				}  

				//echo "<pre>";print_r($server);die;
	
				$update = array(
					"status_id" => 6,
					"username" => $request->getPost('username'),
					"password" => $request->getPost('password')
				);

				if($server) {
					$update['server'] = $server->id;
				}


				if($db->table('hd_orders')->where('id', $id)->update($update)) {
					$result .= lang('hd_lang.account_activated')."<br>";

					$data = array('inv_deleted'=> 'No');
					$db->table('hd_invoices')->where('inv_id', $account->invoice_id)->update($data);
				} 
				

				if($request->getPost('send_details') == 'on') {
					$model = new Order($db);
					$model->send_account_details($id);
				}

				if($request->getPost('create_controlpanel') == 'on') {
				
					$account = Order::get_order($id);
					$package = $db->table('hd_items_saved')->where(array('item_id'=> $account->item_parent))->get()->getRow();

					$details = (object) array(
						'user' => $user, 
						'profile' => $profile, 
						'client' => $client, 
						'account' => $account,
						'package' => $package,
						'server' => $server
					);
					//echo"<pre>";print_r($details);die;
					//echo $server->type;die;
					
						switch ($server->type) {
							case 'plesk':
								// $plesk = new Plesk();
								$plesk = new Plesk_WHMCS();
								// $configuration = $plesk->create_account($details);
								$configuration = $plesk->plesk_CreateAccount($details);
							break;
							case 'ispconfig':
								$ispconfig = new Ispconfig();
								$configuration = $ispconfig->create_account($details);
							break;
							case 'directadmin':
								$directadmin = new Directadmin();
								$configuration = $directadmin->create_account($details);
							break;
							case 'cyberpanel':
								$cyberpanel = new Cyberpanel();
								$configuration = $cyberpanel->create_account($details);
							break;
							case 'cwp':
								$cwp = new Cwp();
								$configuration = $cwp->create_account($details);
							break;
							case 'cpanel':
								$cpanel = new new_Cpanel();
								$configuration = $cpanel->cpanel_CreateAccount($details);
							break;
						}
					// $result .= service('controller')->$server->type->create_account($details);
					//echo "<pre>";print_r($configuration);die;

				}
  
				session()->setFlashdata('response_status', 'info');
                session()->setFlashdata('message', $result);				
                //redirect($_SERVER['HTTP_REFERER']);
		}
		else {
			$data['item'] = Order::get_order($id); 
			$data['servers'] = $db->table('hd_servers')->get()->getResult();
			// $this->load->view('modal/activate', $data);
			echo view('modules/accounts/modal/activate', $data);
		}
	}


	function cancel($id = null)
	{
		$session = \Config\Services::session();

		$custom = new custom_name_helper();

        // Connect to the database  
        $db = \Config\Database::connect();

		//$model->module_access('menu_accounts');
		$request = \Config\Services::request();

		//App::module_access('menu_accounts');
		if ($request->getPost()) {

			if($request->getPost('credit_account') == 'on')
			{
				Invoice::credit_item($request->getPost('id'));
			}

			$result = "";			 
			$id = $request->getPost('id'); 		
			$account = Order::get_order($id);

			if($db->table('hs_orders')->where('id', $id)->update(['status_id' => 7])) {

				if($custom->getconfig_item('demo_mode') != 'TRUE') {

					$package = $db->table('hd_items_saved')->where(array('item_id'=> $account->item_parent))->get()->getRow();						
					
					if($request->getPost('delete_controlpanel') == 'on') {

						$server = Order::get_server($account->server);
						$client = Client::view_by_id($account->client_id); 
						$user = User::view_user($client->primary_contact);
						$details = (object) array('account' => $account, 'server' => $server, 'package' => $package, 'client' => $client, 'user' => $user);

						//$result .= modules::run($server->type.'/terminate_account', $details);

						switch ($server->type) {
							case 'plesk':
								$plesk = new Plesk();
								$result .= $plesk->terminate_account($details);
							break;
							case 'ispconfig':
								$ispconfig = new Ispconfig();
								$result .= $ispconfig->terminate_account($details);
							break;
							case 'directadmin':
								$directadmin = new Directadmin();
								$result .= $directadmin->terminate_account($details);
							break;
							case 'cyberpanel':
								$cyberpanel = new Cyberpanel();
								$result .= $cyberpanel->terminate_account($details);
							break;
							case 'cwp':
								$cwp = new Cwp();
								$result .= $cwp->terminate_account($details);
							break;
							case 'cpanel':
								$cpanel = new Cpanel();
								$result .= $cpanel->terminate_account($details);
							break;
						}

						$db->table('hd_orders')->where('id', $id)->update(['server' => '']);

					}						
				}								
			}

			$db->table('hd_invoices')->where('inv_id', $request->getPost('inv_id'))->update(['inv_deleted' => 'Yes']);
			
			$session->setFlashdata('response_status', 'info');
			$session->setFlashdata('message', $result);
			return redirect()->to('accounts');
		}

		else {
			$data['item'] = Order::get_order($id); 
			$data['servers'] = $db->table('hd_servers')->get()->getResult();
			//$this->load->view('modal/cancel', $data);
			echo view('modules/accounts/modal/cancel', $data);
		}
	}


	function delete($id = null)
	{
		//App::module_access('menu_accounts');

		$session = \Config\Services::session();

		$custom = new custom_name_helper();

        // Connect to the database  
        $db = \Config\Database::connect();

		//$model->module_access('menu_accounts');
		$request = \Config\Services::request();

		if ($request->getPost()) {

			if($request->getPost('credit_account') == 'on')
			{
				Invoice::credit_item($request->getPost('id'));
			}			

			$result = "";			
			$id = $request->getPost('id'); 		
			$account = Order::get_order($id); 
			$terminate = false; 

			if($db->table('hd_orders')->where('order_id', $account->order_id)->countAllResults() == 1) {

				$result = $db->table('orders')
					->where('id', $id)
					->delete();

				if($result) { 
					$terminate = true; 
					//Invoice::delete($account->invoice_id);
					$db->table('hd_invoices')->where('id', $account->invoice_id)->delete();
				}
			}

			else {
				$result = $db->table('orders')
					->where('id', $id)
					->delete();

				if($result) { 
					$terminate = true;
				}
			} 
			
			
			if($terminate){
		
				if($custom->getconfig_item('demo_mode') != 'TRUE') {					
					
					$package = $db->table('hd_items_saved')->where(array('item_id'=> $account->item_parent))->get()->getRow();						
					
					if($request->getPost('delete_controlpanel') == 'on') {

						$server = Order::get_server($account->server);
						$client = Client::view_by_id($account->client_id); 
						$user = User::view_user($client->primary_contact);
						
						$details = (object) array('account' => $account, 'server' => $server, 'package' => $package, 'client' => $client, 'user' => $user);

						//$result .= modules::run($server->type.'/terminate_account', $details);
						
						switch ($server->type) {
							case 'plesk':
								$plesk = new Plesk();
								$result .= $plesk->terminate_account($details);
							break;
							case 'ispconfig':
								$ispconfig = new Ispconfig();
								$result .= $ispconfig->terminate_account($details);
							break;
							case 'directadmin':
								$directadmin = new Directadmin();
								$result .= $directadmin->terminate_account($details);
							break;
							case 'cyberpanel':
								$cyberpanel = new Cyberpanel();
								$result .= $cyberpanel->terminate_account($details);
							break;
							case 'cwp':
								$cwp = new Cwp();
								$result .= $cwp->terminate_account($details);
							break;
							case 'cpanel':
								$cpanel = new Cpanel();
								$result .= $cpanel->terminate_account($details);
							break;
						}
					}
				}									
			}
						
	
			$session->setFlashdata('response_status', 'info');
			$session->setFlashdata('message', $result);
			return redirect()->to('accounts');
		}

		else {
			$data['item'] = Order::get_order($id); 
			$data['servers'] = $db->table('hd_servers')->get()->getResult();
			// $this->load->view('modal/delete', $data);
			echo view('modules/accounts/modal/delete', $data);
		}
	}

	function suspend($id = null, $invoice_id = null)
	{	
		//echo 78;die;
		error_reporting(E_ALL);
        ini_set("display_errors", "1");
		//App::module_access('menu_accounts');
		$session = \Config\Services::session();

		$custom = new custom_name_helper();

        // Connect to the database  
        $db = \Config\Database::connect();

		//$model->module_access('menu_accounts');
		$request = \Config\Services::request();

		if ($request->getPost()) {
			$account = Order::get_order($request->getPost('id'));
			//echo"<pre>";print_r($account->invoice_id);die;
			$reason = $request->getPost('reason');			
			$result = "";
			if($db->table('hd_orders')->where('id', $request->getPost('id'))->update(['status_id' => 9])) {
				$result .=  $account->domain." has been suspended.<br>";
					
					$invoice_details = $db->table('hd_invoices')->where('inv_id', $account->invoice_id)->get()->getRow();
					$order_details = $db->table('hd_orders')->where('invoice_id', $account->invoice_id)->get()->getRow();

					$order_details = $db->table('hd_orders')->where('invoice_id', $invoice_details->inv_id)->get()->getRow();
					$package = $db->table('hd_items_saved')->where(array('item_id'=> $account->item_parent))->get()->getrow();
					$client = Client::view_by_id($account->client_id); 
					$user = User::view_user($client->primary_contact);
					$profile = User::profile_info($client->primary_contact);
					$server = $db->table('hd_servers')->where(array('type'=> $account->server))->get()->getrow();
					//print_r($account->server);die;
					$details = (object) array(
						'user' => $user, 
						'profile' => $profile, 
						'client' => $client, 
						'account' => $account,
						'package' => $package,
						'server' => $server,
						'reason' => $reason
					); 
				
				//$result .= modules::run($account->server_type.'/suspend_account', $details);
				
				//echo"<pre>";print_r($account);die;
				switch ($account->server) {
					case 'plesk':
						$resultAcc = $db->table('hd_plesk_account')->where(array('order_id' => $order_details->order_id, 'invoice_id' => $invoice_details->inv_id))->get()->getRow();
						$plesk = new Plesk_WHMCS();
						$result = $plesk->plesk_SuspendAccount($details, $resultAcc->plesk_user_id);
					break;
					case 'ispconfig':
						$ispconfig = new Ispconfig();
						$result .= $ispconfig->suspend_account($details);
					break;
					case 'directadmin':
						$directadmin = new Directadmin();
						$result .= $directadmin->suspend_account($details);
					break;
					case 'cyberpanel':
						$cyberpanel = new Cyberpanel();
						$result .= $cyberpanel->suspend_account($details);
					break;
					case 'cwp':
						$cwp = new Cwp();
						$result .= $cwp->suspend_account($details);
					break;
					case 'cpanel':
						$results = $db->table('hd_cpanel_account')->where(array('order_id' => $order_details->order_id, 'invoice_id' => $invoice_details->inv_id))->get()->getRow();
						//echo"<pre>";print_r($db->getLastQuery());die;
						$cpanel = new new_Cpanel();
						$result .= $cpanel->cpanel_SuspendAccount($details, $results);
					break;
				}
			} 
			//echo "<pre>";print_r($configuration);die;
			$session->setFlashdata('response_status', 'warning');
			$session->setFlashdata('message', $result);
			return redirect()->to('accounts');
		}

		else {
			$data['id'] = $id; 
			$data['servers'] = $db->table('hd_servers')->get()->getResult();
			// $this->load->view('modal/suspend', $data);
			echo view('modules/accounts/modal/suspend', $data);
		}
	}


	function unsuspend($id = null, $invoice_id = null)
	{	
		error_reporting(E_ALL);
			ini_set("display_errors", "1");
		//App::module_access('menu_accounts');

		$session = \Config\Services::session();

		$custom = new custom_name_helper();

        // Connect to the database  
        $db = \Config\Database::connect();

		//$model->module_access('menu_accounts');
		$request = \Config\Services::request();

		if ($request->getPost()) {
			$account = Order::get_order($request->getPost('id')); 
			$result = "";
			if($db->table('hd_orders')->where('id', $request->getPost('id'))->update(['status_id' => 9])) {
				$result .=  $account->domain." has been unsuspended.<br>";	
				

				$invoice_details = $db->table('hd_invoices')->where('inv_id', $account->invoice_id)->get()->getRow();
				
				$order_details = $db->table('hd_orders')->where('invoice_id', $account->invoice_id)->get()->getRow();
	
				$order_details = $db->table('hd_orders')->where('invoice_id', $invoice_details->inv_id)->get()->getRow();

				$package = $db->table('hd_items_saved')->where(array('item_id'=> $account->item_parent))->get()->getRow();
				$client = Client::view_by_id($account->client_id); 
				$user = User::view_user($client->primary_contact);
				$profile = User::profile_info($client->primary_contact);
				//$server = Order::get_server($account->server);
				$server = $db->table('hd_servers')->where(array('type'=> $account->server))->get()->getrow();
				
				$details = (object) array(
					'user' => $user, 
					'profile' => $profile, 
					'client' => $client, 
					'account' => $account,
					'package' => $package,
					'server' => $server
				); 
				//echo"<pre>";print_r($details);die;
				//  $result .= modules::run($account->server_type.'/unsuspend_account', $details); 
				//print_r($account->server);die;
				switch ($account->server) {
					case 'plesk':
						$resultAcc = $db->table('hd_plesk_account')->where(array('order_id' => $order_details->order_id, 'invoice_id' => $invoice_details->inv_id))->get()->getRow();
						$plesk = new Plesk_WHMCS();
						$result = $plesk->plesk_UnSuspendAccount($details, $resultAcc->plesk_user_id);
					break;
					case 'ispconfig':
						$ispconfig = new Ispconfig();
						$result .= $ispconfig->unsuspend_account($details);
					break;
					case 'directadmin':
						$directadmin = new Directadmin();
						$result .= $directadmin->unsuspend_account($details);
					break;
					case 'cyberpanel':
						$cyberpanel = new Cyberpanel();
						$result .= $cyberpanel->unsuspend_account($details);
					break;
					case 'cwp':
						$cwp = new Cwp();
						$result .= $cwp->unsuspend_account($details);
					break;
					case 'cpanel':
						$results = $db->table('hd_cpanel_account')->where(array('order_id' => $order_details->order_id, 'invoice_id' => $invoice_details->inv_id))->get()->getRow();
						$cpanel = new new_Cpanel();
						$result .= $cpanel->cpanel_UnsuspendAccount($details, $results);
					break;
				}
				$db->table('hd_orders')->where('id', $order_details->id)->update(['status_id' => 6]);
			} 

			$session->setFlashdata('response_status', 'warning');
			$session->setFlashdata('message', $result);
			return redirect()->to('accounts');
		}

		else {
			$data['id'] = $id; 
			$data['servers'] = $db->table('hd_servers')->get()->getResult();
			// $this->load->view('modal/unsuspend', $data);
			echo view('modules/accounts/modal/unsuspend', $data);
		}
	}
 


	function change()
	{
		$id = $_GET['plan'];
		$current = Item::view_item($id);
		$parent = $current->parent;
		$this->session->set_userdata('account_id', $_GET['account']);
		$this->db->select('items_saved.item_id, item_name, item_features, monthly, quarterly, semi_annually, annually'); 
		$this->db->from('items_saved');  
		$this->db->join('item_pricing','items_saved.item_id = item_pricing.item_id','INNER');
		$this->db->join('categories','categories.id = item_pricing.category','LEFT');
		$this->db->where('deleted', 'No');
		$this->db->where('display', 'Yes');  
		$this->db->where('category', $current->category); 
		$this->db->where('items_saved.item_id <>', $id); 
		$data['packages'] = $this->db->get()->result(); 
		$this->load->view('modal/change', $data);		
	}



	function show_options($id)
	{	 
		$this->session->set_userdata('item_id', $id);
		$data['current'] =  $this->session->userdata('account_id'); 
		$data['options'] =  $id;
		$this->template->title(lang('hd_lang.review').' - '.config_item('company_name'));
		$data['page'] = lang('hd_lang.options');			
		$this->template
		->set_layout('users')
		->build('options', $data);	
	}




	function manage($id = null)
	{
		if(User::is_admin()) {

			$request = \Config\Services::request();

			$session = \Config\Services::session();

			$db = \Config\Database::connect();

			if($request->getPost()) { 

				$details = $request->getPost();
				$details['processed'] = explode(' ', $request->getPost('date'))[0];
				
				if($db->table('hd_orders')->where(array('id' => $request->getPost('id')))->update($details))
				{
					$session->setFlashdata('success', lang('hd_lang.account_updated'));
					return redirect()->to('accounts/account/'. $request->getPost('id'));
				}
				else {
					//redirect($_SERVER['HTTP_REFERER']);
					return redirect()->to('accounts/manage/'. $request->getPost('id'));
				}
			}

			else {				
				//$this->template->title(lang('hd_lang.account').' - '.config_item('company_name'));
				$data['account'] = array();
				$data['account_details'] = true;
				$data['page'] = lang('hd_lang.account');
				$data['datepicker'] = true;
				$data['form'] = true;
				$data['id'] = $id;

				// $this->template
				// ->set_layout('users')
				// ->build('manage', $data);

				echo view('modules/accounts/manage', $data);
			}
		}
		else {
			redirect(base_url()."accounts");
		}
	}



	function account($id)
	{	
		$custom_name = new custom_name_helper();

		$order = Order::get_order($id);
		
		$model = new Client();
		$client = $model->get_by_user(User::get_id());
		
		if(User::is_admin() || (isset($client) && $client->co_id == $order->client_id )){
			
			$template = new Template();
			$template->title(lang('hd_lang.account').' - '.$custom_name->getconfig_item('company_name'));
			
			$data['account_details'] = true;
			$data['page'] = lang('hd_lang.account');
			$data['id'] = $id;
			// $this->template
			// ->set_layout('users')
			// ->build('account', $data);
			return view('modules/accounts/account', $data);
		}
		else {
			redirect(base_url()."accounts");
		}
	}



	function review()
	{	 	 
		$data['renewal'] = $this->input->post('renewal');
		$data['renewal_date'] = $this->input->post('next_due');
		$data['payable'] = $this->input->post('payable');
		$data['amount'] = $this->input->post('amount'); 
		$data['item'] = $this->session->userdata('item_id');
		$data['account'] = $this->session->userdata('account_id');

		$upgrade = array(
			'renewal' => $data['renewal'],
			'renewal_date' => $data['renewal_date'],  
			'account' => $data['account'],
			'amount' => $data['amount'],
			'item' => $this->session->userdata('item_id'),
			'payable' => $data['payable']
		);
 
		$this->session->set_userdata('upgrade', $upgrade);
		$this->template->title(lang('hd_lang.review').' - '.config_item('company_name'));

		$data['page'] = lang('hd_lang.review');			
		$this->template
		->set_layout('users')
		->build('review', $data);
	}



	function view_logins($id = null)
	{ 
		$db = \Config\Database::connect();

		$data['item'] = Order::get_order($id); 
		$data['servers'] = $db->table('hd_servers')->get()->getResult();
		echo view('modules/accounts/modal/view_logins', $data);
	}



	function change_password($id = null)
	{
		Applib::is_demo();
		// App::module_access('menu_accounts');

		$request = \Config\Services::request();

		if ($request->getPost()) {
			$account = Order::get_order($request->getPost('id'));
			$password = $request->getPost('password');

			$update = array(
				"password" => $password
			); 
			
			$this->db->where('id', $this->input->post('id'));  
			if($this->db->update('orders', $update)) {

				$account = Order::get_order($this->input->post('id'));
				$server = Order::get_server($account->server);
				$client = Client::view_by_id($account->client_id); 
				$user = User::view_user($client->primary_contact);
				
				$details = (object) array( 
					'account' => $account, 
					'server' => $server,
					'user' => $user
				); 

				 $result = modules::run($account->server_type.'/change_password', $details);  
			} 

			$this->session->set_flashdata('response_status', 'info');
			$this->session->set_flashdata('message', $result);
			redirect($_SERVER['HTTP_REFERER']);
		}

		else {
			$data['id'] = $id;
			echo view('modules/accounts/modal/change_password', $data);
		}
	}




	function login($id) 
	{	Applib::is_demo();
		$order = Order::get_order($id);
		$client = Client::get_by_user(User::get_id());
		if(User::is_admin() || User::perm_allowed(User::get_id(),'manage_accounts') || (isset($client) && $client->co_id == $order->client_id ))
		{
			$account = Order::get_order($id);
			$server = Order::get_server($account->server);
			$params = (object) array('account' => $account, 'server' => $server);

			modules::run($server->type.'/client_login', $params);  
		}
		
		else {
			redirect(base_url());
		}
	}



	public function import_accounts()
	{
        $count = 0;

		if($this->input->post()) 
		{
           $array = array();
           $list = array();       

		   foreach($this->input->post() as $k => $r)
           {
			   if($k != 'package')
			   {
					$array[] = $k;
			   }                
           }

           $accounts = $this->session->userdata('import_accounts');
           foreach($accounts as $k => $r)
           {
                if(in_array($r->id, $array))
                {
                    $list[] = $r;
                }
           }
 

			if(count($list) > 0) 
			{
                foreach($list as $client) 
	  
                {   
					$package = $this->input->post('package');
					if($package[$client->id] > 0)
					{  
						if($this->db->where('co_id', $client->user_id)->where('imported', 1)->get('companies')->num_rows() > 0)
						{ 
							$item = $this->db->where('items_saved.item_id', $package[$client->id])->join('item_pricing', 'item_pricing.item_id = items_saved.item_id')->get('items_saved')->row();
				
							switch($client->status)
							{
								case 'Active' : $status = 6; 
								break;

								case 'Cancelled' : $status = 7; 
								break;

								case 'Terminated' : $status = 8; 
								break;

								case 'Pending' : $status = 5; 
								break;

								case 'Suspended' : $status = 9; 
								break;
							}

							$time = time();
							$date = date('Y-m-d H:i:s', strtotime('first day of last month'));

							$interval = strtolower($client->renewal);

							if($interval == 'semianually')
							{
								$interval == 'semi_anually';
							}

							$items = array(
								'invoice_id' 	=> 0,
								'item_name'		=> $client->domain,
								'item_desc'		=> '-',
								'unit_cost'		=> $client->recurring_amount,
								'item_order'	=> 1,
								'item_tax_rate'	=> 0,
								'item_tax_total'=> 0,
								'quantity'		=> 1,
								'total_cost'	=> $client->recurring_amount
								);
								
							if($item_id = App::save_data('items', $items))

							{
								$order = array(
									'client_id' 	=> $client->user_id,
									'invoice_id'    => 0,
									'date'          => $date,  
									'item'		    => $item_id,
									'domain'        => $client->domain,
									'username'      => $client->username,
									'password'      => !empty($client->password) ? $client->password : create_password(),
									'item_parent'   => $item->item_id,
									'type'		    => 'hosting',
									'process_id'    => $time,
									'order_id'      => $time, 
									'fee'           => $client->recurring_amount,
									'processed'     => $date, 
									'renewal_date'  => !empty($client->due_date) ? $client->due_date : '0000-00-00',
									'status_id'     => $status, 
									'server'		=> $item->server,
									'renewal'       => $interval
								);    
								
								if($order_id = App::save_data('orders', $order)) 
								{
									$count++;
								}
							}							
						}  
					}                    
				}	
			} 
				 
			$this->session->unset_userdata('import');		 		

			$this->session->set_flashdata('response_status', 'info');
			$this->session->set_flashdata('message', "Created ".$count." accounts");			
			if($count == 0)	
            {
                redirect($_SERVER['HTTP_REFERER']);
            }
            else
            {
                redirect('accounts'); 
            }
		}
		else 
		{
 			$this->template->title(lang('hd_lang.import'));
			$data['page'] = lang('hd_lang.accounts');	
			$data['datatables'] = TRUE;  
			$this->template
			->set_layout('users')
			->build('import',isset($data) ? $data : NULL); 
		}
	}



    function import()
    {
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('hd_lang.import'));
        $data['page'] = lang('hd_lang.accounts');
        $this->template
        ->set_layout('users')
        ->build('import',isset($data) ? $data : NULL); 
    }


	
    public function upload()
    {
        $request = \Config\Services::request();
        $db = \Config\Database::connect();

        if ($request->getMethod() === 'post' && $request->getFile('import')) {
            $file = $request->getFile('import');

            $validTypes = ['Xlsx', 'Xls', 'Csv'];
            $valid = false;

            foreach ($validTypes as $type) {
                $reader = IOFactory::createReader($type);
                if ($reader->canRead($file)) {
                    $valid = true;
                    break;
                }
            }

            if (!$valid) {
                session()->setFlashdata('response_status', 'warning');
                session()->setFlashdata('message', lang('hd_lang.not_csv'));
				$data['page'] = lang('hd_lang.accounts');
                return view('modules/accounts/upload', $data);
            }

            try {
                $spreadsheet = IOFactory::load($file);
                $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
                $accounts = [];

                for ($x = 3; $x <= count($sheetData); $x++) {
                    // Check if the domain exists in the database
                    if ($db->table('hd_orders')->where('domain', $sheetData[$x]["G"])
                        ->where('username', $sheetData[$x]["P"])
                        ->where('client_id', $sheetData[$x]["B"])
                        ->where('type', 'hosting')->get()->getNumRows() == 0
                    ) {
                        $domain = [
                            'id' => $sheetData[$x]["A"],
                            'user_id' => $sheetData[$x]["B"],
                            'domain' => $sheetData[$x]["G"],
                            'first_payment' => $sheetData[$x]["J"],
                            'recurring_amount' => $sheetData[$x]["K"],
                            'renewal' => $sheetData[$x]["L"],
                            'due_date' => $sheetData[$x]["M"],
                            'username' => $sheetData[$x]["P"],
                            'password' => $sheetData[$x]["Q"],
                            'status' => $sheetData[$x]["O"],
                            'notes' => $sheetData[$x]["R"],
                            'reason' => $sheetData[$x]["T"]
                        ];

                        $accounts[] = (object) $domain;
                    }
                }

                session()->set('import_accounts', $accounts);
            } catch (\Exception $e) {
                session()->setFlashdata('response_status', 'warning');
                session()->setFlashdata('message', "Error loading file:" . $e->getMessage());
				$data['page'] = lang('hd_lang.accounts');
                return view('modules/accounts/upload', $data);
            }

            return view('accounts/import');
        } else {
            $data['page'] = lang('hd_lang.accounts');
            return view('modules/accounts/upload', $data);
        }
    }
 
 

}