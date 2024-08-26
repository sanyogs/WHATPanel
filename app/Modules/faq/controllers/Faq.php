<?php
 /*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

namespace app\Modules\faq\controllers;
use App\Models\FAQS;
use App\Models\User;
use App\ThirdParty\MX\WhatPanel;
use App\Modules\Layouts\Libraries\Template;
use App\Helpers\custom_name_helper;
use App\Modules\Layouts\Controllers\Layouts;
use CodeIgniter\Controller;

class Faq extends Controller
{

    public function __construct()
	{	
	  $template = new Template();
	  $helper = new custom_name_helper();
	  $model = new FAQS();
	  $module = new Layouts();
      parent::__construct();
        User::logged_in();
        $template->set_theme($helper->getconfig_item('active_theme'));
        $template->set_partial('header', 'sections/header');
        $template->set_partial('footer', 'sections/footer');
    }


    function index()
    { 	
		$template = new Template();
		$helper = new custom_name_helper();
        $template->title(lang('hd_lang.faq').' | '.$helper->getconfig_item('company_name'));      
        $template->set_metadata('description', $helper->getconfig_item('site_description'));
        $data['page'] = lang('hd_lang.frequently_asked_questions'); 
        $data['categories'] = FAQS::categories(); 
        $data['articles'] = FAQS::articles();
       // $this->template->set_layout('main')->build('pages/faq', isset($data) ? $data : NULL);
		echo view('pages/faq', isset($data) ? $data : NULL);
    }


    function category($name)
    { 
	  $template = new Template();
      $name = str_replace("_", " ", $name);
      $template->title($name);
      $data['page'] = ucfirst(ucfirst($name)); 
      $data['categories'] = FAQS::categories(); 
      $data['articles'] = FAQS::category($name);
      echo view('pages/faq', isset($data) ? $data : NULL);
    }
}