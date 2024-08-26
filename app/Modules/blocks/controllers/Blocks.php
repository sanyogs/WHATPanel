<?php
 /*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

namespace App\Modules\blocks\controllers;

use App\Libraries\AppLib;
use App\Models\App;
use App\Models\Block;
use App\Models\BlocksCustom;
use App\Models\BlocksModules;
use App\Helpers\custom_name_helper;
use App\Helpers\file_helper;
use App\Models\Page;
use App\Models\User;
use App\ThirdParty\MX\WhatPanel;
use App\Modules\Layouts\Controllers\Layouts;
use App\Helpers\whatpanel_helper;
use App\Modules\Layouts\Libraries\Template;
use Config\Services;


class Blocks extends WhatPanel
{

    protected $db;
    protected $template;
    protected $blockcustom;
    protected $blockmodules;
    protected $hosting_helpers;

    function __construct()
    {
        error_reporting(E_ALL);
        ini_set("display_errors", "1");
        parent::__construct();
        User::logged_in();
        $session = \Config\Services::session();
        // Connect to the database	
        $db = \Config\Database::connect();
        $this->blockModel = new Block($db);
        
        // $this->layouts = new Layouts(); // Assuming Layouts library is in the App\Libraries namespace
        $this->template = new Template(); // Assuming Template library is in the App\Libraries namespace
        $this->appModel = new App($db);
        
        $this->pageModel = new Page($db);
        $this->blockcustom = new BlocksCustom($db);
        $this->blockmodules = new BlocksModules($db);
        
        $this->hosting_helpers = new whatpanel_helper();
        helper(['file']);
    }


    function index()
    {	
		$request = \Config\Services::request();
		$helper = new file_helper();
        if (User::is_client()) {
            Applib::go_to('clients', 'error', lang('hd_lang.access_denied'));
        }

        $blocks = array();
        $path = APPPATH . 'Modules/';
        $modules = scandir($path);
        $modules = array_diff(scandir($path), array('.', '..'));
        foreach ($modules as $module) {
            if (is_dir(APPPATH . 'modules/' . $module . '/views')) {
                $views = scandir(APPPATH . 'modules/' . $module . '/views/');
                foreach ($views as $view) {
                    $name = explode('.', $view);
                    $str = $name[0];
                    $arr = explode('_', $str);
                    if ($arr[0] == $module && $arr[count($arr) - 1] == 'block') {
                        $data = $helper->read_file(APPPATH . 'modules/' . $module . '/views/' . $view);
                        $mod = array('id' => implode('_', $arr), 'name' => ucfirst(implode(' ', array_slice($arr, 1, -1))), 'type' => 'Module', 'module' => ucfirst($module));
                        $blocks[] = (object) $mod;
                    }
                }
            }
        }
        $session = \Config\Services::session();
        // Connect to the database	
        $db = \Config\Database::connect(); 
        // echo"<pre>"; print_r($db); die;
        $blockcustom = new BlocksCustom($db);
        $blockModel = new Block();

        $blockmodules = new BlocksModules();

        $custom_blocks = $blockcustom->get()->getResult();
        $module_blocks = $blockmodules->get()->getResult();
        $blocks = array_merge($custom_blocks, $blocks, $module_blocks);
        // echo"<pre>"; print_r($blocks); die;
        $sections = $blockModel->select('id, section')->get()->getResult();
        // echo $db->getLastQuery(); die;
        // echo"<pre>";print_r($sections); die;

        // if (!$sections) {
        //     // Output the database error for debugging
        //     var_dump($this->blockModel->errors());
        //     exit;
        // }
        
        // $sections = $sections->get();
        // $this->template->title(lang('hd_lang.blocks'));
        $data = array();
        $data['page'] = lang('hd_lang.blocks');
        $data['blocks'] = $blocks;
        
        $data['datatables'] = TRUE;
		
		// Pagination Configuration
		$page = $request->getGet('page') ? $request->getGet('page') : 1;

        $perPage = $request->getGet('recordsPerPage', FILTER_SANITIZE_NUMBER_INT) ? $request->getGet('recordsPerPage', FILTER_SANITIZE_NUMBER_INT) : 10;

		// Search Filter
		$search = $request->getGet('search');
		$data['search'] = $search;

        $query = $blockModel->listItems([], $search, $perPage, $page);

        // Get items for the current page
		$data['blocks'] = array_map(function($item) {
			return (object) $item;
		}, $query['items']);

        $data['pager'] = $query['pager'];

		$data['message'] = $query['message'];
		
		$data['sections'] = $query['sections'];

		$data['perPage'] = $perPage;
				
        return view('modules/blocks/views/index', $data);
    }



        function add()
    {
        //echo 1; die;
        $request = Services::request();
			
		// Connect to the database	
        $db = \Config\Database::connect();
			
		$session = \Config\Services::session();
			
        //echo 2; die;
        if ($request->getPost()) {
            // print_r($request->getPost()); die;
            // echo 3; die;
            AppLib::is_demo();
            if ($request->getPost('format') == 'rich_text') {
                // print_r($request->getPost('format')); die;
                $block = [
                    'name'    => $request->getPost('name'),
                    'code'    => $request->getPost('content'), // Remove the third argument or pass null
                    'format'  => $request->getPost('format'),
                ];
//print_r($block);die;
                $blockcustom = new BlocksCustom();

                // echo"<pre>";
                // print_r($blockcustom); die;
				//print_r();die;
                if ($blockcustom->insert($block, false)) {
                    //echo $db->getLastQuery(); die;
                    Applib::go_to('hd_blocks', 'success', lang('hd_lang.block_created'));
                    //echo 4;die;
                    $url = site_url('blocks'); // Use site_url() to generate the full URL based on the URI
                    return redirect()->to($url)->with('success', 'Your success message here');
                }
            } else {
                //echo 5;die;
                //$sql = "INSERT INTO hd_blocks_custom (name, code, format) 
                       // VALUES('" . $request->getPost('name') . "', '" . $db->escape_str($request->getPost('content')) . "', '" . $request->getPost('format') . "')";
				
				$block = [
					'name' => $request->getPost('name'),
					'code' => $request->getPost('content'),
					'format' => $request->getPost('format')
				];
				//print_r($data);die;
                if ($db->table('hd_blocks_custom')->insert($block)) {
                    // AppLib::go_to('hd_blocks', 'success', lang('hd_lang.block_created'));
					$session->setFlashData('success', lang('hd_lang.block_created'));
					return redirect()->to('blocks');
                } else {
                    AppLib::go_to('hd_blocks', 'error', $this->db->error());
                }
            }
        } else {
            // echo 2; die;
            $data['editor'] = true;
			$template = new Template();	
            $template->title(lang('hd_lang.new_block'));
            $data['page'] = lang('hd_lang.add_block');
            return view('modules/blocks/views/add', $data);
        }
    }


    function add_code()
    {
		$helper = new custom_name_helper();
        if ($helper->getconfig_item('allow_js_php_blocks') == "TRUE") {
            $this->template->title(lang('hd_lang.new_block'));
            $data['page'] = lang('hd_lang.add_block');
            return view('modules/blocks/views/add_code', $data);
        } else {
            redirect($_SERVER['HTTP_REFERER']);
        }
    }


    function edit($id = null)
    {   
        if (User::is_client()) {
            Applib::go_to('clients', 'error', lang('hd_lang.access_denied'));
        }
        // echo 2;die;
        $request = \Config\Services::request();
        // echo 3;die;
        if ($request->getPost()) {
            Applib::is_demo();
            // echo 4;die;
            $session = \Config\Services::session();

            // Connect to the database	
            $db = \Config\Database::connect();

            $blockModel = new Block($db);

            $blockcustom = new BlocksCustom($db);
            // echo"<pre>";
            // print_r($blockModel); die;
            $block = $blockModel->get_block($request->getPost('id'));
            // print_r($block);die;
            if ($request->getPost('format') == 'rich_text') {
                $block_data = [
                    'name'    => $request->getPost('name'),
                    'code'    => $request->getPost('content'), // Remove the third argument or pass null
                    'format'  => $request->getPost('format'),
                ];
                // print_r($block_data->id); die;
                $blockcustom->where('id', $block->id);
                // print_r($block);die;
                if ($blockcustom->update($block->id, $block_data)) {
                    // Applib::go_to('blocks', 'success', lang('hd_lang.block_updated'));
                    $url = site_url('blocks'); // Use site_url() to generate the full URL based on the URI
                    return redirect()->to($url)->with('success', 'You edited successfully message');
                }
            }
            
            else {
                // echo 63; die;
                $sql = "UPDATE hd_blocks_custom SET 
                    name = '" . $request->getPost('name') . "', 
                    code = '" . $this->db->escape_str($request->getPost('content')) . "',
                    format = '" . $request->getPost('format') . "' 
                    WHERE id = '" . $request->getPost('id') . "'";

                if ($this->db->simple_query($sql)) {
                    // Applib::go_to('blocks', 'success', lang('hd_lang.block_updated'));
                    $url = site_url('blocks'); // Use site_url() to generate the full URL based on the URI
                    return redirect()->to($url)->with('success', 'Your success message here');
                } else {
                    Applib::go_to('blocks', 'error', $this->db->error());
                    $url = site_url('blocks'); // Use site_url() to generate the full URL based on the URI
                    return redirect()->to($url)->with('success', 'Your success message here');
                }
            }
        } 
        
        else {
            $data['block'] = $this->blockModel->get_block($id);

            if (!empty($data['block'])) {
                $block = $data['block'];

                // Now you can directly access the properties of $block
                $view = ($block->format == 'rich_text') ? 'edit' : 'edit_code';

                $data['page'] = lang('hd_lang.edit_block');
                $data['editor'] = true;
                return view('modules/blocks/views/edit', $data);
            } else {
                // Handle the case where no block is found
            }
        }
    }


      function delete($id = null)
    {   
        if (User::is_client()) {
            Applib::go_to('clients', 'error', lang('hd_lang.access_denied'));
        }

        $request = \Config\Services::request();
        if ($request->getPost()) {
            Applib::is_demo();
            $id = $request->getPost('id');

            $db = \Config\Database::connect();

            $config = $db->table('hd_blocks_custom')->where('id', $id)->delete();

            if ($config) {
                // $block = $blockModel->where('id', $request->getPost('id'))->get('blocks')->row();
                $block = $db->table('hd_blocks')->where('id', $request->getPost('id'))->get()->getRow();
                if ($block) {
                    $db->table('hd_blocks')->where('id', $request->getPost('id'))->delete();
                    $db->table('hd_blocks_pages')->where('block_id', $block->block_id)->delete();
                }
                Applib::go_to('blocks', 'success', lang('hd_lang.block_deleted'));
            }
            return $this->index();
        } else {
            // $this->template->title(lang('hd_lang.delete_block'));
            $data['page'] = lang('hd_lang.delete_block');
            $data['id'] = $id;
            echo view('modules/blocks/views/modal/delete', $data);
        }
        
    }


        function configure($id = null, $type = null)
    {	
        // if (User::is_client()) {
        //     Applib::go_to('clients', 'error', lang('hd_lang.access_denied'));
        // }
        $db = \Config\Database::connect();
        $request = Services::request();
        $helper = new whatpanel_helper();
        $cus_name = new custom_name_helper();
        if ($request->getPost()) {
            Applib::is_demo();

            $block_id = 0;
            // echo "<pre>";print_r($this->blockModel);die;
            $block = $this->blockModel->where('id', $request->getPost('id'))
                ->where('theme', $cus_name->getconfig_item('active_theme'))
                ->where('module', $request->getPost('module'))
                ->get()->getRow();

            if ($request->getPost('section') == '') {
                $db->table('hd_blocks')->where('id', $request->getPost('id'))->where('module', $request->getPost('module'))->where('theme', $cus_name->getconfig_item('active_theme'))->delete();
                if ($block) {
                    $db->table('hd_blocks_pages')->where('block_id', $block->block_id)->where('module', $request->getPost('module'))->where('theme', $cus_name->getconfig_item('active_theme'))->delete();
                }
            } else {
                if (!$block) {
                    $data = array(
                        'type' => $request->getPost('type'),
                        'name' => $request->getPost('name'),
                        'id' => $request->getPost('id'),
                        'theme' => $cus_name->getconfig_item('active_theme'),
                        'module' => $request->getPost('module'),
                        'section' => $request->getPost('section'),
                        'weight' => $request->getPost('weight')
                    );
                    $db->table('hd_blocks')->insert($data);
                    $block_id = $db->insertID();
                } else {
                    $data = array(
                        'section' => $request->getPost('section'),
                        'weight' => $request->getPost('weight')
                    );
                    $db->table('hd_blocks')->where('id', $request->getPost('id'))->where('module', $request->getPost('module'))->where('theme', $cus_name->getconfig_item('active_theme'))->update($data);
                    $block_id = $block->block_id;
                }
            }

            $db->table('hd_blocks_pages')->where('block_id', $block_id)->where('theme', $cus_name->getconfig_item('active_theme'))->where('module', $request->getPost('module'))->delete();
            $pages = $request->getPost('pages');
            if ($request->getPost('section') != '') {
                if (is_array($pages) && count($pages) > 0) {
                    foreach ($pages as $page) {
                        $data = array(
                            'block_id' => $block_id,
                            'page' => $page,
                            'mode' => $request->getPost('mode'),
                            'module' => $request->getPost('module'),
                            'theme' => $cus_name->getconfig_item('active_theme')
                        );
                        $db->table('hd_blocks_pages')->insert($data);
                    }
                } else {
                    $data = array(
                        'block_id' => $block_id,
                        'page' => 'all',
                        'mode' => $request->getPost('mode'),
                        'module' => $request->getPost('module'),
                        'theme' => $cus_name->getconfig_item('active_theme')
                    );
                    $db->table('hd_blocks_pages')->insert($data);
                }
            }

            if ($request->getPost('section') != '' && is_array($pages) && count($pages) == 0) {
                $data = array(
                    'block_id' => $block_id,
                    'page' => 'all',
                    'mode' => $request->getPost('mode'),
                    'module' => $request->getPost('module'),
                    'theme' => $cus_name->getconfig_item('active_theme')
                );
                $db->table('hd_blocks_pages')->insert($data);
            }

            if ($request->getPost('type') == 'Module') {
                $settings = array('settings' => serialize(array('title' => $request->getPost('title'))));

                App::update('blocks_modules', array('param' => $request->getPost('id')), $settings);
            }

            Applib::go_to('blocks', 'success', lang('hd_lang.block_updated'));
            return $this->index();
        } else {
           // $id_array = explode('_', $id, 2);
            //print_r($id_array);
            $blocks = array();
			//print_r($type);die;
            //if (is_numeric($id_array[1]) || isset($id)) {
                if ($type == 'block') {
                    $block = $db->table('hd_blocks_custom')->where('id', $id)->get()->getRow();
                } else {
                    $block = $db->table('hd_blocks_modules')->where('param', $id)->get()->getRow();
                }
            //} else {
				
                if (is_dir(APPPATH . 'modules/' . $type . '/views')) {
                    $views = scandir(APPPATH . 'modules/' . $type . '/views/');
                    foreach ($views as $view) {
                        $name = explode('.', $view);
                        $str = $name[0];
                        if ($str == $id) {
                            $mod = array('id' => $id, 'name' => ucfirst(implode(' ', array_slice($id, 1, -1))), 'type' => 'Module', 'module' => ucfirst($type));
                            $block = (object) $mod;
                        }
                    }
                } 
           // }	
            if (is_dir(APPPATH . 'Views/' . $cus_name->getconfig_item('active_theme') . '/blocks/')) {
                $views = scandir(APPPATH . 'Views/' . $cus_name->getconfig_item('active_theme') . '/blocks/');
				
				$directoryPath = APPPATH . 'Views/' . $cus_name->getconfig_item('active_theme') . '/blocks/';
				
                foreach ($views as $view) {
					$filePath = $directoryPath . $view;
					
					if ($view !== '.' && $view !== '..' && is_file($filePath)) {
					
					 	$fileContent = file_get_contents(APPPATH . 'Views/' . $cus_name->getconfig_item('active_theme') . '/blocks/' . $view); 							// Read the entire file content
        
						preg_match('|Name:(.*)$|mi', $fileContent, $name);
						//preg_match('|Name:(.*)$|mi', $data, $name);
						if (count($name) > 0) {
							$blocks[] = (object) array('name' => trim($name[1]), 'section' => explode('.', $view)[0]);
						}
					}
                }
            }
			
            $full_id = explode('_', $id);
            $filter = ($full_id[0] == 'block') ? $full_id[1] : $id;
            $config = $db->table('hd_blocks')->select('hd_blocks.*, hd_blocks_pages.*')->join('hd_blocks_pages', 'hd_blocks_pages.block_id = hd_blocks.block_id', 'INNER')->where('hd_blocks.id', $filter)->where('hd_blocks.theme', $cus_name->getconfig_item('active_theme'))->get()->getResult();

            $model = new Page();
            $data = array();
            // $this->template->title(lang('configure')); 
            $data['page'] = lang('configure') . ' ' . lang('block');
            $data['pages'] = $model->get_pages();
            $data['blocks']    = $blocks;
            $data['config']    = $config;
            $data['_block']    = $block;
            $data['id'] = $id;
            return view('modules/blocks/views/configure', $data);
        }
    }
}