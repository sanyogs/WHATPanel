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

class Item extends Model
{
    protected $table = 'hd_items';
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

    public function __construct()
    {
        parent::__construct();
    }

    public static function list_items($array)
    {
        $session = \Config\Services::session();
        
        // Connect to the database
        $dbName = \Config\Database::connect();

        // $db = \Config\Database::connect();
        $builder = $dbName->table('hd_items_saved');
        // echo"<pre>";print_r($builder);die;
        $builder->select([
            'hd_items_saved.*',
            'hd_item_pricing.*',
            'hd_servers.name AS server',
            'hd_categories.parent',
            'hd_categories.cat_name',
            'hd_categories.id as category_id',
            'hd_categories.pricing_table'
        ]);
        $builder->join('hd_item_pricing', 'hd_items_saved.item_id = hd_item_pricing.item_id', 'INNER');
        $builder->join('hd_categories', 'hd_categories.id = hd_item_pricing.category', 'LEFT');
        $builder->join('hd_servers', 'hd_items_saved.server = hd_servers.id', 'LEFT');
        $builder->where($array);

        return $builder->get()->getResult();
    }

    public static function get_domains($id = null)
    {	//echo 90;die;
        $session = \Config\Services::session();

        $db = \Config\Database::connect();

        $builder = $db->table('hd_items_saved');
        $builder->select('item_name, registration, renewal, transfer');
        $builder->join('hd_item_pricing', 'hd_items_saved.item_id = hd_item_pricing.item_id', 'INNER');
        $builder->join('hd_categories', 'hd_categories.id = hd_item_pricing.category', 'LEFT');
        $builder->where('hd_items_saved.deleted', 'No');
        $builder->where('hd_items_saved.active', 'Yes');
        $builder->where('hd_items_saved.display', 'Yes');
        if ($id) {
            $builder->where('hd_categories.id', $id);
        }
        $builder->where('hd_categories.parent', 8);
        $builder->orderBy('hd_items_saved.order_by', 'ASC');
		//print_r($db->getLastQuery());die;
        $query = $builder->get();
		
		//print_r($db->getLastQuery();die);die;
        return $query->getResult();
    }

    public static function get_hosting($id = null)
    {
        $db = \Config\Database::connect();

        $builder = $db->table('hd_items_saved');
        $builder->select('hd_items_saved.*, hd_item_pricing.*, hd_categories.*');
        $builder->join('hd_item_pricing', 'hd_items_saved.item_id = hd_item_pricing.item_id', 'INNER');
        $builder->join('hd_categories', 'hd_categories.id = hd_item_pricing.category', 'LEFT');
        $builder->where('hd_items_saved.deleted', 'No');
        $builder->where('hd_items_saved.active', 'Yes');
        $builder->where('hd_items_saved.display', 'Yes');
        if ($id) {
            $builder->where('hd_categories.id', $id);
        }
        $builder->where('hd_categories.parent', 9);
        $builder->orderBy('hd_items_saved.order_by', 'ASC');

        return $builder->get()->getResult();
    }

    public static function get_services($id = null)
    {
        $db = \Config\Database::connect();

        $builder = $db->table('hd_items_saved');
        $builder->select('*');
        $builder->join('hd_item_pricing', 'hd_items_saved.item_id = hd_item_pricing.item_id', 'INNER');
        $builder->join('hd_categories', 'hd_categories.id = hd_item_pricing.category', 'LEFT');
        $builder->where('hd_items_saved.deleted', 'No');
        $builder->where('hd_items_saved.active', 'Yes');
        $builder->where('hd_categories.id', $id);
        $builder->orderBy('hd_items_saved.order_by', 'ASC');

        return $builder->get()->getResult();
    }

    public static function get_items()
    {
        $session = \Config\Services::session();
        $db = \Config\Database::connect();
        $builder = $db->table('hd_items_saved');
        $builder->select('item_name, item_id');
        $builder->where('deleted', 'No');
        $builder->where('active', 'Yes');

        return $builder->get()->getResult();
    }

    // public static function update($item, $data)
    // {
    //     $db = \Config\Database::connect();
    //     $builder = $db->table('items');

    //     return $builder->where('item_id', $item)->update($data);
    // }

    public function update($id = null, $data = null): bool
    {
        return $this->update($id, $data);
    }
	
	static function view_item($id)
	{	
		//print_r($id);die;
		//print_r(session()->get());die;
		$session = \Config\Services::session();
        // Connect to the database
        $db = \Config\Database::connect();
	 	//print_r($db);die;
        $builder = $db->table('hd_items_saved');
        $builder->select('hd_items_saved.*, hd_item_pricing.*, hd_categories.parent');
		$builder->join('hd_item_pricing','hd_items_saved.item_id = hd_item_pricing.item_id','LEFT');
		$builder->join('hd_categories','hd_categories.id = hd_item_pricing.category','LEFT');
		$builder->where('hd_items_saved.item_id', $id);
	 	//echo"<pre>";print_r($builder->get()->getRow());die;
		return $builder->get()->getRow();
	}
	
    static function view_item_domain($id)
	{  	
		$session = \Config\Services::session();
        // Connect to the database
        $db = \Config\Database::connect();
	 	$query = $db->table('hd_domains')
					->select('hd_domains.*, hd_item_pricing.*, hd_categories.parent, hd_items_saved.*')
					->join('hd_item_pricing','hd_domains.id = hd_item_pricing.item_id','LEFT')
					->join('hd_items_saved','hd_items_saved.item_id = hd_item_pricing.item_id','LEFT')
					->join('hd_categories','hd_categories.id = hd_item_pricing.category','LEFT')
					->where('hd_domains.id', $id)->get()->getRow();
		
		//echo"<pre>";print_r($query);die;
		return $query;
	}
	
	//By Ketan
	static function view_item_domain_tlds($tlds)
	{
		$tlds = '.' . $tlds;
		
		$db = \Config\Database::connect();
		
		$query = $db->table('hd_items_saved')
				->select('hd_items_saved.*, hd_item_pricing.*, hd_categories.*')
				->join('hd_item_pricing','hd_items_saved.item_id = hd_item_pricing.item_id','LEFT')
                ->join('hd_categories','hd_categories.id = hd_item_pricing.category','LEFT')
				->where('hd_items_saved.item_name', $tlds)->get()->getRow();
		
		//echo"<pre>";print_r($query);die;
		return $query;
	}

}