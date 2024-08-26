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

class Inithook extends Model
{
    protected $table = 'hd_inithooks';
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

    public function get_config()
    {
        return $this->db->table('config')->get()->getResult();
    }


    public function get_lang()
    {
        $session = session();

        if ($session->get('lang')) {
            return $session->get('lang');
        } else {
            $user_id = $session->get('user_id');

            $query = $this->select('language')
                ->where('user_id', $user_id)
                ->get();

            if ($query->getNumRows() > 0) {
                $row = $query->getRow();
                return $row->language;
            }
        }

        return null; // or a default language if none is found
    }
}