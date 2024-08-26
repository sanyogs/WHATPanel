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
use CodeIgniter\Model;

use App\Helpers\custom_name_helper;

class Client extends Model
{
	protected $table = 'hd_clients';
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
		// parent::__construct();
	}

	/**
	 * Insert records to companies table and return INSERT ID
	 */
	public function save($data): bool
	{
		// $this->db->table('companies')->insert($data);
		// return $this->db->insertID();

		$session = \Config\Services::session(); 

        // Connect to the database  
        $dbName = \Config\Database::connect();

        $db = db_connect($dbName);
        $db->table('hd_companies')->insert($data);

        return $db->insertID();
	}

	/**
	 * Update client information
	 */

	public function update($id = null, $data = []): bool
	{
		$db = \Config\Database::connect();

		$db->table('hd_companies')->where('co_id', $id)->update($data);

		return $db->affectedRows();
	}

	/**
	 * Get all clients
	 */
	public static function get_all_clients()
	{
		// return self::$db->where(array('co_id >' => 1))->order_by('company_name', 'ASC')->get('companies')->result();

		$session = \Config\Services::session();
        
        // Connect to the database
        $db = \Config\Database::connect();

		/* return $db->table('hd_companies')
			->distinct('company_name')
			->where('co_id >', 1)
			->orderBy('company_name', 'ASC')
			->get()
			->getResult(); 
		return $db->table('hd_companies')
            ->distinct()
            ->select('company_name')
            ->select('hd_companies.*') // Add other columns you need
            ->where('co_id >', 1)
            ->orderBy('company_name', 'ASC')
            ->get()
            ->getResult(); */
		$subQuery = $db->table('hd_companies')
               ->select('MIN(co_id) as co_id')
               ->select('company_name')
               ->where('co_id >', 1)
               ->groupBy('company_name')
               ->getCompiledSelect();

		$result = $db->table('hd_companies')
			->join("($subQuery) as sub", 'hd_companies.co_id = sub.co_id')
			->orderBy('hd_companies.company_name', 'ASC')
			->get()
			->getResult();
		
		return $result;
	}

	public function due_amount($company)
	{
		$session = \Config\Services::session(); 
        // Connect to the database  
        $db = \Config\Database::connect();

		$due = 0;
		$cur = self::view_by_id($company)->currency;
		$invoices = $db->table('hd_invoices')->where(array('client' => $company, 'status !=' => 'Cancelled', 'status !=' => 'Deleted'))->get()->getResult();
		foreach ($invoices as $key => $invoice) {
			if ($invoice->currency != $cur) {
				$due += Applib::convert_currency($cur, Invoice::get_invoice_due_amount($invoice->inv_id));
			} else {
				$due += Invoice::get_invoice_due_amount($invoice->inv_id);
			}
		}
		return $due;
	}

	public static function client_due_amount($company)
	{
		$session = \Config\Services::session();         

        // Connect to the database  
        $db = \Config\Database::connect();

		$due = 0;
		$cur = self::view_by_id($company)->currency;
		$invoices = $db->table('hd_invoices')->where(array('client' => $company, 'status !=' => 'Cancelled', 'status !=' => 'Deleted'))->get()->getResult();
		foreach ($invoices as $key => $invoice) {
			if ($invoice->currency != $cur) {
				//$due += Applib::client_currency(Invoice::get_invoice_due_amount($invoice->inv_id), $cur);
				$due_amount = Invoice::get_invoice_due_amount($invoice->inv_id);
				$converted_amount = Applib::convert_currency($cur, $due_amount);
				$numeric_amount = floatval(str_replace(['$', ','], '', $converted_amount)); // remove currency symbol and commas
				$due += $numeric_amount;
			} else {
				$due += Invoice::get_invoice_due_amount($invoice->inv_id);
			}
		}
		return $due;
	}


	/**
	 * Get all client files
	 */
	public static function has_files($id)
	{
		// Add logic to get client files
		// ...

		// return $this->where('client_id', $id)->get('files')->getResult();

		$session = \Config\Services::session(); 
        // Connect to the database  
        $db = \Config\Database::connect();

		$builder = $db->table('hd_files');
		$builder->where('client_id', $id);

		return $builder->get()->getResult();
	}

	public static function get_client_contacts($company)
	{
		$session = \Config\Services::session(); 

        // Connect to the database  
        $db = \Config\Database::connect();

		$builder = $db->table('hd_account_details');
		$builder->join('hd_companies', 'hd_companies.co_id = hd_account_details.company');
		$builder->join('hd_users', 'hd_users.id = hd_account_details.user_id');
		$builder->where('hd_account_details.company', $company);

		return $builder->get()->getResult();
	}

	public function payable($company)
	{
		$total = 0;
		$invoices = Invoice::get_client_invoices($company);
		foreach ($invoices as $key => $inv) {
			if ($inv->currency != config_item('default_currency')) {
				$total += Applib::convert_currency($inv->currency, Invoice::payable($inv->inv_id));
			} else {
				$total += Invoice::payable($inv->inv_id);
			}
		}
		return $total;
	}

	public static function client_payable($company)
	{
		$session = \Config\Services::session(); 

        // Modify the 'default' property    
        

        // Connect to the database  
        $db = \Config\Database::connect();

		$total = 0;
		$cur = self::view_by_id($company)->currency;
		$invoices = Invoice::get_client_invoices($company);
		foreach ($invoices as $key => $inv) {
			if ($inv->currency != $cur) {
				$total += Applib::client_currency($cur, Invoice::payable($inv->inv_id));
			} else {
				$total += Invoice::payable($inv->inv_id);
			}
		}
		return $total;
	}

	/**
	 * Get client by ID
	 */
	public static function view_by_id($company)
	{
		// echo $company;die;
		$session = \Config\Services::session();

		$db = \Config\Database::connect();
		
		//$companyData = $db->table('hd_companies') ->select('hd_companies.*, hd_users.username, hd_users.password') ->join('hd_users', 'hd_companies.co_id = hd_users.id', 'left') ->where('hd_companies.co_id', $company) ->get() ->getRow();
		
		$companyData = $db->table('hd_companies')
            ->select('hd_companies.*, hd_users.*')
            ->join('hd_users', 'hd_companies.primary_contact = hd_users.id', 'left')
            ->where('hd_companies.co_id', $company)
            ->get()
            ->getRow();
		
		//echo"<pre>";print_r($companyData);die;
		// Check if company data and password exist
		if ($companyData && isset($companyData->password)) {
			// Convert hashed password to string
			$hashedPassword = $companyData->password;
			$passwordString = password_hash($hashedPassword, PASSWORD_DEFAULT);
			// Replace hashed password with string representation
			$companyData->password = $passwordString;
		}

		return $companyData;
	}


	public function get_by_user($uid)
	{	
		$session = \Config\Services::session();
		
		// Connect to the database
		$db = \Config\Database::connect();
		// print_r($db);die;
		$result = $db->table('hd_companies')
                    ->where('primary_contact', (int)$uid)
                    ->get()
                    ->getRow();
					
		//echo"<pre>";print_r($db->getlastQuery());die;
		return $result;
	}

	public static function custom_fields($client)
	{
		$session = \Config\Services::session(); 

        // Connect to the database  
        $db = \Config\Database::connect();

		return $db->table('hd_formmeta')->where(array('module' => 'clients', 'client_id' => $client))->get()->getResult();
	}

	public static function client_currency($company = false)
	{
		if (!$company) {
			return false;
		}

		$custom_helper = new custom_name_helper();

		$session = \Config\Services::session();
		
		// Modify the 'default' property
		
		// Connect to the database
		$db = \Config\Database::connect();

		$dcurrency = $db->table('hd_currencies')->where('code', $custom_helper->getconfig_item('default_currency'))->get()->getResult();
		$client = $db->table('hd_companies')->where('co_id', $company)->get()->getResult();
		if (count($client) == 0) {
			return $dcurrency[0];
		}
		$currency = $db->table('hd_currencies')->where('code', $client[0]->currency)->get()->getResult();
		if (count($currency) > 0) {
			return $currency[0];
		}
		$dcurrency = $db->table('hd_currencies')->where('code', $custom_helper->getconfig_item('default_currency'))->get()->getResult();
		if (count($dcurrency) > 0) {
			return $dcurrency[0];
		}

	}

	public function client_language($id = false)
	{
		if (!$id) {
			return FALSE;
		}
		$language = self::$db->where('name', self::view_by_id($id)->language)->get('languages')->result();
		return $language[0];
	}

	public static function amount_paid($company)
	{
		$session = \Config\Services::session(); 

        
		$custom_helper = new custom_name_helper();
        

        // Modify the 'default' property    
        

        // Connect to the database  
        $db = \Config\Database::connect();

		$total = 0;
		if ($company > 0) {
			$payments = $db->table('hd_payments')->where(array('paid_by' => $company, 'refunded' => 'No'))->get()->getResult();
			foreach ($payments as $key => $pay) {
				if ($pay->currency != $custom_helper->getconfig_item('default_currency')) {
					$total += Applib::convert_currency($pay->currency, $pay->amount);
				} else {
					$total += $pay->amount;
				}
			}
		}
		return $total;
	}

	public static function client_amount_paid($company)
	{
		$session = \Config\Services::session(); 

        

        

        // Modify the 'default' property    
        

        // Connect to the database  
        $db = \Config\Database::connect();

		$total = 0;
		if ($company > 0) {
			$cur = self::view_by_id($company)->currency;
			$payments = $db->table('hd_payments')->where(array('paid_by' => $company, 'refunded' => 'No'))->get()->getResult();
			foreach ($payments as $key => $pay) {
				if ($pay->currency != $cur) {
					$total += Applib::client_currency($cur, $pay->amount);
				} else {
					$total += $pay->amount;
				}
			}
		}
		return $total;
	}

	// Add other methods as needed

	// ...

	// Examples:

	public function get_currency_code($company = false)
	{
		if (!$company) {
			return FALSE;
		}
		$dcurrency = self::$db->where('code', config_item('default_currency'))->get('currencies')->result();
		$client = self::$db->where('co_id', $company)->get('companies')->result();
		if (count($client) == 0) {
			return $dcurrency[0];
		}
		$currency = self::$db->where('code', $client[0]->currency)->get('currencies')->result();
		if (count($currency) > 0) {
			return $currency[0];
		}
		$dcurrency = self::$db->where('code', config_item('default_currency'))->get('currencies')->result();
		if (count($dcurrency) > 0) {
			return $dcurrency[0];
		}
	}

	public function month_amount($year, $month, $client)
	{
		$session = \Config\Services::session(); 

		$helpers = new custom_name_helper();

            
            // Modify the 'default' property
            
            // Connect to the database
            $db = \Config\Database::connect();

		$total = 0;
		$query = "SELECT * FROM hd_payments WHERE paid_by = '$client' AND MONTH(payment_date) = '$month' AND refunded = 'No' AND YEAR(payment_date) = '$year'";
		$payments = $db->query($query)->getResult();
		foreach ($payments as $p) {
			$amount = $p->amount;
			if ($p->currency != $helpers->getconfig_item('default_currency')) {
				$amount = AppLib::convert_currency($p->currency, $amount);
			}
			$total += $amount;
		}
		return round($total, $helpers->getconfig_item('currency_decimals'));
	}

	public function delete($id = null, bool $purge = false)
	{
		$session = \Config\Services::session(); 

        

        

        // Modify the 'default' property    
        

        // Connect to the database  
        $db = \Config\Database::connect();

		$company_invoices = Invoice::get_client_invoices($id);
		$company_contacts = self::get_client_contacts($id);

		if (count($company_invoices)) {
			foreach ($company_invoices as $invoice) {
				// delete invoice items
				$db->table('hd_items')->where('invoice_id', $invoice->inv_id)->delete();
			}
		}

		// delete invoices
		$db->table('hd_invoices')->where('client', $id)->delete();

		// delete client payments
		$db->table('hd_payments')->where('paid_by', $id)->delete();

		// clear client activities
		$db->table('hd_activities')->where(['module' => 'Clients', 'module_field_id' => $id])->delete();

		// delete company
		$db->table('hd_companies')->where('co_id', $id)->delete();

		if (count($company_contacts)) {
			foreach ($company_contacts as $contact) {
				// set contacts to blank
				$db->table('hd_account_details')->set('company', '-')->where('company', $id)->update();
			}
		}
	}




	public static function recent_activities($user, $limit = 10)
	{
		$session = \Config\Services::session(); 

        

        

        // Modify the 'default' property    
        

        // Connect to the database  
        $db = \Config\Database::connect();

		$result = $db->table('hd_activities')
			->where('user', $user)
			->orderBy('activity_date', 'DESC')
			->get($limit)
			->getResult();

		return $result;
	}
}