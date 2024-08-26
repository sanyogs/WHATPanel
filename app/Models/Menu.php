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

class Menu extends Model
{
    protected $table = 'hd_menu';
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

    public function getMenu($group_id)
    {
        return $this->select('*')
            ->where('group_id', $group_id)
            ->orderBy('parent_id, position')
            ->findAll();
    }

    /**
     * Get group title by group ID
     *
     * @param int $group_id
     * @return object|null
     */
    public function getMenuGroupTitle($group_id)
    {
        return $this->db->table('menu_group')
            ->select('*')
            ->where('id', $group_id)
            ->get()
            ->getRow();
    }

    /**
     * Get all items in the menu group table
     *
     * @return array
     */
    public function getMenuGroups()
    {
        return $this->db->table('menu_group')
            ->select('*')
            ->get()
            ->getResult();
    }

    public function addMenuGroup($data)
    {
        $result = $this->db->table('menu_group')->insert($data);

        if ($result) {
            return [
                'status' => 1,
                'id' => $this->db->insertID(),
            ];
        } else {
            return [
                'status' => 2,
                'msg' => 'Add group error.',
            ];
        }
    }

    public function getRow($id)
    {
        return $this->where('id', $id)->get()->getRow();
    }

    public function getLastPosition($groupId)
    {
        $db = \Config\Database::connect();

        $position = $db->table('hd_menu')->selectMax('position')->where('group_id', $groupId)->where('parent_id', '0')->get()->getRow();
        return $position->position + 1;
    }

    public function getDescendants($id)
    {
        $this->select('id');
        $this->where('parent_id', $id);
        $query = $this->get();
        $data = $query->getResult();

        $ids = [];
        if (!empty($data)) {
            foreach ($data as $row) {
                $ids[] = $row->id;
                $this->getDescendants($row->id);
            }
        }

        return $ids;
    }

    public function delete_menu($id)
    {
        $db = \Config\Database::connect();

        return $db->table('hd_menu')->where('id', $id)->delete();
    }

    public function update_menu_group($data, $id)
    {
        return $this->db->table('menu_group')->update($data, ['id' => $id]);
    }

    public function deleteMenuGroup($id)
    {
        return $this->db->table('menu_group')->where('id', $id)->delete();
    }

    public function deleteMenus($id)
    {
        return $this->where('group_id', $id)->delete();
    }

    public function get_menu_groups()
    {
        $db = \Config\Database::connect();

        try {
            $builder = $db->table('hd_menu_group');
            $query = $builder->get();

            if ($query !== false) {
                return $query->getResult();
            } else {
                // Log or handle the error as needed
                log_message('error', 'Database error: ' . $db->error());
                return []; // or return an empty array or handle the error accordingly
            }
        } catch (\Exception $e) {
            // Log or handle the exception as needed
            log_message('error', 'Exception: ' . $e->getMessage());
            return []; // or return an empty array or handle the exception accordingly
        }
    }

    public function get_menu($group_id)
    {
        $query = $this->select('*')
            ->where('group_id', $group_id)
            ->orderBy('parent_id, position')
            ->get();

        // Check if the query was successful
        if ($query) {
            return $query->getResult();
        }

        // Handle the error, log it, or return an empty array or null
        return [];
    }



    public function get_menu_group_title($group_id)
    {
        $db = \Config\Database::connect();

        $query = $db->table('hd_menu_group')
            ->select('*')
            ->where('id', $group_id)
            ->get();
        // echo "<pre>";
        // echo $this->db->getLastQuery();
        // die;

        if ($query === false) {
            // Handle the error, log it, or return an empty array or null
            return [];
        }

        return $query->getRow();
    }

    public function get_row($id) {
        $db = \Config\Database::connect();

        $query = $db->table('hd_menu')
            ->select('*')
            ->where('id', $id)
            ->get()->getRow();

        return $query;
    }
}