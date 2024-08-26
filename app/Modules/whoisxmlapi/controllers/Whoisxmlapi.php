<?php
 /*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

namespace App\Modules\whoisxmlapi\controllers;

use App\ThirdParty\MX\WhatPanel;
use \App\Libraries;
use App\Models\App;
use App\Models\User;

class Whoisxmlapi extends WhatPanel
{
    private $key;

    function __construct()
    {
        // parent::__construct(); 
        //  $this->config = get_settings('whoisxmlapi');
        //  if(!empty($this->config))
        //  {    
        //   $this->key = $this->config['api_key']; 
        // }    
        parent::__construct();
        User::logged_in();

        // Load the App model
        $this->appModel = new App(); // Adjust the model name and namespace as needed
        // Get the WHOISXMLAPI key from the configuration
        $this->key = config_item('whoisxmlapi_key');
     }
 
 
     public function whoisxmlapi_config($values = null)
     {
         $config = array( 
             array(
                 'label' => lang('whoisxmlapi_key'), 
                 'id' => 'api_key',
                 'value' => isset($values) ? $values['api_key'] : '',
                 'type' => ''
             ) 
         ); 
         
         return $config;        
     } 

 
    public function check_domain ($sld, $tld)
    {   
        // echo 3;die;
        $apiKey = 'XHkwYQZ37rYpL56ijS0CaMN1P0QjpJqq'; // Replace with your actual API key

        $domain = $sld . '.' . $tld;
        $apiUrl = 'https://domain-availability-api.whoisxmlapi.com/api/v1';
        // print_r($apiUrl);die;
        $params = [
            'apiKey' => $apiKey,
            'domainName' => $domain,
            'mode' => 'DNS_AND_WHOIS',
        ];

        $url = $apiUrl . '?' . http_build_query($params);
        // $res = json_decode(file_get_contents($url), true);
        // print_r($res);die;

        if (isset($url['DomainInfo']['domainName']) && $url['DomainInfo']['domainName'] == $domain) {
            if ($url['DomainInfo']['domainAvailability'] == 'UNAVAILABLE') {
                return 0;
            } elseif ($url['DomainInfo']['domainAvailability'] == 'AVAILABLE') {
                return 1;
            }
        }

        // Handle the case when the response structure is not as expected
        return -1;
    
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

    
}