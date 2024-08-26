<?php 

/* Module Name: Resellerclub
 * Module URI: https://whatpanel.com
 * Version: 1.0
 * Category: Domain Registrar
 * Description: Resellerclub API Integration.
 * Author:  WHAT PANEL 
 * Author URI: https://whatpanel.com
 */

/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

namespace Modules\resellerclub\controllers;

use App\ThirdParty\MX\WhatPanel;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class Resellerclub extends WhatPanel
{
    public function resellerclub_config($values = null, $config = null)
    {	
        $configur = array(
            'form_id' => 'resellerForm', // Dynamic form ID
            'fields' => array(
                array(
                    'id' => 'id',
                    'type' => 'hidden',
                    'value' => $config->plugin_id
                ),
                array(
                    'id' => 'system_name',
                    'type' => 'hidden',
                    'value' => $config->system_name
                ),
                array(
                    'label' => lang('hd_lang.resellerclub_resellerid'),
                    'id' => 'auth_id',
                    'value' => isset($values) ? $values['auth_id'] : '',
                    'type' => '',
                    'class' => ''
                ),
                array(
                    'label' => lang('hd_lang.resellerclub_apikey'),
                    'id' => 'api_key',
                    'value' => isset($values) ? $values['api_key'] : '',
                    'type' => '',
                    'class' => ''
                ),
                array(
                    'label' => lang('hd_lang.customerpay_mode'),
                    'id' => 'customerpay_mode',
                    'value' => isset($values) ? $values['customerpay_mode'] : '',
                    'type' => 'radio',
                    'class' => '',
                    'radio_options' => array(
                        array(
                            'id' => 'live',
                            'class' => 'API',
                            'label' => 'API',
                            'value' => 'API',
                            'checked' => false,
                        ),
                        array(
                            'id' => 'test',
                            'class' => 'Manual',
                            'label' => 'Manual',
                            'value' => 'Manual',
                            'checked' => false,
                        )
                    )
                ),
                array(
                    'id' => 'submit',
                    'type' => 'submit',
                    'label' => 'Save',
                    'class' => 'common-button'
                )
            )
        );

        return $configur;
    }
	
	public function resellerclub_config_no_data($config = null)
    {	
        $configur = array(
            'form_id' => 'resellerForm', // Dynamic form ID
            'fields' => array(
                array(
                    'id' => 'id',
                    'type' => 'hidden',
                    'value' => $config->plugin_id
                ),
                array(
                    'id' => 'system_name',
                    'type' => 'hidden',
                    'value' => $config->system_name
                ),
                array(
                    'label' => lang('hd_lang.resellerclub_resellerid'),
                    'id' => 'auth_id',
                    'value' => '',
                    'type' => '',
                    'class' => ''
                ),
                array(
                    'label' => lang('hd_lang.resellerclub_apikey'),
                    'id' => 'api_key',
                    'value' => '',
                    'type' => '',
                    'class' => ''
                ),
                array(
                    'label' => lang('hd_lang.customerpay_mode'),
                    'id' => 'customerpay_mode',
                    'value' => '',
                    'type' => 'radio',
                    'class' => '',
                    'radio_options' => array(
                        array(
                            'id' => 'live',
                            'class' => 'API',
                            'label' => 'API',
                            'value' => 'API',
                            'checked' => false,
                        ),
                        array(
                            'id' => 'test',
                            'class' => 'Manual',
                            'label' => 'Manual',
                            'value' => 'Manual',
                            'checked' => false,
                        )
                    )
                ),
                array(
                    'id' => 'submit',
                    'type' => 'submit',
                    'label' => 'Save',
                    'class' => 'common-button'
                )
            )
        );

        return $configur;
    }

    public function api_customerprice($replace)
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        $db = \Config\Database::connect();

        $request = $db->table('hd_plugins')->select('config')->where('system_name', 'resellerclub')->get()->getRow()->config;
        //  print_r($request);die;
        $results = json_decode($request);
        $percentage = $results->percentage;

        $query = $db->table('hd_customer_pricing')
            ->select('cost')
            ->where('service_type', 'addnewdomain')->where('domain_name', $replace)
            ->get()
            ->getResultArray();
        //echo"<pre>";print_r($results->customerpay_mode == 'API');die;
        // echo"<pre>";print_r($query);die;
        //$costs = array_column($query,'cost');
        //echo"<pre>";print_r($costs);die;

        $final = array();

        if ($results->customerpay_mode == 'API') {
            foreach ($query as $cost) {
                $result = $percentage / 100 * $cost['cost'];
                $final[] = $result + $cost['cost'];
            }
        }
        // echo "<pre>";print_r($final);die;
        return $final;
    }
}
