<?php
 /*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

namespace App\Modules\settings\controllers;

use App\Libraries\AppLib;
use App\Models\App;
use App\Models\Setting;
use App\Models\User;
use App\Models\Item_pricing;
use App\ThirdParty\MX\WhatPanel;
use App\Helpers\whatpanel_helper;
use App\Helpers\file_helper;
use App\Helpers\download_helper;
use App\Helpers\MyUtils;
use App\ThirdParty\MX\Lang;
use CodeIgniter\Database\BackupUtils;
use CodeIgniter\Database\BaseUtils;
use App\Helpers\custom_name_helper;

use CodeIgniter\Language\Language;


class Settings extends WhatPanel
{
    protected $invoice_logo_height;
    protected $invoice_logo_width;
    protected $general_setting;
    protected $domain_setting;
    protected $invoice_setting;
    protected $system_setting;
    protected $theme_setting;
    protected $language_files;
    protected $settingModel;
    protected $applib;

    function __construct()
    {
        // parent::__construct();
        //User::logged_in();
        // if (!User::is_admin() && !User::perm_allowed(User::get_id(), 'edit_settings')) {
        //     redirect('dashboard');
        // }

        error_reporting(E_ALL);
        ini_set("display_errors", "1");

        $this->invoice_logo_height = 0;
        $this->invoice_logo_width = 0;
        // $this->load->library(array('form_validation'));

        // $this->auth_key = config_item('api_key'); // Set our API KEY

        // $this->load->module('layouts');
        // $this->load->library('template');
        // $this->template->title(lang('hd_lang.settings') . ' - ' . config_item('company_name'));

        $session = \Config\Services::session();

        // Connect to the database  
        $db = \Config\Database::connect();

        // $this->load->model(array('Setting'));
        $this->settingModel = new Setting();
        $this->applib = new AppLib();

        $this->general_setting = '?settings=general';
        $this->domain_setting = '?settings=domain';
        $this->invoice_setting = '?settings=invoice';
        $this->system_setting = '?settings=system';
        $this->theme_setting = '?settings=theme';

        $autoloadConfig = config('Autoload');

        $this->language_files = array(
			'tank_auth_lang.php' =>  APPPATH,
			'Calendar.php' => ROOTPATH . 'system/',
			'Date.php' => ROOTPATH . 'system/',
			'Database.php' => ROOTPATH . 'system/',
			'Email.php' => ROOTPATH . 'system/',
			'Validation.php' => ROOTPATH . 'system/',
			'FTP.php' => ROOTPATH . 'system/',
			'Images.php' => ROOTPATH . 'system/',
			'Migrations.php' => ROOTPATH . 'system/',
			'Number.php' => ROOTPATH . 'system/',
			'Profiler.php' => ROOTPATH . 'system/',
			'Test.php' => ROOTPATH . 'system/',
			'Upload.php' => ROOTPATH . 'system/',
			'hd_lang.php' => APPPATH,
		);

    $custom_name_helper = new custom_name_helper();

        $lang = $db->table('hd_config')->select('value')->where('value', $custom_name_helper->getconfig_item('locale'))->get()->getRow()->value;

        $locale = strtolower(str_replace("_", "-", $lang));

        $language = \Config\Services::language();
        // echo $locale;die;
      $language->setLocale($locale);
		
    }

    function index($type = null, $subtype = null, $sub1 = null)
    {
		error_reporting(E_ALL);
        ini_set("display_errors", "1");
        $session = \Config\Services::session();

        // Connect to the database  
        $db = \Config\Database::connect();

        $request = service('request');

        $uri = service('uri');

        $applib = new AppLib();

        $settings = $type ? $type : 'general';

        $data['page'] = lang('hd_lang.settings');
        $data['form'] = TRUE;
        $data['editor'] = TRUE;
        $data['fuelux'] = TRUE;
        $data['datatables'] = TRUE;
        $data['nouislider'] = TRUE;
		//print_r($data);die;
        // $data['translations'] = $applib->translations();
        $data['available'] = $this->available_translations();
        //$data['languages'] = App::languages();
		$data['languages'] = App::languages_locale();
        $data['load_setting'] = $settings;
        $data['load_sub_setting'] = $subtype;
        $data['load_sub_group'] = $sub1;
        // $data['load_sub1'] = $sub1;
        $data['locale_name'] = App::get_locale()->name;

        if ($settings == 'system') {
            $data['countries'] = App::countries();
            $data['locales'] = App::locales();
            $data['currencies'] = App::currencies();
            $data['timezones'] = Setting::timezones();
        }

        $query = $db->table('hd_hooks')
            ->where('hook', 'main_menu_admin')
            ->where('parent', '')
            ->where('access', 1)
            ->orderBy('order', 'ASC')
            ->get()->getResult();

        if ($settings == 'menu') {
            $data['iconpicker'] = TRUE;
            $data['sortable'] = TRUE;

            $data['admin'] = $db->table('hd_hooks')
                ->where('hook', 'main_menu_admin')
                ->where('parent', '')
                ->where('access', 1)
                ->orderBy('order', 'ASC')
                ->get()->getResult();

            $data['client'] = $db->table('hd_hooks')
                ->where('hook', 'main_menu_client')
                ->where('parent', '')
                ->where('access', 2)
                ->orderBy('order', 'ASC')
                ->get()->getResult();

            $data['staff'] = $db->table('hd_hooks')
                ->where('hook', 'main_menu_staff')
                ->where('parent', '')
                ->where('access', 3)
                ->orderBy('order', 'ASC')
                ->get()->getResult();
        }
        if ($settings == 'crons') {
            $data['crons'] = $db->table('hd_hooks')
                ->where('hook', 'cron_job_admin')
                ->where('access', 1)
                ->orderBy('name', 'ASC')
                ->get()->getResult();
        }
        if ($settings == 'general') {
            $data['countries'] = App::countries();
        }

        if ($settings == 'domain') {
            $data['countries'] = App::countries();
        }

        if ($settings == 'sms_templates') {
            $data['templates'] = $db->table('hd_sms_templates')->get()->getResult();
        }

        if ($settings == 'theme') {
            $data['iconpicker'] = TRUE;
        }

        if ($settings == 'translations') {
            $settingModel = new Setting();
            $action = $this->request->uri->getSegment(3);
            // echo $action;die;
            $data['translation_stats'] = $settingModel->translation_stats($this->language_files);
        	//echo"<pre>";print_r($data['translation_stats']);die;
            if ($action == 'add') {
                $this->translations($action, $sub1);
            }

            if ($action == 'view') {
                $data['language'] = $uri->getSegment(4);
                $data['language_files'] = $this->language_files;
                //echo"<pre>";print_r($data);die;
            }
            if ($action == 'edit') {
                $language = $uri->getSegment(4);
                $file = $uri->getSegment(5);
               
                if($file == "hd" || $file == "tank_auth"){
                $path = $this->language_files[$file . '_lang.php'];
                }else{
                $path = $this->language_files[$file . '.php'];
                //print_r($path);die;
                }
                // $path = $this->language_files[$file . '_lang.php'];
                $data['language'] = $language;
                $lang = new Lang();
                
                $data['english'] = $lang->load($file,'en', TRUE, TRUE, $path);
                // print_r($data['english']);die;
				//echo $language;die;
                if ($language == 'english') {
                    $data['translation'] = $data['english'];
                } else {
					$langa = new Lang();
					
					$lang = $db->table('hd_locales')
            		->select('hd_locales.*, hd_locales.code as locale_code, hd_languages.code as language_code, hd_languages.name as name1, 						hd_languages.icon')
            		->join('hd_languages', 'hd_languages.locale = hd_locales.locale', 'left')->where('hd_languages.name', $language)
						->get()->getRow();
					
					$slug = strtolower(str_replace("_", "-", $lang->locale));
					
                    $data['translation'] = $langa->load($file, $slug, TRUE, TRUE, $path);
                }
                $data['language_file'] = $file;
            }
        }
		$data['temp_data'] = $db->table('hd_config')->where('categories', $settings)->get()->getResult();
        // $this->template
        //     ->set_layout('users')
        //     ->build('settings', isset($data) ? $data : NULL);
		
		// echo "<pre>";print_r($data);die;

        //return view('modules/settings/settings', $data);
		if ($settings == 'database') {
            // Get the database connection
            $this->database();
        }
        echo view('modules/settings/settings', $data);
    }

    function vE()
    {
        Settings::_vP();
    }

    function templates()
    {
        $request = \Config\Services::request();

        $session = \Config\Services::session();

        // Connect to the database  
        $db = \Config\Database::connect();

        if ($_POST) {
            Applib::is_demo();
            $group = $request->getPost('email_group');
            $data = array(
                'subject' => $request->getPost('subject'),
                'template_body' => $request->getPost('email_template'),
            );
            // Setting::update_template($group, $data);
            $db->table('email_templates')->where('email_group', $group)->update($data);

            $return_url = $_POST['return_url'];

            $session->setFlashdata('response_status', 'success');
            $session->setFlashdata('message', lang('hd_lang.settings_updated_successfully'));

            return $return_url;
        } else {
            $this->index();
        }
    }


    function _sms_templates()
    {
        $request = \Config\Services::request();

        $session = \Config\Services::session();

        // Connect to the database  
        $db = \Config\Database::connect();

        if ($_POST) {
            Applib::is_demo();
            $template = $request->getPost('template');
            $db->table('hd_sms_templates')->where('type', $template)->update(array('body' => $request->getPost('sms_template')));
            $return_url = $_POST['return_url'];

            $session->setFlashdata('response_status', 'success');
            $session->setFlashdata('message', lang('hd_lang.settings_updated_successfully'));

            return $return_url;
        } else {
            $this->index();
        }
    }



    function customize()
    {
        $this->load->helper('file');
        if ($_POST) {
            $data = $_POST['css-area'];
            if (write_file('./resource/css/style.css', $data)) {
                $this->session->set_flashdata('response_status', 'success');
                $this->session->set_flashdata('message', lang('hd_lang.settings_updated_successfully'));
                redirect('settings/?settings=customize');
            } else {
                $this->session->set_flashdata('response_status', 'error');
                $this->session->set_flashdata('message', lang('hd_lang.operation_failed'));
                redirect('settings/?settings=customize');
            }
        } else {
            $this->index();
        }
    }



    function add_currency()
    {
        $session = \Config\Services::session();
        // Connect to the database  
        $db = \Config\Database::connect();

        $request = \Config\Services::request();

        $validation = \Config\Services::validation();

        if ($request->getPost()) {

            $validation->setRules([
                'code' => 'required|alpha_numeric|max_length[10]', // Example rule: required, alphanumeric, max length 10
                'name' => 'required|string|max_length[100]', // Adjust these rules based on your form fields
                'xrate' => 'required|numeric', // Adjust these rules based on your form fields
                'status' => 'required', // Adjust these rules based on your form fields
                // Add more rules as needed for other fields
            ]);

            if (!$validation->withRequest($request)->run()) {
                // Validation failed
                $session->setFlashdata('response_status', 'error');
                $session->setFlashdata('message', implode('<br>', $validation->getErrors()));
                return redirect()->to('settings/currency');
            }
            else {
                $query = $db->table('hd_currencies')->where('code', $request->getPost('code'))->get();
                if ($query->getNumRows() == 0) {
                    App::save_data('hd_currencies', $request->getPost());
                    $session->setFlashdata('response_status', 'success');
                    $session->setFlashdata('message', lang('hd_lang.currency_added_successfully'));
                    //  redirect()->to('settings/currency');
                // return redirect()->to('settings');
                    return redirect()->to('settings/currency');
                } else {
                    $session->setFlashdata('response_status', 'error');
                    $session->setFlashdata('message', lang('hd_lang.currency_code_exists'));
                    //redirect()->to('settings/system');
                // return redirect()->to('settings');
                    return redirect()->to('settings/currency');
                }
            }
        } else {
            // $this->load->view('modal_add_currency', isset($data) ? $data : NULL);
            return view('modules/settings/modal_add_currency');
        }
    }



    function xrates()
    {
        $session = \Config\Services::session();

        // Connect to the database  
        $db = \Config\Database::connect();

        if ($_POST) {
            $exists = $db->table('hd_config')->where('config_key', 'xrates_app_id')->get();

            if ($exists->getNumRows() > 0) {
                // Update the record if it exists
                $db->table('hd_config')->where('config_key', 'xrates_app_id')->update(['value' => $_POST['xrates_app_id']]);
            } else {
                // Insert the record if it doesn't exist
                $db->table('hd_config')->insert(['config_key' => 'xrates_app_id', 'value' => $_POST['xrates_app_id']]);
            }

            $session->setFlashdata('response_status', 'success');
            $session->setFlashdata('message', lang('hd_lang.currency_updated_successfully'));

            $return_url = $_POST['return_url'];

            return $return_url;
        }
    }

    function edit_currency($code = NULL)
    {
        $session = \Config\Services::session();
        // Connect to the database  
        $db = \Config\Database::connect();

        $request = \Config\Services::request();

        if ($_POST) {
            $code = $request->getPost('code');
            $existingCurrency = $db->table('hd_currencies')->where('code', $code)->get()->getRow();

            if (!$existingCurrency) {
                $session->setFlashdata('response_status', 'error');
                $session->setFlashdata('message', lang('hd_lang.currency_not_found'));
                return redirect()->to('settings/currency');
            }

            // Update the existing currency
            $db->table('hd_currencies')->where('code', $code)->update($request->getPost());
            $session->setFlashdata('response_status', 'success');
            $session->setFlashdata('message', lang('hd_lang.currency_updated_successfully'));
            return redirect()->to('settings/currency');
        } else {
            $currency = $db->table('hd_currencies')->where('code', $code)->get()->getRow();

            if (!$currency) {
                $session->setFlashdata('response_status', 'error');
                $session->setFlashdata('message', lang('hd_lang.currency_not_found'));
                return redirect()->to('settings/currency');
            }
            $data['code'] = $code;
            $data['currency'] = $currency;
            return view('modules/settings/modal_edit_currency', $data);
        }
    }

        public function add_category()
    {
        $request = \Config\Services::request();

        $session = \Config\Services::session();

        // Connect to the database  
        $db = \Config\Database::connect();

        if ($request->getPost()) {
            Applib::is_demo();

            $builder = $db->table('hd_categories');

            $result = $builder->where('cat_name', $request->getPost('cat_name'))
                ->get()
                ->getNumRows();

            if ($result == 0) {
                if ($category_id = App::save_data('hd_categories', $request->getPost())) {
                    $this->add_block($category_id);
                }

                $session->setFlashdata('response_status', 'success');
                $session->setFlashdata('message', lang('hd_lang.category_added_successfully'));
                helper('url');
                // print_r($request->getPost());die;
                return redirect()->to($request->getServer('HTTP_REFERER'));
                // redirect($_SERVER['HTTP_REFERER']);
            } else {
                $session->setFlashdata('response_status', 'error');
                $session->setFlashdata('message', lang('hd_lang.category_exists'));
                helper('url');

                return redirect()->to($request->getServer('HTTP_REFERER'));
            }
        } else {
            //$this->load->view('modal_add_category', isset($data) ? $data : NULL);
            // echo 322;die;
            return view('modules/settings/modal_add_category');
        }
    }


    function edit_category($id = NULL)
    {
        $request = \Config\Services::request();

        $session = \Config\Services::session();

        // Connect to the database
        $db = \Config\Database::connect();

        if ($_POST) {
            Applib::is_demo();

            $id = $request->getPost('id');
            switch ($request->getPost('delete_cat')) {
                case 'on':

                    // Assuming that App is a CodeIgniter 4 model
                    $AppModel = new App();

                    // Delete records from the categories table
                    $db->table('hd_categories')->where('id', $id)->delete();

                    // Delete records from blocks_modules table
                    $db->table('hd_blocks_modules')->where('param', "items_" . $id)->where('module', 'Items')->delete();
                    $db->table('hd_blocks_modules')->where('param', "faq_" . $id)->where('module', 'FAQ')->delete();


                    $block = $db->table('hd_blocks')->where('id', "items_" . $id)->where('module', 'Items')->get()->getRow();

                    if ($block) {
                        // Delete records from the 'blocks' table
                        $db->table('hd_blocks')->where('id', "items_" . $id)->where('module', 'Items')->delete();

                        // Delete records from the 'blocks_pages' table
                        $db->table('hd_blocks_pages')->where('block_id', $block->block_id)->delete();
                    }

                    $block = $db->table('hd_blocks')->where('id', "items_" . $id)->where('module', 'Items')->get()->getRow();
                    if ($block) {
                        $db->table('hd_blocks')->where('id', "faq_" . $id)->where('module', "FAQ")->delete();
                        $db->table('hd_blocks')->where('block_id', $block->block_id)->delete();
                    }

                    $session->setFlashdata('response_status', 'success');
                    $session->setFlashdata('message', lang('hd_lang.operation_successful'));
                    break;

                default:
                    unset($_POST['delete_cat']);
                    $db->table('hd_categories')->where('id', $id)->update($request->getPost());

                    if (
                        $db->table('hd_blocks_modules')->where('param', "items_" . $id)->where('module', 'Items')->get()->getNumRows() == '0' &&
                        $db->table('hd_blocks_modules')->where('param', "faq_" . $id)->where('module', 'FAQ')->get()->getNumRows() == '0'
                    ) {
                        $this->add_block($id);
                    } else {
                        $data = array(
                            'name' => $request->getPost('cat_name')
                        );

                        $db->table('hd_blocks_modules')->where(array('param' => "items_" . $id, 'module' => 'Items'))->update($data);
                        $db->table('hd_blocks_modules')->where(array('param' => "faq_" . $id, 'module' => 'FAQ'))->update($data);
                    }
                    $session->setFlashdata('response_status', 'success');
                    $session->setFlashdata('message', lang('hd_lang.category_updated_successfully'));

                    break;
            }
            return redirect()->to($request->getServer('HTTP_REFERER'));
        } else {
            $data['cat'] = $id;
            // $this->load->view('modal_edit_category', isset($data) ? $data : NULL);
            return view('modules/settings/modal_edit_category', $data);
        }
    }


    function add_block($category_id)
    {
        $request = \Config\Services::request();

        $session = \Config\Services::session();

        // Connect to the database  
        $db = \Config\Database::connect();

        $builder = $db->table('hd_categories');

        $category = $builder->where('id', $category_id)
            ->get()
            ->getRow();

        $list = array(8, 9, 10);
        if (in_array($category->parent, $list)) {
            $data = array(
                'name' => $request->getPost('cat_name'),
                'param' => "items_" . $category_id,
                'type' => 'Module',
                'module' => 'Items'
            );

            App::save_data('hd_blocks_modules', $data);
        }

        if ($category->parent == 6) {
            $data = array(
                'name' => $request->getPost('cat_name'),
                'param' => "faq_" . $category_id,
                'type' => 'Module',
                'module' => 'FAQ'
            );

            App::save_data('hd_blocks_modules', $data);
        }
    }

    function _vP()
    {
        AppLib::pData();
        $data = array('value' => 'TRUE');
        Applib::update('config', array('config_key' => 'valid_license'), $data);
        Applib::make_flashdata(
            array(
                'response_status' => 'success',
                'message' => 'Software validated successfully'
            )
        );
        redirect($_SERVER['HTTP_REFERER']);
    }

    function departments()
    {
        if ($_POST) {
            $settings = $_POST['settings'];
            unset($_POST['settings']);
            App::save_data('departments', $this->input->post());

            $this->session->set_flashdata('response_status', 'success');
            $this->session->set_flashdata('message', lang('hd_lang.department_added_successfully'));
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            $this->index();
        }
    }


    function add_custom_field()
    {
        if ($_POST) {
            if (isset($_POST['targetdept'])) {
                // select department and redirect to creating field
                Applib::go_to('settings/?settings=fields&dept=' . $_POST['targetdept'], 'success', 'Department selected');
            } else {
                $_POST['uniqid'] = $this->_GenerateUniqueFieldValue();
                App::save_data('fields', $this->input->post());

                Applib::go_to('settings/?settings=fields&dept=' . $_POST['deptid'], 'success', 'Custom field added');
                // Insert to database additional fields

            }
        } else {
        }
    }


    function edit_custom_field($field = NULL)
    {
        if ($_POST) {
            if (isset($_POST['delete_field']) and $_POST['delete_field'] == 'on') {
                $this->db->where('id', $_POST['id'])->delete('fields');
                Applib::go_to($_SERVER['HTTP_REFERER'], 'success', lang('hd_lang.custom_field_deleted'));
            } else {
                $this->db->where('id', $_POST['id'])->update('fields', $this->input->post());
                Applib::go_to($_SERVER['HTTP_REFERER'], 'success', lang('hd_lang.custom_field_updated'));
            }
        } else {
            $data['field_info'] = $this->db->where(array('id' => $field))->get('fields')->result();
            $this->load->view('fields/modal_edit_field', isset($data) ? $data : NULL);
        }
    }



    function edit_dept($deptid = NULL)
    {
		$db = \Config\Database::connect();
		
		$request = \Config\Services::request();
		
        if ($request->getPost()) {
            if ($request->getPost('delete_dept') == 'on') {
                $db->table('hd_departments')->where('deptid', $request->getPost('deptid'))->delete();
                //Applib::go_to($_SERVER['HTTP_REFERER'], 'success', lang('hd_lang.department_deleted'));
				return redirect()->to('settings/departments');
            } else {
                $db->table('hd_departments')->where('deptid', $request->getPost('deptid'))->update($request->getPost());
               // Applib::go_to($_SERVER['HTTP_REFERER'], 'success', lang('hd_lang.department_updated'));
				return redirect()->to('settings/departments');
            }
        } else {
            $data['deptid'] = $deptid;
            $data['dept_info'] = $db->table('hd_departments')->where(array('deptid' => $deptid))->get()->getResult();
			// echo "<pre>";print_r($data);die;
            return view('modules/settings/modal_edit_department', $data);
        }
    }



    function translations($action = null, $lang = null)
    {
		// echo $action;
		// echo "<br>";
        // echo $lang;die;
        // echo 2433;die;

        $uri = service('uri');
        $action = $uri->getSegment(3);
        $model = new Setting();

        $request = \Config\Services::request();
		
		$db = \Config\Database::connect();

        if ($request->getPost()) {
            if ($action == 'save') {
                ini_set("display_errors", "1");
                $jpost = array();
                $jsondata = json_decode(html_entity_decode($request->getPost('json')));

                // echo "<pre>";print_r($jsondata);die;
                foreach ($jsondata as $jdata) {
                    $jpost[$jdata->name] = $jdata->value;
                }
				if($jpost['_file'] == 'hd' || $jpost['_file'] == 'tank_auth')
				{
                	$jpost['_path'] = $this->language_files[$jpost['_file'] . '_lang.php'];
				}
				else
				{
					$jpost['_path'] = $this->language_files[$jpost['_file'] . '.php'];
				}
                // echo "<pre>";print_r($jpost);die;
                $data['json'] = $model->save_translation($jpost);
                echo view('modules/settings/json', isset($data) ? $data : NULL);
                return;
            }
            if ($action == 'active') {
                return $db->table('hd_languages')->where('name', $lang)->update($request->getPost());
            }
        } else {
            // echo 777;die;
            if ($action == 'add') {
                // echo 132;die;
                $uri = service('uri');
                $language = $uri->getSegment(4);
                //echo $language;die;
                $model->add_translation($language, $this->language_files);
                session()->setFlashdata('response_status', 'success');
                session()->setFlashdata('message', lang('hd_lang.translation_added_successfully'));
                return redirect()->to('settings/translations');
            }
            if ($action == 'backup') {
				$uri = service('uri');
                $language_id = $uri->segment(4);
                return $model->backup_translation($language_id, $this->language_files);
            }
            if ($action == 'restore') {
                $language = $this->uri->segment(4);
                return $this->Setting->restore_translation($language, $this->language_files);
            }
            //$this->index();
            return redirect()->to('settings/translations');
        }
    }
	
	function delete_translation()
	{
		$db = \Config\Database::connect();
		
		$request = \Config\Services::request();
		
		if($request->getPost())
		{
			$lang_data = $db->table('hd_languages')->where('lang_id', $request->getPost('lang_id'))->get()->getRow();
			
			$slug = strtolower(str_replace("_", "-", $lang_data->locale));
			
			$dirpath = APPPATH . 'Language/' . $slug;
			
			helper('directory');
			
			// Check if the directory exists
			if (is_dir($dirpath)) {
				// Open the directory
				$dir = opendir($dirpath);

				// Loop through each file and subdirectory
				while (($file = readdir($dir)) !== false) {
					// Skip '.' and '..' directories
					if ($file != '.' && $file != '..') {
						// If it's a file, delete it
						if (is_file($dirpath . '/' . $file)) {
							unlink($dirpath . '/' . $file);
						}
						// If it's a directory, recursively delete it
						elseif (is_dir($dirpath . '/' . $file)) {
							// You can use a recursive function here to delete subdirectories
							// For simplicity, I'll just use a system call to remove directories
							system('rm -rf ' . escapeshellarg($dirpath . '/' . $file));
						}
					}
				}

				// Close the directory handle
				closedir($dir);

				// Now try to remove the directory itself
				if (is_dir($dirpath)) {
					rmdir($dirpath);
				}
			}
			
			$db->table('hd_languages')->where('lang_id', $request->getPost('lang_id'))->delete();
			
			return redirect()->to('settings/translations');
		}
	}


    function available_translations()
    {

        $session = \Config\Services::session();

        // Connect to the database  
        $db = \Config\Database::connect();

        $available = array();
        //$ex = App::languages();
		$ex = App::languages_locale();
       // print_r($ex);die;
        foreach ($ex as $e) {
			$existing[] = $e->name;
        }
        
        $query = $db->table('hd_locales')
            ->get();
        
        $ln = $query->getResult();

        foreach ($ln as $l) {
            //if (!in_array($l->language, $existing)) {
			if (!in_array($l->name, $existing)) {
                $available[] = $l;
            }
        }
        return $available;
    }

    public function update()
    {
        $request = \Config\Services::request();
		
        if ($request->getPost()) {
            Applib::is_demo();
            switch ($request->getPost('settings')) {
                case 'general':
                    $this->_update_general_settings($this->general_setting);
                    return redirect()->to('settings/general');
                    break;
                case 'search':
                    $this->_search_settings();
                    return redirect()->to('settings/search');
                    break;
                case 'sms':
                    $this->_sms_settings();
                    return redirect()->to('settings/sms');
                    break;
                case 'sms_templates':
                    $this->_sms_templates();
                    return redirect()->to('settings/sms_templates');
                    break;
                case 'email':
                    $this->_update_email_settings();
                    return redirect()->to('settings/email');
                    break;
                case 'payments':
                    $this->_update_payment_settings();
                    return redirect()->to('settings/payments');
                    break;
                case 'registrars':
                    $this->_update_registrar_settings();
                    return redirect()->to('settings/registrars');
                    break;
                case 'domain':
                    $this->_update_domain_settings();
                    return redirect()->to('settings/domain');
                    break;
                case 'system':
                    $this->_update_system_settings('system');
                    return redirect()->to('settings/system');
                    break;
                case 'theme':
                    if ($request->getFile('iconfile')) { 
                        $stat = $this->upload_favicon($request->getPost());
						return redirect()->to($stat);
                    }
                    if ($request->getFile('appleicon')) {
                        $this->upload_appleicon($request->getPost());
                    }
                    if ($request->getFile('logofile')) {
                        $this->upload_logo($request->getPost());
                    }
                    if ($request->getFile('loginbg')) {
                        $this->upload_login_bg($request->getPost());
                    }
                    $this->_update_theme_settings('theme');
                    return redirect()->to('settings/theme');
                    break;
                case 'crons':
                    $this->_update_cron_settings();
                    return redirect()->to('settings/crons');
                    break;
                case 'invoice':
                    if ($this->request->getFileMultiple('invoicelogo')) {
                        $this->upload_invoice_logo($this->request->getPost());
                    }
                    $this->_update_invoice_settings('invoice');
                    return redirect()->to('settings/invoice');
                    break;
                case 'departments':
                    $this->_update_departments_settings();
                    return redirect()->to('settings/departments');
                    break;
                case 'templates':
                    $this->templates();
                    return redirect()->to('settings/templates');
                    break;
            }
        } else {
			echo 132;die;
            return redirect()->to('settings'); // or wherever you want to redirect to
        }
    }

    function _update_departments_settings()
    {
        Applib::is_demo();

        $session = \Config\Services::session();

        // Connect to the database
        $db = \Config\Database::connect();

        if ($_POST) {

            $db->table('hd_departments')->insert(['deptname' => $_POST['deptname']]);

            $session->setFlashdata('response_status', 'success');
            $session->setFlashdata('message', lang('hd_lang.currency_updated_successfully'));

            $return_url = $_POST['return_url'];

            return $return_url;
        }
    }



    function _update_general_settings($setting)
    {

        Applib::is_demo();

        $session = \Config\Services::session();

        // Connect to the database
        $db = \Config\Database::connect();

        $session = session();

        // $this->load->library('form_validation');
        $validation = \Config\Services::validation();

        $validation->setRule('company_name', 'Company Name', 'required');
        $validation->setRule('company_address', 'Company Address', 'required');
        if (!$validation->withRequest($this->request)->run()) {
            $session->setFlashdata('response_status', 'error');
            $session->setFlashdata('message', lang('hd_lang.settings_update_failed'));
            $session->setFlashdata('form_error', $validation->getErrors());

            // Redirect to the previous page
            return redirect()->to('settings/' . $this->general_setting);
        } else {
            foreach ($_POST as $key => $value) {
                $data = array('value' => $value, 'categories' => '');
                // Update or insert data
                $exists = $db->table('hd_config')->where('config_key', $key)->get();

                if ($exists->getNumRows() > 0) {
                    // Update the record if it exists
                    $db->table('hd_config')->where('config_key', $key)->update(['value' => $value, 'categories' => $_POST['categories']]);
                } else {
                    // Insert the record if it doesn't exist
                    $db->table('hd_config')->insert(['config_key' => $key, 'value' => $value, 'categories' => $_POST['categories']]);
                }
            }
            // $this->session->set_flashdata('response_status', 'success');
            // $this->session->set_flashdata('message', lang('hd_lang.settings_updated_successfully'));
            $session->setFlashdata('response_status', 'error');
            $session->setFlashdata('message', lang('hd_lang.settings_update_failed'));

            $return_url = $_POST['return_url'];

            return redirect()->to('settings');
        }
    }



    function _search_settings()
    {
        Applib::is_demo();

        $session = \Config\Services::session();

        // Connect to the database
        $db = \Config\Database::connect();

        foreach ($_POST as $key => $value) {

            if (strtolower($value) == 'on') {
                $value = 'TRUE';
            } elseif (strtolower($value) == 'off') {
                $value = 'FALSE';
            }

            $exists = $db->table('hd_config')->where('config_key', $key)->get();

            if ($exists->getNumRows() > 0) {
                // Update the record if it exists
                $db->table('hd_config')->where('config_key', $key)->update(['value' => $value, 'categories' => $_POST['categories']]);
            }
        }
        $session->setFlashdata('response_status', 'success');
        $session->setFlashdata('message', lang('hd_lang.settings_updated_successfully'));
        // return redirect()->to('settings/search');

        $return_url = $_POST['return_url'];

        return $return_url;
    }


    function _update_cron_settings()
    {
        Applib::is_demo();

        $session = \Config\Services::session();
        // Connect to the database
        $db = \Config\Database::connect();

        $validation = \Config\Services::validation();

        $validation->setRule('cron_key', 'Cron Key', 'required');

        if ($validation->withRequest($this->request)->run()) {
            $session->setFlashdata('response_status', 'error');
            $session->setFlashdata('message', lang('hd_lang.settings_update_failed'));
            // redirect($_SERVER['HTTP_REFERER']);
            return redirect()->to('settings/crons');
        } else {

            // $this->load->library('encryption');
            // $this->encryption->initialize(
            //     array(
            //         'cipher' => 'aes-256',
            //         'driver' => 'openssl',
            //         'mode' => 'ctr'
            //     )
            // );
            // $_POST['mail_password'] = $this->encryption->encrypt($this->input->post('mail_password'));

            $encrypter = \Config\Services::encrypter();

            $_POST['mail_password'] = base64_encode($encrypter->encrypt($_POST('mail_password')));

            foreach ($_POST as $key => $value) {
                if (strtolower($value) == 'on') {
                    $value = 'TRUE';
                } elseif (strtolower($value) == 'off') {
                    $value = 'FALSE';
                }
                $data = array('value' => $value, 'categories' => '');

                $this->db->where('config_key', $key)->update('config', $data);
            }
            $this->session->set_flashdata('response_status', 'success');
            $this->session->set_flashdata('message', lang('hd_lang.settings_updated_successfully'));
            redirect($_SERVER['HTTP_REFERER']);
        }
    }




    function _update_system_settings($setting)
    {
        Applib::is_demo();

        $session = \Config\Services::session();

        // Connect to the database
        $dbName = \Config\Database::connect();

        $session = session();

        // $this->load->library('form_validation');
        $validation = \Config\Services::validation();

        // Set error delimiters
        $validation->setRule('file_max_size', 'File Max Size', 'required');
        $validation->setRule('other_field', 'Other Field', 'required'); // Add additional rules as needed
        if ($validation->withRequest($this->request)->run()) {
            // Set flashdata for error messages
            $session->setFlashdata('response_status', 'error');
            $session->setFlashdata('message', lang('hd_lang.settings_update_failed'));
            $session->setFlashdata('form_error', $validation->getErrors());

            // Redirect to the previous page
            return redirect()->back();
        } else {
            foreach ($_POST as $key => $value) {
                if (strtolower($value) == 'on') {
                    $value = 'TRUE';
                } elseif (strtolower($value) == 'off') {
                    $value = 'FALSE';
                }
                if($_POST['default_language'])
                {
                    // echo 232;die;
              $locale = $dbName->table('hd_languages')->select('locale')->where('locale_name', $_POST['default_language'])->get()->getRow()->locale;

                    $data = array('value' => $locale, 'categories' => $_POST['categories']);
                    $dbName->table('hd_config')->where('config_key', 'locale')->update($data);
                }

                $custom_name = new custom_name_helper();

				$curr = $custom_name->getconfig_item('default_currency');

                $newCurrency = $_POST['default_currency'];

                $model = new Item_pricing();

                // Fetch all rows
                $items = $model->findAll();

                foreach ($items as $item) {
                    $currencyAmt = json_decode($item['currency_amt'], true);

                    if(!empty($currencyAmt))
                    {
                        // Remove the first key-value pair
                        $firstKey = array_key_first($currencyAmt);
                        $firstValue = $currencyAmt[$firstKey];
                        unset($currencyAmt[$firstKey]);

                        // Add the new currency key with the first value data
                        $currencyAmt = array_merge([$newCurrency => $firstValue], $currencyAmt);

                        // Encode back to JSON
                        $item['currency_amt'] = json_encode($currencyAmt);

                        // Update the record
                        $model->update($item['item_pricing_id'], $item);
                    }
                }

                $data = array('value' => $value, 'categories' => $_POST['categories']);
				//print_r($data);die;
                $dbName->table('hd_config')->where('config_key', $key)->update($data);
            }

            //Set date format for date picker
            switch ($_POST['date_format']) {
                case "%d-%m-%Y":
                    $picker = "dd-mm-yyyy";
                    $phptime = "d-m-Y";
                    break;
                case "%m-%d-%Y":
                    $picker = "mm-dd-yyyy";
                    $phptime = "m-d-Y";
                    break;
                case "%Y-%m-%d":
                    $picker = "yyyy-mm-dd";
                    $phptime = "Y-m-d";
                    break;
                case "%d.%m.%Y":
                    $picker = "dd.mm.yyyy";
                    $phptime = "d.m.Y";
                    break;
                case "%m.%d.%Y":
                    $picker = "mm.dd.yyyy";
                    $phptime = "m.d.Y";
                    break;
                case "%Y.%m.%d":
                    $picker = "yyyy.mm.dd";
                    $phptime = "Y.m.d";
                    break;
            }
            // $this->db->where('config_key', 'date_picker_format')->update('config', array("value" => $picker));
            // $this->db->where('config_key', 'date_php_format')->update('config', array("value" => $phptime));

            $dbName->table('hd_config')->where('config_key', 'date_picker_format')->update(array("value" => $picker));
            $dbName->table('hd_config')->where('config_key', 'date_php_format')->update(array("value" => $phptime));
            $session = session();

            // $this->session->set_flashdata('response_status', 'success');
            // $this->session->set_flashdata('message', lang('hd_lang.settings_updated_successfully'));
            $session->setFlashdata('response_status', 'success');
            $session->setFlashdata('message', lang('hd_lang.settings_updated_successfully'));

            $return_url = $_POST['return_url'];

            return $return_url;
        }
    }



    function _update_theme_settings($setting)
    {
        Applib::is_demo();

        $session = \Config\Services::session();

        // Connect to the database
        $db = \Config\Database::connect();
		
		//echo "<pre>";print_r($_POST);die;

        foreach ($_POST as $key => $value) {
            if (strtolower($value) == 'on') {
                $value = 'TRUE';
            } elseif (strtolower($value) == 'off') {
                $value = 'FALSE';
            }
            $db->table('hd_config')->where('config_key', $key)->update(array('value' => $value, 'categories' => $_POST['categories']));
        }
        $session->setFlashdata('response_status', 'success');
        $session->setFlashdata('message', lang('hd_lang.settings_updated_successfully'));
        //return redirect()->to('settings/theme');
        $return_url = base_url() . 'settings/theme';

        return $return_url;
    }

    function _update_invoice_settings($setting)
    {
        Applib::is_demo();

        $session = \Config\Services::session();

        // Connect to the database
        $db = \Config\Database::connect();

        $validation = \Config\Services::validation();

        $validation->setRule('invoice_color', 'Invoice Color', 'required');

        if (!$validation->withRequest($this->request)->run()) {
            $session->setFlashdata('response_status', 'error');
            $session->setFlashdata('message', lang('hd_lang.settings_update_failed'));
            // redirect('settings/' . $this->invoice_setting);
            $return_url = $_POST['return_url'];

            return $return_url;
        } else {
            foreach ($_POST as $key => $value) {

                if (strtolower($value) == 'on') {
                    $value = 'TRUE';
                } elseif (strtolower($value) == 'off') {
                    $value = 'FALSE';
                }

                $exists = $db->table('hd_config')->where('config_key', $key)->get();
                if ($exists->getNumRows() > 0) {
                    // Update the record if it exists
                    $db->table('hd_config')->where('config_key', $key)->update(['value' => $_POST[$key]]);
                } else {
                    // Insert the record if it doesn't exist
                    $db->table('hd_config')->insert(['config_key' => $key, 'value' => $_POST[$key]]);
                }

                if ($key == 'invoice_logo_height' && $this->invoice_logo_height > 0) {
                    $value = $this->invoice_logo_height;
                }
                if ($key == 'invoice_logo_width' && $this->invoice_logo_width > 0) {
                    $value = $this->invoice_logo_width;
                }
                $data = array('value' => $value, 'categories' => $_POST['categories']);

                $jhkhkj = $db->table('hd_config')->where('config_key', $key)->update($data);
            }
            $session->setFlashdata('response_status', 'success');
            $session->setFlashdata('message', lang('hd_lang.settings_updated_successfully'));

            $return_url = $_POST['return_url'];
            return $return_url;
        }
    }



    function _update_email_settings()
    {
        Applib::is_demo();

        $session = \Config\Services::session();

        // Connect to the database
        $dbName = \Config\Database::connect();

        $validation = \Config\Services::validation();
        $encryption = \Config\Services::encryption();

        // Set error delimiters
        $validation->setRule('settings', 'Settings', 'required');
        $validation->setRule('other_field', 'Other Field', 'required'); // Add additional rules as needed

        if ($validation->withRequest($this->request)->run()) {
            $session = session();

            // Set flashdata for error messages
            $session->setFlashdata('response_status', 'error');
            $session->setFlashdata('form_error', $validation->getErrors());
            $session->setFlashdata('message', lang('hd_lang.settings_update_failed'));

            // Redirect to the previous page
            $return_url = $_POST['return_url'];

            return $return_url;
        } else {

            foreach ($_POST as $key => $value) {
                if (strtolower($value) == 'on') {
                    $value = 'TRUE';
                } elseif (strtolower($value) == 'off') {
                    $value = 'FALSE';
                }

                $app = new App();

                $data = array('value' => $value, 'categories' => $_POST['categories']);

                $dbName->table('hd_config')->where('config_key', $key)->update($data);
            }
            if (isset($_POST['smtp_pass'])) {
                $raw_smtp_pass = $_POST['smtp_pass'];
                $smtp_pass = md5($raw_smtp_pass);
                $data = array('value' => $smtp_pass);
                $dbName->table('hd_config')->where('config_key', 'smtp_pass')->update($data);
            }

            if (isset($_POST['mail_password'])) {
                $raw_mail_pass = $_POST['mail_password'];
                $mail_pass = md5($raw_mail_pass);
                $data = array('value' => $mail_pass);
                // $app->update('config', array('config_key' => 'mail_password'), $data);
                $dbName->table('hd_config')->where('config_key', 'mail_password')->update($data);
            }
            // $this->session->set_flashdata('response_status', 'success');
            // $this->session->set_flashdata('message', lang('hd_lang.settings_updated_successfully'));
            // redirect($_SERVER['HTTP_REFERER']);
            $return_url = $_POST['return_url'];

            return $return_url;
        }
    }




    function _sms_settings()
    {
        Applib::is_demo();

        $session = \Config\Services::session();

        // Connect to the database
        $dbName = \Config\Database::connect();

        $validation = \Config\Services::validation();

        // Set error delimiters
        // $validation->setFormatter('list', '<span class="text-danger">', '</span><br>');

        // Set validation rules
        $validation->setRules([
            'settings' => 'required',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            $session = session();

            $session->setFlashdata('response_status', 'error');
            $session->setFlashdata('form_error', validation_errors());
            $session->setFlashdata('message', lang('hd_lang.settings_update_failed'));
            return redirect()->to('settings');
        } else {

            foreach ($_POST as $key => $value) {
                if (strtolower($value) == 'on') {
                    $value = 'TRUE';
                } elseif (strtolower($value) == 'off') {
                    $value = 'FALSE';
                }

                $data = array('value' => $value, 'categories' => $_POST['categories']);
                // App::update('config', array('config_key' => $key), $data);
                $dbName->table('hd_config')->where('config_key', $key)->update($data);
            }

            $session->setFlashdata('response_status', 'success');
            $session->setFlashdata('message', lang('hd_lang.settings_updated_successfully'));
            // return redirect()->to('settings');
            $return_url = $_POST['return_url'];

            return $return_url;
        }
    }



    function send_test()
    {
        Applib::is_demo();

        $request = \Config\Services::request();

        $session = \Config\Services::session();
        // Connect to the database
        $dbName = \Config\Database::connect();

        if ($request->getPost()) {
            $phone = trim($request->getPost('phone'));
            $message = $request->getPost('message');

            $whatpanel_helper = new whatpanel_helper();

            $result = $whatpanel_helper->send_sms($phone, $message);

            //$this->session->set_flashdata('response_status', 'info');
            //$this->session->set_flashdata('message', $result);
            $session->setFlashdata('response_status', 'info');
            $session->setFlashdata('message', $result);
            return redirect()->to('settings/sms');
        } else {
            //$this->load->view('modal_send_sms', array());
            return view('modules/settings/modal_send_sms', array());
        }
    }



    function _update_payment_settings()
    {
        if ($this->input->post()) {
            Applib::is_demo();



            foreach ($_POST as $key => $value) {
                if (strtolower($value) == 'on') {
                    $value = 'TRUE';
                } elseif (strtolower($value) == 'off') {
                    $value = 'FALSE';
                }

                $data = array('value' => $value, 'categories' => '');
                $this->db->where('config_key', $key)->update('config', $data);
            }


            $this->session->set_flashdata('response_status', 'success');
            $this->session->set_flashdata('message', lang('hd_lang.settings_updated_successfully'));
            redirect('settings/?settings=payments');
        } else {
            $this->session->set_flashdata('response_status', 'error');
            $this->session->set_flashdata('message', lang('hd_lang.settings_update_failed'));
            redirect('settings/?settings=payments');
        }
    }



    function _update_domain_settings()
    {
        $request = \Config\Services::request();

        $session = \Config\Services::session();

        // Connect to the database
        $db = \Config\Database::connect();

        if ($request->getPost()) {
            Applib::is_demo();

            foreach ($_POST as $key => $value) {
                if (strtolower($value) == 'on') {
                    $value = 'TRUE';
                } elseif (strtolower($value) == 'off') {
                    $value = 'FALSE';
                }

                $data = array('value' => $value, 'categories' => $_POST['categories']);
                $db->table('hd_config')->where('config_key', $key)->update($data);
            }


            // $this->session->set_flashdata('response_status', 'success');
            // $this->session->set_flashdata('message', lang('hd_lang.settings_updated_successfully'));
            // redirect('settings/?settings=domain');
            $session->setFlashdata('response_status', 'success');
            $session->setFlashdata('message', lang('hd_lang.settings_updated_successfully'));

            $return_url = $_POST['return_url'];

            return $return_url;
        } else {
            $session->setFlashdata('response_status', 'error');
            $session->setFlashdata('message', lang('hd_lang.settings_update_failed'));

            $return_url = $_POST['return_url'];

            return $return_url;
        }
    }




    function _update_registrar_settings()
    {
        if ($this->input->post()) {
            Applib::is_demo();

            foreach ($_POST as $key => $value) {
                if (strtolower($value) == 'on') {
                    $value = 'TRUE';
                } elseif (strtolower($value) == 'off') {
                    $value = 'FALSE';
                }

                $data = array('value' => $value, 'categories' => '');
                $this->db->where('config_key', $key)->update('config', $data);
            }


            session()->setFlashdata('response_status', 'success');
            session()->setFlashdata('message', lang('hd_lang.settings_updated_successfully'));
            redirect('registrars');
        } else {
            session()->setFlashdata('response_status', 'error');
            session()->setFlashdata('message', lang('hd_lang.settings_update_failed'));
            redirect('registrars');
        }
    }




    function update_email_templates()
    {
        if ($this->input->post()) {
            Applib::is_demo();

            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span><br>');
            $this->form_validation->set_rules('email_invoice_message', 'Invoice Message', 'required');
            $this->form_validation->set_rules('reminder_message', 'Reminder Message', 'required');
            if ($this->form_validation->run() == FALSE) {
                session()->setFlashdata('response_status', 'error');
                session()->setFlashdata('message', lang('hd_lang.settings_update_failed'));
                $_POST = '';
                $this->update('email');
            } else {
                foreach ($_POST as $key => $value) {
                    $data = array('value' => $value, 'categories' => '');
                    $this->db->where('config_key', $key)->update('config', $data);
                }

                session()->setFlashdata('response_status', 'success');
                session()->setFlashdata('message', lang('hd_lang.settings_updated_successfully'));
                redirect('settings/update/email');
            }
        } else {
            session()->setFlashdata('response_status', 'error');
            session()->setFlashdata('message', lang('hd_lang.settings_update_failed'));
            redirect('settings/update/email');
        }
    }



    function upload_favicon()
    {
        Applib::is_demo();

        $request = \Config\Services::request();

        $session = \Config\Services::session();

		$uploadPath = FCPATH . 'uploads/files';

        // Connect to the database
        $db = \Config\Database::connect();

        // Get the uploaded file(s)
        $file = $request->getFile('iconfile');

        // Validate the uploaded file
        $validationRules = [
            'iconfile' => [
                'rules' => 'uploaded[iconfile]|is_image[iconfile]|ext_in[iconfile,jpg,jpeg,png,ico]|max_size[iconfile,1024]|max_dims[iconfile,300,300]',
                'errors' => [
                    'uploaded' => 'No file was uploaded.',
                    'is_image' => 'The file is not a valid image.',
                    'ext_in' => 'Invalid file extension.',
                    'max_size' => 'File size exceeds the limit of 1MB.',
                    'max_dims' => 'Image dimensions exceed the limit of 300x300 pixels.'
                ]
            ]
        ];

        if ($this->validate($validationRules)) {
            // Move the uploaded file to the desired directory
            $newName = $file->getRandomName();
           
            // Use the move method to upload the file to the specified folder
            $file->move($uploadPath, $newName);

            // File path to display preview
            $filepath = base_url("uploads/files/{$newName}");

            $data = ['value' => $newName, 'categories' => $_POST['categories']];

            // Assuming $db is an instance of the database class
            $db->table('hd_config')->where('config_key', 'site_favicon')->update($data);

		    return $_POST['return_url'];

            //return TRUE;
        }
    }


    function upload_appleicon($files)
    {
        Applib::is_demo();

        $request = \Config\Services::request();

        $session = \Config\Services::session();




        // Modify the 'default' property

        // Connect to the database
        $db = \Config\Database::connect();

        // Get the uploaded file(s)
        $file = $request->getFile('appleicon');

        // Specify the absolute path to the target directory
        $uploadPath = base_url() . 'uploads/files';

        // Validate the uploaded file
        $validationRules = [
            'appleicon' => 'uploaded[appleicon]|max_size[appleicon,1024]|is_image[appleicon]|ext_in[appleicon,jpg,jpeg,png,ico,svg]',
        ];

        if ($this->validate($validationRules)) {
            // Move the uploaded file to the desired directory
            $newName = $file->getRandomName();

            // Use the move method to upload the file to the specified folder
            $file->move('./uploads/files/', $newName);

            // File path to display preview
            $filepath = base_url("uploads/files/{$newName}");

            $data = ['value' => $newName, 'categories' => $_POST['categories']];

            // Assuming $db is an instance of the database class
            $db->table('hd_config')->where('config_key', 'site_appleicon')->update($data);

            return TRUE;
        }
    }



    function upload_logo($files)
    {
        Applib::is_demo();

        $request = \Config\Services::request();

        $session = \Config\Services::session();
		
        // Connect to the database
        $db = \Config\Database::connect();

        // Get the uploaded file(s)
        $file = $request->getFile('logofile');

        // Specify the absolute path to the target directory
        $uploadPath = FCPATH . 'uploads/files';
		
		$validation = \Config\Services::validation();

        // Define validation rules
        $validationRules = [
            'logofile' => [
                'label' => 'Logo File',
                'rules' => 'uploaded[logofile]|max_size[logofile,1024]|is_image[logofile]|ext_in[logofile,jpg,jpeg,png,ico,svg]',
                'errors' => [
                    'uploaded' => 'No file was uploaded.',
                    'max_size' => 'File size cannot exceed 1MB.',
                    'is_image' => 'The file must be a valid image.',
                    'ext_in' => 'Allowed file types: jpg, jpeg, png, ico, svg.',
                ],
            ],
        ];

        //if ($this->validate($validationRules)) {
			// echo 132;die;
            // Move the uploaded file to the desired directory
            $newName = $file->getRandomName();

            // Use the move method to upload the file to the specified folder
            //$file->move('./uploads/files/', $newName);
			$file->move($uploadPath, $newName);

            // File path to display preview
            $filepath = base_url("uploads/files/{$newName}");

            $data = ['value' => $newName, 'categories' => $_POST['categories']];

            // Assuming $db is an instance of the database class
            $db->table('hd_config')->where('config_key', 'company_logo')->update($data);

            return TRUE;
      // }
    }




    function upload_login_bg($files)
    {
        Applib::is_demo();

        $request = \Config\Services::request();

        $session = \Config\Services::session();

        // Connect to the database
        $db = \Config\Database::connect();

        // Get the uploaded file(s)
        $file = $request->getFile('loginbg');

        // Specify the absolute path to the target directory
        $uploadPath = base_url() . 'uploads/files';

        // Validate the uploaded file
        $validationRules = [
            'loginbg' => 'uploaded[loginbg]|max_size[loginbg,1024]|is_image[loginbg]|ext_in[loginbg,jpg,jpeg,png,ico,svg]',
        ];

        if ($this->validate($validationRules)) {
            // Move the uploaded file to the desired directory
            $newName = $file->getRandomName();

            // Use the move method to upload the file to the specified folder
            $file->move('./uploads/files/', $newName);

            // File path to display preview
            $filepath = base_url("uploads/files/{$newName}");

            $data = ['value' => $newName, 'categories' => $_POST['categories']];

            // Assuming $db is an instance of the database class
            $db->table('hd_config')->where('config_key', 'login_bg')->update($data);

            return TRUE;
        }
    }




    function upload_invoice_logo($files = null)
    {
        Applib::is_demo();

        $request = \Config\Services::request();

        $session = \Config\Services::session();

        // Connect to the database
        $db = \Config\Database::connect();

        // Get the uploaded file(s)
        $file = $request->getFile('invoicelogo');
        // Specify the absolute path to the target directory
        $uploadPath = base_url() . 'uploads/files';

        // Validate the uploaded file
        $validationRules = [
            'invoicelogo' => 'uploaded[invoicelogo]|max_size[invoicelogo,1024]|is_image[invoicelogo]|ext_in[invoicelogo,jpg,jpeg,png,ico,svg]',
        ];

        if ($this->validate($validationRules)) {
            // Move the uploaded file to the desired directory
            $newName = $file->getRandomName();

            // Use the move method to upload the file to the specified folder
            $file->move('./uploads/files/', $newName);

            // File path to display preview
            $filepath = base_url("uploads/files/{$newName}");

            $data = ['value' => $newName, 'categories' => $_POST['categories']];

            // Assuming $db is an instance of the database class
            $db->table('hd_config')->where('config_key', 'invoice_logo')->update($data);

            return TRUE;
        }
    }


    function _GenerateUniqueFieldValue()
    {
        $uniqid = uniqid('f');
        // Id should start with an character other than digit

        $this->db->where('uniqid', $uniqid)->get('fields');

        if ($this->db->affected_rows() > 0) {
            $this->GetUniqueFieldValue();
            // Recursion
        } else {
            return $uniqid;
        }
    }


   // function database()
   // {
        //     Applib::is_demo();
        //  //   $file_helper = new file_helper();
        //     $prefs = array(
        //         'format' => 'zip',             // gzip, zip, txt
        //         'filename' => 'database-full-backup_' . date('Y-m-d') . '.zip',
        //         'add_drop' => TRUE,              // Whether to add DROP TABLE statements to backup file
        //         'add_insert' => TRUE,              // Whether to add INSERT data to backup file
        //         'newline' => "\n"               // Newline character used in backup file
        //     );
        //     $backup = \Config\Database::utils()->backup($prefs);

        //     if ( ! write_file('resource/backup/database-full-backup_'.date('Y-m-d').'.zip', $backup))
        //     {
        //         $this->session->set_flashdata('response_status', 'error');
        //         $this->session->set_flashdata('message', 'The resource/backup folder is not writable.');
        //         redirect($_SERVER['HTTP_REFERER']);
        //     }
        //     $download_helper = new download_helper();
        //     $download_helper->force_download(ROOTPATH . 'resource/backup/database-full-backup_'.date('Y-m-d').'.zip', $backup);
    //}

	 function database()
    { 	
        Applib::is_demo();
        // Load necessary helpers and services
        $helper = new download_helper();
        $db = db_connect();
        // Load database utilities
        $dbutil = new MyUtils($db);
        
        // Preferences for backup
        $prefs = [
            'format'      => 'zip',             // gzip, zip, txt
            'filename'    => 'database-full-backup_' . date('Y-m-d') . '.zip',
            'add_drop'    => true,              // Whether to add DROP TABLE statements to backup file
            'add_insert'  => true,              // Whether to add INSERT data to backup file
            'newline'     => "\n"               // Newline character used in backup file
        ];
        // Backup the database
        $backup = $dbutil->backup($prefs);
        die;
       // print_r(WRITEPATH . 'backup/' . $prefs['filename']);die;
        // Download the backup file
        return $helper->force_download(WRITEPATH . 'backup/' . $prefs['filename'], $backup);
    }


    function skin()
    {
        $request = \Config\Services::request();

        $session = \Config\Services::session();

        // Connect to the database
        $db = \Config\Database::connect();

        $skin = $request->getPost('skin');
        $categories = $request->getPost('categories');

        return $db->table('hd_config')->where('config_key', 'top_bar_color')->update(array('value' => $skin, 'categories' => $categories));
    }

    function hook($action, $item)
    {
        // print_r($action);die;
        $request = \Config\Services::request();

        $session = \Config\Services::session();

        // Connect to the database  
        $db = \Config\Database::connect();

        switch ($action) {
            case "visible":
                $role = $request->getPost('access');
                $visible = $request->getPost('visible');
                return $db->table('hd_hooks')->where('module', $item)->where('access', $role)->update(array('visible' => $visible));
            case "enabled":
                $role = $request->getPost('access');
                $enabled = $request->getPost('enabled');
                return $db->table('hd_hooks')->where('module', $item)->where('access', $role)->update(array('enabled' => $enabled));
            case "icon":
                $role = $request->getPost('access');
                $icon = $request->getPost('icon');
                return $db->table('hd_hooks')->where('module', $item)->where('access', $role)->update(array('icon' => $icon));
            case "reorder":
                $items = $request->getPost('json', TRUE);
                $items = json_decode($items);
                foreach ($items[0] as $i => $mod) {
                    $db->table('hd_hooks')->where('module', $mod->module)->where('access', $mod->access)->update(array('order' => $i + 1));
                }
                return TRUE;
        }
        return false;
    }
}

/* End of file settings.php */