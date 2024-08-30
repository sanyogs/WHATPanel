<?php
 /*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

namespace App\Modules\domains\controllers;

use App\Libraries\AppLib;
use App\Models\App;
use App\Models\Client;
use App\Models\Domain;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\Order;
use App\Models\Orders;
use App\Models\User;
use App\ThirdParty\MX\WhatPanel;
use App\ThirdParty\MX\Modules;
use App\Modules\Layouts\Libraries\Template;
use Exception;
use PHPExcel_IOFactory;
use App\Helpers\AuthHelper;
use App\Models\Items_saved;
use App\Modules\whoisxmlapi\controllers\Whoisxmlapi;
use App\Helpers\custom_name_helper;
use Modules\customerprice\controllers\Customerprice;

//Domain Registarars List
use App\Controllers\APIController;
use Modules\resellerclub\controllers\Resellerclub;
use Twilio\Rest\Microvisor\V1\AppContext;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Domains extends WhatPanel
{
	protected $filter_by;

	function __construct()
	{	
		error_reporting(E_ALL);
		ini_set("display_errors", "1");
		// parent::__construct();
		// User::logged_in();

		// $this->load->module('layouts');
		// $this->load->library('template'); 
		$this->filter_by = $this->_filter_by();

		// $lang = config_item('default_language');
		// if (isset($_COOKIE['fo_lang'])) { $lang = $_COOKIE['fo_lang']; }
		// if ($this->session->userdata('lang')) { $lang = $this->session->userdata('lang'); }
		// $this->lang->load('hd', $lang);

		$session = \Config\Services::session();

		// Connect to the database
		$dbName = \Config\Database::connect();
		$this->userModel = new User();
		$this->domainModel = new Domain();
		$this->itemModel = new Item();
	}



	function index()
	{
		$request = \Config\Services::request();
		
		$template = new Template();
		
		$custom = new custom_name_helper();

		$type = "(hd_orders.type ='domain' OR hd_orders.type ='domain_only')";
		
		$array = $this->filter_by($this->filter_by);

		$session = \Config\Services::session();

		// echo "<pre>";print_r($session->get());die;

		$user_id = $session->get('userdata.user_id');

		//if(User::is_admin() || User::perm_allowed(User::get_id(),'manage_accounts')){
		//if (User::is_admin()) {
			//$data['domains'] = $this->domainModel::by_where($array, $type);
		//} else {
			// echo 234;die;
			//$client_details = $this->userModel::profile_info($user_id);
			//$array['client_id'] = $client_details->co_id;
			// echo "<pre>";print_r($array);die;
			//$data['domains'] = $this->domainModel::by_where($array, $type);
		//}
		// echo"<pre>";print_r($data);die;
		$template->title(lang('hd_lang.domains') . ' - ' . $custom->getconfig_item('company_name'));
		$data['page'] = lang('hd_lang.domains');
		$data['datatables'] = TRUE;
		
		$orderModel = new Orders();
		
		// Pagination Configuration
		$page = $request->getGet('page') ? $request->getGet('page') : 1;

        $perPage = $request->getGet('recordsPerPage', FILTER_SANITIZE_NUMBER_INT) ? $request->getGet('recordsPerPage', FILTER_SANITIZE_NUMBER_INT) : 10;

		// Search Filter
		$search = $request->getGet('search');
		$data['search'] = $search;
		
		if (User::is_admin()) {
        	// $query = $this->domainModel->listItems([], $search, $perPage, $page);
			$query = $orderModel->listItems([], $search, $perPage, $page, $type);
		}
		else {
			$user_id = $session->get('userdata')['user_id'];
			$array['client'] = User::profile_info($user_id)->company;
			// $query = $this->domainModel->listItems($array, $search, $perPage, $page);
			$query = $orderModel->listItems($array, $search, $perPage, $page, $type);
		}

        // Get items for the current page
		$data['domains'] = array_map(function($item) {
			return (object) $item;
		}, $query['items']);

        $data['pager'] = $query['pager'];

		$data['message'] = $query['message'];

		$data['perPage'] = $perPage;

		return view('modules/domains/domains', $data);
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

			default:
				return array('status_id <>' => 8, 'status_id <>' => 2);
				break;
		}
	}


	function activate($id = null)
	{	
		$request = \Config\Services::request();
		$helper = new custom_name_helper();
		$db = \Config\Database::connect();
		if ($request->getPost()) {
			$domain = $request->getPost('domain');
			$order_id = $request->getPost('id');
			if ($request->getPost('activate_domain') == 'on' && $request->getPost('domain_status') != 6) {
				$update = array('status_id' => 6, 'authcode' => $request->getPost('authcode'), 'registrar' => $request->getPost('registrar'));
				
				if ($db->table('hd_orders')->where('id', $order_id)->update($update)) {
					$result = "Domain: " . $domain . " activated! <br/>";
				}

				if ($request->getPost('registrar') != '') {
					$account = Order::get_order($order_id);

					$action = '/register_domain';

					$nameservers = Order::get_nameservers($order_id);

					if ($nameservers != '') {
						$nameservers = explode(",", $nameservers);
					} else {
						$nameservers = array();

						if ($helper->getconfig_item('nameserver_one') != '') {
							$nameservers[] = $helper->getconfig_item('nameserver_one');
						}
						if ($helper->getconfig_item('nameserver_two') != '') {
							$nameservers[] = $helper->getconfig_item('nameserver_two');
						}
						if ($helper->getconfig_item('nameserver_three') != '') {
							$nameservers[] = $helper->getconfig_item('nameserver_three');
						}
						if ($helper->getconfig_item('nameserver_four') != '') {
							$nameservers[] = $helper->getconfig_item('nameserver_four');
						}
						if ($helper->getconfig_item('nameserver_five') != '') {
							$nameservers[] = $helper->getconfig_item('nameserver_five');
						}
					}


					$action = '/register_domain';
					if ($account->item_name == lang('hd_lang.domain_renewal')) {
						$action = '/renew_domain';
					}
					if ($account->item_name == lang('hd_lang.domain_transfer')) {
						$action = '/transfer_domain';
					}

					$result .= Modules::run($account->registrar . $action, $order_id, $nameservers);
				}
			}

			session()->setFlashdata('response_status', 'success');
			$referer = $this->request->getServer('HTTP_REFERER');
			session()->setFlashdata('success', lang('hd_lang.user_edited_successfully'));
			return redirect()->to($referer);
		} else {
			$data['item'] = Order::get_order($id);
			$data['servers'] = $db->table('hd_servers')->get()->getResult();
			//$this->load->view('modal/activate', $data);
			return view('modules/domains/modal/activate', $data);
		}
	}

	function cancel($id = null)
	{	
		$app = new App();
		$request = \Config\Services::request();
		$helper = new custom_name_helper();
		$db = \Config\Database::connect();
		
		$app->module_access('menu_accounts');
		if ($request->getPost()) {

			if ($request->getPost('credit_account') == 'on') {
				Invoice::credit_item($request->getPost('id'));
			}

			$domain = $request->getPost('domain');
			$order_id = $request->getPost('id');

			if ($request->getPost('cancel_domain') == 'on') {
				$db->table('hd_orders')->set('status_id', 7)->where('id', $order_id)->update();

				if ($db->affectedRows() > 0) {
					$result = "Domain: " . $domain . " cancelled!";

					if ($request->getPost('order') == 'domain_only') {
						$db->table('hd_invoices')->set('inv_deleted', "Yes")->where('inv_id', $request->getPost('inv_id'))->update();
					}
				}
			}

			session()->setFlashdata('response_status', 'success');
			$referer = $this->request->getServer('HTTP_REFERER');
			session()->setFlashdata('success', lang('hd_lang.user_edited_successfully'));
			return redirect()->to($referer);
		} else {
			$data['item'] = Order::get_order($id);
			$data['servers'] = $db->table('hd_servers')->get()->getResult();
			//$this->load->view('modal/cancel', $data);
			return view('modules/domains/modal/cancel', $data);
		}
	}


	function delete($id = null)
	{	
		$app = new App();
		$db = \Config\Database::connect();
		$app->module_access('menu_domains');
		$request = \Config\Services::request();
		if ($request->getPost()) {
			if ($request->getPost('credit_account') == 'on') {
				Invoice::credit_item($request->getPost('id'));
			}

			$domain = $request->getPost('domain');
			$order_id = $request->getPost('id');

			if ($request->getPost('delete_domain') == 'on') {
				
				$db->table('hd_orders')->set('status_id', 8)->where('id', $order_id)->update();

				if ($db->affectedRows() > 0) {
					$result = "Domain: " . $domain . " deleted!";

					if ($request->getPost('order') == 'domain_only') {
						$db->table('hd_invoices')->set('inv_deleted', "Yes")->where('inv_id', $request->getPost('inv_id'))->update();
					}
				}
			}

			session()->setFlashdata('response_status', 'success');
			$referer = $this->request->getServer('HTTP_REFERER');
			session()->setFlashdata('success', lang('hd_lang.user_edited_successfully'));
			return redirect()->to($referer);
		} else {
			$data['item'] = Order::get_order($id);
			$db = \Config\Database::connect();
			$data['servers'] = $db->table('hd_servers')->get()->getResult();
			//$this->load->view('modal/delete', $data);
			return view('modules/domains/modal/delete', $data);
		}
	}


	function proccess()
	{

		$registrar = $this->uri->segment(3);
		$action = "/" . $this->uri->segment(4);
		$order = $this->uri->segment(5);

		$nameservers = Order::get_nameservers($order);

		if ($nameservers != '') {
			$nameservers = explode(",", $nameservers);
		} else {
			$nameservers = array();

			if (config_item('nameserver_one') != '') {
				$nameservers[] = config_item('nameserver_one');
			}
			if (config_item('nameserver_two') != '') {
				$nameservers[] = config_item('nameserver_two');
			}
			if (config_item('nameserver_three') != '') {
				$nameservers[] = config_item('nameserver_three');
			}
			if (config_item('nameserver_four') != '') {
				$nameservers[] = config_item('nameserver_four');
			}
			if (config_item('nameserver_five') != '') {
				$nameservers[] = config_item('nameserver_five');
			}
		}

		$process = modules::run($registrar . $action, $order, $nameservers);
		$data = array(
			'user' => User::get_id(),
			'module' => 'accounts',
			'module_field_id' => $order,
			'activity' => $process,
			'icon' => 'fa-globe',
			'value1' => $registrar,
			'value2' => ''
		);
		App::Log($data);

		$this->session->set_flashdata('response_status', 'info');
		$this->session->set_flashdata('message', $process);
		redirect($_SERVER['HTTP_REFERER']);
	}

	function domain_pricing()
	{
		return $this->itemModel::get_domains();
	}


	function check_availability()
	{	
		$custom = new custom_name_helper();

		$request = \Config\Services::request();

		$domain = $request->getPost('domain');
		
		$type = $request->getPost('type');

		$tlds = $request->getPost('ext');
		
		session()->set('domain_set', 'yes');

		$data = [
			'domain' => $domain,
			'tlds' => $tlds
		];
		//print_r($data);die;
		$domain_checker = $custom->getconfig_item('domain_checker');
		//print_r($domain_checker);die;
		$session = \Config\Services::session();

		// Connect to the database
		$dbName = \Config\Database::connect();

		// Construct the query
		//$query = $dbName->table('hd_items_saved')
		//->select('item_name, registration, renewal, transfer, max_years')
		//->join('hd_item_pricing', 'hd_items_saved.item_id = hd_item_pricing.item_id', 'INNER')
		//->where('item_name', $tlds)
		//->get();

		// Retrieve the result row
		// $ext = $query->getRow();

		if ($tlds == '.com') {
			$replace = str_replace(".com", "dotcompany", $tlds);
		} else {
			$replace = str_replace(".", "dot", $tlds);
		}
		
		$request = $dbName->table('hd_plugins')->select('config')->where('system_name', 'resellerclub')->get()->getRow()->config;
		$results = json_decode($request);
		$res = '';

		//echo "<pre>";print_r($results);die;

		if ($results->customerpay_mode == 'API') {
			
			$controller = new Resellerclub();
			$result = $controller->api_customerprice($replace);
			$get_currency = $custom->getconfig_item('default_currency');
			$get_currency_lowercase = strtolower($get_currency);
			$query = $dbName->table('hd_currencies')->where('code', $get_currency)->get()->getResult();
		} else {
			$query = $result = $dbName->table('hd_domains')->where('ext_name', $tlds)->get()->getRow();
			$total = $query->registration_1;
			//echo "<pre>";print_r($total);die;
		}

		$setting_invoice = $custom->getconfig_item('round_off_value');
		//print_r($setting_invoice);die;
		$options = '';

		$dbquery = $dbName->table('hd_customer_pricing')->where('domain_name', $replace)->get()->getRow();
		//print_r($dbquery);die;
		if ($type == lang('hd_lang.domain_registration')) {
			
			$unavailable = array('result' => "<div class='alert alert-danger'><strong>" . $domain . "</strong> " . lang('hd_lang.is_registered') . "</div>");

			//echo Applib::format_currency($dbquery->cost,'default_currency');die;

			$ext = $dbName->table('hd_items_saved')->where('item_name', $tlds)->get()->getRow();
			
			//echo "<pre>";print_r($query);die;
			//echo "<pre>";print_r($total);die;
			//print_r($ext);die;
			if ($ext->max_years > 1) {
    $options = "<div class='alert alert-success'><strong>" . $domain . "</strong> " . lang('hd_lang.is_available') . " &nbsp; &nbsp; <select id='domain_price'><option value=\"\">Select</option>";
    $i = 0;
    while ($i < $ext->max_years) {
        if ($results->customerpay_mode == 'API') {
            $res = $total = $query[0]->xrate * $result[$i];
        } else {
            $res = $total = 0;

            for ($j = 1; $j <= 10; $j++) {
                $key = 'registration_' . $j;

                if (isset($query->$key)) {
                    $res += $query->$key;
                    $total += $query->$key; 
                }
            }
        }

        // Multiply $res by the number of years
        $yearly_res = $res * ($i + 1);

        $options .= '<option value="' . $yearly_res . '|' . ($i+1) . '">' . AppLib::format_currency($yearly_res, 'default_currency') . ' - ' . ($i+1) . ' ' . lang('hd_lang.years') . '</option>';

        $i++;
    }

    $options .= '</select>';

    $available = array(
        'domain' => $domain,
        'price' => $yearly_res,
        'result' => $options . "&nbsp; &nbsp; <span id='add_available' class='btn btn-success' onClick='$(this).continueOrder()'>" . lang('hd_lang.add_to_cart') . "</span></div>"
    );
} else {
				//echo 789;die;
				$available = array(
					'domain' => $domain,
					'price' => $dbquery,
					'result' => "<div class='alert alert-success'><strong>" . $domain . "</strong> " . lang('hd_lang.is_available') . " &nbsp; | &nbsp; " .
						Applib::format_currency($ext->registration, 'default_currency') . " " . lang('hd_lang.per_year') . " &nbsp; | &nbsp; <span id='add_available' class='btn btn-success' onClick='$(this).continueOrder()'>" . lang('hd_lang.add_to_cart') . "</span></div>"
				);
			}

			// $available = array(
			// 	'domain' => $domain,
			// 	'price' => $res,
			// 	'result' => "<div class='alert alert-success'><strong>" . $domain . "</strong> " . lang('hd_lang.is_available') . " &nbsp; | &nbsp; " .
			// 		Applib::format_currency($res, 'default_currency') . " " . lang('hd_lang.per_year') . " &nbsp; | &nbsp; <span id='add_available' class='btn btn-success' onClick='$(this).continueOrder()'>" . lang('hd_lang.add_to_cart') . "</span></div>"
			// );

			$error = array('result' => "<div class='alert alert-danger'><strong>" . $domain . "</strong> " . lang('hd_lang.error_checking') . "</div>");
			
			if ($custom->getconfig_item('domain_checker') != 'default') {
				
				// $result = modules::run($domain_checker, $domain_name[0], $domain_name[1]);
				switch ($domain_checker) {
					case 'resellerclub':
						$resellerclub = new APIController();
						$resultRes = $resellerclub->checkavailability($data);
						break;
				}
				
				$unknown = array(
					'result' => "<div class='alert alert-danger'><strong>" . $domain . "</strong> the Registry connections are not available</div>"
				);
				
				// echo "<pre>";print_r($resultRes);die;

				switch ($resultRes) {
					case $resultRes['data'][$domain]['status'] == 'available':
						return $this->response
							->setStatusCode(200)
							->setContentType('application/json')
							->setJSON($available);

					case $resultRes['data'][$domain]['status'] == 'regthroughus':
						return $this->response
							->setStatusCode(200)
							->setContentType('application/json')
							->setJSON($unavailable);

					case $resultRes['data'][$domain]['status'] == 'regthroughothers':
						return $this->response
							->setStatusCode(200)
							->setContentType('application/json')
							->setJSON($unavailable);

					case $resultRes['data'][$domain]['status'] == 'unknown':
						return $this->response
							->setStatusCode(400)
							->setContentType('application/json')
							->setJSON($unknown);

					default:
						return $this->response
							->setStatusCode(400) // Adjust the status code as needed
							->setContentType('application/json')
							->setJSON($error);
				}
			} else {
				if (gethostbyname($domain) != $domain) {
					//$this->output->set_content_type('application/json')->set_output(json_encode($unavailable));
					return $this->response
							->setStatusCode(404) // Adjust the status code as needed
							->setContentType('application/json')
							->setJSON($unavailable);
				} else {
					//$this->output->set_content_type('application/json')->set_output(json_encode($available));
					return $this->response
							->setStatusCode(200)
							->setContentType('application/json')
							->setJSON($available);
				}
			}
		}


		if ($type == lang('hd_lang.domain_transfer')) {

			$unavailable = array('result' => "<div class='alert alert-danger'><strong>" . $domain . "</strong> " . lang('hd_lang.is_not_registered') . "</div>");

			$available = array('domain' => $domain, 'price' => $ext->transfer, 'result' => "<div id='add_available' class='alert alert-success'><strong>" . $domain . "</strong> " . lang('hd_lang.is_available_transfer') . " &nbsp; | &nbsp; " . Applib::format_currency($ext->transfer, 'default_currency') . " " . lang('hd_lang.per_year') . " &nbsp; | &nbsp; <span class='btn btn-success' onClick='$(this).continueOrder()'>" . lang('hd_lang.add_to_cart') . "</span></div>");

			$error = array('result' => "<div class='alert alert-danger'><strong>" . $domain . "</strong> " . lang('hd_lang.error_checking') . "</div>");

			// if($custom->getconfig_item('domain_checker') != 'default') {
			//     // echo 78;die;

			//     $whoiszmlapi = new Whoisxmlapi();

			//     // $result = modules::run($domain_checker, $domain_name[0], $domain_name[1]);   
			//     $result = $whoiszmlapi->check_domain($domain_name[0], $domain_name[1]);

			//     switch ($result) {
			//         case 0:
			//             return $this->response
			//                 ->setStatusCode(200)
			//                 ->setContentType('application/json')
			//                 ->setJSON($available);

			//         case 1:
			//             return $this->response
			//                 ->setStatusCode(200)
			//                 ->setContentType('application/json')
			//                 ->setJSON($unavailable);

			//         default:
			//             return $this->response
			//                 ->setStatusCode(200) // Adjust the status code as needed
			//                 ->setContentType('application/json')
			//                 ->setJSON($error);
			//     }
			//     }
			//     else {
			//         if (gethostbyname($domain) != $domain) {                
			//             $this->output->set_content_type('application/json')->set_output(json_encode($available));  
			//         }
			//         else {
			//                 $this->output->set_content_type('application/json')->set_output(json_encode($unavailable)); 
			//         }
			//     }  

			// }
		}
	}

	function check_availability_text()
	{
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
		
		$custom = new custom_name_helper();

		$request = \Config\Services::request();

		$domain = $request->getPost('domain');
		
		$type = $request->getPost('type');

		$tlds = $request->getPost('ext');

		$data = [
			'domain' => $domain,
			'tlds' => $tlds
		];
		//print_r($data);die;
		$domain_checker = $custom->getconfig_item('domain_checker');
		//print_r($domain_checker);die;
		$session = \Config\Services::session();

		// Connect to the database
		$dbName = \Config\Database::connect();

		// Construct the query
		//$query = $dbName->table('hd_items_saved')
		//->select('item_name, registration, renewal, transfer, max_years')
		//->join('hd_item_pricing', 'hd_items_saved.item_id = hd_item_pricing.item_id', 'INNER')
		//->where('item_name', $tlds)
		//->get();

		// Retrieve the result row
		// $ext = $query->getRow();

		if ($tlds == '.com') {
			$replace = str_replace(".com", "dotcompany", $tlds);
		} else {
			$replace = str_replace(".", "dot", $tlds);
		}

		$request = $dbName->table('hd_plugins')->select('config')->where('system_name', 'resellerclub')->get()->getRow()->config;
		$results = json_decode($request);
		$res = '';

		//echo "<pre>";print_r($results);die;

		if ($results->customerpay_mode == 'API') {
			
			$controller = new Resellerclub();
			$result = $controller->api_customerprice($replace);
			// echo "<pre>";print_r($result);die;
			$get_currency = $custom->getconfig_item('default_currency');
			//echo"<pre>";print_r($get_currency);die;
			$get_currency_lowercase = strtolower($get_currency);
			//$controller = new APIController();
			//$x_rates = $controller->x_rates($get_currency_lowercase);
			$query = $dbName->table('hd_currencies')->where('code', $get_currency)->get()->getResult();
			// echo"<pre>";print_r($query);die;
			// $res = $total = $query[0]->xrate * $result;
		} else {
			$query = $result = $dbName->table('hd_domains')->where('ext_name', $tlds)->get()->getRow();
			$total = $query->registration_1;
			//echo "<pre>";print_r($query);die;
		}

		$setting_invoice = $custom->getconfig_item('round_off_value');
		//print_r($setting_invoice);die;
		$options = '';

		$dbquery = $dbName->table('hd_customer_pricing')->where('domain_name', $replace)->get()->getRow();
		//print_r($dbquery);die;
		if ($type == lang('hd_lang.domain_registration')) {
			$unavailable = array('result' => "<div class='alert alert-danger'><strong>" . $domain . "</strong> " . lang('hd_lang.is_registered') . "</div>");

			//echo Applib::format_currency($dbquery->cost,'default_currency');die;

			$ext = $dbName->table('hd_items_saved')->where('item_name', $tlds)->get()->getRow();

			// echo "<pre>";print_r($ext);die;
			//echo "<pre>";print_r($query);die;

			if ($ext->max_years > 1) {
				$options = "<div class='alert alert-success'><strong>" . $domain . "</strong> " . lang('hd_lang.is_available') . " &nbsp; &nbsp;";
				$i = 0;

				$available = array(
					'domain' => $domain,
					'price' => $res,
					'result' => $options 
				);
			} else {
				$available = array(
					'domain' => $domain,
					'price' => $dbquery,
					'result' => "<div class='alert alert-success'><strong>" . $domain . "</strong> " . lang('hd_lang.is_available') . "</div>"
				);
			}

			$error = array('result' => "<div class='alert alert-danger'><strong>" . $domain . "</strong> " . lang('hd_lang.error_checking') . "</div>");

			if ($custom->getconfig_item('domain_checker') != 'default') {
				// $result = modules::run($domain_checker, $domain_name[0], $domain_name[1]);
				switch ($domain_checker) {
					case 'resellerclub':
						$resellerclub = new APIController();
						// echo "efffe";die;
						$resultRes = $resellerclub->checkavailability($data);
						break;
				}

				$unknown = array(
					'result' => "<div class='alert alert-danger'><strong>" . $domain . "</strong> the Registry connections are not available</div>"
				);

				switch ($resultRes) {
					case $resultRes['data'][$domain]['status'] == 'available':
						return $this->response
							->setStatusCode(200)
							->setContentType('application/json')
							->setJSON($available);

					case $resultRes['data'][$domain]['status'] == 'regthroughus':
						return $this->response
							->setStatusCode(200)
							->setContentType('application/json')
							->setJSON($unavailable);

					case $resultRes['data'][$domain]['status'] == 'regthroughothers':
						return $this->response
							->setStatusCode(200)
							->setContentType('application/json')
							->setJSON($unavailable);

					case $resultRes['data'][$domain]['status'] == 'unknown':
						return $this->response
							->setStatusCode(400)
							->setContentType('application/json')
							->setJSON($unknown);

					default:
						return $this->response
							->setStatusCode(400) // Adjust the status code as needed
							->setContentType('application/json')
							->setJSON($error);
				}
			} else {
				echo 103;
				die;
				if (gethostbyname($domain) != $domain) {
					$this->output->set_content_type('application/json')->set_output(json_encode($unavailable));
				} else {
					$this->output->set_content_type('application/json')->set_output(json_encode($available));
				}
			}
		}


		if ($type == lang('hd_lang.domain_transfer')) {

			$unavailable = array('result' => "<div class='alert alert-danger'><strong>" . $domain . "</strong> " . lang('hd_lang.is_not_registered') . "</div>");

			$available = array('domain' => $domain, 'price' => $ext->transfer, 'result' => "<div id='add_available' class='alert alert-success'><strong>" . $domain . "</strong> " . lang('hd_lang.is_available_transfer') . " &nbsp; | &nbsp; " . Applib::format_currency($ext->transfer, 'default_currency') . " " . lang('hd_lang.per_year') . " &nbsp; | &nbsp; <span class='btn btn-success' onClick='$(this).continueOrder()'>" . lang('hd_lang.add_to_cart') . "</span></div>");

			$error = array('result' => "<div class='alert alert-danger'><strong>" . $domain . "</strong> " . lang('hd_lang.error_checking') . "</div>");

			// if($custom->getconfig_item('domain_checker') != 'default') {
			//     // echo 78;die;

			//     $whoiszmlapi = new Whoisxmlapi();

			//     // $result = modules::run($domain_checker, $domain_name[0], $domain_name[1]);   
			//     $result = $whoiszmlapi->check_domain($domain_name[0], $domain_name[1]);

			//     switch ($result) {
			//         case 0:
			//             return $this->response
			//                 ->setStatusCode(200)
			//                 ->setContentType('application/json')
			//                 ->setJSON($available);

			//         case 1:
			//             return $this->response
			//                 ->setStatusCode(200)
			//                 ->setContentType('application/json')
			//                 ->setJSON($unavailable);

			//         default:
			//             return $this->response
			//                 ->setStatusCode(200) // Adjust the status code as needed
			//                 ->setContentType('application/json')
			//                 ->setJSON($error);
			//     }
			//     }
			//     else {
			//         if (gethostbyname($domain) != $domain) {                
			//             $this->output->set_content_type('application/json')->set_output(json_encode($available));  
			//         }
			//         else {
			//                 $this->output->set_content_type('application/json')->set_output(json_encode($unavailable)); 
			//         }
			//     }  

			// }
		}
	}


	function manage($id = null)
	{	
		$request = \Config\Services::request();
		$helper = new custom_name_helper();
		$db = \Config\Database::connect();
		$template = new Template();
		if (User::is_admin() || User::perm_allowed(User::get_id(), 'manage_accounts')) {

			if ($request->getPost()) {

				if (App::update('orders', array('id' => $request->getPost('id')), $request->getPost())) {
					Applib::go_to('domains/domain/' . $request->getPost('id'), 'success', lang('hd_lang.domain_updated'));
				} else {
					redirect($_SERVER['HTTP_REFERER']);
				}
			} else {
				$template->title(lang('hd_lang.account') . ' - ' . $helper->getconfig_item('company_name'));
				$data['account'] = array();
				$data['account_details'] = true;
				$data['page'] = lang('hd_lang.account');
				$data['datepicker'] = true;
				$data['form'] = true;
				$data['id'] = $id;
				//$this->template
					//->set_layout('users')
					//->build('manage', $data);
				echo view('modules/domains/manage', $data);
			}
		} else {
			redirect(base_url() . "domains");
		}
	}



	function domain($id)
	{
		$clientModel = new Client();

		$order = Order::get_order($id);
		$client = $clientModel->get_by_user(User::get_id());
		if (User::is_admin() || User::perm_allowed(User::get_id(), 'manage_accounts') || (isset($client) && $client->co_id == $order->client_id)) {
			$data['page'] = lang('hd_lang.manage_domain');
			$data['id'] = $id;
			$data['order'] = $order;
			$data['co_id'] = $client->co_id;

			return view('modules/domains/domain', $data);
		} else {
			return redirect()->to('domains');
		}
	}



	function manage_nameservers($id)
	{
		$data['page'] = lang('hd_lang.update_nameservers');
		$data['id'] = $id;
		// $this->load->view('domains/modal/nameservers', $data);
		return view('modules/domains/modal/nameservers', $data);
	}


	function suspend($id)
	{
		$data['page'] = lang('hd_lang.suspend');
		$data['id'] = $id;
		// $this->load->view('modal/suspend', $data);
		return view('modules/domains/modal/suspend', $data);
	}
	
    public function upload()
    {
        $session = session();
		$db = \Config\Database::connect();
        
        if ($this->request->getMethod() === 'post') {

            $file = $this->request->getFile('import');
            
            if ($file && $file->isValid() && !$file->hasMoved()) {
                
                $valid = false;
                $types = ['Xlsx', 'Xls', 'Csv']; // PhpSpreadsheet types
                
                foreach ($types as $type) {
                    try {
                        $reader = IOFactory::createReader($type);
                        if ($reader->canRead($file->getTempName())) {
                            $valid = true;
                            break;
                        }
                    } catch (\Exception $e) {
                        continue;
                    }
                }

                if ($valid) {
                    try {
                        $spreadsheet = IOFactory::load($file->getTempName());
                        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);  

                        $domains = [];
                        for ($x = 3; $x <= count($sheetData); $x++) {
                            $row = $sheetData[$x];

                            // Check if the required keys exist in the row
                            if (isset($row["A"], $row["B"], $row["E"], $row["F"], $row["I"], $row["J"], $row["K"], $row["O"], $row["P"])) {
                                if ($db->table('hd_orders')->where('domain', $row["F"])
                                    ->where('client_id', $row["B"])
                                    ->where('type !=', 'hosting')
                                    ->countAllResults() == 0) 
                                {	
                                    $domain = [
                                        'id' => $row["A"],
                                        'user_id' => $row["B"],
                                        'type' => $row["E"],
                                        'domain' => $row["F"],
                                        'period' => $row["I"],
                                        'registration' => $row["J"],
                                        'expires' => $row["K"],
                                        'status' => $row["O"],
                                        'notes' => $row["P"]
                                    ];					 
                                    $domains[] = (object) $domain;		
                                }
                            }
                        }

                        $session->set('import_domains', $domains);

                    } catch (\Exception $e) {
                        $session->setFlashdata('response_status', 'warning');
                        $session->setFlashdata('message', "Error loading file: " . $e->getMessage());			
                         return redirect()->to('domains/upload');						
                    }

                } else {
                    $session->setFlashdata('response_status', 'warning');
                    $session->setFlashdata('message', lang('not_csv'));			
                    return redirect()->to('domains/upload');
                }
            } else {
                $session->setFlashdata('response_status', 'warning');
                $session->setFlashdata('message', lang('no_csv'));			
               return redirect()->to('domains/upload');
            }			

            return redirect()->to('domains/import');

        } else {  
           $template = new Template();
		   $template->title(lang('hd_lang.import'));
		   $data['page'] = lang('hd_lang.domains');
           return view('modules/domains/upload', $data);
        }
    }


	function import_domains()
	{
		$count = 0;
		$request = \Config\Services::request();
		$db = \Config\Database::connect();
		if ($request->getPost()) {
			$array = array();
			$list = array();

			foreach ($request->getPost() as $k => $r) {
				if ($k != 'registrar') {
					$array[] = $k;
				}
			}

			$accounts = session()->get('import_domains');
			foreach ($accounts as $k => $r) {
				if (in_array($r->id, $array)) {
					$list[] = $r;
				}
			}

			if (count($list) > 0) {

				foreach ($list as $client) {
					if ($db->table('hd_companies')->where('co_id', $client->user_id)->where('imported', 1)->get()->getNumRows() > 0) {
						$tld = explode('.', $client->domain, 2);
						$ext = $tld[1];
						$item = $db->table('hd_items_saved')->where('item_name', $ext)->join('item_pricing', 'item_pricing.item_id = items_saved.item_id')->get()->getRow();

						if ($item) {

							$items = array(
								'invoice_id' 	=> 0,
								'item_name'		=> "Domain " . $client->type,
								'item_desc'		=> '-',
								'unit_cost'		=> $item->renewal,
								'item_order'	=> 1,
								'item_tax_rate'	=> 0,
								'item_tax_total' => 0,
								'quantity'		=> 1,
								'total_cost'	=> $item->renewal
							);

							$item_id = App::save_data('items', $items);
							$time = strtotime($client->registration);

							switch ($client->status) {
								case 'Active':
									$status = 6;
									break;
								case 'Cancelled':
									$status = 7;
									break;
								case 'Expired':
									$status = 11;
									break;
								case 'Pending':
									$status = 5;
									break;
								case 'Pending Transfer':
									$status = 5;
									break;
								case 'Terminated':
									$status = 8;
									break;
							}

							$order = array(
								'client_id' 	=> $client->user_id,
								'invoice_id'    => 0,
								'date'          => $client->registration . " " . date('H:i:s'),
								'item'		    => $item_id,
								'domain'        => $client->domain,
								'item_parent'   => $item->item_id,
								'type'		    => 'domain',
								'process_id'    => $time,
								'order_id'      => $time,
								'registrar' 	=> $request->getPost('registrar'),
								'fee'           => 0,
								'processed'     => $client->registration,
								'renewal_date'  => $client->expires,
								'years'			=> $client->period,
								'status_id'     => $status,
								'renewal'       => 'annually'
							);

							if ($order_id = App::save_data('orders', $order)) {
								$count++;
							}
						}
					}
				}
			}

			session()->remove('import');

			session()->setFlashdata('response_status', 'info');
			session()->setFlashdata('message', "Created " . $count . " domains");
			if ($count == 0) {
				return redirect($_SERVER['HTTP_REFERER']);
			} else {
				return redirect()->to('domains');
			}
		} else {
			//$this->template->title(lang('hd_lang.import'));
			$data['page'] = lang('hd_lang.domains');
			$data['datatables'] = TRUE;
			echo view('modules/domains/import', isset($data) ? $data : NULL);
		}
	}



	function import()
	{

		// $this->load->module('layouts');
		// $this->load->library('template');
		$template = new Template();

		$template->title(lang('hd_lang.import'));
		$data['page'] = lang('hd_lang.domains');
		// $this->template
		// ->set_layout('users')
		// ->build('import',isset($data) ? $data : NULL); 
		return view('modules/domains/import', $data);
	}

	function resellerclub_modify_details()
	{
		$request = \Config\Services::request();

		$session = \Config\Services::session();

		// Connect to the database  
		$db = \Config\Database::connect();

		$co_id = $request->getPost('co_id');

		$contact_detail = $db->table('hd_resellerclub_customer_contact')->where('co_id', $co_id)->get()->getRow();

		return json_encode($contact_detail);
	}

	function edit_contact_details()
	{
		$request = \Config\Services::request();

		$session = \Config\Services::session();

		// Connect to the database  
		$db = \Config\Database::connect();

		$formData = [
			"co_id" => $request->getPost('co_id'),
			"contact-id" => $request->getPost('contact_id'),
            "name" => $request->getPost('name'),
            "company" => $request->getPost('company'),
            "email" => $request->getPost('email'),
            "address-line-1" => $request->getPost('address-line-1'),
            "address-line-2" => $request->getPost('address-line-2'),
            "city" => $request->getPost('city'),
            "country" => $request->getPost('country'),
            "zipcode" => $request->getPost('zipcode'),
            "phone-cc" => $request->getPost('phone-cc'),
            "phone" => $request->getPost('phone')
		];

		$apiController = new APIController();

		$modifyCntact = $apiController->modify_contact_details($formData);
	}

	function edit_domain()
	{
		$request = \Config\Services::request();

		$session = \Config\Services::session();

		// Connect to the database  
		$db = \Config\Database::connect();

		// echo "<pre>";print_r($request->getPost());die();

		$formData = [
			"client_id" => $request->getPost('co_id'),
            "ns1" => $request->getPost('nameserver_one'),
            "ns2" => $request->getPost('nameserver_two'),
            "ns3" => $request->getPost('nameserver_three'),
            "ns4" => $request->getPost('nameserver_four'),
            "ns5" => $request->getPost('nameserver_five'),
            "promo" => $request->getPost('promo_code'),
            // "subscription_id" => $request->getPost('subscription_id'),
            // "registration_per" => $request->getPost('registration_per'),
            // "registration_date" => $request->getPost('registration_date'),
            // "expiry_date" => $request->getPost('expiry_date'),
            // "next_due_date" => $request->getPost('next_due_date'),
            "reset_domain" => $request->getPost('reset_domain'),
            "enable" => $request->getPost('enable'),
            "dns_management" => $request->getPost('dns_management'),
            "email_forwarding" => $request->getPost('email_forwarding'),
            "id_protection" => $request->getPost('id_protection'),
            "auto_renew" => $request->getPost('auto_renew')
		];

		$db->table('hd_orders')->where('id', $request->getPost('order_id'))->update($formData);

		return redirect()->to('domains/domain/' . $request->getPost('order_id'));
	}
}
