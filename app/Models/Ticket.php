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

class Ticket extends Model
{
    protected $table = 'hd_tickets';
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

    static function get_tickets()
    {	
		$db = \Config\Database::connect();
		
		$result = $db->table('hd_tickets')->orderBy('created', 'desc')->get()->getResult();
       // return self::$db->order_by('created', 'desc')->get('tickets')->result();
		return $result;
    }

    public function getByWhere($array = null)
    {
        // print_r($array);die;
        $session = \Config\Services::session();
		// Connect to the database
		$db = \Config\Database::connect();

        return $db->table('hd_tickets')->where($array)->orderBy('id', 'DESC')->get()->getResult();
    }

    static function view_by_id($ticket)
    {
        $session = \Config\Services::session(); 
        // Connect to the database  
        $db = \Config\Database::connect();

        return $db->table('hd_tickets')
                ->where('id', $ticket)
                ->get()
                ->getRow();

    }

    public function viewReplies($id)
    {
        return $this->where('ticketid', $id)->orderBy('id', 'asc')->findAll();
    }

    static function save_data($table, $data)
    {
        $session = \Config\Services::session();  
        
        // Connect to the database  
        $db = \Config\Database::connect();

        $builder = $db->table($table);
        $builder->insert($data);

        return $db->insertID();
    }

    public function updateData($table, $where, $data)
    {
        return $this->db->table($table)->where($where)->update($data);
    }

    public function generateCode()
    {
        $query = $this->select('ticket_code')->selectMax('id')->get();
        if ($query->numRows() > 0) {
            $row = $query->getRow();
            $code = intval(substr($row->ticket_code, -4));
            $nextNumber = $code + 1;
            if ($nextNumber < config('App')->ticket_start_no) {
                $nextNumber = config('App')->ticket_start_no;
            }
            $nextNumber = $this->refExists($nextNumber);
            return sprintf('%04d', $nextNumber);
        } else {
            return sprintf('%04d', config('App')->ticket_start_no);
        }
    }

    public function refExists($nextNumber)
    {
        $nextNumber = sprintf('%04d', $nextNumber);

        $records = $this->where('ticket_code', $nextNumber)->countAllResults();
        if ($records > 0) {
            return $this->refExists($nextNumber + 1);
        } else {
            return $nextNumber;
        }
    }

    static function update_data($table, $where, $data)
    {
		$session = \Config\Services::session();
        // Connect to the database  
        $db = \Config\Database::connect();

        $db->table($table)->where($where)->update($data);
    }

    function generate_code()
    {
        $session = \Config\Services::session(); 
        // Connect to the database  
        $db = \Config\Database::connect();

        $query = $db->table('hd_tickets')
                  ->select('ticket_code')
                  ->selectMax('id')
                  ->get();

        if ($query->getNumRows() > 0) {
            $row = $query->getRow();
            $code = intval(substr($row->ticket_code, -4));
            $nextNumber = $code + 1;

            if ($nextNumber < config('ticket_start_no')) {
                $nextNumber = config('ticket_start_no');
            }

            $nextNumber = $this->refExists($nextNumber);

            return sprintf('%04d', $nextNumber);
        } else {
            return sprintf('%04d', config('ticket_start_no'));
        }
    }

    static function view_replies($id)
    {
        $session = \Config\Services::session(); 

        // Connect to the database  
        $db = \Config\Database::connect();

        return $db->table('hd_ticketreplies')
            ->where('ticketid', $id)
            ->orderBy('id', 'asc')
            ->get()
            ->getResult();

    }
	
	public function listItems($array, $search, $perPage, $page)
    {
        $db = \Config\Database::connect();

        // Begin the selection process using the model's query builder directly
        $this->select('hd_tickets.*', 'hd_users.*', 'hd_departments.*')
     ->join('hd_users', 'hd_tickets.reporter = hd_users.id')
     ->join('hd_departments', 'hd_tickets.department = hd_departments.deptid');


        // If there's a search term, apply it across relevant fields
        if (!empty($search)) {
            $this->groupStart()
                ->like('hd_tickets.subject', $search)
                ->orLike('hd_tickets.status', $search)
                ->orLike('hd_tickets.priority', $search)
                ->orLike('hd_users.username', $search)
                ->orLike('hd_departments.deptid', $search)
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