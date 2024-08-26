<?php
 /*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

namespace App\Modules\cart\controllers;

use App\Helpers\custom_name_helper;
use App\Libraries\AppLib;
use App\Models\Addon;
use App\Models\App;
use App\Models\Item;
use App\Models\User;
use App\Modules\Dashboard\Controllers\Layouts as ControllersLayouts;
use App\ThirdParty\MX\WhatPanel;
use App\Modules\Layouts\Libraries\Template;
use App\Modules\Layouts\Controllers\Layouts;
use Config\Session;

class Cart extends WhatPanel
{

    function __construct()
    {	
		error_reporting(E_ALL);
        ini_set("display_errors", "1");
        $custom = new custom_name_helper();
        parent::__construct();

        // $layout = new ControllersLayouts();
        $library = new Template();
        $lang = $custom->getconfig_item('default_language');
        if (isset($_COOKIE['fo_lang'])) {
            $lang = $_COOKIE['fo_lang'];
        }
        $session = session();
        if ($session->has('lang')) {
            $lang = $session->get('lang');
        }
        //print_r(session()->get());die;
        helper('language');
        // $this->lang->load('hd', $lang);

        if (!$session->has('cart')) {
            $session->set('cart', []);
        }
        // echo"<pre>";print_r(session()->get());die;
        if (!$session->has('codes')) {
            $session->set('codes', array());
        }

        if (!$session->has('new_cust')) {
            $session->set('new_cust', array());
        }

        if (!$session->has('discounted')) {
            $session->set('discounted', array());
        }
    }

    function layout($data, $view)
    {
        $data['page'] = lang('hd_lang' . $view);

        $helper = new custom_name_helper();
		
        if (!User::is_logged_in()) {
			//print_r(session()->get());die;
            echo view($helper->getconfig_item('active_theme') . '/' . 'cart/' . $view, isset($data) ? $data : NULL);
        } else {
            //echo view($helper->getconfig_item('active_theme'). '/' .'/cart/' . $view, isset($data) ? $data : NULL);
            echo view('modules/cart/' . $view, $data);
        }
    }


    function index()
    {	
        $data = array();
        // $this->template->title(lang('hd_lang.shopping_cart'));
        // $this->layout($data, 'shopping_cart');
        $this->layout($data, 'shopping_cart');
        // return view('modules/cart/shopping_cart', $data);
    }

    
	function options($itemID = 0)
	{  
		$template = new Template();
		$template->title(lang('hd_lang.add_to_cart'));       
		$cart = session()->get('cart');
		$request = \Config\Services::request();
		$helper = new custom_name_helper(); 
		
		$db = \Config\Database::connect();

		if ($request->getPost()) {     
			
			$selected_options[] = $request->getPost('selected');
			//print_r($selected_options);die;
			foreach ($selected_options as $selected)
			{   
				if(is_array($selected)){
					$c = implode(',', $selected);
					$selected = explode(',', $c); 
				}else{
					$selected = explode(',', $selected); 
				}
				 
				$item = Item::view_item($selected[0]); 

				if($item->item_tax_rate == 'Yes'){
					$tax_rate = $helper->getconfig_item('default_tax');
				}else{
					$tax_rate = 0;
				}

				$tax = Applib::format_deci((null != $tax_rate && $tax_rate > 0) ? ($selected[3]*$tax_rate)/100 : '0.00');
				
				if($item->require_domain == 'Yes') {
					
					if(isset($cart[count($cart) - 1]->nameservers) && !isset($cart[count($cart) - 1]->domain_only)){

						$cart[] = (object) array('cart_id' => $cart[count($cart) - 1]->cart_id, 
						'item' => $selected[0], 
						'name' => $selected[1], 
						'renewal' => $selected[2], 
						'price' => $selected[3],
						'tax' => $tax, 
						'authcode' => '', 
						'domain' => $cart[count($cart) - 1]->domain);
						
						session()->set('cart', $cart);
						
						//$addon_check = $db->table('hd_items_saved')->select('apply_to')->where('item_id', $selected[0])->where('addon', 1)->get()->getRow()->apply_to;
						
						//echo $addon_check;die;

						$domain = $cart[count($cart) - 2];
						$tld = explode('.', $domain->domain, 2);
						$ext = $tld[1]; 
						if($this->additional_fields($ext) || $domain->name == lang('hd_lang.domain_transfer'))
						{    //echo 2;die;
							 if ($domain->name == lang('hd_lang.domain_transfer')) { 
                                session()->set('transfer', true);
                            	}

							return redirect()->to('cart/domain_fields');
						}

						else { 
							// echo 132;die;
							//return redirect()->to('cart');
							$time = time(); 
							$this->show_addons($selected[0], $time);
						}                    
					}

					else {   
						//echo 3;die;
						session()->set('hold', array(
						'cart_id' => time(), 
						'item' => $selected[0], 
						'name' => $selected[1], 
						'renewal' => $selected[2], 
						'price' => $selected[3],       
						'tax' => $tax,         
						'domain' => '')); 
						
						$addon_check = $db->table('hd_items_saved')->select('apply_to')->where('item_id', $selected[0])->get()->getRow();
						
						if($addon_check->apply_to !== '')
						{
							session()->set('addon_cart', array(
								'cart_id' => time(), 
								'item' => $selected[0], 
								'name' => $selected[1], 
								'renewal' => $selected[2], 
								'price' => $selected[3],       
								'tax' => $tax,         
								'domain' => '')); 
						}

						if($item->setup_fee > 0) { 
							session()->set('setup', array(
							'cart_id' => time(), 
							'item' => '', 
							'name' => $selected[1]. ' ' .lang('hd_lang.setup_fee'), 
							'renewal' => 'one_time_payment', 
							'tax' => $tax, 
							'price' => $item->setup_fee, 
							'domain' => '-')); 
						}
						
						if (!User::is_logged_in()) {
							return redirect()->to('carts/domain');
						}
						else {
							return redirect()->to('cart/domain');
						}
					} 
				}
				else 
				{
					$time = time();        
					$cart_item = (object) array(                    
					'cart_id' => $time, 
					'item' => $selected[0], 
					'name' => $selected[1], 
					'renewal' => $selected[2], 
					'price' => $selected[3], 
					'tax' => $tax, 
					'domain' => $item->item_desc);

					if (session()->get('parent')) 
					{   
						$cart_item->parent = session()->get('parent');
					}

					$cart[] = $cart_item;
					
					//$addon_check = $db->table('hd_items_saved')->select('apply_to')->where('item_id', $selected[0])->where('addon', 1)->get()->getRow()->apply_to;
						
						// echo $addon_check;die;

					if($item->setup_fee > 0) { 
						$tax = Applib::format_deci((null != $tax_rate && $tax_rate > 0) ? ($item->setup_fee*$tax_rate)/100 : '0.00');
						$cart[] = (object) array(
						'cart_id' => time(), 
						'item' => '', 
						'name' => $selected[1]. ' ' .lang('hd_lang.setup_fee'), 
						'renewal' => 'one_time_payment', 
						'tax' => $tax, 
						'price' => $item->setup_fee, 
						'domain' => '-');
					}

					session()->set('cart', $cart);

					$this->show_addons($selected[0], $time);

				}
			}

		}
		else {
			$item = '';
            $request = \Config\Services::request();

            if ($itemID > 0) {
                $item = Item::view_item($itemID);
				
                if (!isset($item)) {
                    redirect('home');
                }
            }
            $data['package'][] = $item;

            $data['item'] = $itemID;

            // return view('modules/cart/options', $data);

            return $this->layout($data, 'options');
		}       
	}

	 function domain()
    {  	
		$session = \Config\Services::session();
		$template = new Template();
      	$template->title(lang('hd_lang.domain_registration'));
        $data['domains'] = Item::get_domains();  
        echo $this->layout($data,  'domain'); 
    }




    function hosting_packages()
    {
        if (User::is_logged_in()) {
            redirect('orders/add_order');
        }
        $template = new Template();
        $template->title(lang('hd_lang.hosting_packages'));
        $data = array();

        // return view('modules/carts/views/hosting_packages', $data);

        return $this->layout($data,  'hosting_packages');
    }



	function add_nameservers()
	{  
		$request = \Config\Services::request();
		//print_r($request->getPost());die;
		$nameservers = $request->getPost('nameserver_1') . "," . $request->getPost('nameserver_2');
	
        if ($request->getPost('nameserver_3') != '') : $nameservers .= "," . $request->getPost('nameserver_3');
        endif;
        if ($request->getPost('nameserver_4') != '') : $nameservers .= "," . $request->getPost('nameserver_4');
        endif;
		$cart = session()->get('cart'); 
		$cart[count($cart) - 1]->nameservers = $nameservers;
		
		session()->set('cart', $cart); 

		$item = $cart[count($cart) - 1]; 
		$tld = explode('.', $item->domain, 2);
		$ext = $tld[1]; 

		if($this->additional_fields($ext) || $item->name == lang('domain_transfer'))
		{    echo 87987;die;
			if($item->name == lang('hd_lang.domain_transfer')) {
				echo 5555;die;
				session()->set('transfer', true);
			}
			return redirect()->to('cart/domain_fields');
		}else {
			//echo 333;die;
			echo view('modules/cart/shopping_cart');
		}       
	}


    function add_nameservers_custom()
    {
        error_reporting(E_ALL);
        ini_set("display_errors", "1");
        $session = \Config\Services::session();

        $request = \Config\Services::request();

        $nameservers = $request->getPost('nameserver_1') . "," . $request->getPost('nameserver_2');
        if ($request->getPost('nameserver_3') != '') : $nameservers .= "," . $request->getPost('nameserver_3');
        endif;
        if ($request->getPost('nameserver_4') != '') : $nameservers .= "," . $request->getPost('nameserver_4');
        endif;
        $cart = $session->get('cart');
        $cart[count($cart) - 1]->nameservers = $nameservers;
        $session->set('cart', $cart);
        // print_r(session()->get());die;
        $item = $cart[count($cart) - 1];
        $tld = explode('.', $item->domain, 2);
        $ext = $tld[1];

        if ($this->additional_fields($ext) || $item->name == lang('hd_lang.domain_transfer')) {
            if ($item->name == lang('hd_lang.domain_transfer')) {
                $session->set('transfer', true);
            }
            return redirect()->to('cart/domain_fields');
        } else {
            return redirect()->to('cart');
        }
    }

        function default_nameservers()
    { 
        $cart = session()->get('cart'); 
		//print_r(session()->get());die;
        $item = $cart[count($cart) - 1];
        $tld = explode('.', $item->domain, 2);
        $ext = $tld[1]; 
		
        if($this->additional_fields($ext) || $item->name == lang('domain_transfer'))
        {   
            if($item->name == lang('hd_lang.domain_transfer')) {
                session()->set('transfer', true);
            } 

            //redirect('cart/domain_fields');
			return redirect()->to('cart/domain_fields');
        }
        else { 
            $this->show_addons($item->item, $item->cart_id);
        }     
    }
	

     
    function show_addons($item, $parent)
    {   
        $addons = Addon::addon_all();
        $addon_list = array();
		
		// echo "<pre>";print_r($item);die;
		
        foreach($addons as $addon)
        {  
            if($item == $addon->apply_to)
            {   
                $addon_list[] = $addon;
            }
        }
		
		//echo "<pre>";print_r($addon_list);die;
     
        if(empty($addon_list))
        {	
            $data = [];
            return $this->layout($data, 'shopping_cart'); 
        }
        else
        {   
            session()->set('parent',  $parent);
            $data['id'] = $item; 
            $data['addons'] = $addon_list;    
            $this->layout($data, 'addons'); 
        }        
    }


	function domain_only()
    { 
        $cart = session()->get('cart');
        if(isset($cart[count($cart) - 1]->nameservers)){
             $cart[count($cart) - 1]->domain_only = true;
        }
        session()->set('cart', $cart);
       	return redirect()->to('cart/nameservers');
    }



    function nameservers()
    {
        $template = new Template();
        $data = array();
        $template->title(lang('hd_lang.name_servers'));
        // $this->layout($data, 'nameservers');
        //return view('modules/cart/nameservers', $data);
		echo $this->layout($data, 'nameservers'); 
    }



    function add_existing()
    {
        $template = new Template();
        $data = array();
        $data['page'] = lang('hd_lang.existing_domain');
        $template->title(lang('hd_lang.existing_domain'));
        // $this->layout($data, 'existing_domain');
        //print_r($data);die;
        // return view('modules/cart/existing_domain', $data);
        return $this->layout($data, 'existing_domain');
    }



    // public function existing_domain()
    // {   
    //     $cart = session('cart');

    //     $request = \Config\Services::request();

    //     $held = []; // Initialize $held here

    //     if ($request->getPost()) {
    //         if (session('hold')) {
    //             $held = session('hold');
    //             $held['domain'] = $request->getPost('domain', FILTER_SANITIZE_STRING);
    //             $cart[] = (object) $held;
    //             session()->remove('hold');

    //             if (session('setup')) {
    //                 $cart[] = (object) session('setup');
    //                 session()->remove('setup');
    //             }
    //         }

    //         session()->set('cart', $cart);
    //         $this->show_addons($held['item'], $held['cart_id']);
    //     }
    // }

    public function existing_domain()
    {
        error_reporting(E_ALL);
        ini_set("display_errors", "1");
        $cart = session()->get('cart');
        // echo"<pre>";print_r(session()->get());die;

        $request = \Config\Services::request();

        $db = \Config\Database::connect();

        $session = \Config\Services::session();

        //$held[] = session()->get('hold');
        //echo"<pre>";print_r($request->getPost());die;
		
		$held = [];

        if ($request->getPost()) {
			//echo "<pre>";print_r(session()->get('hold'));die;
            if (session()->get('hold')) {
                // echo "<pre>";print_r(session()->get());die;
                $held = session()->get('hold');
                $held['domain'] = $request->getPost('domain');
                $cart[] = (object)$held;
                // session()->remove('hold');

                if (session()->get('setup')) {
                    $cart[] = (object)session()->get('setup');
                    session()->remove('setup');
                }
            }
			else {
				$held = [];
				$held['item'] = 0;
				$held['cart_id'] = 0;
			}

            //echo "<pre>";print_r($cart);die;

            session()->set('cart', $cart);

            //echo "<pre>";print_r($cart);die;

            $co_id = $session->get('select_client.co_id');
            $book_by = $session->get('userdata.role_id');

            //echo"<pre>";print_r(session()->get());die;
            // $this->show_addons($held['item'], $held['cart_id']);
            $this->show_addons($held['item'], $held['cart_id']);
        }
    }



    // function existing_domain()
    // {   
    //     $session = \Config\Services::session();
    //     $cart = $session->set('cart');
    //     echo"<pre>";print_r($session->get());die;
    //     if ($this->input->post()) {
    //         if($this->session->userdata('hold')) {
    //             $held = $this->session->userdata('hold');
    //             $held['domain'] = $this->input->post('domain', true);
    //             $cart[] = (object) $held;
    //             $this->session->unset_userdata('hold');
    //             if($this->session->userdata('setup')) {
    //                 $cart[] = (object) $this->session->userdata('setup');
    //                 $this->session->unset_userdata('setup');
    //             }
    //         }

    //         $this->session->set_userdata('cart', $cart);  
    //         $this->show_addons($held['item'], $held['cart_id']);
    //     }
    // }


        function add_domain()
    {	 
		error_reporting(E_ALL);
        ini_set("display_errors", "1");	
        $cart = session()->get('cart');
		$request = \Config\Services::request();
		$db = \Config\Database::connect();
		$helper = new custom_name_helper();
			
			//echo "<pre>";print_r(session()->get());die;
        
           if ($request->getPost()) {          
            $count = 0;
            foreach($cart AS $item) {
                if(
                    $item->domain == $request->getPost('domain') && $item->item == '') {
                    $count++;
                }
            }
			   
			   $res = '';

            if($count == 0) {
                $time = time();
                session()->set('fields',  $time);

                $tld = explode('.', $request->getPost('domain'), 2);
                $ext = '.' . $tld[1]; 
                $item = $db->table('hd_items_saved')->where('item_name', $ext)->get()->getRow();
				 //echo"<pre>";print_r($item);die;
				
				if($item->item_tax_rate == 'Yes' || (!empty($item->item_tax_rate))){
				$item_tax_rate = $helper->getconfig_item('default_tax');
				}else{
					$item_tax_rate = 0;
				}
				$price_year = explode('|', $request->getPost('price'));
				
                $tax = Applib::format_deci((null != $item_tax_rate && (float)$item_tax_rate > 0) ? ((float) $price_year[0] * (float) $item_tax_rate) / 100 : '0.00');
				//print_r($item_tax_rate);die;
                $i = Item::view_item($item->item_id);
                
                $c = (object) array(
                    'cart_id' =>  $time,
                    'item' => $item->item_id, 
                    'renewal' => 'annually', 
                    'name' => $request->getPost('type'),             
                    'price' => $request->getPost('price'), 
                    'domain' => $request->getPost('domain'), 
                    'tax' => $tax, 
                    'authcode' => '',
                    'nameservers' => ''); 
				
                    if($item->max_years > 1)
                    {
                        $c->years = floatval($request->getPost('price')) / floatval($i->registration);
                    }
                
                $cart[] = $c;
				
                if(session()->get('hold')) {
                    $held = session()->get('hold');
                    $domain = $request->getPost('domain');
					$held['domain'] = filter_var($domain, FILTER_SANITIZE_STRING);
                    $cart[] = (object) $held;
                    session()->remove('hold');
                    if(session()->get('setup')) {
                        $cart[] = (object) session()->get('setup');
                        session()->remove('setup');
                    }
                }
				
            }

            session()->set('cart', $cart);  
			//echo"<pre>";print_r($tax);die;
            if($this->additional_fields($ext) || $item->item_name == lang('hd_lang.domain_transfer'))
            {   
                if($item->name == lang('hd_lang.domain_transfer')) {
                    session()->set('transfer', true);
                } 
                return redirect()->to('cart/domain_fields');
            }else 
            {
                if($count == 0)
                {
					//echo "<pre>";print_r(session()->get());die;
					if (session()->get('addon_cart')) {
						// echo "<pre>";print_r(session()->get());die;
						$held = session()->get('addon_cart');
						$held['domain'] = $request->getPost('domain');
						$cart[] = (object)$held;
						// session()->remove('hold');

						if (session()->get('setup')) {
							$cart[] = (object)session()->get('setup');
							session()->remove('setup');
						}
					}
					else {
						$held = [];
						$held['item'] = 0;
						$held['cart_id'] = 0;
					}
					//echo "<pre>";print_r($held);die;
					$this->show_addons($held['item'], $held['cart_id']);
					
					//echo $item->item_id;die;
					// echo "<pre>";print_r($cart);die;
                    //$this->show_addons($item->item_id, $time);
                }
            }                  
        }
    }

    function add_domain_only()
    {
        error_reporting(E_ALL);
        ini_set("display_errors", "1");
        $request = \Config\Services::request();

        // echo "<pre>";print_r($request->getPost());die;

        if ($request->getPost()) {

            $data = [
                'name' => $request->getPost('domain') ?? null,
                'price' => $request->getPost('price') ?? null,
                'domain' => $request->getPost('domain') ?? null,
                'tax' => $request->getPost('domain') ?? null,
                'domain_only' => 1
            ];

            // echo "<pre>";print_r($data);die;

            echo $this->layout($data, 'shopping_cart');
        }
    }



    function additional_fields($tld = null)
    {
        $session = \Config\Services::session();

        $tlds = array(
            "us", "co.uk", "net.uk", "org.uk", "plc.uk", "ltd.uk", "me.uk", "uk", "ca", "es", "sg", "com.sg", "edu.sg", "net.sg", "org.sg", "per.sg", "tel", "it",
            "de", "com.au", "net.au", "org.au", "asn.au", "id.au", "asia", "pro", "coop", "cn", "fr", "re", "pm", "tf", "wf", "yt", "nu", "quebec", "jobs", "travel",
            "ru", "ro", "srts.ro", "co.ro", "com.ro", "firm.ro", "info.ro", "nom.ro", "nt.ro", "org.ro", "rec.ro", "ro.ro", "store.ro", "tm.ro", "www.ro",
            "hk", "com.hk", "edu.hk", "gov.hk", "idv.hk", "net.hk", "org.hk", "pl", "pc.pl", "miasta.pl", "atm.pl", "rel.pl", "gmina.pl", "szkola", "sos.pl",
            "media.pl", "edu.pl", "auto.pl", "agro.pl", "turystyka.pl", "gov.pl", "aid.pl", "nieruchomosci.pl", "com.pl", "priv.pl", "tm.pl", "travel.pl", "info.pl",
            "org.pl", "net.pl", "sex.pl", "sklep.pl", "powiat.pl", "mail.pl", "realestate.pl", "shop.pl", "mil.pl", "nom.pl", "gsm.pl", "tourism.pl", "targi.pl", "biz.pl",
            "se", "tm.se", "org.se", "pp.se", "parti.se", "presse.se"
        );
        if (in_array($tld, $tlds)) {
            $session->set('tld', $tld);
            return true;
        }
        return false;
    }




    function remove($id)
    {
		//echo $id;die;
        $db = \Config\Database::connect();
        $session = \Config\Services::session();
        $co_id = isset(session()->get('select_client')['co_id']) ? session()->get('select_client')['co_id'] : session()->get('userdata.client_id');
		
		$cart_id_to_remove = $id;
			
			$cart = session()->get('cart');
			
			foreach ($cart as $key => $item) {
				if ($item->cart_id == $cart_id_to_remove) {
					unset($cart[$key]);
					// Re-index the array to maintain proper indexes
					$cart = array_values($cart);
					break;
				}
			}
			
			session()->set('cart', $cart);
		
		/*if(!User::is_logged_in())
		{
			$cart_id_to_remove = $id;
			
			$cart = session()->get('cart');
			
			// Remove the item directly in the script
			foreach ($cart as $key => $item) {
				if ($item->cart_id == $cart_id_to_remove) {
					unset($cart[$key]);
					// Re-index the array to maintain proper indexes
					$cart = array_values($cart);
					break;
				}
			}
			
			session()->set('cart', $cart);
		}
		else
		{	
			//$cart = $db->table('hd_carts')->where('co_id', $co_id)->get()->getResult();
			//$cart = $db->table('hd_carts')->select('hd_carts.*, hd_invoices.client, hd_invoices.status')->join('hd_invoices', 'hd_carts.co_id = hd_invoices.client')->get()->getResult();
			//echo"<pre>";print_r(session()->get());die;
			//foreach ($cart as $key => $row) {
				//if ($row->cart_id == $id) {
					//$query = $db->table('hd_carts')->where('cart_id', $id)->delete();
				//}
			//}
			//session()->set('cart', $cart);
			
			$cart_id_to_remove = $id;
			
			$cart = session()->get('cart');
			
			foreach ($cart as $key => $item) {
				if ($item->cart_id == $cart_id_to_remove) {
					unset($cart[$key]);
					// Re-index the array to maintain proper indexes
					$cart = array_values($cart);
					break;
				}
			}
			
			session()->set('cart', $cart);
		} */
        //echo view('modules/cart/shopping_cart');
		$data = [];
		echo $this->layout($data, 'shopping_cart');
    }



	function remove_all()
    {   // echo 23;die;
        session()->set('cart', array());
        session()->set('codes', array());
        session()->set('new_cust', array());
        session()->set('discounted', array());

        session()->remove('hold');
        session()->set('tld', '');
        $data = array();
		// Step 1: Connect to the database
		$db = \Config\Database::connect();

		// Step 2: Prepare and execute the delete query
		$query = $db->table('hd_carts')->get(); // Retrieve the records to be deleted
		$results = $query->getResult(); // Get the result set (optional, depends on your use case)

		// Execute the delete operation
		$db->table('hd_carts')->truncate(); // This deletes all rows from the 'hd_carts' table

		// Optionally, you can check if the deletion was successful
		if ($db->affectedRows() > 0) {
			//echo "Deletion successful.";
			echo $this->layout($data, 'shopping_cart');
		} else {
			//echo "No records deleted.";
			echo $this->layout($data, 'shopping_cart');
		}
		
    }



    function continue()
    {
        (User::is_logged_in()) ? redirect('orders/add_order') : redirect(base_url());
    }



    function shopping_cart()
    {
        $data = array();
        $template = new Template();
        $template->title(lang('hd_lang.shopping_cart'));
		//session()->remove('domain_set', 'yes');
        $this->layout($data, 'shopping_cart');
    }


    function domain_fields()
    {
        $session = \Config\Services::session();

        $request = \Config\Services::request();

        // echo "<pre>";print_r($session->get());die;

        $data = array();
        // $this->template->title(lang('hd_lang.domain_registration'));
        if ($request->getPost()) {
            foreach ($request->getPost() as $key => $value) {
                if ($key != 'authcode') {
                    $fields = array(
                        'domain' => $session->get('fields'),
                        'field_name' => str_replace("_", " ", $key),
                        'field_value' => $value
                    );
                    App::save_data('hd_additional_fields', $fields);
                }
            }

            $cart = $session->get('cart');
            $cart[count($cart) - 1]->authcode = $request->getPost('authcode');
            $session->set('cart', $cart);
            $session->remove('transfer');
            $session->remove('tld');
            $this->show_addons($cart[count($cart) - 1]->item, $cart[count($cart) - 1]->cart_id);
        }
        $this->layout($data, 'additional_fields');
    }



    function checkout()
    {
        $order = (object) array('order' => session()->get('cart'));
        $data = array('order' => $order, 'process' => true);
        session()->set($data);
        //redirect('auth/register');
        return redirect()->to('auth/register');
    }



    function validate_code()
    {
        $db = \Config\Database::connect();
        $request = \Config\Services::request();
        $code = $request->getPost('code', true);
        $promotion = $db->table('hd_promotions')->where('code', $code)->get()->getRow();
        //print_r($db);die;
        if (!empty($promotion)) {
            $codes = session()->get('codes');
            $new_cust = session()->get('new_cust');
            $discounted = session()->get('discounted');

            $items = unserialize($promotion->apply_to);
            $required = unserialize($promotion->required);
            $billing = unserialize($promotion->billing_cycle);

            $type = $promotion->type;
            $value = $promotion->value;

            if (
                $promotion->use_date == 1 &&
                (strtotime(date('Y-m-d')) < strtotime($promotion->start_date) ||
                    strtotime(date('Y-m-d')) > strtotime($promotion->end_date))
            ) {
                redirect('cart');
            }

            $cart = $this->session->userdata('cart');

            foreach ($cart as $k => $c) {
                if ($c->domain != 'promo') {
                    $i = time();
                    $processed = false;

                    if (!empty($discounted)) {
                        if ($promotion->per_order == 1) {
                            foreach ($discounted as $discount) {
                                if ($discount['code'] == $code) {
                                    $processed = true;
                                }
                            }
                        } else {
                            foreach ($discounted as $discount) {
                                if ($discount['code'] == $code && $k == $discount['key']) {
                                    $processed = true;
                                }
                            }
                        }


                        if ($processed) {
                            continue;
                        }
                    }


                    if (is_array($items) && in_array($c->item, $items) || $c->item == $items) {
                        if (!empty($required)) {
                            $count = 0;

                            foreach ($items as $item) {
                                if (is_array($required) && in_array($item, $required) || $item == $required) {
                                    $count++;
                                }
                            }

                            if ($count == 0) {
                                continue;
                            }
                        }
                    }


                    if (!empty($billing)) {
                        if (is_array($billing) && !in_array($c->renewal, $billing) || $c->renewal != $billing) {
                            continue;
                        }
                    }

                    if ($promotion->per_order == 1) {
                        if (in_array($code, $codes)) {
                            continue;
                        }
                    }


                    $item = Item::view_item($c->item);
                    $tax = Applib::format_deci((null != $item->item_tax_rate && $item->item_tax_rate > 0) ?
                        ($c->price * $item->item_tax_rate) / 100 : 0);
                    $total = $c->price + $tax;

                    if ($type == 2) {
                        $discount = ($value / 100) * $total;
                    } else {
                        $discount = $value;
                    }


                    if ($promotion->new_customers == 1) {
                        $new_cust[] = $i;
                        session()->set('new_cust', $new_cust);
                        $promotion->description = (User::is_logged_in()) ?
                            $promotion->description :
                            $promotion->description .
                            " (" . lang('hd_lang.pending_validation') . ")";
                    }

                    $cart[] = (object) array(
                        'cart_id' => $i,
                        'item' => $promotion->description,
                        'name' => $promotion->code,
                        'renewal' => 'one_time_payment',
                        'price' => -1 * $discount,
                        'tax' => 0,
                        'domain' => 'promo'
                    );

                    session()->set('cart', $cart);

                    $discounted[] = array('code' => $code, 'item' => $c->item, 'amount' => $discount, 'key' => $k);
                    session()->set('discounted', $discounted);

                    $codes[] = $code;
                    $codes = session()->set('codes', $codes);
                }
            }
        }

        redirect('cart');
    }
}
