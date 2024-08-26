<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */


namespace App\Libraries;

use App\Models\Login_attempts;
use App\Models\User_autologin;
use CodeIgniter\Cookie\CookieStore;
use Config\Services;
use App\Models\User;
use App\Models\Company;
use App\Libraries\AppLib;
use CodeIgniter\Security\PasswordHash;
use App\Models\LoginAttemptsModel;
use App\Models\tank_auth\Remote_login;
use Config\Database;
use App\Models\Users;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\Response;
use App\Helpers\custom_name_helper;


class Tank_auth
{
    private $error = [];

    protected $config, $session, $usersModel, $companiesModel;

    public $login_count_attempts = true; // Set to true if you want to enable login attempts counting
    public $login_max_attempts = 5; // Set the maximum number of login attempts

    protected $loginAttemptsModel;

    private $ci;


    public function __construct()
    {
        $session = Services::session();

        $config = new Database();

        $this->config = config('tank_auth'); // Assuming you still need this line
        $this->loginAttemptsModel = new Login_attempts();
        $this->companiesModel = new Company();
        $this->usersModel = new Users();
        // echo "<pre>";
        // print_r($this->usersModel);
        // die;
    }


    function get_error_message()
    {
        return $this->error;
    }

    private function clear_login_attempts($login)
    {
        $config = config('Tank_auth'); // Adjust based on your configuration file name

        if ($config->login_count_attempts) {
            $loginAttemptsModel = new Login_attempts(); // Adjust the namespace based on your directory structure

            $loginAttemptsModel->clearAttempts(
                $this->ci->request->getIPAddress(),
                $login,
                $config->login_attempt_expire
            );
        }
    }
    private function create_autologin($user_id)
    {
        helper('cookie');

        $key = substr(md5(uniqid(rand() . get_cookie('sess_cookie_name'))), 0, 16);

        $session = Services::session();

        // Connect to the database
        $db = Database::connect();

        $userAutologinModel = new User_autologin($db); // Adjust the namespace based on your directory structure
        $userAutologinModel->purge($user_id);

        if (
            $userAutologinModel->insert([
                'user_id' => $user_id,
                'key_id' => md5($key),
                'user_agent' => substr(Services::request()->getUserAgent(), 0, 149),
                'last_ip' => Services::request()->getIPAddress(),
            ])
        ) {
            $cookie = [
                'name' => 'autologin_cookie_name',
                'value' => serialize(['user_id' => $user_id, 'key' => $key]),
                'expire' => $this->config->item('autologin_cookie_life', 'tank_auth'),
            ];

            // Use the response() helper to set cookies
            Services::response()->setCookie($cookie);

            return true;
        }

        return false;
    }

    private function increase_login_attempt($login)
    {
        $config = config('tank_auth');
        print_r($config);
        die;
        if ($config->login_count_attempts) {
            if (!$this->is_max_login_attempts_exceeded($login)) {
                $loginAttemptsModel = new Login_attempts();  // Adjust the namespace if needed

                $data = [
                    'ip_address' => $this->request->getIPAddress(),
                    'login' => $login,
                ];

                $loginAttemptsModel->insert($data);
            }
        }
    }



    public function login($login, $password, $remember, $login_by_username, $login_by_email)
    {
        if ((strlen($login) > 0) && (strlen($password) > 0)) {

            // Which function to use to login (based on config)
            $get_user_func = '';
            if ($login_by_username && $login_by_email) {
                $get_user_func = 'get_user_by_login';
            } elseif ($login_by_username) {
                $get_user_func = 'get_user_by_username';
            } else {
                $get_user_func = 'get_user_by_email';
            }

            $users = null; // Initialize $users to null
            // echo "<pre>";
            // print_r($this->usersModel);
            // die;

            // Ensure the usersModel is loaded before using it
            if ($this->usersModel) {
                $session = Services::session();

                // Connect to the database
                $db = Database::connect();

                // Use try-catch to handle potential exceptions
                try {
                    $result = $db
                        ->table('hd_users')
                        ->select('*')
                        ->where('email', $login)
                        ->get()
                        ->getRow();
                    //echo"<pre>";print_r($password);die;
                    if ($result) {
                        // User found, verify the password
                        $storedHashedPassword = $result->password; // Replace 'password' with your actual column name
                        //print_r($storedHashedPassword);die;
                        if (password_verify($password, $storedHashedPassword)) {
                            //echo 1;die;
                            $users = $result;

                            // Handle the case where the query was not successful
                        } else {
                            //echo 2;die;
                            // Passwords do not match, authentication failed
                            log_message('error', 'Error executing the SQL query.');
                        }
                    } else {
                        //echo 3;die;
                        // User not found, handle accordingly (e.g., show error message)
                        echo 'User not found';
                    }
                } catch (\Exception $e) {
                    //echo 4;die;
                    log_message('error', 'Exception: ' . $e->getMessage());
                    // Handle the exception, log it, and possibly show an error message
                }
            } else {
                //echo 5;die;
                // Handle the case where usersModel is not properly loaded
                log_message('error', 'usersModel is not loaded.');
                // You might want to redirect the user to an error page or show an error message.
            }
            // Load the Password library
            if (isset($users->password)) {
                $hasher = Services::passwordHash();
                if (password_verify($password, $users->password)) {

                    // Password is correct
                    if ($users->banned == 1) {

                        // Fail - banned
                        $this->error = ['banned' => $users->ban_reason];
                        return false;
                    } else {
                        $company = $db->table('hd_companies')->where('primary_contact', $users->id)->get()->getRow();
						
                        $user_data = [
                            'user_id' => $users->id,
                            'username' => $users->username,
                            'role_id' => $users->role_id,
                            'status' => ($users->activated == 1) ? STATUS_ACTIVATED : STATUS_NOT_ACTIVATED,
                            'client_id' => $company->co_id,
                            'admin' => ($users->role_id == 1 || $users->role_id == 3) ? [$users->id, $users->username, $users->role_id] : null,
                        ];
                        //print_r($user_data);die;
                        $session = Services::session();
                        $session->set('userdata', $user_data);

                        $session->set('status', ($users->activated == 1) ? STATUS_ACTIVATED : STATUS_NOT_ACTIVATED);

                        $session->set('userid', $users->id);

                        $session->set('client_id', $company->co_id);

                        $session->set('logged_in', true);

                        if ($users->activated == 0) {
                            // Fail - not activated
                            $this->error = ['not_activated' => ''];
                            return false;
                        } else {
                            // Success
                            if ($remember) {
                                //$this->create_autologin($users->id);
                            }

                            //$this->clear_login_attempts($login);

                            // $this->usersModel->update_login_info(
                            //     $users->id,
                            //     $this->config->login_record_ip,
                            //     $this->config->login_record_time
                            // );

                            return true;
                        }
                    }
                } else {
                    // Fail - wrong password
                    //$this->increase_login_attempt($login);
                    $this->error = ['password' => 'auth_incorrect_password'];
                    return false;
                }
            } else {
                // Fail - wrong login
                //$this->increase_login_attempt($login);
                $this->error = ['login' => 'auth_incorrect_login'];
                return false;
            }
        }

        return false;
    }

    public function logout()
    {
        $this->delete_autologin();

        // Clear user-related session data
        session()->remove(['user_id', 'username', 'status']);

        // Destroy the session
        session()->destroy();
    }

    public function is_logged_in($activated = true)
    {
        $session = session();

        // echo "<pre>";print_r($session->get());die;
        // Assuming 'status' is the key used in session for user status
        // if($session->get('userdata') !== Null) {
        //     $status = $session->get('userdata')['status'];
        // }

        $status = $session->get('status');

        $statusToCheck = $activated ? STATUS_ACTIVATED : STATUS_NOT_ACTIVATED;

        return $status === $statusToCheck;
    }

    // public function get_user_id()
    // {
    //     return session()->get('user_id');
    // }

    // public function get_role_id()
    // {
    //     return session()->get('role_id');
    // }

    public function get_username()
    {
       return session()->get('username');
    }

    public function user_role($r)
    {
        $session = Services::session();

        // Connect to the database
        $db = Database::connect();

        if ($this->is_logged_in()) {
            return $db->table('hd_roles')->select('role')->where('r_id', $r)->get()->getRow()->role;
        }

        return false;
    }

    private function delete_autologin()
    {
        helper(['cookie']);

        $request = \Config\Services::request();

        $autologinCookieName = config('tank_auth')->autologin_cookie_name ?? 'tank_auth';// Get the autologin cookie name from the config
        if ($cookie = $request->getCookie($autologinCookieName, true)) {
            $data = unserialize($cookie);

            // Load the user_autologin model
            $userAutologinModel = new \App\Models\tank_auth\User_autologin();
            $userAutologinModel->delete($data['user_id'], md5($data['key']));

            // Delete the autologin cookie
            delete_cookie($autologinCookieName);
        }
    }

    public function create_user(
        $username,
        $email,
        $password,
        $fullname,
        $company,
        $role,
        $phone,
        $email_activation,
        $company_name,
        $individual,
        $address = '',
        $city = '',
        $state = '',
        $zip = '',
        $country = '',
        $code = 0
    ) {
        $session = Services::session();

        $user = new User();

        $db = \Config\Database::connect();

        $data['detzilx'] = $user->where('username', $username)->first();
        // echo "<pre>"; print_r($db->getLastQuery());
        // die;
        // echo 101;die;
        if ((strlen($username) > 0) && $data['detzilx'] !== null) {
            $this->error = ['username' => 'auth_username_in_use'];
        } elseif ($data['detzilx'] !== null) {
            $this->error = ['email' => 'auth_email_in_use'];
        } else {
            // Hash password using password_hash (CI4 has native support for password hashing)
            // echo 1001;die;
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $request = Services::request();

            $data = [
                'username' => $username,
                'password' => $hashed_password,
                'email' => $email,
                'role_id' => $role,
                'last_ip' => $request->getIPAddress(),
                'new_email_key' => md5(random_bytes(16)),
				'created' => date('Y-m-d', time())
            ];

            // _create_company function should be replaced with your actual logic
            $company_id = $this->_create_company($company_name, $email, $individual, $phone, $address, $city, $state, $zip, $country);

            // $defaultLanguage = config('App')->get('defaultLanguage') ?? 'english';

            $profile = [
                'company'   => $company_id,
                'fullname' => $fullname,
                'phone' => $phone,
                'avatar'    => 'default_avatar.jpg',
                'language' => 'english',
                'locale' => 'en_US',
            ];
			
			// echo "<pre>";print_r($data);die;

            //if (!is_null($res = $user->add($data, $profile, !$email_activation, $company))) {
            if ($db->table('hd_users')->insert($data)) {
                $user_id = $db->insertID(); // Get the last inserted ID

                $profile = [
                    'company'   => $company_id,
                    'fullname' => $fullname,
                    'phone' => $phone,
                    'avatar'    => 'default_avatar.jpg',
                    'language' => 'english',
                    'locale' => 'en_US',
                    'user_id' => $user_id
                ];

                $db->table('hd_account_details')->insert($profile);

                // Perform additional operations here

                // Example: Update the company's primary contact if needed
                // $co = $this->companiesModel->table('hd_companies')->where('co_id', $company_id)->get()->getRow();
                // if ($co->primary_contact == "") {
                //     $this->companiesModel->table('hd_companies')->where('co_id', $co->co_id)->update(['primary_contact' => $user_id]);
                // }

                $db->table('hd_companies')->where('co_id', $company_id)->update(['primary_contact' => $user_id]);

                // Add the user_id to the $data array
                $data['user_id'] = $user_id;

                // Add other necessary data modifications
                $data['password'] = $password;
                unset($data['last_ip']);

                // Return the modified data
                return $data;
            } else {
                // Handle the case when the insertion fails
                return null;
            }
        }
    }

    public function is_company_available($company, $email)
    {
        $result = $this->companiesModel
            ->select('co_id')
            ->where('company_name', $company)
            ->orWhere('company_email', $email)
            ->get()
            ->getRow();

        return $result ? $result->co_id : null;
    }

    public function admin_create_user($username, $email, $password, $fullname, $company, $role, $phone, $email_activation)
    {
        $request = \Config\Services::request();
        // Validate username and email
        // if ((strlen($username) > 0) && !$this->is_username_available($username)) {
        //     return ['error' => 'auth_username_in_use'];
        // } elseif (!$this->is_email_available($email)) {
        //     return ['error' => 'auth_email_in_use'];
        // }

        // Hash password using CodeIgniter's PasswordHash
        // $hasher = Services::passwordHash();
        // $hashedPassword = $hasher->hash($password);

        $data = [
            'username' => $username,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'email' => $email,
            'role_id' => ($role == 1 && $company != 1) ? 2 : $role,
            'last_ip' => $request->getIPAddress()
        ];

        $password = password_hash($password, PASSWORD_DEFAULT);

        $comp = ($company) ? $company : 1;

        $profile = [
            'company' => ($company) ? $company : 1,
            'fullname' => $fullname,
            'phone' => $phone,
            'avatar' => 'default_avatar.jpg',
            'language' => 'english',
            'locale' => 'en_US',
        ];

        if ($email_activation) {
            $data['new_email_key'] = md5(rand() . microtime());
        }

        $res = $this->create_user($username, $email, $password, $fullname, $comp, $role, $phone, $email_activation, '', '', '', '', '', '', '', '');

        if ($res) {
            // Update primary contact if necessary
            // $com = $this->companiesModel->where('co_id', $company)->first();
            // if (intval($com->primary_contact) == 0) {
            //     $this->companiesModel->set('primary_contact', $res['user_id'])
            //         ->where('co_id', $company)
            //         ->update();
            // }

            unset($data['last_ip']);
            $data['user_id'] = $res['user_id'];
            $data['password'] = $password;

            return $data;
        }
    }

    public function _create_company($company_name, $company_email, $individual, $phone, $address, $city, $state, $zip, $country)
    {
        $custom_helper = new custom_name_helper();

        // $this->load->library('applib');
        $applib = new AppLib();
        // $lang = config('App')->defaultLanguage ?: 'english';
        $lang = 'english';
        $currency = $custom_helper->getconfig_item('default_currency') ?: 'USD';

        $data = [
            'company_ref' => $applib->generate_string(),
            'company_name' => $company_name,
            'company_email' => $company_email,
            'language' => $lang,
            'currency' => $currency,
            'individual' => $individual,
            'company_address' => $address,
            'company_phone' => $phone,
            'city' => $city,
            'state' => $state,
            'zip' => $zip,
            'country' => $country,
        ];

        $this->companiesModel->insert($data);
        return $this->companiesModel->insertID();
    }

    /**
     * Login user automatically if he/she provides correct autologin verification
     *
     * @return	void
     */
    // private function autologin()
    // {
    //     if (!$this->isLoggedIn() && !$this->isLoggedIn(false)) {
    //         $autologinCookieName = $this->config->get('autologin_cookie_name');

    //         $cookie = $this->request->getCookie($autologinCookieName, FILTER_SANITIZE_STRING);

    //         if ($cookie) {
    //             $data = unserialize($cookie);

    //             if (isset($data['key']) && isset($data['user_id'])) {
    //                 $userAutologinModel = new \App\Models\UserAutologinModel();

    //                 $user = $userAutologinModel->get($data['user_id'], md5($data['key']));

    //                 if (!is_null($user)) {
    //                     // Login user
    //                     $this->session->set('user_id', $user['id']);
    //                     $this->session->set('username', $user['username']);
    //                     $this->session->set('role_id', $user['role_id']);
    //                     $this->session->set('status', 'STATUS_ACTIVATED');

    //                     // Renew users cookie to prevent it from expiring
    //                     $this->response->setCookie([
    //                         'name' => $autologinCookieName,
    //                         'value' => $cookie,
    //                         'expire' => $this->config->get('autologin_cookie_life'),
    //                     ]);

    //                     // Update login info
    //                     $this->usersModel->updateLoginInfo(
    //                         $user['id'],
    //                         $this->config->get('login_record_ip'),
    //                         $this->config->get('login_record_time')
    //                     );

    //                     return true;
    //                 }
    //             }
    //         }
    //     }
    //     return false;
    // }

    // ... (rest of the class)


    public function is_max_login_attempts_exceeded($login): ?bool
    {
        $config = config('TankAuth'); // Adjust this based on your config file location
        $this->loginAttemptsModel = new Login_attempts();
        $config = new Tank_auth();
        $loginCountAttempts = $config->login_count_attempts;
        if ($loginCountAttempts) {
            $ipAddress = Services::request()->getIPAddress();
            $attemptsNum = $this->loginAttemptsModel->getAttemptsNum($ipAddress, $login);

            return $attemptsNum >= $config->login_max_attempts;
        }

        return null;
    }

    function get_role_id()
    {
        $session = \Config\Services::session();

        $userdata = $session->get('userdata');

        return $userdata['role_id'];
    }


    function create_remote_login($user_id)
    {
        $remote = new Remote_login();
        $helper = new custom_name_helper();
        $key = substr(md5(uniqid(rand() . time())), 0, 16);
        $remote->purge($user_id);
        $expiration = date("Y-m-d H:m:i", time() + 3600 * $helper->getconfig_item('remote_login_expires'));

        if ($remote->setUser($user_id, md5($key), $expiration)) {
            return md5($key);
        }

        return FALSE;
    }
	
	function set_new_email($new_email, $password)
	{
		$user_id = session()->get('user_id');
		
		$users = new Users();

		if (!is_null($user = $users->get_user_by_id($user_id, TRUE))) {

			// Check if password correct
			$hasher = new PasswordHash(
					$custom->getconfig_item('phpass_hash_strength', 'tank_auth'),
					$custom->getconfig_item('phpass_hash_portable', 'tank_auth'));
			if ($hasher->CheckPassword($password, $user->password)) {			// success

				$data = array(
					'user_id'	=> $user_id,
					'username'	=> $user->username,
					'new_email'	=> $new_email,
				);

				if ($user->email == $new_email) {
					$this->error = array('email' => 'auth_current_email');

				} elseif ($user->new_email == $new_email) {		// leave email key as is
					$data['new_email_key'] = $user->new_email_key;
					return $data;

				} elseif ($this->is_email_available($new_email)) {
					$data['new_email_key'] = md5(rand().microtime());
					$this->set_new_email($user_id, $new_email, $data['new_email_key'], TRUE);
					return $data;

				} else {
					$this->error = array('email' => 'auth_email_in_use');
				}
			} else {															// fail
				$this->error = array('password' => 'auth_incorrect_password');
			}
		}
		return NULL;
	}
	
	 function forgot_password($login)
	{
		if (strlen($login) > 0) {
			if (!is_null($user = $this->ci->users->get_user_by_login($login))) {

				$data = array(
					'user_id'		=> $user->id,
					'username'		=> $user->username,
					'email'			=> $user->email,
					'new_pass_key'	=> md5(rand().microtime()),
				);

				$this->ci->users->set_password_key($user->id, $data['new_pass_key']);
				return $data;

			} else {
				$this->error = array('login' => 'auth_incorrect_email_or_username');
			}
		}
		return NULL;
	}
}
