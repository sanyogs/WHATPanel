<?php
 /*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

namespace App\Modules\Dashboard\controllers;

use App\Controllers\BaseController;
use App\Libraries\AppLib;
use App\Models\App;
use App\Models\Invoice;
use App\Models\User;
use App\ThirdParty\MX\WhatPanel;
use Users;
use App\Modules\Layouts\Libraries\Template;
use CodeIgniter\Controller;
use Config\Database;

class DashboardController extends Controller
{
    protected $template;

    function __construct()
    {   
		error_reporting(E_ALL);
        ini_set("display_errors", "1");
        // parent::__construct();
        
        // User::logged_in();
   
        // if (User::is_client()) {
        //     redirect('clients');
        // }
        // if(isset($_GET['setyear'])){ $this->session->set_userdata('chart_year', $_GET['setyear']); }
        // if(isset($_GET['chart'])){ $this->session->set_userdata('chart', $_GET['chart']); }

        // $lang = config_item('default_language');
        // if (isset($_COOKIE['fo_lang'])) { $lang = $_COOKIE['fo_lang']; }
        // if ($this->session->userdata('lang')) { $lang = $this->session->userdata('lang'); }
        // $this->lang->load('hd', $lang);
    }

    function index()
    {	
		error_reporting(E_ALL);
        ini_set("display_errors", "1");

        $session = \Config\Services::session();

        //echo "<pre>";print_r($session->get());die;
        // $template = new Template();
        //$this->template = new Template();
        // $template->someMethod();
        // $template->title(config_item('company_name'));
        $data['page'] = lang('hd_lang.dashboard');
        // $data['activities'] = App::get_activity($limit = 30);

        $data['sums'] = $this->_totals();
        $data['sums2'] = $this->_totals_per_currency();
            // if(App::counter('items',array()) == 0){
            //     $data['no_invoices'] = TRUE;
            // }
        // $this->template
        // ->set_layout('users')
        // ->build('user_home',isset($data) ? $data : NULL);

        echo view('modules/dashboard/user_home', $data);
    }
        
        
    function _totals() {
        $session = \Config\Services::session();	

        // Modify the 'default' property	
        	

        // Connect to the database	
        $db = \Config\Database::connect();

        $invoiceModel = new \App\Models\Invoice($db);

        $paid = $due = array();
        //$currency = config_item('default_currency');
        $currency = 'USD';
        $symbol = array();
        $paid = $due = 0;
        foreach($invoiceModel->get() as $inv) {
            $paid_amount = $invoiceModel::get_invoice_paid($inv->inv_id ?? 0);
            $due_amount = $invoiceModel::get_invoice_due_amount($inv->inv_id ?? 0);
            // if ($inv->currency != $currency) {
            //     $paid_amount = AppLib::convert_currency($inv->currency, $paid_amount);
            //     $due_amount = AppLib::convert_currency($inv->currency, $due_amount);
            // }
            $paid += $paid_amount;
            $due += $due_amount;
        }
        return array("paid"=>$paid, "due"=>$due);
    
    }
    
    function _totals_per_currency() {

        $session = \Config\Services::session();	

        // Modify the 'default' property	
        	
        // Connect to the database	
        $db = \Config\Database::connect();

        $invoiceModel = new \App\Models\Invoice($db);

        $paid = $due = array();
        foreach($invoiceModel->get() as $inv) {
            $paid_amount = $invoiceModel::get_invoice_paid($inv->inv_id ?? 0);
            $due_amount = $invoiceModel::get_invoice_due_amount($inv->inv_id ?? 0);
            // if (!isset($paid[$inv->currency])) { $paid[$inv->currency] = 0; }
            // if (!isset($due[$inv->currency])) { $due[$inv->currency] = 0; }
            // $paid[$inv->currency] += $paid_amount;
            // $due[$inv->currency] += $due_amount;
        }
        return array("paid"=>$paid, "due"=>$due);
    
    }
}