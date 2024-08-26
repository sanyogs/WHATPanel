<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

/* Module Name: cPanel
 * Module URI: https://whatpanel.com
 * Version: 1.0
 * Category: Servers
 * Description: cPanel API Integration.
 * Author:  WHAT PANEL 
 * Author URI: https://whatpanel.com
 */

namespace Modules\cpanel\controllers;
use App\ThirdParty\MX\WhatPanel;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class new_Cpanel extends WhatPanel
{	
	function cpanel_TestConnection($params)
	{	
		// cPanel credentials
		$whmHostip = $params->ip;
		$whmHostname = $params->hostname;
		$whmUsername = $params->username; // Assuming username field is 'username', adjust if needed
		$whmPassword = $params->password;
		$whmAuthKey = $params->authkey;
		$whmHttpPrefix = $params->use_ssl;
		$whmPort = $params->port;

		// cPanel API URL
		$url = "http://$whmHostname:$whmPort/json-api/version"; // Adjust with your cPanel domain
		//print_r($url);die;
		// Initialize cURL session
		$ch = curl_init();

		// Set cURL options
		curl_setopt($ch, CURLOPT_URL, $url); // Set the URL
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the response as a string
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification (use only for testing purposes)
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC); // Use Basic Authentication
		curl_setopt($ch, CURLOPT_USERPWD, "$whmUsername:$whmPassword"); // Set the username and password

		// Execute the cURL request
		$response = curl_exec($ch);
		//print_r($response);die;
		// Check for errors
		if(curl_errno($ch)){
			echo 'Error: ' . curl_error($ch);
		}

		// Close cURL session
		curl_close($ch);

		// Output the response
		return $response;
	}
	
	
	
	function package_Name($params = null)
	{
		//echo "<pre>";print_r($params);die;
		$username = $params->username;
		$password = $params->password;

		$hostname = $params->hostname;

		$port = $params->port;

		// cPanel API URL
		//$url = 'https://pinox.vntservers.net:2087/json-api/listpkgs'; // Adjust with your cPanel domain

		$url = 'https://' . $hostname . ':' . $port . '/json-api/listpkgs';
		
		//echo $url;die;
		
		// Initialize cURL session
		$ch = curl_init();

		// Set cURL options
		curl_setopt($ch, CURLOPT_URL, $url); // Set the URL
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the response as a string
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification (use only for testing purposes)
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC); // Use Basic Authentication
		curl_setopt($ch, CURLOPT_USERPWD, "$username:$password"); // Set the username and password

		// Execute the cURL request
		$response = curl_exec($ch);
		
		// Check for errors
		if(curl_errno($ch)){
			echo 'Error: ' . curl_error($ch);
		}

		// Close cURL session
		curl_close($ch);

		// Output the response
		$result = json_decode($response, JSON_PRETTY_PRINT);
		// echo "<pre>";print_r($result);die;
		if ($result !== null) {
			//$packageNames = []; // Initialize an empty array to store package names
			// Iterate through each element of the 'package' array
			foreach ($result['package'] as $package) {
				// Fetch the 'name' field for each package
				$packageName[] = $package['name'];
			}
			return $packageName;
			//echo "<pre>";print_r($packageName);
		} else {
			echo "Failed to decode JSON.";
		}
	}
		
	
		function cpanel_CreateAccount($params) 
		{	
			//echo"<pre>";print_r($params);die;
			error_reporting(E_ALL);
			ini_set("display_errors", "1");
			//echo"<pre>";print_r($params);die;
			$password = $this->random_password(10);
			// cPanel credentials
			$whmHostname = $params->server->hostname;
			$whmUsername = $params->server->username; // Assuming username field is 'username', adjust if needed
			$whmPassword = $params->server->password;
			$whmPort = $params->server->port;
			//print_r($whmPassword);die;
			// cPanel API URL
			$url = "https://$whmHostname:$whmPort/json-api/createacct";
			//print_r($url);die;
			// Account details
			$accountDetails = array(
				'username' => $params->account->username, // Change this to the desired username for the new account
				'domain' => $params->account->domain, // Change this to the desired domain for the new account
				'password' => md5($password), // Change this to the desired password for the new account
				'contactemail' => $params->client->company_email // Change this to the desired contact email for the new account
				// Add other parameters as needed, refer to cPanel API documentation for more options
			);
			//print_r($accountDetails);die;

			// Initialize cURL session
			$ch = curl_init();

			// Set cURL options
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification (use only for testing purposes)
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
			curl_setopt($ch, CURLOPT_POSTFIELDS, ($accountDetails));
			curl_setopt($ch, CURLOPT_USERPWD, "$whmUsername:$whmPassword");

			// Execute the cURL request
			$response = curl_exec($ch);
			//print_r($response);die;
			
			$result = json_decode($response);
			//print_r($result);die;
			if ($result === null || $result->result[0]->options == '') {
            // Handle JSON decoding error
            return "This Domain already exist";
        	}else{
			$accountInfo = $result->result[0]->options;
			//print_r($accountInfo);die;
			$rawout = $result->result[0]->rawout;
			preg_match('/Domain:\s*(.*?)\n.*Ip:\s*(.*?)\s.*UserName:\s*(.*?)\n.*PassWord:\s*(.*?)\n.*Contact Email:\s*(.*?)\n/s', $rawout, 				$rawMatches);

			// Extracting values from $rawMatches using list()
			list( , $domain, $ip, $username, $password, $email) = $rawMatches;

			// Creating the $rawoutData array
			$rawoutData = array(
				'domain' => $domain, // Accessing the first capturing group
				'ip' => $ip, // Accessing the second capturing group
				'hascgi' => $rawMatches[3] ?? null, // Assuming 'HasCgi' is in the third capturing group
				'username' => $username, // Accessing the fourth capturing group
				'password' => $password, // Accessing the fifth capturing group
				'cpanelmod' => $rawMatches[6] ?? null,// Assuming 'CpanelMod' is in the sixth capturing group
				'homeroot' => $rawMatches[7] ?? null, // Assuming 'HomeRoot' is in the seventh capturing group
				'quota' => $rawMatches[8] ?? null, // Assuming 'Quota' is in the eighth capturing group
				'nameserver1' => $rawMatches[9] ?? null,// Assuming 'NameServer1' is in the ninth capturing group
				'nameserver2' => $rawMatches[10] ?? null,// Assuming 'NameServer2' is in the tenth capturing group
				'nameserver3' => $rawMatches[11] ?? null, // Assuming 'NameServer3' is in the eleventh capturing group
				'nameserver4' => $rawMatches[12] ?? null, // Assuming 'NameServer4' is in the twelfth capturing group
				'contactemail' => $email, // Accessing the email from the extracted values
				//'package' => $accountInfo->[package],
				'invoice_id' => $params->account->invoice_id, // Accessing 'client_id' from $params array
				'order_id' => $params->account->order_id // Accessing 'order_id' from $params array
			);
			//echo"<pre>";print_r($rawoutData);die;

			$db = \Config\Database::connect();

			$builder = $db->table('hd_cpanel_account');

			$builder->insert($rawoutData);

			$insertedId = $db->insertID();
			//print_r($insertedId);die;
			}
			
			// Check for errors
			if (curl_errno($ch)) {
				echo 'Error: ' . curl_error($ch);
			}

			// Close cURL session
			curl_close($ch);

			// Output the response
			return $response;
		}
	
		function random_password($length = 8) {
			echo 89;die;
			$alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!@#$%^&*()_+=-<>?/,.';
			$password = str_shuffle($alphabet);
			return substr($password, 0, $length);
		}
	
		function cpanel_SuspendAccount($params, $details) 
		{	
			//print_r($details);die;
			error_reporting(E_ALL);
			ini_set("display_errors", "1");
			// cPanel credentials
			$whmHostname = $params->server->hostname;
			$whmUsername = $params->server->username; // Assuming username field is 'username', adjust if needed
			$whmPassword = $params->server->password;
			$whmPort = $params->server->port;

			// cPanel API URL
			$url = "https://$whmHostname:$whmPort/json-api/suspendacct";
		
			// Account details
			$accountDetails = array(
				'user' => $details->username, // Change this to the username of the account you want to unsuspend
				'reason' => 'Violation of terms of service', // Provide a reason for suspending the account
				'domain' => $details->domain, // Change this to the desired domain for the new account
				'password' => $details->password, // Change this to the desired password for the new account
				'contactemail' => $details->contactemail // Change this to the desired contact email for the new account
				//'user' => "Krishna", // Change this to the username of the account you want to unsuspend
				//'reason' => 'Violation of terms of service', // Provide a reason for suspending the account
				//'domain' => "krishna.com", // Change this to the desired domain for the new account
				//'password' => "123456", // Change this to the desired password for the new account
				//'contactemail' => "anjali@version-next.com" // Change this to the desired contact email for the new account
			);
			// Initialize cURL session
			$ch = curl_init();

			// Set cURL options
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification (use only for testing purposes)
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
			curl_setopt($ch, CURLOPT_POSTFIELDS, ($accountDetails));
			curl_setopt($ch, CURLOPT_USERPWD, "$whmUsername:$whmPassword");

			// Execute the cURL request
			$response = curl_exec($ch);
			//print_r($response);die;
			// Check for errors
			if (curl_errno($ch)) {
				echo 'Error: ' . curl_error($ch);
			}

			// Close cURL session
			curl_close($ch);

			// Output the response
			return $response;
		}	
	
		function cpanel_UnsuspendAccount($params, $details)
		{
			error_reporting(E_ALL);
			ini_set("display_errors", "1");

			// cPanel credentials
			$whmHostname = $params->server->hostname;
			$whmUsername = $params->server->username; // Assuming username field is 'username', adjust if needed
			$whmPassword = $params->server->password;
			$whmPort = $params->server->port;

			// cPanel API URL
			$url = "https://$whmHostname:$whmPort/json-api/unsuspendacct";
			//print_r($url);die;
			// Account details
			$accountDetails = array(
				'user' => $details->username, // Change this to the username of the account you want to unsuspend
				'domain' => $details->domain, // Change this to the desired domain for the new account
				'password' => $details->password, // Change this to the desired password for the new account
				'contactemail' => $details->contactemail // Change this to the desired contact email for the new account
			);

			// Initialize cURL session
			$ch = curl_init();

			// Set cURL options
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification (use only for testing purposes)
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
			curl_setopt($ch, CURLOPT_POSTFIELDS, ($accountDetails));
			curl_setopt($ch, CURLOPT_USERPWD, "$whmUsername:$whmPassword");

			// Execute the cURL request
			$response = curl_exec($ch);
			//print_r($response);die;
			// Check for errors
			if (curl_errno($ch)) {
				echo 'Error: ' . curl_error($ch);
			}

			// Close cURL session
			curl_close($ch);

			// Output the response
			return $response;
		}
	
		
		



}
