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
use Config\MyConfig;

class Report extends Model
{
    protected $table = 'hd_reports';
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

    public function recentPaid()
    {
        return $this->orderBy('created_date', 'desc')->where('inv_deleted', 'No')->findAll(5);
    }

    public function monthAmount($year, $month)
    {
        $total = 0;
        $query = "SELECT * FROM hd_payments WHERE MONTH(payment_date) = '$month' AND refunded = 'No' AND YEAR(payment_date) = '$year'";
        $payments = $this->query($query)->getResult();
        foreach ($payments as $p) {
            $amount = $p->amount;
            if ($p->currency != config_item('default_currency')) {
                $amount = Applib::convert_currency($p->currency, $amount);
            }
            $total += $amount;
        }
        return round($total, config_item('currency_decimals'));
    }

    public function yearAmount($year)
    {
        $total = 0;
        $query = "SELECT * FROM hd_payments WHERE refunded = 'No' AND YEAR(payment_date) = '$year'";
        $payments = $this->query($query)->getResult();
        foreach ($payments as $p) {
            $amount = $p->amount;
            if ($p->currency != config_item('default_currency')) {
                $amount = Applib::convert_currency($p->currency, $amount);
            }
            $total += $amount;
        }
        return round($total, config_item('currency_decimals'));
    }

    static function invoiced($year, $month)
    {   
        $db = \Config\Database::connect();
        $helper = new custom_name_helper();
        // Use raw SQL query with placeholders
        $sql = "SELECT * FROM hd_invoices 
                WHERE MONTH(date_saved) = ? 
                AND YEAR(date_saved) = ?";
        $query = $db->query($sql, [$month, $year]);

        $invoices = $query->getResult();
        $total = 0;
        foreach ($invoices as $key => $i) {
            if ($i->currency != $helper->getconfig_item('default_currency')) {
                $total += Applib::convert_currency($i->currency, Invoice::payable($i->inv_id));
            } else {
                $total += Invoice::payable($i->inv_id);
            }
        }
        return round($total, $helper->getconfig_item('currency_decimals'));
    }

    static function total_paid()
    {
        $total = 0;
		$helper = new custom_name_helper();
		$db = \Config\Database::connect();
        //$query = "SELECT * FROM hd_payments WHERE refunded = 'No'";
		
		$payments = $db->table('hd_payments')->select('*')->where('refunded', 'No')->get()->getResult();
        //$payments = self::$db->query($query)->getResult();
		
        foreach ($payments as $p) {
            $amount = $p->amount;
            if ($p->currency != $helper->getconfig_item('default_currency')) {
                $amount = Applib::convert_currency($p->currency, $amount);
            }
            $total += $amount;
        }
        return round($total, $helper->getconfig_item('currency_decimals'));
    }

    public function numPayments()
    {
        $query = "SELECT * FROM hd_payments WHERE refunded = 'No'";
        return $this->query($query)->getNumRows();
    }

    public function paidAvg()
    {
        $this->selectAvg('amount');
        return $this->where('refunded', 'No')->get()->getRow()->amount;
    }

    public function topClients($limit = null)
    {
        $this->orderBy('payment_date', 'desc');
        $this->groupBy("paid_by");
        $this->join('companies', 'companies.co_id = payments.paid_by');
        return $this->where('refunded', 'No')->get($limit)->getResult();
    }


    public function invoiceItems()
    {
        return $this->orderBy('item_id', 'desc')->get('items')->getResult();
    }

    static function year_amount($year)
    {
        $session = \Config\Services::session();
            

            
            // Modify the 'default' property
            
            // Connect to the database

        $db = \Config\Database::connect();

        $total = 0;
        $query = "SELECT * FROM hd_payments WHERE refunded = 'No' AND YEAR(payment_date) = '$year'";
        $payments = $db->query($query)->getResult();
        foreach ($payments as $p) {
            $amount = $p->amount;
            if ($p->currency != config_item('default_currency')) {
                $amount = Applib::convert_currency($p->currency, $amount);
            }
            $total += $amount;
        }
        return round($total, config_item('currency_decimals'));
    }

    static function month_amount($year, $month)
    {
        $session = \Config\Services::session();
            

            
            // Modify the 'default' property
            
            // Connect to the database

        $db = \Config\Database::connect();

        $query = $db->table('hd_payments')
            ->select('amount, currency')
            ->where('MONTH(payment_date)', $month)
            ->where('YEAR(payment_date)', $year)
            ->where('refunded', 'No')
            ->get();

        $payments = $query->getResult();

        $config = new MyConfig();

        $total = 0;

        foreach ($payments as $p) {
            $amount = $p->amount;

            if ($p->currency != $config->default_currency) {
                $amount = Applib::convert_currency($p->currency, $amount);
            }

            $total += $amount;
        }

        return round($total, $config->currencyDecimals);
    }


    static function num_payments()
    {
        // $query = "SELECT * FROM hd_payments WHERE refunded = 'No'";
        // return self::$db->query($query)->num_rows();
        $session = \Config\Services::session();
        

        
        // Modify the 'default' property
        
        // Connect to the database
        $db = \Config\Database::connect(); // Load the database service

        $query = $db->table('hd_payments')
            ->where('refunded', 'No')
            ->countAllResults();

        return $query;
    }

    static function top_clients($limit = NULL)
    {
        $session = \Config\Services::session();
        

        
        // Modify the 'default' property
        
        // Connect to the database
        $db = \Config\Database::connect(); // Load the database service

        $query = $db->table('hd_payments')
            ->select('*')
            ->orderBy('payment_date', 'desc')
            ->groupBy('paid_by')
            ->join('hd_companies', 'hd_companies.co_id = hd_payments.paid_by')
            ->where('refunded', 'No')
            ->get($limit);

        return $query->getResult();
    }


    static function outstanding($limit = NULL)
    {
        $session = \Config\Services::session();
        

        
        // Modify the 'default' property
        
        // Connect to the database
        $db = \Config\Database::connect(); // Load the database service

        $builder = $db->table('hd_invoices');

        $invoices = $builder
            ->where(['archived' => '0', 'status !=' => 'Cancelled'])
            ->get($limit)
            ->getResult();

        foreach ($invoices as $key => &$i) {
            if (Invoice::payment_status($i->inv_id) == 'fully_paid') {
                unset($invoices[$key]);
            }
        }

        return $invoices;
    }
}