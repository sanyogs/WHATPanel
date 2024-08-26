<?php
 /*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

namespace App\Modules\plugins\controllers;

use App\Libraries\AppLib;
use App\Models\Plugin;
use App\Models\Update;
use App\ThirdParty\MX\WhatPanel;
use App\ThirdParty\MX\Modules;
use DateInterval;
use DateTime;
use App\Helpers\module_helper;

class Plugins extends WhatPanel 
{

    private $_plugins;
    protected $plug;

    public function __construct()
    {
        parent::__construct();

        error_reporting(E_ALL);
        ini_set("display_errors", "1");
        
        // $this->load->module('layouts');
        // $this->load->library(array('template','settings'));  
        // $this->load->model(array('Plugin', 'Update')); 
        // $this->load->helper('form');
        // $this->_plugins = $this->Plugin->get_plugins();
        // $this->module->update_all_module_headers();

        $session = \Config\Services::session();
			

			
			// Modify the 'default' property
			
			// Connect to the database
			$dbName = \Config\Database::connect();

            $this->plug = new Plugin($dbName);

            $this->_plugins = $this->plug->get_plugins();
    }

    
    public function index()
    {
        error_reporting(E_ALL);
        ini_set("display_errors", "1");

        $request = \Config\Services::request();

        $data = array();
        $data['plugins'] = $this->plug->get_plugins(); 
        // $this->template->title(lang('hd_lang.plugins').' - '.config_item('company_name'));
        $data['page'] = lang('hd_lang.plugins'); 
        $data['datatables'] = true;

        // Pagination Configuration
		//$page = $request->getGet('page') ? $request->getGet('page') : 1;

        //$perPage = $request->getGet('recordsPerPage', FILTER_SANITIZE_NUMBER_INT) ? $request->getGet('recordsPerPage', FILTER_SANITIZE_NUMBER_INT) : 10;

		// Search Filter
		//$search = $request->getGet('search');
		//$data['search'] = $search;

        //$query = $this->plug->listItems([], $search, $perPage, $page);

        // echo "<pre>";print_r($query);die;

        // Get items for the current page
		//$data['plugins'] = array_map(function($item) {
		//	return (object) $item;
		//}, $query['items']);

       // $data['pager'] = $query['pager'];

		//$data['message'] = $query['message'];

		//$data['perPage'] = $perPage;

        // echo "<pre>";print_r($data);die;
        // $this->template
        // ->set_layout('users')
        // ->build('plugin_list',isset($data) ? $data : NULL);
        return view('modules/plugins/views/plugin_list', $data);
    }


    public function config($plugin = null)
    {
        $request = \Config\Services::request();

        $session = \Config\Services::session();
        // Connect to the database
        $dbName = \Config\Database::connect();

        if($request->getPost())
        {
            $data = [
                'config' => json_encode($request->getPost()),
            ];

            $dbName->table('hd_plugins')->where('plugin_id', $request->getPost('id'))->update($data);

            return redirect()->to('plugins');
        }else
        {
            $data = array();

            if( ! $plugin)
            {
                return redirect()->to('plugins');
            }
            elseif( ! isset($this->_plugins[$plugin]))
            {
                die("Unknown plugin {$plugin}");
            }
            elseif($this->_plugins[$plugin]->status != 1)
            {
                die("The plugin {$plugin} isn't enabled");
            }

            $data['config'] = $this->plug::get_plugin($plugin); 
            $data['plugin'] = $plugin;
			// echo"<pre>";print_r($data);die;
            //$this->load->view('modal/settings', $data);
            return view('modules/plugins/views/modal/settings', $data);
        }
    }


    public function activate($module)
    {
        $module_helper = new module_helper();
        if($module_helper->enable_module($module))
        {
            // $this->session->set_flashdata('response_status', 'success');
            // $this->session->set_flashdata('message', 'Enabled');				
            // redirect($_SERVER['HTTP_REFERER']);

            $session = \Config\Services::session();

            $session->setFlashdata('response_status', 'success');
            $session->setFlashdata('message', 'Enabled');

            return redirect()->to('plugins');
        }	 
    }



    public function deactivate($module)
    {
        $module_helper = new module_helper();

        if($module_helper->disable_module($module))
        {
            // $this->session->set_flashdata('response_status', 'success');
            // $this->session->set_flashdata('message', 'Disabled');				
            // redirect($_SERVER['HTTP_REFERER']);

            $session = \Config\Services::session();

            $session->setFlashdata('response_status', 'success');
            $session->setFlashdata('message', 'Disabled');

            return redirect()->to('plugins');
        }  
    }



    public function uninstall($plugin)
    {
        $session = \Config\Services::session();

        

			
			// Modify the 'default' property
			
			// Connect to the database
			$dbName = \Config\Database::connect();

            $plug = new Plugin($dbName);
            
        if($plug::reset_settings($plugin))
        {
            // $this->session->set_flashdata('response_status', 'success');
            // $this->session->set_flashdata('message', 'Plugin settings removed');
            $session = \Config\Services::session();

            $session->setFlashdata('response_status', 'success');
            $session->setFlashdata('message', 'Plugin settings removed');
        }   
        else
        {
            // $this->session->set_flashdata('response_status', 'success');
            // $this->session->set_flashdata('message', 'Error occurred!');

            $session = \Config\Services::session();

            $session->setFlashdata('response_status', 'success');
            $session->setFlashdata('message', 'Error occurred!');
        }  
        
        // redirect($_SERVER['HTTP_REFERER']); 
        return redirect()->to('plugins');
    }

     

    public function upload(){
    
        if ($this->input->post()) {

            $this->load->library('Files');
            $main_path = FCPATH . "/resource/uploads/plugins";
            $upload_data = $this->files->upload_files('plugin_file', $main_path);

            $zip = $main_path . "/" . $upload_data['file_name'];
            $unzip_path = $main_path . "/" . $upload_data['raw_name']; 
            $controller_path = $unzip_path . "/" . $upload_data['raw_name'] . "/controllers";
            $model_path = $unzip_path . "/" . $upload_data['raw_name'] . "/models";
            $sql = $unzip_path . "/" . $upload_data['raw_name'] . ".sql";
            $plugin_module_path = $unzip_path . "/" . $upload_data['raw_name'];
            $js = $unzip_path . "/" . $upload_data['raw_name'] . ".js";
            $css = $unzip_path . "/" . $upload_data['raw_name'] . ".css";
            $js_path = FCPATH ."/resource/js/" . $upload_data['raw_name'] . ".js";
            $css_path = FCPATH ."/resource/css/" . $upload_data['raw_name'] . ".css";

            //unzip plugin
            if (strlen($upload_data['file_name']) > 0) {
                $this->load->library('Unzip');
                $this->unzip->extract($zip, $unzip_path);
                $this->unzip->close();
                //check if xml exists
                if ($this->files->check_if_file_exists($xml)) {
                    //check if xml format is good
                    $main_elms = array('name', 'description', 'defaultcontroller', 'defaultbackendmethod', 'version', 'developer', 'developercontact');
                    if ($this->files->is_main_xml_correct($xml, $main_elms)) {
              
                     
   
                       // $plugin_id = $this->plugin_model->add_plugin($plugin_data);
                        //check controller
                        if (!$this->files->is_dir_empty($controller_path)) {
                          //check model
                           
                            //check if admin menu exists
                            if ($this->files->if_node_exits($xml, 'admin_menu')) {        
                                                        
                                //move files to modules
                                $this->files->move_files($plugin_module_path, $module_path);
                                //move js
                                if ($this->files->check_if_file_exists($js))
                                    $this->files->move_files($js, $js_path);
                                //move css
                                if ($this->files->check_if_file_exists($css))
                                    $this->files->move_files($css, $css_path);
                                if (strlen($upload_data['raw_name']) > 0)
                                    $this->files->delete_directory($unzip_path);
                                if ($this->files->check_if_file_exists($zip))
                                    $this->files->delete_file($zip);

                                $this->index("Plugin has been successfully installed.", 'info');
                            } else {
                                if (strlen($upload_data['raw_name']) > 0)
                                    $this->files->delete_directory($unzip_path);
                                //delete zip
                                if ($this->files->check_if_file_exists($zip))
                                    $this->files->delete_file($zip);
                                //delete js
                                if ($this->files->check_if_file_exists($js_path))
                                    $this->files->delete_file($js_path);
                                //delete css
                                if ($this->files->check_if_file_exists($css_path))
                                    $this->files->delete_file($css_path);
                                //show message in view
                                $this->index("Sorry, Plugin has not been installed. Admin menu was not found", 'error');
                            }

                        } else {
                            if (strlen($upload_data['raw_name']) > 0)
                                $this->files->delete_directory($unzip_path);
                            //delete zip
                            if ($this->files->check_if_file_exists($zip))
                                $this->files->delete_file($zip);
                            //delete js
                            if ($this->files->check_if_file_exists($js_path))
                                $this->files->delete_file($js_path);
                            //delete css
                            if ($this->files->check_if_file_exists($css_path))
                                $this->files->delete_file($css_path);
                            //show message in view
                            $this->index("Sorry, Plugin has not been installed. Kindly check whether the controller exists", 'error');
                        }
                    } else {

                        //delete folder
                        $this->files->delete_directory($unzip_path);
                        //delete zip
                        if ($this->files->check_if_file_exists($zip))
                            $this->files->delete_file($zip);
                        //delete js
                        if ($this->files->check_if_file_exists($js_path))
                            $this->files->delete_file($js_path);
                        //delete css
                        if ($this->files->check_if_file_exists($css_path))
                            $this->files->delete_file($css_path);

                        //show message in view
                        $this->index("Sorry, Plugin has not been installed. The " . $upload_data['raw_name'] . ".xml file is missing a few key elements", 'error');
                    }

                } else {
                    //delete folder
                    if (strlen($upload_data['raw_name']) > 0)
                        $this->files->delete_directory($unzip_path);
                    //delete zip
                    if ($this->files->check_if_file_exists($zip))
                        $this->files->delete_file($zip);
                    //delete js
                    if ($this->files->check_if_file_exists($js_path))
                        $this->files->delete_file($js_path);
                    //delete css
                    if ($this->files->check_if_file_exists($css_path))
                        $this->files->delete_file($css_path);
                    //show message in view
                    $this->index("Sorry, Plugin has not been installed. Kindly check whether " . $upload_data['raw_name'] . ".xml exists", 'error');
                }
            } else {
                $this->index("Sorry, Plugin has not been installed. Kindly upload the plugin zip file", 'error');
            }
        } 

    }


    public function download()
    {        
        $this->template->title(lang('hd_lang.plugins').' - '.config_item('company_name'));
        $data['page'] = lang('hd_lang.plugins'); 
        $data['datatables'] = true;
        $data['plugins'] = Plugin::active_plugins();
        $this->template->set_layout('users')->build('download',isset($data) ? $data : NULL);	
    }


    function install($id)
    {  
      //$this->template->title('Update '.config_item('company_name'));
      $data['page'] = 'Update Information';      
      $data['version_notifications_array'] = $this->process($id);
      $data['status'] = '';
      //$this->template
     // ->set_layout('users')
     // ->build('install',isset($data) ? $data : NULL);		
		return view('modules/plugins/views/install', $data);
    }


    function installed()
    {  
        return redirect()->to('plugins'); 	
    }


    function version($id)
    {   
       $this->template->title('Update '.config_item('company_name'));
      $data['page'] = 'Version Information';  
      $data['version_notifications_array'] = Update::version($id); 
      $data['version'] = $id;
      $this->template
      ->set_layout('users')
      ->build('version',isset($data) ? $data : NULL);		
    }


    function process($id)
    {
      $response = Update::install($id);

      if($response['notification_case'] == "notification_operation_ok")
        {
          $database = Update::database($id);

          if ($database['notification_case'] == "notification_operation_ok")  
            {
                $sql = trim($database['notification_data']);
                if($sql != '')
                {
                  $sqls = explode(';', $sql);
                  array_pop($sqls);

                  $this->db->trans_start();
                  foreach($sqls as $statement)
                  {
                      $statment = trim($statement) . ";";
                      $this->db->query($statement);   
                  }
                  $this->db->trans_complete(); 
                }               
            }
        }

        return $response;
    }


    public function add_plugin(){
        //$this->load->view('modal/add_plugin'); 
		return view('modules/plugins/views/modal/add_plugin');
    }

}