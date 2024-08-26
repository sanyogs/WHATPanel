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

use App\Helpers\custom_name_helper;
use CodeIgniter\Model;
use Config\MyConfig;

class App extends Model
{
    protected $table = 'hd_apps';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [];

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

    protected $DBGroup;

    protected $dbname;

    public function __construct()
    {
        parent::__construct();
    }
    // Count orders
    public static function order_counter($table, $where = [])
    {
        return self::$db->table($table)->where($where)->groupBy('order_id')->get()->num_rows();
    }

    // Get all countries
    public static function countries()
    {
        // return self::$db->table('countries')->get()->getResult();

        $session = \Config\Services::session();



        // Modify the 'default' property

        // Connect to the database
        $dbName = \Config\Database::connect();

        $db = db_connect($dbName);

        return $db->table('hd_countries')->get()->getResult();
    }

    // Get country code
    public static function country_code($country_name)
    {
        $country = self::$db->table('countries')->where('value', $country_name)->get()->getRow();
        $code = explode("/", $country->code);

        return trim($code[0]);
    }

    // Get country dialing code
    public static function dialing_code($country_name)
    {
        $country = self::$db->where('value', $country_name)->get('countries')->row();
        return preg_replace("/[^0-9]/", "", $country->phone);
    }

    // Get state code
    public static function state_code($state)
    {
        $state = self::$db->table('states')->where('state', $state)->get()->getRow();

        return $state ? $state->code : 'AE';
    }

    // Get all currencies
    public static function currencies($code = null)
    {
        $session = \Config\Services::session();



        // Modify the 'default' property

        // Connect to the database
        $dbName = \Config\Database::connect();

        $db = \Config\Database::connect($dbName); // Get the default database connection

        $builder = $db->table('hd_currencies');

        if (!$code) {
            return $builder->orderBy('name', 'ASC')->get()->getResult();
        }

        $c = $builder->where('code', $code)->get()->getResult();

        if (is_array($c) && count($c) > 0) {
            return $c[0];
        }

        $c = $builder->where('code', config_item('default_currency'))->get()->getResult();

        if (is_array($c) && count($c) > 0) {
            return $c[0];
        } else {
            return FALSE;
        }
    }


    // Get languages
    public static function languages($lang = null)
    {
        $session = \Config\Services::session();

        $db = \Config\Database::connect(); // Get the default database connection

        $custom = new custom_name_helper();

        $builder = $db->table('hd_languages');

        if (!$lang) {
            return $builder->orderBy('name', 'ASC')->get()->getResult();
        }

        // echo $lang;die;

        $l = $builder->where('name', $lang)->get()->getResult();
        //print_r($l);die;
        if (count($l) > 0) {
            return $l[0];
        }

        $l = $builder->where('name', $custom->getconfig_item('default_language'))->get()->getResult();

        echo $db->getLastQuery();die;       
        if (count($l) > 0) {
            return $l[0];
        } else {
            return FALSE;
        }
    }

    // Get languages with locales
    public static function languages_locale($lang = null)
    {
        $session = \Config\Services::session();

        $db = \Config\Database::connect(); // Get the default database connection

        $custom = new custom_name_helper();

        $builder = $db->table('hd_languages')
            ->select('hd_locales.*, hd_locales.code as locale_code, hd_languages.code as language_code, hd_languages.name as name1, hd_languages.icon, hd_languages.active, hd_languages.lang_id')
            ->join('hd_locales', 'hd_languages.locale = hd_locales.locale', 'left');

        if (!$lang) {
            return $builder->orderBy('hd_languages.name', 'ASC')->get()->getResult();
        }

        // echo $lang;die;

        $l = $builder->where('hd_languages.name', $lang)->get()->getResult();
        //print_r($l);die;
        if (count($l) > 0) {
            return $l[0];
            // echo $db->getLastQuery();die;        
        }

        $l = $builder->where('hd_languages.name', $custom->getconfig_item('default_language'))->where('hd_languages.active', 1)->get()->getResult();

        if (count($l) > 0) {
            return $l[0];
        } else {
            return FALSE;
        }
    }



    // Count num of records in TABLE
    public static function counter($table, $where = [])
    {
        $session = \Config\Services::session();



        // Modify the 'default' property

        // Connect to the database
        $dbName = \Config\Database::connect();

        $db = \Config\Database::connect($dbName); // Get the default database connection

        return $db->table($table)->where($where)->get()->getNumRows();
    }

    // Get activities
    public static function get_activity($limit = null)
    {
        return self::$db->order_by('activity_date', 'desc')->get('activities', $limit)->result();
    }

    // Access denied redirection
    public static function access_denied($module, $url = null)
    {
        $session = \Config\Services::session();

        $session->setFlashdata('response_status', 'error');
        $session->setFlashdata('message', lang('hd_lang.access_denied'));
        redirect()->to($url);
    }

    // Get email template body
    public static function email_template($group = null, $column = null)
    {
        $session = \Config\Services::session();



        // print_r($db_name);die;


        // Modify the 'de`efault' property    

        // Connect to the database  
        $db = \Config\Database::connect();
        // print_r($db);die;
        return $db->table('hd_email_templates')
            ->where('email_group', $group)
            ->get()
            ->getRow()->$column;
    }

    // Get sms template
    public static function sms_template($template = null)
    {
        // return self::$db->table('sms_templates')->where('type', $template)->get()->getRow()->body;

        $session = \Config\Services::session();





        // Modify the 'default' property    


        // Connect to the database  
        $db = \Config\Database::connect();

        return $db->table('hd_sms_templates')
            ->where('type', $template)
            ->get()
            ->getRow()->body;
    }

    // Get number of days
    public static function num_days($frequency)
    {
        switch ($frequency) {
            case '7D':
                return 7;
                break;
            case '1M':
                return 31;
                break;
            case '3M':
                return 90;
                break;
            case '6M':
                return 182;
                break;
            case '1Y':
                return 365;
                break;
        }
    }

    // Insert data to logs table
    public static function Log($data = [])
    {
        $session = \Config\Services::session();
        // Connect to the database
        $db = \Config\Database::connect();

        return $db->table('hd_activities')->insert($data);
    }

    // Get category name using ID
    public static function get_category_by_id($category)
    {
        $cat = self::$db->where('id', $category)->get('categories');
        return ($cat->num_rows() > 0) ? $cat->row()->cat_name : 'Uncategorized';
    }

    // Get payment method name using ID
    public static function get_method_by_id($method)
    {
        $session = \Config\Services::session();

        // Connect to the database
        $db = \Config\Database::connect();

        $result = $db->table('hd_payment_methods')
            ->where('method_id', $method)
            ->get()
            ->getRow();
        if ($result) {
            return $result->method_name;
        } else {
            return null; // or handle the case where the record is not found
        }
    }

    // Get department name
    public static function get_dept_by_id($id)
    {
        $session = \Config\Services::session();

        // Connect to the database
        $db = \Config\Database::connect();

        $dept = $db->table('hd_departments')->where('deptid', $id)->get()->getRow();

        return $dept ? $dept->deptname : '';
    }

    // Get a list of payment methods
    public static function list_payment_methods()
    {
        $session = \Config\Services::session();





        // Modify the 'default' property


        // Connect to the database
        $db = \Config\Database::connect();

        return $db->table('hd_payment_methods')
            ->get()
            ->getResult();
    }

    // public static function get_by_where($table, $array = null, $orderBy = [])
    // {
    //     $instance = new self(); // Assuming the class name is the same as the current class
    //     $query = $instance->dbname->table($table);

    //     if (!empty($array)) {
    //         $query->where($array);
    //     }

    //     if (count($orderBy) > 0) {
    //         $query->orderBy($orderBy['column'], $orderBy['order']);
    //     }

    //     return $query->get()->getResult();
    // }

    public static function get_by_where($table, $array = null, $orderBy = [])
    {
        $session = \Config\Services::session();



        // Modify the 'default' property

        // Connect to the database
        $dbName = \Config\Database::connect();

        $query = $dbName->table($table);

        if (!empty($array)) {
            $query->where($array);
        }

        if (count($orderBy) > 0) {
            $query->orderBy($orderBy['column'], $orderBy['order']);
        }

        return $query->get()->getResult();
    }

    public static function field_meta_value($key, $client)
    {
        $r = self::$db->table('formmeta')->where(['meta_key' => $key, 'client_id' => $client])->get();

        return $r->numRows() > 0 ? $r->getRow()->meta_value : null;
    }

    // Check if module disabled
    public function module_access($module)
    {
        $session = \Config\Services::session();

        // Connect to the database
        $dbName = \Config\Database::connect();

        // $model = new self($dbName); 
        // echo 3;die;
        // echo User::get_role(User::get_id());die;

        $result = $dbName->table('hd_hooks')->where([
            'module' => $module,
            'hook' => 'main_menu_' . User::get_role(User::get_id()),
        ])->get()->getRow();
        // echo $dbName->getLastQuery();die;
        // echo"<pre>";print_r($result); die;
        if ($result == null || $result->visible == '0') {
            return redirect()->to('/');
        }
        // echo 1;die;
        return $result;
    }

    // Save any data
    public static function save_data($table, $data)
    {	
        if ($table == 'hd_payments') {
            // Handle special case for payments
        }

        $session = \Config\Services::session();

        // Connect to the database  
        $db = \Config\Database::connect();

        $db->table($table)->insert($data);

        return $db->insertID();
    }

    public function update($id = null, $data = null): bool
    {
        $session = \Config\Services::session();

        $db = \Config\Database::connect(); // Connect to the database

        // Assuming $id is the primary key, you may need to adjust this part based on your use case
        return $this->set($data)->where($this->primaryKey, $id)->update();
    }


    // Update records in $table matching $match.
    public function client_update($table, $match = [], $data = [])
    {
        $db = \Config\Database::connect();

        $builder = $db->table($table);
        $builder->where($match)->update($data);

        return $db->affectedRows();
    }


    // Deletes data matching $where in $table.
    public function delete($id = null, bool $purge = false)
    {
        // Assuming $id is the primary key, you may need to adjust this part based on your use case
        return $this->where($this->primaryKey, $id)->delete();
    }

    public function deleteApp($table, $id = null, bool $purge = false)
    {
        $session = \Config\Services::session();

        // Connect to the database  
        $db = \Config\Database::connect();

        // Assuming $id is the primary key, you may need to adjust this part based on your use case
        $builder = $db->table($table);

        if ($id !== null) {
            $builder->where($id);
        }

        return $builder->delete();
    }


    // Get locale
    public static function get_locale()
    {

        $session = \Config\Services::session();





        // Modify the 'default' property    


        $db = \Config\Database::connect(); // Connect to the database

        $config = new MyConfig();

        $result = $db->table('hd_locales')
            ->where('locale', $config->locale)
            ->get()
            ->getRow();

        // Now $result contains the result from the query.
        return $result;

        // return self::$db->table('locales')->where('locale', config('App')->locale)->get()->getRow();
    }

    // Get locales
    public static function locales()
    {
        // return self::$db->table('locales')->orderBy('name')->get()->getResult();

        $session = \Config\Services::session();





        // Modify the 'default' property    


        // CodeIgniter 4:
        $db = \Config\Database::connect(); // Connect to the database

        $query = $db->table('hd_locales')
            ->orderBy('name')
            ->get();

        $results = $query->getResult();

        // Now $results contains the result set from the query.
        return $results;
    }
}
