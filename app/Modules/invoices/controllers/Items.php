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
use App\Helpers\custom_name_helper;

class Items extends WhatPanel  {

	function __construct()
	{	
		// parent::__construct();	
		// User::logged_in();	
 
		// $this->load->library(array('form_validation'));
		// $this->load->model(array('Invoice','App','Client'));

		// $this->applib->set_locale();
	}

	//Done by Ketan
	function add(){
		$request = \Config\Services::request();

        $session = \Config\Services::session();
		
		$custom_name_helper = new custom_name_helper();

        // Connect to the database  
        $db = \Config\Database::connect();

		if ($request->getPost()) {

			// echo "<pre>";print_r($request->getPost());die;

		$invoice_id = $request->getPost('invoice_id');

		// In your controller constructor
		$validator = \Config\Services::validation();

		// Replace 'required' with 'required' rule, you can add other rules as needed
		$validator->setRules([
			'invoice_id' => 'required',
			'quantity' => 'required',
			'unit_cost' => 'required',
		]);

		if (!$validator->withRequest($this->request)->run()) {
			return redirect()->to('invoices/view/' . $invoice_id);
		}else{	
			$item_name = $request->getPost('item_name');
			$sub_total = $request->getPost('unit_cost') * $request->getPost('quantity');
			$tax_rate = $request->getPost('item_tax_rate');

			if ($tax_rate == '0.00') {
				if($row = $db->table('hd_items_saved')->where('item_name', $item_name)->get()->getRow()){
					$tax_rate = $row->item_tax_rate;
				}
				
			}
			
			$tax_rate = $custom_name_helper->getconfig_item('default_tax');
			
			$item_tax_total = Applib::format_deci(($tax_rate / 100) *  $sub_total);
			$total_cost =  Applib::format_deci($sub_total + $item_tax_total);

			$data = array(
				'invoice_id'	=> $request->getPost('invoice_id'),
				'item_name'		=> $item_name,
				'item_desc'		=> $request->getPost('item_desc'),
				'unit_cost'		=> $request->getPost('unit_cost'),
				'item_order'	=> $request->getPost('item_order'),
				'item_tax_rate'	=> $tax_rate,
				'item_tax_total'=> $item_tax_total,
				'quantity'		=> $request->getPost('quantity'),
				'total_cost'	=> $total_cost
			);
			// unset($_POST['tax']);

				if($db->table('hd_items')->insert($data)){
					//Applib::go_to('invoices/view/'.$invoice_id,'success',lang('hd_lang.item_added_successfully'));
					$session->setFlashdata('success', lang('hd_lang.item_added_successfully'));
					return redirect()->to('invoices/view/'.$invoice_id);
				}
			}
		}
	}

	//Done by Ketan
	function edit($id = null){
		$request = \Config\Services::request();

        $session = \Config\Services::session(); 
        
        // Connect to the database  
        $db = \Config\Database::connect();

		if ($request->getPost()) {

		$item_id = $request->getPost('item_id');
		$invoice_id = Invoice::view_item($item_id)->invoice_id;

		// In your controller constructor
		$validator = \Config\Services::validation();

		// Replace 'required' with 'required' rule, you can add other rules as needed
		$validator->setRules([
			'invoice_id' => 'required',
			'item_name' => 'required',
			'quantity' => 'required',
			'unit_cost' => 'required',
		]);

		if (!$this->validator->withRequest($this->request)->run()) {
			return redirect()->to('invoices/view/' . $invoice_id)->with('error', lang('hd_lang.error_in_form'));
		}else{	
			
			$sub_total = $request->getPost('unit_cost') * $request->getPost('quantity');
			$tax_rate = $request->getPost('item_tax_rate');
			$item_tax_total = Applib::format_deci(($tax_rate / 100) *  $sub_total);

			$total_cost = Applib::format_deci($sub_total + $item_tax_total);

			$data = array(
						'invoice_id'	=> $request->getPost('invoice_id'),
						'item_name'		=> $request->getPost('item_name'),
						'item_desc'		=> $request->getPost('item_desc'),
						'unit_cost'		=> $request->getPost('unit_cost'),
						'item_tax_rate'	=> $tax_rate,
						'item_tax_total'=> $item_tax_total,
						'quantity'		=> $request->getPost('quantity'),
						'total_cost'	=> $total_cost
						);

			if($db->table('hd_items')->where('item_id', $item_id)->update($data)){
				$session->setFlashdata('success', lang('hd_lang.item_added_successfully'));
					return redirect()->to('invoices/view/'.$invoice_id);
					//Applib::go_to('invoices/view/'.$invoice_id,'success',lang('hd_lang.item_added_successfully'));
			}
		}
		}else{
			$data['id'] = $id;
			//$this->load->view('modal/edit_item',$data);
			return view('modules/invoices/modal/edit_item', $data);
		}
	}

	function insert($id = null)
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
				'item' => 'required'
			]);

			if (!$validator->withRequest($this->request)->run()) {
				return redirect()->to('invoices/view/' . $invoice)->with('error', lang('hd_lang.error_in_form'));
			}else{	

			$item = $request->getPost('item');

			$saved_item = $db->table('hd_items_saved')
                ->where('item_id', $item)
                ->get()
                ->getRow();
			
            $items = Invoice::has_items($invoice);

			$form_data = array(
				'invoice_id' 		=> $invoice,
				'item_name'  		=> $saved_item->item_name,
				'item_desc' 		=> $saved_item->item_desc,
				'unit_cost' 		=> $saved_item->unit_cost,
				'item_tax_rate' 	=> $saved_item->item_tax_rate,
				'item_tax_total' 	=> $saved_item->item_tax_total,
				'quantity' 			=> $saved_item->quantity,
				'total_cost' 		=> $saved_item->total_cost,
				'item_order' 		=> count($items) + 1
			);
			if($db->table('hd_items')->insert($form_data)){
					//Applib::go_to('invoices/view/'.$invoice,'success',lang('hd_lang.item_added_successfully'));
					$session->setFlashdata('success', lang('hd_lang.item_added_successfully'));
					return redirect()->to('invoices/view/'.$invoice);
				}
			}
		}else{
			$data['invoice'] = $id;
			// $this->load->view('modal/quickadd',$data);
			return view('modules/invoices/modal/quickadd', $data);
		}
	}

	function delete($item = null, $invoice = null){

		$request = \Config\Services::request();

        $session = \Config\Services::session();
        // Connect to the database  
        $db = \Config\Database::connect();

		if ($request->getPost()){
					$item_id = $request->getPost('item');
					$invoice = $request->getPost('invoice');
				if($db->table('hd_items')->where('item_id', $item_id)->delete()){
						//Applib::go_to('invoices/view/'.$invoice,'success',lang('hd_lang.item_deleted_successfully'));
						$session->setFlashdata('success', lang('hd_lang.item_deleted_successfully'));
					return redirect()->to('invoices/view/'.$invoice);
				}
		}else{
			$data['item_id'] = $item;
			$data['invoice'] = $invoice;
			// $this->load->view('modal/delete_item',$data);
			return view('modules/invoices/modal/delete_item', $data);
		}
	}

	function reorder(){
                if ($this->input->post() ){
                        $items = $this->input->post('json', TRUE);
                        $items = json_decode($items);
                        foreach ($items[0] as $ix => $item) {
                            App::update('items', array('item_id' => $item->id),array("item_order"=>$ix+1));
                        }
                }
                $data['json'] = $items;
                $this->load->view('json',isset($data) ? $data : NULL);
	}
        
}