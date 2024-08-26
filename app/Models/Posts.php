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

class Posts extends Model
{
    protected $table            = 'hd_posts';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function getBySlug($type)
    {
        // $slug = service('request')->uri->getSegment(1);
        // $this->where('post_type', 'page');
        // $this->where('slug', $slug);
        // $this->select('*');
        // $query = $this->getWhere(); // Use getWhere instead of get

        // return $query->getRow();

        $session = \Config\Services::session();

        

        

        // Modify the 'default' property
        

        // Connect to the database
        $db = \Config\Database::connect();

        $model = new self($db);

        $result = $model->select('hd_posts.*')
            ->where('post_type', 'page')
            ->where('slug', $type) // Assuming $type is a variable
            ->get()
            ->getRow();

        return $result;
    }

}