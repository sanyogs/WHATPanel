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

class AccountDetail extends Model
{
    protected $table            = 'hd_account_details';
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
	
	
	public function listItems($array, $search, $perPage, $page, $type)
    {
        $db = \Config\Database::connect();

        // Begin the selection process using the model's query builder directly
        // $query = $db->table('hd_orders')
        //     ->select('hd_orders.*, hd_items.item_name, hd_status.*, hd_companies.companies_name')
        //     ->join('hd_items', 'hd_items.invoice_id = hd_orders.invoice_id')
        //     ->join('hd_companies', 'hd_companies.co_id = hd_orders.client_id')
        //     ->join('hd_status', 'hd_status.some_column = hd_orders.some_related_column', 'left');
			$this->select('hd_orders.id AS id,
					inv_id, 
					order_id, 
					client_id, 
					item_name, 
					status_id, 
					hd_orders.username, 
					domain, 
					company_name, 
					hd_status.status AS order_status, 
					date, 
					type,
					item_parent,
					hd_invoices.status, 
					nameservers,
					hd_servers.type,
					hd_servers.name AS server_name,
					reference_no, hd_orders.*')
			->join('hd_items', 'hd_orders.item = hd_items.item_id', 'LEFT')
			->join('hd_invoices', 'hd_orders.invoice_id = hd_invoices.inv_id', 'LEFT')
			->join('hd_status', 'hd_orders.status_id = hd_status.id', 'LEFT')
			->join('hd_companies', 'hd_orders.client_id = hd_companies.co_id', 'LEFT')
			->join('hd_servers', 'hd_orders.server = hd_servers.id', 'LEFT')
			// ->where($type)
			//->where($array)
			//->where('hd_orders.o_id', 0)
			->orderBy('id', 'desc');
		
        // If there's a search term, apply it across relevant fields
        if (!empty($search)) {
            $c = $this->groupStart()
                ->orlike('hd_items.item_name', $search)
                ->orLike('hd_status.status', $search)
                ->orLike('hd_orders.domain', $search)
                ->orLike('hd_orders.status_id', $search)
                ->orLike('hd_companies.companies_name', $search)
				->orLike('hd_orders.server', $search)
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

}
