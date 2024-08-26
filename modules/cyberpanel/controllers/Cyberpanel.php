<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */
/* Module Name: Cyberpanel
 * Module URI: https://whatpanel.com
 * Version: 1.0
 * Category: Servers
 * Description: Cyberpanel API Integration.
 * Author:  WHAT PANEL 
 * Author URI: https://whatpanel.com
 */

 namespace Modules\cyberpanel\controllers;

use App\ThirdParty\MX\WhatPanel;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class Cyberpanel extends WhatPanel
{      

    public function cyberpanel_config ($values = null)
    {
        $packages = $this->getPackageList($values);

        // Initialize an empty array to store package options
		$packageOptions = [];

		// Generate options for the dropdown
		foreach ($packages as $package) {
			// Assuming the 'id' of the package is its name
			$packageOptions[$package] = $package;
		}

		$config = array(
			array(
				'label' => 'Package Name',
				'id' => 'package',
				'options' => $packageOptions, // Assign package options to the field
				'value' => isset($packageOptions) ? $packageOptions[$package] : null, // Set the selected value if provided
				'type' => 'select' // Set the field type to 'select' for dropdown
			) 
		); 

		return $config;        
    }
	
	public function getPackageList($params = null)
	{
        $username = $params->username;
		$password = $params->password;

		$hostname = $params->hostname;

		$port = $params->port;

		// $endpoint = "https://cyberpanel.madpopo.in:8090/api/listPackages";
        $endpoint = "$hostname:$port/api/listPackages";

        $data = [
    		'adminUser' => $username,
    		'adminPass' => $password,
		];
		
		// Create a Guzzle client
        $client = new Client();

        try {
            // Send a POST request with Basic Authentication
            $response = $client->request('POST', $endpoint, [
                'json' => $data,
				'headers' => [
					'Content-Type' => 'application/json',
				],
            ]);

            // Check response status code
            if ($response->getStatusCode() == 200) {
                // Successful response
                $body = $response->getBody()->getContents();

                $result = json_decode($body, JSON_PRETTY_PRINT);

                if ($result !== null) {
                    
                    return $result;
                    //echo "<pre>";print_r($packageName);
                } else {
                    echo "Failed to decode JSON.";
                }
            } else {
                echo "Error: Unexpected status code - " . $response->getStatusCode();
            }
        } catch (RequestException $e) {
            // Request failed
            echo "Error: " . $e->getMessage();
        }
	}

    
    public function check_connection($server = null)
    {
        // Cyberpanel server credentials
        $host = $server->hostname;
        $username = $server->username;
        $password = $server->password;
        $port = $server->port;

        // $endpoint = "https://cyberpanel.madpopo.in:8090/api/verifyConn";
        $endpoint = "https://$host:$port/api/verifyConn";
		
		$data = [
    		'adminUser' => $username,
    		'adminPass' => $password,
		];
		
		// Create a Guzzle client
        $client = new Client();

        try {
            // Send a POST request with Basic Authentication
            $response = $client->request('POST', $endpoint, [
                'json' => $data,
				'headers' => [
					'Content-Type' => 'application/json',
				],
            ]);

            // Check response status code
            if ($response->getStatusCode() == 200) {
                // Successful response
                $body = $response->getBody()->getContents();
                return "Response: $body";
            } else {
                echo "Error: Unexpected status code - " . $response->getStatusCode();
            }
        } catch (RequestException $e) {
            // Request failed
            echo "Request Error: " . $e->getMessage();
        }
    }

    function random_password($length = 8) {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!@#$%^&*()_+=-<>?/,.';
        $password = str_shuffle($alphabet);
        return substr($password, 0, $length);
    }

    public function create_account($params)
    {       
        //echo"<pre>";print_r($params);die;

        // Create a Guzzle client
        $client = new Client();

        $password = $this->random_password(10);

        $whmHostname = $params->server->hostname;
		$whmUsername = $params->server->username;
		$whmPassword = $params->server->password;
		$whmPort = $params->server->port;

        $endpointUser = "$whmHostname:$whmPort/api/submitUserCreation";

        $userParams = [
            'adminUser' => $whmUsername,
            'adminPass' => $whmPassword,
            'firstName' => "Ketan",
            'lastName' => "Vyas",
            'email' => $params->user->email,
            'userName' => $params->user->username,
            'password' => md5($password),
            'websitesLimit' => 1,
            'selectedACL' => "user",
            'securityLevel' => "HIGH"
        ];

        $responseUser = $client->request('POST', $endpointUser, [
            'json' => $userParams,
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ]);

        $bodyUser = $responseUser->getBody()->getContents();

        $endpointWeb = "$whmHostname:$whmPort/api/createWebsite";

        $postParams =  [
            'adminUser' => $whmUsername,
            'adminPass' => $whmPassword,
            'domainName' => $params->account->domain,
            'ownerEmail' => $params->user->email,
            'packageName' => $params->package->package_name,
            'websiteOwner' => $params->user->username,
            'ownerPassword' => md5($password),
            'websitesLimit' => 1,
            'acl' => "user"
        ];

        $responseWeb = $client->request('POST', $endpointWeb, [
            'json' => $postParams,
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ]);

        $bodyWeb = $responseWeb->getBody()->getContents();

        echo "<pre>";
        print_r($bodyUser);
        echo "<br>";
        print_r($bodyWeb);
    }
	
	function admin_options($server) 
    {   
        $protocol = ($server->use_ssl == 'Yes') ? 'https://' : 'http://';
        $code = '<a class="btn btn-success btn-xs" href="'.base_url().'servers/index/'.$server->id.'"><i class="fa fa-options"></i> '.lang('test_connection').'</a>
        <a class="btn btn-primary btn-xs" href="'.base_url().'servers/edit_server/'.$server->id.'" data-toggle="ajaxModal"><i class="fa fa-pencil"></i> '.lang('edit').'</a>
        <a class="btn btn-danger btn-xs" href="'.base_url().'servers/delete_server/'.$server->id.'" data-toggle="ajaxModal"><i class="fa fa-trash"></i> '.lang('delete').'</a>
        <form action="'. $protocol . $server->hostname.':8090" method="get" target="_blank" style="display:inline;">
        <button type="submit" class="btn btn-success btn-xs"><i class="fa fa-user"></i> '.lang("login").'</button>
        </form>';

        return $code;
    }
	
	public function client_options($id = null) 
    { 
        $code = '<a href="'.base_url().'accounts/view_logins/'.$id.'" class="btn btn-sm btn-success" data-toggle="ajaxModal">
        <i class="fa fa-eye"></i>'.lang('view_cpanel_logins').'</a>
        <a href="'.base_url().'accounts/change_password/'.$id.'" class="btn btn-sm btn-info" data-toggle="ajaxModal">
        <i class="fa fa-edit"></i>'.lang('change_cpanel_password').'</a>         
        <a href="'.base_url().'accounts/login/'.$id.'" class="btn btn-sm btn-warning" target="_blank"><i class="fa fa-sign-in"></i> &nbsp;'.lang('control_panel').'</a>';

        return $code; 
    }

    public function cyberpanel_SuspendWebsite($params = null)
    {
        // echo"<pre>";print_r($params);die;

        $whmHostname = $params->server->hostname;
        $whmUsername = $params->server->username;
        $whmPassword = $params->server->password;
        $whmPort = $params->server->port;

        $endpoint = "$whmHostname:$whmPort/api/submitWebsiteStatus";

        $suspendParams = [
            'adminUser' => $whmUsername,
            'adminPass' => $whmPassword,
            'websiteName' => $params->account->domain,
            'state' => "Suspend"
        ];

        // Create a Guzzle client
        $client = new Client();

        try {
            // Send POST request to suspend user
            $response = $client->request('POST', $endpoint, [
                'json' => $suspendParams,
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
            ]);
        
            // Get response body
            $body = $response->getBody()->getContents();
        
            // Print response
            echo "<pre>";
            print_r($body);
        } catch (RequestException $e) {
            // Catch and handle exceptions
            if ($e->hasResponse()) {
                echo "Error Response: " . $e->getResponse()->getStatusCode() . " " . $e->getResponse()->getReasonPhrase() . "\n";
                echo "Body: " . $e->getResponse()->getBody() . "\n";
            } else {
                echo "Request Exception: " . $e->getMessage() . "\n";
            }
        }
    }

    public function cyberpanel_UnSuspendWebsite($params = null)
    {
        // echo"<pre>";print_r($params);die;

        $whmHostname = $params->server->hostname;
        $whmUsername = $params->server->username;
        $whmPassword = $params->server->password;
        $whmPort = $params->server->port;

        $endpoint = "$whmHostname:$whmPort/api/submitWebsiteStatus";

        $suspendParams = [
            'adminUser' => $whmUsername,
            'adminPass' => $whmPassword,
            'websiteName' => $params->account->domain,
            'state' => "Activate"
        ];

        // Create a Guzzle client
        $client = new Client();

        try {
            // Send POST request to suspend user
            $response = $client->request('POST', $endpoint, [
                'json' => $suspendParams,
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
            ]);
        
            // Get response body
            $body = $response->getBody()->getContents();
        
            // Print response
            echo "<pre>";
            print_r($body);
        } catch (RequestException $e) {
            // Catch and handle exceptions
            if ($e->hasResponse()) {
                echo "Error Response: " . $e->getResponse()->getStatusCode() . " " . $e->getResponse()->getReasonPhrase() . "\n";
                echo "Body: " . $e->getResponse()->getBody() . "\n";
            } else {
                echo "Request Exception: " . $e->getMessage() . "\n";
            }
        }
    }
 
}
