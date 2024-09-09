<?php

namespace App\Modules\tickets\controllers;

use App\Libraries\AppLib;
use App\Models\App;
use App\Models\Ticket;
use App\Models\User;
use App\ThirdParty\MX\WhatPanel;
use App\ThirdParty\MX\Modules;
use Config\Services;
use CodeIgniter\Files\File;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\Response;
use App\Modules\Layouts\Libraries\Template;
use App\Helpers\AuthHelper;
use App\Helpers\custom_name_helper;
use CodeIgniter\Validation\Rules;
use App\Helpers\app_helper;


class Tickets extends WhatPanel
{

	function __construct()
	{
		// parent::__construct();
		// User::logged_in();
		$template = new Template();
		// $this->load->module('layouts');
		// $this->load->library(array('template', 'form_validation'));
		// $this->template->title(lang('hd_lang.tickets') . ' - ' . config_item('company_name'));

		// $lang = config_item('default_language');
		// if (isset($_COOKIE['fo_lang'])) {
		// 	$lang = $_COOKIE['fo_lang'];
		// }
		// if ($this->session->userdata('lang')) {
		// 	$lang = $this->session->userdata('lang');
		// }
		// $this->lang->load('hd', $lang);

		// $this->load->model(array('Ticket', 'App'));

		// App::module_access('menu_tickets');

		// $archive = FALSE;
		// if (isset($_GET['view'])) {
		// 	if ($_GET['view'] == 'archive') {
		// 		$archive = TRUE;
		// 	}
		// }

		// $this->filter_by = $this->_filter_by();
	}

	function index($view = null)
	{
		$request = \Config\Services::request();
		
		$ticket = new Ticket();
		error_reporting(E_ALL);
        ini_set("display_errors", "1");
		$archive = FALSE;
		if (isset($view)) {
			if ($view == 'archive') {
				$archive = TRUE;
			}
		}
		$data = array(
			'page' => lang('hd_lang.tickets'),
			'datatables' => TRUE,
			'archive' => $archive,
			'tickets' => $this->_ticket_list($archive, $view),
			'filter_by' => $this->_filter_by($view)
		);
		
		// Pagination Configuration
		$page = $request->getGet('page') ? $request->getGet('page') : 1;

        $perPage = $request->getGet('recordsPerPage', FILTER_SANITIZE_NUMBER_INT) ? $request->getGet('recordsPerPage', FILTER_SANITIZE_NUMBER_INT) : 10;

		// Search Filter
		$search = $request->getGet('search');
		$data['search'] = $search;

        $query = $ticket->listItems([], $search, $perPage, $page);

        // Get items for the current page
		$data['servers'] = array_map(function($item) {
			return (object) $item;
		}, $query['items']);

        $data['pager'] = $query['pager'];

		$data['message'] = $query['message'];

		$data['perPage'] = $perPage;

		// echo "<pre>";print_r($data);die;
		// $this->template
		// 	->set_layout('users')
		// 	->build('tickets', isset($data) ? $data : NULL);
		return view('modules/tickets/tickets', $data);
	}

	function _filter_by($archive = null)
	{

		$filter = isset($archive) ? $archive : '';

		return $filter;
	}


	function _ticket_list($archive = NULL, $view = NULL)
	{

		if (User::is_admin()) {
			return $this->_admin_tickets($archive, $this->_filter_by($view));
		} elseif (User::is_staff()) {
			return $this->_staff_tickets($archive, $this->_filter_by($view));
		} else {
			return $this->_client_tickets($archive, $this->_filter_by($view));
		}
	}

	function view($id = NULL)
	{
		$user = new User();

		// if (!$user->can_view_ticket(User::get_id(), $id)) {
		// 	App::access_denied('tickets');
		// }

		$data['page'] = lang('hd_lang.tickets');
		$data['editor'] = TRUE;
		$data['id'] = $id;
		$data['tickets'] = $this->_ticket_list(); // GET a list of the Tickets
		// $this->template
		// 	->set_layout('users')
		// 	->build('view', isset($data) ? $data : NULL);
		return view('modules/tickets/view', $data);
	}


	function add($dept_id = null)
	{
		$request = \Config\Services::request();

		$session = \Config\Services::session(); 
		
		$custom = new custom_name_helper();
        // Connect to the database  
        $db = \Config\Database::connect();

		if ($request->getPost()) {
			if (isset($_POST['dept'])) {
				AppLib::go_to('tickets/add/' . $_POST['dept'], 'success', '');
			}

			$validation = \Config\Services::validation();

			// Set validation rules
			$validation->setRules([
				'ticket_code' => 'required',
				'subject'     => 'required',
				'body'        => 'required',
			]);

			if (!$validation->withRequest($this->request)->run()) {
				AppLib::make_flashdata(
					array(
						'response_status' => 'error',
						'message' => lang('hd_lang.operation_failed'),
						'form_error' => $validation->getErrors()
					)
				);

				return redirect()->back();
			} else {
				date_default_timezone_set($custom->getconfig_item('timezone'));
				$attachment = '';
				if ($_FILES['ticketfiles']['tmp_name'][0]) {
					$attachment = $this->_upload_attachment($_POST);
				}

				// check additional fields
				$additional_fields = array();

				$additional_data = $db->table('hd_fields')->where(array('deptid' => $_POST['department']))
					->get()
					->getResult();

				if (is_array($additional_data))
					foreach ($additional_data as $additional) {
						// We create these vales as an array
						$name = $additional['uniqid'];
						$additional_fields[$name] = $request->getPost($name);
					}
				$subject = $request->getPost('subject');
				$code = $request->getPost('ticket_code');

				$_POST['real_subject'] = $subject;

				$_POST['subject'] = '[' . $code . '] : ' . $subject;

				$insert = array(
					'subject' => $_POST['subject'],
					'ticket_code' => $code,
					'department' => $_POST['department'],
					'priority' => $_POST['priority'],
					'body' => $request->getPost('body'),
					'status' => 'open',
					'created' => date("Y-m-d H:i:s", time())
				);

				if (is_array($additional_fields)) {
					$insert['additional'] = json_encode($additional_fields);
				}

				if (isset($attachment)) {
					$insert['attachment'] = $attachment;
				}
				if (!User::is_admin()) {
					$insert['reporter'] = User::get_id();
					$_POST['reporter'] = User::get_id();
				} else {
					$insert['reporter'] = $_POST['reporter'];
				}

				if ($ticket_id = Ticket::save_data('hd_tickets', $insert)) {

					// Send email to Staff
					//$this->_send_email_to_staff($ticket_id);
					// Send email to Client
					//$this->_send_email_to_client($ticket_id);


					$data = array(
						'module' => 'tickets',
						'module_field_id' => $ticket_id,
						'user' => User::get_id(),
						'activity' => 'activity_ticket_created',
						'icon' => 'fa-ticket',
						'value1' => $subject,
						'value2' => ''
					);
					App::Log($data);

					// Applib::go_to('tickets/view/' . $ticket_id, 'success', lang('hd_lang.ticket_created_successfully'));
					return redirect()->to('tickets/view/' . $ticket_id);
				}


			}
		} else {

			$data = array(
				'page' => lang('hd_lang.tickets'),
				'datepicker' => TRUE,
				'form' => TRUE,
				'editor' => TRUE,
				'tickets' => $this->_ticket_list()
			);

			// $this->template
			// 	->set_layout('users')
			// 	->build('create_ticket', isset($data) ? $data : NULL);

			return view('modules/tickets/create_ticket', $data);

		}
	}


	function edit($id = NULL)
	{
		$request = \Config\Services::request();

		$session = \Config\Services::session(); 

        // Connect to the database  
        $db = \Config\Database::connect();

		if ($request->getPost()) {
			$ticket_id = $request->getPost('id');

			$validation = \Config\Services::validation();

			// $this->form_validation->set_rules('ticket_code', 'Ticket Code', 'required');
			// $this->form_validation->set_rules('subject', 'Subject', 'required');
			// $this->form_validation->set_rules('body', 'Body', 'required');

			// Set validation rules
			$validation->setRules([
				'ticket_code' => 'required',
				'subject'     => 'required',
				'body'        => 'required',
			]);

			if (!$validation->withRequest($this->request)->run()) {
				Applib::make_flashdata(
					array(
						'response_status' => 'error',
						'message' => lang('hd_lang.error_in_form'),
						'form_error' => validation_errors()
					)
				);

				redirect($_SERVER['HTTP_REFERER']);
			} else {

				if ($_FILES['ticketfiles']['tmp_name'][0]) {
					$attachment = $this->_upload_attachment($request->getPost());
				}

				if (isset($attachment)) {
					$_POST['attachment'] = $attachment;
				}

				Ticket::update_data('hd_tickets', array('id' => $ticket_id), $request->getPost());

				$data = array(
					'module' => 'tickets',
					'module_field_id' => $ticket_id,
					'user' => User::get_id(),
					'activity' => 'activity_ticket_edited',
					'icon' => 'fa-pencil',
					'value1' => $request->getPost('subject'),
					'value2' => ''
				);
				App::Log($data);
				//Applib::go_to('tickets/view/' . $ticket_id, 'success', lang('hd_lang.ticket_edited_successfully'));
				return redirect()->to('tickets/view/' . $ticket_id);

			}
		} else {
			// if (!User::can_view_ticket(User::get_id(), $id)) {
			// 	App::access_denied('tickets');
			// }
			$data = array(
				'page' => lang('hd_lang.tickets'),
				'datepicker' => TRUE,
				'form' => TRUE,
				'editor' => TRUE,
				'tickets' => $this->_ticket_list(),
				'id' => $id
			);

			// $this->template
			// 	->set_layout('users')
			// 	->build('edit_ticket', isset($data) ? $data : NULL);
			return view('modules/tickets/edit_ticket', $data);
		}
	}


	function quick_edit()
	{
		$request = \Config\Services::request();

		$session = \Config\Services::session();

		$db = \Config\Database::connect();

		if ($request->getPost()) {
			$ticket_id = $request->getPost('id');
			$data = array(
				'reporter' => $request->getPost('reporter'),
				'department' => $request->getPost('department'),
				'priority' => $request->getPost('priority'),
			);

			//Ticket::update_data('tickets', array('id' => $ticket_id), $data);
			$db->table('hd_tickets')->where('id', $ticket_id)->update($data);

			$session->setFlashdata('success', lang('hd_lang.ticket_edited_successfully'));
			return redirect()->to('tickets/view/' . $ticket_id);
			// Applib::go_to('tickets/view/' . $ticket_id, 'success', lang('hd_lang.ticket_edited_successfully'));
		}
	}


	function reply()
	{
		$cutom = new custom_name_helper();
		$request = \Config\Services::request();
		if ($request->getPost()) {
			$ticket_id = $request->getPost('ticketid');

			$validation = \Config\Services::validation();

			$validation->setRules([
				'reply' => 'required'
			]);

			if (!$validation->withRequest($this->request)->run()) {
				$_POST = '';
				//Applib::go_to('tickets/view/' . $ticket_id, 'error', lang('hd_lang.error_in_form'));
			} else {

				$attachment = '';
				if ($_FILES['ticketfiles']['tmp_name'][0]) {
					$attachment = $this->_upload_attachment($request->getPost());
				}

				$insert = array(
					'ticketid' => $_POST['ticketid'],
					'body' => $request->getPost('reply'),
					'attachment' => json_encode($attachment),
					'replierid' => User::get_id(),
				);

				if ($reply_id = Ticket::save_data('hd_ticketreplies', $insert)) {

					// if ticket is closed send re-opened email to staff/client
					if (Ticket::view_by_id($ticket_id)->status == 'closed') {
						if ($custom->getconfig_item('notify_ticket_reopened') == 'TRUE') {
							$this->_notify_ticket_reopened($ticket_id);
						}

					}

					Ticket::update_data('hd_tickets', array('id' => $ticket_id), array('status' => 'open'));

					(User::is_client())
						? $this->_notify_ticket_reply('admin', $ticket_id, $reply_id)
						: $this->_notify_ticket_reply('client', $ticket_id, $reply_id);
					// Send email to client/admins

					$data = array(
						'module' => 'tickets',
						'module_field_id' => $ticket_id,
						'user' => User::get_id(),
						'activity' => 'activity_ticket_replied',
						'icon' => 'fa-ticket',
						'value1' => Ticket::view_by_id($ticket_id)->subject,
						'value2' => ''
					);
					App::Log($data);

					return redirect()->to('tickets/view/' . $ticket_id);

					//Applib::go_to('tickets/view/' . $ticket_id, 'success', lang('hd_lang.ticket_replied_successfully'));
				}
			}
		} else {
			$this->index();

		}
	}


	function delete($id = NULL)
	{
		$request = \Config\Services::request();

		$session = \Config\Services::session(); 

        // Connect to the database  
        $db = \Config\Database::connect();

		if ($request->getPost()) {

			$ticket = $request->getPost('ticket');

			$db->table('hd_ticketreplies')->where('ticketid', $ticket)->delete(); //delete ticket replies

			//clear ticket activities
			$db->table('hd_activities')->where('module', 'tickets')->where('module_field_id', $ticket)->delete();

			//delete ticket
			$db->table('hd_tickets')->where('id', $ticket)->delete();

			//AppLib::go_to('tickets', 'success', lang('hd_lang.ticket_deleted_successfully'));
			return redirect()->to('tickets');

		} else {
			$data['ticket'] = $id;
			//$this->load->view('modal/delete_ticket', $data);
			return view('modules/tickets/modal/delete_ticket', $data);

		}
	}

	function archive($id=null, $archived=null)
	{
		//$id = $this->uri->segment(3);
		$info = Ticket::view_by_id($id);
		//$archived = $this->uri->segment(4);
		$data = array("archived_t" => $archived);
		Ticket::update_data('hd_tickets', array('id' => $id), $data);

		$data = array(
			'module' => 'tickets',
			'module_field_id' => $id,
			'user' => User::get_id(),
			'activity' => 'activity_ticket_edited',
			'icon' => 'fa-pencil',
			'value1' => $info->subject,
			'value2' => ''
		);
		App::Log($data);
		//Applib::go_to('tickets', 'success', lang('hd_lang.ticket_edited_successfully'));
		return redirect()->to('tickets');
	}

	function download_file($ticket = NULL)
	{
		$this->load->helper('download');
		$file_name = Ticket::view_by_id($ticket)->attachment;
		if (file_exists('./resource/attachments/' . $file_name)) {
			$data = file_get_contents('./resource/attachments/' . $file_name); // Read the file's contents
			force_download($file_name, $data);
		} else {
			Applib::go_to('tickets/view/' . $ticket, 'error', lang('hd_lang.operation_failed'));
		}
	}


	function status($ticket = NULL, $status = NULL)
	{	
		$custom = new custom_name_helper();
		if (isset($status)) {
			$current_status = Ticket::view_by_id($ticket)->status;

			if ($current_status == 'closed' && $status != 'closed') {
				if ($custom->getconfig_item('notify_ticket_reopened') == 'TRUE') {
					$this->_notify_ticket_reopened($ticket);
				}

			}

			$data = array('status' => $status);
			Ticket::update_data('hd_tickets', array('id' => $ticket), $data);

			if ($status == 'closed' && $current_status != 'closed') {
				// Send email to ticket reporter
				$this->_ticket_closed($ticket);
			}


			$data = array(
				'module' => 'tickets',
				'module_field_id' => $ticket,
				'user' => User::get_id(),
				'activity' => 'activity_ticket_status_changed',
				'icon' => 'fa-ticket',
				'value1' => Ticket::view_by_id($ticket)->subject,
				'value2' => ''
			);
			App::Log($data);
			// Applib::go_to('tickets/view/' . $ticket, 'success', lang('hd_lang.ticket_status_changed'));
			return redirect()->to('tickets/view/' . $ticket);

		} else {
			$this->index();
		}
	}


	function _ticket_closed($ticket)
	{	
		$custom = new custom_name_helper();
		$app_helper = new app_helper();
		if ($custom->getconfig_item('notify_ticket_closed') == 'TRUE') {
			$message = App::email_template('ticket_closed_email', 'template_body');
			$subject = App::email_template('ticket_closed_email', 'subject');
			$signature = App::email_template('email_signature', 'template_body');

			$info = Ticket::view_by_id($ticket);

			$no_of_replies = App::counter('ticketreplies', array('ticketid' => $ticket));

			$reporter_email = User::login_info($info->reporter)->email;

			$logo_link = $app_helper->create_email_logo();

			$logo = str_replace("{INVOICE_LOGO}", $logo_link, $message);

			$code = str_replace("{TICKET_CODE}", $info->ticket_code, $logo);
			$title = str_replace("{SUBJECT}", $info->subject, $code);
			$reporter = str_replace("{REPORTER_EMAIL}", $reporter_email, $title);
			$staff = str_replace("{STAFF_USERNAME}", User::displayName(User::get_id()), $reporter);
			$status = str_replace("{TICKET_STATUS}", 'Closed', $staff);
			$replies = str_replace("{NO_OF_REPLIES}", $no_of_replies, $status);
			$link = str_replace("{TICKET_LINK}", base_url() . 'tickets/view/' . $ticket, $replies);
			$EmailSignature = str_replace("{SIGNATURE}", $signature, $link);
			$message = str_replace("{SITE_NAME}", $custom->getconfig_item('company_name'), $EmailSignature);

			$subject = str_replace("[TICKET_CODE]", '[' . $info->ticket_code . ']', $subject);
			$subject = str_replace("[SUBJECT]", $info->subject, $subject);

			$data['message'] = $message;
			// $message = $this->load->view('email_template', $data, TRUE);
			$message = view('email_template', $data);

			$params['subject'] = $subject;
			$params['message'] = $message;
			$params['attached_file'] = '';
			$params['alt_email'] = 'support';

			$params['recipient'] = $reporter_email;
			Modules::run('fomailer/send_email', $params);
		}

	}

	function _notify_ticket_reply($group, $id, $reply_id)
	{
		$db = \Config\Database::connect();

		$app_helper = new app_helper();

		$custom = new custom_name_helper();
		if ($custom->getconfig_item('notify_ticket_reply') == 'TRUE') {

			$message = App::email_template('ticket_reply_email', 'template_body');
			$subject = App::email_template('ticket_reply_email', 'subject');
			$signature = App::email_template('email_signature', 'template_body');

			$info = Ticket::view_by_id($id);
			$reply = $db->table('hd_ticketreplies')->where('id', $reply_id)->get()->getRow();


			$logo_link = $app_helper->create_email_logo();

			$logo = str_replace("{INVOICE_LOGO}", $logo_link, $message);

			$code = str_replace("{TICKET_CODE}", $info->ticket_code, $logo);
			$title = str_replace("{SUBJECT}", $info->subject, $code);
			$status = str_replace("{TICKET_STATUS}", ucfirst($info->status), $title);
			$link = str_replace("{TICKET_LINK}", base_url() . 'tickets/view/' . $id, $status);
			$body = str_replace("{TICKET_REPLY}", $reply->body, $link);
			$EmailSignature = str_replace("{SIGNATURE}", $signature, $body);

			$message = str_replace("{SITE_NAME}", $custom->getconfig_item('company_name'), $EmailSignature);

			$subject = str_replace("[TICKET_CODE]", '[' . $info->ticket_code . ']' . $info->subject, $subject);
			$subject = str_replace("[SUBJECT]", $info->subject, $subject);

			$data['message'] = $message;
			$message = view('email_template', $data);

			$params['subject'] = $subject;
			$params['message'] = $message;
			$params['attached_file'] = '';
			$params['alt_email'] = 'support';



			switch ($group) {
				case 'admin':
					// Send to admins
					if (count(User::team())) {
						$staff_members = User::team();
						// Send email to staff department
						foreach ($staff_members as $key => $user) {
							$profile_info = User::profile_info($user->id);
							if ($profile_info && isset($profile_info->department)) {
								$dep = json_decode($profile_info->department, TRUE);
								if (is_array($dep) && in_array($info->department, $dep)) {
									$login_info = User::login_info($user->id);
									if ($login_info && isset($login_info->email)) {
										$email = $login_info->email;
										$params['recipient'] = $email;
										// modules::run('fomailer/send_email', $params);
									}
								}
							}
						}
					}


					return TRUE;
					break;

				default:
					$params['recipient'] = User::login_info($info->reporter)->email;
					// modules::run('fomailer/send_email', $params);

					return TRUE;
					break;
			}

		}
	}


	function _notify_ticket_reopened($ticket)
	{
		$app_helper = new app_helper();
		$custom = new custom_name_helper();
		$message = App::email_template('ticket_reopened_email', 'template_body');
		$subject = App::email_template('ticket_reopened_email', 'subject');
		$signature = App::email_template('email_signature', 'template_body');

		$info = Ticket::view_by_id($ticket);

		$logo_link = $app_helper->create_email_logo();

		$logo = str_replace("{INVOICE_LOGO}", $logo_link, $message);

		$title = str_replace("{SUBJECT}", $info->subject, $logo);
		$user = str_replace("{USER}", User::displayName(User::get_id()), $title);
		$link = str_replace("{TICKET_LINK}", base_url() . 'tickets/view/' . $ticket, $user);
		$EmailSignature = str_replace("{SIGNATURE}", $signature, $link);
		$message = str_replace("{SITE_NAME}", $custom->getconfig_item('company_name'), $EmailSignature);

		$subject = str_replace("[SUBJECT]", $info->subject, $subject);

		$data['message'] = $message;
		//$message = $this->load->view('email_template', $data, TRUE);
		$message = view('email_template', $data, []);
		$params['subject'] = $subject;
		$params['message'] = $message;
		$params['attached_file'] = '';
		$params['alt_email'] = 'support';

		if (User::is_client()) {
			// Get admins
			if (count(User::team())) {
				$staff_members = User::team();
				// Send email to staff department
				foreach ($staff_members as $key => $user) {
					$profile_info = User::profile_info($user->id);
					if ($profile_info !== null) {
						$dep = json_decode($profile_info->department, TRUE);
						if (is_array($dep) && in_array($info->department, $dep)) {
							$login_info = User::login_info($user->id);
							if ($login_info !== null) {
								$email = $login_info->email;
								$params['message'] = str_replace("{RECIPIENT}", $email, $message);
								$params['recipient'] = $email;
								modules::run('fomailer/send_email', $params);
							}
						}
					}
				}
			}
		} else {
			$email = User::login_info($info->reporter)->email;
			$params['message'] = str_replace("{RECIPIENT}", $email, $message);
			$params['recipient'] = $email;
			modules::run('fomailer/send_email', $params);
		}


	}

	function _send_email_to_staff($id)
	{	
		$custom = new custom_name_helper();
		$app_helper = new app_helper();
		if ($custom->getconfig_item('email_staff_tickets') == 'TRUE') {

			$message = App::email_template('ticket_staff_email', 'template_body');
			$subject = App::email_template('ticket_staff_email', 'subject');
			$signature = App::email_template('email_signature', 'template_body');

			$info = Ticket::view_by_id($id);

			$reporter_email = User::login_info($info->reporter)->email;

			$logo_link = $app_helper->create_email_logo();

			$logo = str_replace("{INVOICE_LOGO}", $logo_link, $message);

			$code = str_replace("{TICKET_CODE}", $info->ticket_code, $logo);
			$title = str_replace("{SUBJECT}", $info->subject, $code);
			$reporter = str_replace("{REPORTER_EMAIL}", $reporter_email, $title);
			// $UserEmail =
			$link = str_replace("{TICKET_LINK}", base_url() . 'tickets/view/' . $id, $reporter);
			$signature = str_replace("{SIGNATURE}", $signature, $link);
			$message = str_replace("{SITE_NAME}", $custom->getconfig_item('company_name'), $signature);

			$data['message'] = $message;
			// $message = $this->load->view('email_template', $data, TRUE);
			$message = view('email_template', $data);

			$subject = str_replace("[TICKET_CODE]", '[' . $info->ticket_code . ']', $subject);
			$subject = str_replace("[SUBJECT]", $info->subject, $subject);

			$params['subject'] = $subject;

			$params['attached_file'] = '';
			$params['alt_email'] = 'support';

			if (count(User::team())) {
				$staff_members = User::team();
				// Send email to staff department
				foreach ($staff_members as $key => $user) {
					$dep = json_decode(User::profile_info($user->id)->department, TRUE);
					if (is_array($dep) && in_array($info->department, $dep)) {
						$email = User::login_info($user->id)->email;
						$params['message'] = str_replace("{USER_EMAIL}", $email, $message);
						$params['recipient'] = $email;
						modules::run('fomailer/send_email', $params);
					}
				}
			}

			return TRUE;

		} else {
			return TRUE;
		}

	}

	function _send_email_to_client($id)
	{
		$custom = new custom_name_helper();
		$app_helper = new app_helper();
		$message = App::email_template('ticket_client_email', 'template_body');
		$subject = App::email_template('ticket_client_email', 'subject');
		$signature = App::email_template('email_signature', 'template_body');

		$info = Ticket::view_by_id($id);

		$email = User::login_info($info->reporter)->email;

		$logo_link = $app_helper->create_email_logo();

		$logo = str_replace("{INVOICE_LOGO}", $logo_link, $message);

		$client_email = str_replace("{CLIENT_EMAIL}", $email, $logo);
		$ticket_code = str_replace("{TICKET_CODE}", $info->ticket_code, $client_email);
		$title = str_replace("{SUBJECT}", $info->subject, $ticket_code);
		$ticket_link = str_replace("{TICKET_LINK}", base_url() . 'tickets/view/' . $id, $title);
		$EmailSignature = str_replace("{SIGNATURE}", $signature, $ticket_link);
		$message = str_replace("{SITE_NAME}", $custom->getconfig_item('company_name'), $EmailSignature);
		$data['message'] = $message;

		// $message = $this->load->view('email_template', $data, TRUE);
		$message = view('email_template', $data);

		$subject = str_replace("[TICKET_CODE]", '[' . $info->ticket_code . ']', $subject);
		$subject = str_replace("[SUBJECT]", $info->subject, $subject);

		$params['recipient'] = $email;
		$params['subject'] = $subject;
		$params['message'] = $message;
		$params['attached_file'] = '';
		$params['alt_email'] = 'support';

		modules::run('fomailer/send_email', $params);
		return TRUE;

	}

	function _upload_attachment($data)
	{
		$request = \Config\Services::request();
		$response = \Config\Services::response();

		$validation = \Config\Services::validation();
		
		$input = $validation->setRules([
        'ticketfiles.*' => 'uploaded[ticketfiles.*]|max_size[ticketfiles.*,1024]|ext_in[ticketfiles.*,jpg,jpeg,png,webp]',
    	]);

	   if (!$input) {
		   $data['validation'] = $this->validator; 
			return ['errors' => $validation->getErrors()];
	   } else {
		   $uploadedFiles = $request->getFiles();
		   $uploadedPaths = [];

		   foreach ($uploadedFiles['ticketfiles'] as $file) {
			   if ($file->isValid() && !$file->hasMoved()) {

				   $newName = $file->getClientName();

				   $uploadPath = FCPATH . 'attachments/';
				
				   $file->move($uploadPath, $newName);

				   $uploadedPaths[] = $uploadPath . $newName;
			   }
		   }

		   return $uploadedPaths;
	   }
	}


	function _admin_tickets($archive = FALSE, $filter_by = NULL)
	{
		$session = Services::session();
		// Connect to the database
		$db = \Config\Database::connect();

		$ticketModel = new Ticket();

		// echo $archive;die;

		if ($filter_by == NULL)
			return $ticketModel->getByWhere(array('archived_t !=' => 1));	

			if ($archive) return $ticketModel->getByWhere(array('archived_t' => '1'));

		switch ($filter_by) {
			case 'open':
				return $ticketModel->where(array('archived_t !=' => '1', 'status' => 'open'))->findAll();
				break;
			case 'closed':
				return $ticketModel->where(array('archived_t !=' => '1', 'status' => 'closed'))->findAll();
				break;
			case 'pending':
				return $ticketModel->where(array('archived_t !=' => '1', 'status' => 'pending'))->findAll();
				break;
			case 'resolved':
				return $ticketModel->where(array('archived_t !=' => '1', 'status' => 'resolved'))->findAll();
				break;
			default:
				return $ticketModel->where(array('archived_t !=' => '1'))->findAll();
				break;
		}

	}


	function _staff_tickets($archive = FALSE, $filter_by = NULL)
	{

		$staff_department = User::profile_info(User::get_id())->department;
		$dep = json_decode($staff_department, TRUE);

		if ($filter_by == NULL) {

			($archive) ? $this->db->where(array('archived_t' => '1'))
				: $this->db->where(array('archived_t !=' => '1'));

			if (is_array($dep)) {
				$this->db->where_in('department', $dep);
			} else {
				$this->db->where('department', $staff_department);
			}
			$output = $this->db->or_where('reporter', User::get_id())->get('tickets')->result();

			return $output;

		}

		switch ($filter_by) {
			case 'open':
				$this->db->where(array('archived_t !=' => '1', 'status' => 'open'));
				if (is_array($dep)) {
					$this->db->where_in('department', $dep);
				} else {
					$this->db->where('department', $staff_department);
				}
				return $this->db->or_where('reporter', User::get_id())->get('tickets')->result();

				break;
			case 'closed':

				$this->db->where(array('archived_t !=' => '1', 'status' => 'closed'));
				if (is_array($dep)) {
					$this->db->where_in('department', $dep);
				} else {
					$this->db->where('department', $staff_department);
				}
				return $this->db->or_where('reporter', User::get_id())->get('tickets')->result();

				break;
			case 'pending':
				$this->db->where(array('archived_t !=' => '1', 'status' => 'pending'));
				if (is_array($dep)) {
					$this->db->where_in('department', $dep);
				} else {
					$this->db->where('department', $staff_department);
				}
				return $this->db->or_where('reporter', User::get_id())->get('tickets')->result();

				break;
			case 'resolved':
				$this->db->where(array('archived_t !=' => '1', 'status' => 'resolved'));
				if (is_array($dep)) {
					$this->db->where_in('department', $dep);
				} else {
					$this->db->where('department', $staff_department);
				}
				return $this->db->or_where('reporter', User::get_id())->get('tickets')->result();

				break;

			default:
				$this->db->where(array('archived_t !=' => '1'));
				if (is_array($dep)) {
					$this->db->where_in('department', $dep);
				} else {
					$this->db->where('department', $staff_department);
				}
				return $this->db->or_where('reporter', User::get_id())->get('tickets')->result();
				break;
		}



	}



	function _client_tickets($archive = FALSE, $filter_by = NULL)
	{
		$session = Services::session();

		$db = \Config\Database::connect();

		$ticketModel = new Ticket($db);
		
		$custom = new custom_name_helper();

		if ($filter_by == NULL) {

			if ($archive) {
				return $ticketModel->where(array('reporter' => User::get_id(), 'archived_t' => '1'))->findAll();
			} else {
				return $ticketModel->where(array('reporter' => User::get_id(), 'archived_t !=' => '1'))->findAll();
			}

		}

		switch ($filter_by) {
			case 'open':
				return $ticketModel->where(array('archived_t !=' => '1', 'status' => 'open', 'reporter' => User::get_id()))->findAll();

				break;
			case 'closed':
				return $ticketModel->where(array('archived_t !=' => '1', 'status' => 'closed', 'reporter' => User::get_id()))->findAll();

				break;
			case 'pending':
				return $ticketModel->where(array('archived_t !=' => '1', 'status' => 'pending', 'reporter' => User::get_id()))->findAll();
				break;
			case 'resolved':
				return $ticketModel->where(array('archived_t !=' => '1', 'status' => 'resolved', 'reporter' => User::get_id()))->findAll();
				break;

			default:
				return $ticketModel->where(array('archived_t !=' => '1', 'reporter' => User::get_id()))->findAll();
				break;
		}
	}


}

/* End of file invoices.php */