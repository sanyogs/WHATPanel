<?php

namespace app\Modules\settings\controllers;

use App\Libraries\AppLib;
use App\Helpers\app_helper;
use App\Models\User;
use App\ThirdParty\MX\WhatPanel;

use function system\Helpers\underscore;

class Fields extends WhatPanel
{

    public function __construct()
    {
        // parent::__construct();
        // $this->load->module('layouts');
        // $this->load->library('template');
        // $this->template->title(lang('hd_lang.settings') . ' - ' . config_item('company_name'));
        // $this->load->model(array('Setting'));
        // $this->applib->set_locale();

        // User::logged_in();
        // if (!User::is_admin())
        //     redirect('');
    }

    function index()
    {
        
        $data['page'] = lang('hd_lang.settings');
        $data['formbuilder'] = TRUE;
        $data['load_setting'] = 'fields';
        $data['module'] ='';
        $data['fields'] = [];
        // $module = [];
        // print_r($module);die;
        // print_r($data);die;
        // $this->template
        //     ->set_layout('users')
        //     ->build('fields/custom.php', isset($data) ? $data : NULL);
        return view('modules/settings/fields/custom', $data);

    }
    function module($type = Null)
    {      
        $request = \Config\Services::request();
        
        $data['page'] = lang('hd_lang.settings');
        $data['formbuilder'] = TRUE;
        $data['load_setting'] = 'fields';

        $data['module'] = (isset($type)) ? $type : $request->getPost('module');
        // print_r($data);die;
        if ($_POST) {
            if (isset($data['module'])) {
                // $data['module'] = $request->getPost('module');
                $data['department'] = ($request->getPost('department')) ? $request->getPost('department') : NULL;
            }
        } else {
            $module = isset($_GET['mod']) ? $_GET['mod'] : '';
            if ($module != '') {
                $data['module'] = $module;
            } else {
                $data['module'] = '';
            }
        }

        $session = \Config\Services::session();

        // Connect to the database
        $db = \Config\Database::connect();

        $orderModel = new \App\Models\Order();

        // $data['fields'] = $orderModel->table('hd_fields')->orderBy('order', 'DESC')->where('module', $data['module'])->get()->getResult();
        $data['fields'] = $db->table('hd_fields')->orderBy('order', 'DESC')->where('hd_fields.module', $data['module'])->get()->getResult();
        // print_r($data);die;
        return view('modules/settings/fields/custom', $data);
    }

    public function saveform($empty = Null)
    {   
        // echo 63; die;
        helper('inflector');
        helper('app_helper');
        
        $request = \Config\Services::request();
        
        // print_r($request->getPost());die;
        // $module = $_POST['module'];
        $module = $request->getPost('module');
        
        $fields = $request->getPost('formcontent');
        
        $fields = json_decode($_POST['formcontent'], true);
       
        $session = \Config\Services::session();

        $db_name = $session->get('db_name');

        $config = new \Config\Database();

        // Modify the 'default' property
        $config->default['database'] = $db_name;

        // Connect to the database
        $db = \Config\Database::connect($config->default);

        // Delete records from the 'fields' table where 'module' matches the given value
        $result = $db->table('hd_fields')->where('module', $module)->delete();
        // print_r($result);die;
        $order = 1;
        // print_r($fields['fields']);die;
        if (is_array($fields['fields'])) 
        {   
            foreach ($fields['fields'] as $key => $f) {
                
                $id_exist = 0;
                
                if (isset($f->uniqid)):
                    
                    $fields = $request->getPost('formcontent');
    
                    $session = \Config\Services::session();
    
                    $db_name = $session->get('db_name');
    
                    $config = new \Config\Database();
    
                    // Modify the 'default' property
                    $config->default['database'] = $db_name;
    
                    // Connect to the database
                    $db = \Config\Database::connect($config->default);
    
                    // $this->db->where('uniqid', $f['uniqid'])->get('fields');
    
                    $db->table('hd_fields')->where('uniqid', $f['uniqid'])->get();
                       
                    $id_exist = $db->affectedRows();
                    
                    // print_r($id_exist); die;
                endif;

                $applib = new AppLib();
                // echo"<pre>";print_r($applib);die;
                $uniqid = ($id_exist == 0) ? $applib->generate_unique_value() : $f['uniqid'];

                $app = new app_helper();

                $data = array(
                    'label' => $f['label'],
                    'module' => $module,
                    'deptid' => $_POST['deptid'],
                    'name' => underscore($app->clean($f['label'])),
                    'uniqid' => $uniqid,
                    'type' => $f['field_type'],
                    'required' => $f['required'],
                    'field_options' => json_encode($f['field_options']),
                    'cid' => $f['cid'],
                    'order' => $order++
                );
    
                $session = \Config\Services::session();
    
                $db_name = $session->get('db_name');
    
                $config = new \Config\Database();
    
                // Modify the 'default' property
                $config->default['database'] = $db_name;
    
                // Connect to the database
                $db = \Config\Database::connect($config->default);
    
                // ($id_exist == 0) ? $db->table('hd_fields', $data)->insert('hd_fields', $data) : $db->where('uniqid', $f['uniqid'])->update();
                
                if ($id_exist == 0) {
                    $db->table('hd_fields')->insert($data);
                } else {
                    $db->table('hd_fields', $data)->where('uniqid', $f['uniqid'])->update();
                }
            }
        }
        else {
            "WOOOPPPSSS Something went wrong :(";
        }

        // redirect('settings/fields/module?mod=' . $module);
        return redirect()->to('public/settings/fields/module/' . $module);
        // return redirect()->to('public/settings/fields/module');

    }

}