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
use App\Models\BlocksCustom;
use App\Models\BlocksModules;

class Block extends Model
{
    protected $table            = 'hd_blocks';
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


    // public function __construct()
    // {
    //     parent::__construct();

        
    //     // If $db is not provided, use the default database connection
    //     // $this->db = $db ?? db_connect();

    //     // Call a separate method to perform actions related to the User model
    //     // $this->User();

    // }

    public function get_sliders($status = null)
    {
        $db = \Config\Database::connect(); // Get the default database connection

        $builder = $db->table('slider');

        $builder->select('slider.*, sliders.*, count(slide_id) AS slides');
        $builder->join('sliders', 'slider.slider_id = sliders.slider', 'left');
        $builder->groupBy('slider.slider_id'); // Use groupBy instead of group_by

        if (!is_null($status)) {
            $builder->where('status', 1);
        }

        return $builder->get()->getResult();
    }

    public function get_block($id)
    {
        $session = \Config\Services::session();
        // Connect to the database	
        $db = \Config\Database::connect();

        $builder = $db->table('hd_blocks_custom');
        $builder->select('hd_blocks_custom.*');
        $builder->where('id', $id);

        // Ensure that it returns a single result, not a Result object
        return $builder->get()->getRow();
    }
	
	public function listItems($array, $search, $perPage, $page)
    {
        $db = \Config\Database::connect();
		
		$blockcustom = new BlocksCustom();
		$blocks = array();
        $blockModel = new Block();

        $blockmodules = new BlocksModules();

        $custom_blocks = $blockcustom->get()->getResult();
        $module_blocks = $blockmodules->get()->getResult();
        $blocks = array_merge($custom_blocks, $blocks, $module_blocks);
        // echo"<pre>"; print_r($blocks); die;
        $sections = $blockModel->select('id, section')->get()->getResult();
		
        // Begin the selection process using the model's query builder directly
        $this->select('hd_blocks.*');

        // If there's a search term, apply it across relevant fields
        if (!empty($search)) {
            $this->groupStart()
                ->like('hd_blocks.name', $search)
                ->orLike('hd_blocks.module', $search)
                ->orLike('hd_blocks.type', $search)
				->orLike('hd_blocks.section', $search)
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
            'items' => $blocks,
            'pager' => $this->pager,
            'message' => $message,
			'blocks' => $blocks,
			'sections' => $sections
        ];
    }

    public function load_blocks($page, $section)
    {
        $db = \Config\Database::connect(); // Get the default database connection

        $query = $db->table('blocks')
            ->select('*')
            ->where('section', $section)
            ->where('page', $page)
            ->get();

        return $query->getResult();
    }
}
