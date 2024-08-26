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

class MenuGroup extends Model {
    protected $table = 'hd_menu_group';
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





    public function get_menu_group_titles($group_id) {
        $query = $this->db->table($this->table)
            ->select('*')
            ->where('id', $group_id)
            ->get();

        if($query === false) {
            // Handle the error, log it, or return an empty array or null
            return [];
        }

        return $query->getRow();
    }

    public function get_menu_groupss() {
        try {
            $builder = $this->db->table('hd_menu_group');
            $query = $builder->get();
            // echo $this->db->getLastQuery();
            // die;
            if($query !== false) {
                return $query->getResult();
            } else {
                // Log or handle the error as needed
                log_message('error', 'Database error: '.$this->db->error());
                return []; // or return an empty array or handle the error accordingly
            }
        } catch (\Exception $e) {
            // Log or handle the exception as needed
            log_message('error', 'Exception: '.$e->getMessage());
            return []; // or return an empty array or handle the exception accordingly
        }
    }

    public function get_row($id) {
        $db = \Config\Database::connect();

        $query = $db->table('hd_menu_group')
            ->select('*')
            ->where('id', $id)
            ->get()->getRow();

        return $query;
    }
}