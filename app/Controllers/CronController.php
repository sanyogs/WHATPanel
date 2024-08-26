<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Order;
use App\Helpers\custom_name_helper;
use CodeIgniter\I18n\Time;
use App\Models\DomainRegistrar;
use GuzzleHttp\Client;
use Config\Services\validation;
use GuzzleHttp\Exception\ClientException;
use Config\Services;
use App\Models\Item;
use App\Controllers\Installer;

class CronController extends BaseController
{	
    private $endpoint = 'api.php';

	function Suspend()
	{	
		error_reporting(E_ALL);
        ini_set("display_errors", "1");
		// Your existing code
		$model = new Order();
		$db = \Config\Database::connect();
		$get_all_orders = $db->table('hd_orders')->get()->getResult();
		// Get the current date
		$currentDate = strtotime(date('Y-m-d')); // Format: YYYY-MM-DD
		
		foreach($get_all_orders as $order){
			$renewalDate = $order->renewal_date;
			$helper = new custom_name_helper();
			$dateTime = new \DateTime($renewalDate);
            // Add days
            $dateTime->modify('+' . $helper->getconfig_item('invoices_due_after') . ' days');
			$newDate = $dateTime->format('Y-m-d');

			if($currentDate < $newDate)
			{
				$result = $helper->auto_suspend($order->invoice_id);
				//print_r($result);die;
			}
			else{
				echo "Nachoooo!!";
			}
		}
	}
	
	
	
	function GetDomainInformation(array $params = null,)
	{	
	 	$apiUrl = 'https://test.httpapi.com/api/';
		$authUserId = '205077';
        $apiKey = 'XHkwYQZ37rYpL56ijS0CaMN1P0QjpJqq'; 
		
		$db = \Config\Database::connect();
		$orders = $db->table('hd_orders')->get()->getResult();
		
		foreach($orders as $domains)
		{
			$domain = $domains->domain;

			$postfields = [
            "auth-userid" => $authUserId,
            "api-key" => $apiKey,
			"domain-name" => $domain,
            "options" => "All",
            "TestMode" => 1
        ];
			
		}

        if (empty($authUserId)) {
            $errorResponse = [
                'status'  => 'error',
                'message' => 'Missing required credentials. Please check authUserID.',
            ];
            return $this->response->setStatusCode(400)->setJSON($errorResponse);
        }
        if (empty($apiKey)) {
            $errorResponse = [
                'status'  => 'error',
                'message' => 'Missing required credentials. Please check apiKey.',
            ];
            return $this->response->setStatusCode(400)->setJSON($errorResponse);
        }

        // Retrieve data from the request
        $data = $this->request->getGet();

        // Construct the API endpoint
        $endpoint = $apiUrl . 'details-by-name.json?';

        // Set up the HTTP client
        $client = new Client();
        
        $validation = \Config\Services::validation();

	 	// Validation rules
        $rules = [
            'auth-userid' => 'required',
            'api-key' => 'required',
            "options" => '',
        ];

        $validation->setRules($rules);
	 	
	 	$sendcommand = new APIController();
		
        $domainStatus = $sendcommand->resellerclub_SendCommand("details-by-name", "domains", $postfields, $params, "GET");
		//print_r($domainStatus);die;
        // echo "<pre>";print_r($domainStatus);die;
        return $domainStatus;
        // return $domainStatus['domainname'];

        // $response = [
        //     'status' => 200,
        //     'msg' => 'Info Fetched Successfully',
        //     'data' => $domainStatus
        // ];

        // header('Content-Type: application/json');
        // echo json_encode($response, JSON_PRETTY_PRINT);
	}

	public function tlds_customer_pricing()
    {   
        $db = \Config\Database::connect();
        //$user = $db->table('hd_users')->select('id, email')->where('id', $co_id)->get()->getRow();
        // Replace these with your ResellerClub API redentials
        $authUserId = '205077';
        $apiKey = 'XHkwYQZ37rYpL56ijS0CaMN1P0QjpJqq';

        // Check if required credentials are present
        if (empty($authUserId)) {
            $errorResponse = [
                'status'  => 'error',
                'message' => 'Missing required credentials. Please check authUserID.',
            ];
            return $this->response->setStatusCode(400)->setJSON($errorResponse);
        }
        if (empty($apiKey)) {
            $errorResponse = [
                'status'  => 'error',
                'message' => 'Missing required credentials. Please check apiKey.',
            ];
            return $this->response->setStatusCode(400)->setJSON($errorResponse);
        }

        // Construct the API endpoint
        $endpoint = $this->apiUrl . 'products/customer-price.json';

        // Set up the HTTP client
        $client = new Client();
        $validation = \Config\Services::validation();

        // Prepare the request parameters
        $params = [
            "auth-userid" => $authUserId,
            "api-key" => $apiKey,
        ];

        // Validation rules
        $rules = [
            'auth-userid' => 'required',
            'api-key' => 'required',
        ];
        
       // $validation->setRules($rules);

        if ($params === FALSE) {
            //echo 78;die;
            // Handle validation errors
            $validationErrors = $this->validator->getErrors();
            $errorResponse = [
                'status' => 'error',
                'message' => $validationErrors,
            ];
            return $this->response->setStatusCode(400)->setJSON($errorResponse); // Adjust the HTTP status code as needed
        } else {
            try {
                //echo 78;die;
                // Make the API request
                $response = $client->post($endpoint, [
                    'form_params' => $params,
                    'curl' => [
                        CURLOPT_CAINFO => 'C:\wamp64\www\hosting_share\cacert.pem',
                    ],
                ]);
                //echo 80;die;
                // Process the API response
                $responseData = json_decode($response->getBody(), true);
                
                // Example response
                $successResponse = [
                    'status' => 'success',
                    //'message' => 'Successfully',
                    'data' => $responseData,
                ];
                return json_encode($successResponse);
            } catch (\Exception $e) {
                // Handle API request failure
                $errorResponse = [
                    'status' => 'error',
                    'message' => $e,
                ];
                return json_encode($errorResponse);
                //return $this->response->setStatusCode(500)->setJSON($errorResponse);
            }
        }
    }
	
	
	 public function xrate()
    {   
        $db = \Config\Database::connect();
        $helper = new custom_name_helper();
        $xrates_app_id = $helper->getconfig_item('xrates_app_id');
        $app_id = $xrates_app_id;
        $oxr_url = "https://openexchangerates.org/api/latest.json?app_id=" . $app_id;

        $client = new Client();

        try {
            // Send a GET request to the API endpoint
            $response = $client->request('GET', $oxr_url);

            // Get the response body as a string
            $json = $response->getBody()->getContents();

            // Decode JSON response
            $oxr_latest = json_decode($json);

            $db->table('hd_currencies')->truncate();
  
            foreach($oxr_latest->rates as $currency => $price){
                $currencies = $db->table('hd_currencies_symbol')->where('code',$currency)->get()->getRow();
                $data = array(
                    "code" => $currency,
                    "xrate" => $price,
                    "name" => $currencies->name,
                    "symbol" => $currencies->symbol,
                );
                $query = $db->table('hd_currencies')->insert($data);
            }
            // You can now access the rates inside the parsed object, like so:
            // printf(
            //     "1 %s equals %s GBP at %s",
            //     $oxr_latest->base,
            //     $oxr_latest->rates->GBP,
            //     date('H:i jS F, Y', $oxr_latest->timestamp)
            // );
            // -> eg. "1 USD equals: 0.656741 GBP at 11:11, 11th December 2015"
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
	
    public function consent()
    {   
        $db = \Config\Database::connect();
        
        $key = $db->table('hd_config')->where('config_key', 'salt_key')->get()->getRow();
		
		$client = new Client();
		$baseURL = env('app.baseURL');
		$trim = trim($baseURL,"https:/");
		$club = new Installer();
		$url = $club->club() . $this->endpoint;
		$apiKey = $key->value;
		$body = [
				 'action' => 'check_license',
				 'domain_name' => $trim,
				 'license' => $apiKey
			 ];

				 // Make the GET request with the Authorization header
				 $response = $client->post($url, [
					 'headers' => [
						 'Api-Key' => 'sanyog',
						 'Content-Type' => 'application/json'
					 ],
					 'json' => $body
			]);

				 // Get the response body
			$responseBody = $response->getBody()->getContents();
			$data = json_decode($responseBody, true);
			
			return $data;
	}

}
