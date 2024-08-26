<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Application Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */
namespace App\Controllers;

use App\Controllers\BaseController;
use App\ThirdParty\MX\WhatPanel;
use GuzzleHttp\Client;

class PackageController extends BaseController
{
	function fetchCpanelPackages()
    {	
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
		
		$client = new Client();

        try {
			$headers = [
    			'Authorization' => 'whm FX876RIRE78NQU2RLC9TZF9KV98DW2Q5'
			];
			
			$response = $client->request('GET', 'https://root:gk^3F=U$W(Xx0Q*F@194.233.75.89:2087/json-api/listpkgs', [
        'headers' => $headers
    ]);
			print_r($response);die;
		
            if ($response->getStatusCode() === 200) {
                $packages = json_decode($response->getBody()->getContents(), true);
                print_r($packages);die;
                // return $packages;
            } else {
                // Handle API errors
                throw new \Exception('API request failed with status code: ' . $response->getStatusCode());
            }
        } catch (\Exception $e) {
            echo 'Error fetching packages: ' . $e->getMessage();
            return []; // Return an empty array if an error occurs
        }
    }
}