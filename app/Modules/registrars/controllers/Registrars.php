<?php
 /*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

namespace App\Modules\registrars\controllers;

use App\Libraries\AppLib;
use App\Models\Plugin;
use App\Models\User;
use App\ThirdParty\MX\WhatPanel;
use App\ThirdParty\MX\Modules;


class Registrars extends WhatPanel 
{

    protected $pluginModel;

	function __construct()
	{
        // User::logged_in(); 

        // $this->load->module('layouts');

        // if (User::is_client()) {
        //     AppLib::go_to('dashboard', 'error', lang('hd_lang.access_denied'));
        // } 

        $this->pluginModel = new Plugin();
    }
    


	function index($id = null)
	{		
        $request = \Config\Services::request();

        //$this->template->title(lang('hd_lang.domain_registrars'));
        $data['page'] = lang('hd_lang.domain_registrars');	
        $data['datatables'] = TRUE;

        // Pagination Configuration
		$page = $request->getGet('page') ? $request->getGet('page') : 1;

        $perPage = $request->getGet('recordsPerPage', FILTER_SANITIZE_NUMBER_INT) ? $request->getGet('recordsPerPage', FILTER_SANITIZE_NUMBER_INT) : 10;

        // Search Filter
		$search = $request->getGet('search');
		$data['search'] = $search;

        $query = $this->pluginModel->listDomainRegistrars([], $search, $perPage, $page);

        // Get items for the current page
		$data['registrars'] = array_map(function($item) {
			return (object) $item;
		}, $query['items']);

        $data['pager'] = $query['pager'];

		$data['message'] = $query['message'];

		$data['perPage'] = $perPage;

        return view('modules/registrars/index', $data);
    }

 

     function config ($registrar = null){ 
        if($this->input->post())
        { 
            Applib::is_demo();
            Applib::update('plugins',
		    array('plugin_id' => $this->input->post('id')), array('config' => serialize($this->input->post())));
                $this->session->set_flashdata('response_status', 'success');
				$this->session->set_flashdata('message', ucfirst($this->input->post('system_name')) . ' ' . lang('hd_lang.settings_updated'));
				redirect($_SERVER['HTTP_REFERER']); 
             
        }
        else
        {
            $data['config'] = Plugin::get_plugin($registrar); 
            $this->load->view('modal/config', $data); 
        }
      
     }



     function check_balance ($registrar){ 
          
        $module = Modules::run($registrar.'/check_balance', '');
        $data['response'] = isset($module['response']) ? $module['response'] : '';
        $data['registrar'] = $registrar;
        $this->load->view('balance', $data);  
      
     }


}

/* End of file Registrars.php */