<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */
/*
 * @ PHP 5.6
 * @ Decoder version : 1.0.0.1
 * @ Release on : 24.03.2018
 * @ Website    : http://EasyToYou.eu
 */

namespace Modules\plesk\controllers;

use Modules\plesk\libraries\Plesk_Loader;
use Modules\plesk\libraries\Plesk_Registry;
use Modules\plesk\libraries\Plesk_Api;
use Modules\plesk\Object\Plesk_Object_Customer;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class Plesk_WHMCS
{
    function plesk_TestConnection($params)
    {
        // Plesk server credentials
        $host = $params->hostname;
        $username = $params->username;
        $password = $params->password;
        $port = $params->port;

        if ($port == 0) {
            $endpoint = "https://$host/api/v2/server";
        } else {
            $endpoint = "https://$host:$port/api/v2/server";
        }

        // Endpoint for Plesk REST API
        // Create a Guzzle client
        $client = new Client();

        try {
            // Send a GET request with Basic Authentication
            $response = $client->request('GET', $endpoint, [
                'auth' => [$username, $password],
                'verify' => FALSE
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
            echo "Error: " . $e->getMessage();
        }
    }

    function plesk_PackageName($params = null)
    {
        // Plesk credentials
        $username = $params->username; // Replace with your Plesk username
        $password = $params->password; // Replace with your Plesk password

        $port = $params->port;

        $ip_addr = $params->ip;

        $hostname = $params->hostname;

        // Plesk API URL
        $url = 'https://' . $hostname . ':' . $port . '/enterprise/control/agent.php';

        // XML request to get all service plans
        $request = <<<XML
            <packet version="1.6.3.0">
                <service-plan>
                    <get>
                        <filter>
                        </filter>
                    </get>
                </service-plan>
            </packet>
        XML;

        // Initialize cURL session
        $ch = curl_init();

        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, $url); // Set the URL
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the response as a string
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true); // Enable SSL verification
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC); // Use Basic Authentication
        //curl_setopt($ch, CURLOPT_USERPWD, "$username:$password"); // Set the username and password
        curl_setopt($ch, CURLOPT_POST, true); // Set method to POST
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request); // Set POST fields
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("HTTP_AUTH_LOGIN: $username", "HTTP_AUTH_PASSWD: $password", "Content-Type: text/xml"));


        // Execute the cURL request
        $response = curl_exec($ch);

        // Check for errors
        if (curl_errno($ch)) {
            echo 'Error: ' . curl_error($ch);
        }

        // Close cURL session
        curl_close($ch);

        $result = simplexml_load_string($response);

        if ($result !== null) {
            $packageName = array(); // Initialize the array to store package names
            foreach ($result->{"service-plan"}->get->result as $package) {
                // Assuming 'name' is a child element of each 'result'
                $packageName[] = (string)$package->name;
            }

            // Return the array
            return $packageName;
        } else {
            echo "Failed to decode XML.";
        }
    }

    function plesk_CreateAccount($params)
    {
        $db = \Config\Database::connect();

        $username = $params->server->username; // Replace with your Plesk username
        $password = $params->server->password; // Replace with your Plesk password

        $port = $params->server->port;

        $ip_addr = $params->server->ip;

        $hostname = $params->server->hostname;

        $password_hash = $this->random_password(10);

        $url = 'https://' . $hostname . ':' . $port . '/enterprise/control/agent.php';

        $request = <<<XML
        <packet version="1.6.7.0">
            <customer>
                <add>
                    <gen_info>
                        <cname>{$params->client->company_name}</cname>
                        <pname>{$params->client->company_name}</pname>
                        <login>{$params->account->username}</login>
                        <passwd>{$password_hash}</passwd>
                        <status>0</status>
                        <phone>{$params->client->company_mobile}</phone>
                        <fax>{$params->client->company_fax}</fax>
                        <email>{$params->client->company_email}</email>
                        <address>{$params->client->company_address}</address>
                        <city>{$params->client->city}</city>
                        <state />
                        <pcode />
                        <country>US</country>
                    </gen_info>
                </add>
            </customer>
        </packet>
        XML;


        $ch = curl_init();

        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, $url); // Set the URL
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the response as a string
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true); // Enable SSL verification
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC); // Use Basic Authentication
        //curl_setopt($ch, CURLOPT_USERPWD, "$username:$password"); // Set the username and password
        curl_setopt($ch, CURLOPT_POST, true); // Set method to POST
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request); // Set POST fields
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("HTTP_AUTH_LOGIN: $username", "HTTP_AUTH_PASSWD: $password", "Content-Type: text/xml"));

        // Execute the cURL request
        $response = curl_exec($ch);

        // Check for errors
        if (curl_errno($ch)) {
            echo 'Error: ' . curl_error($ch);
        }

        // Close cURL session
        curl_close($ch);

        $resultCust = simplexml_load_string($response);
        $req = <<<XML
        <packet>
            <webspace>
                <add>
                    <gen_setup>
                        <name>{$params->account->domain}</name>
                        <ip_address>{$params->server->ip}</ip_address>
                        <htype>vrt_hst</htype>
                    </gen_setup>
                    <hosting>
                        <vrt_hst>
                            <property>
                                <name>ftp_login</name>
                                <value>{$params->account->username}</value>
                            </property>
                            <property>
                                <name>ftp_password</name>
                                <value>{$password_hash}</value>
                            </property>
                            <!-- Add other hosting properties as needed -->
                        </vrt_hst>
                    </hosting>
                </add>
            </webspace>
        </packet>
        XML;

        $ch = curl_init();

        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, $url); // Set the URL
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the response as a string
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true); // Enable SSL verification
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC); // Use Basic Authentication
        //curl_setopt($ch, CURLOPT_USERPWD, "$username:$password"); // Set the username and password
        curl_setopt($ch, CURLOPT_POST, true); // Set method to POST
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req); // Set POST fields
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("HTTP_AUTH_LOGIN: $username", "HTTP_AUTH_PASSWD: $password", "Content-Type: text/xml"));

        // Execute the cURL request
        $responseDomain = curl_exec($ch);

        // Check for errors
        if (curl_errno($ch)) {
            echo 'Error: ' . curl_error($ch);
        }

        // Close cURL session
        curl_close($ch);

        $resultDomain = simplexml_load_string($responseDomain);
		
		try {
			$plesk_acc = array(
				'order_id' => $params->account->order_id,
				'invoice_id' => $params->account->invoice_id,
				'server_type' => $params->account->server,
				'plesk_user_id' => $resultCust->customer->add->result->id,
				'plesk_guid' => $resultCust->customer->add->result->guid,
				'plesk_domain_id' => $resultDomain->webspace->add->result->id,
				'plesk_domain_guid' => $resultDomain->webspace->add->result->guid,
				'plesk_status' => 'ok',
				'created_on' => date('d-m-Y H;i:s', time())
			);

			$db->table('hd_plesk_account')->insert($plesk_acc);

			if ($resultCust && $resultDomain) {
				echo 'done';
			} else {
				echo false;
			}
		} catch (Exception $e) {
			log_message('error', 'Error creating Plesk account: ' . $e->getMessage());
			return redirect()->to('orders')->with('Error creating Plesk account: ' . $e->getMessage());
		}
    }
	
	function random_password($length = 8) {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!@#$%^&*()_+=-<>?/,.';
        $password = str_shuffle($alphabet);
        return substr($password, 0, $length);
	}

    function plesk_SuspendAccount($params = null, $plesk_id = null)
    {
        //echo "<pre>";print_r($params);die;
        // Plesk server credentials
        $host = $params->server->hostname;
        $username = $params->server->username;
        $password = $params->server->password;
        $port = $params->server->port;

        $id = 35;

        if ($port == 0) {
            $endpoint = "https://$host/api/v2/clients/$plesk_id/suspend";
        } else {
            $endpoint = "https://$host:$port/api/v2/clients/$plesk_id/suspend";
        }

        // Endpoint for Plesk REST API


        // Create a Guzzle client
        $client = new Client();

        try {
            // Send a GET request with Basic Authentication
            $response = $client->request('PUT', $endpoint, [
                'auth' => [$username, $password],
                'verify' => FALSE
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
            echo "Error: " . $e->getMessage();
        }
    }

    function plesk_UnSuspendAccount($params = null, $plesk_id = null)
    {
        // Plesk server credentials
        $host = $params->server->hostname;
        $username = $params->server->username;
        $password = $params->server->password;
        $port = $params->server->port;

        if ($port == 0) {
            $endpoint = "https://$host/api/v2/clients/$plesk_id/activate";
        } else {
            $endpoint = "https://$host:$port/api/v2/clients/$plesk_id/activate";
        }

        // Endpoint for Plesk REST API


        // Create a Guzzle client
        $client = new Client();

        try {
            // Send a GET request with Basic Authentication
            $response = $client->request('PUT', $endpoint, [
                'auth' => [$username, $password],
                'verify' => FALSE
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
            echo "Error: " . $e->getMessage();
        }
    }

    function plesk_CreateAccount_REST($params)
    {
        //echo "<pre>";print_r($params);die;

        $db = \Config\Database::connect();

        $username = $params->server->username; // Replace with your Plesk username
        $password = $params->server->password; // Replace with your Plesk password

        $port = $params->server->port;

        $ip_addr = $params->server->ip;

        $hostname = $params->server->hostname;

        $password_hash = $this->random_password(10);

        $accountDetails = array(
            'name' => $params->account->username,
            'company' => $params->client->company_name,
            'login' => $params->account->username,
            'status' => 0,
            'email' => $params->client->company_email,
            'locale' => $params->profile->locale,
            'owner_login' => $params->account->username,
            'external_id' => $params->client->company_email,
            'description' => $params->client->company_email,
            'password' => $password_hash,
            'type' => $params->account->type
        );
        // print_r(json_encode($accountDetails));die;

        if ($port == 0) {
            $endpoint = "https://$hostname/api/v2/clients";
        } else {
            $endpoint = "https://$hostname:$port/api/v2/clients";
        }

        $ch = curl_init();

         // Set cURL options
         curl_setopt($ch, CURLOPT_URL, $endpoint);
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification (use only for testing purposes)
         curl_setopt($ch, CURLOPT_POST, true);
         curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
         curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($accountDetails));
         curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
         //curl_setopt($ch, CURLOPT_HTTPHEADER, array("HTTP_AUTH_LOGIN: $username", "HTTP_AUTH_PASSWD: $password", "Content-Type: application/json"));

        // Execute the cURL request
        $response = curl_exec($ch);

        // Check for errors
        if (curl_errno($ch)) {
            echo 'Error: ' . curl_error($ch);
        }

        // Close cURL session
        curl_close($ch);

        echo "<pre>";print_r($response);die;

        $data = json_decode($response, true);

        $domain_details = array(
            'name' => $params->account->domain,
            'description' => $params->package->item_desc,
            'hosting_type' => $params->account->type,
            'hosting_settings' => array(
                'ftp_login' => $params->account->username,
                'ftp_password' => $password_hash,
            ),
            'base_domain' => array(
                'id' => $data['id'],
                'name' => $params->account->domain,
                'guid' => $data['guid'],
            ),
            'parent_domain' => array(
                'id' => $data['id'],
                'name' => $params->account->domain,
                'guid' => $data['guid'],
            ),
            'owner_client' => array(
                'id' => $data['id'],
                'login' => $params->account->username,
                'guid' => $data['guid'],
                'external_id' => $data['guid'],
            ),
            'ipv4' => array(
                $params->server->ip
            ),
            'plan' => array(
                'name' => $params->package->package_name
            )
        );

        if ($port == 0) {
            $endpointDomain = "https://$hostname/api/v2/domains";
        } else {
            $endpointDomain = "https://$hostname:$port/api/v2/domains";
        }

        $ch = curl_init();

         // Set cURL options
         curl_setopt($ch, CURLOPT_URL, $endpointDomain);
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification (use only for testing purposes)
         curl_setopt($ch, CURLOPT_POST, true);
         curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
         curl_setopt($ch, CURLOPT_POSTFIELDS, $domain_details);
         curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
         //curl_setopt($ch, CURLOPT_HTTPHEADER, array("HTTP_AUTH_LOGIN: $username", "HTTP_AUTH_PASSWD: $password", "Content-Type: application/json"));

        // Execute the cURL request
        $responseDomain = curl_exec($ch);

        // Check for errors
        if (curl_errno($ch)) {
            echo 'Error: ' . curl_error($ch);
        }

        // Close cURL session
        curl_close($ch);

        $dataDomain = json_decode($responseDomain, true);

        $plesk_acc = array(
            'order_id' => $params->account->order_id,
            'invoice_id' => $params->account->invoice_id,
            'server_type' => $params->account->server,
            'plesk_user_id' => $data['id'],
            'plesk_guid' => $data['guid'],
            'plesk_domain_id' => $dataDomain['id'],
            'plesk_domain_guid' => $dataDomain['guid'],
            'plesk_status' => 'ok',
            'created_on' => time()
        );

        $db->table('hd_plesk_account')->insert($plesk_acc);

        echo "<pre>";
        print_r($data);
        echo "<br>";
        print_r($dataDomain);
    }
}
