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
use stdClass;
use App\Helpers\whatpanel_helper;

class Page extends Model
{
    protected $table = 'hd_posts';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'title',
        'slug',
        'body',
        'status',
        'menu',
        'sidebar_right',
        'sidebar_left',
        'post_type',
        'pubdate',
        'user_id',
        'order',
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

    public function getNew()
    {
        return [
            'title' => '',
            'slug' => '',
            'body' => '',
            'category_id' => '',
            'parent_id' => '',
            'option' => '',
            'meta_title' => '',
            'meta_desc' => '',
            'knowledge' => '',
            'faq' => '',
            'knowledge_id' => '',
            'faq_id' => '',
            'views' => '',
            'video' => '',
            'status' => 1,
            'menu' => 0,
            'sidebar_right' => 0,
            'sidebar_left' => 0,
            'post_type' => 'page',
            'pubdate' => date('Y-m-d'),
            'user_id' => session()->get('user_id'),
            'order' => '0',
        ];
    }
	
	function rules()
	{
		$rules = [
			'title' => [
				'field' => 'title',
				'label' => 'Title',
				'rules' => 'required'
			],
			'slug' => [
				'field' => 'slug',
				'label' => 'Slug',
				'rules' => 'required|max_length[100]'
			], 
			'body' => [
				'field' => 'body',
				'label' => 'Body',
				'rules' => 'required'
			],
			'meta_title' => [
				'field' => 'meta_title',
				'label' => 'Page Title',
				'rules' => 'max_length[100]'
			],
			'meta_desc' => [
				'field' => 'meta_desc',
				'label' => 'Page Description',
				'rules' => 'max_length[200]'
			],
			'video' => [
				'field' => 'video',
				'label' => 'Video URL',
				'rules' => 'max_length[200]'
			]
		];
		return $rules;
	}
	
	 public function listItems($array, $search, $perPage, $page)
    {
        $db = \Config\Database::connect();

        // Begin the selection process using the model's query builder directly
        $this->select('hd_posts.*');

        // If there's a search term, apply it across relevant fields
        if (!empty($search)) {
            $this->groupStart()
                ->like('hd_posts.title', $search)
                ->orLike('hd_posts.type', $search)
                ->orLike('hd_posts.slug', $search)
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

    public function get($id = null, $single = false, $published = false)
    {	
        $db = \Config\Database::connect();

        $query = $db->table('hd_posts')
            ->select('*')
            ->where('post_type', 'page');
			if ($published !== false) {
				$this->setPublished($query);
			}

			if(isset($id)) {
				$query->where('hd_posts.id', $id);
			}
			
			if ($single) {
				return $query->get()->getRow();
			} else {
				return $query->get()->getResult();
			}
    }

	
    public function get_by_slug($slug = null)
    {	
		$helper = new whatpanel_helper();
        $slug = $helper->get_slug();
		$db = \Config\Database::connect();
		$query = $db->table('hd_posts')->where('post_type', 'page')->where('slug', $slug)->get()->getRow();
		return $query;
    }

    public function get_by($where = null, $single = false, $like = null)
    {
        if ($where !== null) {
            $this->where($where);
        }
        if ($like !== null) {
            $this->like($like);
        }
        return $this->get(null, $single, true);
    }

    public function get_by_id($id = null, $single = false, $published = false)
    {
        if ($published !== false) {
            $this->setPublished();
        }
        return $this->get($id, $single);
    }

    public function setPublished($builder)
    {	
		$db = \Config\Database::connect();
		$builder->where('pubdate <=', date('Y-m-d'))
            ->where('status', 1);
    }

    public function getPages($published = false)
    {
        $this->where('post_type', 'page');
        $this->select('title, slug');
        if ($published !== false) {
            $this->setPublished();
        }
        return parent::findAll();
    }


    public function get_new()
    {
        $page = new stdClass();
        $page->title = '';
        $page->slug = '';
        $page->body = '';
        $page->status = 1;
        $page->menu = 0;
        $page->sidebar_right = 0;
        $page->sidebar_left = 0;
        $page->post_type = 'page';
        $page->pubdate = date('Y-m-d');
        $page->user_id = session('user_id'); // Replace $this->session->userdata('user_id') with session('user_id')
        $page->order = '0';
        return $page;

    }
	
	
	public function get_pages($published = FALSE)
	{   
        $builder = $this->db->table('hd_posts');

        $builder->where('post_type', 'page');
        $builder->select('title, slug');

        if ($published != false) {
            $this->setPublished();
        }

        $this->primaryKey = 'id';
        return $builder->get()->getResult();
	}
	
	public function array_from_post($fields){
		$request = \Config\Services::request();
		$data = array();
		foreach ($fields as $field) {
			$data[$field] = $request->getPost($field);
		}
		return $data;
	}


}