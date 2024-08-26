<?php
 /*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

namespace app\Modules\servers\controllers;

use App\Libraries\AppLib;
use App\Models\App;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Plugin;
use App\Models\Servers as Servers_model;
use App\Models\User;
use App\ThirdParty\MX\WhatPanel;
use App\ThirdParty\MX\Modules;
use PasswordHash;
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
use Modules\plesk\controllers\Plesk_WHMCS;
use Modules\cpanel\controllers\new_Cpanel;

class Servers extends WhatPanel
{
    protected $dbName;
    protected $pluginModel;
    protected $serverModel;

    function __construct()
    {
        parent::__construct();
        // $this->load->module('layouts');
        // $this->load->library(array('template'));

        //User::logged_in();
        // $this->load->library('encryption');
        // $this->encryption->initialize(
        //     array(
        //         'cipher' => 'aes-256',
        //         'driver' => 'openssl',
        //         'mode' => 'ctr'
        //     )
        // );

        $session = \Config\Services::session();        
        // Connect to the database
        $this->dbName = \Config\Database::connect();

        $this->pluginModel = new Plugin($this->dbName);

        $this->serverModel = new Servers_model();
    }



    function index($id = null)
    {
        error_reporting(E_ALL);
        ini_set("display_errors", "1");
        
        $request = \Config\Services::request();
		
        if ($id) {
            $server = $this->dbName->table('hd_servers')->where('id', $id)->get()->getRow();
            if(!empty($server)) {
                $response = $this->check_connection($id);
                $data['response'] = $server->name . ": " . $response;
            }
        }
		
        //$this->template->title(lang('hd_lang.servers'));
        $data['page'] = lang('hd_lang.servers');
        $data['datatables'] = TRUE;

        // Pagination Configuration
		$page = $request->getGet('page') ? $request->getGet('page') : 1;

        $perPage = $request->getGet('recordsPerPage', FILTER_SANITIZE_NUMBER_INT) ? $request->getGet('recordsPerPage', FILTER_SANITIZE_NUMBER_INT) : 10;

		// Search Filter
		$search = $request->getGet('search');
		$data['search'] = $search;

        $query = $this->serverModel->listItems([], $search, $perPage, $page);

        // Get items for the current page
		$data['servers'] = array_map(function($item) {
			return (object) $item;
		}, $query['items']);

        $data['pager'] = $query['pager'];

		$data['message'] = $query['message'];

		$data['perPage'] = $perPage;

        // echo "<pre>";print_r($data);die;
        // $this->template
        //     ->set_layout('users')
        //     ->build('index', isset($data) ? $data : NULL);
        return view('modules/servers/index', $data);
    }



     function add_server()
    {
        $request = \Config\Services::request();
        if ($request->getPost()) {
            //Applib::is_demo();

            $data = array(
       		 'type' => empty($request->getPost('type')) ? '' : $request->getPost('type'),
			 'name' => $request->getPost('name'),	
			 'hostname' => $request->getPost('hostname'),
			 'port' => $request->getPost('port'),
			 'use_ssl' => ($request->getPost('use_ssl') == 'on') ? 'Yes' : 'No',
			 'username' => $request->getPost('username'),
			 'password' => $request->getPost('password'),
			 'authkey' => $request->getPost('authkey'),
			 'ip' => $request->getPost('ip'),
			 'selected' => ($request->getPost('selected') == 'on') ? 1 : 0,
			 'ns1' => $request->getPost('ns1'),
			 'ns2' => $request->getPost('ns2'),
			 'ns3' => $request->getPost('ns3'),
			 'ns4' => $request->getPost('ns4'),
			 'ns5' => $request->getPost('ns5'),
             'ip1' => $request->getPost('ip1'),
			 'ip2' => $request->getPost('ip2'),
			 'ip3' => $request->getPost('ip3'),
			 'ip4' => $request->getPost('ip4'),
			 'ip5' => $request->getPost('ip5'),
			);

            $session = \Config\Services::session();

			
			// Connect to the database
			$dbName = \Config\Database::connect();

            if ($dbName->table('hd_servers')->insert($data)) {
                //$this->session->set_flashdata('response_status', 'success');
                //$this->session->set_flashdata('message', lang('hd_lang.server_added'));

                $session = \Config\Services::session();

            	$session->setFlashdata('response_status', 'success');
            	$session->setFlashdata('message', lang('hd_lang.server_added'));

                $redirectUrl = $request->getPost('r_url');
				return redirect()->to($redirectUrl);
            }
        } else {
            $data['servers'] = $this->pluginModel::servers();
            // $this->load->view('modal/add_server', $data);
            echo view('modules/servers/modal/add_server' ,$data);
        }
    }



        function edit_server($id = null)
    {
        $request = \Config\Services::request();
        if ($request->getPost()) {
            //Applib::is_demo();

            $session = \Config\Services::session();

			// Connect to the database
			$dbName = \Config\Database::connect();

            if ($request->getPost('selecte3d') == 'on') {
                $data = array(
                    'selected' => 0
                );

                $dbName->table('hd_servers')->where('selected', 1)->update($data);
            }

            $id = (isset($id)) ? $id : $request->getPost('item_id');

            $update = array(
                "type" => empty($request->getPost('type')) ? '' : $request->getPost('type'),
                "name" => $request->getPost('name'),
                "selected" => ($request->getPost('selected') == 'on') ? 1 : 0,
                "use_ssl" => ($request->getPost('use_ssl') == 'on') ? 'Yes' : 'No',
                "hostname" => $request->getPost('hostname'),
                "port" => $request->getPost('port'),
                "username" => $request->getPost('username'),
                "password" => $request->getPost('password'),
                "authkey" => $request->getPost('authkey'),
                "ns1" => $request->getPost('ns1'),
                "ns2" => $request->getPost('ns2'),
                "ns3" => $request->getPost('ns3'),
                "ns4" => $request->getPost('ns4'),
                "ns5" => $request->getPost('ns5')
            );

            if ($dbName->table('hd_servers')->where('id', $id)->update($update)) {
                // $this->session->set_flashdata('response_status', 'success');
                // $this->session->set_flashdata('message', lang('hd_lang.server_edited'));
                $session = \Config\Services::session();

            	$session->setFlashdata('response_status', 'success');
            	$session->setFlashdata('message', lang('hd_lang.server_edited'));

                $redirectUrl = $request->getPost('r_url');
				return redirect()->to($redirectUrl);
            }
        } else {
            // $data['data'] = $this->db->where(array('id' => $id))->get('servers')->row();
            $data['data'] = $this->dbName->table('hd_servers')->where('id', $id)->get()->getRow();
            // $this->load->view('modal/edit_server', $data);
            echo view('modules/servers/modal/edit_server' ,$data);
        }
    }



    function delete_server($id = NULL)
    {
        $request = \Config\Services::request();
        if ($request->getPost()) {
            // Applib::is_demo();
            $server_id = $request->getPost('id');

            $session = \Config\Services::session();
			
			// Connect to the database
			$dbName = \Config\Database::connect();

            // App::delete('servers', array('id' => $server_id));
            $dbName->table('hd_servers')->where('id', $server_id)->delete();

            // $this->session->set_flashdata('response_status', 'success');
            // $this->session->set_flashdata('message', lang('hd_lang.server_deleted'));

            $session = \Config\Services::session();

            $session->setFlashdata('response_status', 'success');
            $session->setFlashdata('message', lang('hd_lang.server_edited'));

            $redirectUrl = $request->getPost('r_url');
			return redirect()->to($redirectUrl);
        } else {
            
            $data['id'] = $id;
            // $this->load->view('modal/delete_server', $data);
            echo view('modules/servers/modal/delete_server' ,$data);
        }

    }



    function create_order($item, $co_id, $a, $id)
    {
        $items = array(
            'invoice_id' => 0,
            'item_name' => $item->item_name,
            'item_desc' => '-',
            'unit_cost' => $item->unit_cost,
            'item_order' => 1,
            'item_tax_rate' => $item->item_tax_rate,
            'item_tax_total' => $item->item_tax_total,
            'quantity' => 1,
            'total_cost' => $item->total_cost,
        );

        $item_id = App::save_data('hd_items', $items);

        $time = strtotime($a['startdate']);
        $date = gmdate("Y-m-d", $time);


        $order = array(
            'client_id' => $co_id,
            'invoice_id' => 0,
            'date' => date('Y-m-d H:i:s'),
            'nameservers' => "",
            'item' => $item_id,
            'domain' => $a['domain'],
            'item_parent' => $item->item_id,
            'type' => 'hosting',
            'process_id' => $time,
            'order_id' => $time,
            'fee' => 0,
            'processed' => $date,
            'username' => $a['user'],
            'password' => $a['pass'],
            'renewal_date' => $date,
            'status_id' => 6,
            'server' => $id,
            'renewal' => ''
        );

        return App::save_data('hd_orders', $order);
    }



    function check_connection($id)
    {
        // $server = $this->db->where(array('id' => $id))->get('servers')->row();
        $server = $this->dbName->table('hd_servers')->where('id', $id)->get()->getRow();	
		
        switch ($server->type) {
            case 'plesk':
                $plesk = new Plesk_WHMCS();
                $configuration = $plesk->plesk_TestConnection($server);
            break;
            case 'ispconfig':
                $ispconfig = new Ispconfig();
                $configuration = $ispconfig->check_connection($server);
            break;
            case 'directadmin':
                $directadmin = new Directadmin();
                $configuration = $directadmin->check_connection($server);
            break;
            case 'cyberpanel':
                $cyberpanel = new Cyberpanel();
                $configuration = $cyberpanel->check_connection($server);
            break;
            case 'cwp':
                $cwp = new Cwp();
                $configuration = $cwp->check_connection($server);
            break;
            case 'cpanel':
                $cpanel = new new_Cpanel();
                $configuration = $cpanel->cpanel_TestConnection($server);
            break;
        }

        return $configuration;

        //return Modules::run($server->type . '/check_connection', $server);
    }



    function import($id = NULL)
    {
        $session = \Config\Services::session(); 

        $request = \Config\Services::request(); 
        
        // Connect to the database  
        $dbName = \Config\Database::connect();

        $list = array();
        // $server = $this->db->where(array('id' => $id))->get('servers')->row();
        $server = $dbName->table('hd_servers')->where('id', $id)->get()->getRow();
        $count = 0;

        // $list = modules::run($server->type . '/import', $server);

        switch ($server->type) {
            case 'plesk':
                $plesk = new Plesk();
                $list = $plesk->import($server);
            break;
            case 'whoisxmlapi':
                $whoisxmlapi = new Whoisxmlapi();
                $list = $whoisxmlapi->import($server);
            break;
            case 'stripepay':
                $stripepay = new Stripepay();
                $list = $stripepay->import($server);
            break;
            case 'paypal':
                $paypal = new Paypal();
                $list = $paypal->import($server);
            break;
            case 'razorpay':
                $razorpay = new Razorpay();
                $list = $razorpay->import($server);
            break;
            case 'payfast':
                $payfast = new Payfast();
                $list = $payfast->import($server);
            break;
            case 'ispconfig':
                $ispconfig = new Ispconfig();
                $list = $ispconfig->import($server);
            break;
            case 'directadmin':
                $directadmin = new Directadmin();
                $list = $directadmin->import($server);
            break;
            case 'cyberpanel':
                $cyberpanel = new Cyberpanel();
                $list = $cyberpanel->import($server);
            break;
            case 'cwp':
                $cwp = new Cwp();
                $list = $cwp->import($server);
            break;
            case 'cpanel':
                $cpanel = new Cpanel();
                $list = $cpanel->import($server);
            break;
            case 'coinpayments':
                $coinpayments = new Coinpayments();
                $list = $coinpayments->import($server);
            break;
            case 'checkout':
                $checkout = new Checkout();
                $list = $checkout->import($server);
            break;
            case 'bitcoin':
                $bitcoins = new Bitcoin();
                $list = $bitcoins->import($server);
            break;
        }

        $accounts = $dbName->table('hd_orders')
        ->where(['type' => 'hosting', 'status_id' => 6])
        ->join('hd_companies', 'hd_companies.co_id = hd_orders.client_id')
        ->get()
        ->getResult();

        $clients = $dbName->table('hd_companies')
        ->where('co_id >', 1)
        ->get()
        ->getResult();

        if ($request->getPost() && is_array($list)) {
            Applib::is_demo();
            foreach ($request->getPost() as $key => $domain) {
                foreach ($list as $k => $a) {
                    if ($a['domain'] == str_replace("_", ".", $key)) {
                        $item = $dbName->table('hd_items_saved')
                            ->where('package_name', $a['plan'])
                            ->join('hd_servers', 'hd_servers.id = hd_items_saved.server')
                            ->get()
                            ->getRow();

                        $client = $dbName->table('hd_companies')
                            ->where('company_email', $a['email'])
                            ->get()
                            ->getRow();

                        if (is_object($client)) {
                            $resultCount = $dbName->table('orders')
                            ->where('domain', $a['domain'])
                            ->where('type', 'hosting')
                            ->get()
                            ->getNumRows();
                            if ($resultCount == 0) {

                                if ($this->create_order($item, $client->co_id, $a, $id)) {
                                    $count++;
                                }
                            } else {
                                $data = [
                                    'username' => $a['user'],
                                ];
                                
                                $dbName->table('hd_orders')
                                    ->where('domain', $a['domain'])
                                    ->where('client_id', $client->co_id)
                                    ->where('status_id', 6)
                                    ->update($data);
                            }

                        } else {

                            if ($a['email'] != '') {
                                $username = explode('@', $a['email'])[0];
                                $email = $a['email'];
                                $password = $a['email'];
                            } else {
                                $username = $a['user'];
                                $email = $a['user'] . '@' . $a['domain'];
                                $password = $a['email'];
                            }


                            // $hasher = new PasswordHash(
                            //     $this->config->item('phpass_hash_strength', 'tank_auth'),
                            //     $this->config->item('phpass_hash_portable', 'tank_auth')
                            // );
                            // $hashed_password = $hasher->HashPassword($password);

                            $hashed_password = password_hash($password, PASSWORD_DEFAULT);


                            if (!is_username_available($username)) {
                                $username = explode('.', $a['domain'], 2)[0];
                            }

                            $data = array(
                                'username' => $username,
                                'password' => $hashed_password,
                                'email' => $email,
                                'role_id' => 2
                            );

                            $user_id = App::save_data('hd_users', $data);

                            $client = array(
                                'company_name' => ucfirst($a['domain']) . " " . lang('hd_lang.account'),
                                'company_email' => $email,
                                'company_ref' => $this->applib->generate_string(),
                                'language' => config_item('default_language'),
                                'currency' => config_item('default_currency'),
                                'primary_contact' => $user_id,
                                'individual' => 0,
                                'company_address_two' => '',
                                'company_phone' => '',
                                'city' => '',
                                'state' => '',
                                'zip' => ''
                            );

                            if ($co_id = App::save_data('hd_companies', $client)) {

                                $profile = array(
                                    'user_id' => $user_id,
                                    'company' => $co_id,
                                    'fullname' => ucfirst($a['domain']) . " " . lang('hd_lang.account'),
                                    'phone' => '',
                                    'avatar' => 'default_avatar.jpg',
                                    'language' => config_item('default_language'),
                                    'locale' => config_item('locale') ? config_item('locale') : 'en_US'
                                );

                                App::save_data('account_details', $profile);
                                if ($this->create_order($item, $co_id, $a, $id)) {
                                    $count++;
                                }
                            }

                        }
                    }
                }
            }

            // $session->set_flashdata('response_status', 'info');
            // $session->set_Flashdata('message', "Created " . $count . " accounts");
            // redirect($_SERVER['HTTP_REFERER']);
            return redirect()->to('servers');
        } else {
            if (is_array($list)) {
                foreach ($list as $key => $a) {

                    foreach ($clients as $client) {
                        if ($a['email'] == $client->company_email) {
                            $list[$key]['client'] = $client->company_name;
                        }
                    }

                    $item = $dbName->table('hd_items_saved')
                    ->where('hd_items_saved.package_name', $a['plan'])
                    ->join('hd_servers', 'hd_servers.id = hd_items_saved.server')
                    ->where('hd_items_saved.reseller_package', 'No')
                    ->get()
                    ->getRow();

                    if (isset($item->package_name)) {
                        $list[$key]['package'] = $item->item_name;
                        $list[$key]['server'] = $item->name;
                        $list[$key]['import'] = ($id == $item->server) ? 1 : 0;
                    }

                    foreach ($accounts as $acc) {
                        if ($a['domain'] == $acc->domain && $a['user'] == $acc->username) {
                            unset($list[$key]);
                        }
                    }
                }
            }
        }


        $data['data'] = $list;
        $data['id'] = $id;
        //$this->template->title(lang('hd_lang.import_accounts'));
        $data['page'] = lang('hd_lang.import_accounts');
        // $this->template
        //     ->set_layout('users')
        //     ->build('import', isset($data) ? $data : NULL);

        echo view('modules/servers/import' ,$data);

    }




    function login($id)
    {
        $session = \Config\Services::session(); 

        $request = \Config\Services::request();    

        // Connect to the database  
        $dbName = \Config\Database::connect();

        Applib::is_demo();
        if (User::is_admin() || User::perm_allowed(User::get_id(), 'manage_accounts')) {
            $server = $dbName->table('hd_servers')->where('id', $id)->get()->getRow();

            // return modules::run($server->type . '/admin_login', $server);
            switch ($server->type) {
                case 'plesk':
                    $plesk = new Plesk();
                    $list = $plesk->admin_login($server);
                break;
                case 'whoisxmlapi':
                    $whoisxmlapi = new Whoisxmlapi();
                    $list = $whoisxmlapi->admin_login($server);
                break;
                case 'stripepay':
                    $stripepay = new Stripepay();
                    $list = $stripepay->admin_login($server);
                break;
                case 'paypal':
                    $paypal = new Paypal();
                    $list = $paypal->admin_login($server);
                break;
                case 'razorpay':
                    $razorpay = new Razorpay();
                    $list = $razorpay->admin_login($server);
                break;
                case 'payfast':
                    $payfast = new Payfast();
                    $list = $payfast->admin_login($server);
                break;
                case 'ispconfig':
                    $ispconfig = new Ispconfig();
                    $list = $ispconfig->admin_login($server);
                break;
                case 'directadmin':
                    $directadmin = new Directadmin();
                    $list = $directadmin->admin_login($server);
                break;
                case 'cyberpanel':
                    $cyberpanel = new Cyberpanel();
                    $list = $cyberpanel->admin_login($server);
                break;
                case 'cwp':
                    $cwp = new Cwp();
                    $list = $cwp->admin_login($server);
                break;
                case 'cpanel':
                    $cpanel = new Cpanel();
                    $list = $cpanel->admin_login($server);
                break;
                case 'coinpayments':
                    $coinpayments = new Coinpayments();
                    $list = $coinpayments->admin_login($server);
                break;
                case 'checkout':
                    $checkout = new Checkout();
                    $list = $checkout->admin_login($server);
                break;
                case 'bitcoin':
                    $bitcoins = new admin_login();
                    $list = $bitcoins->import($server);
                break;
            }

            return $list;
        } else {
            redirect(base_url());
        }
    }


}

/* End of file Servers.php */