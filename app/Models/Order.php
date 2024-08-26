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
use App\ThirdParty\MX\Modules;
use CodeIgniter\Model;

use App\Helpers\app_helper;
use App\Helpers\custom_name_helper;

use Config\Database;

class Order extends Model
{
    protected $table = 'hd_orders';
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

    public function listOrders($array, $search, $perPage, $page)
    {
        $session = \Config\Services::session();

        // Connect to the database	
        $db = \Config\Database::connect();

		 $this->select('min(hd_orders.id) AS id, order_id, status_id, order_status_id, inv_id, company_name, hd_status.status AS order_status, date, hd_invoices.status, reference_no')
        ->join('hd_invoices', 'hd_orders.invoice_id = hd_invoices.inv_id', 'LEFT')
        ->join('hd_status', 'hd_orders.status_id = hd_status.id', 'LEFT')
        ->join('hd_companies', 'hd_orders.client_id = hd_companies.co_id', 'LEFT')
        ->join('hd_items', 'hd_orders.item = hd_items.item_id', 'LEFT')
        ->where('hd_orders.invoice_id >', 0)
        ->where($array)
        ->groupBy('hd_orders.order_id') // Use the table alias in the GROUP BY clause
        ->orderBy('id', 'desc');
        // If there's a search term, apply it across relevant fields
        if (!empty($search)) {
            $this->groupStart()
                ->like('hd_invoices.reference_no', $search)
                ->orLike('hd_companies.company_name', $search)
                ->orLike('hd_orders.order_id', $search)
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

    public static function get_order($id)
    {
        $session = \Config\Services::session();
        $config = new Database();
        // Connect to the database	
        $db = Database::connect($config->default);

        $query = $db->table('hd_orders')
            ->select('hd_orders.*, hd_items.*, hd_servers.type AS server_type, hd_status.status AS order_status, hd_items_saved.package_name, hd_servers.name AS server_name, hd_servers.hostname, hd_servers.ns1, hd_servers.ns2, hd_servers.ns3, hd_servers.ns4, hd_servers.ns5, reseller_package')
            ->join('hd_items', 'hd_orders.item = hd_items.item_id', 'LEFT')
            ->join('hd_items_saved', 'hd_orders.item_parent = hd_items_saved.item_id', 'LEFT')
            ->join('hd_servers', 'hd_orders.server = hd_servers.id', 'LEFT')
            ->join('hd_invoices', 'hd_orders.invoice_id = hd_invoices.inv_id', 'LEFT')
            ->join('hd_status', 'hd_orders.status_id = hd_status.id', 'LEFT')
            ->where('hd_orders.id', $id);
        return $query->get()->getRow();
    }

    static function get_server($id)
    {
        // self::$db->select('*'); 
        // self::$db->from('servers');
        // self::$db->where('id', $id); 
        // return self::$db->get()->row();

        $session = \Config\Services::session();



        $config = new Database();

        // Modify the 'default' property	


        // Connect to the database	
        $db = Database::connect($config->default);

        return $db->table('hd_servers')
            ->select('*')
            ->where('id', $id)
            ->get()
            ->getRow();
    }

    public function getPackage($id)
    {
        return $this->select('*')->from('items_saved')->where('item_id', $id)->get()->getRow();
    }

    public function getDomainOrder($id)
    {
        return $this->select('orders.*, status.status as domain_status, invoices.inv_id, invoices.reference_no, items.*')
            ->from('orders')
            ->join('items', 'orders.item = items.item_id', 'LEFT')
            ->join('status', 'orders.status_id = status.id', 'LEFT')
            ->join('invoices', 'orders.invoice_id = invoices.inv_id', 'LEFT')
            ->where('orders.id', $id)
            ->get()
            ->getRow();
    }

    public function getResellerClubIds($domain)
    {
        return $this->select('*')
            ->from('resellerclub_ids')
            ->where('domain', $domain)
            ->get()
            ->getRow();
    }

    public static function viewItem($id)
    {
		$db = \Config\Database::connect();
		
        return $result = $db->table('hd_orders')
			->select('hd_orders.*, hd_invoices.*, hd_companies.*')
			->join('hd_invoices', 'hd_orders.invoice_id = hd_invoices.inv_id', 'LEFT')
			->join('hd_companies', 'hd_orders.client_id = hd_companies.co_id', 'LEFT')
			->where('hd_orders.order_id', $id)
			->get()
			->getRow();
    }

    static function pending_domains()
    {
        $db = new Order();
        $query = $db->table('orders')
            ->select('id')
            ->where('status_id', 5)
            ->whereIn('type', ['domain', 'domain_only']);

        return $query->countAllResults();
    }

    static function pending_accounts()
    {
        $db = \Config\Database::connect();

        $query = $db->table('orders')
            ->select('id')
            ->where('status_id', 5)
            ->where('type', 'hosting')
            ->get();

        return $query->getNumRows();
    }

    public function clientDomains($client)
    {
        return $this->select('id')
            ->from('orders')
            ->where('client_id', $client)
            ->where('status_id >', 5)
            ->where("(type ='domain' OR type ='domain_only')")
            ->get()
            ->getNumRows();
    }

    public function clientAccounts($client)
    {
        return $this->select('id')
            ->from('orders')
            ->where('client_id', $client)
            ->where(['o_id' => 0, 'type' => 'hosting'])
            ->countAllResults();
    }

    public static function all_orders()
    {
        $model = new Order(); // Create an instance of the model

        $query = $model->table('orders')
            ->select('*')
            ->join('items', 'orders.item = items.item_id', 'LEFT')
            ->where('status_id', 6)
            ->orderBy('renewal_date', 'DESC')
            ->get();

        return $query->getResult();
    }

    public function clientOrders($client)
    {
        return $this->select('*')
            ->from('orders')
            ->join('items', 'orders.item = items.item_id', 'LEFT')
            ->where('client_id', $client)
            ->where('status_id', 6)
            ->where('date(renewal_date) >', date('Y-m-d'))
            ->get()
            ->getResult();
    }

    static function unpaid_orders()
    {
        $db = \Config\Database::connect();

        $query = $db->table('invoices')
            ->select('inv_id')
            ->join('orders', 'orders.invoice_id = invoices.inv_id', 'LEFT')
            ->where('inv_deleted', 'No')
            ->where('status', 'Unpaid')
            ->groupBy('inv_id')
            ->get();

        return $query->getNumRows();
    }

    public function getNameservers($id, $domainServers = null)
    {
        $order = $this->getOrder($id);

        if ($order->nameservers != '') {
            return $order->nameservers;
        }

        if ($domainServers) {
            foreach ($domainServers as $array) {
                foreach ($array as $k => $v) {
                    if ($k == $order->domain) {
                        $server = $this->db->table('servers')
                            ->select('*')
                            ->where('id', $v)
                            ->get()
                            ->getRow();

                        $ns = $this->getNs($server);
                        return $ns;
                    }
                }
            }
        }

        $server = $this->getServer($order->server);
        $ns = $this->getNs($server);
        return $ns;
    }

    // Assuming you are in the OrderModel class

    public function getNS($server)
    {
        $nameservers = '';

        if (!empty($server->ns1)) {
            $nameservers .= $server->ns1;
        }

        if (!empty($server->ns2)) {
            $nameservers .= ',' . $server->ns2;
        }

        if (!empty($server->ns3)) {
            $nameservers .= ',' . $server->ns3;
        }

        if (!empty($server->ns4)) {
            $nameservers .= ',' . $server->ns4;
        }

        if (!empty($server->ns5)) {
            $nameservers .= ',' . $server->ns5;
        }

        return $nameservers;
    }

    public static function send_account_details($id = null)
    {
        $app_helper = new app_helper();
        $helper = new custom_name_helper();
        $account = Order::get_order($id);
        $client = Client::view_by_id($account->client_id);
        $message = App::email_template('hosting_account', 'template_body');
        $subject = App::email_template('hosting_account', 'subject');
        $signature = App::email_template('email_signature', 'template_body');
        $subject = $subject . ' for ' . $account->domain;

        $logo_link = $app_helper->create_email_logo();
        $logo = str_replace("{INVOICE_LOGO}", $logo_link, $message);
        $username = str_replace("{ACCOUNT_USERNAME}", $account->username, $logo);
        $password = str_replace("{ACCOUNT_PASSWORD}", $account->password, $username);
        $domain = str_replace("{DOMAIN}", $account->domain, $password);
        $renewal_date = str_replace("{RENEWAL_DATE}", $account->renewal_date, $domain);
        $package = str_replace("{PACKAGE}", $account->item_name, $renewal_date);
        $renewal = str_replace("{RENEWAL}", ucfirst($account->renewal), $package);
        $amount = str_replace("{AMOUNT}", App::currencies($helper->getconfig_item('default_currency'))->symbol . AppLib::format_quantity($account->total_cost), $renewal);
        $EmailSignature = str_replace("{SIGNATURE}", $signature, $amount);
        $client_name = str_replace("{CLIENT}", $client->company_name, $EmailSignature);
        $company = str_replace("{SITE_NAME}", $helper->getconfig_item('company_name'), $client_name);
        $server = str_replace("{SERVER_URL}", $account->hostname, $company);
        $ns1 = str_replace("{NAMESERVER_1}", $account->ns1, $server);
        $ns2 = str_replace("{NAMESERVER_2}", $account->ns2, $ns1);
        $ns3 = str_replace("{NAMESERVER_3}", $account->ns3, $ns2);
        $ns4 = str_replace("{NAMESERVER_4}", $account->ns4, $ns3);
        $message = str_replace("{NAMESERVER_5}", $account->ns5, $ns4);

        $params = array(
            'recipient' => $client->company_email,
            'subject' => $subject,
            'message' => $message,
        );

        //Modules::run('fomailer/send_email', $params);
    }


    public function get_package($id)
    {
        $session = \Config\Services::session();


        // Connect to the database
        $dbName = \Config\Database::connect();

        return $dbName->table('hd_items_saved')->where('item_id', $id)->get()->getRow();
    }

    // public function update($order, $data)
    // {
    //     $builder = $this->db->table($this->table);

    //     return $builder->where('id', $order)->update($data);
    // }

    public function update($id = null, $data = null): bool
    {
        $builder = $this->db->table($this->table);

        return $builder->where('id', $id)->update($data);
    }

    static function client_orders($client)
    {
        $session = \Config\Services::session();



        $config = new Database();

        // Modify the 'default' property	


        // Connect to the database	
        $db = Database::connect($config->default);

        $result = $db->table('hd_orders')
            ->select('*')
            ->join('hd_items', 'hd_orders.item = hd_items.item_id', 'LEFT')
            ->where('client_id', $client)
            ->where('status_id', 6)
            ->where('DATE(renewal_date) >', date('Y-m-d'))
            ->get()
            ->getResult();

        return $result;
    }

    static function client_domains($client)
    {
        $session = \Config\Services::session();



        $config = new Database();

        // Modify the 'default' property	


        // Connect to the database	
        $db = Database::connect($config->default);

        $query = $db->table('hd_orders')
            ->select('id')
            ->where('client_id', $client)
            ->where('status_id >', 5)
            ->where("(type ='domain' OR type ='domain_only')")
            ->get();

        return $query->getNumRows();
    }

    static function client_accounts($client)
    {
        $session = \Config\Services::session();

        // Connect to the database	
        $db = Database::connect();

        $query = $db->table('hd_orders')
            ->select('id')
            ->where('client_id', $client)
            ->where('o_id', 0)
            ->where("(type ='hosting')")
            ->get();

        return $query->getNumRows();
    }

    static function get_domain_order($id)
    {
        $session = \Config\Services::session();

        // Connect to the database	
        $db = Database::connect();

        $query = $db->table('hd_orders')
            ->select('hd_orders.*, hd_status.status as domain_status, hd_invoices.inv_id, hd_invoices.reference_no, hd_items.*')
            ->join('hd_items', 'hd_orders.item = hd_items.item_id', 'LEFT')
            ->join('hd_status', 'hd_orders.status_id = hd_status.id', 'LEFT')
            ->join('hd_invoices', 'hd_orders.invoice_id = hd_invoices.inv_id', 'LEFT')
            ->where('hd_orders.id', $id)
            ->get();

        return $query->getRow();
    }

    static function get_nameservers ($id, $domain_servers = null)
	{ 		
        $db = Database::connect();

		$order = self::get_order($id); 

		if($order->nameservers != '')
		{
			return $order->nameservers;
		}

		if($domain_servers)
		{
			 
			foreach ($domain_servers as $array)
			{
				foreach ($array as $k => $v)
				{
					if($k == $order->domain)
					{
						$server = $db->table('hd_servers')->where('id', $v)->get()->getRow();

					 	$ns = self::get_ns($server);
						return $ns;						
					}
				}
			}
		}

		$server = self::get_server($order->server);
		$ns = self::get_ns($server);
		return $ns; 
	}

    static function get_ns ($server)
	{
		$nameservers = '';

		if(isset($server->ns1) && $server->ns1 != '')
		{
			$nameservers .=	$server->ns1;
		}

		if(isset($server->ns2) && $server->ns2 != '')
		{
			$nameservers .=	','.$server->ns2;
		}

		if(isset($server->ns3) && $server->ns3 != '')
		{
			$nameservers .=	','.$server->ns3;
		}

		if(isset($server->ns4) && $server->ns4 != '')
		{
			$nameservers .=	','.$server->ns4;
		}

		if(isset($server->ns5) && $server->ns5 != '')
		{
			$nameservers .=	','.$server->ns5;
		}

		return $nameservers;
	}
	
	public function evaluate_order($id)
    {
        $session = \Config\Services::session(); 
		
        // Connect to the database
        $db = \Config\Database::connect();
		
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
}
