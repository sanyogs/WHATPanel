<?php

namespace app\Modules\companies\controllers;

use App\Libraries\AppLib;
use App\Models\App;
use App\Models\Client;
use App\Models\Company;
use App\Models\Invoice;
use App\Models\User;
use App\ThirdParty\MX\WhatPanel;
use App\ThirdParty\MX\Modules;
use Exception;
use PasswordHash;
use PHPExcel_IOFactory;
use App\Helpers\whatpanel_helper;
use App\Helpers\custom_name_helper;


class Companies extends WhatPanel
{

    protected $custom_helper;

    public function __construct()
    {	
		error_reporting(E_ALL);
        ini_set("display_errors", "1");
        // parent::__construct();
        // User::logged_in();

        // $this->load->library(array('form_validation'));
        // if (!User::is_admin() && !User::is_staff()) {
        //     $this->session->set_flashdata('message', lang('hd_lang.access_denied'));
        //     redirect('');
        // }

        // foreach ($this->custom_helper->getconfig_item('tank_auth') as $key => $value) {
        //     $this->config->set_item($key, $value);
        // }

        // $this->load->helper(array('inflector'));
        // $this->applib->set_locale();

        $this->custom_helper = new custom_name_helper();
    }

    public function index()
    {
        // $this->load->module('layouts');
        // $this->load->library('template');
        // $this->template->title(lang('hd_lang.clients') . ' - ' . $this->custom_helper->getconfig_item('company_name'));
        $data['page'] = lang('hd_lang.clients');
        $data['datatables'] = true;
        $data['form'] = true;
        $data['currencies'] = App::currencies();
        $data['languages'] = App::languages();
        $data['companies'] = Client::get_all_clients();
		$request = \Config\Services::request();
		
		$companyModel = new Company();
		
		// Pagination Configuration
		$page = $request->getGet('page') ? $request->getGet('page') : 1;

        $perPage = $request->getGet('recordsPerPage', FILTER_SANITIZE_NUMBER_INT) ? $request->getGet('recordsPerPage', FILTER_SANITIZE_NUMBER_INT) : 10;

		// Search Filter
		$search = $request->getGet('search');
		$data['search'] = $search;

        $query = $companyModel->listItems([], $search, $perPage, $page);
		
        // Get items for the current page
		$data['servers'] = array_map(function($item) {
			return (object) $item;
		}, $query['items']);

        $data['pager'] = $query['pager'];

		$data['message'] = $query['message'];

		$data['perPage'] = $perPage;
		
        $data['countries'] = App::countries();
        // $this->template
        //     ->set_layout('users')
        //     ->build('companies', isset($data) ? $data : null);
        //echo "<pre>";print_r($data);die;
        return view('modules/companies/companies', $data);
    }



    public function view($company = null, $tab = null)
    {
        // $this->load->module('layouts');
        // $this->load->library('template');
        // $this->template->title(lang('hd_lang.clients') . ' - ' . $this->custom_helper->getconfig_item('company_name'));

        $session = \Config\Services::session();

        // Connect to the database  
        $db = \Config\Database::connect();

        $servers = $db->table('hd_servers')->get()->getResult();

        $data['page'] = lang('hd_lang.client');
        $data['datatables'] = true;
        $data['form'] = true;
        $data['editor'] = true;
        $data['tab'] = ($tab == '') ? 'accounts' : $tab;
        $data['company'] = $company;
		//print_r($data);die;
        return view('modules/companies/view', $data);
    }



    public function create()
    {	
		$request = \Config\Services::request();
        if ($request->getPost()) 
        {
            $custom_fields = array();
            foreach ($_POST as $key => &$value) {
                if (strpos($key, 'cust_') === 0) {
                    $custom_fields[$key] = $value;
                    unset($_POST[$key]);
                }
            }
            
            $validation = \Config\Services::validation();
            
            $helper = new whatpanel_helper();
            
           $validationRules = [
              'company_name' => 'required',
              'first_name' => 'required',
              'last_name' => 'required',
              'company_email' => 'required',
              'username' => 'required',
              'password' => 'required'
            ];
			
           $validation->setRules($validationRules);
            
            if (!$validation->withRequest($request)->run()) 
			{ 
               $_POST = '';
               $errors = validation_errors();
               AppLib::go_to('hd_companies', 'error', lang('hd_lang.error_in_form'));
               return redirect()->to('companies');
            } else {
                $username = $request->getPost('username');
                $email = $request->getPost('company_email');
                $password = $request->getPost('password');
                    unset($_POST['username']);
                    unset($_POST['password']);
                    unset($_POST['confirm_password']);
                    
                    
                    $data = array(
                        'username' => $username,
                        'password' => password_hash($password, PASSWORD_DEFAULT),
                        'email' => $email,
                        'role_id' => 2
                    );
                    $user_id = App::save_data('hd_users', $data);
                   	 
                    $_POST['primary_contact'] = $user_id;
					//echo"<pre>";print_r($_POST['primary_contact']);die;
				
                    $dataComp = array(
                        'company_name' => $request->getPost('company_name'),
                        'first_name' => $request->getPost('first_name'),
                        'middle_name' => $request->getPost('middle_name'),
                        'last_name' => $request->getPost('last_name'),
                        'company_email' => $request->getPost('company_email'),
                        'company_mobile' => $request->getPost('company_mobile'),
                        'company_phone' => $request->getPost('company_phone'),
                        'company_address' => $request->getPost('company_address1'),
                        'company_address_two' => $request->getPost('company_address2'),
                        'company_fax' => $request->getPost('company_fax'),
                        'company_website' => $request->getPost('company_website'),
                        'language' => $request->getPost('language'),
                        'city' => $request->getPost('city'),
                        'state' => $request->getPost('state'),
                        'currency' => $request->getPost('currency'),
                        'country' => $request->getPost('country'),
                        'VAT' => $request->getPost('VAT'),
                        'zip' => $request->getPost('zip'),
                        'notes' => $request->getPost('notes'),
                        'primary_contact' => $user_id
                    );
                    $company_id = App::save_data('hd_companies', $dataComp);
                   					 
                    $profile = array(
                        'user_id' => $user_id,
                        'company' => $company_id,
                        'fullname' => $request->getPost('company_name'),
                        'phone' => $request->getPost('company_phone'),
						'mobile' => $request->getPost('company_mobile'),
                        'avatar' => 'default_avatar.jpg',
                        'language' => $request->getPost('language'),
                        'locale' => $this->custom_helper->getconfig_item('locale') ? $this->custom_helper->getconfig_item('locale') : 'en_US'
                    );
                    
                    $user_id = App::save_data('hd_account_details', $profile);
                    // }
                    $session = \Config\Services::session();  
                
                
                // Connect to the database  
                $db = \Config\Database::connect();

                // print_r($custom_fields);die;

                if(!empty($custom_fields)) {
                    foreach ($custom_fields as $key => $f) {
                        $key = str_replace('cust_', '', $key);
                        $r = $db->table('hd_formmeta')->where(array('client_id' => $company_id, 'meta_key' => $key))->get()->getResult();
                        $cf = $db->table('hd_fields')->where(array('name' => $key))->get()->getResult();
                        $data = array(
                            'module' => 'clients',
                            'field_id' => $cf['id'],
                            'client_id' => $company_id,
                            'meta_key' => $cf['name'],
                            'meta_value' => is_array($f) ? json_encode($f) : $f
                        );
                        ($r->num_rows() == 0) ? $db->table('hd_formmeta')->insert($data) : $db->table('hd_formmeta')->where(array('client_id' => $company_id, 'meta_key' => $cf['name']))->update($data);
                    }
                }

                $args = array(
                    'user' => User::get_id(),
                    'module' => 'Clients',
                  //  'module_field_id' => $company_id,
                    'activity' => 'activity_added_new_company',
                    'icon' => 'fa-user',
                    'value1' => $request->getPost('company_name'),
                );
                App::Log($args);

                // $this->session->set_flashdata('response_status', 'success');
                // $this->session->set_flashdata('message', lang('hd_lang.client_registered_successfully'));
                // redirect($_SERVER['HTTP_REFERER']);
                return redirect()->to('companies');
            }
        } else {
            // $this->load->view('modal/create');
            echo view('modules/companies/modal/create');
        }
    }


    public function update($company = null)
    {
        $session = \Config\Services::session();  
                
        // Connect to the database  
        $db = \Config\Database::connect();

        $request = \Config\Services::request();

        if ($request->getPost()) {

            $custom_fields = array();
            foreach ($_POST as $key => &$value) {
                if (strpos($key, 'cust_') === 0) {
                    $custom_fields[$key] = $value;
                    unset($_POST[$key]);
                }
            }

            $validation = \Config\Services::validation();
            
            $helper = new whatpanel_helper();
            
            $validationRules = [
                'company_ref' => 'required',
                'company_name' => 'required',
                'company_email' => 'required|valid_email'
            ];

            $validation->setRules($validationRules);

            //if ($validation->withRequest($request)->run()) {
                // $_POST = '';
                // $errors = validation_errors();
                // AppLib::go_to('companies', 'error', lang('hd_lang.error_in_form'));
                // return redirect()->to('companies');
           // } else {  echo 9;die;
                $company_id = $_POST['co_id'];

                foreach ($custom_fields as $key => $f) {
                    $key = str_replace('cust_', '', $key);

                    $r = $db->table('hd_formmeta')
                        ->where(['client_id' => $company_id, 'meta_key' => $key])
                        ->get()
                        ->getResult();

                    $cf = $db->table('hd_fields')
                        ->where('name', $key)
                        ->get()
                        ->getRow();

                    $data = array(
                        'module' => 'clients',
                        'field_id' => $cf->row()->id,
                        'client_id' => $company_id,
                        'meta_key' => $cf->row()->name,
                        'meta_value' => is_array($f) ? json_encode($f) : $f
                    );
                    if ($db->table('hd_formmeta')->where(['client_id' => $company_id, 'meta_key' => $cf->name])->countAllResults() == 0) {
                        $db->table('hd_formmeta')->insert($data);
                    } else {
                        $db->table('hd_formmeta')->where(['client_id' => $company_id, 'meta_key' => $cf->name])->update($data);
                    }                    
                }

                $client = new Client();

                $dataComp = array(
                    'company_name' => $request->getPost('company_name'),
                    'first_name' => $request->getPost('first_name'),
                    'middle_name' => $request->getPost('middle_name'),
                    'last_name' => $request->getPost('last_name'),
                    'company_email' => $request->getPost('company_email'),
                    'company_mobile' => $request->getPost('company_mobile'),
                    'company_phone' => $request->getPost('company_phone'),
                    'company_address' => $request->getPost('company_address1'),
                    'company_address_two' => $request->getPost('company_address2'),
                    'company_fax' => $request->getPost('company_fax'),
                    'company_website' => $request->getPost('company_website'),
                    'language' => $request->getPost('language'),
                    'city' => $request->getPost('city'),
                    'state' => $request->getPost('state'),
                    'currency' => $request->getPost('currency'),
                    'country' => $request->getPost('country'),
                    'VAT' => $request->getPost('VAT'),
                    'zip' => $request->getPost('zip'),
					//'company_username' => $request->getPost('username'),
					//'company_password' => $request->getPost('password'),
					//'confirm_password' => $request->getPost('confirm_password'),
                    'notes' => $request->getPost('notes')
                );

                $db->table('hd_companies')->where('co_id', $company_id)->update($dataComp);

                $args = array(
                    'user' => User::get_id(),
                    'module' => 'Clients',
                    'module_field_id' => $company_id,
                    'activity' => 'activity_updated_company',
                    'icon' => 'fa-edit',
                    'value1' => $request->getPost('company_name'),
                );
                App::Log($args);

                // $this->session->set_flashdata('response_status', 'success');
                // $this->session->set_flashdata('message', lang('hd_lang.client_updated'));
                // redirect('companies/view/' . $company_id);
                return redirect()->to('companies/view/' . $company_id);
            //}
        } else {
            $data['company'] = $company;
            //$this->load->view('companies/modal/edit', $data);
            echo view('modules/companies/modal/edit', $data);
        }
    }

     public function file($id = null)
    {
        $request = \Config\Services::request();
        $db = \Config\Database::connect();
        $custom_helper = new custom_name_helper();

        if ($this->request->uri->getSegment(3) == 'delete') { // Delete file code
            if ($request->getPost()) {
                $file_id = $request->getPost('file');
                $file = $db->table('hd_files')->where('file_id', $file_id)->get()->getRow();

                $fullpath = 'resource/uploads/' . $file->file_name;
                if (file_exists($fullpath)) {
                    unlink($fullpath);
                }

                $session = \Config\Services::session();

                $db->table('hd_app')->where('file_id', $file_id)->delete();

                // Log activity
                $data = [
                    'module' => 'Clients',
                    'module_field_id' => $file->client_id,
                    'user' => User::get_id(),
                    'activity' => 'activity_deleted_a_file',
                    'icon' => 'fa-times',
                    'value1' => $file->file_name,
                    'value2' => '',
                ];
                AppModel::Log($data);
                return redirect()->to('companies/view/' . $file->client_id)->with('success', lang('hd_lang.file_deleted'));
            } else {
                $data['file_id'] = $this->request->uri->getSegment(4);
                $data['action'] = 'delete_file';
                echo view('modules/companies/modal/file_action', $data);
            }
        } elseif ($this->request->uri->getSegment(3) == 'add') { // Adding a file
            if ($request->getPost()) {
                $company = $request->getPost('company');
                $description = $request->getPost('description');

                $files = $this->request->getFiles();
                $validationRules = [
                    'clientfiles.*' => [
                        'label' => 'Client Files',
                        'rules' => 'uploaded[clientfiles.*]|mime_in[clientfiles.*,application/pdf,image/jpg,image/jpeg,image/png]|max_size[clientfiles.*,2048]',
                    ],
                ];

                //if (!$this->validate($validationRules)) {
                 //   return redirect()->back()->with('error', lang('hd_lang.operation_failed'))
                    //    ->with('form_error', $this->validator->getErrors());
                //}

                foreach ($files['clientfiles'] as $file) {
                    if ($file->isValid() && !$file->hasMoved()) {
                        $newName = $file->getRandomName();
                        $file->move('uploads', $newName);

                        // Determine if the file is an image based on MIME type
                        //$isImage = in_array($file->getMimeType(), ['pdf', 'image/jpeg', 'image/png']);

                        $data = [
                            'client_id' => $company,
                            'path' => null,
                            'file_name' => $file->getName(),
                            'title' => $request->getPost('title'),
                            'ext' => $file->getClientExtension(),
                            'size' => $file->getSize(),
                            //'is_image' => $isImage,
                           // 'image_width' => $isImage ? $file->getProperties()['image_width'] : null,
                            //'image_height' => $isImage ? $file->getProperties()['image_height'] : null,
                            'description' => $description,
                            'uploaded_by' => User::get_id(),
                        ];
                        $db->table('hd_files')->insert($data);
                    }
                }

                // Log activity
                $logData = [
                    'module' => 'Clients',
                    'module_field_id' => $company,
                    'user' => User::get_id(),
                    'activity' => 'activity_uploaded_file',
                    'icon' => 'fa-file',
                    'value1' => $request->getPost('title'),
                    'value2' => '',
                ];
                App::Log($logData);
                return redirect()->to('companies/view/' . $company);
            } else {
                $data['company'] = $this->request->uri->getSegment(4);
                $data['action'] = 'add_file';
                echo view('modules/companies/modal/file_action', $data);
            }
        } else { // Download file
            $file = $db->table('hd_files')->where('file_id', $id)->get()->getRow();
            $fullpath = './uploads/' . $file->file_name;

            if (file_exists($fullpath)) {
                return $this->response->download($fullpath, null);
            } else {
                return redirect()->to('companies/view/' . $file->client_id)->with('error', lang('hd_lang.operation_failed'));
            }
        }
    }

	
	    public function send_email($id = null)
    {   
        $request = \Config\Services::request();

        $helper = new custom_name_helper();

        if ($request->getPost()) 
        {           
            $config = array();

            if ($helper->getconfig_item('protocol') == 'smtp') {
                $encryption = \Config\Services::encryption();

                // Assuming $helper is an instance of your helper class
                $raw_smtp_pass = $encryption->decrypt($helper->getconfig_item('smtp_pass'));
                $config = array(
                    'smtp_host'     => $helper->getconfig_item('smtp_host'),
                    'smtp_port'     => $helper->getconfig_item('smtp_port'),
                    'smtp_user'     => $helper->getconfig_item('smtp_user'),
                    'smtp_pass'     => $raw_smtp_pass,
                    'crlf' 		    => "\r\n",  
                    'smtp_crypto'   => $helper->getconfig_item('smtp_encryption')
            );						
        } 

            $email = \Config\Services::email();
            // Send email 
            $config['protocol'] = $helper->getconfig_item('protocol');
            $config['mailtype'] = "html";
            $config['newline'] = "\r\n";
            $config['charset'] = 'utf-8';
            $config['wordwrap'] = TRUE;  

            $email->initialize($config);   
            $email->setFrom($helper->getconfig_item('company_email'), $helper->getconfig_item('company_name')); 
            $email->setTo($request->getPost('email'));  
            $email->setSubject($request->getPost('subject'));
            $email->setMessage($request->getPost('message'));   
            if ($request->getPost('cc')) {
				
				$email->setCC($request->getPost('cc')); 
			}

			$email->setReplyTo($helper->getconfig_item('company_email'), $helper->getconfig_item('company_name'));

            if ($email->send(false)) {
                //echo 'success';die;
				session()->setFlashdata('message', lang('message_sent'));
			} else {
                //echo 'falied';die;
				$debuggerInfo = implode('<br>', $email->printDebugger(['headers', 'subject', 'body']));
               // print_r($debuggerInfo);die;
				session()->setFlashdata('message', lang('message_not_sent') . "<p>{$debuggerInfo}</p>");
			}

            //redirect($_SERVER['HTTP_REFERER']);
            session()->setFlashdata('response_status', 'success');
			session()->setFlashdata('message', lang('hd_lang.message_sent'));
			//return redirect()->back();
            return redirect()->to(site_url('companies/view/' . $request->getPost('com_id').'/email'));
            
        }  
    }




    function send_sms($id = null)
    {
        if ($request->getPost()) {
            $phone = trim($request->getPost('phone'));
            $message = $request->getPost('message');

            $result = send_sms($phone, $message);

            $this->session->set_flashdata('response_status', 'info');
            $this->session->set_flashdata('message', $result);
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            $company = Client::view_by_id($id);
            $data['mobile'] = $company->company_mobile;
            $this->load->view('modal/send_sms', $data);
        }
    }



    public function send_invoice($user = null)
    {	
		error_reporting(E_ALL);
        ini_set("display_errors", "1");
        $company = $this->request->uri->getSegment(4);
		$request = \Config\Services::request();
		
        if ($request->getPost()) {
            $lib = new Applib();
            $invoice_id = $request->getPost('invoice_id');
            $company = $request->getPost('company');
            $contact = $request->getPost('user');

            $info = Invoice::view_by_id($invoice_id);
            $client = Client::view_by_id($info->client);

            if ($contact > 0) {
                $login = '?login=' . $this->tank_auth->create_remote_login($contact);
            } else {
                $login = '';
            }

            $amount = number_format(Invoice::get_invoice_due_amount($invoice_id), 2, $this->custom_helper->getconfig_item('decimal_separator'), $this->custom_helper->getconfig_item('thousand_separator'));

            $cur = App::currencies($info->currency)->symbol;

            $message = App::email_template('invoice_message', 'template_body');
            $subject = App::email_template('invoice_message', 'subject');
            $signature = App::email_template('email_signature', 'template_body');

            $logo_link = create_email_logo();

            $logo = str_replace('{INVOICE_LOGO}', $logo_link, $message);

            $client_name = str_replace('{CLIENT}', $client->company_name, $logo);
            $Ref = str_replace('{REF}', $info->reference_no, $client_name);
            $Amount = str_replace('{AMOUNT}', $amount, $Ref);
            $Currency = str_replace('{CURRENCY}', $cur, $Amount);
            $link = str_replace('{INVOICE_LINK}', base_url() . 'invoices/view/' . $invoice_id . $login, $Currency);
            $EmailSignature = str_replace('{SIGNATURE}', $signature, $link);
            $message = str_replace('{SITE_NAME}', $this->custom_helper->getconfig_item('company_name'), $EmailSignature);

            $this->_email_invoice($invoice_id, $message, $subject, $contact); // Email Invoice

            $data = array('emailed' => 'Yes', 'date_sent' => date('Y-m-d H:i:s', time()));

            App::update('invoices', array('inv_id' => $invoice_id), $data);

            // Log Activity
            $activity = array(
                'user' => User::get_id(),
                'module' => 'invoices',
                'module_field_id' => $invoice_id,
                'activity' => 'activity_invoice_sent',
                'icon' => 'fa-envelope',
                'value1' => $info->reference_no,
            );
            App::Log($activity);

            Applib::go_to($_SERVER['HTTP_REFERER'], 'success', lang('hd_lang.invoice_sent_successfully'));
        } else {
            $data['invoices'] = Invoice::get_client_invoices($company);
            $data['company'] = $company;
            $data['user'] = $user;
           // $this->load->view('modal/email_invoice', $data);
			echo view('modules/companies/modal/email_invoice', $data);
			
        }
    }

    public function _email_invoice($invoice_id, $message, $subject, $contact)
    {
        $info = Invoice::view_by_id($invoice_id);

        $data['message'] = $message;

        $message = $this->load->view('email_template', $data, true);

        $params = array(
            'recipient' => User::login_info($contact)->email,
            'subject' => $subject,
            'message' => $message,
        );

        $this->load->helper('file');
        $attach['inv_id'] = $invoice_id;
        if ($this->custom_helper->getconfig_item('pdf_engine') == 'invoicr') {
            $invoicehtml = Modules::run('fopdf/attach_invoice', $attach);
        }
        if ($this->custom_helper->getconfig_item('pdf_engine') == 'mpdf') {
            $invoicehtml = Modules::run('invoices/attach_pdf', $invoice_id);
        }

        $params['attached_file'] = './resource/tmp/' . lang('hd_lang.invoice') . ' ' . $info->reference_no . '.pdf';
        $params['attachment_url'] = base_url() . 'resource/tmp/' . lang('hd_lang.invoice') . ' ' . $info->reference_no . '.pdf';

        Modules::run('fomailer/send_email', $params);
        //Delete invoice in tmp folder
        if (is_file('./resource/tmp/' . lang('hd_lang.invoice') . ' ' . $info->reference_no . '.pdf')) {
            unlink('./resource/tmp/' . lang('hd_lang.invoice') . ' ' . $info->reference_no . '.pdf');
        }
    }

   	public function make_primary()
	{
		$contact = $this->request->uri->getSegment(3);
		$company = $this->request->uri->getSegment(4);
		$db = \Config\Database::connect();
		$db->table('hd_companies')->set('primary_contact', $contact)->where('co_id', $company)->update();
		session()->setFlashdata('response_status', 'success');
		session()->setFlashdata('message', lang('hd_lang.primary_contact_set'));
		   //redirect('companies/view/' . $company);
		return redirect()->to(site_url('companies/view/' . $company));
	}


    public function account()
    {
        $client = $this->db->where('co_id', $this->uri->segment(4))->get('companies')->result();
        $data['client'] = $client[0];
        $data['type'] = $this->uri->segment(3);
        $this->load->view('modal/account', isset($data) ? $data : null);
    }

    // Delete Company
    public function delete($id = null)
    {
        $request = \Config\Services::request();

        $session = \Config\Services::session();

        $clientModel = new Client();

        if ($request->getPost()) {
            $company = $request->getPost('company');

            $clientModel->delete($company);

            $session->setFlashdata('response_status', 'success');
            $session->setFlashdata('message', lang('hd_lang.company_deleted_successfully'));
            return redirect()->to('companies');
            //redirect('companies');
        } else {
            $data['company_id'] = $id;
            //$this->load->view('modal/delete', $data);
            echo view('modules/companies/modal/delete', $data);

        }
    }

    public function import_clients()
    {
        $count = 0;

        if ($request->getPost()) {
            $array = array();
            $list = array();

            foreach ($request->getPost() as $k => $r) {
                $array[] = $k;
            }

            $accounts = $this->session->userdata('import_clients');
            foreach ($accounts as $k => $r) {
                if (in_array($r->id, $array)) {
                    $list[] = $r;
                }
            }


            if (count($list) > 0) {

                foreach ($list as $client) {
                    $co_id = $client->id;

                    if (0 == $this->db->where('co_id', $co_id)->get('companies')->num_rows()) {
                        $hasher = new PasswordHash(
                            $this->config->item('phpass_hash_strength', 'tank_auth'),
                            $this->config->item('phpass_hash_portable', 'tank_auth')
                        );

                        $hashed_password = $hasher->HashPassword(create_password());
                        $username = $client->first_name;

                        if (!is_username_available($username)) {
                            $username = $client->first_name . $client->last_name;
                        }

                        $data = array(
                            'username' => $username,
                            'password' => $hashed_password,
                            'email' => $client->email,
                            'role_id' => 2
                        );

                        $user_id = App::save_data('users', $data);

                        $company = array(
                            'company_name' => $client->company,
                            'company_email' => $client->email,
                            'company_ref' => $this->applib->generate_string(),
                            'language' => $this->custom_helper->getconfig_item('default_language'),
                            'currency' => $this->custom_helper->getconfig_item('default_currency'),
                            'primary_contact' => $user_id,
                            'individual' => 0,
                            'company_address' => $client->address_1 . " " . $client->address_2,
                            'company_phone' => $client->phone,
                            'city' => $client->city,
                            'country' => $client->country,
                            'transaction_value' => $client->credit,
                            'co_id' => $co_id,
                            'imported' => 1
                        );

                        if (App::save_data('companies', $company)) {

                            $profile = array(
                                'user_id' => $user_id,
                                'company' => $co_id,
                                'fullname' => $client->first_name . " " . $client->last_name,
                                'phone' => $client->phone,
                                'avatar' => 'default_avatar.jpg',
                                'language' => $this->custom_helper->getconfig_item('default_language'),
                                'locale' => $this->custom_helper->getconfig_item('locale') ? $this->custom_helper->getconfig_item('locale') : 'en_US'
                            );

                            if (App::save_data('account_details', $profile)) {
                                $count++;
                            }
                        }
                    }
                }
            }

            $this->session->unset_userdata('import');

            $this->session->set_flashdata('response_status', 'info');
            $this->session->set_flashdata('message', "Created " . $count . " clients");
            if ($count == 0) {
                redirect($_SERVER['HTTP_REFERER']);
            } else {
                redirect('companies');
            }

        } else {
            $this->template->title(lang('hd_lang.import'));
            $data['page'] = 'Namecheap';
            $data['datatables'] = TRUE;
            $data['domains'] = $this->get_accounts();
            $this->template
                ->set_layout('users')
                ->build('import', isset($data) ? $data : NULL);
        }
    }



    function import()
    {
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('hd_lang.import'));
        $data['page'] = lang('hd_lang.clients');
        $this->template
            ->set_layout('users')
            ->build('import', isset($data) ? $data : NULL);
    }



    function upload()
    {
        if ($request->getPost()) {

            $this->load->library('excel');
            ob_start();
            $file = $_FILES["import"]["tmp_name"];
            if (!empty($file)) {
                $valid = false;
                $types = array('Excel2007', 'Excel5', 'CSV');
                foreach ($types as $type) {
                    $reader = PHPExcel_IOFactory::createReader($type);
                    if ($reader->canRead($file)) {
                        $valid = true;
                    }
                }
                if (!empty($valid)) {
                    try {
                        $objPHPExcel = PHPExcel_IOFactory::load($file);
                    } catch (Exception $e) {
                        $this->session->set_flashdata('response_status', 'warning');
                        $this->session->set_flashdata('message', "Error loading file:" . $e->getMessage());
                        redirect($_SERVER['HTTP_REFERER']);

                    }
                    $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);

                    $clients = array();
                    $list = array();

                    for ($x = 3; $x <= count($sheetData); $x++) {
                        if ($this->db->where('co_id', $sheetData[$x]["A"])->where('imported', 1)->get('companies')->num_rows() == 0) {
                            $code = $sheetData[$x]["K"];
                            $string = '[[:<:]]' . $code . '[[:>:]]';
                            $country = $this->db->get_where('countries', array("code REGEXP BINARY " => $string), FALSE)->row();

                            $domain = array();
                            $domain['id'] = trim($sheetData[$x]["A"]);
                            $domain['first_name'] = $sheetData[$x]["B"];
                            $domain['last_name'] = $sheetData[$x]["C"];
                            $domain['company'] = $sheetData[$x]["D"];
                            $domain['email'] = $sheetData[$x]["E"];
                            $domain['address_1'] = $sheetData[$x]["F"];
                            $domain['address_2'] = $sheetData[$x]["G"];
                            $domain['city'] = $sheetData[$x]["H"];
                            $domain['country'] = $country->value;
                            $domain['phone'] = $sheetData[$x]["L"];
                            $domain['credit'] = $sheetData[$x]["O"];
                            $clients[] = (object) $domain;
                        }
                    }

                    $this->session->set_userdata('import_clients', $clients);

                } else {
                    $this->session->set_flashdata('response_status', 'warning');
                    $this->session->set_flashdata('message', lang('hd_lang.not_csv'));
                    redirect($_SERVER['HTTP_REFERER']);
                }
            } else {
                $this->session->set_flashdata('response_status', 'warning');
                $this->session->set_flashdata('message', lang('hd_lang.no_csv'));
                redirect($_SERVER['HTTP_REFERER']);
            }
            redirect('companies/import');
        } else {
            $this->load->module('layouts');
            $this->load->library('template');
            $this->template->title(lang('hd_lang.import'));
            $data['page'] = lang('hd_lang.clients');
            $this->template
                ->set_layout('users')
                ->build('upload', isset($data) ? $data : NULL);
        }
    }


}
/* End of file controllers/Companies.php */