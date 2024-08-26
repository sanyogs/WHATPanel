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
use Config\Database;
use Config\Services;

class Slider extends Model
{
    protected $table = 'hd_slider';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'name',
    ];

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


    public function __construct($db = null)
    {
        parent::__construct($db);

        $session = \Config\Services::session();
        
        // Connect to the database
        $dbName = \Config\Database::connect();
    }
    // public function get_sliders($status = null)
    // {
    //     $builder = new Slider($this->db);
    //     echo "<pre>";
    //     print_r($builder);
    //     die;
    //     $builder->select('slider.*, sliders.*, COUNT(slide_id) AS slides');
    //     $builder->join('sliders', 'slider.slider_id = sliders.slider', 'left');
    //     $builder->groupBy('slider.slider_id');

    //     if (!is_null($status)) {
    //         $builder->where('status', 1);
    //     }

    //     $result = $builder->get();
    //     echo $result;
    //     die;

    //     if ($result === false) {
    //         // Display the query for debugging purposes
    //         // die($builder->getLastQuery());
    //         // You can also show the database error using the following:
    //         // $this->db->error();
    //         // Or log it for further investigation
    //         // log_message('error', 'Database Error: ' . $this->db->error());
    //         echo 1;
    //         die;
    //     }

    //     return $result->getResult();
    // }


    public function getSlides($id)
    {
        return $this->select('slider.*, sliders.*')
            ->join('sliders', 'slider.slider_id = sliders.slider', 'right')
            ->where('slider_id', $id)
            ->get()
            ->getResult();
    }

    public function getSlider($id)
    {
        return $this->select('*')
            ->where('slider_id', $id)
            ->get()
            ->getRow();
    }

    public function getSlide($id)
    {
        return $this->select('*')
            ->from('sliders')
            ->where('slide_id', $id)
            ->get()
            ->getRow();
    }

    public function get_slider($id)
    {
        $builder = $this->db->table('hd_slider');
        $builder->select('*');
        $builder->where('slider_id', $id);

        $result = $builder->get();

        if ($result !== false && $result->getNumRows() > 0) {
            return $result->getRow();
        } else {
            // Handle the case where no records are found
            return null;
        }
    }

    public static function get_slide($id)
    {   
        $session = \Config\Services::session();
        
        // Connect to the database
        $db = \Config\Database::connect();
        // print_r($db);die;   
        $query = $db->table('hd_sliders')
            ->where('slide_id', $id)
            ->get()->getRow();

        return $query;
    }


    public static function get_slides($id)
    {
        $session = \Config\Services::session();
        
        // Connect to the database
        $dbName = \Config\Database::connect();

        $builder = new Slider($dbName);
        $builder->select('hd_slider.*, hd_sliders.*');
        $builder->join('hd_sliders', 'hd_slider.slider_id = hd_sliders.slider', 'right');
        $builder->where('slider_id', $id);

        $result = $builder->get();

        if ($result) {
            return $result->getResult();
        } else {
            // Log or handle the database error
            // log_message('error', 'Database error: ' . $db->error());
            return [];
        }
    }
	
	public function listItems($array, $search, $perPage, $page)
    {
        $db = \Config\Database::connect();

        // Begin the selection process using the model's query builder directly
        $this->select('hd_slider.*');

        // If there's a search term, apply it across relevant fields
        if (!empty($search)) {
            $this->groupStart()
                ->like('hd_slider.name', $search)
                ->orLike('hd_slider.status', $search)
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