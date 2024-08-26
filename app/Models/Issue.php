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

class Issue extends Model
{
    protected $table = 'hd_issues';
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

    public static function requests()
    {
        $db = \Config\Database::connect();

        $builder = $db->table('tickets');
        $builder->select('tickets.*, count(distinct hd_ticketreplies.id) as comments');
        $builder->join('ticketreplies', 'tickets.id = ticketreplies.ticketid', 'left');
        $builder->where('department', 8);
        $builder->orWhere('department', 2);
        $builder->groupBy('tickets.id');

        return $builder->get()->getResult();
    }

    public static function request($slug)
    {
        $db = \Config\Database::connect();

        return $db->table('tickets')
            ->select('tickets.*')
            ->where('ticket_code', explode('_', $slug)[0])
            ->get()
            ->getRow();
    }

    public static function replies($slug)
    {
        $db = \Config\Database::connect();

        return $db->table('ticketreplies')
            ->select('ticketreplies.*')
            ->join('tickets', 'tickets.id = ticketreplies.ticketid')
            ->where('ticket_code', explode('_', $slug)[0])
            ->get()
            ->getResult();
    }

    public static function category($name)
    {
        $db = \Config\Database::connect();

        return $db->table('tickets')
            ->select('tickets.*')
            ->join('departments', 'departments.deptid = tickets.department')
            ->where('deptname', $name)
            ->get()
            ->getResult();
    }

    public static function categories()
    {
        $db = \Config\Database::connect();

        return $db->table('tickets')
            ->select('count(distinct tickets.id) AS num, deptname as cat_name')
            ->join('departments', 'departments.deptid = tickets.department')
            ->whereIn('department', [8, 2])
            ->groupBy('cat_name')
            ->get()
            ->getResult();
    }

    public static function search($text)
    {
        $db = \Config\Database::connect();

        return $db->table('your_table_name')
            ->select('subject')
            ->where('department', 8)
            ->orWhere('department', 2)
            ->like('subject', $text)
            ->get()
            ->getResult();
    }

}