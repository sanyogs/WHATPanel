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

use App\Libraries\AppLib;
use App\Models\App;
use App\Models\Invoice;
use App\Models\User;
use App\ThirdParty\MX\WhatPanel;

class Tax_rates extends WhatPanel  {

	function __construct()
	{
		// parent::__construct();	
		// if(!User::is_admin() && !User::is_staff()) {redirect('clients');} 
		// $this->load->library(array('form_validation'));
		// $this->load->model(array('Invoice','App'));
		// $this->load->module('layouts');      
        // $this->load->library('template');
		// $this->applib->set_locale();
	}

	function index()
	{
		// $this->template->title(lang('hd_lang.tax_rates'));
		$data['page'] = lang('hd_lang.tax_rates');	
		$data['datatables'] = TRUE;
		$data['rates'] = Invoice::get_tax_rates();

		// $this->template
		// ->set_layout('users')
		// ->build('rates',isset($data) ? $data : NULL);
		return view('modules/invoices/rates', $data);
	}

	function add(){
		$request = \Config\Services::request();
		if ($request->getPost()) {

			$validation = \Config\Services::validation();

			$rules = [
				'tax_rate_name' => 'required',
				'tax_rate_percent' => 'required'
			];

			$validation->setRules($rules);

			if (!$validation->withRequest($this->request)->run()) {
				// Validation failed
				
				// Redirect to the tax_rates page with an error message
				return redirect()->to('tax_rates')->with('error', lang('hd_lang.error_in_form'));
			}else{
				$data = [
					'tax_rate_name' => $request->getPost('tax_rate_name'),
					'tax_rate_percent' => $request->getPost('tax_rate_percent')
				];
				if(Invoice::save_tax($data)){
					// Applib::go_to('tax_rates','success',lang('hd_lang.tax_added_successfully'));
					$redirectUrl = $request->getPost('r_url');
					return redirect()->to($redirectUrl);
				}
			}
		}else{
			// $this->load->view('modal/add_rate');
			echo view('modules/invoices/modal/add_rate');
		}
	}

	function edit($id = null){
		$request = \Config\Services::request();

		if ($request->getPost()) {
		
			$validation = \Config\Services::validation();

			$rules = [
				'tax_rate_name' => 'required',
				'tax_rate_percent' => 'required'
			];

			$validation->setRules($rules);

		if (!$validation->withRequest($this->request)->run())
		{	
				$_POST = '';
				Applib::go_to('invoices/tax_rates','error',lang('hd_lang.error_in_form'));	
		}else{	
			$data = array(
				'tax_rate_name' => $request->getPost('tax_rate_name'),
				'tax_rate_percent' => $request->getPost('tax_rate_percent')
				);

			Invoice::update_tax($request->getPost('tax_rate_id'),$data);

			$session = \Config\Services::session();
			$session->setFlashdata('response_status', 'success');
			$session->setFlashdata('message',lang('hd_lang.tax_updated_successfully'));

			$redirectUrl = $request->getPost('r_url');
			return redirect()->to($redirectUrl);
				
			}
		}else{

			$data['id'] = $id;
			// $this->load->view('modal/edit_rate',$data);
			echo view('modules/invoices/modal/edit_rate', $data);
		}
	}

	function delete($id = null){
		$request = \Config\Services::request();
		
		if ($request->getPost()){
		$tax_rate_id = $request->getPost('tax_rate_id');

		if(Invoice::delete_tax($tax_rate_id)){
			// Applib::go_to('invoices/tax_rates','success',lang('hd_lang.tax_deleted_successfully'));
			$session = \Config\Services::session();
			$session->setFlashdata('response_status', 'success');
			$session->setFlashdata('message',lang('hd_lang.tax_deleted_successfully'));

			$redirectUrl = $request->getPost('r_url');
			return redirect()->to($redirectUrl);
		}
		}else{
			$data['tax_rate_id'] = $id;
			// $this->load->view('modal/delete_tax',$data);
			echo view('modules/invoices/modal/delete_tax', $data);
		}
	}


}