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

use CodeIgniter\Model;

use CodeIgniter\Files\File;

class Plugin extends Model
{
    protected $table = 'hd_plugins';
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

    public function __construct($db = null)
    {
        parent::__construct($db);
        // $this->path = base_url() . 'modules/';
        // $directoryPath = base_url() . 'modules/';

        if ($db !== null) {
            $this->DBGroup = $db->getDatabase();
        }
    }

    public function modules()
    {
        // $path = base_url() . 'modules/';
        $path = ROOTPATH . 'modules' . DIRECTORY_SEPARATOR;

        $glob = glob($path . '/*');

        if ($glob === false) {
            return array();
        }

        return array_filter($glob, function ($dir) {
            return is_dir($dir);
        });
    }

    public function get_plugins()
    {
        $plugins = [];
        $return = [];

        helper(['file']); // Load the file helper

        $session = \Config\Services::session();



        // Modify the 'default' property


        $db = \Config\Database::connect();

        $query = $db->table('hd_plugins')->get();
        $result = $query->getResult();

        foreach ($this->modules() as $module) {
            $module = explode('/', $module);
            $module = $module[count($module) - 1];

            $path = ROOTPATH . 'modules' . DIRECTORY_SEPARATOR;

            if (is_dir($path . '/' . $module . '/controllers')) {
                $controller = $path . '/' . $module . '/controllers/' . ucfirst($module) . '.php';

                if (is_file($controller)) {
                    $module_data = file_get_contents($controller);

                    preg_match('|Module Name:(.*)$|mi', $module_data, $name);
                    preg_match('|Category:(.*)$|mi', $module_data, $category);
                    preg_match('|Module URI:(.*)$|mi', $module_data, $uri);
                    preg_match('|Version:(.*)|i', $module_data, $version);
                    preg_match('|Description:(.*)$|mi', $module_data, $description);
                    preg_match('|Author:(.*)$|mi', $module_data, $author_name);
                    preg_match('|Author URI:(.*)$|mi', $module_data, $author_uri);

                    $arr = [];

                    if (isset($name[1])) {
                        $arr['name'] = trim($name[1]);
                        $name = strtolower(str_replace(' ', '_', $arr['name']));
                        $arr['system_name'] = $name;

                        foreach ($result as $r) {
                            if ($name == $r->system_name) {
                                $arr['status'] = $r->status;
                            }
                        }
                    }

                    if (!isset($arr['status'])) {
                        $arr['status'] = 0;
                        $arr['installed'] = 0;
                    } else {
                        $arr['installed'] = 1;
                    }

                    if (isset($uri[1])) {
                        $arr['uri'] = trim($uri[1]);
                    }

                    if (isset($category[1])) {
                        $arr['category'] = trim($category[1]);
                    }

                    if (isset($version[1])) {
                        $arr['version'] = trim($version[1]);
                    }

                    if (isset($description[1])) {
                        $arr['description'] = trim($description[1]);
                    }

                    if (isset($author_name[1])) {
                        $arr['author'] = trim($author_name[1]);
                    }

                    if (isset($author_uri[1])) {
                        $arr['author_uri'] = trim($author_uri[1]);
                    }

                    $return[$arr['system_name']] = (object) $arr;
                }
            }
        }
		
		//echo "<pre>";print_r($return);die;

        return $return;
    }



    public function update_plugin_info($plugin, array $settings)
    {
        $session = \Config\Services::session();
		//echo 122;die;
        // Connect to the database  
        $db = \Config\Database::connect();

        $existingRecord = $db->table('hd_plugins')
            ->where('system_name', $plugin)
            ->get()
            ->getFirstRow();

        if ($existingRecord) {
            // Update existing record
            return $db->table('hd_plugins')
                ->where('system_name', $plugin)
                ->update($settings);
        } else {
            // Insert new record
            $settings['system_name'] = strtolower(str_replace(' ', '_', $settings['name']));
			//if($settings['name'] == 'razorpay')
			//{
				//$arrayRaz = [
												//"api_key" => "",
												//"secret_key" => "",
												//"razorpay_mode" => "",
												//"id" => "",
												//"system_name" => "razorpay",
												//"submit" => "Save"
				//];
				//$settings['config'] = json_encode($arrayRaz);
			//}
			//else {
				
				$settings['config'] = strtolower(str_replace(' ', '_', $settings['name']));
			//}
			
				
            $db->table('hd_plugins')->insert($settings);
        }
    }




    public function set_status($plugin, $status)
    {

        $session = \Config\Services::session();





        // Modify the 'default' property    


        // Connect to the database  
        $db = \Config\Database::connect();

        log_message("error", "PLUGIN: $plugin; STATUS: $status");

        $data = ['status' => $status];

        if (
            !$db->table('hd_plugins') // Replace 'plugins' with the actual table name
                ->where('system_name', $plugin)
                ->update($data)
        ) {
            return false;
        }

        return true;
    }

    public static function reset_settings($plugin)
    {
        $session = \Config\Services::session();





        // Modify the 'default' property    


        $db = \Config\Database::connect();

        $data = ['config' => ''];

        $db->table('hd_plugins') // Replace 'plugins' with the actual table name
            ->where('system_name', $plugin)
            ->update($data);

        return $db->affectedRows() > 0;
    }


    public static function get_plugin($plugin)
    {
        $session = \Config\Services::session();





        // Modify the 'default' property	


        // Connect to the database	
        $db = \Config\Database::connect();

        $query = $db->table('hd_plugins') // Replace 'plugins' with the actual table name
            ->where(['system_name' => $plugin])
            ->get();

        $result = $query->getResult();

        return (!empty($result[0]) ? $result[0] : false);
    }

    public static function active_plugins()
    {
        $db = \Config\Database::connect();

        return $db->table('your_table_name') // Replace 'your_table_name' with the actual table name
            ->select('plugin_id, system_name, version, category')
            ->where('status', 1)
            ->get()
            ->getResult();
    }

    public static function payment_gateways()
    {
        $db = \Config\Database::connect();

        return $db->table('hd_plugins') // Replace 'your_table_name' with the actual table name
            ->where('category', 'Payment Gateways')
            ->where('status', 1)
            ->get()
            ->getResult();
    }

    public static function domain_registrars()
    {
        $session = \Config\Services::session();

        // Connect to the database	
        $db = \Config\Database::connect();

        return $db->table('hd_plugins')
            ->where('category', 'Domain Registrar')
            ->where('status', 1)
            ->get()
            ->getResult();
    }

    public static function servers()
    {
        $session = \Config\Services::session();





        // Modify the 'default' property	


        // Connect to the database	
        $db = \Config\Database::connect();

        $model = new self($db); // You can use $this->db if it's available in the context.

        return $model->table('hd_plugins')
            ->where('category', 'Servers')
            ->where('status', 1)
            ->get()
            ->getResult();
    }

    public function listDomainRegistrars($array, $search, $perPage, $page)
    {
        $session = \Config\Services::session();

        // Connect to the database	
        $db = \Config\Database::connect();

        // Begin the selection process using the model's query builder directly
        $this->select('hd_plugins.*')
            ->where('category', 'Domain Registrars')
            ->where('status', 1);

        // If there's a search term, apply it across relevant fields
        if (!empty($search)) {
            $this->groupStart()
                ->like('hd_plugins.system_name', $search)
                ->orLike('hd_plugins.name', $search)
                ->orLike('hd_plugins.category', $search)
                ->groupEnd();
        }

        // Utilize the model's built-in pagination
        $data = $this->paginate($perPage);

        // Check if the result set is empty
        if (empty($data)) {
            $message = 'No items found';
        } else {
            $message = '';
        }

        // The pager is directly accessible via $this->pager after pagination is called
        return [
            'items' => $data,
            'pager' => $this->pager,
            'message' => $message
        ];
    }

    public function listItems($array, $search, $perPage, $page)
    {

        $db = \Config\Database::connect();

        // Begin the selection process using the model's query builder directly
        $this->select('hd_plugins.*');

        // If there's a search term, apply it across relevant fields
        if (!empty($search)) {
            $this->groupStart()
                ->like('hd_plugins.system_name', $search)
                ->orLike('hd_plugins.name', $search)
                ->orLike('hd_plugins.category', $search)
                ->orLike('hd_plugins.uri', $search)
                ->orLike('hd_plugins.description', $search)
                ->orLike('hd_plugins.author', $search)
                ->groupEnd();
        }

        // Utilize the model's built-in pagination
        $dataArray = $this->paginate();

        // Convert each item in the array to an object
        $objectArray = array_map(function ($item) {
            return (object) $item;
        }, $dataArray);

        // If you want to paginate, ensure $objectArray is returned
        $data = $objectArray;

        $data_count = count($data);

        // echo $data_count;die;

        foreach ($this->modules() as $module) {
            $module = explode('/', $module);
            $module = $module[count($module) - 1];

            $path = ROOTPATH . 'modules' . DIRECTORY_SEPARATOR;

            if($data_count > 1)
            {
                if (is_dir($path . '/' . $module . '/controllers')) {
                    $controller = $path . '/' . $module . '/controllers/' . ucfirst($module) . '.php';
    
                    if (is_file($controller)) {
                        $module_data = file_get_contents($controller);
    
                        preg_match('|Module Name:(.*)$|mi', $module_data, $name);
                        preg_match('|Category:(.*)$|mi', $module_data, $category);
                        preg_match('|Module URI:(.*)$|mi', $module_data, $uri);
                        preg_match('|Version:(.*)|i', $module_data, $version);
                        preg_match('|Description:(.*)$|mi', $module_data, $description);
                        preg_match('|Author:(.*)$|mi', $module_data, $author_name);
                        preg_match('|Author URI:(.*)$|mi', $module_data, $author_uri);
    
                        $arr = [];
    
                        if (isset($name[1])) {
                            $arr['name'] = trim($name[1]);
                            $name = strtolower(str_replace(' ', '_', $arr['name']));
                            $arr['system_name'] = $name;
    
                            foreach ($data as $r) {
                                if ($name == $r->system_name) {
                                    $arr['status'] = $r->status;
                                }
                            }
                        }
    
                        if (!isset($arr['status'])) {
                            // echo 132;die;
                            $arr['status'] = 0;
                            $arr['installed'] = 0;
                        } else {
                            $arr['installed'] = 1;
                        }
    
                        if (isset($uri[1])) {
                            $arr['uri'] = trim($uri[1]);
                        }
    
                        if (isset($category[1])) {
                            $arr['category'] = trim($category[1]);
                        }
    
                        if (isset($version[1])) {
                            $arr['version'] = trim($version[1]);
                        }
    
                        if (isset($description[1])) {
                            $arr['description'] = trim($description[1]);
                        }
    
                        if (isset($author_name[1])) {
                            $arr['author'] = trim($author_name[1]);
                        }
    
                        if (isset($author_uri[1])) {
                            $arr['author_uri'] = trim($author_uri[1]);
                        }
    
                        $return[$arr['system_name']] = (object) $arr;
                    }
                }
            }
            elseif($data_count == 1) {
                // echo "<pre>";print_r($data);die;
                foreach($data as $module_details)
                {
                    if($module == $module_details->system_name)
                    {
                        if (is_dir($path . '/' . $module . '/controllers')) {
                            $controller = $path . '/' . $module . '/controllers/' . ucfirst($module) . '.php';
            
                            if (is_file($controller)) {
                                $module_data = file_get_contents($controller);
            
                                preg_match('|Module Name:(.*)$|mi', $module_data, $name);
                                preg_match('|Category:(.*)$|mi', $module_data, $category);
                                preg_match('|Module URI:(.*)$|mi', $module_data, $uri);
                                preg_match('|Version:(.*)|i', $module_data, $version);
                                preg_match('|Description:(.*)$|mi', $module_data, $description);
                                preg_match('|Author:(.*)$|mi', $module_data, $author_name);
                                preg_match('|Author URI:(.*)$|mi', $module_data, $author_uri);
            
                                $arr = [];
            
                                if (isset($name[1])) {
                                    $arr['name'] = trim($name[1]);
                                    $name = strtolower(str_replace(' ', '_', $arr['name']));
                                    $arr['system_name'] = $name;
            
                                    foreach ($data as $r) {
                                        if ($name == $r->system_name) {
                                            $arr['status'] = $r->status;
                                        }
                                    }
                                }
            
                                if (!isset($arr['status'])) {
                                    // echo 132;die;
                                    $arr['status'] = 0;
                                    $arr['installed'] = 0;
                                } else {
                                    $arr['installed'] = 1;
                                }
            
                                if (isset($uri[1])) {
                                    $arr['uri'] = trim($uri[1]);
                                }
            
                                if (isset($category[1])) {
                                    $arr['category'] = trim($category[1]);
                                }
            
                                if (isset($version[1])) {
                                    $arr['version'] = trim($version[1]);
                                }
            
                                if (isset($description[1])) {
                                    $arr['description'] = trim($description[1]);
                                }
            
                                if (isset($author_name[1])) {
                                    $arr['author'] = trim($author_name[1]);
                                }
            
                                if (isset($author_uri[1])) {
                                    $arr['author_uri'] = trim($author_uri[1]);
                                }
            
                                $return[$arr['system_name']] = (object) $arr;
                            }
                        }
                    }
                }
            }
            else {
                $return = [];
            }
        }

        // Check if the result set is empty
        if (empty($data)) {
            $message = 'No items found';
        } else {
            $message = '';
        }

        // The pager is directly accessible via $this->pager after pagination is called
        return [
            'items' => $return,
            'pager' => $this->pager,
            'message' => $message
        ];
    }
}
