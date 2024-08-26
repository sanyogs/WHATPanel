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
use App\Helpers\custom_name_helper;
use CodeIgniter\I18n\Time;
use App\Models\DomainRegistrar;
use GuzzleHttp\Client;
use Config\Services\validation;
use GuzzleHttp\Exception\ClientException;
use Config\Services;
use GuzzleHttp\Exception\RequestException;
use Modules\cpanel\controllers\new_cPanel;
use App\Models\Item;


class APIController extends BaseController
{
    private $apiUrl = 'https://test.httpapi.com/api/';

    public function __construct()
    {
        // Load the validation service
        $this->validator = Services::validation();

        error_reporting(E_ALL);
        ini_set("display_errors", "1");
    }

    function resellerclub_GetConfigArray()
    {
        $configArray = array("Description" => array("Type" => "System", "Value" => "Don't have a ResellerClub Account yet? Get one here: " . "<a href=\"http://go.whmcs.com/86/resellerclub\" target=\"_blank\">" . "www.whmcs.com/partners/resellerclub</a>"), "ResellerID" => array("Type" => "text", "Size" => "20", "Description" => "You can get this from the LogicBoxes Control Panel in " . "Settings > Personal Information > Primary Profile"), "APIKey" => array("Type" => "password", "Size" => "20", "Description" => "Your API Key. You can get this from the LogicBoxes " . "Control Panel in Settings -> API"), "DesignatedAgent" => array("FriendlyName" => "Designated Agent", "Type" => "yesno", "Description" => "Check to act as Designated Agent for all contact changes. " . "Ensure you understand your role and responsibilities before checking this option."), "TestMode" => array("Type" => "yesno"));
        return $configArray;
    }

    public function checkavailability($data = null)
    {
        //print_r($data);die;
        $domainUrl = 'https://domaincheck.httpapi.com/api/';

        $db = \Config\Database::connect();

        // Replace these with your ResellerClub API credentials

        $requestPlugin = $db->table('hd_plugins')->select('config')->where('system_name', 'resellerclub')->get()->getRow()->config;

        $results = json_decode($requestPlugin);

        // $authUserId = '205077';
        // $apiKey = 'XHkwYQZ37rYpL56ijS0CaMN1P0QjpJqq';

        $authUserId = $results->auth_id;
        $apiKey = $results->api_key;
		
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

        // Set up the HTTP client
        $client = new Client();
        $validation = \Config\Services::validation();

        $domain = explode(".", $data['domain']);
        //print_r($domain);die;
        $domain_val = $domain[0];

        // Construct the base URL
        $base_url = "https://domaincheck.httpapi.com/api/domains/available.json";

        $domain_without_periods = str_replace('.', '', $data['tlds']);
		
		// echo $domain_without_periods;die;

        // Construct the query parameters
        $query_params = [
            'auth-userid' => $authUserId,
            'api-key' => $apiKey,
            'domain-name' => $domain_val,
            'tlds' => $domain_without_periods
        ];

        // Validation rules
        $rules = [
            'auth-userid' => 'required',
            'api-key' => 'required',
            'domain-name' => 'required',
            'tlds' => 'required'
        ];
		// print_
        $c = $validation->setRules($rules);
		
        if ($validation->run($rules) === FALSE) {
			// echo 78;die;
            // Handle validation errors
            $validationErrors = $this->validator->getErrors();
            $errorResponse = [
                'status' => 'error',
                'message' => $validationErrors,
            ];
            return $this->response->setStatusCode(400)->setJSON($errorResponse); // Adjust the HTTP status code as needed
        } else {
			// echo 23;die;
            try {
				// echo 4;die;
                // Make the API request

                // Endpoint
                $endpoint = $base_url . '?' . http_build_query($query_params);
                // echo"<pre>";print_r($endpoint);die;
                $response = $client->get($endpoint, []);
				
				// echo "<pre>";print_r($response);die;

                // Process the API response
                $responseData = json_decode($response->getBody()->getContents(), true);
				
                // Example response
                $successResponse = [
                    'statusCode' => 200,
                    'status' => 'success',
                    'message' => 'Domain Details',
                    'data' => $responseData,
                ];

                return $successResponse;
            } catch (\Exception $e) {
                // Handle API request failure
                $errorResponse = [
                    'statusCode' => 400,
                    'status' => 'error',
                    'message' => $e
                ];

                return $errorResponse;
            }
        }
    }

    public function resellerclub_GetDomainInformation(array $params = null,)
    {
        error_reporting(E_ALL);
        ini_set("display_errors", "1");

        $db = \Config\Database::connect();

        // ini_set('display_errors', 1);
        $requestPlugin = $db->table('hd_plugins')->select('config')->where('system_name', 'resellerclub')->get()->getRow()->config;

        $results = json_decode($requestPlugin);

        // $authUserId = '205077';
        // $apiKey = 'XHkwYQZ37rYpL56ijS0CaMN1P0QjpJqq';

        $authUserId = $results->auth_id;
        $apiKey = $results->api_key;
        $domain = 'madpopo.com';

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

        $postFields = [
            "auth-userid" => $authUserId,
            "api-key" => $apiKey,
            "domain-name" => $domain,
        ];

        // Retrieve data from the request
        $data = $this->request->getGet();

        // Construct the API endpoint
        // $endpoint = $this->apiUrl . 'domains/register.json';

        // Set up the HTTP client
        $client = new Client();

        $validation = \Config\Services::validation();

        $postFields = [
            "auth-userid" => $authUserId,
            "api-key" => $apiKey,
            "order-id" => 70849003,
            "options" => "All",
            "TestMode" => 1
        ];

        // Validation rules
        $rules = [
            'auth-userid' => 'required',
            'api-key' => 'required',
            "order-id" => '',
            "options" => '',
        ];

        $validation->setRules($rules);

        $domainStatus = $this->resellerclub_SendCommand("details", "domains", $postFields, $params, "GET");

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

    public function resellerclub_GetInfo(array $params = null)
    {
        // $authUserId = '205077';
        // $apiKey = 'XHkwYQZ37rYpL56ijS0CaMN1P0QjpJqq';

        $db = \Config\Database::connect();

        $requestPlugins = $db->table('hd_plugins')->select('config')->where('system_name', 'resellerclub')->get()->getRow()->config;

        $results = json_decode($requestPlugins);

        $authUserId = $results->auth_id;
        $apiKey = $results->api_key;

        $domain = 'madpopo.com';

        $postFields = [
            "auth-userid" => $authUserId,
            "api-key" => $apiKey,
            "domain-name" => $domain,
            "options" => 'OrderDetails',
            "TestMode" => 1
        ];
        $domainStatus = $this->resellerclub_SendCommand("details-by-name", "domains", $postFields, $params, "GET");

        $response = [
            'status' => 200,
            'msg' => 'Info Fetched Successfully',
            'data' => $domainStatus
        ];

        header('Content-Type: application/json');
        echo json_encode($response, JSON_PRETTY_PRINT);
    }

    function resellerclub_SendCommand($command, $type, $postfields, $params, $method, $jsonDecodeResult = false)
    {
        // print_r($method);die;
        if ($postfields["TestMode"]) {
            $url = "https://test.httpapi.com/api/{$type}/{$command}.json";
        } else {
            $url = "https://httpapi.com/api/{$type}/{$command}.json";
        }

        // $testMode = isset($postfields["TestMode"]) ? $postfields["TestMode"] : null;

        // if ($testMode) {
        //     $url = "https://test.httpapi.com/api/{$type}/{$command}.json";
        // } else {
        //     $url = "https://httpapi.com/api/{$type}/{$command}.json";
        // }
        if ($command == "available") {
            $url = "https://domaincheck.httpapi.com/api/{$type}/{$command}.json";
        }
        $options = [];
        $callDataForLog = $curlPostData = $postfields;
        $postFieldQuery = "";

        if ($method == "GET") {
            // print_r($command); die;
            $queryParams = http_build_query($curlPostData, '', '&', PHP_QUERY_RFC3986);

            if ($queryParams) {
                $url .= "?" . ltrim($queryParams, "&");
            }

            unset($queryParams);
            $callDataForLog["url"] = $url;
            // echo 1;die;
        } else {
            // $isEsTld = $params["domainObj"]->getLastTLDSegment() == "es";
            // echo 1;die;

            $isEsTld = '';
            foreach ($curlPostData as $field => $data) {
                if ($field == "ns") {
                    $postFieldQuery .= "&" . http_build_query([$field => $data], NULL, '&', PHP_QUERY_RFC3986);
                    continue;
                }

                if ($isEsTld && !$data) {
                    if ($field == "attr-value2") {
                        $data = 0;
                    } else {
                        if ($field == "attr-value3") {
                            $data = $params["additionalfields"]["ID Form Number"];
                        }
                    }
                }

                $postFieldQuery .= "&" . http_build_query([$field => $data], NULL, '&', PHP_QUERY_RFC3986);
            }
            // echo 4545451; die;
            $postFieldQuery = ltrim($postFieldQuery, "&");
            // echo 23;die;
            $callDataForLog["posteddata"] = $postFieldQuery;
            $options['body'] = $postFieldQuery;
        }

        $client = new Client();
        // echo 1;die;
        try {
            //print_r($url); die;
            // $response = $client->request($method, $url, $options);
            $response = $client->request($method, $url, [
                'query' => $postfields,
                'http_errors' => false, // Handle errors manually
            ]);



            $data = $response->getBody()->getContents();

            $resp = [
                'status' => 'error',
                'data' => $data
            ];
            //echo"<pre>";print_r($resp);
            //die;

            header('Content-Type: application/json');
            echo json_encode($resp, JSON_PRETTY_PRINT);

            // if (!$jsonDecodeResult && is_numeric($data)) {
            //     $result = $data;
            // } else {
            //     $result = json_decode($data, true);
            // }

            // // echo $result;die;
            // // logModuleCall("logicboxes", "{$type}/{$command}", $callDataForLog, $data, $result, [$params["ResellerID"], $params["APIKey"]]);
            // // echo 1;die;
            // return $result;

            // Process successful response



        } catch (ClientException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    function resellerclub_getDomainName()
    {
        $db = \Config\Database::connect();

        $requestPlugin = $db->table('hd_plugins')->select('config')->where('system_name', 'resellerclub')->get()->getRow()->config;

        $results = json_decode($requestPlugin);

        // $authUserId = '205077';
        // $apiKey = 'XHkwYQZ37rYpL56ijS0CaMN1P0QjpJqq';

        $authUserId = $results->auth_id;
        $apiKey = $results->api_key;
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
        $data = $this->request->getGet();
        // print_r($data); die;

        $client = new Client();
        $validation = \Config\Services::validation();

        // Prepare the request parameters
        $postFields = [
            'auth-userid' => $authUserId,
            'api-key' => $apiKey,
            "TestMode" => 1,
        ];

        $domainName = $this->resellerclub_GetDomainInformation();

        // print_r($domainName); die;
        if (empty($domainName)) {
            return array("error" => $domainName);
        }
        return $domainName['domainname'];
    }

    function resellerclub_GetCustomerNameservers($params = NULL)
    {
        $db = \Config\Database::connect();

        $requestPlugin = $db->table('hd_plugins')->select('config')->where('system_name', 'resellerclub')->get()->getRow()->config;

        $results = json_decode($requestPlugin);

        // $authUserId = '205077';
        // $apiKey = 'XHkwYQZ37rYpL56ijS0CaMN1P0QjpJqq';

        $authUserId = $results->auth_id;
        $apiKey = $results->api_key;
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

        // Prepare the request parameters
        $postFields = [
            'auth-userid' => $authUserId,
            'api-key' => $apiKey,
            "TestMode" => 1,
        ];
        // Retrieve data from the request
        $data = $this->request->getGet();
        // print_r($data); die;

        $client = new Client();
        $validation = \Config\Services::validation();

        $domainName = $this->resellerclub_GetDomainInformation();

        // print_r($domainName); die;
        if (empty($domainName)) {
            return array("error" => $domainName);
        }

        $customerid = $domainName['customerid'];
        // print_r($customerid); die;
        unset($postFields);
        $postFields = [
            'auth-userid' => $authUserId,
            'api-key' => $apiKey,
            "TestMode" => 1,
            'customer-id' => $customerid,
            "TestMode" => 1,
        ];

        $result = $this->resellerclub_SendCommand("customer-default-ns", "domains", $postFields, $params, "GET");
        // print_r($result); die;
        // print_r($result); die;
        // for ($x = 1; $x <= 5; $x++) {
        //     $values["ns" . $x] = $result["ns" . $x];
        // }
        // return $result;
        $response = [
            'status' => 200,
            'msg' => 'Info Fetched Successfully',
            'data' => $result
        ];

        header('Content-Type: application/json');
        echo json_encode($response, JSON_PRETTY_PRINT);
    }

    function resellerclub_getOrderID()
    {
        $db = \Config\Database::connect();

        // $params = injectDomainObjectIfNecessary($params);
        $requestPlugin = $db->table('hd_plugins')->select('config')->where('system_name', 'resellerclub')->get()->getRow()->config;

        $results = json_decode($requestPlugin);

        // $authUserId = '205077';
        // $apiKey = 'XHkwYQZ37rYpL56ijS0CaMN1P0QjpJqq';

        $authUserId = $results->auth_id;
        $apiKey = $results->api_key;
        $domain = 'madpopo.com';
        if (empty($domain)) {
            $errorResponse = [
                'status'  => 'error',
                'message' => 'Missing required credentials. Please check authUserID.',
            ];
            return $this->response->setStatusCode(400)->setJSON($errorResponse);
        }

        // Retrieve data from the request
        $data = $this->request->getGet();
        // echo 1;die;
        $apiUrl = 'https://test.httpapi.com/api/';
        // Construct the API endpoint
        // $endpoint = $this->apiUrl . 'domains/register.json';
        // Construct the API endpoint
        $endpoint = $apiUrl . 'domains/orderid.json';

        // Set up the HTTP client
        $client = new Client();
        $validation = \Config\Services::validation();
        // Prepare the request parameters
        $params = [
            'auth-userid'        => $authUserId,
            'api-key'            => $apiKey,
            'domain-name'        => $domain,
        ];

        // Append parameters to the URL
        $endpoint .= '?' . http_build_query($params);

        // Validation rules
        $rules = [
            'auth-userid'        => 'required',
            'api-key'            => 'required',
            'domain-name'        => 'required',
        ];

        $validation->setRules($rules);

        if ($validation->run($params) === FALSE) {
            // Handle validation errors
            $validationErrors = $this->validator->getErrors();
            $errorResponse = [
                'status'  => 'error',
                'message' => $validationErrors,
            ];
            return $this->response->setStatusCode(400)->setJSON($errorResponse); // Adjust the HTTP status code as needed
        } else {
            // echo $endpoint;die;
            try {
                // Make the API request
                $response = $client->get($endpoint, [
                    'curl' => [
                        CURLOPT_CAINFO => 'C:\wamp64\www\hosting_share\cacert.pem', // Use the full server path
                    ],
                ]);

                // Process the API response
                $responseData = json_decode($response->getBody(), true);

                // Example response
                $successResponse = [
                    'status'  => 'success',
                    'message' => 'Transfered successfully',
                    'data'    => $responseData,
                ];
                // print_r($successResponse); die;
                // return $this->response->setJSON($successResponse);
                return $responseData;
            } catch (\Exception $e) {
                // Handle API request failure
                $errorResponse = [
                    'status'  => 'error',
                    // 'message' => $e->getMessage(),
                    'message' =>  'Unable to obtain Order-ID',
                ];

                return $this->response->setStatusCode(500)->setJSON($errorResponse);
            }
        }
    }

    function resellerclub_tld_type(array $params = NULL)
    {
        $db = \Config\Database::connect();

        // $params = injectDomainObjectIfNecessary($params);
        $requestPlugin = $db->table('hd_plugins')->select('config')->where('system_name', 'resellerclub')->get()->getRow()->config;

        $results = json_decode($requestPlugin);

        // $authUserId = '205077';
        // $apiKey = 'XHkwYQZ37rYpL56ijS0CaMN1P0QjpJqq';

        $authUserId = $results->auth_id;
        $apiKey = $results->api_key;
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
        $tld = '.com';
        // print_r($tld);die;
        $session = \Config\Services::session();

        // Retrieve data from session
        $transientData = $session->get("ResellerClubTldData");
        // print_r($transientData); die;
        // $transientData = WHMCS\TransientData::getInstance()->retrieve("ResellerClubTldData");
        if ($transientData) {
            $transientData = json_decode($transientData, true);
        }
        // echo 1;die;
        if (!$transientData) {
            // Retrieve data from the request
            $data = $this->request->getPost();

            $client = new Client();
            $validation = \Config\Services::validation();

            // Prepare the request parameters
            $postFields = [
                'auth-userid' => $authUserId,
                'api-key' => $apiKey,
                "TestMode" => 1,
            ];
            // print_r($postfields); die;
            $transientData = $this->resellerclub_sendcommand("tld-info", "domains", $postFields, $params, "POST");
            $transientData = $session->set("ResellerClubTldData", json_encode($transientData), 2592000);
            // echo"<pre>";print_r($transientData); die;
        }
        // echo 1; die;
        $type = "generic";
        // echo 1;die;
        if (array_key_exists($tld, $transientData)) {
            $type = $transientData[$tld]["type"];
        }
        unset($transientData);
        return $type;
    }

    function resellerclub_GetRegistrarLock($params = NULL)
    {
        $db = \Config\Database::connect();

        // $params = injectDomainObjectIfNecessary($params);
        $requestPlugin = $db->table('hd_plugins')->select('config')->where('system_name', 'resellerclub')->get()->getRow()->config;

        $results = json_decode($requestPlugin);

        // $authUserId = '205077';
        // $apiKey = 'XHkwYQZ37rYpL56ijS0CaMN1P0QjpJqq';

        $authUserId = $results->auth_id;
        $apiKey = $results->api_key;
        $domain = 'madpopo.com';
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
        $data = $this->request->getGet();
        // print_r($data); die;

        $client = new Client();
        $validation = \Config\Services::validation();

        // Prepare the request parameters
        $postFields = [
            'auth-userid' => $authUserId,
            'api-key' => $apiKey,
            'domain-name' => $domain,
            "TestMode" => 1,
        ];

        $orderid = $this->resellerclub_getOrderID();
        if (!is_numeric($orderid)) {
            return array("error" => $orderid);
        }
        // echo $orderid;die;
        unset($postFields);
        $postFields = [
            'auth-userid' => $authUserId,
            'api-key' => $apiKey,
            "TestMode" => 1,
            'order-id' => $orderid,

        ];
        // print_r($postFields); die;
        // echo 1;die;
        $lockstatus = "unlocked";
        $result =  $this->resellerclub_SendCommand("locks", "domains", $postFields, $params, "GET");
        if ($result["transferlock"] == "1") {
            $lockstatus = "locked";
        }
        return $lockstatus;
    }

    function resellerclub_GetEPPCode($params = NULL)
    {
        $db = \Config\Database::connect();

        // $params = injectDomainObjectIfNecessary($params);
        $requestPlugin = $db->table('hd_plugins')->select('config')->where('system_name', 'resellerclub')->get()->getRow()->config;

        $results = json_decode($requestPlugin);

        // $authUserId = '205077';
        // $apiKey = 'XHkwYQZ37rYpL56ijS0CaMN1P0QjpJqq';

        $authUserId = $results->auth_id;
        $apiKey = $results->api_key;
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
        $data = $this->request->getGet();
        // print_r($data); die;

        $client = new Client();
        $validation = \Config\Services::validation();

        $domainName = $this->resellerclub_GetDomainInformation();

        // print_r($domainName); die;
        if (empty($domainName)) {
            return array("error" => $domainName);
        }
        $getdomainname = $domainName['domainname'];
        // Prepare the request parameters
        $postFields = [
            'auth-userid' => $authUserId,
            'api-key' => $apiKey,
            'domain-name' => $getdomainname,
            "TestMode" => 1,
        ];
        $orderid = $this->resellerclub_getOrderID();
        if (!is_numeric($orderid)) {
            return array("error" => $orderid);
        }
        unset($postFields);
        $postFields = [
            'auth-userid' => $authUserId,
            'api-key' => $apiKey,
            'order-id' => $orderid,
            'options' => 'OrderDetails',
            "TestMode" => 1,
        ];
        $result = $this->resellerclub_SendCommand("details", "domains", $postFields, $params, "GET");
        // if (strtoupper($result["status"]) == "ERROR") {
        //     if (!$result["message"]) {
        //         $result["message"] = $result["error"];
        //     }
        //     return array("error" => $result["message"]);
        // }
        $values["eppcode"] = $result["domsecret"];

        $response = [
            'status' => 200,
            'msg' => 'Info Fetched Successfully',
            'data' => $values
        ];

        header('Content-Type: application/json');
        echo json_encode($response, JSON_PRETTY_PRINT);


        // return $values;
    }

    function resellerclub_GetDNS($params = NULL)
    {
        $db = \Config\Database::connect();

        $requestPlugins = $db->table('hd_plugins')->select('config')->where('system_name', 'resellerclub')->get()->getRow()->config;

        $results = json_decode($requestPlugins);

        // $authUserId = '205077';
        // $apiKey = 'XHkwYQZ37rYpL56ijS0CaMN1P0QjpJqq';

        $authUserId = $results->auth_id;
        $apiKey = $results->api_key;
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
        $data = $this->request->getGet();
        // print_r($data); die;

        $client = new Client();
        $validation = \Config\Services::validation();

        $domainName = $this->resellerclub_GetDomainInformation();
        // print_r($domainName); die;
        if (empty($domainName)) {
            return array("error" => $domainName);
        }
        $getdomainname = $domainName['domainname'];
        // Prepare the request parameters
        $postfields = [
            'auth-userid' => $authUserId,
            'api-key' => $apiKey,
            'domain-name' => $getdomainname,
        ];
        // print_r($postfields);die;

        $orderid = $this->resellerclub_getOrderID();
        if (!is_numeric($orderid)) {
            return array("error" => $orderid);
        }

        unset($postfields);
        $postfields = [
            'auth-userid' => $authUserId,
            'api-key' => $apiKey,
            'order-id' => $orderid,
        ];
        print_r($postfields);
        die;

        $result = $this->resellerclub_SendCommand("activate", "dns", $postfields, $params, "POST");
        print_r($result);
        die;

        if (strtoupper($result["status"]) == "ERROR") {
            if (!$result["message"]) {
                $result["message"] = $result["error"];
            }
            return array("error" => $result["message"]);
        }
        unset($postfields);
        $postfields = [
            'auth-userid' => $authUserId,
            'api-key' => $apiKey,
            'domain-name' => $getdomainname,
            'no-of-records' => 50,
        ];
        $typelist = array("A", "MX", "CNAME", "TXT", "AAAA");
        $hostrecords = array();
        foreach ($typelist as $type) {
            $pageNumber = 0;
            $numTotalRecords = 0;
            $postfields["type"] = $type;
            $maxPagesToRequest = 4;
            $postfields["page-no"] = ++$pageNumber;
            $result = resellerclub_SendCommand("search-records", "dns/manage", $postfields, $params, "GET");
            if (strtoupper($result["status"]) == "ERROR") {
                if (!$result["message"]) {
                    $result["message"] = $result["error"];
                }
                return array("error" => $result["message"]);
            }
            $numResultRecords = (int) $result["recsonpage"];
            $numTotalRecords += $numResultRecords;
            if (0 < $numResultRecords) {
                foreach ($result as $entry => $value) {
                    if (!is_array($value)) {
                        continue;
                    }
                    $recid = $entry;
                    $host = $value["host"];
                    $address = $value["value"];
                    if ($type == "MX") {
                        $priority = $value["priority"];
                    } else {
                        $priority = "";
                    }
                    if ($host && $address) {
                        $hostrecords[] = array("hostname" => htmlentities($host), "type" => (string) $type, "address" => htmlentities($address), "priority" => (string) $priority, "recid" => $recid);
                    }
                }
            }
            if (!(0 < $numResultRecords && $numTotalRecords < $result["recsindb"] && 0 < --$maxPagesToRequest)) {
            }
        }
        unset($postfields);
        $postfields = [
            'auth-userid' => $authUserId,
            'api-key' => $apiKey,
            'TestMode' => 1,
            'order-id' => $orderid,
            'no-of-records' => 50,
        ];

        $result = resellerclub_SendCommand("details", "domainforward", $postfields, $params, "GET");
        if (!$result["status"] && $result["forward"]) {
            $host = "";
            $address = "";
            $recid = "";
            $hostrecords[] = array("hostname" => "@", "type" => "URL", "address" => htmlentities($result["forward"]));
        }
        return $hostrecords;
    }

    function resellerclub_GetContactDetails($params = NULL)
    {
        $db = \Config\Database::connect();
        
        $requestPlugins = $db->table('hd_plugins')->select('config')->where('system_name', 'resellerclub')->get()->getRow()->config;

        $results = json_decode($requestPlugins);

        // $authUserId = '205077';
        // $apiKey = 'XHkwYQZ37rYpL56ijS0CaMN1P0QjpJqq';

        $authUserId = $results->auth_id;
        $apiKey = $results->api_key;
        $domain = 'madpopo.com';
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
        // echo 1;die;
        $postFields = [
            'auth-userid' => $authUserId,
            'api-key' => $apiKey,
            'domain-name' => $domain,
            "TestMode" => 1,
        ];
        // print_r($postFields); die;
        $orderid = $this->resellerclub_getOrderID();
        if (!is_numeric($orderid)) {
            return array("error" => $orderid);
        }
        unset($postFields);
        $postFields = [
            'auth-userid' => $authUserId,
            'api-key' => $apiKey,
            'order-id' => $orderid,
            'options' => "ContactIds",
            "TestMode" => 1,
        ];
        // print_r($postFields); die;
        $result = $this->resellerclub_SendCommand("details", "domains", $postFields, $params, "GET");
        // print_r($result);die;

        // if (strtoupper($result["status"]) == "ERROR") {
        //     if (!$result["message"]) {
        //         $result["message"] = $result["error"];
        //     }
        //     return array("error" => $result["message"]);
        // }
        $contacts = array();
        if ($result["registrantcontactid"] != -1) {
            $contacts["Registrant"] = $result["registrantcontactid"];
        }
        if ($result["admincontactid"] != -1) {
            $contacts["Admin"] = $result["admincontactid"];
        }
        if ($result["techcontactid"] != -1) {
            $contacts["Tech"] = $result["techcontactid"];
        }
        if ($result["billingcontactid"] != -1) {
            $contacts["Billing"] = $result["billingcontactid"];
        }
        unset($postFields);
        $tempValues = $values = array();
        //  print_r($tempValues); die;


        foreach ($contacts as $contactType => $contactId) {
            if (array_key_exists($contactId, $tempValues)) {
                continue;
            }
            $postFields = [
                'auth-userid' => $authUserId,
                'api-key' => $apiKey,
                'contact-id' => $contactId,
                "TestMode" => 1,
            ];
            // print_r($postFields); die;
            $result = $this->resellerclub_SendCommand("details", "contacts", $postFields, $params, "GET");
            // print_r($result); die;

            // if (strtoupper($result["status"]) == "ERROR") {
            //     if (!$result["message"]) {
            //         $result["message"] = $result["error"];
            //     }
            //     return array("error" => $result["message"]);
            // }
            $tempValues[$contactId] = array("Full Name" => $result["name"], "Email" => $result["emailaddr"], "Company Name" => $result["company"], "Address 1" => $result["address1"], "Address 2" => $result["address2"], "City" => $result["city"], "State" => $result["state"], "Postcode" => $result["zip"], "Country" => $result["country"], "Phone Number" => "+" . $result["telnocc"] . $result["telno"]);
            // print_r($tempValues[$contactId]);die;

            unset($postFields);
        }
        foreach ($contacts as $contactType => $contactId) {
            $values[$contactType] = $tempValues[$contactId];
        }
        // return $values;
        $response = [
            'status' => 200,
            'msg' => 'Info Fetched Successfully',
            'data' => $values
        ];

        header('Content-Type: application/json');
        echo json_encode($response, JSON_PRETTY_PRINT);
    }

    // function resellerclub_GetEmailForwarding($params = NULL)
    // {
    //     $authUserId = '205077';
    //     $apiKey = 'XHkwYQZ37rYpL56ijS0CaMN1P0QjpJqq';
    //     $domain = 'madpopo.com';
    //     if (empty($authUserId)) {
    //         $errorResponse = [
    //             'status' => 'error',
    //             'message' => 'Missing required credentials. Please check authUserID.',
    //         ];
    //         return $this->response->setStatusCode(400)->setJSON($errorResponse);
    //     }
    //     if (empty($apiKey)) {
    //         $errorResponse = [
    //             'status' => 'error',
    //             'message' => 'Missing required credentials. Please check apiKey.',
    //         ];
    //         return $this->response->setStatusCode(400)->setJSON($errorResponse);
    //     }

    //     $postFields = [
    //         'auth-userid' => $authUserId,
    //         'api-key' => $apiKey,
    //         'domain-name' => $domain,
    //         "TestMode" => 1,
    //     ];

    //     $orderid = $this->resellerclub_getOrderID();
    //     if (!is_numeric($orderid)) {
    //         return array("error" => $orderid);
    //     }

    //     unset($postFields);

    //     $postFields = [
    //         'auth-userid' => $authUserId,
    //         'api-key' => $apiKey,
    //         'order-id' => $orderid,
    //         "TestMode" => 1,
    //     ];

    //     try {
    //         $result = $this->resellerclub_SendCommand("is-ownership-verified", "mail/domain", $postFields, $params, "GET");

    //         $res = json_encode($result);
    //     } catch (ClientException  $e) {

    //     }

    //     $data = json_decode($res, true);

    //     print_r($data);die;

    // echo $data['status'];die;

    //     if ($result["response"]["isOwnershipVerified"] != "true") {

    //         unset($postFields);

    //         $postFields = [
    //             'auth-userid' => $authUserId,
    //             'api-key' => $apiKey,
    //             'domain-name' => $domain,
    //             'value' => '@',
    //             'type' => 'MX',
    //             'host' => 'mx1.mailhostbox.com',
    //             'priority' => 100,
    //         ];

    //         // print_r($postFields); die;
    //         $result = $this->resellerclub_SendCommand("add-mx-record", "dns/manage", $postFields, $params, "POST");
    //         // print_r($result); die;

    //         $postfields["host"] = "mx2.mailhostbox.com";
    //     }
    //     unset($postFields);
    //     $postFields = [
    //         'auth-userid' => $authUserId,
    //         'api-key' => $apiKey,
    //         'order-id' => $orderid,
    //     ];

    //     $result = $this->resellerclub_SendCommand("activate", "mail", $postFields, $params, "POST");

    //     $postfields["account-types"] = "forward_only";

    //     $result = $this->resellerclub_SendCommand("search", "mail/users", $postFields, $params, "GET");

    //     // if (strtoupper($result["status"]) == "ERROR") {
    //     //     if (!$result["message"]) {
    //     //         $result["message"] = $result["error"];
    //     //     }
    //     //     return array("error" => $result["message"]);
    //     // }

    //     foreach ($result["response"]["users"] as $entry => $value) {
    //         $email = explode("@", $value["emailAddress"]);
    //         $values[$entry]["prefix"] = $email[0];
    //         $values[$entry]["forwardto"] = $value["adminForwards"];
    //     }

    //     // return $values;
    //     $response = [
    //         'status' => 200,
    //         'msg' => 'Info Fetched Successfully',
    //         'data' => $values
    //     ];

    //     header('Content-Type: application/json');
    //     echo json_encode($response, JSON_PRETTY_PRINT);
    // }

    function resellerclub_GetIP()
    {
        $client = new Client();

        try {
            $response = $client->get("https://api1.whmcs.com/ip/get", [
                'curl' => [
                    CURLOPT_CAINFO => 'C:\wamp64\www\hosting_share\cacert.pem', // Use the full server path
                ],
            ]);

            $contents = $response->getBody()->getContents();

            if (!empty($contents)) {
                $data = json_decode($contents, true);

                if (is_array($data) && isset($data["ip"])) {
                    return $data["ip"];
                }
            }
        } catch (ClientException $e) {
            // Handle the exception as needed
        }

        return "";
    }

    function resellerclub_GetPremiumPrice(array $params)
    {
        $premiumPricing = array();
        return $premiumPricing;
    }

    function resellerclub_GetDomainExtensionGroup()
    {
        $jsonList = "{\"gTld\":[\"ae.org\",\"asia\",\"at\",\"berlin\",\"bid\",\"biz\",\"blue\",\"build\",\"buzz\",\"bz\",\"cc\",\"club\",\"cn\"," . "\"cn.com\",\"co\",\"co.bz\",\"co.com\",\"co.de\",\"co.in\",\"co.nz\",\"co.uk\",\"com\",\"com.au\",\"com.bz\",\"com.cn\",\"com.co\"," . "\"com.de\",\"coop\",\"dance\",\"de\",\"de.com\",\"democrat\",\"es\",\"eu\",\"eu.com\",\"firm.in\",\"futbol\",\"gen.in\",\"gr.com\"," . "\"green\",\"hu.com\",\"immobilien\",\"in\",\"in.net\",\"ind.in\",\"info\",\"ink\",\"jpn.com\",\"kim\",\"la\",\"me\",\"me.uk\",\"menu\"," . "\"mn\",\"mobi\",\"name\",\"net\",\"net.au\",\"net.bz\",\"net.cn\",\"net.co\",\"net.in\",\"net.nz\",\"ninja\",\"nl\",\"no.com\"," . "\"nom.co\",\"org\",\"org.bz\",\"org.cn\",\"org.in\",\"org.nz\",\"org.uk\",\"pink\",\"pro\",\"pw\",\"qc.com\",\"red\",\"reviews\"," . "\"ru.com\",\"sa.com\",\"sc\",\"se.com\",\"se.net\",\"shiksha\",\"social\",\"sx\",\"tel\",\"trade\",\"tv\",\"uk\",\"uk.net\",\"uno\"," . "\"us\",\"vc\",\"webcam\",\"wien\",\"wiki\",\"ws\",\"xn--3ds443g\",\"xn--6frz82g\",\"xn--c1avg\",\"xn--fiq228c5hs\"," . "\"xn--i1b6b1a6a2e\",\"xn--ngbc5azd\",\"xn--nqv7f\",\"xxx\",\"xyz\"],\"Donuts\":[\"academy\",\"agency\",\"apartments\"," . "\"associates\",\"bargains\",\"bike\",\"bingo\",\"boutique\",\"builders\",\"cab\",\"cafe\",\"capital\",\"cards\",\"care\"," . "\"careers\",\"cash\",\"catering\",\"center\",\"chat\",\"cheap\",\"church\",\"city\",\"claims\",\"clinic\",\"clothing\",\"coach\"," . "\"codes\",\"coffee\",\"community\",\"computer\",\"condos\",\"construction\",\"contractors\",\"cool\",\"coupons\",\"cruises\"," . "\"dating\",\"deals\",\"delivery\",\"dental\",\"diamonds\",\"digital\",\"direct\",\"directory\",\"discount\",\"domains\"," . "\"education\",\"email\",\"engineering\",\"enterprises\",\"equipment\",\"estate\",\"events\",\"exchange\",\"expert\",\"exposed\"," . "\"express\",\"fail\",\"farm\",\"finance\",\"financial\",\"fish\",\"fitness\",\"flights\",\"florist\",\"football\",\"foundation\"," . "\"fund\",\"furniture\",\"fyi\",\"gallery\",\"gifts\",\"gmbh\",\"golf\",\"graphics\",\"gratis\",\"gripe\",\"group\",\"guide\",\"guru\"," . "\"healthcare\",\"hockey\",\"holdings\",\"holiday\",\"house\",\"immo\",\"industries\",\"institute\",\"insure\",\"international\"," . "\"jewelry\",\"land\",\"lease\",\"legal\",\"life\",\"lighting\",\"limited\",\"limo\",\"ltd\",\"maison\",\"management\",\"marketing\"," . "\"mba\",\"media\",\"memorial\",\"money\",\"network\",\"partners\",\"parts\",\"photography\",\"photos\",\"pizza\",\"place\",\"plus\"," . "\"productions\",\"properties\",\"recipes\",\"reisen\",\"rentals\",\"repair\",\"report\",\"restaurant\",\"run\",\"salon\",\"sarl\"," . "\"school\",\"schule\",\"services\",\"show\",\"singles\",\"soccer\",\"solutions\",\"style\",\"supplies\",\"supply\",\"support\"," . "\"surgery\",\"systems\",\"tax\",\"taxi\",\"team\",\"technology\",\"tennis\",\"theater\",\"tienda\",\"tips\",\"tools\",\"tours\"," . "\"town\",\"training\",\"university\",\"vacations\",\"ventures\",\"viajes\",\"villas\",\"vin\",\"vision\",\"voyage\",\"watch\"," . "\"wine\",\"works\",\"world\",\"wtf\",\"zone\"]}";
        return json_decode($jsonList, true);
    }


    public function resellerclub_ModifyContactDetails()
    {
        $db = \Config\Database::connect();
        // Replace these with your ResellerClub API redentials
        $requestPlugins = $db->table('hd_plugins')->select('config')->where('system_name', 'resellerclub')->get()->getRow()->config;

        $results = json_decode($requestPlugins);

        // $authUserId = '205077';
        // $apiKey = 'XHkwYQZ37rYpL56ijS0CaMN1P0QjpJqq';

        $authUserId = $results->auth_id;
        $apiKey = $results->api_key;
        $order_id = '70849003';
        $reg_contact_id = '59646325';
        $admin_contact_id = '79568205';
        $tech_contact_id = '79568205';
        $billing_contact_id = '79568205';

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

        // Retrieve data from the request
        $data = $this->request->getPost();

        // Construct the API endpoint
        $endpoint = $this->apiUrl . 'domains/modify-contact.json';

        // Set up the HTTP client
        $client = new Client();
        $validation = \Config\Services::validation();

        // Prepare the request parameters
        $params = [
            'auth-userid' => $authUserId,
            'api-key' => $apiKey,
            "order-id" => $order_id,
            "reg-contact-id" => $reg_contact_id,
            "admin-contact-id" => $admin_contact_id,
            "tech-contact-id" => $tech_contact_id,
            "billing-contact-id" => $billing_contact_id,
        ];

        // Validation rules
        $rules = [
            'auth-userid' => 'required',
            'api-key' => 'required',
            "order-id" => 'required',
            "reg-contact-id" => 'required',
            "admin-contact-id" => 'required',
            "tech-contact-id" => 'required',
            "billing-contact-id" => 'required'
        ];

        $validation->setRules($rules);

        if ($validation->run($params) === FALSE) {
            // Handle validation errors
            $validationErrors = $this->validator->getErrors();
            $errorResponse = [
                'status' => 'error',
                'message' => $validationErrors,
            ];
            return $this->response->setStatusCode(400)->setJSON($errorResponse); // Adjust the HTTP status code as needed
        } else {
            try {

                // Make the API request
                $response = $client->post($endpoint, [
                    'form_params' => $params,
                    'curl' => [
                        CURLOPT_CAINFO => 'C:\wamp64\www\hosting_share\cacert.pem',
                    ],
                ]);
                echo 80;
                die;
                // Process the API response
                $responseData = json_decode($response->getBody(), true);

                // Example response
                $successResponse = [
                    'status' => 'success',
                    'message' => 'Renewal successfully',
                    'data' => $responseData,
                ];
                return $this->response->setJSON($successResponse);
            } catch (\Exception $e) {
                echo "<pre>";
                print_r($e);
                echo "</pre>";
                die;
                // Handle API request failure
                $errorResponse = [
                    'status' => 'error',
                    'message' => 'Renewal unsuccessful',
                ];

                return $this->response->setStatusCode(500)->setJSON($errorResponse);
            }
        }
    }

    public function register($co_id, $inv_id)
    {
        $session = \Config\Services::session();
        //echo"<pre>";print_r(session()->get());die;
        $helper = new custom_name_helper($co_id);

        $db = \Config\Database::connect();

        $company_detail = $db->table('hd_companies')->where('co_id', $co_id)->get()->getRow();

        $user = $db->table('hd_users')
            ->select('hd_users.id, hd_users.email, hd_resellerclub_customer_details.*')
            ->join('hd_resellerclub_customer_details', 'hd_resellerclub_customer_details.username = hd_users.email')
            ->where('hd_users.id', $company_detail->primary_contact)
            ->get()
            ->getRow();

        // Replace these with your ResellerClub API credentials
        $requestPlugins = $db->table('hd_plugins')->select('config')->where('system_name', 'resellerclub')->get()->getRow()->config;

        $results = json_decode($requestPlugins);

        // $authUserId = '205077';
        // $apiKey = 'XHkwYQZ37rYpL56ijS0CaMN1P0QjpJqq';

        $authUserId = $results->auth_id;
        $apiKey = $results->api_key;
        //$domain = session()->get('cart');
        $years = '1';

        //foreach($domain as $obj){
        //$result = $obj->domain;
        //}

        $query = $db->table('hd_invoices')->where('client', $co_id)->get()->getRow();
        $getorders = $db->table('hd_orders')->where('invoice_id', $query->inv_id)->get()->getRow();

        //echo"<pre>";print_r($result);die;
        // Check if required credentials are present
        if (empty($authUserId) || empty($apiKey)) {
            $errorResponse = [
                'status'  => 'error',
                'message' => 'Missing required credentials. Please check authUserID and apiKey.',
            ];
            return json_encode($errorResponse);
        }

        // Set up the HTTP client
        $client = new Client();

        // Prepare the request parameters
        $domain = $getorders->domain;
        //$ns1 = 'ns1.topcloudwebhosting.com';
        //$ns2 = 'ns2.topcloudwebhosting.com';
        $ns1 = 'mp1.popopower.com';
        $ns2 = 'mp2.popopower.com';
        $customerId = $user->customerid;
        $regContactId = $user->registrant;
        $adminContactId = $user->registrant;
        $techContactId = $user->tech;
        $billingContactId = $user->billing;
        $invoiceOption = 'NoInvoice';
        $discountAmount = '0.0';

        $url = "https://test.httpapi.com/api/domains/register.xml?auth-userid=$authUserId&api-key=$apiKey&domain-name=$domain&years=$years&ns=$ns1&ns=$ns2&customer-id=$customerId&reg-contact-id=$regContactId&admin-contact-id=$adminContactId&tech-contact-id=$techContactId&billing-contact-id=$billingContactId&invoice-option=$invoiceOption&discount-amount=$discountAmount";
        try {
            // Make the API request
            $response = $client->post($url);

            // Process the API response
            $responseData = simplexml_load_string($response->getBody()->getContents());

            // Example success response
            $successResponse = [
                'status' => 'success',
                'message' => 'Registration successful',
                'data' => $responseData
            ];

            echo "<pre>";
            print_r($responseData);
            die;

            // Serialize the success response array into a string
            $serializedSuccessResponse = serialize($successResponse);
            //print_r($serializedSuccessResponse);die;
            // Set flashdata to pass the serialized success response to the next request
            $session = session();
            $session->setFlashdata('successResponse', $serializedSuccessResponse);

            // Redirect to the desired page
            return redirect()->to('invoices/view/' . $inv_id);
        } catch (\Exception $e) {
            // Handle API request failure
            $errorResponse = [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
            return json_encode($errorResponse);
        }
    }


    public function transfer()
    {
        $db = \Config\Database::connect();

        $request = \Config\Services::request();

        $co_id = $request->getPost('co_id');

        $users_co_id = $request->getPost('users_co_id');

        $resellerclub_customer_contact = $db->table('hd_resellerclub_customer_contact')->where('co_id', $co_id)->get()->getRow();

        $resellerclub_customer_details = $db->table('hd_resellerclub_customer_details')->where('customerid', $resellerclub_customer_contact->customerid)->get()->getRow();

        $customer_co_id = $this->cust_details_by_username($users_co_id);

        $cust_details = json_decode($customer_co_id);

        if ($cust_details->status == 'success') {
            // Replace these with your ResellerClub API redentials
            $requestPlugins = $db->table('hd_plugins')->select('config')->where('system_name', 'resellerclub')->get()->getRow()->config;

            $results = json_decode($requestPlugins);

            // $authUserId = '205077';
            // $apiKey = 'XHkwYQZ37rYpL56ijS0CaMN1P0QjpJqq';

            $authUserId = $results->auth_id;
            $apiKey = $results->api_key;
            $domain = $request->getPost('domain');
            $ns1 = 'mp1.popopower.com';
            $ns2 = 'mp2.popopower.com';
            $reg_contact_id = $resellerclub_customer_details->registrant;
            $admin_contact_id = $resellerclub_customer_details->admin;
            $tech_contact_id = $resellerclub_customer_details->tech;
            $billing_contact_id = $resellerclub_customer_details->billing;
            $invoice_option = 'NoInvoice';
            $purchase_privacy = 'true';

            $customer_id = $cust_details->customerid;

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

            // Retrieve data from the request
            $data = $this->request->getPost();

            // Construct the API endpoint
            $endpoint = $this->apiUrl . 'domains/transfer.json';

            // Set up the HTTP client
            $client = new Client();
            $validation = \Config\Services::validation();

            // Prepare the request parameters
            $params = [
                "auth-userid" => $authUserId,
                "api-key" => $apiKey,
                "domain-name" => $domain,
                "ns"    => $ns1,
                "ns"    => $ns2,
                "customer-id"   => $customer_id,
                "reg-contact-id" => $reg_contact_id,
                "admin-contact-id" => $admin_contact_id,
                "tech-contact-id" => $tech_contact_id,
                "billing-contact-id" => $billing_contact_id,
                "invoice-option"    => $invoice_option,
                "purchase-privacy"   => $purchase_privacy
            ];

            // Validation rules
            $rules = [
                'auth-userid' => 'required',
                'api-key' => 'required',
                'years' => '',
                'ns' => '',
                'customer-id' => '',
                'reg-contact-id' => 'required',
                'admin-contact-id' => 'required',
                'tech-contact-id' => 'required',
                'billing-contact-id' => 'required',
                'invoice-option'  => '',
                'discount-amount' => ''
            ];

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

                    // Make the API request
                    $response = $client->post($endpoint, [
                        'form_params' => $params,
                    ]);
                    //echo 80;die;
                    // Process the API response
                    $responseData = json_decode($response->getBody(), true);

                    // Example response
                    $successResponse = [
                        'status' => 'success',
                        'message' => 'Successfully',
                        'data' => $responseData,
                    ];
                    return $this->response->setJSON($successResponse);
                } catch (\Exception $e) {

                    // Handle API request failure
                    $errorResponse = [
                        'status' => 'error',
                        'message' => 'Unsuccessful',
                    ];

                    return $this->response->setStatusCode(500)->setJSON($errorResponse);
                }
            }
        }
    }

    public function modifynameserver()
    {
        $db = \Config\Database::connect();

        // Replace these with your ResellerClub API redentials
        $requestPlugins = $db->table('hd_plugins')->select('config')->where('system_name', 'resellerclub')->get()->getRow()->config;

        $results = json_decode($requestPlugins);

        // $authUserId = '205077';
        // $apiKey = 'XHkwYQZ37rYpL56ijS0CaMN1P0QjpJqq';

        $authUserId = $results->auth_id;
        $apiKey = $results->api_key;
        $order_id = '109191094';
        $ns1 = 'vnt.mars.orderbox-dns.com';
        $ns2 = 'vnt.earth.orderbox-dns.com';
        $ns3 = 'vnt.venus.orderbox-dns.com';
        $ns4 = 'vnt.mercury.orderbox-dns.com';

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

        // Retrieve data from the request
        $data = $this->request->getPost();

        // Construct the API endpoint
        $endpoint = $this->apiUrl . 'domains/modify-ns.json';

        // Set up the HTTP client
        $client = new Client();
        $validation = \Config\Services::validation();

        // Prepare the request parameters
        $params = [
            "auth-userid" => $authUserId,
            "api-key" => $apiKey,
            "order-id" => $order_id,
            "ns"    => [$ns1, $ns2] // Store nameservers as an array
        ];

        // Validation rules
        $rules = [
            'auth-userid' => 'required',
            'api-key' => 'required',
            'order-id' => '',
            'ns' => '',
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
                // Make the API request
                // $response = $client->post($endpoint, [
                //     'query' => $params,
                //     'curl' => [
                //         CURLOPT_CAINFO => 'C:\wamp64\www\hosting_share\cacert.pem',
                //     ],
                // ]);

                $url = 'https://test.httpapi.com/api/domains/modify-ns.json?auth-userid=' . $authUserId . '&api-key=' . $apiKey . '&order-id=' . $order_id . '&ns=' . $ns1 . '&ns=' . $ns2 . '&ns=' . $ns3 . '&ns=' . $ns4;


                $response = $client->post($url, [
                    'curl' => [
                        CURLOPT_CAINFO => 'C:\wamp64\www\hosting_share\cacert.pem',
                    ],
                ]);
                //echo 80;die;
                // Process the API response
                $responseData = json_decode($response->getBody(), true);
                //print_r($responseData);die;
                // Example response
                $successResponse = [
                    'status' => 'success',
                    'message' => 'Successfully',
                    'data' => $responseData,
                ];
                return $this->response->setJSON($successResponse);
            } catch (\Exception $e) {
                echo "<pre>";
                print_r($e);
                echo "</pre>";
                die;
                // Handle API request failure
                $errorResponse = [
                    'status' => 'error',
                    'message' => 'Unsuccessful',
                ];

                return $this->response->setStatusCode(500)->setJSON($errorResponse);
            }
        }
    }


    public function activationdnsservice()
    {
        $db = \Config\Database::connect();

        $requestPlugins = $db->table('hd_plugins')->select('config')->where('system_name', 'resellerclub')->get()->getRow()->config;

        $results = json_decode($requestPlugins);

        // Replace these with your ResellerClub API redentials
        // $authUserId = '205077';
        // $apiKey = 'XHkwYQZ37rYpL56ijS0CaMN1P0QjpJqq';
        $authUserId = $results->auth_id;
        $apiKey = $results->api_key;
        $order_id = '109191094';

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

        // Retrieve data from the request
        $data = $this->request->getPost();

        // Construct the API endpoint
        $endpoint = $this->apiUrl . 'dns/activate.xml';

        // Set up the HTTP client
        $client = new Client();
        $validation = \Config\Services::validation();

        // Prepare the request parameters
        $params = [
            "auth-userid" => $authUserId,
            "api-key" => $apiKey,
            "order-id" => $order_id
        ];

        // Validation rules
        $rules = [
            'auth-userid' => 'required',
            'api-key' => 'required',
            'order-id' => ''
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
                    'message' => 'Successfully',
                    'data' => $responseData,
                ];
                return $this->response->setJSON($successResponse);
            } catch (\Exception $e) {

                // Handle API request failure
                $errorResponse = [
                    'status' => 'error',
                    'message' => 'Unsuccessful',
                ];

                return $this->response->setStatusCode(500)->setJSON($errorResponse);
            }
        }
    }


    public function adding_ipv4_address_record()
    {
        $db = \Config\Database::connect();

        $requestPlugins = $db->table('hd_plugins')->select('config')->where('system_name', 'resellerclub')->get()->getRow()->config;

        $results = json_decode($requestPlugins);

        // Replace these with your ResellerClub API redentials
        // $authUserId = '205077';
        // $apiKey = 'XHkwYQZ37rYpL56ijS0CaMN1P0QjpJqq';
        $authUserId = $results->auth_id;
        $apiKey = $results->api_key;
        $domain = 'madpopo.in';
        $value = '3.111.197.73';
        $host = 'cyberpanel2';

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

        // Retrieve data from the request
        $data = $this->request->getPost();

        // Construct the API endpoint
        $endpoint = $this->apiUrl . 'dns/manage/add-ipv4-record.json';

        // Set up the HTTP client
        $client = new Client();
        $validation = \Config\Services::validation();

        // Prepare the request parameters
        $params = [
            "auth-userid" => $authUserId,
            "api-key" => $apiKey,
            "domain-name" => $domain,
            "value" => $value,
            "host" => $host
        ];

        // Validation rules
        $rules = [
            'auth-userid' => 'required',
            'api-key' => 'required',
            'order-id' => ''
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
                    'message' => 'Successfully',
                    'data' => $responseData,
                ];
                return $this->response->setJSON($successResponse);
            } catch (\Exception $e) {
                echo "<pre>";
                print_r($e);
                echo "</pre>";
                die;
                // Handle API request failure
                $errorResponse = [
                    'status' => 'error',
                    'message' => 'Unsuccessful',
                ];

                return $this->response->setStatusCode(500)->setJSON($errorResponse);
            }
        }
    }


    public function adding_ipv6_address_record()
    {
        // Replace these with your ResellerClub API redentials
        $db = \Config\Database::connect();

        $requestPlugins = $db->table('hd_plugins')->select('config')->where('system_name', 'resellerclub')->get()->getRow()->config;

        $results = json_decode($requestPlugins);

        // $authUserId = '205077';
        // $apiKey = 'XHkwYQZ37rYpL56ijS0CaMN1P0QjpJqq';
        $authUserId = $results->auth_id;
        $apiKey = $results->api_key;
        $domain = 'madpopo.in';
        $value = '393a:7dec:5ccc:a9f3:f607:c4d2:1275:882c';
        $host = 'cyberpanel2';

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

        // Retrieve data from the request
        $data = $this->request->getPost();

        // Construct the API endpoint
        $endpoint = $this->apiUrl . 'dns/manage/add-ipv6-record.json';

        // Set up the HTTP client
        $client = new Client();
        $validation = \Config\Services::validation();

        // Prepare the request parameters
        $params = [
            "auth-userid" => $authUserId,
            "api-key" => $apiKey,
            "domain-name" => $domain,
            "value" => $value,
            "host" => $host
        ];

        // Validation rules
        $rules = [
            'auth-userid' => 'required',
            'api-key' => 'required',
            'order-id' => ''
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
                    'message' => 'Successfully',
                    'data' => $responseData,
                ];
                return $this->response->setJSON($successResponse);
            } catch (\Exception $e) {
                echo "<pre>";
                print_r($e);
                echo "</pre>";
                die;
                // Handle API request failure
                $errorResponse = [
                    'status' => 'error',
                    'message' => 'Unsuccessful',
                ];

                return $this->response->setStatusCode(500)->setJSON($errorResponse);
            }
        }
    }

    public function add_cname_record()
    {
        // Replace these with your ResellerClub API redentials
        // $authUserId = '205077';
        // $apiKey = 'XHkwYQZ37rYpL56ijS0CaMN1P0QjpJqq';

        $db = \Config\Database::connect();

        $requestPlugins = $db->table('hd_plugins')->select('config')->where('system_name', 'resellerclub')->get()->getRow()->config;

        $results = json_decode($requestPlugins);

        $authUserId = $results->auth_id;
        $apiKey = $results->api_key;
        $domain = 'madpopo.in';
        $value = 'blog.madpopo.in';
        $host = 'cyberpanel5';

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

        // Retrieve data from the request
        $data = $this->request->getPost();

        // Construct the API endpoint
        $endpoint = $this->apiUrl . 'dns/manage/add-cname-record.json';

        // Set up the HTTP client
        $client = new Client();
        $validation = \Config\Services::validation();

        // Prepare the request parameters
        $params = [
            "auth-userid" => $authUserId,
            "api-key" => $apiKey,
            "domain-name" => $domain,
            "value" => $value,
            "host" => $host
        ];

        // Validation rules
        $rules = [
            'auth-userid' => 'required',
            'api-key' => 'required',
            'order-id' => ''
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
                return $this->response->setJSON($successResponse);
            } catch (\Exception $e) {
                echo "<pre>";
                print_r($e);
                echo "</pre>";
                die;
                // Handle API request failure
                $errorResponse = [
                    'status' => 'error',
                    'message' => 'Unsuccessful',
                ];

                return $this->response->setStatusCode(500)->setJSON($errorResponse);
            }
        }
    }

    public function add_mx_record()
    {
        // Replace these with your ResellerClub API redentials
        // $authUserId = '205077';
        // $apiKey = 'XHkwYQZ37rYpL56ijS0CaMN1P0QjpJqq';

        $db = \Config\Database::connect();

        $requestPlugins = $db->table('hd_plugins')->select('config')->where('system_name', 'resellerclub')->get()->getRow()->config;

        $results = json_decode($requestPlugins);

        $authUserId = $results->auth_id;
        $apiKey = $results->api_key;

        $domain = 'madpopo.in';
        $value = 'www.cyberpanel2.madpopo.in';
        $host = 'cyberpanel2';

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

        // Retrieve data from the request
        $data = $this->request->getPost();

        // Construct the API endpoint
        $endpoint = $this->apiUrl . 'dns/manage/add-mx-record.json';

        // Set up the HTTP client
        $client = new Client();
        $validation = \Config\Services::validation();

        // Prepare the request parameters
        $params = [
            "auth-userid" => $authUserId,
            "api-key" => $apiKey,
            "domain-name" => $domain,
            "value" => $value,
            "host" => $host
        ];

        // Validation rules
        $rules = [
            'auth-userid' => 'required',
            'api-key' => 'required',
            'order-id' => ''
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
                return $this->response->setJSON($successResponse);
            } catch (\Exception $e) {
                echo "<pre>";
                print_r($e);
                echo "</pre>";
                die;
                // Handle API request failure
                $errorResponse = [
                    'status' => 'error',
                    'message' => 'Unsuccessful',
                ];

                return $this->response->setStatusCode(500)->setJSON($errorResponse);
            }
        }
    }

    public function add_ns_record()
    {
        // Replace these with your ResellerClub API redentials
        // $authUserId = '205077';
        // $apiKey = 'XHkwYQZ37rYpL56ijS0CaMN1P0QjpJqq';

        $db = \Config\Database::connect();

        $requestPlugins = $db->table('hd_plugins')->select('config')->where('system_name', 'resellerclub')->get()->getRow()->config;

        $results = json_decode($requestPlugins);

        $authUserId = $results->auth_id;
        $apiKey = $results->api_key;

        $domain = 'madpopo.in';
        $value = 'www.cyberpanel2.madpopo.in';
        $host = 'cyberpanel2';

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

        // Retrieve data from the request
        $data = $this->request->getPost();

        // Construct the API endpoint
        $endpoint = $this->apiUrl . 'dns/manage/add-ns-record.json';

        // Set up the HTTP client
        $client = new Client();
        $validation = \Config\Services::validation();

        // Prepare the request parameters
        $params = [
            "auth-userid" => $authUserId,
            "api-key" => $apiKey,
            "domain-name" => $domain,
            "value" => $value,
            "host" => $host
        ];

        // Validation rules
        $rules = [
            'auth-userid' => 'required',
            'api-key' => 'required',
            'order-id' => ''
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
                return $this->response->setJSON($successResponse);
            } catch (\Exception $e) {
                echo "<pre>";
                print_r($e);
                echo "</pre>";
                die;
                // Handle API request failure
                $errorResponse = [
                    'status' => 'error',
                    'message' => 'Unsuccessful',
                ];

                return $this->response->setStatusCode(500)->setJSON($errorResponse);
            }
        }
    }

    public function add_txt_record()
    {
        // Replace these with your ResellerClub API redentials
        // $authUserId = '205077';
        // $apiKey = 'XHkwYQZ37rYpL56ijS0CaMN1P0QjpJqq';
        $db = \Config\Database::connect();

        $requestPlugins = $db->table('hd_plugins')->select('config')->where('system_name', 'resellerclub')->get()->getRow()->config;

        $results = json_decode($requestPlugins);

        $authUserId = $results->auth_id;
        $apiKey = $results->api_key;
        $domain = 'madpopo.in';
        $value = 'www.cyberpanel2.madpopo.in';
        $host = 'cyberpanel2';

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

        // Retrieve data from the request
        $data = $this->request->getPost();

        // Construct the API endpoint
        $endpoint = $this->apiUrl . 'dns/manage/add-txt-record.json';

        // Set up the HTTP client
        $client = new Client();
        $validation = \Config\Services::validation();

        // Prepare the request parameters
        $params = [
            "auth-userid" => $authUserId,
            "api-key" => $apiKey,
            "domain-name" => $domain,
            "value" => $value,
            "host" => $host
        ];

        // Validation rules
        $rules = [
            'auth-userid' => 'required',
            'api-key' => 'required',
            'order-id' => ''
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
                return $this->response->setJSON($successResponse);
            } catch (\Exception $e) {
                echo "<pre>";
                print_r($e);
                echo "</pre>";
                die;
                // Handle API request failure
                $errorResponse = [
                    'status' => 'error',
                    'message' => 'Unsuccessful',
                ];

                return $this->response->setStatusCode(500)->setJSON($errorResponse);
            }
        }
    }

    public function add_srv_record()
    {
        // Replace these with your ResellerClub API redentials
        // $authUserId = '205077';
        // $apiKey = 'XHkwYQZ37rYpL56ijS0CaMN1P0QjpJqq';
        $db = \Config\Database::connect();

        $requestPlugins = $db->table('hd_plugins')->select('config')->where('system_name', 'resellerclub')->get()->getRow()->config;

        $results = json_decode($requestPlugins);

        $authUserId = $results->auth_id;
        $apiKey = $results->api_key;
        $domain = 'madpopo.in';
        $host = '_madpopo._tcp.madpopo.in';
        $value = 'chat.madpopo.in ';

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

        // Retrieve data from the request
        $data = $this->request->getPost();

        // Construct the API endpoint
        $endpoint = $this->apiUrl . 'dns/manage/add-srv-record.json';

        // Set up the HTTP client
        $client = new Client();
        $validation = \Config\Services::validation();

        // Prepare the request parameters
        $params = [
            "auth-userid" => $authUserId,
            "api-key" => $apiKey,
            "domain-name" => $domain,
            "host" => $host,
            "value" => $value
        ];

        // Validation rules
        $rules = [
            'auth-userid' => 'required',
            'api-key' => 'required',
            'order-id' => ''
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
                return $this->response->setJSON($successResponse);
            } catch (\Exception $e) {
                echo "<pre>";
                print_r($e);
                echo "</pre>";
                die;
                // Handle API request failure
                $errorResponse = [
                    'status' => 'error',
                    'message' => 'Unsuccessful',
                ];

                return $this->response->setStatusCode(500)->setJSON($errorResponse);
            }
        }
    }


    public function modifying_ipv4_address_record()
    {
        // Replace these with your ResellerClub API redentials
        // $authUserId = '205077';
        // $apiKey = 'XHkwYQZ37rYpL56ijS0CaMN1P0QjpJqq';
        $db = \Config\Database::connect();

        $requestPlugins = $db->table('hd_plugins')->select('config')->where('system_name', 'resellerclub')->get()->getRow()->config;

        $results = json_decode($requestPlugins);

        $authUserId = $results->auth_id;
        $apiKey = $results->api_key;

        $domain = 'madpopo.in';
        $value = '3.111.197.73';
        $newvalue = '3.111.197.73';
        $host = 'cyberpanel2';

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

        // Retrieve data from the request
        $data = $this->request->getPost();

        // Construct the API endpoint
        $endpoint = $this->apiUrl . 'dns/manage/update-ipv4-record.json';

        // Set up the HTTP client
        $client = new Client();
        $validation = \Config\Services::validation();

        // Prepare the request parameters
        $params = [
            "auth-userid" => $authUserId,
            "api-key" => $apiKey,
            "domain-name" => $domain,
            "current-value" => $value,
            "new-value" => $newvalue,
            "host" => $host
        ];

        // Validation rules
        $rules = [
            'auth-userid' => 'required',
            'api-key' => 'required',
            'order-id' => ''
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
                return $this->response->setJSON($successResponse);
            } catch (\Exception $e) {
                echo "<pre>";
                print_r($e);
                echo "</pre>";
                die;
                // Handle API request failure
                $errorResponse = [
                    'status' => 'error',
                    'message' => 'Unsuccessful',
                ];

                return $this->response->setStatusCode(500)->setJSON($errorResponse);
            }
        }
    }

    public function modifying_ipv6_address_record()
    {
        // Replace these with your ResellerClub API redentials
        // $authUserId = '205077';
        // $apiKey = 'XHkwYQZ37rYpL56ijS0CaMN1P0QjpJqq';
        $db = \Config\Database::connect();

        $requestPlugins = $db->table('hd_plugins')->select('config')->where('system_name', 'resellerclub')->get()->getRow()->config;

        $results = json_decode($requestPlugins);

        $authUserId = $results->auth_id;
        $apiKey = $results->api_key;

        $domain = 'madpopo.in';
        $host = 'cyberpanel2';
        $currentvalue = '393a:7dec:5ccc:a9f3:f607:c4d2:1275:882c';
        $newvalue = '92d7:d8fd:3fcb:323e:83d6:bbf7:3125:69c1';

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

        // Retrieve data from the request
        $data = $this->request->getPost();

        // Construct the API endpoint
        $endpoint = $this->apiUrl . 'dns/manage/update-ipv4-record.json';

        // Set up the HTTP client
        $client = new Client();
        $validation = \Config\Services::validation();

        // Prepare the request parameters
        $params = [
            "auth-userid" => $authUserId,
            "api-key" => $apiKey,
            "domain-name" => $domain,
            "current-value" => $currentvalue,
            "new-value" => $newvalue,
            "host" => $host
        ];

        // Validation rules
        $rules = [
            'auth-userid' => 'required',
            'api-key' => 'required',
            'order-id' => ''
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
                return $this->response->setJSON($successResponse);
            } catch (\Exception $e) {
                echo "<pre>";
                print_r($e);
                echo "</pre>";
                die;
                // Handle API request failure
                $errorResponse = [
                    'status' => 'error',
                    'message' => 'Unsuccessful',
                ];

                return $this->response->setStatusCode(500)->setJSON($errorResponse);
            }
        }
    }

    public function modifying_cname_record()
    {
        // Replace these with your ResellerClub API redentials
        // $authUserId = '205077';
        // $apiKey = 'XHkwYQZ37rYpL56ijS0CaMN1P0QjpJqq';
        $db = \Config\Database::connect();

        $requestPlugins = $db->table('hd_plugins')->select('config')->where('system_name', 'resellerclub')->get()->getRow()->config;

        $results = json_decode($requestPlugins);

        $authUserId = $results->auth_id;
        $apiKey = $results->api_key;

        $domain = 'madpopo.in';
        $value = 'www.cyberpanel2.madpopo.in';
        $newvalue = 'wp.madpopo.in';
        $host = 'www.cyberpanel2.madpopo.in';

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

        // Retrieve data from the request
        $data = $this->request->getPost();

        // Construct the API endpoint
        $endpoint = $this->apiUrl . 'dns/manage/update-cname-record.json';

        // Set up the HTTP client
        $client = new Client();
        $validation = \Config\Services::validation();

        // Prepare the request parameters
        $params = [
            "auth-userid" => $authUserId,
            "api-key" => $apiKey,
            "domain-name" => $domain,
            "current-value" => $value,
            "new-value" => $newvalue,
            "host" => $host
        ];

        // Validation rules
        $rules = [
            'auth-userid' => 'required',
            'api-key' => 'required',
            'order-id' => ''
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
                return $this->response->setJSON($successResponse);
            } catch (\Exception $e) {
                echo "<pre>";
                print_r($e);
                echo "</pre>";
                die;
                // Handle API request failure
                $errorResponse = [
                    'status' => 'error',
                    'message' => 'Unsuccessful',
                ];

                return $this->response->setStatusCode(500)->setJSON($errorResponse);
            }
        }
    }


    public function modifying_mx_record()
    {
        // Replace these with your ResellerClub API redentials
        // $authUserId = '205077';
        // $apiKey = 'XHkwYQZ37rYpL56ijS0CaMN1P0QjpJqq';
        $db = \Config\Database::connect();

        $requestPlugins = $db->table('hd_plugins')->select('config')->where('system_name', 'resellerclub')->get()->getRow()->config;

        $results = json_decode($requestPlugins);

        $authUserId = $results->auth_id;
        $apiKey = $results->api_key;

        $domain = 'madpopo.in';
        $value = 'www.cyberpanel2.madpopo.in';
        $newvalue = 'www.cyberpanel2.madpopo.com';

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

        // Retrieve data from the request
        $data = $this->request->getPost();

        // Construct the API endpoint
        $endpoint = $this->apiUrl . 'dns/manage/update-mx-record.json';

        // Set up the HTTP client
        $client = new Client();
        $validation = \Config\Services::validation();

        // Prepare the request parameters
        $params = [
            "auth-userid" => $authUserId,
            "api-key" => $apiKey,
            "domain-name" => $domain,
            "current-value" => $value,
            "new-value" => $newvalue
        ];

        // Validation rules
        $rules = [
            'auth-userid' => 'required',
            'api-key' => 'required',
            'order-id' => ''
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
                return $this->response->setJSON($successResponse);
            } catch (\Exception $e) {
                echo "<pre>";
                print_r($e);
                echo "</pre>";
                die;
                // Handle API request failure
                $errorResponse = [
                    'status' => 'error',
                    'message' => 'Unsuccessful',
                ];

                return $this->response->setStatusCode(500)->setJSON($errorResponse);
            }
        }
    }

    public function modifying_ns_record()
    {
        // Replace these with your ResellerClub API redentials
        // $authUserId = '205077';
        // $apiKey = 'XHkwYQZ37rYpL56ijS0CaMN1P0QjpJqq';
        $db = \Config\Database::connect();

        $requestPlugins = $db->table('hd_plugins')->select('config')->where('system_name', 'resellerclub')->get()->getRow()->config;

        $results = json_decode($requestPlugins);

        $authUserId = $results->auth_id;
        $apiKey = $results->api_key;

        $domain = 'madpopo.in';
        $value = 'www.cyberpanel2.madpopo.in';
        $newvalue = 'www.cyberpanel24.madpopo.in';

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

        // Retrieve data from the request
        $data = $this->request->getPost();

        // Construct the API endpoint
        $endpoint = $this->apiUrl . 'dns/manage/update-ns-record.json';

        // Set up the HTTP client
        $client = new Client();
        $validation = \Config\Services::validation();

        // Prepare the request parameters
        $params = [
            "auth-userid" => $authUserId,
            "api-key" => $apiKey,
            "domain-name" => $domain,
            "current-value" => $value,
            "new-value" => $newvalue
        ];

        // Validation rules
        $rules = [
            'auth-userid' => 'required',
            'api-key' => 'required',
            'order-id' => ''
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
                return $this->response->setJSON($successResponse);
            } catch (\Exception $e) {
                echo "<pre>";
                print_r($e);
                echo "</pre>";
                die;
                // Handle API request failure
                $errorResponse = [
                    'status' => 'error',
                    'message' => 'Unsuccessful',
                ];

                return $this->response->setStatusCode(500)->setJSON($errorResponse);
            }
        }
    }

    public function modifying_txt_record()
    {
        // Replace these with your ResellerClub API redentials
        // $authUserId = '205077';
        // $apiKey = 'XHkwYQZ37rYpL56ijS0CaMN1P0QjpJqq';
        $db = \Config\Database::connect();

        $requestPlugins = $db->table('hd_plugins')->select('config')->where('system_name', 'resellerclub')->get()->getRow()->config;

        $results = json_decode($requestPlugins);

        $authUserId = $results->auth_id;
        $apiKey = $results->api_key;

        $domain = 'madpopo.in';
        $value = 'mail';
        $newvalue = 'Chat Server';

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

        // Retrieve data from the request
        $data = $this->request->getPost();

        // Construct the API endpoint
        $endpoint = $this->apiUrl . 'dns/manage/update-txt-record.json';

        // Set up the HTTP client
        $client = new Client();
        $validation = \Config\Services::validation();

        // Prepare the request parameters
        $params = [
            "auth-userid" => $authUserId,
            "api-key" => $apiKey,
            "domain-name" => $domain,
            "current-value" => $value,
            "new-value" => $newvalue
        ];

        // Validation rules
        $rules = [
            'auth-userid' => 'required',
            'api-key' => 'required',
            'order-id' => ''
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
                return $this->response->setJSON($successResponse);
            } catch (\Exception $e) {
                echo "<pre>";
                print_r($e);
                echo "</pre>";
                die;
                // Handle API request failure
                $errorResponse = [
                    'status' => 'error',
                    'message' => 'Unsuccessful',
                ];

                return $this->response->setStatusCode(500)->setJSON($errorResponse);
            }
        }
    }

    public function modifying_srv_record()
    {
        // Replace these with your ResellerClub API redentials
        // $authUserId = '205077';
        // $apiKey = 'XHkwYQZ37rYpL56ijS0CaMN1P0QjpJqq';
        $db = \Config\Database::connect();

        $requestPlugins = $db->table('hd_plugins')->select('config')->where('system_name', 'resellerclub')->get()->getRow()->config;

        $results = json_decode($requestPlugins);

        $authUserId = $results->auth_id;
        $apiKey = $results->api_key;

        $domain = 'madpopo.in';
        $value = 'chat.madpopo.in';
        $newvalue = 'chat2.madpopo.in';
        $host = 'cyberpanel2';

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

        // Retrieve data from the request
        $data = $this->request->getPost();

        // Construct the API endpoint
        $endpoint = $this->apiUrl . 'dns/manage/update-srv-record.json';

        // Set up the HTTP client
        $client = new Client();
        $validation = \Config\Services::validation();

        // Prepare the request parameters
        $params = [
            "auth-userid" => $authUserId,
            "api-key" => $apiKey,
            "domain-name" => $domain,
            "current-value" => $value,
            "new-value" => $newvalue,
            "host" => $host
        ];

        // Validation rules
        $rules = [
            'auth-userid' => 'required',
            'api-key' => 'required',
            'order-id' => ''
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
                return $this->response->setJSON($successResponse);
            } catch (\Exception $e) {
                echo "<pre>";
                print_r($e);
                echo "</pre>";
                die;
                // Handle API request failure
                $errorResponse = [
                    'status' => 'error',
                    'message' => 'Unsuccessful',
                ];

                return $this->response->setStatusCode(500)->setJSON($errorResponse);
            }
        }
    }

    public function modifying_soa_record()
    {
        // Replace these with your ResellerClub API redentials
        // $authUserId = '205077';
        // $apiKey = 'XHkwYQZ37rYpL56ijS0CaMN1P0QjpJqq';
        $db = \Config\Database::connect();

        $requestPlugins = $db->table('hd_plugins')->select('config')->where('system_name', 'resellerclub')->get()->getRow()->config;

        $results = json_decode($requestPlugins);

        $authUserId = $results->auth_id;
        $apiKey = $results->api_key;
        $domain = 'madpopo.in';
        $responsible_person = 'anjali@version-next.com';
        $refresh = '7200';
        $retry = '7200';
        $expire = '172800';
        $ttl = '14400';

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

        // Retrieve data from the request
        $data = $this->request->getPost();

        // Construct the API endpoint
        $endpoint = $this->apiUrl . 'dns/manage/update-soa-record.json';

        // Set up the HTTP client
        $client = new Client();
        $validation = \Config\Services::validation();

        // Prepare the request parameters
        $params = [
            "auth-userid" => $authUserId,
            "api-key" => $apiKey,
            "domain-name" => $domain,
            "responsible-person" => $responsible_person,
            "refresh" => $refresh,
            "retry" => $retry,
            "expire" => $expire,
            "ttl" => $ttl
        ];

        // Validation rules
        $rules = [
            'auth-userid' => 'required',
            'api-key' => 'required',
            'domain-name' => '',
            'responsible-person' => '',
            'refresh' => '',
            'retry' => '',
            'expire' => '',
            'ttl' => '',
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
                return $this->response->setJSON($successResponse);
            } catch (\Exception $e) {
                echo "<pre>";
                print_r($e);
                echo "</pre>";
                die;
                // Handle API request failure
                $errorResponse = [
                    'status' => 'error',
                    'message' => 'Unsuccessful',
                ];

                return $this->response->setStatusCode(500)->setJSON($errorResponse);
            }
        }
    }

    public function searching_dns_record()
    {
        // Replace these with your ResellerClub API redentials
        // $authUserId = '205077';
        // $apiKey = 'XHkwYQZ37rYpL56ijS0CaMN1P0QjpJqq';
        $db = \Config\Database::connect();

        $requestPlugins = $db->table('hd_plugins')->select('config')->where('system_name', 'resellerclub')->get()->getRow()->config;

        $results = json_decode($requestPlugins);

        $authUserId = $results->auth_id;
        $apiKey = $results->api_key;
        $domain = 'madpopo.in';
        $type = 'A';
        $no_of_records = '2';
        $page_no = '1';

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

        // Retrieve data from the request
        $data = $this->request->getGet();

        // Construct the API endpoint
        $endpoint = $this->apiUrl . 'dns/manage/search-records.json';

        // Set up the HTTP client
        $client = new Client();
        $validation = \Config\Services::validation();

        // Prepare the request parameters
        $params = [
            "auth-userid" => $authUserId,
            "api-key" => $apiKey,
            "domain-name" => $domain,
            "type" => $type,
            "no-of-records" => $no_of_records,
            "page-no" => $page_no
        ];

        // Validation rules
        $rules = [
            'auth-userid' => 'required',
            'api-key' => 'required',
            'type' => '',
            'no-of-records' => '',
            'page-no' => '',
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
                $response = $client->get($endpoint, [
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
                return $this->response->setJSON($successResponse);
            } catch (\Exception $e) {
                echo "<pre>";
                print_r($e);
                echo "</pre>";
                die;
                // Handle API request failure
                $errorResponse = [
                    'status' => 'error',
                    'message' => 'Unsuccessful',
                ];

                return $this->response->setStatusCode(500)->setJSON($errorResponse);
            }
        }
    }

    public function delete_dns_record()
    {
        // Replace these with your ResellerClub API redentials
        // $authUserId = '205077';
        // $apiKey = 'XHkwYQZ37rYpL56ijS0CaMN1P0QjpJqq';
        $db = \Config\Database::connect();

        $requestPlugins = $db->table('hd_plugins')->select('config')->where('system_name', 'resellerclub')->get()->getRow()->config;

        $results = json_decode($requestPlugins);

        $authUserId = $results->auth_id;
        $apiKey = $results->api_key;
        $domain = 'madopop.in';
        $host = 'cyberpanel2';
        $value = 'ns1.domain.com';

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

        // Retrieve data from the request
        $data = $this->request->getGet();

        // Construct the API endpoint
        $endpoint = $this->apiUrl . 'dns/manage/delete-record.json';

        // Set up the HTTP client
        $client = new Client();
        $validation = \Config\Services::validation();

        // Prepare the request parameters
        $params = [
            "auth-userid" => $authUserId,
            "api-key" => $apiKey,
            "domain" => $domain,
            "host" => $host,
            "value" => $value,
        ];

        // Validation rules
        $rules = [
            'auth-userid' => 'required',
            'api-key' => 'required',
            'host' => '',
            'value' => '',
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
                $response = $client->get($endpoint, [
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
                return $this->response->setJSON($successResponse);
            } catch (\Exception $e) {
                echo "<pre>";
                print_r($e);
                echo "</pre>";
                die;
                // Handle API request failure
                $errorResponse = [
                    'status' => 'error',
                    'message' => 'Unsuccessful',
                ];

                return $this->response->setStatusCode(500)->setJSON($errorResponse);
            }
        }
    }

    public function delete_ipv4_address_record()
    {
        // Replace these with your ResellerClub API redentials
        // $authUserId = '205077';
        // $apiKey = 'XHkwYQZ37rYpL56ijS0CaMN1P0QjpJqq';
        $db = \Config\Database::connect();

        $requestPlugins = $db->table('hd_plugins')->select('config')->where('system_name', 'resellerclub')->get()->getRow()->config;

        $results = json_decode($requestPlugins);

        $authUserId = $results->auth_id;
        $apiKey = $results->api_key;
        $domain = 'madpopo.in';
        $host = 'cyberpanel2';
        $value = 'ns1.domain.com';

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

        // Retrieve data from the request
        $data = $this->request->getPost();

        // Construct the API endpoint
        $endpoint = $this->apiUrl . 'dns/manage/delete-ipv4-record.json';

        // Set up the HTTP client
        $client = new Client();
        $validation = \Config\Services::validation();

        // Prepare the request parameters
        $params = [
            "auth-userid" => $authUserId,
            "api-key" => $apiKey,
            "domain-name" => $domain,
            "host" => $host,
            "value" => $value,
        ];

        // Validation rules
        $rules = [
            'auth-userid' => 'required',
            'api-key' => 'required',
            'host' => '',
            'value' => '',
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
                return $this->response->setJSON($successResponse);
            } catch (\Exception $e) {
                echo "<pre>";
                print_r($e);
                echo "</pre>";
                die;
                // Handle API request failure
                $errorResponse = [
                    'status' => 'error',
                    'message' => 'Unsuccessful',
                ];

                return $this->response->setStatusCode(500)->setJSON($errorResponse);
            }
        }
    }

    public function delete_ipv6_address_record()
    {
        // Replace these with your ResellerClub API redentials
        // $authUserId = '205077';
        // $apiKey = 'XHkwYQZ37rYpL56ijS0CaMN1P0QjpJqq';
        $db = \Config\Database::connect();

        $requestPlugins = $db->table('hd_plugins')->select('config')->where('system_name', 'resellerclub')->get()->getRow()->config;

        $results = json_decode($requestPlugins);

        $authUserId = $results->auth_id;
        $apiKey = $results->api_key;
        $domain = 'madpopo.in';
        $host = 'cyberpanel2';
        $value = '393a:7dec:5ccc:a9f3:f607:c4d2:1275:882c';

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

        // Retrieve data from the request
        $data = $this->request->getPost();

        // Construct the API endpoint
        $endpoint = $this->apiUrl . 'dns/manage/delete-ipv6-record.json';

        // Set up the HTTP client
        $client = new Client();
        $validation = \Config\Services::validation();

        // Prepare the request parameters
        $params = [
            "auth-userid" => $authUserId,
            "api-key" => $apiKey,
            "domain-name" => $domain,
            "host" => $host,
            "value" => $value,
        ];

        // Validation rules
        $rules = [
            'auth-userid' => 'required',
            'api-key' => 'required',
            'host' => '',
            'value' => '',
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
                return $this->response->setJSON($successResponse);
            } catch (\Exception $e) {
                echo "<pre>";
                print_r($e);
                echo "</pre>";
                die;
                // Handle API request failure
                $errorResponse = [
                    'status' => 'error',
                    'message' => 'Unsuccessful',
                ];

                return $this->response->setStatusCode(500)->setJSON($errorResponse);
            }
        }
    }

    public function delete_cname_record()
    {
        // Replace these with your ResellerClub API redentials
        // $authUserId = '205077';
        // $apiKey = 'XHkwYQZ37rYpL56ijS0CaMN1P0QjpJqq';
        $db = \Config\Database::connect();

        $requestPlugins = $db->table('hd_plugins')->select('config')->where('system_name', 'resellerclub')->get()->getRow()->config;

        $results = json_decode($requestPlugins);

        $authUserId = $results->auth_id;
        $apiKey = $results->api_key;
        $domain = 'madpopo.in';
        $host = 'cyberpanel2';
        $value = 'ns1.domain.com';

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

        // Retrieve data from the request
        $data = $this->request->getPost();

        // Construct the API endpoint
        $endpoint = $this->apiUrl . 'dns/manage/delete-cname-record.json';

        // Set up the HTTP client
        $client = new Client();
        $validation = \Config\Services::validation();

        // Prepare the request parameters
        $params = [
            "auth-userid" => $authUserId,
            "api-key" => $apiKey,
            "domain-name" => $domain,
            "host" => $host,
            "value" => $value,
        ];

        // Validation rules
        $rules = [
            'auth-userid' => 'required',
            'api-key' => 'required',
            'host' => '',
            'value' => '',
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
                return $this->response->setJSON($successResponse);
            } catch (\Exception $e) {
                echo "<pre>";
                print_r($e);
                echo "</pre>";
                die;
                // Handle API request failure
                $errorResponse = [
                    'status' => 'error',
                    'message' => 'Unsuccessful',
                ];

                return $this->response->setStatusCode(500)->setJSON($errorResponse);
            }
        }
    }

    public function delete_mx_record()
    {
        // Replace these with your ResellerClub API redentials
        // $authUserId = '205077';
        // $apiKey = 'XHkwYQZ37rYpL56ijS0CaMN1P0QjpJqq';
        $db = \Config\Database::connect();

        $requestPlugins = $db->table('hd_plugins')->select('config')->where('system_name', 'resellerclub')->get()->getRow()->config;

        $results = json_decode($requestPlugins);

        $authUserId = $results->auth_id;
        $apiKey = $results->api_key;
        $domain = 'madpopo.in';
        $host = 'cyberpanel2';
        $value = 'ns1.domain.com';

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

        // Retrieve data from the request
        $data = $this->request->getGet();

        // Construct the API endpoint
        $endpoint = $this->apiUrl . 'dns/manage/delete-mx-record.json';

        // Set up the HTTP client
        $client = new Client();
        $validation = \Config\Services::validation();

        // Prepare the request parameters
        $params = [
            "auth-userid" => $authUserId,
            "api-key" => $apiKey,
            "domain-name" => $domain,
            "host" => $host,
            "value" => $value,
        ];

        // Validation rules
        $rules = [
            'auth-userid' => 'required',
            'api-key' => 'required',
            'host' => '',
            'value' => '',
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
                $response = $client->get($endpoint, [
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
                return $this->response->setJSON($successResponse);
            } catch (\Exception $e) {
                echo "<pre>";
                print_r($e);
                echo "</pre>";
                die;
                // Handle API request failure
                $errorResponse = [
                    'status' => 'error',
                    'message' => 'Unsuccessful',
                ];

                return $this->response->setStatusCode(500)->setJSON($errorResponse);
            }
        }
    }

    public function delete_ns_record()
    {
        // Replace these with your ResellerClub API redentials
        // $authUserId = '205077';
        // $apiKey = 'XHkwYQZ37rYpL56ijS0CaMN1P0QjpJqq';
        $db = \Config\Database::connect();

        $requestPlugins = $db->table('hd_plugins')->select('config')->where('system_name', 'resellerclub')->get()->getRow()->config;

        $results = json_decode($requestPlugins);

        $authUserId = $results->auth_id;
        $apiKey = $results->api_key;
        $domain = 'madpopo.in';
        $host = 'cyberpanel2';
        $value = 'ns1.domain.com';

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

        // Retrieve data from the request
        $data = $this->request->getPost();

        // Construct the API endpoint
        $endpoint = $this->apiUrl . 'dns/manage/delete-ns-record.json';

        // Set up the HTTP client
        $client = new Client();
        $validation = \Config\Services::validation();

        // Prepare the request parameters
        $params = [
            "auth-userid" => $authUserId,
            "api-key" => $apiKey,
            "domain-name" => $domain,
            "host" => $host,
            "value" => $value,
        ];

        // Validation rules
        $rules = [
            'auth-userid' => 'required',
            'api-key' => 'required',
            'host' => '',
            'value' => '',
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
                return $this->response->setJSON($successResponse);
            } catch (\Exception $e) {
                echo "<pre>";
                print_r($e);
                echo "</pre>";
                die;
                // Handle API request failure
                $errorResponse = [
                    'status' => 'error',
                    'message' => 'Unsuccessful',
                ];

                return $this->response->setStatusCode(500)->setJSON($errorResponse);
            }
        }
    }

    public function delete_txt_record()
    {
        // Replace these with your ResellerClub API redentials
        // $authUserId = '205077';
        // $apiKey = 'XHkwYQZ37rYpL56ijS0CaMN1P0QjpJqq';
        $db = \Config\Database::connect();

        $requestPlugins = $db->table('hd_plugins')->select('config')->where('system_name', 'resellerclub')->get()->getRow()->config;

        $results = json_decode($requestPlugins);

        $authUserId = $results->auth_id;
        $apiKey = $results->api_key;
        $domain = 'madpopo.in';
        $host = 'cyberpanel2';
        $value = 'ns1.domain.com';

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

        // Retrieve data from the request
        $data = $this->request->getGet();

        // Construct the API endpoint
        $endpoint = $this->apiUrl . 'dns/manage/delete-txt-record.json';

        // Set up the HTTP client
        $client = new Client();
        $validation = \Config\Services::validation();

        // Prepare the request parameters
        $params = [
            "auth-userid" => $authUserId,
            "api-key" => $apiKey,
            "domain-name" => $domain,
            "host" => $host,
            "value" => $value,
        ];

        // Validation rules
        $rules = [
            'auth-userid' => 'required',
            'api-key' => 'required',
            'host' => '',
            'value' => '',
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
                $response = $client->get($endpoint, [
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
                return $this->response->setJSON($successResponse);
            } catch (\Exception $e) {
                echo "<pre>";
                print_r($e);
                echo "</pre>";
                die;
                // Handle API request failure
                $errorResponse = [
                    'status' => 'error',
                    'message' => 'Unsuccessful',
                ];

                return $this->response->setStatusCode(500)->setJSON($errorResponse);
            }
        }
    }

    public function delete_srv_record()
    {
        // Replace these with your ResellerClub API redentials
        // $authUserId = '205077';
        // $apiKey = 'XHkwYQZ37rYpL56ijS0CaMN1P0QjpJqq';
        $db = \Config\Database::connect();

        $requestPlugins = $db->table('hd_plugins')->select('config')->where('system_name', 'resellerclub')->get()->getRow()->config;

        $results = json_decode($requestPlugins);

        $authUserId = $results->auth_id;
        $apiKey = $results->api_key;
        $domain = 'madpopo.in';
        $host = 'cyberpanel2';
        $value = 'ns1.domain.com';
        $port = '8090';
        $weight = '0';

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

        // Retrieve data from the request
        $data = $this->request->getPost();

        // Construct the API endpoint
        $endpoint = $this->apiUrl . 'dns/manage/delete-srv-record.json';

        // Set up the HTTP client
        $client = new Client();
        $validation = \Config\Services::validation();

        // Prepare the request parameters
        $params = [
            "auth-userid" => $authUserId,
            "api-key" => $apiKey,
            "domain-name" => $domain,
            "host" => $host,
            "value" => $value,
            "port" => $port,
            "weight" => $weight,
        ];

        // Validation rules
        $rules = [
            'auth-userid' => 'required',
            'api-key' => 'required',
            'host' => '',
            'value' => '',
            'port' => '',
            'weight' => '',
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
                return $this->response->setJSON($successResponse);
            } catch (\Exception $e) {
                echo "<pre>";
                print_r($e);
                echo "</pre>";
                die;
                // Handle API request failure
                $errorResponse = [
                    'status' => 'error',
                    'message' => 'Unsuccessful',
                ];

                return $this->response->setStatusCode(500)->setJSON($errorResponse);
            }
        }
    }

    public function cust_sign_up($co_id)
    {
        // Replace these with your ResellerClub API redentials
        $db = \Config\Database::connect();
        $query = $db->table('hd_companies')->where('co_id', $co_id)->get()->getRow();

        helper("text");
        //print_r($query);die;
        //$controller = new new_cPanel();
        //$password = $this->random_password(12);
        //print_r($password);die;
        // Retrieve data from the request
        ///$data = $this->request->getPost();
        //$country = 'india';
        // Construct the API endpoint
        // $endpoint = $this->apiUrl . 'customers/v2/signup.xml';
        // Set up the HTTP client
        $client = new Client();

        $validation = \Config\Services::validation();

        // Prepare the request parameters
        $apiUrl = "https://test.httpapi.com/api/customers/v2/signup.xml";
        // $authUserId = '205077';
        // $apiKey = 'XHkwYQZ37rYpL56ijS0CaMN1P0QjpJqq';
        $db = \Config\Database::connect();

        $requestPlugins = $db->table('hd_plugins')->select('config')->where('system_name', 'resellerclub')->get()->getRow()->config;

        $results = json_decode($requestPlugins);

        $authUserId = $results->auth_id;
        $apiKey = $results->api_key;
        $email = $query->company_email;
        $password = "Pioneer@7";
        $name = $query->first_name;
        $company = $query->company_name;
        $address = $query->company_address;
        $city = $query->city;
        $state = $query->state;
        $country = "IN";
        $zipcode = $query->zip;
        $phoneCc = '91';
        $phone = $query->company_mobile;
        $langPref = "en";

        $url = $apiUrl . "?auth-userid=$authUserId&api-key=$apiKey&username=$email&passwd=$password&name=$name&company=$company&address-line-1=$address&city=$city&state=$state&country=$country&zipcode=$zipcode&phone-cc=$phoneCc&phone=$phone&lang-pref=$langPref";
        // Validation rules
        //echo"<pre>";print_r($params);die;
        print_r($url);
        die;
        $rules = [
            'auth-userid' => 'required',
            'api-key' => 'required',
        ];

        // $validation->setRules($rules);

        // Adjust the HTTP status code as needed
        // } else {

        try {

            $response = $client->post($url);
            //echo 90;die;
            // Check if the response is successful (status code 200)
            if ($response->getStatusCode() == 200) {
                // Get the response body as string
                $xmlResponse = $response->getBody()->getContents();

                // Use SimpleXMLElement to parse the XML
                $xml = simplexml_load_string($xmlResponse);

                // Extract the customer ID from XML
                $customerId = (int)$xml;
                //print_r($customerId);die;
                // Prepare the success response
                $successResponse = [
                    'status' => 'success',
                    'data' => ['customer_id' => $customerId],
                ];
                //print_r($successResponse);die;
                // Return the success response
                return $this->response->setJSON($successResponse);
            } else {
                // Handle non-200 response (e.g., error)
                // Prepare error response
                $errorResponse = [
                    'status' => 'error',
                    'message' => 'Failed to get customer ID from the API.',
                ];

                // Return the error response
                return $this->response->setJSON($errorResponse);
            }
        } catch (\Exception $e) {
            echo "<pre>";
            print_r($e);
            echo "</pre>";
            die;
            // Handle exceptions
            // Prepare error response
            $errorResponse = [
                'status' => 'error',
                'message' => $e
            ];

            // Return the error response
            return $this->response;
        }

        //}
    }

    function random_password($length = 8)
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!@#$%^&*()_+=-<>?/,.';
        $password = str_shuffle($alphabet);
        return substr($password, 0, $length);
    }

    public function cust_details_by_username($co_id)
    {
        // Connect to the database
        $db = \Config\Database::connect();

        $requestPlugins = $db->table('hd_plugins')->select('config')->where('system_name', 'resellerclub')->get()->getRow()->config;

        $results = json_decode($requestPlugins);

        $authUserId = $results->auth_id;
        $apiKey = $results->api_key;

        // Fetch all users' emails from the database
        $companies = $db->table('hd_companies')->where('co_id', $co_id)->get()->getRow();
        $users = $db->table('hd_users')->where('id', $companies->primary_contact)->get()->getRow();
        $params = [
            "auth-userid" => $authUserId,
            "api-key" => $apiKey,
            "username" => $users->email
        ];

        // $authUserId = 205077;
        // $apiKey = 'XHkwYQZ37rYpL56ijS0CaMN1P0QjpJqq';
        $username = $users->email;
        // print_r($params);die;
        // Construct the API endpoint
        $endpoint = $this->apiUrl . "customers/details.json?auth-userid=$authUserId&api-key=$apiKey&username=$username";
        // Set up the HTTP client
        $client = new Client();
        try {

            // Make the API request for each user
            $response = $client->get($endpoint);

            // Process the API response
            $responseData = json_decode($response->getBody()->getContents(), true);

            // echo"<pre>";print_r($responseData);die;

            $details = [
                'customerid' => $responseData['customerid'],
                'name' => $responseData['name'],
                'username' => $responseData['username'],
                'useremail' => $responseData['useremail'],
                'parentid' => $responseData['parentid'],
                'salespersonshash' => $responseData['salespersonshash'],
                'resellerid' => $responseData['resellerid'],
                'customerstatus' => $responseData['customerstatus'],
            ];

            $db = \Config\Database::connect();

            $db->table('hd_resellerclub_customer_details')->insert($details);

            // Store the response data
            $responses[] = $responseData;
            //echo"<pre>";print_r($responses);die;

            $successResponse = [
                'status' => 'success',
                'message' => 'user exists',
                'data' => $responseData,
            ];

            return json_encode($successResponse);
            //return $this->response;
        } catch (\Exception $e) {
            //print_r($e);die;
            // Handle API request failure
            $errorResponse = [
                'status' => 'error',
                'message' => $e // Adjust to get specific error message
            ];
            return json_encode($errorResponse);
        }
    }


    public function get_contact_details($co_id)
    {
        // echo $co_id;die;
        $db = \Config\Database::connect();

        $requestPlugins = $db->table('hd_plugins')->select('config')->where('system_name', 'resellerclub')->get()->getRow()->config;

        $results = json_decode($requestPlugins);

        $authUserId = $results->auth_id;
        $apiKey = $results->api_key;

        $company_detail = $db->table('hd_companies')->where('co_id', $co_id)->get()->getRow();

        //$user = $db->table('hd_users')->select('id, email')->where('id', $co_id)->get()->getRow();
        $user = $db->table('hd_users')
            ->select('hd_users.id, hd_users.email, hd_resellerclub_customer_details.*')
            ->join('hd_resellerclub_customer_details', 'hd_resellerclub_customer_details.username = hd_users.email')
            ->where('hd_users.id', $company_detail->primary_contact)
            ->get()
            ->getRow();

        // echo $db->getLastQuery();die;

        // Replace these with your ResellerClub API redentials
        // $authUserId = '205077';
        // $apiKey = 'XHkwYQZ37rYpL56ijS0CaMN1P0QjpJqq';
        $customerId = $user->customerid;
        $type = 'Contact';

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
        $endpoint = $this->apiUrl . 'contacts/default.json';

        // Set up the HTTP client
        $client = new Client();
        $validation = \Config\Services::validation();

        // Prepare the request parameters
        $params = [
            "auth-userid" => $authUserId,
            "api-key" => $apiKey,
            "customer-id" => $customerId,
            "type" => $type,
        ];

        // Validation rules
        $rules = [
            'auth-userid' => 'required',
            'api-key' => 'required',
            'customer-id' => '',
            'type' => '',
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
                ]);
                //echo 80;die;
                // Process the API response
                $responseData = json_decode($response->getBody(), true);

                $contact_id =  $responseData['Contact']['registrantContactDetails']['contact.contactid'];

                $data = [
                    'registrant' => $responseData['Contact']['registrant'],
                    'tech' => $responseData['Contact']['tech'],
                    'billing' => $responseData['Contact']['billing'],
                    'admin' => $responseData['Contact']['admin'],
                ];

                // Construct the API endpoint
                $endpoint = $this->apiUrl . 'contacts/details.json';

                // Prepare the request parameters
                $params = [
                    "auth-userid" => $authUserId,
                    "api-key" => $apiKey,
                    "contact-id" => $contact_id
                ];

                $response = $client->post($endpoint, [
                    'form_params' => $params,
                ]);

                $responseDataContact = json_decode($response->getBody(), true);

                $dataContact = [
                    'co_id' => $co_id,
                    'contact_id' => $responseDataContact['contactid'],
                    'email_addr' => $responseDataContact['emailaddr'],
                    'zip' => $responseDataContact['zip'],
                    'address1' => $responseDataContact['address1'],
                    'address2' => '',
                    'telnocc' => $responseDataContact['telnocc'],
                    'city' => $responseDataContact['city'],
                    'contact_status' => $responseDataContact['contactstatus'],
                    'eaqid' => $responseDataContact['eaqid'],
                    'company' => $responseDataContact['company'],
                    'type' => $responseDataContact['type'],
                    'description' => $responseDataContact['description'],
                    'tel_no' => $responseDataContact['telno'],
                    'state' => $responseDataContact['state'],
                    'name' => $responseDataContact['name'],
                    'country' => $responseDataContact['country'],
                    'entityid' => $responseDataContact['entityid'],
                    'entitytypeid' => $responseDataContact['entitytypeid'],
                    'currentstatus' => $responseDataContact['currentstatus'],
                    'customerid' => $responseDataContact['customerid'],
                    'created_on' => date('Y-m-d H:i:s')
                ];

                $db->table('hd_resellerclub_customer_contact')->insert($dataContact);

                $db->table('hd_resellerclub_customer_details')->where('customerid', $user->customerid)->update($data);

                // Example response
                $successResponse = [
                    'status' => 'success',
                    //'message' => 'Successfully',
                    'data' => $responseData,
                    'dataContact' => $responseDataContact
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

    //Ketan
    public function modify_contact_details($params)
    {
        $db = \Config\Database::connect();

        $requestPlugins = $db->table('hd_plugins')->select('config')->where('system_name', 'resellerclub')->get()->getRow()->config;

        $results = json_decode($requestPlugins);

        $authUserId = $results->auth_id;
        $apiKey = $results->api_key;

        $request = \Config\Services::request();

        $session = \Config\Services::session();

        $hd_resellerclub_detail = $db->table('hd_plugins')->select('config')->where('system_name', 'resellerclub')->get()->getRow()->config;

        $contact_detail = $db->table('hd_resellerclub_customer_contact')->where('co_id', $params['co_id'])->get()->getRow();

        // Replace these with your ResellerClub API redentials
        // $authUserId = '205077';
        // $apiKey = 'XHkwYQZ37rYpL56ijS0CaMN1P0QjpJqq';

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

        // Set up the HTTP client
        $client = new Client();
        $validation = \Config\Services::validation();

        // Construct the API endpoint
        $endpoint = $this->apiUrl . 'contacts/modify.json';

        // Prepare the request parameters
        $paramsData = [
            "auth-userid" => $authUserId,
            "api-key" => $apiKey,
            "contact-id" => $params['contact-id'],
            "name" => $params['name'],
            "company" => $params['company'],
            "email" => $params['email'],
            "address-line-1" => $params['address-line-1'],
            "address-line-2" => $params['address-line-2'],
            "city" => $params['city'],
            "country" => $params['country'],
            "zipcode" => $params['zipcode'],
            "phone-cc" => $params['phone-cc'],
            "phone" => $params['phone']
        ];

        // Validation rules
        $rules = [
            'auth-userid' => 'required',
            'api-key' => 'required',
            'contact-id' => 'required'
        ];

        if ($paramsData === FALSE) {
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
                $response = $client->post($endpoint, [
                    'form_params' => $paramsData,
                ]);

                $responseData = json_decode($response->getBody(), true);

                $data = [
                    "name" => $params['name'],
                    "company" => $params['company'],
                    "email_addr" => $params['email'],
                    "address1" => $params['address-line-1'],
                    "address2" => $params['address-line-2'],
                    "city" => $params['city'],
                    "state" => $params['state'],
                    "country" => $params['country'],
                    "zip" => $params['zipcode'],
                    "telnocc" => $params['phone-cc'],
                    "tel_no" => $params['phone'],
                    "updated_by" => $session->get('userdata.user_id'),
                    "updated_on" => date('Y-m-d H:i:s')
                ];

                $db->table('hd_resellerclub_customer_contact')->where('con_id', $contact_detail->con_id)->where('co_id', $params['co_id'])->update($data);

                // Example response
                $successResponse = [
                    'status' => 'success',
                    //'message' => 'Successfully',
                    'data' => $responseData
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


    public function tlds_customer_pricing()
    {
        error_reporting(E_ALL);
        ini_set("display_errors", "1");
        // $controller = new CustomerPrice();
        // $method = $controller->api_customerprice();
        $db = \Config\Database::connect();
        //$user = $db->table('hd_users')->select('id, email')->where('id', $co_id)->get()->getRow();
        // Replace these with your ResellerClub API redentials
        // $authUserId = '205077';
        // $apiKey = 'XHkwYQZ37rYpL56ijS0CaMN1P0QjpJqq';

        $requestPlugins = $db->table('hd_plugins')->select('config')->where('system_name', 'resellerclub')->get()->getRow()->config;

        $results = json_decode($requestPlugins);

        $authUserId = $results->auth_id;
        $apiKey = $results->api_key;

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
            // Handle validation errors
            $validationErrors = $this->validator->getErrors();
            $errorResponse = [
                'status' => 'error',
                'message' => $validationErrors,
            ];
            return $this->response->setStatusCode(400)->setJSON($errorResponse); // Adjust the HTTP status code as needed
        } else { 
            try {
                // Make the API request
                $response = $client->get($endpoint, [
                    'query' => $params,
                ]);
                // Process the API response
                $responseData = json_decode($response->getBody(), true);
                //echo"<pre>";print_r($responseData);die;

                $db = \Config\Database::connect();

                $query = $db->table('hd_domains')->get()->getResult();

                foreach ($query as $key => $queries) {
                    $result = $queries->ext_name;

                    if ($queries->ext_name == ".com") {
                        $results = str_replace(".com", "dotcompany", $result);

                        $replace[] = $results;
                    }
                    $replace[] = str_replace(".", "dot", $result);
                }

                //echo "<pre>";print_r($replace);die;

                // $res[] = array_intersect_key($responseData,$replace);
                $builder = $db->table('hd_customer_pricing')->truncate();
                $builder = $db->table('hd_customer_pricing_view')->truncate();

                $res = [];

                // Establish the database connection outside the loop
                $db = \Config\Database::connect();

                $dataReg = [];
                $dataren = [];

                foreach ($responseData as $key => $value) {
                    foreach ($replace as $replaces) {
                        if ($key == $replaces) {
                            foreach (['addnewdomain', 'restoredomain', 'renewdomain', 'addtransferdomain'] as $service) {
                                for ($duration = 1; $duration <= 10; $duration++) {
                                    $data = array(
                                        "domain_name" => $key,
                                        "service_type" => $service,
                                        "duration" => $duration,
                                        "cost" => isset($value[$service][$duration]) ? $value[$service][$duration] : 0.00,
                                    );
                                    // Insert the data into the database
                                    $query = $db->table('hd_customer_pricing')->insert($data);

                                    $replaceExt = str_replace("dot", ".", $key);

                                    if ($replaceExt == ".company") {
                                        $replaceExt = str_replace(".company", ".com", $replaceExt);
                                    }

                                    if ($service == 'addnewdomain') {
                                        $dataReg['registration_' . $duration] = $value[$service][$duration];
                                        $dataReg['registration_1'] = $value[$service][1];
                                    }

                                    if ($service == 'renewdomain') {
                                        $dataren['renewal_' . $duration] = $value[$service][$duration];
                                        $dataReg['renewal_1'] = $value[$service][1];
                                    }

                                    if ($service == 'addtransferdomain') {
                                        $dataTrans = $value['addtransferdomain'][1];
                                    }
                                }
                            }

                            $dataNew = array(
                                "ext_name" => $replaceExt,
                                "registrar" => 'resellerclub',
                                "registration_data" => $dataReg,
                                "renewal_data" => $dataren,
                            );

                            foreach ($dataNew["registration_data"] as $key => $value) {
                                if (strpos($key, 'registration_') === 0) {
                                    $index = intval(substr($key, strlen('registration_')));
                                    $dataNew['registration_' . $index] = $value;
                                }
                            }

                            foreach ($dataNew["renewal_data"] as $key => $value) {
                                if (strpos($key, 'renewal_') === 0) {
                                    $index = intval(substr($key, strlen('renewal_')));
                                    $dataNew['renewal_' . $index] = $value;
                                }
                            }

                            $dataNew['transfer_1'] = $dataTrans;

                            // Remove registration_data and renewal_data from $dataNew
                            unset($dataNew["registration_data"]);
                            unset($dataNew["renewal_data"]);
                            $helper = new custom_name_helper();

                        $category = $db->table('hd_domains')->select('category')->where('ext_name', $replaceExt)->get()->getRow()->category;

                            $items_saved_data = [
                                'item_name' =>  $replaceExt,
                                'item_tax_rate' => $helper->getconfig_item('default_tax'),
                                'create_account' => "Yes",
								'item_type' => 'api',
                                'default_registrar'  => $helper->getconfig_item('domain_checker'),
                                'max_years' => 10
                            ];

                            // echo "<pre>";print_r($dataNew);die;

                            $db->table('hd_customer_pricing_view')->insert($dataNew);
                            $db->table('hd_items_saved')->insert($items_saved_data);

                            // Get the last inserted ID
                            $itemid = $db->insertID();

                            $items_pricing_data = [
                                'ext_name' => $replaceExt,
                                'item_id' => $itemid,
                                'registration' => $dataReg['registration_1'],
                                'transfer'  => $dataTrans,
                                'renewal'  => $dataReg['renewal_1'],
                                'category' => $category
                            ];

                            $itempricing = $db->table('hd_item_pricing')->insert($items_pricing_data);
                            //print_r($itempricing);die;
							echo"<pre>";print_r($responseData);die;
                        }
                    }
                }

				
                echo "<pre>";
                print_r($responseData);
                die;
                // Example response
                $successResponse = [
                    'status' => 'success',
                    //'message' => 'Successfully',
                    'data' => $responseData,
                ];

                return json_encode($successResponse);
            } catch (\Exception $e) {
                echo "<pre>";
                print_r($e);
                die;
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


    public function domain_name_is_premium()
    {
        $db = \Config\Database::connect();

        $requestPlugins = $db->table('hd_plugins')->select('config')->where('system_name', 'resellerclub')->get()->getRow()->config;

        $results = json_decode($requestPlugins);

        $authUserId = $results->auth_id;
        $apiKey = $results->api_key;

        //$user = $db->table('hd_users')->select('id, email')->where('id', $co_id)->get()->getRow();
        // Replace these with your ResellerClub API redentials
        // $authUserId = '205077';
        // $apiKey = 'XHkwYQZ37rYpL56ijS0CaMN1P0QjpJqq';
        $domain = 'jsgdusgds.com';

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
        $endpoint = $this->apiUrl . 'domains/premium-check.json';

        // Set up the HTTP client
        $client = new Client();
        $validation = \Config\Services::validation();

        // Prepare the request parameters
        $params = [
            "auth-userid" => $authUserId,
            "api-key" => $apiKey,
            "domain-name" => $domain,
        ];

        // Validation rules
        $rules = [
            'auth-userid' => 'required',
            'api-key' => 'required',
            'domain-name' => '',
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


    public function get_tlds_details()
    {
        // Replace these with your ResellerClub API redentials
        // $authUserId = '205077';
        // $apiKey = 'XHkwYQZ37rYpL56ijS0CaMN1P0QjpJqq';

        $requestPlugins = $db->table('hd_plugins')->select('config')->where('system_name', 'resellerclub')->get()->getRow()->config;

        $results = json_decode($requestPlugins);

        $authUserId = $results->auth_id;
        $apiKey = $results->api_key;


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
        $endpoint = $this->apiUrl . 'domains/tlds.json';

        // Set up the HTTP client
        $client = new Client();
        $validation = \Config\Services::validation();

        // Prepare the request parameters
        $params = [
            "auth-userid" => $authUserId,
            "api-key" => $apiKey
        ];

        // Validation rules
        $rules = [
            'auth-userid' => 'required',
            'api-key' => 'required'
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
                $response = $client->get($endpoint, [
                    'form_params' => $params
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


    public function x_rates($get_currency_lowercase)
    {
        error_reporting(E_ALL);
        ini_set("display_errors", "1");
        $get_currency_lowercasess = explode(' ', $get_currency_lowercase);

        $client = new Client();

        $response = $client->get('https://latest.currency-api.pages.dev/v1/currencies/usd.json');

        // Get the response body as a string
        $body = $response->getBody()->getContents();

        // Decode JSON response
        $data = json_decode($body, true);
        //$result = isset($data[$get_currency_lowercasess[0]][$get_currency_lowercasess[1]]) ? 
        //$data[$get_currency_lowercasess[0]][$get_currency_lowercasess[1]] : 
        //null;
        //echo"<pre>";print_r($data);die;
        //echo"<pre>";print_r($data['usd']['inr']);die;
        $currency_price = null;
        foreach ($data['usd'] as $currency => $price) {
            if ($currency == $get_currency_lowercasess[0]) {
                $currency_price = $price;
                break;
            }
        }
        // Print the response data
        return $currency_price;
    }

    public function resellerclub_renewal_info()
    {
        $db = \Config\Database::connect();

        $request = \Config\Services::request();

        $domain = $request->getPost('domain');

        $domain_arr = explode('.', $domain);

        $tlds = $domain_arr[1];

        $tld_details = $db->table('hd_customer_pricing_view')->where('ext_name',  '.' . $tlds)->get()->getRow();

        // Create an array to store renewal data
        $renewal_data = [];

        // Loop through the object properties
        foreach ($tld_details as $key => $value) {
            // Check if the property starts with 'renewal_'
            if (strpos($key, 'renewal_') === 0) {
                // If it does, add it to the renewal data array
                $renewal_data[$key] = $value;
            }
        }

        return json_encode(array('data' => $renewal_data));
    }

    public function submit_renewal_details()
    {
        $db = \Config\Database::connect();

        $request = \Config\Services::request();

        // Replace these with your ResellerClub API redentials
        // $authUserId = '205077';
        // $apiKey = 'XHkwYQZ37rYpL56ijS0CaMN1P0QjpJqq';

        $requestPlugins = $db->table('hd_plugins')->select('config')->where('system_name', 'resellerclub')->get()->getRow()->config;

        $results = json_decode($requestPlugins);

        $authUserId = $results->auth_id;
        $apiKey = $results->api_key;

        $domain_name = $request->getPost('domain');


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
        $endpoint = $this->apiUrl . "domains/orderid.json?auth-userid=$authUserId&api-key=$apiKey&domain-name=$domain_name";

        // Set up the HTTP client
        $client = new Client();
        $validation = \Config\Services::validation();

        // Prepare the request parameters
        $params = [
            "auth-userid" => $authUserId,
            "api-key" => $apiKey,
            "domain-name" => $request->getPost('domain')
        ];

        // Validation rules
        $rules = [
            'auth-userid' => 'required',
            'api-key' => 'required',
            'domain-name' => 'required'
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
                $response = $client->get($endpoint);

                $order_id_of_domain = json_decode($response->getBody()->getContents(), true);

                $years = $request->getPost('renewal_year');

                $order_details = $db->table('hd_orders')->where('domain', $domain_name)->get()->getRow();

                $epoch = strtotime($order_details->renewal_date);

                $renewEndpoint = $this->apiUrl . "domains/renew.json?auth-userid=$authUserId&api-key=$apiKey&order-id=$order_id_of_domain&years=$years&exp-date=$epoch&invoice-option=NoInvoice&discount-amount=0.0";

                $responseRenewal = $client->post($renewEndpoint);

                $responseRenewalData = json_decode($responseRenewal->getBody()->getContents(), true);

                // Example response
                $successResponse = [
                    'status' => 'success',
                    'message' => 'Domain Renewed Successfully' . $domain_name,
                    'data' => $responseRenewalData,
                ];

                return json_encode($successResponse);
            } catch (\Exception $e) {
                // Example response
                $errorResponse = [
                    'status' => 'error',
                    'message' => 'Error for renewing domain ' . $domain_name,
                    'data' => $e,
                ];

                return json_encode($errorResponse);
            }
        }
    }

    function get_eep_code()
    {
        $db = \Config\Database::connect();

        $request = \Config\Services::request();

        // Replace these with your ResellerClub API redentials
        // $authUserId = '205077';
        // $apiKey = 'XHkwYQZ37rYpL56ijS0CaMN1P0QjpJqq';

        $requestPlugins = $db->table('hd_plugins')->select('config')->where('system_name', 'resellerclub')->get()->getRow()->config;

        $results = json_decode($requestPlugins);

        $authUserId = $results->auth_id;
        $apiKey = $results->api_key;

        $domain_name = $request->getPost('domain');

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
        $endpoint = $this->apiUrl . "domains/details-by-name.json?auth-userid=$authUserId&api-key=$apiKey&domain-name=$domain_name&options=OrderDetails";

        // Set up the HTTP client
        $client = new Client();
        $validation = \Config\Services::validation();

        // Prepare the request parameters
        $params = [
            "auth-userid" => $authUserId,
            "api-key" => $apiKey,
            "domain-name" => $request->getPost('domain')
        ];

        // Validation rules
        $rules = [
            'auth-userid' => 'required',
            'api-key' => 'required',
            'domain-name' => 'required'
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
                $response = $client->get($endpoint);

                $domain_details = json_decode($response->getBody()->getContents(), true);

                return json_encode(array('data' => $domain_details));
            } catch (\Exception $e) {
                // Example response
                $errorResponse = [
                    'status' => 'error',
                    'message' => 'Website doesn\'t exist for ' . $domain_name,
                    'data' => $e,
                ];

                return json_encode($errorResponse);
            }
        }
    }

    function transfer_page()
    {
        $session = \Config\Services::session();

        $user_co_id = $session->get('userdata')['user_id'];

        $db = \Config\Database::connect();

        $request = \Config\Services::request();

        $users = $db->table('hd_users')
            ->select('hd_users.*, hd_companies.*')
            ->join('hd_companies', 'hd_users.id = hd_companies.primary_contact')
            ->where('hd_users.id !=', 1)
            ->where('hd_users.id !=', $user_co_id)
            ->get()->getResult();

        return json_encode(array('data' => $users));
    }
}
