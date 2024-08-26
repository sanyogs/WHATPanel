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

class Items_saved extends Model
{
    protected $table = 'hd_items_saved';
    protected $primaryKey = 'item_id';

    public function listItems($array, $search, $perPage, $page)
    {

        $db = \Config\Database::connect();

        // Begin the selection process using the model's query builder directly
        $this->select('hd_items_saved.*, hd_item_pricing.*, hd_categories.parent, hd_categories.cat_name, hd_categories.id as category_id, hd_categories.pricing_table')
            ->join('hd_item_pricing', 'hd_items_saved.item_id = hd_item_pricing.item_id', 'INNER')
            ->join('hd_categories', 'hd_categories.id = hd_item_pricing.category', 'LEFT')
            ->join('hd_servers', 'hd_items_saved.server = hd_servers.id', 'LEFT')
            ->where($array);

        // If there's a search term, apply it across relevant fields
        if (!empty($search)) {
            $this->groupStart()
                ->like('hd_items_saved.item_name', $search)
                ->orLike('hd_categories.cat_name', $search)
                ->orLike('hd_servers.type', $search)
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

    public static function listItemsFilter($array)
    {

        $session = \Config\Services::session();	

        	

        $config = new \Config\Database();	

        // Modify the 'default' property	
        	

        // Connect to the database	
        $db = \Config\Database::connect();

        $model = new self($db);

        $result = $model->select('hd_items_saved.*, hd_item_pricing.*, hd_servers.name AS server, hd_categories.parent, hd_categories.cat_name, hd_categories.id as category_id, hd_categories.pricing_table')
            ->join('hd_item_pricing', 'hd_items_saved.item_id = hd_item_pricing.item_id', 'INNER')
            ->join('hd_categories', 'hd_categories.id = hd_item_pricing.category', 'LEFT')
            ->join('hd_servers', 'hd_items_saved.server = hd_servers.id', 'LEFT')
            ->where($array)
            ->findAll();

        return $result;
    }

    public static function view_item($id)
    {
        $session = \Config\Services::session();	

        	

        	

        // Modify the 'default' property	
        	

        // Connect to the database	
        $db = \Config\Database::connect();

        $model = new self($db);

        $builder = $model->table('hd_items_saved');
        $builder->select('hd_items_saved.*, hd_item_pricing.*, hd_categories.parent');
        $builder->join('hd_item_pricing', 'hd_items_saved.item_id = hd_item_pricing.item_id', 'LEFT');
        $builder->join('hd_categories', 'hd_categories.id = hd_item_pricing.category', 'LEFT');
        $builder->where('hd_items_saved.item_id', $id);

        return $builder->get()->getRow();
    }
}




?>