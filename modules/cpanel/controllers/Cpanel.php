<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */
/* Module Name: cPanel
 * Module URI: https://whatpanel.com
 * Version: 1.0
 * Category: Servers
 * Description: cPanel API Integration.
 * Author:  WHAT PANEL 
 * Author URI: https://whatpanel.com
 */

namespace Modules\cpanel\controllers;

use App\ThirdParty\MX\WhatPanel;

use Modules\cpanel\libraries\Cpanel_exec;
use Modules\cpanel\controllers\new_Cpanel;

class cPanel extends WhatPanel
{
    public function cpanel_config($values = null)
    {	
		//echo 555;die;
		$result = new new_Cpanel();
		$packages = $result->package_Name($values); // Fetch package names from the cPanel API

		// Initialize an empty array to store package options
		$packageOptions = [];

		// Generate options for the dropdown
		foreach ($packages as $package) {
			// Assuming the 'id' of the package is its name
			$packageOptions[$package] = $package;
		}

		$config = array(
			array(
				'label' => 'Package Name',
				'id' => 'package',
				'options' => $packageOptions, // Assign package options to the field
				'value' => isset($packageOptions) ? $packageOptions[$package] : null, // Set the selected value if provided
				'type' => 'select' // Set the field type to 'select' for dropdown
			) 
		); 

		return $config;       
    }

    public function cpanel_config_old($values = null, $config = null)
    {
        $configur = array(
            'form_id' => 'banktransferForm', // Dynamic form ID
            'fields' => array(
                array(
                    'id' => 'id',
                    'type' => 'hidden',
                    'value' => $config->plugin_id
                ),
                array(
                    'id' => 'system_name',
                    'type' => 'hidden',
                    'value' => $config->system_name
                ),
                array(
                    'label' => 'Package Name',
                    'id' => 'package',
                    'placeholder' => 'The package name as it appears in WHM',
                    'value' => isset($values) ? $values['package'] : '',
                    'type' => ''
                ),
                array(
                    'id' => 'submit',
                    'type' => 'submit',
                    'label' => 'Save',
                    'class' => 'common-button'
                )
            ) 
        ); 
        
        return $configur;        
    }



    public function check_connection ($server = NULL)
    {
        $params = array(
            'user' => trim($server->username)
        );  

        $cpanelLib = new Cpanel_exec($server);
        
        //$this->load->library('cpanel/Cpanel_exec', $server);
        // $response = $this->cpanel_exec->call('accountsummary', $params);

        $response = $cpanelLib->call('accountsummary', $params);
        return (isset($response['metadata']['reason'])) ? $response['metadata']['reason'] : 'Connection Failed!'; 
    }



    public function create_account ($params)
    {          				
        $this->load->library('cpanel/Cpanel_exec', $params->server);
        $payload = array(
            'plan' => $params->package->package_name,
            'username' => $params->account->username,
            'password' => $params->account->password,
            'domain' => $params->account->domain,
            'contactemail' => $params->client->company_email,
            'cgi' => 1,
            'hasshell' => 1,
            'cpmod' => 'paper_lantern'
        );

        $response =$this->cpanel_exec->call('createacct', $payload);
        $result .= $domain." ".$response['metadata']['reason'];	
            

        if($params->package->reseller_package == 'Yes') { 
                if(isset($response['metadata'])){
                    $payload = array(
                        'makeowner' => 1,
                        'username' => $params->account->username
                    );

                    $response = $this->cpanel_exec->call('setupreseller', $payload);
                    $result .=  $response['metadata']['reason'];

                }
            }
            
        return $result;
    }



    public function suspend_account ($params)
    {      
        $this->load->library('cpanel/Cpanel_exec', $params->server);        
       
            $req = array(							
                "user" => $params->account->username,
                "reason" => $params->reason
            );			

            if($params->package->reseller_package == 'Yes') 
            {
                $response = $this->cpanel_exec->call('suspendreseller', $req); 
            }
            else
            {
                $response = $this->cpanel_exec->call('suspendacct', $req); 
            } 
             
            $result = isset($response['metadata']['reason']) ? $response['metadata']['reason'] : '';        
            
        return $result;
    }




    public function unsuspend_account ($params)
    {        
        $this->load->library('cpanel/Cpanel_exec', $params->server);								 
        $req = array(							
            "user" => $params->account->username 
        );			
        $response = $this->cpanel_exec->call('unsuspendacct', $req); 
        $result = $response['metadata']['reason'];
            
        return $result;
    }




    public function change_password ($params)
    {       
        $this->load->library('cpanel/Cpanel_exec', $params->server);								 
        $req = array(							
            "user" => $params->account->username,
            "password" => $params->account->password
        );			
        $response = $this->cpanel_exec->call('passwd', $req); 
        $result = $response['metadata']['reason'];
            
        return $result;
    }




    public function get_usage ($order)
    {
        $params = array();
        $usage = array('disk_limit' => 0, 'disk_used' => 0, 'bw_limit' => 0, 'bw_used' => 0);
        $server = Order::get_server($order->server);

        $this->load->library('cpanel/Cpanel_exec', $server);
        $response = $this->cpanel_exec->call('showbw', $params);      

        if(isset($response['data']['acct'])){
            $data = $response['data']['acct'];
            foreach($data AS $account) {
                if($account['maindomain'] == $order->domain) {
                    $bwused = $account["totalbytes"];
                    $bwlimit = $account["limit"];
                    $usage['bw_used'] = $bwused / (1024 * 1024);
                    $usage['bw_limit'] = $bwlimit / (1024 * 1024);
                }
            }
        }       

        $params = array( 
            'domain' => $order->domain
        );
        $response = $this->cpanel_exec->call('accountsummary', $params); 

        if(isset($response['data']) && isset($response['data']['acct'][0])) { 
            $data = $response['data']['acct'][0];
            $usage['disk_limit'] = preg_replace('/[^0-9]/', '', $data['disklimit']);
            $usage['disk_used'] = preg_replace('/[^0-9]/', '', $data['diskused']); 
        }
       
        return $usage;
    }
    
    

    public function change_package ($params)
    {
        $this->load->library('cpanel/Cpanel_exec', $params->server);								 
        $req = array(
            'user' => $params->account->username,
            'pkg' => $params->package->package_name			
        );
        $response = $this->cpanel_exec->call('changepackage', $req); 
        $result = $response['metadata']['reason'];
            
        return $result; 
    }



    public function terminate_account ($params)
    {
        $request = array(							
            'username' => $params->account->username								
        );
        
        $this->load->library('cpanel/Cpanel_exec', $params->server);

        if($params->package->reseller_package == 'Yes') {	
            $response = $this->cpanel_exec->call('terminatereseller', $request); 
        }
        else
        {
            $response = $this->cpanel_exec->call('removeacct', $request); 
        }

        $result = $response['metadata']['reason'];
            
        return $result; 
    }




    public function client_options ($id = null) 
    { 
        $code = '<a href="'.base_url().'accounts/view_logins/'.$id.'" class="btn btn-sm btn-success" data-toggle="ajaxModal">
        <i class="fa fa-eye"></i>'.lang('view_cpanel_logins').'</a>
        <a href="'.base_url().'accounts/change_password/'.$id.'" class="btn btn-sm btn-info" data-toggle="ajaxModal">
        <i class="fa fa-edit"></i>'.lang('change_cpanel_password').'</a>         
        <a href="'.base_url().'accounts/login/'.$id.'" class="btn btn-sm btn-warning" target="_blank"><i class="fa fa-sign-in"></i> &nbsp;'.lang('control_panel').'</a>';

        return $code; 
    }



    public function import($server)
    {
        try {

            $params = array(
                'user' => trim($server->username)
            ); 

            $cpanelLib = new Cpanel_exec($server);
            
            // $this->load->library('cpanel/Cpanel_exec', $server); 
            // $response = $this->cpanel_exec->call('listaccts', $params);
            $response = $cpanelLib->call('listaccts', $params);

            if(isset($response['data']) && is_array($response['data'])) {
                $list = (is_array($response['data']['acct'])) ? $response['data']['acct'] : ['metadata']['reason'];

                if(is_array($list)) {
                    foreach($list as $key => $li){
                        $list[$key]['pass'] = '';
                    }
                }
            }

            

          } catch (Exception $e) {
             $list = 'Error: '. $e->getMessage();  
          } 

          return (isset($list) && is_array($list)) ? $list : NULL;
    }




    public function client_login ($params)
    { 
        $session = \Config\Services::session();
        // $this->load->library('cpanel/Cpanel_exec', $params->server); 
        $cpanelLib = new Cpanel_exec($params->server);
        $req = array(							
            'user' => $params->account->username,
            'service' => 'cpaneld'
        );			
        $response = $cpanelLib->call('create_user_session', $req); 

        if(isset($response['data'])) {
            $url = $response['data']['url'];
            redirect($url);
        }
        
        else {		

            $session->setFlashdata('response_status', 'warning');
            //$session->setFlashdata('message', $response['metadata']['reason']);
            // redirect($_SERVER['HTTP_REFERER']);
            return redirect()->to('servers');
        }
    }




    public function admin_login ($server)
    {
        $session = \Config\Services::session();
        // $this->load->library('cpanel/Cpanel_exec', $server);
        $cpanelLib = new Cpanel_exec($server);
        $req = array(							
            'user' => $server->username,
            'service' => 'cpaneld'
        );			
        $response = $cpanelLib->call('create_user_session', $req); 

        if(isset($response['data'])) {
            $url = $response['data']['url'];
            $url = str_replace(2083,2087,$url);
            redirect($url);
        }
        
        else {			
            $session->setFlashdata('response_status', 'warning');
           // $session->setFlashdata('message', $response['metadata']['reason']);
            return redirect()->to('servers');
        }
    } 


	function admin_options($server)
	{ 
		// Assume $success holds the status of the connection test, true for success and false for failure
		$success = true; // Replace this with your actual logic to determine the success of the connection test

		$icon = $success ? '<i class="fa fa-check-circle"></i> ' : ''; // Green tick icon if success, empty string otherwise

		$code = '<a id="test-connection-btn" class="btn btn-success btn-xs" href="'.base_url().'servers/index/'.$server->id.'" style= "color:white !important;">
                '.lang('hd_lang.test_connection').'
            </a>
            <a class="btn btn-warning btn-xs" href="'.base_url().'servers/import/'.$server->id.'" style= "color:white !important;">
                <i class="fa fa-download"></i> '.lang('hd_lang.import_accounts').'
            </a>
            <a class="btn btn-primary btn-xs" href="'.base_url().'servers/edit_server/'.$server->id.'" data-toggle="ajaxModal" style= "color:white !important;">
                <i class="fa fa-pencil"></i> '.lang('hd_lang.edit').'
            </a>
            <a class="btn btn-danger btn-xs" href="'.base_url().'servers/delete_server/'.$server->id.'" data-toggle="ajaxModal" style= "color:white !important;">
                <i class="fa fa-trash"></i> '.lang('hd_lang.delete').'
            </a>
            <a class="btn btn-success btn-xs" href="'.base_url().'servers/login/'.$server->id.'" target="_blank" style= "color:white !important;">
                <i class="fa fa-user"></i> '.lang('hd_lang.login').'
            </a>';

		return $code;
	}



                
    public function activate($data)
    { 
        return true;
    }



    public function install()
    { 
        return true;
    }



    public function uninstall()
    { 
        return true;
    }


    
}
