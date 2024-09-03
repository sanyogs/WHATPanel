<?php
 /*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

namespace App\Modules\items\controllers;

use App\Helpers\custom_name_helper;
use App\Libraries\AppLib;
use App\Models\App;
use App\Models\Client;
use App\Models\FAQS;
use App\Models\Invoice;
use App\Models\Items_saved;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Models\Item;
use App\Models\Domain;
use App\ThirdParty\MX\WhatPanel;
use App\ThirdParty\MX\Modules;
use DateInterval;
use DateTime;

use Modules\cyberpanel\controllers\Cyberpanel;
use Modules\bitcoin\controllers\Bitcoin;
use Modules\cwp\controllers\CWP;
use Modules\cpanel\controllers\Cpanel;
use Modules\coinpayments\controllers\Coinpayments;
use Modules\checkout\controllers\Checkout;
use Modules\directadmin\controllers\Directadmin;
use Modules\ispconfig\controllers\Ispconfig;
use Modules\plesk\controllers\Plesk;
use Modules\whoisxmlapi\controllers\Whoisxmlapi;
use Modules\razorpay\controllers\Razorpay;
use Modules\stripepay\controllers\Stripepay;
use Modules\paypal\controllers\Paypal;
use Modules\payfast\controllers\Payfast;
use Modules\cpanel\controllers\new_Cpanel;

class Items extends WhatPanel
{

	protected $filter_by;
	protected $item;
	protected $domain;
	protected $app;
	protected $dbName;

	function __construct()
	{
		$session = \Config\Services::session();
		// Connect to the database	
		$dbName = \Config\Database::connect();
		// parent::__construct(); 
		// $this->load->module('layouts'); 
		// $this->load->library(array('form_validation','settings','template')); 
		// $this->load->helper('form'); 
		$this->filter_by = $this->_filter_by();
		// $this->applib->set_locale(); 
		$this->item = new Items_saved();

		$this->app = new App();

		$this->domain = new Domain();
	}


	function index($param1)
	{
		$this->list_items($param1);
		//$this->can_access();
	}


	function can_access()
	{
		if (!User::is_admin() && !User::is_staff()) {
			redirect('clients');
		}
	}


	function list_items($param1)
	{
		$session = \Config\Services::session();

		$dbName = \Config\Database::connect();

		$request = \Config\Services::request();

		//$this->can_access();
		//$this->template->title(lang('hd_lang.item_lookups').' - '.config_item('company_name'));
		$data['page'] = lang('hd_lang.items');
		$data['datatables'] = TRUE;
		$data['form'] = TRUE;
		$array = $this->filter_by($param1);
		// Pagination Configuration
		$page = $request->getGet('page') ? $request->getGet('page') : 1;
		$perPage = 10; // Number of items per page

		// Search Filter
		$search = $request->getGet('search');
		$data['search'] = $search;

		$query = $this->item->listItems($array, $search, $perPage, $page);

		// Get items for the current page
		$data['items'] = (object)$query['items'];

		$data['pager'] = $query['pager'];

		$data['message'] = $query['message'];

		$data['param'] = $param1;
		
		$data['perPage'] = $perPage;

		$builder = $dbName->table('hd_config');

		$affiliates = $builder->select('value')
			->where('config_key', 'affiliates')
			->get()
			->getRow();

		$data['affiliates'] = (isset($affiliates->value)) ? $affiliates->value : 'FALSE';

		// echo "<pre>";print_r($data);die;

		// echo view('App\Modules\items\views\items', $data);
		echo view('modules/items/items', $data);
	}



	function categories()
	{
		if (User::is_client()) {
			Applib::go_to('clients', 'error', lang('hd_lang.access_denied'));
		}
		//$this->template->title(lang('hd_lang.categories').' - '.config_item('company_name'));
		$data['page'] = lang('hd_lang.categories');
		// $this->template
		// ->set_layout('users')
		// ->build('categories',isset($data) ? $data : NULL);
		echo view('modules/items/categories', $data);
	}

	function _filter_by()
	{
		$filter = isset($_GET['view']) ? $_GET['view'] : '';
		return $filter;
	}



	function filter_by($filter_by)
	{
		switch ($filter_by) {
			case 'domains':
				return array('hd_categories.parent' => 8, 'deleted' => 'No');
				break;

			case 'hosting':
				return array('hd_categories.parent' => 9, 'deleted' => 'No');
				break;

			case 'service':
				return array('hd_categories.parent' => 10, 'deleted' => 'No');
				break;

			default:
				return array('deleted' => 'No');
				break;
		}
	}


	function add_hosting()
	{
		helper('url');

		$session = \Config\Services::session();

		$status = $this->add_item('hosting');

		if ($status == 'done') {
			return redirect()->to('hosting/index/hosting');
		}
		elseif ($status == 'validation_failed')
		{
			$session->setFlashdata('error', lang('hd_lang.enter_field'));
			return redirect()->to('hosting/index/hosting');
		}
	}

	function add_domain()
	{	
		$this->add_item('domain');
	}

	function add_service()
	{
		helper('url');

		$status = $this->add_item('service');

		if ($status == 'done') {
			return redirect()->to('hosting/index/service');
		}
	}

	function add_addon()
	{
		helper('url');

		$status = $this->add_item('addon');

		if ($status == 'done') {
			return redirect()->to('addons/list_items');
		}
	}

	function add_item($item = null)
	{	
		error_reporting(E_ALL);
        ini_set("display_errors", "1");

		$dbName = \Config\Database::connect();

		$request = \Config\Services::request();
		
		$helper = new custom_name_helper();
		if ($request->getPost()) {
			// Load the form validation library
			$validation = \Config\Services::validation();

			// Set validation rules
			//$validation->setRules([
				//'monthly_payments' => 'required|numeric|greater_than_equal_to[100000]|less_than_equal_to[999999]',
				//'item_name' => 'required',
				//'item_features' => 'required'
			//]);

			//if (!$validation->withRequest($this->request)->run()) {
				// If validation fails, redirect back to the form with validation errors
			//	return 'validation_failed';
			//}
			//echo"<pre>";print_r($request->getPost('unit_cost'));die;
			if (!empty($request->getPost())){
				if ($request->getPost('unit_cost') != "") {
					$sub_total = $request->getPost('unit_cost') * $request->getPost('quantity');
					$unit_cost = $request->getPost('unit_cost');
				} elseif(isset($request->getPost('full_payment')[0]) && $request->getPost('full_payment')[0] != ""){
					$sub_total = $request->getPost('full_payment')[0] * $request->getPost('quantity');
					$unit_cost = $request->getPost('full_payment')[0];
				} else {
					$sub_total = 0;
				}
			}

			//$item_tax_rate = $request->getPost('item_tax_rate');
			if(!empty($request->getPost('item_tax_rate'))){
				$item_tax_rate = $helper->getconfig_item('default_tax');
			}else{
				$item_tax_rate = 0;
			}
			//print_r($item_tax_rate);die;
			$item_tax_total = Applib::format_deci(($item_tax_rate / 100) *  $sub_total);			
			// $item_tax_total = 0;

			$data = array(
				//'item_tax_rate' => $item_tax_rate,
				'item_tax_total' => $item_tax_total,
				'quantity' => $request->getPost('quantity'),
				'total_cost' => Applib::format_deci($sub_total + $item_tax_total),
				'item_name' => $request->getPost('item_name'),
				'display' => ($request->getPost('display') == 'on') ? 'Yes' : 'No',
				'item_features' => $request->getPost('item_features'),
				'unit_cost' => $unit_cost ?? null,
				'order_by' => $request->getPost('order_by'),
				'setup_fee' => $request->getPost('setup_fee') ?? 0
			);
			//print_r($data);die;
			
			if($item == 'addon')
			{
				$data['require_domain'] = 'No';
			}
			
			if ($request->getPost('item_tax_rate')) {
				$data['item_tax_rate'] = ($request->getPost('item_tax_rate') == 'on') ? 'Yes' : 'No';
			}
			
			if ($request->getPost('cat_type')) {
				$data['cat_type'] = $request->getPost('cat_type');
			}
			
			if ($request->getPost('server')) {
				$data['server'] = $request->getPost('server');
			}

			if ($request->getPost('max_years')) {
				$data['max_years'] = $request->getPost('max_years');
			}
			if ($request->getPost('require_domain')) {
				//echo $item;die;
				if($item == 'addon')
				{
					$data['require_domain'] = 'No';
				}
				else
				{
					$data['require_domain'] = ($request->getPost('require_domain') == 'on') ? 'Yes' : 'No';
				}
			}
			if ($request->getPost('allow_upgrade')) {
				$data['allow_upgrade'] = ($request->getPost('allow_upgrade') == 'on') ? 'Yes' : 'No';
			}
			if ($request->getPost('price_change')) {
				$data['price_change'] = ($request->getPost('price_change') == 'on') ? 'Yes' : 'No';
			}
			if ($request->getPost('reseller_package')) {
				$data['reseller_package'] = ($request->getPost('reseller_package') == 'on') ? 'Yes' : 'No';
			}
			if ($request->getPost('default_registrar')) {
				$data['default_registrar'] = $request->getPost('default_registrar');
			}
			if ($request->getPost('create_account')) {
				$data['create_account'] = ($request->getPost('create_account') == 'on') ? 'Yes' : 'No';
			}

			if ($item == 'hosting') {
				$data['create_account'] = 'Yes';
			}
			
			if ($item == 'addon') {
				$data['apply_to'] = $request->getPost('apply_to');
				//$data['apply_to'] = serialize($request->getPost('apply_to'));
				$data['item_desc'] = $request->getPost('item_desc');
				$data['create_account'] = 'No';
				$data['addon'] = 1;
				$category = 5;
			} else {
				$category = $request->getPost('category');
			}

			$session = \Config\Services::session();
			//echo"<pre>";print_r($data);die;
			

			if ($dbName->table('hd_items_saved')->insert($data)) {
				$id = $dbName->insertID();

				$pricing = array(
					'item_id' => $id,
					'category' => $category,
					// 'monthly' => $request->getPost('monthly'),
					// 'quarterly' => $request->getPost('quarterly'),
					// 'semi_annually' => $request->getPost('semi_annually'),
					// 'annually' => $request->getPost('annually'),
					// 'biennially' => $request->getPost('biennially'),
					// 'triennially' => $request->getPost('triennially'),
					'registration' => $request->getPost('registration'),
					'transfer' => $request->getPost('transfer'),
					'renewal' => $request->getPost('renewal')
				);

				$result = [];
				
				
				foreach ($request->getPost('currencies') as $index => $currency) {
					
					if(null !== $request->getPost('unit_cost'))
					{
						if($request->getPost('unit_cost') != '')
						{
							$sub_total = $request->getPost('unit_cost') * $request->getPost('quantity');
						}
					}
					
					if($request->getPost('monthly_payments')[$index] != '')
					{
						$sub_total = $request->getPost('monthly_payments')[$index] * $request->getPost('quantity');
					}
					else
					{
						if (isset($request->getPost('full_payment')[$index]) && $request->getPost('full_payment')[$index] !== '') {
							$sub_total = $request->getPost('full_payment')[$index] * $request->getPost('quantity');
						}
					}
					
					$item_tax_total = Applib::format_deci(($item_tax_rate / 100) *  $sub_total);
					
					$result[$currency] = [
						'monthly' => $request->getPost('monthly_payments')[$index],
						'quarterly' => $request->getPost('quarterly_payments')[$index],
						'semi_annually' => $request->getPost('semi_annually_payments')[$index],
						'annually' => $request->getPost('annually_payments')[$index],
						'biennially' => $request->getPost('biennially_payments')[$index],
						'triennially' => $request->getPost('triennially_payments')[$index],
						'setup_fee' => $request->getPost('setup_fee') ?? null,
						'total_cost' => $unit_cost ?? null,
						'full_payment' => $request->getPost('full_payment')[$index] ?? null,
						'monthly_payments' => $request->getPost('monthly')[$index] ?? null,
						'quarterly_payments' => $request->getPost('quarterly')[$index] ?? null,
						'semi_annually_payments' => $request->getPost('semi_annually')[$index] ?? null,
						'annually_payments' => $request->getPost('annually')[$index] ?? null,
						'biennially_payments' => $request->getPost('biennially')[$index] ?? null,
						'triennially_payments' => $request->getPost('triennially')[$index] ?? null,
						//'total_cost' => Applib::format_deci($sub_total + $item_tax_total)
					];
				}

				$pricing['currency_amt'] = json_encode($result);
				// echo "<pre>";print_r($pricing);die;

				$item_pricing = $dbName->table('hd_item_pricing')->insert($pricing);
				//print_r($item_pricing);die;
			}

			// $session = \Config\Services::session();

			// $session->setFlashdata('response_status', 'success');
			// $session->setFlashdata('message', lang('hd_lang.item_added_successfully'));

			return 'done';
		} else {
			$data['form'] = TRUE;
			$data['categories'] = $this->app::get_by_where('hd_categories', array('parent >' => '7'));
			$data['rates'] = $this->app::get_by_where('hd_tax_rates', array());
			$data['servers'] = $this->app::get_by_where('hd_servers', array());
			//print_r($item);die;
			echo view('modules/items/modal/add_' . $item, $data);
		}
	}


	function domian_pricing($param1)
	{
		error_reporting(E_ALL);
		ini_set("display_errors", "1");

		$session = \Config\Services::session();

		// Connect to the database	
		$dbName = \Config\Database::connect();
		
		//$this->can_access();
		//$this->template->title(lang('hd_lang.item_lookups').' - '.config_item('company_name'));
		$data['page'] = lang('hd_lang.items');
		$data['datatables'] = TRUE;
		$data['form'] = TRUE;
		$array = $this->filter_by($param1);
		$data['invoice_items'] = $this->listdomainItems();
		$data['param'] = $param1;
		
		$builder = $dbName->table('hd_config');
		
        $affiliates = $builder->select('value')
		->where('config_key', 'affiliates')
		->get()
		->getRow();
		
		$data['affiliates'] = (isset($affiliates->value)) ? $affiliates->value : 'FALSE';
		
		$sysConfig = $dbName->table('hd_plugins')->select('config')->where('system_name', 'resellerclub')->get()->getRow()->config;
		
		$decode = json_decode($sysConfig);

		$data['customerpay_mode'] = $decode->customerpay_mode;
		
		//echo"<pre>";print_r($data['invoice_items']);die;
		echo view('modules/items/domain_pricing', $data);
	}


	function listdomainItems()
	{
		try {
		$db = \Config\Database::connect();
		$session = \Config\Services::session();

		// Fetch configuration
		$result = $db->table('hd_plugins')->select('config')->where('system_name', 'resellerclub')->get()->getRow();

		if ($result==null) {
			return redirect()->to('dashboard');
		}

		$request = $result->config;
		$decode = json_decode($request);

		if (json_last_error() !== JSON_ERROR_NONE) {
			$error = json_last_error_msg();
			$json = json_encode($decode, JSON_PRETTY_PRINT);
			throw new \Exception("JSON decode error: $error. JSON data: $json");
		}
		if ($decode->customerpay_mode == 'API') {
			$query = $db->table('hd_domains')
				->select('hd_domains.ext_name, hd_domains.ext_order, hd_categories.cat_name, hd_customer_pricing_view.*')
				->join('hd_categories', 'hd_domains.category = hd_categories.id')
				->join('hd_customer_pricing_view', 'hd_domains.ext_name = hd_customer_pricing_view.ext_name')
				->get()
				->getResult();

			$transformedObject = new \stdClass();
			foreach ($query as $object) {
				// Create a new stdClass object for each extension
				if (!isset($transformedObject->{$object->ext_name})) {
					$transformedObject->{$object->ext_name} = new \stdClass();
					$transformedObject->{$object->ext_name}->cat_name = $object->cat_name;
				}

				// Assign cost for the service type
				$transformedObject->{$object->ext_name}->id = $object->view_id;
				$transformedObject->{$object->ext_name}->ext_name = $object->ext_name;
				// $transformedObject->{$object->ext_name}->type = 'API';
				$transformedObject->{$object->ext_name}->registration_1 = $object->registration_1;
				$transformedObject->{$object->ext_name}->renewal_1 = $object->renewal_1;
				$transformedObject->{$object->ext_name}->transfer_1 = $object->transfer_1;
				$transformedObject->{$object->ext_name}->ext_order = 1;
			}
			return $transformedObject;
		} else {
			$user = $db->table('hd_categories')
				->select('hd_categories.id, hd_categories.cat_name, hd_domains.*')
				->join('hd_domains', 'hd_domains.category = hd_categories.id')
				->get()
				->getResult();
			return $user;
		}
	 }catch (\Exception $e) { 
		return redirect()->to('items/domian_pricing/domains');
		}
	}

	function add_domains()
	{	//echo 6789;die;
		$db = \Config\Database::connect();
		$session =  \Config\Services::Session();
		$request = \Config\Services::request();
		//echo"<pre>";print_r($request->getPost());die;
		$category = $request->getPost('category');
		$maxYears = $request->getPost('max_years');

		$renewal = '';
		$transfer = '';
		$registration = '';
		//$data = [];
		// Edit method
		//print_r($request->getPost());die;
		if ($request->getPost('edit_id')) { 
			$edit_id = $request->getPost('edit_id');
			$data = array(
				'category' => $request->getPost('category'),
				'ext_name' => $request->getPost('item_name'),
				'registrar' => $request->getPost('default_registrar'),
				'max_years' => $request->getPost('max_years'),
				'tax_rate' => ($request->getPost('item_tax_rate') == 'on') ? 'Yes' : 'No',
				'ext_order' => $request->getPost('order_by'),
				'display' => ($request->getPost('display') == 'on') ? 'Yes' : 'No',
				'parent_category' => $request->getPost('category'),
			);
			
			// Adjust the field names according to the edited data
			for ($i = 1; $i <= $request->getPost('max_years'); $i++) {
				$data['registration_' . $i] = $request->getPost('registration_' . $i);
				$data['transfer_' . $i] = $request->getPost('transfer_' . $i);
				$data['renewal_' . $i] = $request->getPost('renewal_' . $i);
			}
			
			$db->table('hd_domains')->where('id', $edit_id)->update($data);
			$details = $db->table('hd_domains')->where('id', $edit_id)->get()->getRow();
			$edit_details = $db->table('hd_item_pricing')->where('category', $details->category)->update(['ext_name' => $data['ext_name']]);
			//echo"<pre>";print_r($details);die;
			
		}
		if ($request->getPost('type_of')) { 

			$exists = $db->table('hd_domains')->where('ext_name', $request->getPost('item_name'))->get()->getRow();

			if(!empty($exists))
			{
				$session->setFlashdata('response_status', 'danger');
				$session->setFlashdata('message', lang('hd_lang.already_exists'));
				return redirect()->to('items/domian_pricing/domains');
			}

			$data = array(
				'category' => $category,
				'ext_name' => $request->getPost('item_name'),
				'registrar' => $request->getPost('default_registrar'),
				'max_years' => $maxYears,
				'tax_rate' => ($request->getPost('tax_rate') == 'on') ? 'Yes' : 'No',
				'ext_order' => $request->getPost('order_by'),
				'display' => ($request->getPost('display') == 'on') ? 'Yes' : 'No',
				'parent_category' => $request->getPost('category'),
			);
			
			for ($i = 1; $i <= $maxYears; $i++) {
				$data['registration_' . $i] = $request->getPost('registration_' . $i);
				$data['transfer_' . $i] = $request->getPost('transfer_' . $i);
				$data['renewal_' . $i] = $request->getPost('renewal_' . $i);

				$renewal = $request->getPost('renewal_1');
				$transfer = $request->getPost('transfer_1');
				$registration = $request->getPost('registration_1');
			}
			
			//$item_tax = ($request->getPost('tax_rate') == 'on') ? 'Yes' : 'No'; 

$db->table('hd_items_saved')->insert(['item_name' => str_replace('.', '', $request->getPost('item_name')), 'item_tax_rate' => $data['tax_rate'], 'item_type'  => $request->getPost('item_type')] );
			
			$item_saved_id = $db->insertID();

			$dataItemPricing = [
				'item_id' => $item_saved_id,
				'ext_name' => $request->getPost('item_name'),
				'registration' => $registration,
				'transfer' => $transfer,
				'renewal' => $renewal,
				'category' => $request->getPost('category'),
				//'domain_id' => $domain_id
			];
			
			$db->table('hd_domains')->insert($data);
			
			//$domain_id = $db->table('hd_domains')->where('ext_name', $request->getPost('item_name'))->get()->getRow();
		
			$db->table('hd_item_pricing')->insert($dataItemPricing);
		}

		// Delete method
		if ($request->getPost('delete_id')) {
			$delete_id = $request->getPost('delete_id');
			$db->table('hd_domains')->where('id', $delete_id)->delete();
		}

		// Redirect to domains page
		return redirect()->to('items/domian_pricing/domains');
	}
	
	function update_domains()
	{
		if ($request->getPost('edit_id')) { 
			$edit_id = $request->getPost('edit_id');
			$data = array(
				'category' => $request->getPost('category'),
				'ext_name' => $request->getPost('item_name'),
				'registrar' => $request->getPost('default_registrar'),
				'max_years' => $request->getPost('max_years'),
				'tax_rate' => ($request->getPost('item_tax_rate') == 'on') ? 'Yes' : 'No',
				'ext_order' => $request->getPost('order_by'),
				'display' => ($request->getPost('display') == 'on') ? 'Yes' : 'No',
				'parent_category' => $request->getPost('category'),
			);
			
			// Adjust the field names according to the edited data
			for ($i = 1; $i <= $request->getPost('max_years'); $i++) {
				$data['registration_' . $i] = $request->getPost('registration_' . $i);
				$data['transfer_' . $i] = $request->getPost('transfer_' . $i);
				$data['renewal_' . $i] = $request->getPost('renewal_' . $i);
			}
			
			$db->table('hd_domains')->where('id', $edit_id)->update($data);
			$details = $db->table('hd_domains')->where('id', $edit_id)->get()->getRow();
			$edit_details = $db->table('hd_item_pricing')->where('category', $details->category)->update(['ext_name' => $data['ext_name']]);
			//echo"<pre>";print_r($details);die;
			
		}
	}
	
	function get_domains()
	{	
		try {
		$db = \Config\Database::connect();

		$query = $db->table('hd_customer_pricing')
					->where('service_type', 'addnewdomain')
					->where('duration', 1)
					->get()
					->getResult();

		$replace = []; // Initialize the array to store results

		foreach ($query as $queries) {
			$result = $queries->domain_name;

			if ($result == "dotcompany") {
				$results = str_replace("dotcompany", ".com", $result);
				$replace[] = $results;
			} else {
				$replace[] = str_replace("dot", ".", $result);
			}
		}
			return $replace;
		} catch (\Exception $e) {
			// Log the exception message or handle it as needed
			log_message('error', 'Database query failed: ' . $e->getMessage());
			return redirect()->to('orders/select_client');
		}
	}

	public static function view_item($id)
	{
		$session = \Config\Services::session();

		// Connect to the database	
		$db = \Config\Database::connect();

		$result = $db->table('hd_domains')->where('id', $id)->get()->getRow();

		return $result;
	}

	function edit_hosting($id = NULL)
	{
		helper('url');

		$status = $this->edit_item($id, 'hosting');

		if ($status == 'done') {
			return redirect()->to('hosting/index/hosting');
		}
	}

	function edit_domain($id = NULL)
	{
		$this->edit_item($id, 'domain');
	}

	function edit_service($id = NULL)
	{
		helper('url');

		$status = $this->edit_item($id, 'service');

		if ($status == 'done') {
			return redirect()->to('hosting/index/service');
		}
	}

	function edit_addon($id = NULL)
	{
		helper('url');

		$status = $this->edit_item($id, 'addon');

		if ($status == 'done') {
			return redirect()->to('addons/list_items');
		}
	}


	
	function edit_item($id = NULL, $item = NULL)
	{
		//App::module_access('menu_items');
		$request = \Config\Services::request();
		
		$helper = new custom_name_helper();
		
		if ($request->getPost()) {
			//Applib::is_demo();
			if ($request->getPost('unit_cost')) {
				$sub_total = $request->getPost('unit_cost') * $request->getPost('quantity');
				$unit_cost = $request->getPost('unit_cost');
			} elseif(isset($request->getPost('full_payment')[0]) && $request->getPost('full_payment')[0] != ""){
					$sub_total = $request->getPost('full_payment')[0] * $request->getPost('quantity');
					$unit_cost = $request->getPost('full_payment')[0];
				} else {
				$sub_total = 0;
			}
			
			if(!empty($request->getPost('item_tax_rate'))){
				$item_tax_rate = $helper->getconfig_item('default_tax');
			}else{
				$item_tax_rate = 0;
			}
			
			$item_tax_total = Applib::format_deci(($item_tax_rate / 100) *  $sub_total);	
			
			$data = array(
				'item_tax_rate' => ($request->getPost('item_tax_rate') == 'on') ? 'Yes' : 'No',
				'item_tax_total' => $item_tax_total,
				'quantity' => $request->getPost('quantity'),
				'total_cost' => Applib::format_deci($sub_total + $item_tax_total),
				'item_name' => $request->getPost('item_name'),
				'default_registrar' => $request->getPost('default_registrar'),
				'reseller_package'=> ($request->getPost('reseller_package') == 'on') ? 'Yes' : 'No',
				'require_domain' => ($request->getPost('require_domain') == 'on') ? 'Yes' : 'No',
				'create_account' => ($request->getPost('create_account') == 'on') ? 'Yes' : 'No',
				'allow_upgrade' => ($request->getPost('allow_upgrade') == 'on') ? 'Yes' : 'No',
				'display' => ($request->getPost('display') == 'on') ? 'Yes' : 'No',
				'price_change' => ($request->getPost('price_change') == 'on') ? 'Yes' : 'No',
				'item_features' => $request->getPost('item_features'),
				'unit_cost' => $unit_cost ?? null,
				'order_by' => $request->getPost('order_by')
			);

			if ($request->getPost('max_years')) {
				$data['max_years'] = $request->getPost('max_years');
			}
			if ($request->getPost('require_domain')) {
				$data['require_domain'] = ($request->getPost('require_domain') == 'on') ? 'Yes' : 'No';
			}
			if ($request->getPost('allow_upgrade')) {
				$data['allow_upgrade'] = ($request->getPost('allow_upgrade') == 'on') ? 'Yes' : 'No';
			}
			if ($request->getPost('price_change')) {
				$data['price_change'] = ($request->getPost('price_change') == 'on') ? 'Yes' : 'No';
			}
			if ($request->getPost('reseller_package')) {
				$data['reseller_package'] = ($request->getPost('reseller_package') == 'on') ? 'Yes' : 'No';
			}
			if ($request->getPost('default_registrar')) {
				$data['default_registrar'] = $request->getPost('default_registrar');
			}
			if ($request->getPost('create_account')) { 
				$data['create_account'] = ($request->getPost('create_account') == 'on') ? 'Yes' : 'No';
			}
		
			if ($item == 'addon') {
				$data['apply_to'] = $request->getPost('apply_to');
				$data['item_desc'] = $request->getPost('item_desc');
				$data['create_account'] = 'No';
				$data['addon'] = 1;
				$category = 5;
			} else {
				$category = $request->getPost('category');
			}

			$session = \Config\Services::session();

			// Connect to the database
			$dbName = \Config\Database::connect();

			if ($dbName->table('hd_items_saved')->where('item_id', $request->getPost('item_id'))->update($data)) {

				$pricing = array(
					'category' => $category,
					'registration' => $request->getPost('registration'),
					'transfer' => $request->getPost('transfer'),
					'renewal' => $request->getPost('renewal')
				);

				$result = [];

				$result = [];

				foreach ($request->getPost('currencies') as $index => $currency) {
					$result[$currency] = [
						'monthly' => $request->getPost('monthly_payments')[$index],
						'quarterly' => $request->getPost('quarterly_payments')[$index],
						'semi_annually' => $request->getPost('semi_annually_payments')[$index],
						'annually' => $request->getPost('annually_payments')[$index],
						'biennially' => $request->getPost('biennially_payments')[$index],
						'triennially' => $request->getPost('triennially_payments')[$index],
						'full_payment' => $request->getPost('full_payment')[$index] ?? null,
						'monthly_payments' => $request->getPost('monthly')[$index] ?? null,
						'quarterly_payments' => $request->getPost('quarterly')[$index] ?? null,
						'semi_annually_payments' => $request->getPost('semi_annually')[$index] ?? null,
						'annually_payments' => $request->getPost('annually')[$index] ?? null,
						'biennially_payments' => $request->getPost('biennially')[$index] ?? null,
						'triennially_payments' => $request->getPost('triennially')[$index] ?? null,
						'setup_fee' => isset($request->getPost('setup_fee')[$index]) ? $request->getPost('setup_fee')[$index] : 0,
						'total_cost' => $unit_cost ?? null,
					];
				}

				// Encode $result array into JSON
				$pricing['currency_amt'] = json_encode($result);

				try {
					$dbName->table('hd_item_pricing')
						   ->where('item_id', $request->getPost('item_id'))
						   ->update($pricing);
					$dbName->table('hd_item_pricing')->where('item_id', $request->getPost('item_id'))->update(['currency_amt' => $pricing['currency_amt']]);
				} catch (\Exception $e) {
					return redirect()->to('hosting/index/service')->with('Something went wrong during editing, Please try again');
				}
			}

			//$this->session->set_flashdata('response_status', 'success');
			//$this->session->set_flashdata('message', lang('hd_lang.operation_successful'));

			$session = \Config\Services::session();

			$session->setFlashdata('response_status', 'success');
			$session->setFlashdata('message', lang('hd_lang.operation_successful'));
			
			return 'done';
		} else {
			$data['categories'] = $this->app::get_by_where('hd_categories', array('parent >' => '7'));
			$data['rates'] = $this->app::get_by_where('hd_tax_rates', array());
			$data['id'] = $id;
			echo view('modules/items/modal/edit_' . $item, $data);
		}
	}


	function package($id = NULL)
	{
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
		$request = \Config\Services::request();

		$session = \Config\Services::session();

		// Connect to the database  
		$db = \Config\Database::connect();

		//App::module_access('menu_items');

		if ($request->getPost()) {
			Applib::is_demo();
			$data['package_config'] = serialize($request->getPost());
			$data['server'] = $request->getPost('server');
			$data['package_name'] = $request->getPost('package');

			//  $db->where('item_id', $request->getPost('item_id'));

			if ($db->table('hd_items_saved')->where('item_id', $request->getPost('item_id'))->update($data)) {
				$session->setFlashdata('response_status', 'success');
				$session->setFlashdata('message', lang('hd_lang.operation_successful'));
				// $this->session->set_flashdata('response_status', 'success');
				// $this->session->set_flashdata('message', lang('hd_lang.operation_successful'));
				return redirect()->to('hosting/index/hosting');
				// redirect($_SERVER['HTTP_REFERER']); 
			}
		} else {
			$this->can_access();
			//$this->template->title(lang('hd_lang.items').' - '.config_item('company_name'));
			$data['page'] = Item::view_item($id)->item_name;

			// Assuming $db is an instance of CodeIgniter\Database\ConnectionInterface
			$builder = $db->table('hd_servers');
			$results = $builder->get()->getResult();
			$data['servers'] = $results;
			$data['id'] = $id;
			//echo"<pre>";print_r($data);die;
			echo view('modules/items/package', isset($data) ? $data : NULL);
		}
	}


	function get_config_items($server_type = null)
	{
		error_reporting(E_ALL);
		ini_set("display_errors", "1");

		$session = \Config\Services::session();

		// Connect to the database  
		$db = \Config\Database::connect();

		$server_info = $db->table('hd_servers')->where('type', $server_type)->get()->getRow();

		$configuration = [];

		switch ($server_type) {
			case 'plesk':
				$plesk = new Plesk();
				$configuration = $plesk->plesk_config($server_info);
				break;
			case 'whoisxmlapi':
				$whoisxmlapi = new Whoisxmlapi();
				$configuration = $whoisxmlapi->whoisxmlapi_config();
				break;
			case 'stripepay':
				$stripepay = new Stripepay();
				$configuration = $stripepay->stripepay_config();
				break;
			case 'paypal':
				$paypal = new Paypal();
				$configuration = $paypal->paypal_config();
				break;
			case 'razorpay':
				$razorpay = new Razorpay();
				$configuration = $razorpay->razorpay_config();
				break;
			case 'payfast':
				$payfast = new Payfast();
				$configuration = $payfast->payfast_config();
				break;
			case 'ispconfig':
				$ispconfig = new Ispconfig();
				$configuration = $ispconfig->ispconfig_config();
				break;
			case 'directadmin':
				$directadmin = new Directadmin();
				$configuration = $directadmin->directadmin_config();
				break;
			case 'cyberpanel':
				$cyberpanel = new Cyberpanel();
				$configuration = $cyberpanel->cyberpanel_config($server_info);
				break;
			case 'cwp':
				$cwp = new Cwp();
				$configuration = $cwp->cwp_config();
				break;
			case 'cpanel':
				$cpanel = new Cpanel();
				$configuration = $cpanel->cpanel_config($server_info);
				break;
			case 'coinpayments':
				$coinpayments = new Coinpayments();
				$configuration = $coinpayments->coinpayments_config();
				break;
			case 'checkout':
				$checkout = new Checkout();
				$configuration = $checkout->checkout_config();
				break;
			case 'bitcoin':
				$bitcoins = new Bitcoin();
				$configuration = $bitcoins->bitcoin_config();
				break;
		}

		$formHtml = '<form method="POST" id="formCode">';
		foreach ($configuration as $field) {
			$formHtml .= '<div>';
			$formHtml .= '<label class="common-label" for="' . $field['id'] . '">' . $field['label'] . '</label>';

			if ($field['type'] === 'select') {
				// Generate dropdown options
				$formHtml .= '<select class="common-select" id="' . $field['id'] . '" name="' . $field['id'] . '">';
				foreach ($field['options'] as $optionValue => $optionLabel) {
					$selected = ($field['value'] === $optionValue) ? 'selected' : '';
					$formHtml .= '<option value="' . $optionValue . '" ' . $selected . '>' . $optionLabel . '</option>';
				}
				$formHtml .= '</select>';
			} elseif ($field['type'] === 'textarea') {
				$formHtml .= '<textarea class="common-input" id="' . $field['id'] . '" name="' . $field['id'] . '" placeholder="' . $field['placeholder'] . '"></textarea>';
			} else {
				$formHtml .= '<input class="common-input" type="' . ($field['type'] ?: 'text') . '" id="' . $field['id'] . '" name="' . $field['id'] . '" placeholder="' . $field['placeholder'] . '" value="' . ($field['value'] ?: '') . '">';
				$formHtml .= '<input class="common-input" type="' . ($field['type'] ?: 'hidden') . '" id="server_id" name="server_id" value="' . $server_info->id . '">';
				$formHtml .= '<input class="common-input" type="' . ($field['type'] ?: 'hidden') . '" id="server_name" name="server_name" value="' . $server_info->name . '">';
			}

			$formHtml .= '</div>';
		}

		$formHtml .= '<button class="common-button m-4" type="submit" id="btnCode">Submit</button>';
		$formHtml .= '</form>';

		return $formHtml;
	}

	function save_config_items()
	{
		$request = \Config\Services::request();

		$session = \Config\Services::session();

		// Connect to the database  
		$db = \Config\Database::connect();

		$data['package_config'] = serialize($request->getPost());
		$data['server'] = $request->getPost('server');
		$data['package_name'] = $request->getPost('package');

		// print_r($data);die;

		if ($db->table('hd_items_saved')->where('item_id', $request->getPost('item_id'))->update($data)) {
			$session->setFlashdata('response_status', 'success');
			$session->setFlashdata('message', lang('hd_lang.operation_successful'));
			// $this->session->set_flashdata('response_status', 'success');
			// $this->session->set_flashdata('message', lang('hd_lang.operation_successful'));
			//return redirect()->to('hosting/index/hosting');
			return json_encode(array('statusCode' => 200, 'msg' => 'Package Added Successfully'));
			// redirect($_SERVER['HTTP_REFERER']); 
		}
	}




	function delete_hosting($id = NULL)
	{
		helper('url');

		$status = $this->delete_item($id, 'hosting');

		if ($status == 'done') {
			return redirect()->to('hosting/index/hosting');
		}
	}

	function delete_domain($id = NULL)
	{
		$this->delete_item($id, 'domains');
	}

	function delete_domains($id = NULL)
	{
		$data['id'] = $id;
		echo view('modules/items/modal/delete_domain', isset($data) ? $data : NULL);
	}

	function delete_addons($id = NULL)
	{
		$data['id'] = $id;
		echo view('modules/items/modal/delete_addon', isset($data) ? $data : NULL);
	}

	function delete_service($id = NULL)
	{
		helper('url');

		$status = $this->delete_item($id, 'service');

		if ($status == 'done') {
			return redirect()->to('hosting/index/service');
		}
	}

	function delete_addon($id = NULL)
	{
		helper('url');

		$status = $this->delete_item($id, 'addon');

		if ($status == 'done') {
			return redirect()->to('addons/list_items');
		}
	}


	function delete_item($id = NULL, $item = NULL)
	{
		//App::module_access('menu_items');
		$request = \Config\Services::request();
		if ($request->getPost()) {
			//Applib::is_demo();

			$session = \Config\Services::session();



			// Modify the 'default' property

			// Connect to the database
			$dbName = \Config\Database::connect();

			$dbName->table('hd_items_saved')->where('item_id', $request->getPost('item'))->delete();
			// $this->app->delete('hd_item_pricing',array('item_id' => $item_id));
			$dbName->table('hd_item_pricing')->where('item_id', $request->getPost('item'))->delete();

			// $this->session->set_flashdata('response_status', 'success');
			// $this->session->set_flashdata('message', lang('hd_lang.item_deleted_successfully'));
			$session = \Config\Services::session();

			$session->setFlashdata('response_status', 'success');
			$session->setFlashdata('message', lang('hd_lang.item_deleted_successfully'));

			// redirect($item == 'addon' ? 'addons' : $request->getPost('r_url'));
			// $redirectUrl = ($item == 'addon') ? 'addons' : $request->getPost('r_url');
			// return redirect()->to($redirectUrl);

			return 'done';
		} else {
			$data['item_id'] = $id;
			$data['path'] = $item;
			// $this->load->view('modal/delete_item',$data);
			echo view('modules/items/modal/delete_item', $data);
		}
	}

	function duplicate_hosting($param1 = null)
	{
		$db = \Config\Database::connect();

		$array = $this->filter_by($param1);

		$query = $this->item->listItemsFilter($array);

		$data['query'] = $query;

		$data['path'] = $param1;

		echo view('modules/items/modal/duplicate_items', isset($data) ? $data : NULL);
	}

	function duplicate_item()
	{
		$request = \Config\Services::request();

		$db = \Config\Database::connect();

		if($request->getPost())
		{
			// Load necessary helpers or libraries
			helper('url');

			$id = $request->getPost('item_id');

			$item = $request->getPost('item_type');

			$oldRecordItem = $db->table('hd_items_saved')->where('item_id', $id)->get()->getRowArray();

			unset($oldRecordItem['item_id']);$db->table('hd_items_saved')->insert($oldRecordItem);

			$item_id = $db->insertID();

			$oldRecordPricing = $db->table('hd_item_pricing')->where('item_id', $id)->get()->getRowArray();

			$oldRecordPricing['item_id'] = $item_id;

			unset($oldRecordPricing['item_pricing_id']);

			$item_pricing_id = $db->table('hd_item_pricing')->insert($oldRecordPricing);

			$data['categories'] = $this->app::get_by_where('hd_categories', array('parent >' => '7'));
			$data['rates'] = $this->app::get_by_where('hd_tax_rates', array());
			$data['id'] = $item_id;
			return redirect()->to('hosting/index/hosting');
			// echo view('modules/items/modal/duplicate_' . $item, $data);
		}
	}



	function item_links($id = NULL)
	{
		$data['id'] = $id;
		// $this->load->view('modal/item_links',$data);
		// echo view('App\Modules\items\Views\modal\item_links',$data);
		echo view('modules/items/modal/item_links', isset($data) ? $data : NULL);
	}



	function affiliates($id = NULL)
	{

		$request = \Config\Services::request();

		$session = \Config\Services::session();





		// Modify the 'default' property    


		// Connect to the database  
		$db = \Config\Database::connect();

		if ($request->getPost()) {
			$data['commission'] = $request->getPost('commission');
			$data['commission_payout'] = $request->getPost('commission_payout');
			$data['commission_amount'] = $request->getPost('commission_amount');

			$builder = $db->table('hd_items_saved');

			$builder->where('item_id', $request->getPost('item_id'));

			if ($builder->update($data)) {
				$session = session();
				$session->setFlashdata('response_status', 'success');
				$session->setFlashdata('message', lang('hd_lang.operation_successful'));

				return redirect()->to($request->getPost('r_url'));
			}
		} else {
			$data['id'] = $id;
			// $this->load->view('modal/item_affiliates',$data); 
			// echo view('App\Modules\items\Views\modal\item_affiliates',$data);
			echo view('modules/items/modal/item_affiliates', isset($data) ? $data : NULL);
		}
	}



	function items_block($id)
	{
		// echo $id;die;
		$db = \Config\Database::connect();

		$custom_helper = new custom_name_helper();

		$category = $db->table('hd_categories')->where('id', $id)->where('parent >', '7')->get()->getRow();
		// echo "<pre>";print_r($category);die;
		if(empty($category)){
			$category = $db->table('hd_categories')->where('parent >', '7')->get()->getRow();
		}
		$items = array();

		if ($category->parent == 8) {
			$items = Item::get_domains($id);
			$view = 'domains_block';
		}

		if ($category->parent == 9) {
			$items = Item::get_hosting($id);
			$view = 'hosting_block';
		}

		if ($category->parent == 10) {
			$items = Item::get_services($id);
			$view = 'services_block';
		}

		$data['items'] = $items;
		$data['style'] =  $category->pricing_table;
		echo $view;die;
		echo view($custom_helper->getconfig_item('active_theme') . '/views/blocks/' . $view, $data);
	}

	function showModal()
	{
		$request = \Config\Services::request();

		$view_file = $request->getPost('path');

		$viewContent = view('modules/items/modal/add_' . $view_file);
		return json_encode(['content' => $viewContent]);
	}

	public function menus_block($group_id)
    {
        $helper = new custom_name_helper();
        $db = \Config\Database::connect(); // Get database connection
        $object = new \stdClass();
        $object->id = $group_id;
        $main_menu = [];

        $menu = $db->table('hd_menu')->select('*')->where('group_id', $group_id)->orderBy('position', 'DESC')->where('active', 1)->get()->getResult();

        foreach ($menu as $item) {
            if ($item->parent_id == 0) {
                $main_menu[] = $item;
            }
        }

        foreach ($main_menu as $main_item) {
            $parent_menu = [];
            foreach ($menu as $menu_item) {
                if ($menu_item->parent_id == $main_item->id) {
                    $parent_menu[] = $menu_item;
                }
            }
            $main_item->parent_menu = $parent_menu;
        }

        foreach ($main_menu as $main_item) {
            foreach ($main_item->parent_menu as $parent_menu_item) {
                $parent_submenu = [];
                foreach ($menu as $submenu_item) {
                    if ($parent_menu_item->id == $submenu_item->parent_id) {
                        $parent_submenu[] = $submenu_item;
                    }
                }
                $parent_menu_item->parent_submenu = array_unique($parent_submenu, SORT_REGULAR);
            }
        }

        $object->main_menu = $main_menu;

        $data['menu'] = $object;
        //echo"<pre>";print_r($data);die;
        echo view($helper->getconfig_item('active_theme') . '/views/blocks/menu_block', $data);
    }
}

/* End of file items.php */