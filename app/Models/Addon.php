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

class Addon extends Model
{
    protected $table            = 'hd_items_saved';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function all($array, $search, $perPage, $page)
    {
        $session = \Config\Services::session();

        // Connect to the database
        $db = \Config\Database::connect();

        $this->select('hd_items_saved.*, hd_item_pricing.*')
            ->join('hd_item_pricing', 'hd_items_saved.item_id = hd_item_pricing.item_id', 'INNER')
            ->where('hd_items_saved.addon', 1);

        // If there's a search term, apply it across relevant fields
        if (!empty($search)) {
            $this->groupStart()
                ->like('hd_items_saved.item_name', $search)
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
	
	public static function addon_all()
    {
        $session = \Config\Services::session();
		
        // Connect to the database
        $dbName = \Config\Database::connect();

        $db = db_connect($dbName);

        $builder = $db->table('hd_items_saved');
        $builder->select('hd_items_saved.*, hd_item_pricing.*');
        $builder->join('hd_item_pricing', 'hd_items_saved.item_id = hd_item_pricing.item_id', 'INNER');
        $builder->where('hd_items_saved.addon', 1);

        return $builder->get()->getResult();
    }

    public static function view($id)
    {
        $db = db_connect();

        $builder = $db->table('items_saved');
        $builder->select('items_saved.*, item_pricing.*');
        $builder->join('item_pricing', 'items_saved.item_id = item_pricing.item_id', 'INNER');
        $builder->where('items_saved.item_id', $id);

        return $builder->get()->getRow();
    }

    public static function get_addons()
    {
        $db = db_connect();

        $builder = $db->table('items_saved');
        $builder->select('item_name, item_id');
        $builder->where('deleted', 'No');
        $builder->where('active', 'Yes');
        $builder->where('addon', 1);

        return $builder->get()->getResult();
    }
}
