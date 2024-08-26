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

class FAQS extends Model
{
    protected $table = 'hd_faqs';
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

    public static function articles()
    {
        self::$db->select('posts.*');
        self::$db->from('posts');
        self::$db->join('categories', 'categories.id = posts.faq_id');
        self::$db->where('faq', 1);
        self::$db->where('status', 1);
        return self::$db->get()->result();
    }

    public static function category($name)
    {
        self::$db->select('posts.*');
        self::$db->from('posts');
        self::$db->join('categories', 'categories.id = posts.faq_id');
        self::$db->where('posts.faq_id >', 0);
        self::$db->where('cat_name', $name);
        return self::$db->get()->result();
    }

    public static function categories()
    {
        self::$db->select('count(distinct hd_posts.id) AS num, cat_name');
        self::$db->from('posts');
        self::$db->join('categories', 'categories.id = posts.faq_id');
        self::$db->where('posts.faq_id >', 0);
        self::$db->where('status', 1);
        self::$db->group_by('cat_name');
        return self::$db->get()->result();
    }
}