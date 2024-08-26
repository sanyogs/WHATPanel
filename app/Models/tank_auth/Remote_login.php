<?php
 /*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */


namespace App\Models\tank_auth;

use CodeIgniter\Model;
use Config\MyConfig;
/**
 * User_Autologin
 *
 * This model represents user autologin data. It can be used
 * for user verification when user claims his autologin passport.
 *
 * @package	Tank_auth
 * @author	Ilya Konyukhov (http://konyukhov.com/soft/)
 */
class Remote_login extends Model
{	
	protected $table = 'hd_user_autologin';
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

    protected $dbname;


	function __construct()
	{
		//parent::__construct();

		// $this->table_name = $ci->config->item('db_table_prefix', 'tank_auth') . $this->table_name;
		// $this->users_table_name = $ci->config->item('db_table_prefix', 'tank_auth') . $this->users_table_name;
	}

	/**
	 * Get user data for auto-logged in user.
	 * Return NULL if given key or user ID is invalid.
	 *
	 * @param	int
	 * @param	string
	 * @return	object
	 */
	function get($key)
	{
		$this->db->select($this->users_table_name . '.id');
		$this->db->select($this->users_table_name . '.username');
		$this->db->select($this->users_table_name . '.role_id');
		$this->db->select($this->table_name . '.expires');
		$this->db->from($this->users_table_name);
		$this->db->join($this->table_name, $this->table_name . '.user_id = ' . $this->users_table_name . '.id');
		$this->db->where($this->table_name . '.key_id', $key);
		$query = $this->db->get();
		if ($query->num_rows() == 1)
			return $query->row();
		return NULL;
	}

	/**
	 * Save data for user's autologin
	 *
	 * @param	int
	 * @param	string
	 * @return	bool
	 */
	function setUser($user_id, $key, $expires)
	{
		$db = \Config\Database::connect();

		$data = array(
			'user_id' => $user_id,
			'key_id' => $key,
			'expires' => $expires,
			'remote' => 1,
		);
		
		return $db->table($this->table)->insert($data);
	}

	/**
	 * Delete user's autologin data
	 *
	 * @param	int
	 * @param	string
	 * @return	void
	 */
	function deleteUser($user_id, $key)
	{
		$db = \Config\Database::connect();

		$db->table($this->table)->where('user_id', $user_id)->where('key_id', $key)->delete();
	}

	/**
	 * Delete all autologin data for given user
	 *
	 * @param	int
	 * @return	void
	 */
	function clear($user_id)
	{
		$this->db->where('user_id', $user_id);
		$this->db->delete($this->table_name);
	}

	/**
	 * Purge autologin data for given user and login conditions
	 *
	 * @param	int
	 * @return	void
	 */
	function purge($user_id)
	{
		$currentTime = date("Y-m-d H:i:s", time());

		$db = \Config\Database::connect();
        
        // Build the query to delete expired remote logins for the given user
        $db->table('hd_user_autologin')->where('user_id', $user_id)
             //->where('expiration <', $currentTime)
             ->where('remote', 1)
             ->delete();
	}
}

/* End of file user_autologin.php */
/* Location: ./application/models/auth/user_autologin.php */