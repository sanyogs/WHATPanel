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

class Payment extends Model
{
    protected $table = 'hd_payments';
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

    static function recent_paid()
	{
        $session = \Config\Services::session();
        // Connect to the database
        $db = \Config\Database::connect();

        $model = new self($db); // Create an instance of the current model

		return $model->orderBy('created_date', 'desc')
            ->where('inv_deleted', 'No')
            ->limit(15)
            ->findAll();
	}

    static function method_name_by_id($id = null)
	{   
		$db = \Config\Database::connect();

		$result = $db->table('hd_payment_methods')->select('method_name')->where('method_id', $id)->get()->getRow();

		// Check if $result is not null before accessing its properties
		if ($result !== null) {
			return $result->method_name;
		} else {
			return null;
		}
	}

    public function getAll()
    {
        return $this->orderBy('created_date', 'desc')->where('inv_deleted', 'No')->findAll();
    }

    public function getByClient($client = null)
    {
        if ($client > 0) {
            return $this->orderBy('created_date', 'desc')->where(['paid_by' => $client, 'inv_deleted' => 'No'])->findAll();
        } else {
            return [];
        }
    }

    static function view_by_id($id = NULL)
    {
        $session = \Config\Services::session();        

        // Connect to the database
        $db = \Config\Database::connect();

        return $db->table('hd_payments')
                    ->where('p_id', $id)
                    ->get()
                    ->getRow();
    }

    public function clientPayments($company = null)
    {
        return $this->where('paid_by', $company)->findAll();
    }

    public function savePayment($data)
    {
        $this->insert($data);
        return $this->getInsertID();
    }

    static function save_pay($data)
    {
        $db = \Config\Database::connect();

        $res = $db->table('hd_payments')->insert($data);
        if ($res) {
            return $db->insertID();
        } else {
            // Handle the error appropriately
            // For debugging purposes, you can use something like:
            die("Error occurred during insertion: " . $db->error());
        }

    }

    public function updatePayment($id, $data)
    {
        return $this->where('p_id', $id)->update($data);
    }

    public static function deletePayment($id)
    {
        // return $this->where('p_id', $id)->delete();

        $session = \Config\Services::session();

        // Connect to the database
        $db = \Config\Database::connect();
        
        return $db->table('hd_payments')
                    ->where('p_id', $id)
                    ->delete();
    }

    static function by_invoice($id)
    {
        // return self::$db->where('invoice', $id)->get('payments')->result();

        $session = \Config\Services::session();
        
        // Connect to the database
        $db = \Config\Database::connect();

        $result = $db->table('hd_payments')
            ->where('invoice', $id)
            ->get()
            ->getResult();

        return $result;
    }

    public function byRange($start, $end)
    {
        $sql = "SELECT * FROM hd_payments WHERE payment_date BETWEEN '$start' AND '$end' AND refunded = 'No'";
        return $this->db->query($sql)->getResult();
    }

    static function update_pay($id, $data)
    {
        // return self::$db->where('p_id', $id)->update('payments', $data);

        $session = \Config\Services::session();
        
        // Connect to the database
        $db = \Config\Database::connect();

        return $db->table('hd_payments')
            ->where('p_id', $id)
            ->update($data);
    }

    // public static function delete($id)
    // {
    //     $db = \Config\Database::connect(); // Get the database instance

    //     return $db->table('payments')->where('p_id', $id)->delete();
    // }

    static function by_client($client = NULL)
    {
        $session = \Config\Services::session(); 

        // Connect to the database
        $db = \Config\Database::connect();

        if ($client > 0) {
            $payments = $db
                ->table('hd_payments')
                ->orderBy('created_date', 'desc')
                ->where(['paid_by' => $client, 'inv_deleted' => 'No'])
                ->get()
                ->getResult();
        
            return $payments;
        } else {
            return [];
        }
    }

    static function all()
    {
        $session = \Config\Services::session(); 
    
        // Connect to the database
        $db = \Config\Database::connect();

        $payments = $db
        ->table('hd_payments')
        ->orderBy('created_date', 'desc')
        ->where('inv_deleted', 'No')
        ->get()
        ->getResult();
        return $payments;
    }

    static function by_range($start, $end)
    {
        $sql = "SELECT * FROM hd_payments WHERE payment_date BETWEEN '$start' AND '$end' AND refunded = 'No'";
        return self::$db->query($sql)->result();
    }

    static function client_payments($company = NULL)
    {
        // return self::$db->where('paid_by', $company)->get('payments')->result();

        $session = \Config\Services::session(); 

        // Connect to the database  
        $db = \Config\Database::connect();

        $builder = $db->table('hd_payments');
        $builder->where('paid_by', $company);

        return $builder->get()->getResult();
    }
	
	public function listItems($array, $search, $perPage, $page)
    {
        $db = \Config\Database::connect();

        // Begin the selection process using the model's query builder directly
		$this->select('hd_payments.*, hd_invoices.*, hd_users.*')->join('hd_invoices', 'hd_payments.invoice = hd_invoices.inv_id')
     	->join('hd_users', 'hd_payments.p_id = hd_users.id', 'LEFT'); // Specify the join type if necessary


        // If there's a search term, apply it across relevant fields
        if (!empty($search)) {
            $this->groupStart()
                ->like('hd_payments.amount', $search)
                ->orLike('hd_payments.payment_date', $search)
                ->orLike('hd_payments.created_date', $search)
                ->orLike('hd_invoices.reference_no', $search)
                ->orLike('hd_users.username', $search)
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

    public function payment_mode($invoice_id)
	{	
		$db = \Config\Database::connect();
		$mode = $db->table('hd_payments')
					->select('hd_payments.*, hd_invoices.inv_id, hd_invoices.status')
					->join('hd_invoices', 'hd_payments.invoice = hd_invoices.inv_id')
					->orderBy('hd_payments.created_date', 'desc')
					->where('hd_payments.invoice', $invoice_id)
					->where('hd_payments.inv_deleted', 'No')
					->get()
					->getResult();
		return $mode;
	}
}