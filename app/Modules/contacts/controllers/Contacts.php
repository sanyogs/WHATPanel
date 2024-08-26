<?php
 /*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

namespace app\Modules\contacts\controllers;

use App\Models\User;
use App\ThirdParty\MX\WhatPanel;
use App\Libraries\AppLib;
use App\Helpers\custom_name_helper;
use CodeIgniter\HTTP\RequestInterface;
use App\Models\App;

class Contacts extends WhatPanel
{
	function __construct()
	{
		//parent::__construct();
		User::logged_in();

		if (!User::is_admin()) {
			$this->session->set_flashdata('message', lang('hd_lang.access_denied'));
			redirect('');
		}
		//$this->applib->set_locale();

	}
	function index()
	{
		redirect();
	}

	function update()
	{
		$request = \Config\Services::request();
		
		$helper = new custom_name_helper();
		 
		$app = new App();

		if ($request->getPost()) {
			
			Applib::is_demo();

			$validation = \Config\Services::validation();

			// Set error delimiters
			//$validation->setFormatter('text');

			$rules = [
				'email' => 'required|valid_email',
				'company' => 'required',
				'fullname' => 'required'
			];

			$validation->setRules($rules);

			if (!$validation->run($request->getPost())) {
				// Validation failed
				session()->setFlashdata('response_status', 'error');
				session()->setFlashdata('message', lang('hd_lang.operation_failed'));
				return redirect()->to(site_url('companies/view/' . $request->getPost('company')));
			} else {
				$user_id = $request->getPost('user_id', FILTER_SANITIZE_NUMBER_INT);
				$args = [
					'fullname' => $request->getPost('fullname', FILTER_SANITIZE_STRING),
					'company' => $request->getPost('company'),
					'phone' => $request->getPost('phone'),
					'language' => $request->getPost('language'),
					'mobile' => $request->getPost('mobile'),
					'skype' => $request->getPost('skype'),
					'locale' => $request->getPost('locale'),
				];
				
				$app->client_update('hd_account_details', ['user_id' => $user_id], $args);
				date_default_timezone_set($helper->getconfig_item('timezone'));
				$user_data = [
					'email' => $request->getPost('email', FILTER_SANITIZE_EMAIL),
					'modified' => date("Y-m-d H:i:s")
				];
				$c = $app->client_update('hd_users', ['id' => $user_id], $user_data);
				$data = [
					'module' => 'contacts',
					'module_field_id' => $user_id,
					'user' => User::get_id(),
					'activity' => 'activity_contact_edited',
					'icon' => 'fa-edit',
					'value1' => $request->getPost('fullname', FILTER_SANITIZE_STRING),
					'value2' => ''
				];
			
				App::Log($data);

				session()->setFlashdata('response_status', 'success');
				session()->setFlashdata('message', lang('hd_lang.user_edited_successfully'));
				$referer = $this->request->getServer('HTTP_REFERER');
				session()->setFlashdata('success', lang('hd_lang.user_edited_successfully'));
				return redirect()->to($referer);
				//return redirect()->to('companies/view');

			}
		} else {
			//$data['id'] = $request->uri->getSegment(3, FILTER_SANITIZE_NUMBER_INT);
			$data['id'] = $this->request->uri->getSegment(3);
			//$this->load->view('modal/edit_contact', $data);
			echo view('modules/contacts/views/modal/edit_contact', $data);
		}
	} 



	function add()
	{
		if ($this->input->post()) {
			redirect('contacts');
		} else {
			$data['company'] = $this->uri->segment(3);
			$this->load->view('modal/add_client', $data);
		}
	}
	function username_check()
	{
		$username = $this->input->post('username', TRUE);
		$users = $this->db->where('username', $username)->get('users')->num_rows();
		if ($users > 0) {
			echo '<div class="alert alert-danger">Username already in use</div>';
			exit;
		} else {
			echo '<div class="alert alert-success">Awesome! Your username is available!</div>';
			exit;
		}
	}
	function email_check()
	{
		$email = $this->input->post('email', TRUE);
		$users = $this->db->where('email', $email)->get('users')->num_rows();
		if ($users > 0) {
			echo '<div class="alert alert-danger">Email already in use</div>';
			exit;
		} else {
			echo '<div class="alert alert-success">Great! The email entered is available</div>';
			exit;
		}
	}
}
/* End of file contacts.php */