<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

 

namespace App\ThirdParty\MX;

use CodeIgniter\Controller;

use App\Models\Addon;
use App\Models\App;
use App\Models\Block;
use App\Models\Client;
use App\Models\Domain;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\Menu;
use App\Models\Order;
use App\Models\Page;
use App\Models\Payment;
use App\Models\Report;
use App\Models\Ticket;
use App\Models\User;
use CodeIgniter\Config\Services;

use App\Helpers\custom_name_helper;

class WhatPanel extends Controller
{
    protected $session;
    protected $config;
    protected $userModel;
    protected $ticketModel;
    protected $orderModel;
    protected $clientModel;
    protected $appModel;
    protected $domainModel;
    protected $itemModel;
    protected $invoiceModel;
    protected $pageModel;
    protected $blockModel;
    protected $menuModel;
    protected $paymentModel;
    protected $reportModel;
    protected $addonModel;

    public function __construct()
    {
        $this->session = Services::session();

        // $localization = Services::localization();

        //$this->config = config('app'); // For the app.php file

        $request = service('request');

        $session = \Config\Services::session();
        
		$helper = new custom_name_helper();  

        // Connect to the database  
        $db = \Config\Database::connect();
		
        $this->userModel = new User($db);
        $this->ticketModel = new Ticket($db);
        $this->orderModel = new Order($db);
        $this->clientModel = new Client($db);
        $this->appModel = new App($db);
        $this->domainModel = new Domain($db);
        $this->itemModel = new Item($db);
        $this->invoiceModel = new Invoice($db);
        $this->pageModel = new Page($db);
        $this->blockModel = new Block($db);
        $this->menuModel = new Menu($db);
        $this->paymentModel = new Payment($db);
        $this->reportModel = new Report($db);
        $this->addonModel = new Addon($db);
		
		// $lang = "";

        //$lang = $helper->getconfig_item('default_language');
        //if ($request->getCookie('fo_lang')) {
        //    $lang = $request->getCookie('fo_lang');
        //}
		
        //if ($session->get('lang')) {
            //$lang = $session->get('lang');
        //}

        // if ($localization !== null) { // Check if $localization is not null
        //     $localization->setLocale($lang);
        // } else {
        //     // Handle the case where $localization is null
        //     // You might want to log an error or use a default locale
        // }


        // service('locale')->setLocale($locale);
    }
}