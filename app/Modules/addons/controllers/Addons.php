<?php
 /*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

namespace app\Modules\addons\Controllers;

use App\Libraries\AppLib;
use App\Models\Addon;
use App\Models\App;
use App\Models\User;
use App\ThirdParty\MX\WhatPanel;
use App\Helpers\custom_name_helper;


class Addons extends WhatPanel
{

	function __construct()
	{
		// parent::__construct(); 
		// $this->load->module('layouts');     
		// $this->load->library(array('form_validation','template')); 
		// $this->load->helper('form');  
	}


	function index()
	{
		$this->list_items();
		// $this->can_access();
	}


	function can_access() {
		if(!User::is_admin() && !User::is_staff()) {
			redirect('clients');
		}
	}


	function list_items()
	{
		error_reporting(E_ALL);
        ini_set("display_errors", "1");
		
		$session = \Config\Services::session();

		$request = \Config\Services::request();

		// Pagination Configuration
		$page = $request->getGet('page') ? $request->getGet('page') : 1;
		$perPage = 10; // Number of items per page

		// Search Filter
		$search = $request->getGet('search');
		$data['search'] = $search;

		$addsModel = new Addon();

		$custom_helper = new custom_name_helper();

		$currency = $custom_helper->getconfig_item('default_currency');

		// $this->can_access();
		//$this->template->title(lang('hd_lang.addons').' - '.config_item('company_name'));
		$data['page'] = lang('hd_lang.addons');
		$data['datatables'] = TRUE;
		$data['form'] = TRUE;
		// $data['addons'] = $addsModel->all([], $search, $perPage, $page);
		$query = $addsModel->all([], $search, $perPage, $page);

		// Get items for the current page
		$addons = array_map(function($item) {
			return (object) $item;
		}, $query['items']);

		if(!empty($addons)) {
			foreach($addons as $addon)
			{
				$payment_data = json_decode($addon->currency_amt, true);
	
				if (array_key_exists($currency, $payment_data)) {
					// Fetch the data for the selected currency
					$currency_data = $payment_data[$currency];
				
					$addon->monthly = $currency_data['monthly'];
					$addon->quarterly = $currency_data['quarterly'];
					$addon->semi_annually = $currency_data['semi_annually'];
					$addon->annually = $currency_data['annually'];
					$addon->biennially = $currency_data['biennially'];
					$addon->triennially = $currency_data['triennially'];
				}
	
				$data['addons'][] = $addon;
			}
		}


		$data['pager'] = $query['pager'];

		$data['message'] = $query['message'];
		
		// echo"<pre>";print_r($data);die;
		return view('modules/addons/addons', $data); 
	}
 

	function add(){
		
		$request = \Config\Services::request();
		if ($request->getPost()) {
			AppLib::is_demo();
			// echo 21132132;die;
                $_POST['apply_to'] = serialize($request->getPost('apply_to'));             
                if(App::save_data('addons', $request->getPost())){
                    session()->setFlashdata('response_status', 'success');
                    session()->setFlashdata('message', lang('hd_lang.server_added'));
                    // redirect($_SERVER['HTTP_REFERER']);
					$url = site_url('addons/list_items'); // Use site_url() to generate the full URL based on the URI
                	return redirect()->to($url)->with('success', 'You added menu successfully here');              
                }
            } 
        else{
			// echo 234343; die;
            $data['form'] = TRUE; 
            $data['datepicker'] = TRUE;
            $data['rates'] = [];
			return view('modules/addons/modal/add', $data);
			
        }     
    }
}