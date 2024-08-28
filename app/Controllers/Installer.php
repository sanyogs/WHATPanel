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

use App\ThirdParty\MX\MX_Controller;
use App\Helpers\app_helper;
use GuzzleHttp\Client;
use App\Helpers\AuthHelper;
use CodeIgniter\Files\File;
use Config\Database; 
use CodeIgniter\Config\Services;
use App\Libraries\Tank_auth;

class Installer extends MX_Controller
{
    private $update_url;
	
	private $endpoint = 'api.php';

    protected $session;

    public function __construct()
    {
        error_reporting(E_ALL);
    	ini_set('display_errors', 1);
		
        parent::__construct();
        
        helper(['url']);
    }

    public function index()
    { 
        $myfile = fopen(APPPATH."/Config/alldone.txt", "r") or die("Unable to open file!");
        $result = fread($myfile, filesize(APPPATH."/Config/alldone.txt"));
        fclose($myfile);
		 //var_dump(trim($result));die;
        if(trim($result) == 'Done'){
			// echo 232;die;
            return redirect()->to('home');
        }else{
            return view('install');
        }
    }

    public function _check_install()
    {
        if (is_file('./app/config/installed.txt')) {
            return true;
        }
        return false;
    }

    public function _installed()
    {
        $this->_enable_system_access();
        $this->_change_routing();
        redirect();
    }

    public function start()
    {	
        // redirect('installer/?step=2', 'refresh');
        return redirect()->to('installer_steps/2'); 
    }

    public function installer_steps($step = null) {
        $data['step'] = $step;
        
        // Load the view and pass data to it
        return view('install', $data);
    }

    public function db_setup()
    {
        // echo APPPATH;die;
        try{
            $db_connect = $this->verify_db_connection();
        }catch(\Exception $e) {
            print_r($e);
        }

        if ($db_connect) {
            $create_config = $this->_create_db_config();

            $this->_step_complete('database_setting', '4');

            $request = \Config\Services::request();

            // redirect('installer/?step=3');
            return redirect()->to('installer_steps/4'); 
        } else {
            // $this->session->setFlashdata('message', 'Database connection failed please try again.');
            // redirect('installer/?step=2');
            return redirect()->to('installer_steps/3'); 
        }
    }

	public function license()
	{
		// echo APPPATH;die;
        try{
		   $db = \Config\Database::connect();
		   $request = \Config\Services::request();
		   $baseURL = env('app.baseURL');
		   $trim = trim($baseURL,"https:/");
           if ($request->getPost()) {
			   if (isset($_POST['key']) && !empty(trim($_POST['key']))) {
				$client = new Client();
				$url = $this->club() . $this->endpoint;
				$apiKey = $request->getPost('key');
				session()->set('key', $apiKey);
				$body = [
					'action' => 'check_license',
					//'domain_name' => $trim,
					'domain_name' => "sanyog.com",
					'license' => $apiKey
				];
				
				try {
                    $client = new \GuzzleHttp\Client([
                        'verify' => false, // or use a path to cacert.pem for a more secure approach
                    ]);
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

					 // Check if the status indicates an error
					if (isset($data['status']) && $data['status'] === 'error') {
						session()->setFlashdata('message', $data['message']);
						return redirect()->to('installer_steps/2');
					}					
					// Otherwise, pass the data to the view
					return redirect()->to('installer_steps/3');

				} catch (\GuzzleHttp\Exception\RequestException $e) {
					return $this->response->setStatusCode(500)->setBody('An error occurred: ' . $e->getMessage());
				}
			}
        } else {  
            return redirect()->to('installer_steps/2'); 
        }
       }catch(\Exception $e) { 
		   return redirect()->to('installer_steps/2'); 
           print_r($e);
        }
	}
	
    public function install()
	{
		$session = session();
		$session->set('lang', 'english');
        $baseURL = env('app.baseURL');
		$version = config('App')->version;
		//echo $version; die;
		if (!$this->_initialize_db($version)) {
			$session->setFlashdata('message', 'Database import failed. Check if the file exists:' . $baseURL . $version . '.sql');
			return redirect()->to('installer_steps/4');
		}

		$this->_step_complete('verify_purchase', '5');
		return redirect()->to('installer_steps/5');
	}



    public function complete()
    {
        $this->_enable_system_access();

        $this->_create_admin_account();

        $this->_change_routing();

        $this->_change_htaccess();

        // $this->session->destroy();

        // redirect('installer/done');
        return redirect()->to('done');
    }

    public function done()
    {
		// Define the file path
        $filePath = APPPATH."/Config/alldone.txt";

        // Open the file for writing, or create it if it doesn't exist
        $file = fopen($filePath, "w") or die("Unable to open file!");

        // Write the value "Done" into the file
        fwrite($file, "Done");
				
        // Close the file
        fclose($file);
		
		$random_number = session()->get('key');
		
		$baseURL = env('app.baseURL');
		
		$trim = trim($baseURL,"https:/");

		$db = \Config\Database::connect();
		
		$db->table('hd_config')->where('config_key', 'salt_key')->update(['value' => $random_number]);
		$db->table('hd_config')->where('config_key', 'salt_domain')->update(['value' => $trim]);
		
		$builder = $db->table('hd_blocks_modules');

		$data = [
			['id' => 6, 'name' => 'Main Menu', 'settings' => '', 'param' => 'menus_1', 'type' => 'Module', 'module' => 'Menus'],
			['id' => 7, 'name' => 'Home Slider', 'settings' => 'a:1:{s:5:"title";s:2:"no";}', 'param' => 'sliders_7', 'type' => 'Module', 'module' => 'Sliders'],
			['id' => 9, 'name' => 'cPanel Hosting', 'settings' => 'a:1:{s:5:"title";s:2:"no";}', 'param' => 'items_12', 'type' => 'Module', 'module' => 'Items'],
			['id' => 10, 'name' => 'Website Packages', 'settings' => 'a:1:{s:5:"title";s:2:"no";}', 'param' => 'items_13', 'type' => 'Module', 'module' => 'Items'],
			['id' => 12, 'name' => 'Plesk Hosting', 'settings' => 'a:1:{s:5:"title";s:2:"no";}', 'param' => 'items_19', 'type' => 'Module', 'module' => 'Items'],
			['id' => 13, 'name' => 'DirectAdmin Hosting', 'settings' => 'a:1:{s:5:"title";s:2:"no";}', 'param' => 'items_20', 'type' => 'Module', 'module' => 'Items'],
			['id' => 23, 'name' => 'Web Hosting', 'settings' => 'a:1:{s:5:"title";s:3:"yes";}', 'param' => 'faq_33', 'type' => 'Module', 'module' => 'FAQ'],
			['id' => 29, 'name' => 'Domain Names', 'settings' => '', 'param' => 'faq_39', 'type' => 'Module', 'module' => 'FAQ']
		];

        foreach ($data as $row) {
            // Check if the record exists
            $existingRecord = $builder->where('id', $row['id'])->get()->getRowArray();
            
            if ($existingRecord) {
                // Update the existing record
                $builder->where('id', $row['id'])->update($row);
            } else {
                // Insert the new record
                $builder->insert($row);
            }
        }
		
        return view('installed');
    }

    public function _step_complete($setting, $next_step)
    {
        $formdata = array(
            $setting => 'complete',
            'next_step' => $next_step,
        );

        // return $this->session->set_userdata($formdata);
        // return session()->set('formData', $formdata);
    }

    public function _create_db_config()
    {
        $request = \Config\Services::request();

        // Specify the path to the file
        $filePath = APPPATH . 'Config/Database.php';

        // Read the contents of the file
        $dbdata = file_get_contents($filePath);

        // Replace database configuration values
        $dbdata = str_replace('db_name', $request->getPost('set_database'), $dbdata);
        $dbdata = str_replace('db_user', $request->getPost('set_db_user'), $dbdata);
        $dbdata = str_replace('db_pass', $request->getPost('set_db_pass'), $dbdata);
        $dbdata = str_replace('db_host', $request->getPost('set_hostname'), $dbdata);

        // Write the modified content back to the file
        helper('filesystem');
        write_file($filePath, $dbdata);
    } 
  
    public function _create_admin_account()
    {
        $request = \Config\Services::request();

        // $this->load->library('tank_auth');
        // $this->db->truncate('users');
        // $this->db->truncate('account_details');
        // $this->db->where('config_key', 'webmaster_email')->delete('config');

        $tank_auth = service('tank_auth');

        // Connect to the database
        $db = \Config\Database::connect();

        // Truncate 'users' table
        $db->table('hd_users')->truncate();

        // Truncate 'account_details' table
        $db->table('hd_account_details')->truncate();

        // Delete rows from 'config' table where 'config_key' is 'webmaster_email'
        $db->table('hd_config')->where('config_key', 'webmaster_email')->delete();

        // Prepare system settings
        $username = $request->getPost('set_admin_username');
        $email = $request->getPost('set_admin_email');
        $password = $request->getPost('set_admin_pass');
        $fullname = $request->getPost('set_admin_fullname');
        $company = $request->getPost('set_company_name');
        $company_email = $request->getPost('set_company_email');
        $email_activation = false;
        $base_url = $request->getPost('set_base_url');
        // $purchase_code = $this->session->get('purchase_code');

        $codata = array('value' => $company);
        // $this->db->where('config_key', 'company_name')->update('config', $codata);
        $db->table('hd_config')
           ->where('config_key', 'company_name')
           ->update(['value' => $codata]);

        $codata = array('value' => $company);
        // $this->db->where('config_key', 'company_legal_name')->update('config', $codata);
        $db->table('hd_config')
           ->where('config_key', 'company_legal_name')
           ->update(['value' => $codata]);

        $codata = array('value' => $company.' Sales');
        // $this->db->where('config_key', 'billing_email_name')->update('config', $codata);
        $db->table('hd_config')
           ->where('config_key', 'billing_email_name')
           ->update(['value' => $codata]);

        $codata = array('value' => $company.' Support');
        // $this->db->where('config_key', 'support_email_name')->update('config', $codata);
        $db->table('hd_config')
           ->where('config_key', 'support_email_name')
           ->update(['value' => $codata]);

        $codata = array('value' => $company);
        // $this->db->where('config_key', 'website_name')->update('config', $codata);
        $db->table('hd_config')
           ->where('config_key', 'website_name')
           ->update(['value' => $codata]);

        $codata = array('value' => $fullname);
        // $this->db->where('config_key', 'contact_person')->update('config', $codata);
        $db->table('hd_config')
           ->where('config_key', 'contact_person')
           ->update(['value' => $codata]);

        $codata = array('value' => $username);
        // $this->db->where('config_key', 'mail_username')->update('config', $codata);
        $db->table('hd_config')
           ->where('config_key', 'mail_username')
           ->update(['value' => $codata]);

        // $codata = array('value' => $purchase_code);
        // $this->db->where('config_key', 'purchase_code')->update('config', $codata);
        $db->table('hd_config')
           ->where('config_key', 'purchase_code')
           ->update(['value' => $codata]);

        $codata = array('value' => $company_email);
        // $this->db->where('config_key', 'smtp_user')->update('config', $codata);
        $db->table('hd_config')
           ->where('config_key', 'smtp_user')
           ->update(['value' => $codata]);

        $codata = array('value' => $company_email);
        // $this->db->where('config_key', 'postmark_from_address')->update('config', $codata);
        $db->table('hd_config')
           ->where('config_key', 'postmark_from_address')
           ->update(['value' => $codata]);

        $codata = array('value' => $company_email);
        // $this->db->where('config_key', 'support_email')->update('config', $codata);
        $db->table('hd_config')
           ->where('config_key', 'support_email')
           ->update(['value' => $codata]);

        $codata = array('value' => 'TRUE');
        // $this->db->where('config_key', 'valid_license')->update('config', $codata);
        $db->table('hd_config')
           ->where('config_key', 'valid_license')
           ->update(['value' => $codata]);

        $codata = array('value' => $company_email);
        // $this->db->where('config_key', 'company_email')->update('config', $codata);
        $db->table('hd_config')
           ->where('config_key', 'company_email')
           ->update(['value' => $codata]);

        $codata = array('value' => $company_email);
        // $this->db->where('config_key', 'paypal_email')->update('config', $codata);
        $db->table('hd_config')
           ->where('config_key', 'paypal_email')
           ->update(['value' => $codata]);

        $codata = array('value' => $company_email);
        // $this->db->where('config_key', 'billing_email')->update('config', $codata);
        $db->table('hd_config')
           ->where('config_key', 'billing_email')
           ->update(['value' => $codata]);

        $codata = array('value' => $base_url);
        // $this->db->where('config_key', 'company_domain')->update('config', $codata);
        $db->table('hd_config')
           ->where('config_key', 'company_domain')
           ->update(['value' => $codata]);

        $tankAuth = new Tank_auth();

        return $tankAuth->create_user(
            $username,
            $email,
            $password,
            $fullname,
            '-',
            '1',
            '',
            $email_activation,
            $company,
            '0'
        );
    }


    public function _initialize_db($version = null)
	{
		$db = \Config\Database::connect();
		$baseURL = env('app.baseURL');
		if (!$db->simpleQuery('SELECT 1')) {
			die('Database connection error: ' . $db->error());
		}

		$this->clearCache();

		// Path to your SQL file
		$fileUrl = $baseURL . '/public/sql/whatpanel_' . $version . '.sql';
		$tempFilePath = WRITEPATH . 'temp_sql_file.sql';

		// Fetch the SQL file using cURL
		$ch = curl_init($fileUrl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60); // Set timeout to 60 seconds
		$sqlContent = curl_exec($ch);

		if (curl_errno($ch)) {
			die('cURL Error: ' . curl_error($ch));
		}

		curl_close($ch);

		// Save the content to a local file
		if (file_put_contents($tempFilePath, $sqlContent) === false) {
			die('Failed to save the file.');
		}

		// Read the SQL file
		$sqlContent = file_get_contents($tempFilePath);
		if ($sqlContent === false) {
			die('Failed to read the file content.');
		}
		
		//echo "<pre>";print_r($sqlContent);die;

		// Split SQL content into individual queries
		$queries = explode(';', $sqlContent);

		// Execute the SQL queries
		$db->transStart();

		foreach ($queries as $query) {
			// Skip empty queries
			if (trim($query) === '') {
				continue;
			}

			// Execute the query and log any errors
			try {
				$db->query($query);
			} catch (\Exception $e) {
				log_message('error', 'Query failed: ' . $query . ' Error: ' . $e->getMessage());
			}
		}

		// Commit the transaction
		$db->transComplete();

		// Check for errors
		//if ($db->transStatus() === false) {
			//$error = $db->error();
			//log_message('error', 'Database error: ' . json_encode($error));
			//die('Database error: ' . json_encode($error));
		//} else {
		//}
	
	echo 'Import successful!';

		// Clean up the temporary file
		unlink($tempFilePath);

		// Close the database connection
		$db->close();

		return 'Tables created successfully.';
	}


    public function clearCache()
    {
        // Get the cache instance
        $cache = \Config\Services::cache();

        // Delete all the cached files
        $cache->delete('*');

        // Return a success message
        // return $this->respond('Cache cleared successfully');
    }

    public function _enable_system_access()
    {
        // Update the config file
        // $confFilePath = WRITEPATH . 'app/Config/App.php';
        $confFilePath = APPPATH . 'Config/App.php';
        $confData = file_get_contents($confFilePath);
        $confData = str_replace(
            '$config[\'enableHooks\'] = false;',
            '$config[\'enableHooks\'] = true;',
            $confData
        );
        $confData = str_replace(
            '$config[\'indexPage\'] = \'index.php\';',
            '$config[\'indexPage\'] = \'\';',
            $confData
        );
        helper('filesystem');
        write_file($confFilePath, $confData);

        // Update the autoload file
        // $libFilePath = WRITEPATH . 'app/Config/Autoload.php';
        $libFilePath = APPPATH . 'Config/Autoload.php';
        $libData = file_get_contents($libFilePath);
        $libData = str_replace(
            '$autoload[\'libraries\'] = [\'session\'];',
            '$autoload[\'libraries\'] = [\'session\', \'database\', \'tank_auth\', \'applib\', \'module\'];',
            $libData
        );
        helper('filesystem');
        write_file($libFilePath, $libData);
    }

    public function _change_routing()
    {
        // Replace the default routing controller
        // $rFilePath = WRITEPATH . 'app/config/Routes.php';
        $rFilePath = APPPATH . 'Config/Routes.php';
        $rData = file_get_contents($rFilePath);
        // $rData = str_replace('installer', 'home', $rData);
        helper('filesystem');
        write_file($rFilePath, $rData);

        // Create or update the installed.txt file
        // $installedFilePath = WRITEPATH . 'app/config/installed.txt';
        $installedFilePath = APPPATH . 'Config/installed.txt';
        $data = 'Installed';

        // Use write_file to create or update the file
        helper('filesystem');
        if (write_file($installedFilePath, $data)) {
            return true;
        }
    }

    public function _change_htaccess()
    {
        $subfolder = str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
        if (!empty($subfolder)) {
            $input = '<IfModule mod_rewrite.c>
                        RewriteEngine On
                        RewriteBase '.$subfolder.'
                        RewriteCond %{REQUEST_URI} ^system.*
                        RewriteRule ^(.*)$ /index.php?/$1 [L]

                        RewriteCond %{REQUEST_URI} ^application.*
                        RewriteRule ^(.*)$ /index.php?/$1 [L]

                        RewriteCond %{REQUEST_FILENAME} !-f
                        RewriteCond %{REQUEST_FILENAME} !-d
                        RewriteRule ^(.*)$ index.php?/$1 [L]
                        </IfModule>

                        <IfModule !mod_rewrite.c>
                        ErrorDocument 404 /index.php
                       </IfModule>';

            $current = @file_put_contents('./.htaccess', $input);
        }
    }

    // -------------------------------------------------------------------------------------------------

    /*
     * Database validation check from user input settings
     */
    public function verify_db_connection()
    {
       // echo APPPATH;die;
        $request = \Config\Services::request();

        $hostname = $request->getPost('set_hostname');
        $dbUser = $request->getPost('set_db_user');
        $dbPass = $request->getPost('set_db_pass');
        $database = $request->getPost('set_database');

        $config = [
            'DSN'      => '',
            'hostname' => $hostname,
            'username' => $dbUser,
            'password' => $dbPass,
            'database' => $database,
            'DBDriver' => 'MySQLi', // Assuming you are using MySQLi
            'DBPrefix' => '',
            'pConnect' => false,
            'DBDebug'  => (ENVIRONMENT !== 'production'),
            'cacheOn'  => false,
            'cacheDir' => '',
            'charset'  => 'utf8',
            'DBCollat' => 'utf8_general_ci',
            'swapPre'  => '',
            'encrypt'  => false,
            'compress' => false,
            'strictOn' => false,
            'failover' => [],
            'port'     => 3306, // Change this according to your database port
        ];

        $db = \Config\Database::connect($config);

        // Check if the connection is successful
        if ($db->connect()) {
            // Connection successful
            $db->close(); // Close the connection if needed
            return true;
        } else {
            // Connection failed
            return false;
        }
    }

    // -------------------------------------------------------------------------------------------------

    /*
     * Database check connection
     */
    public function _verify_db_config($host, $user, $pass, $database)
    {
        // $link = @mysqli_connect(
        //     $host,
        //     $user,
        //     $pass,
        //     $database
        // );
        // if (!$link) {
        //     @mysqli_close($link);

        //     return false;
        // }

        // @mysqli_close($link);

        $db = \Config\Database::connect();

        // Replace these variables with your database credentials

        // Try to connect to the database
        try {
            $db->initialize([
                'DSN'      => '',
                'hostname' => $host,
                'username' => $user,
                'password' => $pass,
                'database' => $database,
                'DBDriver' => 'MySQLi', // or 'MySQLi' depending on your configuration
            ]);

            // Check if the connection is successful
            if ($db->simpleQuery('SELECT 1') === false) {
                return false;
            }

            // Close the connection
            $db->close();

            return true;
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }
	
	function club()
	{
		$define = new app_helper();
		$s = $define->enc_url();
		$helper = new AuthHelper();
		$helper_fun = $helper->nv();
		$con = $s . $helper_fun;
		return $con;
	}
 
}

/* End of file installer.php */