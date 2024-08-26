<?php
 /*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */


namespace App\Models;

use App\Libraries\AppLib;
use App\Libraries\Tank_auth;
use CodeIgniter\Config\Services;
use CodeIgniter\Model;
use App\Helpers\custom_name_helper;

class User extends Model
{
    protected $table = 'hd_users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'username',
        'password',
        'email',
        'role_id',
        'activated',
        'banned',
        'ban_reason',
        'new_password_key',
        'new_password_requested',
        'new_email',
        'new_email_key',
        'last_ip',
        'last_login',
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];
    // protected $DBGroup = 'default';

    protected $DBGroup;

    protected $tankAuth;

    public function __construct($db = null)
    {
        parent::__construct($db);

        // if ($db !== null) {
        //     $this->DBGroup = $db->getDatabase();
        // }

        $this->tankAuth = new \App\Libraries\Tank_auth();

        $session = \Config\Services::session();

        $userdata = $session->get('userdata');

        if(empty($userdata))
        {
            return redirect()->to('login');
        }
    }

    public static function logged_in()
    {
        $session = session();
        $isLoggedIn = $session->has('logged_in') && $session->get('logged_in') === true;

        // Uncomment the line below if you want to redirect when not logged in
        // if (!$isLoggedIn) return redirect()->to('login');

        return $isLoggedIn;
    }

    public static function is_client()
    {
        $session = \Config\Services::session();

        $userdata = $session->get('userdata');

        if(empty($userdata))
        {
            return redirect()->to('login');
        }

        if($userdata['role_id'] == 2) {
            return "client";
        }
    }

    public static function is_staff()
    {
        $session = \Config\Services::session();

        $userdata = $session->get('userdata');

        if(empty($userdata))
        {
            return redirect()->to('login');
        }

        if($userdata['role_id'] == 3) {
            return "staff";
        }
    }

    public static function is_admin()
    {
        $session = \Config\Services::session();

        $userdata = $session->get('userdata');

        if(empty($userdata))
        {
            return redirect()->to('login');
        }

        if($userdata['role_id'] == 1) {
            return "admin";
        }
    }

    static function get_roles(){
        $session = \Config\Services::session();

        // Connect to the database  
        $db = \Config\Database::connect();

        return $db->table('hd_roles')->get()->getResult();
    }

    static function perm_allowed($user, $perm)
    {   
        $session = \Config\Services::session();

        // Connect to the database
        $db = \Config\Database::connect();

        $permission = $db->table('hd_permissions')->where('status', 'active')->get()->getResult();
       
        // Fetching permissions from the database where status is active.

        $allowed_modules = isset(self::profile_info($user)->allowed_modules) ? self::profile_info($user)->allowed_modules : '{"settings":"permissions"}';
        // Fetching allowed modules from the user's profile information. If not available, using a default value.
        
        $allowed_modules = json_decode($allowed_modules, true);
        // Decoding the JSON representation of allowed modules into an associative array.
        
        if (!array_key_exists($perm, $allowed_modules)) {
            return FALSE;
        }

        foreach ($permission as $key => $p) {
            if (array_key_exists($p->name, $allowed_modules) && $allowed_modules[$perm] == 'on') {
                // If the permission name is present in the allowed modules and the specific module is set to 'on'.
                return TRUE;
            }
        }

        return FALSE;
    }

    public static function profile_info($id)
    {
        $session = \Config\Services::session();
        // Connect to the database
        $db = \Config\Database::connect();

        // return $db->table('hd_account_details')->where('user_id',$id)->get()->getRow();

        $result = $db->table('hd_companies')
        ->select('hd_companies.*, hd_account_details.*') // Select columns from both tables
        ->join('hd_account_details', 'hd_account_details.user_id = hd_companies.primary_contact') // Join the 'users' table on the 'primary_contact' column
        ->where('hd_companies.primary_contact', $id)
        ->get()
        ->getRow();
		
        return $result;
    }
	

    public static function get_id()
    {
        $session = \Config\Services::session();
        //print_r($session->get());die();
        $user = $session->get('userdata');

        if(empty($user))
        {
            return redirect()->to('login');
        }

        return $user['user_id'];
    }

    public static function get_id_client()
    {
        $session = \Config\Services::session();
        //print_r($session->get());die();
        $user = $session->get('userdata');

        if(empty($user))
        {
            return redirect()->to('login');
        }
        
        return $user['user_id'];
    }

    static function view_user($id)
    {
        // return self::$db->where('id', $id)->get('users')->row();

        $session = \Config\Services::session();
        // Connect to the database
        $db = \Config\Database::connect();

        return $db->table('hd_users')
            ->where('id', $id)
            ->get()
            ->getRow();
    }

    static function login_info($id)
    {
        $session = \Config\Services::session();

        // Connect to the database
        $dbConnection = \Config\Database::connect();

        // Use the connection to fetch data
        $result = $dbConnection->table('hd_users')->where('id', $id)->get()->getRow();

        return $result;
    }

    static function team()
    {	
		$db = \Config\Database::connect();
		
		$result = $db->table('hd_users')->where('role_id !=', 2)->get()->getResult();
        //return self::$db->where('role_id !=', 2)->get('users')->result();
		return $result;
    }

    static function admin_list()
    {
        return self::$db->where(array('role_id' => 1, 'activated' => 1))->get('users')->result();
    }

    static function is_logged_in()
    {
        $session = session();

        return $session->get('logged_in') ? true : false;
    }

    static function can_view_invoice($user, $invoice)
    {
        $role = self::login_role_name();
        if ($role == 'admin')
            return TRUE;
        if ($role == 'staff' && self::perm_allowed($user, 'view_all_invoices'))
            return TRUE;
        if (self::check_user_exist($user) > 0) {
            $client = Invoice::view_by_id($invoice)->client;
            $show_client = Invoice::view_by_id($invoice)->show_client;
            return ($client == self::profile_info($user)->company && $show_client == 'Yes') ? TRUE : FALSE;
        } else {
            return FALSE;
        }
    }

    static function login_role_name()
    {
        $tank_auth = new Tank_auth();
        return $tank_auth->user_role($tank_auth->get_role_id());
    }

    static function check_user_exist($user)
    {
        //return self::$db->where('id', $user)->get('users')->num_rows();

        $session = \Config\Services::session();
            // Connect to the database
            $dbName = \Config\Database::connect();

        return $dbName->table('hd_users')->where('id', $user)->countAllResults();
    }

    static function can_add_invoice()
    {
        if (self::login_role_name() == 'admin')
            return TRUE;
        elseif (self::login_role_name() == 'staff' && self::perm_allowed(self::get_id(), 'add_invoices')) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    static function can_pay_invoice()
    {
        if (self::login_role_name() == 'admin')
            return TRUE;
        elseif (self::login_role_name() == 'staff' && self::perm_allowed(self::get_id(), 'pay_invoice_offline')) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    static function displayName($user = '')
    {
        if (!self::check_user_exist($user))
            return '[MISSING USER]';

            $profileInfo = self::profile_info($user);
            $loginInfo = self::login_info($user);
            
            return ($profileInfo && $profileInfo->fullname !== NULL)
                ? $profileInfo->fullname
                : $loginInfo->username;
    }


    static function user_log($user)
	{	
		$db = \Config\Database::connect();
		return $db->table('hd_activities')->where('user', $user)->orderBy('activity_date', 'DESC')->get()->getResult();
	}

    static function can_view_ticket($user, $ticket)
    {
        $info = Ticket::view_by_id($ticket);
        $user_dept = self::profile_info(self::get_id())->department;
        $dep = json_decode($user_dept, TRUE);

        if (
            is_array($dep) && in_array($info->department, $dep) || (self::is_staff()
                && $user_dept == $info->department || $info->reporter == $user)
        ) {
            return TRUE;
        }

        if (self::is_admin() || $info->reporter == self::get_id()) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    static function all_users()
    {
        // return self::$db->get('users')->result();

        $session = \Config\Services::session();

        // Connect to the database  
        $db = \Config\Database::connect();

        return $db->table('hd_users')->get()->getResult();
    }

    static function get_role($user)
    {
        $tank_auth = new Tank_auth();
        if ($tank_auth->is_logged_in()) {
            $id = self::login_info($user)->role_id;
        } else {
            $id = null;
        }
        return $tank_auth->user_role($id);
    }

    static function avatar_url($user = NULL)
    {	
		$helper = new custom_name_helper();
        if (!self::check_user_exist($user))
            return base_url('avatar/default_avatar.jpg');

        if ($helper->getconfig_item('use_gravatar') == 'TRUE' && self::profile_info($user)->use_gravatar == 'Y') {
            $user_email = self::login_info($user)->email;
            return AppLib::get_gravatar($user_email);
        } else {
			return "empty"; //base_url() . 'avatar/' . $this->profile_info($user)->avatar;
        }
    }

    public function get_user_by_email($email)
    {
        $this->db = \Config\Database::connect(); // Load database

        return $this->where('email', $email)->first();
    }

    public function get_user_by_username($username)
    {
        $this->db = \Config\Database::connect(); // Load database

        return $this->where('username', $username)->first();
    }

}