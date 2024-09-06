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
use App\Libraries\AppLib;
use Config\MyConfig;
use DateTime;
use DateInterval;

use App\Helpers\custom_name_helper;

class Invoice extends Model
{
    protected $table = 'hd_invoices';
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

    protected $dbName;

    public function __construct($db = null)
    {
        parent::__construct($db);

        if ($db !== null) {
            $this->DBGroup = $db->getDatabase();
        }

        $session = \Config\Services::session();
            
        // Connect to the database
        $this->dbName = \Config\Database::connect();
    }
	
	public function listItems($array, $search, $perPage, $page)
    {
        $db = \Config\Database::connect();
        // Begin the selection process using the model's query builder directly
        $this->select('hd_invoices.*, hd_companies.*')->join('hd_companies', 'hd_companies.co_id = hd_invoices.client')->where($array);

        // If there's a search term, apply it across relevant fields
        if (!empty($search)) {
            $this->groupStart()
                ->like('hd_invoices.reference_no', $search)
                ->orLike('hd_invoices.tax', $search)
                ->orLike('hd_invoices.currency', $search)
                ->orLike('hd_invoices.status', $search)
				->orLike('hd_companies.company_name', $search)
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
	
    public static function view_by_id($invoice)
    {
        $session = \Config\Services::session();
            
		// Connect to the database
		$db = \Config\Database::connect();

        // Use the Query Builder to get the row
        $result = $db->table('hd_invoices')
        ->where('inv_id', $invoice)
        ->get()
        ->getRow();

        return $result;
    }

    public static function view_items($invoice)
    {
        return self::$db->table('items')->where('invoice_id', $invoice)->get()->getResult();
    }

    public function view_by_ref($reference)
    {
        return $this->where('reference_no', $reference)->get()->getRow();
    }

    public static function get_invoices($limit = null, $status = null, $cancelled = false)
    {	
		//$request = \Config\Services::request();
		// Pagination Configuration
		//$page = $request->getGet('page') ? $request->getGet('page') : 1;

        //$perPage = $request->getGet('recordsPerPage', FILTER_SANITIZE_NUMBER_INT) ? $request->getGet('recordsPerPage', FILTER_SANITIZE_NUMBER_INT) : 10;

		// Search Filter
		// $search = $request->getGet('search');
		//$data['search'] = $search;

        //$query = $this->listItems([], $search, $perPage, $page);

        // Get items for the current page
		//$data['invoices'] = array_map(function($item) {
			//return (object) $item;
		//}, $query['items']);

        // $data['pager'] = $query['pager'];

		// $data['message'] = $query['message'];

		// $data['perPage'] = $perPage;
		
        $session = \Config\Services::session();

        $db = \Config\Database::connect();

        $builder = $db->table('hd_invoices');

        if ($status != null) {
            $builder->where('status', $status);
        }

        if ($cancelled) {
            return $builder->where(['inv_id >' => 0, 'status !=' => 'Cancelled'])->get()->getResult();
        } else {
            return $builder->orderBy('inv_id', 'desc')->where(['inv_id >' => 0, 'inv_deleted' => 'No'])->get($limit)->getResult();
        }
    }

    public static function saved_items()
    {
        $session = \Config\Services::session();

        $db = \Config\Database::connect();

        return $db->table('hd_items_saved')->where('unit_cost >', 0)->where('deleted', 'No')->get()->getResult();
    }

    /**
     * Update Invoice
     */
    // public static function update($invoice, $data)
    // {
    //     return self::$db->where('inv_id', $invoice)->update('invoices', $data);
    // }

    // public function update($invoice, $data)
    // {
    //     $db = \Config\Database::connect(); // Get the database instance
    //     return $db->table('invoices')->where('inv_id', $invoice)->update($data);
    // }

    public function update($invoice = null, $data = null): bool
    {
        if ($invoice === null || $data === null) {
            return false;
        }

        $session = \Config\Services::session();
        
        // Connect to the database
        $db = \Config\Database::connect();

        return $db->table('hd_invoices')->where('inv_id', $invoice)->update($data);
    }

    /**
     * Save Invoice
     */
    public function save($data = null): bool
    {
        if ($data === null) {
            return false;
        }

        $this->insert($data);
        return $this->getInsertID();
    }

    /**
     * Save items
     */
    public function save_items($data)
    {
        return $this->db->table('items')->insert($data);
    }

    /**
     * Save tax rates
     */
    public static function save_tax($data)
    {
        $session = \Config\Services::session();
        // Connect to the database
        $db = \Config\Database::connect();

        return $db->table('hd_tax_rates')->insert($data);
    }

    /**
     * Get tax rate using ID
     */
    public static function tax_by_id($id)
    {
        $session = \Config\Services::session();
        
        // Connect to the database
        $db = \Config\Database::connect();

        return $db->table('hd_tax_rates')->where('tax_rate_id', $id)->get()->getRow();
    }

    /**
     * Update tax rate
     */
    public static function update_tax($id, $data)
    {
        $session = \Config\Services::session();
        // Connect to the database
        $db = \Config\Database::connect();

        return $db->table('hd_tax_rates')->where('tax_rate_id', $id)->update($data);
    }

    /**
     * Delete tax rate from DB
     */
    public static function delete_tax($id)
    {
        $session = \Config\Services::session();
             
        // Connect to the database
        $db = \Config\Database::connect();

        return $db->table('hd_tax_rates')->where('tax_rate_id', $id)->delete();
    }

    /**
     * View item
     */
    public function view_item($id)
    {
        return $this->db->table('items')->where('item_id', $id)->get()->getRow();
    }

    /**
     * Get invoice due amount
     */
    public static function get_invoice_due_amount($invoice)
    {
        $tax = self::get_invoice_tax($invoice, null, true);
        $discount = self::get_invoice_discount($invoice);
        $invoice_cost = self::get_invoice_subtotal($invoice);
        $payment_made = self::get_invoice_paid($invoice);
        $fee = self::get_invoice_fee($invoice);
        //echo $tax;die;
        $due_amount = (($invoice_cost - $discount) + $tax + $fee) - $payment_made;
        if ($due_amount <= 0) {
            $due_amount = 0;
        }

        return round($due_amount, 2);
    }

    public static function payable($id)
    {
        return (self::get_invoice_subtotal($id) + self::get_invoice_tax($id, null, true) + self::get_invoice_fee($id)) - self::get_invoice_discount($id);
    }

    // Calculate Invoice Tax
    public static function get_invoice_tax($invoice, $type = 'tax1', $sum_tax = false)
    {
        if ($sum_tax) {
            return self::total_tax($invoice);
        }
        $tax = ($type == 'tax2') ? self::view_by_id($invoice)->tax2 : self::view_by_id($invoice)->tax;
        if ($type == 'tax2') {
            return ($tax / 100) * self::get_invoice_subtotal($invoice);
        }

        return ($tax / 100) * self::get_invoice_subtotal($invoice);
    }

	public static function total_tax($invoice = null)
	{
		try {
			if ($invoice) {
				$tax1 = self::view_by_id($invoice)->tax;
				$tax2 = self::view_by_id($invoice)->tax2;

				// Ensure the subtotal is calculated correctly
				$subtotal = self::get_invoice_subtotal($invoice);
				if ($subtotal === false) {
					throw new Exception('Invalid invoice subtotal.');
				}

				$totalTax = ($tax1 / 100) * $subtotal + ($tax2 / 100) * $subtotal;
				return $totalTax;
			} else {
				return 0;
			}
		} catch (Exception $e) {
			// Redirect to the same page with an error message
			$errorMessage = urlencode($e->getMessage());
			header("Location: {$_SERVER['HTTP_REFERER']}?error=$errorMessage");
			exit;
		}
	}
	
	public static function edit_discount($invoice = null)
    {
		$db = \Config\Database::connect();
		//echo "<pre>";print_r(self::view_by_id($invoice));die;
        if($invoice) {
			//return self::view_by_id($invoice)->discount;
         return (self::view_by_id($invoice)->discount / 100) * self::get_invoice_subtotal($invoice);
        }
        else 
        {
            return 0;
        }
    }


    public static function get_invoice_discount($invoice = null)
    {	
        $db = \Config\Database::connect();

        // echo $invoice;die;
        if ($invoice) { 
            $invoice_details = self::view_by_id($invoice);

            if($invoice_details->discount_type == 'Amount')
            {	
                // return (self::view_by_id($invoice)->discount) - self::get_invoice_subtotal($invoice);
                return (self::view_by_id($invoice)->discount);
            }
            elseif($invoice_details->discount_type == 'Percentage')
            { 
                // return (self::view_by_id($invoice)->discount / 100) - self::get_invoice_subtotal($invoice);
                // return (self::view_by_id($invoice)->discount / 100);

                $totalCost = $db
                ->table('hd_items')
                ->selectSum('total_cost')
                ->where('invoice_id', $invoice)
                ->get()
                ->getRow()
                ->total_cost;

                $discountPercent = self::view_by_id($invoice)->discount_percentage;
                $discountAmount = ($totalCost * $discountPercent) / 100;
                $newTotal = $totalCost - $discountAmount;

                return $discountAmount;
            }        
        } else {
            return 0;
        }
    }

    public static function get_invoice_fee($invoice = null)
    {
        if ($invoice) {
            return (self::view_by_id($invoice)->extra_fee / 100) * self::get_invoice_subtotal($invoice);
        } else {
            return 0;
        }
    }

    public static function get_invoice_subtotal($invoice = null)
    {
        $session = \Config\Services::session();
            // Connect to the database
            $db = \Config\Database::connect();

        if ($invoice) { 
            $totalCost = $db
                ->table('hd_items')
                ->selectSum('total_cost')
                ->where('invoice_id', $invoice)
                ->get()
                ->getRow()
                ->total_cost;
        
            return $totalCost;
        } else { 
            return 0;
        }
    }
	
	public static function get_invoice_tax_total($invoice = null)
    {
        $session = \Config\Services::session();
            // Connect to the database
            $db = \Config\Database::connect();

        if ($invoice) { 
            $totalItemTax = $db
                ->table('hd_items')
                ->selectSum('item_tax_total')
                ->where('invoice_id', $invoice)
                ->get()
                ->getRow()
                ->item_tax_total;
        
            return $totalItemTax;
        } else { 
            return 0;
        }
    }

    public static function get_invoice_paid($invoice)
    {
        $session = \Config\Services::session();
        // echo $invoice;die;
        // Connect to the database
        $db = \Config\Database::connect();

        $model = new self($db); // Create an instance of the current model

        $result = $model->selectSum('hd_payments.amount', 'total_amount')
            ->join('hd_payments', 'hd_payments.invoice = hd_invoices.inv_id')
            ->where(['hd_invoices.inv_id' => $invoice, 'hd_payments.refunded' => 'No'])
            ->get()
            ->getRow();

        return $result ? $result->total_amount : 0;
    }


    public static function get_payment_process($id, $gateway)
    {
        return self::$db->select('*')->where(array('trans_id' => $id, 'gateway' => $gateway))->get('payment_process')->row();
    }

    public function all_invoice_amount()
    {
        $custom_name_helper = new custom_name_helper();

        $invoices = self::get_invoices(null, null, true);
        $cost[] = array();
        foreach ($invoices as $key => $invoice) {
            $tax = self::get_invoice_tax($invoice->inv_id, null, true);
            $discount = self::get_invoice_discount($invoice->inv_id);
            $invoice_cost = self::get_invoice_subtotal($invoice->inv_id);

            $tempcost = ($invoice_cost + $tax) - $discount;
            if ($invoice->currency != $custom_name_helper->getconfig_item('default_currency')) {
                $tempcost = Applib::convert_currency($invoice->currency, $tempcost);
            }
            $cost[] = $tempcost;
        }
        if (is_array($cost)) {
            return round(array_sum($cost), $custom_name_helper->getconfig_item('currency_decimals'));
        } else {
            return 0;
        }
    }

    public function invoice_amount()
	{
		$custom_name_helper = new custom_name_helper();

		$invoices = self::get_invoices(null, null, true);
		$total_cost = 0; // Initialize total cost variable

		foreach ($invoices as $invoice) {
			$tax = self::get_invoice_tax($invoice->inv_id, null, true);
			$discount = self::get_invoice_discount($invoice->inv_id);
			$invoice_cost = self::get_invoice_subtotal($invoice->inv_id);

			$tempcost = ($invoice_cost + $tax) - $discount;

			// Convert currency if needed
			if ($invoice->currency != $custom_name_helper->getconfig_item('default_currency')) { 
				$tempcost = Applib::convert_currency($invoice->currency, $tempcost);
			}

			// Add to total cost
			$total_cost += $tempcost;
		}

		// Round the total cost
		return round($total_cost, $custom_name_helper->getconfig_item('currency_decimals'));
	}

    public static function outstanding()
    {
        $custom_name_helper = new custom_name_helper();

        $total = 0;
        $invoices = self::get_invoices(null, null, true);
        foreach ($invoices as $key => $inv) {
            if ($custom_name_helper->getconfig_item('default_currency') != $custom_name_helper->getconfig_item('default_currency')) {
                $total += Applib::convert_currency($custom_name_helper->getconfig_item('default_currency'), self::get_invoice_due_amount($inv->inv_id));
            } else {
                $total += self::get_invoice_due_amount($inv->inv_id);
            }
        }

        return $total;
    }

    public static function overdue()
    {
        $custom_name_helper = new custom_name_helper();

        $total = 0;
        $dateToday = date('Y-m-d');
        $sql = "SELECT * FROM hd_invoices WHERE DATE(due_date) <= '$dateToday' AND status = 'Unpaid'";
        $invoices = self::$db->query($sql)->getResult();
        foreach ($invoices as $key => $inv) {
            if ($custom_name_helper->getconfig_item('default_currency') != $custom_name_helper->getconfig_item('default_currency')) {
                $total += Applib::convert_currency($custom_name_helper->getconfig_item('default_currency'), self::get_invoice_due_amount($inv->inv_id));
            } else {
                $total += self::get_invoice_due_amount($inv->inv_id);
            }
        }

        return $total;
    }

    // Get tax rates
    public static function get_tax_rates()
    {
        $session = \Config\Services::session(); 

        

        

        // Modify the 'default' property    
        

        // Connect to the database  
        $db = \Config\Database::connect();

        return $db->table('hd_tax_rates')->get()->getResult();
    }


    // Get payment methods
    public static function payment_methods()
    {
        $db = \Config\Database::connect();
        return $db->table('payment_methods')->get()->getResult();
    }

    // List items ordered
    public static function has_items($id, $type = 'invoice')
    {
        $table = 'hd_items';

        $session = \Config\Services::session(); 

        // Connect to the database  
        $db = \Config\Database::connect();
        return $db->table($table)->where($type . '_id', $id)->orderBy('item_order', 'asc')->get()->getResult();
    }

    public static function get_renewal_date($date, $days)
    {
        $renewalDate = new DateTime($date);
        $interval = 'P' . $days . 'D'; // Construct ISO 8601 duration format
        $renewalDate->add(new DateInterval($interval));
        return $renewalDate->format('Y-m-d');
    }

    static function add_days_to_date($date, $days) {
        $dateObj = new DateTime($date);
        $dateObj->modify('+' . $days . ' day');
        return $dateObj->format('Y-m-d');
    }    

    // Get Invoice Status
    public static function payment_status($invoice = null)
    {
        if ($invoice) {
            $invoiceStatus = self::view_by_id($invoice)->status;
        } else {
            $invoiceStatus = lang('hd_lang.unknown');
        }

        $tax = self::get_invoice_tax($invoice, null, true);
        $discount = self::get_invoice_discount($invoice);
        $invoiceCost = self::get_invoice_subtotal($invoice);
        $paymentMade = round(self::get_invoice_paid($invoice), 2);
        $due = round(((($invoiceCost - $discount) + $tax) - $paymentMade));

        if ($invoiceStatus == 'Cancelled') {
            return 'cancelled'; // Cancelled
        } elseif ($invoiceStatus != 'Paid') {
            return 'not_paid'; // Not paid
        } elseif ($due <= 0) {
            return 'fully_paid'; // Fully paid
        } else {
            return 'partially_paid'; // Partially Paid
        }
    }

    // Get Invoice Activities
    public static function activities($invoice = null)
    {
        $db = \Config\Database::connect();
        return $db->table('activities')->where(['module_field_id' => $invoice, 'module' => 'invoices'])
            ->orderBy('activity_date', 'desc')->get()->getResult();
    }

    // Get Invoices by CLIENT ID
    public static function get_client_invoices($company)
    {
        $session = \Config\Services::session();
		
            // Connect to the database
 		$db = \Config\Database::connect();

        // return $db->table('hd_invoices')->where(['client' => $company, 'status !=' => 'Cancelled', 'show_client' => 'Yes'])
            //->orderBy('inv_id', 'desc')->get()->getResult();

            $query = $db->table('hd_invoices')
    ->where(['client' => $company, 'status !=' => 'Cancelled', 'show_client' => 'Yes'])
    ->orderBy('inv_id', 'desc')->get()->getResult();
		
        return $query;
    }

    // Get list of paid invoices
    public static function paid_invoices($company = null)
    {	
		$request = \Config\Services::request();
		// Pagination Configuration
		$page = $request->getGet('page') ? $request->getGet('page') : 1;

        $perPage = $request->getGet('recordsPerPage', FILTER_SANITIZE_NUMBER_INT) ? $request->getGet('recordsPerPage', FILTER_SANITIZE_NUMBER_INT) : 10;

		// Search Filter
		$search = $request->getGet('search');
		$data['search'] = $search;

        $query = $this->listItems([], $search, $perPage, $page);

        // Get items for the current page
		$data['invoices'] = array_map(function($item) {
			return (object) $item;
		}, $query['items']);

        $data['pager'] = $query['pager'];

		$data['message'] = $query['message'];

		$data['perPage'] = $perPage;
		
        $session = \Config\Services::session();
            
		// Connect to the database
		$db = \Config\Database::connect();

        if ($company != null) {
            $db->table('hd_invoices')->where(['client' => $company, 'show_client' => 'Yes']);
        }

        return $db->table('hd_invoices')->where(['status' => 'Paid', 'inv_id >' => 0])->get()->getResult();
    }

    // Get list of unpaid invoices
    public static function unpaid_invoices($company = null)
    {	
		$request = \Config\Services::request();
		// Pagination Configuration
		$page = $request->getGet('page') ? $request->getGet('page') : 1;

        $perPage = $request->getGet('recordsPerPage', FILTER_SANITIZE_NUMBER_INT) ? $request->getGet('recordsPerPage', FILTER_SANITIZE_NUMBER_INT) : 10;

		// Search Filter
		$search = $request->getGet('search');
		$data['search'] = $search;

        $query = $this->listItems([], $search, $perPage, $page);

        // Get items for the current page
		$data['invoices'] = array_map(function($item) {
			return (object) $item;
		}, $query['items']);

        $data['pager'] = $query['pager'];

		$data['message'] = $query['message'];

		$data['perPage'] = $perPage;
		
        $session = \Config\Services::session();
		
        $invoices = ($company != null) ? self::get_client_invoices($company) : self::get_invoices(null, null, true);

        foreach ($invoices as $key => &$inv) {
            if (self::payment_status($inv->inv_id) != 'not_paid') {
                unset($invoices[$key]);
            }
        }

        return $invoices;
    }

    // Generate new Invoice Number
    public static function generate_invoice_number()
    {
        $session = \Config\Services::session(); 

        $custom = new custom_name_helper();

        // Modify the 'default' property    
            

        // Connect to the database  
        $db = \Config\Database::connect();

        $builder = $db->table('hd_invoices');

        $subquery = $builder->select('MAX(inv_id)')
            ->getCompiledSelect();

        $query = $builder->select('reference_no, inv_id')
            ->where("inv_id = ($subquery)", null, false)
            ->get();

        if ($query->getNumRows() > 0) {
            $row = $query->getRow();
            $refNumber = intval(substr($row->reference_no, -4));
            $nextNumber = $refNumber + 1;

            if ($nextNumber < $custom->getconfig_item('invoice_start_no')) {
                $nextNumber = $custom->getconfig_item('invoice_start_no');
            }

            $nextNumber = self::ref_exists($nextNumber);

            return sprintf('%04d', $nextNumber);
        } else {
            return sprintf('%04d', $custom->getconfig_item('invoice_start_no'));
        }
    }

    public static function ref_exists($next_number)
    {
        $session = \Config\Services::session(); 

            

           

        // Modify the 'default' property    
            

        // Connect to the database  
        $db = \Config\Database::connect();

        $config = new MyConfig();

        $nextNumber = sprintf('%04d', $next_number);

        $query = $db->table('hd_invoices')
            ->where('reference_no', $config->invoice_prefix . $nextNumber)
            ->get();

        $records = $query->getNumRows();

        if ($records > 0) {
            return self::ref_exists($nextNumber + 1);
        } else {
            return $nextNumber;
        }
    }

    /**
     * Get invoices by date range
     */
    public function by_range($start, $end, $reportBy = null)
    {
        $reportBy = ($reportBy == 'InvoiceDueDate') ? 'due_date' : 'date_saved';
        $sql = "SELECT * FROM invoices WHERE $reportBy BETWEEN '$start' AND '$end'";

        return $this->query($sql)->getResult();
    }

    /**
     * Get a list of partially paid invoices
     */
    public static function partially_paid_invoices($company = null)
    {	
		$request = \Config\Services::request();
		// Pagination Configuration
		$page = $request->getGet('page') ? $request->getGet('page') : 1;

        $perPage = $request->getGet('recordsPerPage', FILTER_SANITIZE_NUMBER_INT) ? $request->getGet('recordsPerPage', FILTER_SANITIZE_NUMBER_INT) : 10;

		// Search Filter
		$search = $request->getGet('search');
		$data['search'] = $search;

        $query = $this->listItems([], $search, $perPage, $page);

        // Get items for the current page
		$data['invoices'] = array_map(function($item) {
			return (object) $item;
		}, $query['items']);

        $data['pager'] = $query['pager'];

		$data['message'] = $query['message'];

		$data['perPage'] = $perPage;
		
        $session = \Config\Services::session();
		
        $invoices = ($company != null) ? self::get_client_invoices($company) : self::get_invoices(null, null, true);
        foreach ($invoices as $key => &$inv) {
            if (self::payment_status($inv->inv_id) != 'partially_paid') {
                unset($invoices[$key]);
            }
        }

        return $invoices;
    }

    /**
     * Get a list of recurring invoices
     */
    public static function recurring_invoices($company = null)
    {	
		$request = \Config\Services::request();
		// Pagination Configuration
		$page = $request->getGet('page') ? $request->getGet('page') : 1;

        $perPage = $request->getGet('recordsPerPage', FILTER_SANITIZE_NUMBER_INT) ? $request->getGet('recordsPerPage', FILTER_SANITIZE_NUMBER_INT) : 10;

		// Search Filter
		$search = $request->getGet('search');
		$data['search'] = $search;

        $query = $this->listItems([], $search, $perPage, $page);

        // Get items for the current page
		$data['invoices'] = array_map(function($item) {
			return (object) $item;
		}, $query['items']);

        $data['pager'] = $query['pager'];

		$data['message'] = $query['message'];

		$data['perPage'] = $perPage;
		
        $session = \Config\Services::session();
		
        $db = \Config\Database::connect(); // Load the database service

        $builder = $db->table('hd_invoices'); // Replace 'your_table_name' with the actual table name

        if ($company != null) {
            $builder->where(['client' => $company, 'show_client' => 'Yes']);
        }

        $result = $builder->where(['recurring' => 'Yes', 'inv_id >' => 0])->get()->getResult();

        return $result;
    }

    /**
     * Evaluate invoice and update status
     */
    public function evaluate_invoice($id)
    {
        $session = \Config\Services::session(); 

        

        

        // Modify the 'default' property    
        

        // Connect to the database
        $db = \Config\Database::connect();

        $has_balance = $this->get_invoice_due_amount($id);

        $has_items = $db
        ->table('hd_items')
        ->where('invoice_id', $id)
        ->countAllResults();

        $is_cancelled = (self::view_by_id($id)->status == 'Cancelled') ? true : false;
        if (!$is_cancelled):
            if ($has_items == 0 || $has_balance > 0) {
                $db
                ->table('hd_invoices')
                ->set('status', 'Unpaid')
                ->where('inv_id', $id)
                ->update();
            } else {
                // self::$db->set('status', 'Paid')->where('inv_id', $id)->update('invoices');
                $db
                ->table('hd_invoices')
                ->set('status', 'Paid')
                ->where('inv_id', $id)
                ->update();
            }
        endif;

        return;
    }

    /**
     * Delete Invoice and related records
     */
    public static function deleteInvoice($invoice)
    {
        $session = \Config\Services::session(); 

        // Connect to the database  
        $db = \Config\Database::connect();

        // Delete invoice items
        $db->table('hd_items')->where('invoice_id', $invoice)->delete();

        // Delete invoice payments
        $db->table('hd_payments')->where('invoice', $invoice)->delete();

        // Clear invoice activities
        $db->table('hd_activities')->where(['module' => 'invoices', 'module_field_id' => $invoice])->delete();

        // Delete invoice
        return $db->table('hd_invoices')->where('inv_id', $invoice)->delete();
    }

    /**
     * Set invoice as recurring
     */
    public static function recur($invoice, $data)
    {
        $custom_name_helper = new custom_name_helper();
        
        $recur_days = App::num_days($data['r_freq']);
        $due_date = self::view_by_id($invoice)->due_date;
        $next_date = date('Y-m-d', strtotime($due_date . '+ ' . $recur_days . ' days'));
        if ($data['recur_end_date'] == '') {
            $recur_end_date = '0000-00-00';
        } else {
            $recur_end_date = date_format(date_create_from_format($custom_name_helper->getconfig_item('date_php_format'), $data['recur_end_date']), 'Y-m-d');
        }
        $data = array(
            'recurring' => 'Yes',
            'r_freq' => $recur_days,
            'recur_frequency' => $data['r_freq'],
            'recur_start_date' => date_format(date_create_from_format($custom_name_helper->getconfig_item('date_php_format'), $data['recur_start_date']), 'Y-m-d'),
            'recur_end_date' => $recur_end_date,
            'recur_next_date' => $next_date,
        );
        self::update($invoice, $data);
        // Log recur activity
        $activity = array(
            'user' => User::get_id(),
            'module' => 'invoices',
            'module_field_id' => $invoice,
            'activity' => 'activity_invoice_made_recur',
            'icon' => 'fa-tweet',
            'value1' => self::view_by_id($invoice)->reference_no,
            'value2' => $next_date,
        );
        App::Log($activity);

        return true;
    }

    /**
     * Credit client's transaction value
     */
    public static function credit_client($id)
	{
		try {
			$payments = Payment::by_invoice($id);
			$amount = 0;

			foreach ($payments as $payment) {
				$amount += $payment->amount;
			}
			
			if(!empty(self::view_by_id($id))){
			$client = Client::view_by_id(self::view_by_id($id)->client);
			$credit = $client->transaction_value;
			$bal = $credit + $amount;

			$balance = [
				'transaction_value' => AppLib::format_deci($bal)
			];

			// Connect to the database
			$db = \Config\Database::connect();

			$db->table('hd_companies')
				->where('co_id', $client->co_id)
				->update($balance);

			// Redirect to the same page after successful update
			return redirect()->to(current_url())->with('success', 'Company balance updated successfully.');
			}
			else{
				return redirect()->to(current_url())->with('error', 'User not found');
			}

		} catch (\Exception $e) {
			// Handle any exceptions thrown during the process
			log_message('error', 'Error in credit_client method: ' . $e->getMessage());

			// Redirect back with error message
			return redirect()->to(current_url())->with('error', 'An error occurred while updating company balance.');
		}
	}


    public static function credit_item($id)
    {
        $db = \Config\Database::connect();

        $item = Order::get_order($id);
        if (self::payment_status($item->invoice_id) == 'fully_paid') {
            $client = Client::view_by_id($item->client_id);
            $credit = $client->transaction_value;
            $bal = $credit + $item->fee;

            $balance = array(
                'transaction_value' => Applib::format_deci($bal)
            );

            $db->table('hd_companies')->where('co_id', $client->co_id)->update($balance);

        }

    }
}