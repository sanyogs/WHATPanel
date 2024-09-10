<?php
 /*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

namespace App\Modules\auth\controllers;

use App\Libraries\AppLib;
use App\Models\App;
use App\Models\User;
use App\Models\Users;
use App\ThirdParty\MX\WhatPanel;
use App\ThirdParty\MX\Modules;
use Config\Services;
use App\Libraries\Tank_auth;
use App\Controllers\BaseController;
use App\Helpers\custom_name_helper;
use App\Modules\Layouts\Libraries\Template;
use App\Helpers\app_helper;
use CodeIgniter\Language\Language;

class Auth extends WhatPanel
{	
    protected $helpers = ['form', 'url', 'security', 'language']; // Add 'language' helper

    protected $tankAuth;

    protected $login_by_username = true;
    protected $login_by_email = true;
    protected $use_username = true;
	
	protected $validation;

    public function __construct()
    {	
        error_reporting(E_ALL);
        ini_set("display_errors", "1");
        //parent::__construct();

        // Load necessary libraries, helpers, and models here
        $this->session = Services::session();
        $this->form_validation = Services::validation();
        $this->tankAuth = new Tank_auth(); // Make sure to adjust the namespace

        // Load any other dependencies you may need

        //helper(['form', 'url', 'security']); // Load helpers
        //$this->lang = Services::language();

        // Set the default language
        // $lang = config('App')->defaultLanguage;
        //$lang = $helper->getconfig_item('default_language');
        //if ($this->request && $this->request->getCookie('fo_lang')) {
        //$lang = $this->request->getCookie('fo_lang');
        //}
        // if (session()->get('lang')) {
        //$lang = session('lang');
        //}

        //$this->lang->setLocale($lang);

        // Load the tank_auth config items
        //$tankAuthConfig = config('tank_auth');

        // if ($tankAuthConfig) {
        // foreach ($tankAuthConfig as $key => $value) {
        //     $this->config->$key = $value;
        // }
        // } else {
        // Handle the case where the configuration is null
        // You might want to log an error or provide default values
        // }

        $this->appModel = new App();
        $this->userModel = new User();

        // Load the necessary modules and libraries using dependency injection
        $this->layouts = service('layouts'); // Adjust this based on your module's namespace
        $this->template = Services::renderer();
    }

    function index()
    {	
        error_reporting(E_ALL);
        ini_set("display_errors", "1");
        if ($message = $this->session->flashdata('message')) {
            $this->load->view('auth/general_message', array('message' => $message));
        } else {
            redirect('login');
        }
    }

    public function login_page()
    {
        $custom_name_helper = new custom_name_helper();

        $data['login_by_username'] = ($this->login_by_username and
            $this->use_username);
        $data['login_by_email'] = $this->login_by_email;
        $data['captcha_login'] = FALSE;
        $data['show_captcha'] = FALSE;

        //if (config_item('captcha_login') == 'TRUE') {
        //$data['show_captcha'] = TRUE;
        //if ($data['use_recaptcha']) {
        //$data['recaptcha_html'] = $this->_create_recaptcha();
        //} else {
        //$data['captcha_html'] = $this->_create_captcha();
        //}
        // print_r('$data');
        // die();
        //}
        //echo view('custom/views/sections/header');
		//print_r($data);die;
        echo view('custom/views/auth/login_form', $data);
        //echo view('custom/views/sections/footer');

    }
    /**
     * Login user on the site
     *
     * @return void
     */
    public function login()
    {
		//echo 56789;die;
        $this->tankAuth = new Tank_auth();
        if ($this->tankAuth->is_logged_in()) {
            // logged in
            if ($this->session->get('process') == true && !User::is_admin()) {
                return redirect()->to('invoices/create');
            } else {
                if(session()->get()['userdata']['role_id'] == 2){
					return redirect()->to('clients');
				} elseif(session()->get()['userdata']['role_id'] == 3){
                    return redirect()->to('staff');
                } else {
					return redirect()->to('dashboard');
				}
            }
        } elseif ($this->tankAuth->is_logged_in(FALSE)) {
            // logged in, not activated
            return redirect()->to('auth/send_again/');
        } else {
            $data['login_by_username'] = ($this->login_by_username and $this->use_username);

            $data['login_by_email'] = $this->login_by_email;

            $validationRules = [
                'login' => 'trim|required',
                'password' => 'trim|required',
                'remember' => 'integer',
            ];

            // Get login for counting attempts to login
            if (($login = $this->request->getPost('login'))) {
                // $login = $this->security->xss_clean($login);
            } else {
                $login = '';
            }

            //$data['use_recaptcha'] = (config_item('use_recaptcha') == 'TRUE') ? TRUE : FALSE;

            //if (config_item('captcha_login') == 'TRUE') {
            // $captchaRules = [];

            // if ($data['use_recaptcha']) {
            //    $captchaRules['g-recaptcha-response'] = 'trim|required|callback__check_recaptcha';
            // } elseif (config_item('use_recaptcha') == 'TRUE') {
            //    $captchaRules['captcha'] = 'trim|required|callback__check_captcha';
            // }
            //$this->validate($captchaRules);
            //}

            $data['errors'] = [];

            $validationRules = ['login' => 'trim|required', 'password' => 'trim|required'];

            if ($this->validate($validationRules)) {

                $login = $this->request->getPost('login');

                $password = $this->request->getPost('password');

                $remember = (bool) $this->request->getPost('remember');

                $loginByUsername = $data['login_by_username'];

                $loginByEmail = $data['login_by_email'];

                if ($this->tankAuth->login($login, $password, $remember, $loginByUsername, $loginByEmail)) {
                   if ($this->session->get('process') == true && !User::is_admin()) {
						return redirect()->to('invoices/create');
					} else {
						if(session()->get()['userdata']['role_id'] == 2){
							return redirect()->to('clients');
						} elseif(session()->get()['userdata']['role_id'] == 3){
							return redirect()->to('staff');
						} else {
							return redirect()->to('dashboard');
						}
					}
                } else {
                    //echo 2;
                    //die;
                    // Failed login logic
                    $errors = $this->tankAuth->get_error_message();
                    if (isset($errors['banned'])) {
                        $this->session->setFlashdata('message', lang('hd_lang.auth_message_banned') . ' ' . $errors['banned']);
                        //return redirect()->to('login_page');
                        $data['login_by_username'] = ($this->login_by_username and
                            $this->use_username);
                        $data['login_by_email'] = $this->login_by_email;
                        $data['captcha_login'] = FALSE;
                        $data['show_captcha'] = FALSE;
                        echo view('custom/views/auth/login_form', $data);
                    } elseif (isset($errors['not_activated'])) {
                        return redirect()->to('auth/send_again/');
                    }
                    //else {
                    // Handle other login errors
                    //   foreach ($errors as $k => $v) {
                    //       $data['errors'][$k] = $this->lang->line($v);
                    //    }
                    // }
                }
            }

            // echo 875;die;

            $data['captcha_login'] = FALSE;
            $data['show_captcha'] = FALSE;
            //if (config_item('captcha_login') == 'TRUE') {
            //$data['show_captcha'] = TRUE;
            //if ($data['use_recaptcha']) {
            // $data['recaptcha_html'] = $this->_create_recaptcha();
            // } else {
            //$data['captcha_html'] = $this->_create_captcha();
            //}
            //}

            // if ($this->tankAuth->is_max_login_attempts_exceeded($login)) {
            //     $data['show_captcha'] = TRUE;
            // }

            // echo 8;
            // die;

            $session = Services::session();

            $users = $session->get('userdata');

            // Successful login logic


            //$this->setTitle(lang('hd_lang.welcome_to') . ' ' . config_item('company_name'));

            // $data['ref_item'] = $this->request->getGet('r_url', TRUE) ? $this->request->getGet('r_url', TRUE) : NULL;

            // return $this->render('login', $data);
            // return 'success';   // add the dashboard route
            $data['login_by_username'] = ($this->login_by_username and $this->use_username);
            $data['login_by_email'] = $this->login_by_email;
            $data['captcha_login'] = FALSE;
            $data['show_captcha'] = FALSE;
            if ($this->session->getFlashdata('error')) {
                $data['error'] = $this->session->getFlashdata('error');
            }
            echo view('custom/views/auth/login_form', $data);
        }
    }

    /**
     * Logout user
     *
     * @return void
     */
    function logout()
    {
        $tankAuth = new Tank_auth();
        $tankAuth->logout();
        return redirect()->to('home');
    }

    /**
     * Register user on the site
     *
     * @return void
     */
    function register()
    {
        helper(['form']);

        $custom = new custom_name_helper();
        $captcha_registration = ($custom->getconfig_item('captcha_registration') == 'TRUE') ? TRUE : FALSE;
        $use_recaptcha = ($custom->getconfig_item('use_recaptcha') == 'TRUE') ? TRUE : FALSE;
        $use_username = $custom->getconfig_item('use_username');
        //print_r($use_username);die;
        $session = \Config\Services::session();
        $template = new Template();

        $request = \Config\Services::request();

        $tank_auth = new Tank_auth();

        if ($tank_auth->is_logged_in()) {
			// echo 1232;die;
            if (session()->get('process') == true) {
				// echo 2222;die;
                // redirect('invoices/create');
                // echo 123;die;
                return redirect()->to('invoices/create');
            } else {
                // redirect('dashboard');
                return redirect()->to('dashboard');
            }
        } elseif ($tank_auth->is_logged_in(FALSE)) {  
			// echo 3333;die;
			// logged in, not activated
            return redirect()->to('auth/send_again');
        } elseif ($custom->getconfig_item('allow_client_registration') == 'FALSE') {
			//echo 444;die;
			// registration is off
            session()->setFlashdata('response_status', 'error');
            session()->setFlashdata('message', lang('hd_lang.auth_message_registration_disabled'));
            return redirect()->to('login');
        } else {
			//echo 1;die;
            if ($request->getPost()) {
				// echo 2;die;
                $validationRules = [
                    'fullname' => 'trim|required',
                    'email' => 'trim|required|valid_email',
                    'password' => 'trim|required|min_length[' . $custom->getconfig_item('password_min_length') . ']|max_length[' . $custom->getconfig_item('password_max_length') . ']',
                    'confirm_password' => 'trim|required|matches[password]',
                    'address' => 'trim|required',
                    'phone' => 'trim|required',
                    'city' => 'trim|required',
                    'state' => 'trim|required',
                    'zip' => 'trim|required',
                ];

                if ($use_username) {
                    $validationRules['username'] = 'trim|required|min_length[' . $custom->getconfig_item('username_min_length') . ']|max_length[' . $custom->getconfig_item('username_max_length') . ']';
                }

                $this->validate($validationRules);


                $validationRules = [];

                if ($captcha_registration) {
                    if ($use_recaptcha) {
                        $validationRules['g-recaptcha-response'] = 'trim|required|callback__check_recaptcha';
                    } else {
                        $validationRules['captcha'] = 'trim|required|callback__check_captcha';
                    }
                }

                $this->validate($validationRules);
                $data['errors'] = array();
                $email_activation = $custom->getconfig_item('email_activation');
                $individual = $request->getPost('company_name') == '' ? 1 : 0;

                    if (
                        !is_null(
                            $data = $tank_auth->create_user(
                                $request->getPost('username'),
                                $request->getPost('email'),
                                $request->getPost('password'),
                                $request->getPost('fullname'),
                                '-',
                                '2',
                                $request->getPost('phone'),
                                $email_activation,
                                $individual == 1 ? $request->getPost('fullname') : $request->getPost('company_name'),
                                $individual,
                                $request->getPost('address'),
                                $request->getPost('city'),
                                $request->getPost('state'),
                                $request->getPost('zip'),
                                $request->getPost('country'),
                                ''
                            )
                        )
                    ) {  // success

                        $data['site_name'] = $custom->getconfig_item('company_name');

                        if ($email_activation) {                                    // send "activate" email
                            $data['activation_period'] = $custom->getconfig_item('email_activation_expire') / 3600;

                            $this->_send_email('activate', $data['email'], $data);

                            unset($data['password']); // Clear password (just for any case)

                            $session->setFlashdata('message', lang('hd_lang.auth_message_registration_completed_1'));
                            return redirect()->to('login');
                        } else {
                            if ($custom->getconfig_item('email_account_details') == 'TRUE') {   // send "welcome" email

                                $this->_send_email('welcome', $data['email'], $data);
                            }
                            unset($data['password']); // Clear password (just for any case)
                            $session->setFlashdata('message', lang('hd_lang.auth_message_registration_completed_2'));
                            return redirect()->to('login');
                            //$this->_show_message($this->lang->line('auth_message_registration_completed_2').' '.anchor('/auth/login/', 'Login'));
                        }
                    } else {
                        $errors = $tank_auth->get_error_message();
                        foreach ($errors as $k => $v)
                            $data['errors'][$k] = $v;
                    }

                if ($captcha_registration) {
                    if ($use_recaptcha) {
                        // $data['recaptcha_html'] = $this->_create_recaptcha();
                    } else {
                        $data['captcha_html'] = $this->_create_captcha();
                    }
                }

               // return redirect()->to('login');
            } else {
				//echo 9;die;
                $data['use_username'] = $use_username;
                $data['captcha_registration'] = $captcha_registration;
                $data['use_recaptcha'] = $use_recaptcha;

                $template->title('Register - ' . $custom->getconfig_item('company_name'));
                //$this->template->set_layout('main')->build('auth/register_form', isset($data) ? $data : NULL);
                // echo 1312;die;
                echo view($custom->getconfig_item('active_theme') . '/views/auth/register_form', $data);
            }
        }
    }

    /**
     * Register user as admin on the site
     *
     * @return void
     */
    function register_user()
    {	
        $custom = new custom_name_helper();

        $use_username = $custom->getconfig_item('use_username');

        $session = \Config\Services::session();

        $request = \Config\Services::request();

        $tank_auth = new Tank_auth();

        $data['errors'] = array();

        $email_activation = $custom->getconfig_item('email_activation');
		
        $validation = \Config\Services::validation();

        // Set validation rules
        $validation->setRule('username', 'Username', 'trim|required');
        $validation->setRule('email', lang('hd_lang.email'), 'trim|required|valid_email');
        $validation->setRule('password', 'Password', 'trim');
        $validation->setRule('confirm_password', lang('hd_lang.confirm_password'), 'trim|required|matches[password]');

        $email_contact = ($request->getPost('email_contact')) ? TRUE : FALSE;

        if ($validation->withRequest($this->request)->run()) {        // validation ok

            if (
                !is_null(
                    $data = $tank_auth->admin_create_user(
                        $request->getPost('username'),
                        $request->getPost('email'),
                        $request->getPost('password'),
                        $request->getPost('fullname'),
                        $request->getPost('company'),
                        $request->getPost('role'),
                        $request->getPost('phone'),
                        $email_activation
                    )
                )
            ) {
                $data['site_name'] = $custom->getconfig_item('company_name');
				
                if ($email_activation) {                                    // send "activate" email
					echo 12;die;
                    $data['activation_period'] = $custom->getconfig_item('email_activation_expire') / 3600;

                    $this->_send_email('activate', $data['email'], $data);

                    unset($data['password']); // Clear password (just for any case)
                    $session->setFlashdata('response_status', 'success');
                    $session->setFlashdata('message', lang('hd_lang.client_registered_successfully_activate'));
                    redirect()->to('account');
                } else {
                    if ($custom->getconfig_item('email_account_details') == 'TRUE' && $email_contact) {    // send "welcome" email
						// echo 14;die;
                        $this->_send_email('welcome', $data['email'], $data);
                    }

                    unset($data['password']); // Clear password (just for any case)
                    $session->setFlashdata('response_status', 'success');
                    $session->setFlashdata('message', lang('hd_lang.user_added_successfully'));
                    redirect()->to('account');
                }
            } else {
                $errors = $tank_auth->get_error_message();

                foreach ($errors as $k => $v)
               	//$data['errors'][$k] = $this->lang->line($v);
                $session->setFlashdata('response_status', 'error');
                $session->setFlashdata('message', lang('hd_lang.client_registration_failed'));
                $session->setFlashdata('form_errors', 'Error');
                redirect()->to('account');
            }
        } else {
            $session->setFlashdata('response_status', 'error');
            $session->setFlashdata('message', lang('hd_lang.client_registration_failed'));
            $session->setFlashdata('form_errors', validation_errors('<span class="text-danger">', '</span><br>'));
            redirect()->to('account');
        }
        //redirect()->to($request->getPost('r_url'));
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



    /**
     * Send activation email again, to the same or new email address
     *
     * @return void
     */
    function send_again()
    {
        $custom = new custom_name_helper();
        if (!$this->tank_auth->is_logged_in(FALSE)) {                            // not logged in or activated
            redirect('auth/login/');
        } else {
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');

            $data['errors'] = array();

            if ($this->form_validation->run()) {                                // validation ok
                if (
                    !is_null(
                        $data = $this->tank_auth->change_email(
                            $this->form_validation->set_value('email')
                        )
                    )
                ) {            // success

                    $data['site_name'] = $custom->getconfig_item('company_name');
                    $data['activation_period'] = config_item('email_activation_expire') / 3600;

                    $this->_send_email('activate', $data['email'], $data);
                    $session->setFlashdata('message', sprintf($this->lang->line('auth_message_activation_email_sent'), $data['email']));
                    redirect('/auth/login');
                    //$this->_show_message(sprintf($this->lang->line('auth_message_activation_email_sent'), $data['email']));

                } else {
                    $errors = $this->tank_auth->get_error_message();
                    foreach ($errors as $k => $v)
                        $data['errors'][$k] = $this->lang->line($v);
                }
            }

            $this->template->title('Send Password - ' . config_item('company_name'));
            $this->template->set_layout('main')->build('auth/send_again_form', isset($data) ? $data : NULL);
        }
    }

    /**
     * Activate user account.
     * User is verified by user_id and authentication code in the URL.
     * Can be called by clicking on link in mail.
     *
     * @return void
     */
    function activate()
    {
        $user_id = $this->uri->segment(3);
        $new_email_key = $this->uri->segment(4);

        // Activate user
        if ($this->tank_auth->activate_user($user_id, $new_email_key)) {        // success
            $this->tank_auth->logout();
            //$this->_show_message($this->lang->line('auth_message_activation_completed').' '.anchor('/auth/login/', 'Login'));
            $session->setFlashdata('message', $this->lang->line('auth_message_activation_completed'));
            redirect('/auth/login');
        } else {
            // fail
            $session->setFlashdata('message', $this->lang->line('auth_message_activation_failed'));
            redirect('/auth/login');
            //$this->_show_message($this->lang->line('auth_message_activation_failed'));
        }
    }

    /**
     * Generate reset code (to change password) and send it to user
     *
     * @return void
     */
    function forgot_password()
    {      
        $this->validation = \Config\Services::validation();
        $tank_auth = new Tank_auth();
        $lang = new Language('');
        $helper = new custom_name_helper();
        $request = \Config\Services::request();
        $template = new Template();
        if ($tank_auth->is_logged_in()) {                                   // logged in
            redirect('');
        } elseif ($tank_auth->is_logged_in(FALSE)) {                       // logged in, not activated
            redirect('/auth/send_again/');
        } else { 
            $rules = ['login' => 'required|min_length[5]|max_length[12]', 
                      'Email or Username' => 'required|valid_email'];

            $data['errors'] = array();
            if ($this->validate($rules)) {                        // validation ok
                if (
                    !is_null(
                        $data =  $tank_auth->forgot_password(
                            // Inside your controller method where you are rendering the form
                        $data['login'] = $request->getPost('login')
                        )
                    )
                ) {
                    $data['site_name'] = $helper->getconfig_item('company_name');

                    // Send email with password activation link
                    $this->_send_email('forgot_password', $data['email'], $data);
                    session()->setFlashdata('message', $lang->line('auth_message_new_password_sent'));
                    //$this->_show_message($this->lang->line('auth_message_new_password_sent'));
                    redirect('login');
                } else {
                    $errors =  $tank_auth->get_error_message();
                    foreach ($errors as $k => $v)
                        $data['errors'][$k] = $lang->line($v);
                }
            }
            $template->title('Forgot Password - ' . $helper->getconfig_item('company_name'));
            // $this->template->set_layout('main')->build('auth/forgot_password_form', isset($data) ? $data : NULL);
            echo view($helper->getconfig_item('active_theme') .'/auth/forgot_password_form', isset($data) ? $data : NULL);

        }
    }
    /**
     * Replace user password (forgotten) with a new one (set by user).
     * User is verified by user_id and authentication code in the URL.
     * Can be called by clicking on link in mail.
     *
     * @return void
     */
    function reset_password()
    {
        $user_id = $this->uri->segment(3);
        $new_pass_key = $this->uri->segment(4);

        $this->form_validation->set_rules('new_password', 'New Password', 'trim|required|min_length[' . config_item('password_min_length') . ']|max_length[' . config_item('password_max_length') . ']');
        $this->form_validation->set_rules('confirm_new_password', 'Confirm new Password', 'trim|required|matches[new_password]');

        $data['errors'] = array();

        if ($this->form_validation->run()) {                                // validation ok
            if (
                !is_null(
                    $data = $this->tank_auth->reset_password(
                        $user_id,
                        $new_pass_key,
                        $this->form_validation->set_value('new_password')
                    )
                )
            ) {    // success

                $data['site_name'] = config_item('company_name');

                // Send email with new password
                $this->_send_email('reset_password', $data['email'], $data);
                $session->setFlashdata('message', $this->lang->line('auth_message_new_password_activated'));
                redirect('login');
            } else {
                // fail
                $session->setFlashdata('message', $this->lang->line('auth_message_new_password_failed'));
                redirect('login');
            }
        } else {
            // Try to activate user by password key (if not activated yet)

            if (config_item('email_activation')) {
                $this->tank_auth->activate_user($user_id, $new_pass_key, FALSE);
            }

            if (!$this->tank_auth->can_reset_password($user_id, $new_pass_key)) {
                $session->setFlashdata('message', $this->lang->line('auth_message_new_password_failed'));
                redirect('login');
            }
        }

        $this->template->title('Forgot Password - ' . config_item('company_name'));
        $this->template->set_layout('main')->build('auth/reset_password_form', isset($data) ? $data : NULL);
    }

    /**
     * Change user password
     *
     * @return void
     */
    function change_password()
    {
        $request = \Config\Services::request();

        $session = \Config\Services::session();

        $custom_helper = new custom_name_helper();

        $usersModel = new Users();

        $tank_auth = new Tank_auth();

        if (!$tank_auth->is_logged_in()) {                                // not logged in or not activated
            return redirect()->to('login');
        } else {

            AppLib::is_demo();

            $validation = \Config\Services::validation();

            // echo "<pre>";print_r($request->getPost());die;

            $validation->setRules([
                'old_password' => 'required',
                'new_password' => 'required'
            ]);
            // $validation->setRules(['confirm_new_password', 'trim|required|matches[new_password]']);

            $data['errors'] = array();

            if ($validation->run($request->getPost()) == TRUE) {                                // validation ok
                if (
                    $usersModel->change_password(
                        $request->getPost('old_password'),
                        $request->getPost('new_password')
                    )
                ) {    // success

                    $session->setFlashdata('response_status', 'success');
                    $session->setFlashdata('message', lang('hd_lang.auth_message_password_changed'));
                    return redirect()->to('profile/settings');
                    //$this->_show_message($this->lang->line('auth_message_password_changed'));

                } else {                                                        // fail
                    // $errors = $tank_auth->get_error_message();
                    // foreach ($errors as $k => $v)
                    //     $data['errors'][$k] = $this->lang->line($v);
                }
            }

            else{
                $session->setFlashdata('response_status', 'error');
                $session->setFlashdata('message', lang('hd_lang.password_or_field_error'));
                return redirect()->to('profile/settings');
            }

        }
    }

    /**
     * Change user email
     *
     * @return void
     */
    public function change_email()
    {
        $request = Services::request();
        $session = Services::session();
        $custom_helper = new custom_name_helper(); // Assuming you have a CustomNameHelper class
        $usersModel = new Users(); // Assuming you have a Users model
        $tankAuth = new Tank_auth(); // Assuming you have a TankAuth model
        $validation = Services::validation();

        if (!$tankAuth->is_logged_in()) { // not logged in or not activated
            return redirect()->to(base_url('login'));
        } else {
            // Assuming Applib::is_demo() is a static method
            Applib::is_demo(); 

            $validation = Services::validation();

            $rules = [
            'password' => 'required|xss_clean',
            'email' => 'required|xss_clean|valid_email'
        	];
			
			$validation->setRules($rules);
			
            //if ($validation->run($rules) === TRUE) { // validation ok
                $email = $request->getPost('email');
                $password = $request->getPost('password');

                if (!is_null($data = $tankAuth->set_new_email($email, $password))) { // success
                    $data['site_name'] = $custom_helper->getconfig_item('company_name'); // Assuming you have set companyName in Config\App.php
					//print_r($email);die;
                    // Send email with new email address and its activation link
                    $this->_send_email('change_email', $email, $data); // Assuming _send_email is defined in the same controller

                    session()->setFlashdata('response_status', 'success');
                    session()->setFlashdata('message', sprintf(lang('auth_message_new_email_sent'), $data['new_email']));
                    return redirect()->to($request->getPost('r_url'));
                } else {
                    $errors = $tankAuth->get_error_message();
                    foreach ($errors as $k => $v) {
                        $data['errors'][$k] = lang($v); // Assuming lang() function is available for language translation
                    }
					return redirect()->to(base_url('profile/settings'));
                }
           // } else {
              //  session()->setFlashdata('response_status', 'error');
                //session()->setFlashdata('message', lang('hd_lang.password_or_email_error'));
                //return redirect()->to(base_url('profile/settings'));
            //}
        }
    }

    /**
     * Change username
     *
     * @return void
     */
    function change_username()
    {	
		$db = \Config\Database::connect();
        if (!$this->tank_auth->is_logged_in()) { // not logged in or not activated
            redirect('login');
        } else {

            Applib::is_demo();

            $this->form_validation->set_rules('username', 'Username', 'trim|required');

            $data['errors'] = array();

            if ($this->form_validation->run($this)) {                                // validation ok
				$data = [
					'username' => $request->getPost('username', FILTER_SANITIZE_STRING)
				];

				$db->table('hd_users')->where('username', $this->tank_auth->get_username())->update($data);
                session()->setFlashdata('response_status', 'success');
                session()->setFlashdata('message', lang('hd_lang.username_changed_successfully'));
                redirect($request->getPost('r_url'));
            }
            session()->setFlashdata('response_status', 'error');
            session()->setFlashdata('message', lang('hd_lang.username_not_available'));
            redirect('profile/settings');
        }
    }

    /**
     * Replace user email with a new one.
     * User is verified by user_id and authentication code in the URL.
     * Can be called by clicking on link in mail.
     *
     * @return void
     */
    function reset_email()
    {
        $user_id = $this->uri->segment(3);
        $new_email_key = $this->uri->segment(4);

        // Reset email
        if ($this->tank_auth->activate_new_email($user_id, $new_email_key)) {    // success
            $this->tank_auth->logout();
            $session->setFlashdata('message', $this->lang->line('auth_message_new_email_activated'));
            redirect('login');
        } else {                                                                // fail
            $session->setFlashdata('message', $this->lang->line('auth_message_new_email_failed'));
            redirect('login');
        }
    }

    /**
     * Delete user from the site (only when user is logged in)
     *
     * @return void
     */
    function unregister()
    {
        if (!$this->tank_auth->is_logged_in()) {                                // not logged in or not activated
            redirect('/auth/login/');
        } else {
            $this->form_validation->set_rules('password', 'Password', 'trim|required');

            $data['errors'] = array();

            if ($this->form_validation->run()) {                                // validation ok
                if (
                    $this->tank_auth->delete_user(
                        $this->form_validation->set_value('password')
                    )
                ) {        // success
                    $this->_show_message($this->lang->line('auth_message_unregistered'));
                } else {                                                        // fail
                    $errors = $this->tank_auth->get_error_message();
                    foreach ($errors as $k => $v)
                        $data['errors'][$k] = $this->lang->line($v);
                }
            }
            $this->load->view('auth/unregister_form', $data);
        }
    }

    /**
     * Show info message
     *
     * @param	string
     * @return	void
     */
    function _show_message($message)
    {
        $session->setFlashdata('message', $message);
        redirect('/auth/');
    }

    /**
     * Send email message of given type (activate, forgot_password, etc.)
     *
     * @param	string
     * @param	string
     * @param	array
     * @return	void
     */
    function _send_email($type, $email, &$data)
    {
        switch ($type) {
            case 'activate':
                return $this->_activate_email($email, $data);
                break;
            case 'welcome':
                return $this->_welcome_email($email, $data);
                break;
            case 'forgot_password':
                return $this->_email_forgot_password($email, $data);
                break;
            case 'reset_password':
                return $this->_email_reset_password($email, $data);
                break;
            case 'change_email':
                return $this->_email_change_email($email, $data);
                break;
        }
    }

    function _welcome_email($email, $data)
    {

        $message = App::email_template('registration', 'template_body');
        $subject = App::email_template('registration', 'subject');
        $signature = App::email_template('email_signature', 'template_body');

        $app_helper = new app_helper();

        $custom_helper = new custom_name_helper();

        $logo_link = $app_helper->create_email_logo();

        $logo = str_replace("{INVOICE_LOGO}", $logo_link, $message);

        $site_url = str_replace("{SITE_URL}", base_url() . 'login', $logo);
        $username = str_replace("{USERNAME}", $data['username'], $site_url);
        $user_email = str_replace("{EMAIL}", $data['email'], $username);
        $user_password = str_replace("{PASSWORD}", $data['password'], $user_email);
        $EmailSignature = str_replace("{SIGNATURE}", $signature, $user_password);
        $message = str_replace("{SITE_NAME}", $custom_helper->getconfig_item('company_name'), $EmailSignature);

        $params['recipient'] = $email;

        $params['subject'] = '[ ' . $custom_helper->getconfig_item('company_name') . ' ]' . ' ' . $subject;
        $params['message'] = $message;

        $params['attached_file'] = '';

        Modules::run('fomailer/send_email', $params);
    }


    function _email_change_email($email, $data)
    {	
		$app_helper = new app_helper();
		$custom = new custom_name_helper();
        $message = App::email_template('change_email', 'template_body');
        $subject = App::email_template('change_email', 'subject');
        $signature = App::email_template('email_signature', 'template_body');

        $logo_link = $app_helper->create_email_logo();

        $logo = str_replace("{INVOICE_LOGO}", $logo_link, $message);

        $email_key = str_replace("{NEW_EMAIL_KEY_URL}", base_url() . 'auth/reset_email/' . $data['user_id'] . '/' . $data['new_email_key'], $logo);
        $new_email = str_replace("{NEW_EMAIL}", $data['new_email'], $email_key);
        $site_url = str_replace("{SITE_URL}", base_url(), $new_email);
        $EmailSignature = str_replace("{SIGNATURE}", $signature, $site_url);
        $message = str_replace("{SITE_NAME}", $custom->getconfig_item('company_name'), $EmailSignature);

        $params['recipient'] = $email;

        $params['subject'] = '[ ' . $custom->getconfig_item('company_name') . ' ]' . ' ' . $subject;
        $params['message'] = $message;

        $params['attached_file'] = '';

        modules::run('fomailer/send_email', $params);
    }

    function _email_reset_password($email, $data)
    {

        $message = App::email_template('reset_password', 'template_body');
        $subject = App::email_template('reset_password', 'subject');
        $signature = App::email_template('email_signature', 'template_body');

        $logo_link = create_email_logo();

        $logo = str_replace("{INVOICE_LOGO}", $logo_link, $message);

        $username = str_replace("{USERNAME}", $data['username'], $logo);
        $user_email = str_replace("{EMAIL}", $data['email'], $username);
        $user_password = str_replace("{NEW_PASSWORD}", $data['new_password'], $user_email);
        $EmailSignature = str_replace("{SIGNATURE}", $signature, $user_password);
        $message = str_replace("{SITE_NAME}", config_item('company_name'), $EmailSignature);

        $params['recipient'] = $email;

        $params['subject'] = '[ ' . config_item('company_name') . ' ]' . $subject;
        $params['message'] = $message;

        $params['attached_file'] = '';

        modules::run('fomailer/send_email', $params);
    }

    function _email_forgot_password($email, $data)
    {

        $message = App::email_template('forgot_password', 'template_body');
        $subject = App::email_template('forgot_password', 'subject');
        $signature = App::email_template('email_signature', 'template_body');

        $logo_link = create_email_logo();

        $logo = str_replace("{INVOICE_LOGO}", $logo_link, $message);

        $site_url = str_replace("{SITE_URL}", base_url() . 'login', $logo);
        $key_url = str_replace("{PASS_KEY_URL}", base_url() . 'auth/reset_password/' . $data['user_id'] . '/' . $data['new_pass_key'], $site_url);
        $EmailSignature = str_replace("{SIGNATURE}", $signature, $key_url);
        $message = str_replace("{SITE_NAME}", config_item('company_name'), $EmailSignature);

        $params['recipient'] = $email;

        $params['subject'] = '[ ' . config_item('company_name') . ' ] ' . $subject;
        $params['message'] = $message;

        $params['attached_file'] = '';

        Modules::run('fomailer/send_email', $params);
    }

    function _activate_email($email, $data)
    {

        $message = App::email_template('activate_account', 'template_body');
        $subject = App::email_template('activate_account', 'subject');
        $signature = App::email_template('email_signature', 'template_body');

        $logo_link = create_email_logo();

        $logo = str_replace("{INVOICE_LOGO}", $logo_link, $message);

        $activate_url = str_replace("{ACTIVATE_URL}", site_url('/auth/activate/' . $data['user_id'] . '/' . $data['new_email_key']), $logo);
        $activate_period = str_replace("{ACTIVATION_PERIOD}", $data['activation_period'], $activate_url);
        $username = str_replace("{USERNAME}", $data['username'], $activate_period);
        $user_email = str_replace("{EMAIL}", $data['email'], $username);
        $user_password = str_replace("{PASSWORD}", $data['password'], $user_email);
        $EmailSignature = str_replace("{SIGNATURE}", $signature, $user_password);
        $message = str_replace("{SITE_NAME}", config_item('company_name'), $EmailSignature);

        $params['recipient'] = $email;
        $params['subject'] = '[ ' . config_item('company_name') . ' ]' . ' ' . $subject;
        $params['message'] = $message;
        $params['attached_file'] = '';

        modules::run('fomailer/send_email', $params);
    }


    /**
     * Create CAPTCHA image to verify user as a human
     *
     * @return	string
     */
    function _create_captcha()
    {
        $this->load->helper('captcha');

        $cap = create_captcha(
            array(
                'img_path' => './' . config_item('captcha_path'),
                'img_url' => base_url() . config_item('captcha_path'),
                'font_path' => './' . config_item('captcha_fonts_path'),
                'font_size' => config_item('captcha_font_size'),
                'img_width' => config_item('captcha_width'),
                'img_height' => config_item('captcha_height'),
                'show_grid' => config_item('captcha_grid'),
                'expiration' => config_item('captcha_expire'),
            )
        );

        // Save captcha params in database
        $data = array(
            'captcha_time' => time(),
            'ip_address' => $this->input->ip_address(),
            'word' => $cap['word']
        );
        $query = $this->db->insert_string('hd_captcha', $data);
        $this->db->query($query);

        return $cap['image'];
    }

    /**
     * Callback function. Check if CAPTCHA test is passed.
     *
     * @param	string
     * @return	bool
     */
    function _check_captcha()
    {
        // First, delete old captchas
        $expiration = time() - config_item('captcha_expire');
        $this->db->query("DELETE FROM hd_captcha WHERE captcha_time < " . $expiration);

        // Then see if a captcha exists:
        $sql = "SELECT COUNT(*) AS count FROM hd_captcha WHERE word = ? AND ip_address = ? AND captcha_time > ?";
        $binds = array($_POST['captcha'], $this->input->ip_address(), $expiration);
        $query = $this->db->query($sql, $binds);
        $row = $query->row();

        if ($row->count == 0) {

            $this->form_validation->set_message('_check_captcha', lang('hd_lang.auth_incorrect_captcha'));
            return FALSE;
        } else {
            return TRUE;
        }
    }

    /**
     * Create reCAPTCHA JS and non-JS HTML to verify user as a human
     *
     * @return	string
     */
    function _create_recaptcha()
    {
        $this->load->helper('recaptcha');

        // Add custom theme so we can get only image
        $options = "<script>var RecaptchaOptions = {theme: 'custom', custom_theme_widget: 'recaptcha_widget'};</script>\n";

        // Get reCAPTCHA JS and non-JS HTML
        $html = recaptcha_get_html(config_item('recaptcha_public_key'));

        return $options . $html;
    }

    /**
     * Callback function. Check if reCAPTCHA test is passed.
     *
     * @return	bool
     */
    function _check_recaptcha()
    {
        // Catch the user's answer
        $captcha_answer = $request->getPost('g-recaptcha-response');
        // Verify user's answer
        $response = $this->recaptcha->verifyResponse($captcha_answer);
        // Processing ...
        if ($response['success']) {
            return TRUE;
        } else {
            // Something goes wrong
            $this->form_validation->set_message('_check_recaptcha', $this->lang->line('auth_incorrect_captcha'));
            return FALSE;
        }
    }

    protected function render($view, $data = [])
    {
        $data['content'] = view($view, $data);
        return view('layouts/default', $data);
    }

    protected function setTitle($title)
    {
        $this->data['title'] = $title;
    }
}

/* End of file auth.php */
/* Location: ./application/controllers/auth.php */