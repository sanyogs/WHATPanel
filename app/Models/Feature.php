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

class Feature extends Model
{
	protected $table = 'hd_features';
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
	/**
	 * Get all requests with comments count
	 */
	public static function requests()
	{
		self::$db->select('tickets.*, count(distinct hd_ticketreplies.id) as comments');
		self::$db->join('ticketreplies', 'tickets.id = ticketreplies.ticketid', 'left');
		self::$db->from('tickets');
		self::$db->where('department', 6);
		self::$db->group_by('tickets.id');
		return self::$db->get()->result();
	}

	/**
	 * Search for tickets with a given text
	 */
	public static function search($text)
	{
		self::$db->select('subject');
		self::$db->from('tickets');
		self::$db->where('department', 6);
		self::$db->like('subject', $text);
		return self::$db->get()->result();
	}


	public static function request($slug)
	{
		self::$db->select('tickets.*');
		self::$db->from('tickets');
		self::$db->where('ticket_code', explode('_', $slug)[0]);
		$article = self::$db->get()->row();
		return $article;
	}


	public static function replies($slug)
	{
		self::$db->select('ticketreplies.*');
		self::$db->join('tickets', 'tickets.id = ticketreplies.ticketid');
		self::$db->from('ticketreplies');
		self::$db->where('department', 6);
		self::$db->where('ticket_code', explode('_', $slug)[0]);
		return self::$db->get()->result();
	}


	public static function category($name)
	{
		self::$db->select('tickets.*, count(distinct hd_ticketreplies.id) as comments');
		self::$db->join('ticketreplies', 'tickets.id = ticketreplies.ticketid', 'left');
		self::$db->from('tickets');
		self::$db->where('department', 6);
		self::$db->where('type', $name);
		self::$db->group_by('tickets.id');
		return self::$db->get()->result();
	}


	public static function categories()
	{
		self::$db->select('count(distinct id) AS num, type as cat_name');
		self::$db->from('tickets');
		self::$db->where('department', 6);
		self::$db->group_by('cat_name');
		return self::$db->get()->result();
	}
}