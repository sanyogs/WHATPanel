<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */
/* Module Name: CustomerPrice
 * Module URI: https://whatpanel.com
 * Version: 1.0
 * Category: ResellerClub Customer Payment
 * Description: ResellerClub Customer Payment.
 * Author:  WHAT PANEL 
 * Author URI: https://whatpanel.com
 */

namespace Modules\customerprice\controllers;

use App\Controllers\APIController;
use App\ThirdParty\MX\WhatPanel;

use App\Models\Invoice;
use App\Models\App;
use App\Models\Client;

use App\Libraries\AppLib;

use App\Modules\Layouts\Libraries\Template;

use App\Helpers\custom_name_helper;

use GuzzleHttp\Client as GuzzleClient;

class CustomerPrice extends WhatPanel
{

    private $api_key;

    function __construct()
    {
        // 	parent::__construct();		
        //     User::logged_in();

        //     $this->config = get_settings('razorpay');
        //     if(!empty($this->config))
        //     {
        //         $this->api_key = $this->config['api_key']; 
        //    }
    }

    function cancel()
    {
        $this->session->set_flashdata('response_status', 'error');
        $this->session->set_flashdata('message', 'Payfast Payment Cancelled!');
        redirect('clients');
    }

    public function activate($data)
    {
        return true;
    }


    public function install()
    {
        return true;
    }


    public function uninstall()
    {
        return true;
    }


    public function customerprice_config($values = null)
    {
        $config = array(
            array(
                'label' => lang('hd_lang.customerpay_api_key'),
                'id' => 'api_key',
                'value' => isset($values) ? $values['api_key'] : '',
                'type' => ''
            ),
            array(
                'label' => lang('hd_lang.customerpay_api_secret'),
                'id' => 'secret_key',
                'value' => isset($values) ? $values['secret_key'] : '',
                'type' => ''
            ),
            array(
                'label' => lang('hd_lang.customerpay_mode'),
                'id' => 'customerpay_mode',
                'value' => isset($values) ? $values['customerpay_mode'] : '',
                'type' => 'radio',
                'radio_options' => array(
                    array(
                        'id' => 'live',
                        'class' =>  'API',
                        'label' => 'API',
                        'value' => 'API',
                        'checked' => false, // Set to true if this option should be initially checked
                    ),
                    array(
                        'id' => 'test',
                        'class' =>  'Manual',
                        'label' => 'Manual',
                        'value' => 'Manual',
                        'checked' => false,
                    )
                ),
            )
        );

        return $config;
    }


    public function api_customerprice()
    {   
        $db = \Config\Database::connect();

        $request = $db->table('hd_plugins')->select('config')->where('system_name', 'customerprice')->get()->getRow()->config;

        $results = json_decode($request);

        $percentage = $results->percentage;

        $query = $db->table('hd_customer_pricing')
        ->select('cost')
        ->where('service_type', 'addnewdomain')
        ->get()
        ->getResultArray();
                    
        //echo"<pre>";print_r($query);die;
        //$costs = array_column($query,'cost');
        //echo"<pre>";print_r($costs);die;

        foreach($query as $cost)
        {
            $results = $percentage/100*$cost['cost'];
            $final = $results+$cost['cost'];
        }
        
        return $final;
        
    }
}
