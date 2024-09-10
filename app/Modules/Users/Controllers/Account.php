<?php
 /*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

namespace App\Modules\Users\Controllers;

use App\ThirdParty\MX\WhatPanel;
use App\Models\User;
use App\Modules\Layouts\Libraries\Template;
use App\Models\App;
use App\Libraries\AppLib;

use App\Helpers\custom_name_helper;

class Account extends WhatPanel 
{
		function __construct()
		{
			parent::__construct();

			$session = \Config\Services::session();			

			// Connect to the database
			$db = \Config\Database::connect();

			$user = new User($db);
			// if (!User::is_admin() && !User::perm_allowed(User::get_id(), 'edit_settings')) {
			// if (!User::is_admin()) {
			// 	redirect('dashboard');
			// }
			helper('security');
		}


	function index(){
		$this->active();
	}
 

	function active() 
	{
		// $this->load->module('layouts');
		// $this->load->library('template');
		$custom = new custom_name_helper();
		$template = new Template();
		$template->title(lang('hd_lang.users').' - '.$custom->getconfig_item('company_name'));
		$data['page'] = lang('hd_lang.users');
		$data['datatables'] = TRUE;
		$data['form'] = TRUE;
		// $this->template
		// ->set_layout('users')
		// ->build('users',isset($data) ? $data : NULL);
		echo view('modules/Users/users', $data);
	}
	

	function permissions()
	{	
		$request = \Config\Services::request();
		$db = \Config\Database::connect();
		if ($request->getPost()) {
			 $postData = $request->getPost();
			 $permissions = json_encode($postData);
			 $userId = $postData['user_id'];
			
			 //$permissions = json_encode($request->getPost());
			 $data = ['allowed_modules' => $permissions];
			 $db->table('hd_account_details')->where('user_id', $userId)->update($data);

			  session()->setFlashdata('response_status', 'success');
			  session()->setFlashdata('message', lang('hd_lang.settings_updated_successfully'));
	
			  return redirect()->to('account');
		}else{
			$staff_id = $this->request->uri->getSegment(3);

			if (User::login_info($staff_id)->role_id != '3') { 
				session()->setFlashdata('response_status', 'error');
				session()->setFlashdata('message', lang('hd_lang.operation_failed'));
				return redirect()->back();
			}
			$data['user_id'] = $staff_id;
			echo view('modules/Users/Modal/edit_permissions', $data);
		}
	}


	function update($id = null)
	{
		$request = \Config\Services::request();

        $session = \Config\Services::session();

		$custom = new custom_name_helper();

		$db = \Config\Database::connect();

		if ($request->getPost()) {
			if ($custom->getconfig_item('demo_mode') == 'TRUE') {
			$session->setFlashdata('response_status', 'error');
			$session->setFlashdata('message', lang('hd_lang.demo_warning'));
			return redirect()->to('users/account');
		}
		$validation = \Config\Services::validation();

		$validation->setRule('fullname', 'Full Name', 'required');

		if (!$validation->withRequest($this->request)->run())
		{
			$session->setFlashdata('response_status', 'error');
			$session->setFlashdata('message', lang('hd_lang.operation_failed'));
			return redirect()->to('account');
		}else{
			$user_id =  $request->getPost('user_id');

			$profile_data = array(
				'fullname' => $request->getPost('fullname'),
				'company' => $request->getPost('company'),
				'phone' => $request->getPost('phone'),
				'mobile' => $request->getPost('mobile'),
				'skype' => $request->getPost('skype'),
				'language' => $request->getPost('language'),
				'locale' => $request->getPost('locale'),
				'hourly_rate' => $request->getPost('hourly_rate')
			);

			if (isset($_POST['department'])) {
				$profile_data['department'] = json_encode($_POST['department']);
			}

			$db->table('hd_account_details')->where('user_id',$user_id)->update($profile_data);

			$data = array(
				'module' => 'users',
				'module_field_id' => $user_id,
				'user' => User::get_id(),
				'activity' => 'activity_updated_system_user',
				'icon' => 'fa-edit',
				'value1' => User::displayName($user_id),
				'value2' => ''
			);

			App::Log($data);

			$session->setFlashdata('response_status', 'success');
			$session->setFlashdata('message', lang('hd_lang.user_edited_successfully'));

			return redirect()->to('account');
		}
		}else{
			$data['id'] = $id;
			// $this->load->view('modal/edit_user',$data);
			echo view('modules/Users/Modal/edit_user', $data);
		}
	}


	function ban($id = null)
	{
		$request = \Config\Services::request();

        $session = \Config\Services::session();

		$custom = new custom_name_helper();

		$db = \Config\Database::connect();

		if ($request->getPost()) {
			$user_id = $request->getPost('user_id');
			$ban_reason = $request->getPost('ban_reason');

			$action = (User::login_info($user_id)->banned == 1) ? 0 : 1;

			 $data = array('banned' => $action,'ban_reason' => $ban_reason);

			 //App::update('users',array('id' => $user_id),$data);

			 $db->table('hd_users')->where('id',$user_id)->update($data);

			 $session->setFlashdata('response_status', 'success');
			 $session->setFlashdata('message', lang('hd_lang.settings_updated_successfully'));

			 return redirect()->to('account');

		}else{
			$user_id = $id;
			$data['user_id'] = $user_id;
			$data['username'] = User::login_info($user_id)->username;
			// $this->load->view('modal/ban_user',isset($data) ? $data : NULL);
			echo view('modules/Users/Modal/ban_user', $data);
		}
	}

	function auth($id = null)
	{
		$request = \Config\Services::request();

		$session = \Config\Services::session();

		$custom = new custom_name_helper();

		$db = \Config\Database::connect();

		if ($request->getPost()) {

			Applib::is_demo();

			$user_password = $request->getPost('password');
			$username = $request->getPost('username');

			//$this->config->load('tank_auth', TRUE);

			$validation = \Config\Services::validation();

			$validation->setRule('email', 'Email', 'required');
			$validation->setRule('username', 'User Name', 'required|trim|xss_clean');

			if (!empty($user_password)) {
				$validation->setRule('password', 'Password', 'trim|required|xss_clean|min_length[4]|max_length[32]');
				$validation->setRule('confirm_password', 'Confirm Password', 'trim|required|xss_clean|matches[password]');
			}

			if (!$validation->withRequest($this->request)->run()) {
				$session->setFlashdata('response_status', 'error');
				$session->setFlashdata('message', lang('hd_lang.operation_failed'));
				return redirect()->to('account');
			} else {
				date_default_timezone_set($custom->getconfig_item('timezone'));
				$user_id =  $request->getPost('user_id');
				$args = array(
					'email' 	=> $request->getPost('email'),
					'role_id' 	=> $request->getPost('role_id'),
					'modified' 	=> date("Y-m-d H:i:s")
				);

				$data = [
					'username' => $username,
				];
				
				// Specify the condition for the update
				$where = ['id' => $user_id];

				$result = $db->table('hd_users')->update($data, $where);

				if (!$result) {
					$session->setFlashdata('response_status', 'error');
					$session->setFlashdata('message', lang('hd_lang.username_not_available'));
					return redirect()->to('account');
				}

				//App::update('users', array('id' => $user_id), $args);

				if (!empty($user_password)) {
					//$this->tank_auth->set_new_password($user_id, $user_password);
					$args['password'] = password_hash($request->getPost('password'), PASSWORD_DEFAULT);
				}

				$db->table('hd_users')->where('id', $user_id)->update($args);

				$data = array(
					'module' => 'users',
					'module_field_id' => $user_id,
					'user' => User::get_id(),
					'activity' => 'activity_updated_system_user',
					'icon' => 'fa-edit',
					'value1' => User::displayName($user_id),
					'value2' => ''
				);

				App::Log($data);

				$session->setFlashdata('response_status', 'success');
				$session->setFlashdata('message', lang('hd_lang.user_edited_successfully'));
				return redirect()->to('account');
			}
		} else {
			$data['id'] = $id;
			// $this->load->view('modal/edit_login',$data);
			echo view('modules/Users/Modal/edit_login', $data);
		}
	}

	function delete($id = null)
	{
		$request = \Config\Services::request();

		$session = \Config\Services::session();
        
        // Connect to the database
        $db = \Config\Database::connect();

        $validation = \Config\Services::validation();

		$app = new App($db);

		if ($request->getPost()) {

		// Applib::is_demo();

		// $this->load->library('form_validation');
		$validation->setRule('user_id', 'User ID', 'required');
		if (!$validation->withRequest($this->request)->run())
		{
			$session->setFlashdata('response_status', 'error');
			$session->setFlashdata('message', lang('hd_lang.delete_failed'));
			$request->getPost('r_url');
		}else{
			$user = $request->getPost('user_id');
			//$deleted_user = User::displayName($user);

			// if (User::profile_info($user)->avatar != 'default_avatar.jpg') {
			// 	if(is_file('./resource/avatar/'.User::profile_info($user)->avatar))
			// 	unlink('./resource/avatar/'.User::profile_info($user)->avatar);
			// }
			$user_companies = App::get_by_where('hd_companies',array('primary_contact' => $user));
			foreach ($user_companies as $co) {
				$ar = array('primary_contact' => '');
				$app->client_update('hd_companies',array('primary_contact' => $user),$ar);
			}
			$user_tickets = App::get_by_where('hd_tickets',array('reporter' => $user));
			foreach ($user_tickets as $ticket) {
				$app->delete('hd_tickets',array('reporter' => $user));
			}
			$app->deleteApp('hd_activities', array('user' => $user));

			$app->deleteApp('hd_account_details', array('user_id' => $user));
			$app->deleteApp('hd_users', array('id' => $user));

			// Log activity
			$data = array(
				'module' => 'users',
				'module_field_id' => $user,
				'user' => User::get_id(),
				'activity' => 'activity_deleted_system_user',
				'icon' => 'fa-trash-o',
				'value1' => $user,
				'value2' => ''
				);
			App::Log($data);

			$session->setFlashdata('response_status', 'success');
			$session->setFlashdata('message', lang('hd_lang.user_deleted_successfully'));
			return redirect()->to($request->getPost('r_url'));
		}
		}else{
			$data['user_id'] = $id;
			// $this->load->view('modal/delete_user',$data);
			echo view('modules/Users/Modal/delete_user', $data);
		}
	}
}

/* End of file account.php */
