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

class KB extends Model
{
    protected $table = 'hd_kbs';
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

    static function pages()
    {
        self::$db->select('posts.*, cat_name');
        self::$db->from('posts');
        self::$db->join('categories', 'categories.id = posts.knowledge_id');
        self::$db->where('status', 1);
        return self::$db->get()->result();
    }

    static function page($slug)
    {
        self::$db->select('posts.*, cat_name');
        self::$db->from('posts');
        self::$db->join('categories', 'categories.id = posts.knowledge_id');
        self::$db->where('slug', $slug);
        $article = self::$db->get()->row();
        self::counter($article);
        return $article;
    }

    // public function latest()
    // {
    //     return $this->select('posts.title, cat_name, views, modified, slug')
    //         ->from('posts')
    //         ->join('categories', 'categories.id = posts.knowledge_id')
    //         ->orderBy('created', 'desc')
    //         ->limit(10)
    //         ->get()
    //         ->getResult();
    // }

    // public function popular()
    // {
    //     return $this->select('posts.title, cat_name, views, modified, slug')
    //         ->from('posts')
    //         ->join('categories', 'categories.id = posts.knowledge_id')
    //         ->where('views >', 0)
    //         ->orderBy('views', 'desc')
    //         ->limit(10)
    //         ->get()
    //         ->getResult();
    // }

    static function search($text)
    {
        self::$db->select('title, slug');
        self::$db->from('posts');
        self::$db->where('knowledge', 1);
        self::$db->like('title', $text);
        return self::$db->get()->result();
    }

    public function counter($article)
    {
        $data = ['views' => $article->views + 1];
        $this->update(['id' => $article->id], $data);
    }

    static function category($name)
    {
        self::$db->select('posts.title, cat_name, views, modified, slug');
        self::$db->from('posts');
        self::$db->join('categories', 'categories.id = posts.knowledge_id');
        self::$db->where('cat_name', $name);
        return self::$db->get()->result();
    }

    // public function categories()
    // {
    //     return $this->select('count(distinct hd_posts.id) AS num, cat_name')
    //         ->from('posts')
    //         ->join('categories', 'categories.id = posts.knowledge_id')
    //         ->where('status', 1)
    //         ->groupBy('cat_name')
    //         ->get()
    //         ->getResult();
    // }

    static function categories()
    {
        self::$db->select('count(distinct hd_posts.id) AS num, cat_name');
        self::$db->from('posts');
        self::$db->join('categories', 'categories.id = posts.knowledge_id');
        self::$db->where('status', 1);
        self::$db->group_by('cat_name');
        return self::$db->get()->result();
    }

    static function popular()
    {
        self::$db->select('posts.title, cat_name, views, modified, slug');
        self::$db->from('posts');
        self::$db->join('categories', 'categories.id = posts.knowledge_id');
        self::$db->where('views >', 0);
        self::$db->order_by('views', 'desc');
        self::$db->limit(10);
        return self::$db->get()->result();
    }

    static function latest()
    {
        self::$db->select('posts.title, cat_name, views, modified, slug');
        self::$db->from('posts');
        self::$db->join('categories', 'categories.id = posts.knowledge_id');
        self::$db->order_by('created', 'desc');
        self::$db->limit(10);
        return self::$db->get()->result();
    }
}