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

class Domain extends Model
{
	protected $table = 'hd_domains';
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

		if ($db !== null) {
			$this->DBGroup = $db->getDatabase();
		}
	}

	public static function by_where($array = null, $type)
	{
		$session = \Config\Services::session();        

		$db = \Config\Database::connect(); // Get a new database instance

		$query = $db->table('hd_orders')
			->select('hd_orders.id AS id,
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
					item_parent,
					hd_invoices.status, 
					nameservers,
					hd_servers.type,
					hd_servers.name AS server_name,
					reference_no')
			->join('hd_items', 'hd_orders.item = hd_items.item_id', 'LEFT')
			->join('hd_invoices', 'hd_orders.invoice_id = hd_invoices.inv_id', 'LEFT')
			->join('hd_status', 'hd_orders.status_id = hd_status.id', 'LEFT')
			->join('hd_companies', 'hd_orders.client_id = hd_companies.co_id', 'LEFT')
			->join('hd_servers', 'hd_orders.server = hd_servers.id', 'LEFT')
			->where($type)
			->where($array)
			->where('o_id', 0)
			->distinct('domain')
			->orderBy('id', 'desc');

		$result = $query->get()->getResult();

		return $result;
	}

	public static function by_client($company, $type)
	{	
		$session = \Config\Services::session();
		$db = \Config\Database::connect();
		$query = $db->table('hd_orders')->select('hd_orders.id AS id, order_id, client_id, item_name, status_id, username, password,domain, company_name, status.status AS order_status, date, nameservers, hd_invoices.status, reference_no')->join('items','orders.item = items.item_id','LEFT')->join('invoices','orders.invoice_id = invoices.inv_id','LEFT')->join('status','orders.status_id = status.id','LEFT')->join('companies','orders.client_id = companies.co_id','LEFT')->where($type)->where('client_id', $company)->orderBy('id', 'desc')->get()->getResult();
		print_r($db->getLastQuery());die;
		return $query;
	}

	public static function get_details($domain)
	{
		self::$db->select('*');
		self::$db->from('orders');
		self::$db->where('type', 'domain');
		self::$db->where('domain', $domain);
		return self::$db->get()->row();
	}

	public function listItems($array, $search, $perPage, $page)
    {
        $db = \Config\Database::connect();

        // Begin the selection process using the model's query builder directly
        $this->select('hd_domains.*, hd_categories.cat_name')
			//->join('hd_categories', 'hd_domains.category = hd_categories.id');
			->join('hd_categories', 'hd_domains.parent_category = hd_categories.id')->where('hd_domains.display', 'yes');

        // If there's a search term, apply it across relevant fields
        if (!empty($search)) {
            $this->groupStart()
                ->like('hd_domains.ext_name', $search)
                ->orLike('hd_domains.registrar', $search)
                ->orLike('hd_categories.cat_name', $search)
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

	public function listItemsAPI($array, $search, $perPage, $page)
    {
		// echo 456;die;	
        $db = \Config\Database::connect();

        // Begin the selection process using the model's query builder directly
        $this->select('hd_domains.ext_name,hd_domains.ext_order, hd_categories.cat_name, hd_customer_pricing_view.*')
			->join('hd_categories', 'hd_domains.category = hd_categories.id')
			->join('hd_customer_pricing_view', 'hd_domains.ext_name = hd_customer_pricing_view.ext_name');

        // If there's a search term, apply it across relevant fields
        if (!empty($search)) {
            $this->groupStart()
				->like('hd_domains.ext_name', $search)
				->orLike('hd_domains.registrar', $search)
				->orLike('hd_categories.cat_name', $search)
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