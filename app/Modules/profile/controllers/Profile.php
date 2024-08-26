<?php
 /*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

namespace App\Modules\profile\controllers;

use App\Libraries\AppLib;
use App\Models\App;
use App\Models\Client;
use App\Models\User;
use App\ThirdParty\MX\WhatPanel;
use App\Modules\Layouts\Libraries\Template;
use App\Helpers\custom_name_helper;
use App\Modules\Dashboard\Controllers\Layouts as ControllersLayouts;
use App\Libraries\Tank_auth;

class Profile extends WhatPanel
{

	function __construct()
	{
		// parent::__construct();
		// User::logged_in();		
		// $this->load->model(array('App','Client'));
		// $this->applib->set_locale();
	}

	
	function index(){
		return redirect()->to('profile/settings');
	}


	
	function switch() 
	{
		$request = \Config\Services::request();

		$session = \Config\Services::session();

		$db = \Config\Database::connect();

		// echo"<pre>";print_r(session()->get());die;

		$user = User::view_user($request->getPost('user_id'));

		$client = $db->table('hd_companies')->where('primary_contact', $request->getPost('user_id'))->get()->getRow();

		// echo "<pre>";print_r($client);die;

		$user_data = [
			'user_id' => $user->id,
			'username' => $user->username,
			'role_id' => $user->role_id,
			'status' => ($user->activated == 1) ? STATUS_ACTIVATED : STATUS_NOT_ACTIVATED,
			'client_id' => $client->co_id,
			'is_admin' => TRUE
		];

		// echo "<pre>";print_r($user_data);die;

		$session->set('userdata', $user_data);

		// $session->set('client_id', $client->co_id);

		// echo "<pre>";print_r($session->get());die;

		return redirect()->to('clients');
	}

	
	function switch_back() {
		// error_reporting(E_ALL);
        // ini_set("display_errors", "1");
		$request = \Config\Services::request();

		$session = \Config\Services::session();
			// echo"<pre>";print_r(session()->get());die;
		$user = User::view_user($session->get('userid'));
		//echo"<pre>";print_r($session->get('userdata.user_id'));die;
			$user_data = [
				'user_id' => $user->id,
				'username' => $user->username,
				'role_id' => $user->role_id,
				'status' => ($user->activated == 1) ? STATUS_ACTIVATED : STATUS_NOT_ACTIVATED
			];
			//print_r($user_data);die;
			$session->set('userdata', $user_data);
			// redirect(base_url('dashboard'));
			return redirect()->to('dashboard');

	}


	function settings()
	{	
		$request = \Config\Services::request();
		
		if($_POST){
			AppLib::is_demo();

		$custom_fields = array();
		foreach ($_POST as $key => &$value) {
			if (strpos($key, 'cust_') === 0) {
				$custom_fields[$key] = $value;
				unset($_POST[$key]);
			}
		}
		$db = \Config\Database::connect();
		$validation = \Config\Services::validation();
		// echo "<pre>";print_r($request->getPost());die;
		$validation->setRules(['fullname' => 'required']);
    	//$validation->setTemplate('<span class="text-danger">', '</span><br>');

		if ($validation->run($request->getPost()) == FALSE) // validation hasn't been passed
		{
			session()->setFlashdata('response_status', 'error');
			session()->setFlashdata('message',lang('hd_lang.error_in_form'));
			$_POST = '';
			$this->settings();
			return redirect()->to('profile/settings');
		}else{ 
			$request = \Config\Services::request();
			$id = $request->getPost('co_id'); 

			foreach ($custom_fields as $key => $f) {
				$key = str_replace('cust_', '', $key);
				$r = $db->table('hd_formmeta')->where(array('client_id'=>$id,'meta_key'=>$key))->get();
				$cf = $db->table('hd_fields')->where('name',$key)->get();
				$data = array(
					'module'    => 'clients',
					'field_id'  => $cf->getRow()->id,
					'client_id' => $id,
					'meta_key'  => $cf->getRow()->name,
					'meta_value'    => is_array($f) ? json_encode($f) : $f
				);
				($r->num_rows() == 0) ? $db->table('hd_formmeta')->insert($data) : $db->table('hd_formmeta')->where(array('client_id'=>$id,'meta_key'=>$cf->getRow()->name))->update($data);
			}

			$postData = $request->getPost();


			if (isset($_POST['company_data'])) {
				$company_data = $postData['company_data'];
				// Client::update($id,$company_data);
				$db->table('hd_companies')->where('co_id', $id)->update($company_data);
				unset($postData['company_data']);
			}

			// Unset the 'files' key
			unset($postData['co_id']);

			// echo "<pre>";print_r($postData);die;
			// App::update('account_details',array('user_id'=>User::get_id()),$request->getPost());
			$db->table('hd_account_details')->where(array('user_id'=>User::get_id()))->update($postData);

			session()->setFlashdata('response_status', 'success');
			session()->setFlashdata('message',lang('hd_lang.profile_updated_successfully'));
			return redirect()->to('profile/settings');
		}

		}else{
			$template = new Template();
			$helper = new custom_name_helper();

			// Set the title using the template library
			$template->title(lang('hd_lang.profile').' - '.$helper->getconfig_item('company_name'));
			// Prepare data to be passed to the view
			$data = [
				'page' => lang('hd_lang.manage_profile'),
				'form' => true
			];
			// Load the view and pass data to it
			echo view('modules/profile/views/edit_profile', $data);		
		}
	}

	function changeavatar()
	{ 	
		$request = \Config\Services::request();
		if ($request->getPost()) {
		
		$tankAuth = new Tank_auth();
		Applib::is_demo();

		if(file_exists($_FILES['userfile']['tmp_name']) || is_uploaded_file($_FILES['userfile']['tmp_name'])) {
			$current_avatar = User::profile_info(User::get_id())->avatar;
							$config['upload_path'] = 'resource/avatar/';
							$config['allowed_types'] = 'gif|jpg|png|jpeg';
							$config['file_name'] = strtoupper('USER-'.$tankAuth->get_username()).'-AVATAR';
							$config['overwrite'] = FALSE;
			
							$upload = service('upload', $config);

							if (isset($upload) && ! $upload->do_upload())
									{	
										session()->setFlashdata('response_status', 'error');
										session()->setFlashdata('message',lang('hd_lang.avatar_upload_error'));
										redirect($request->getPost('r_url', TRUE));
							}else{		
										$data = isset($upload) ? $upload->data() : null;
										print_r($data);die;
										$ar = array('avatar' => $data['file_name']);
										App::update('account_details',array('user_id'=>User::get_id()),$ar);
										
								if(file_exists('resource/avatar/'.$current_avatar) 
									&& $current_avatar != 'default_avatar.jpg'){
									unlink('resource/avatar/'.$current_avatar);
								}
							}
				}

				if(isset($_POST['use_gravatar']) && $_POST['use_gravatar'] == 'on'){
					$ar = array('use_gravatar' => 'Y');
					App::update('account_details',array('user_id'=>User::get_id()),$ar);

				}else{ 
					$ar = array('use_gravatar' => 'N');
					App::update('account_details',array('user_id'=>User::get_id()),$ar);
					}

				session()->setFlashdata('response_status', 'success');
				session()->setFlashdata('message',lang('hd_lang.avatar_uploaded_successfully'));
				redirect('profile/settings');

					
			}else{
				session()->setFlashdata('response_status', 'error');
				session()->setFlashdata('message', lang('hd_lang.no_avatar_selected'));
				redirect('profile/settings');
		}
	}

	function activities()
	{	
		//$layouts = new ControllersLayouts();
		$template = new Template();
		$helper = new custom_name_helper();
		$db = \Config\Database::connect();
		$template->title(lang('hd_lang.profile').' - '.$helper->getconfig_item('company_name'));
		$data['page'] = lang('hd_lang.activities');
		$data['datatables'] = TRUE;
		$data['lastseen'] = $helper->getconfig_item('last_seen_activities');
		$db->table('hd_config')->where('config_key','last_seen_activities')->update(array('value'=>time()));
		//$this->template
		//->set_layout('users')
		//->build('activities',isset($data) ? $data : NULL);
		echo view('modules/profile/views/activities', $data);		
	}

	function help()
	{
	$this->load->model('profile_model');
	$this->load->module('layouts');
	$this->load->library('template');
	$this->template->title(lang('hd_lang.profile').' - '.config_item('company_name'));
	$data['page'] = lang('hd_lang.home');
	$this->template
	->set_layout('users')
	->build('intro',isset($data) ? $data : NULL);
	}
}

/* End of file profile.php */