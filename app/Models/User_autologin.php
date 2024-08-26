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

/**
 * User_Autologin
 *
 * This model represents user autologin data. It can be used
 * for user verification when user claims his autologin passport.
 *
 * @package	Tank_auth
 * @author	Ilya Konyukhov (http://konyukhov.com/soft/)
 */
class User_autologin extends Model
{
	private $table_name = 'hd_user_autologin';
	private $users_table_name = 'hd_users';

	function __construct($db)
	{
		parent::__construct($db);

		$session = \Config\Services::session();
		

		
		// Modify the 'default' property
		
		// Connect to the database
		$dbName = \Config\Database::connect();

	}

	/**
	 * Get user data for auto-logged in user.
	 * Return NULL if given key or user ID is invalid.
	 *
	 * @param	int
	 * @param	string
	 * @return	object
	 */
	function get($user_id, $key)
	{
		$builder = $this->db->table($this->users_table_name);
		$builder->select([
			$this->users_table_name . '.id',
			$this->users_table_name . '.username',
			$this->users_table_name . '.role_id'
		]);

		$builder->join($this->table_name, $this->table_name . '.user_id = ' . $this->users_table_name . '.id');
		$builder->where($this->table_name . '.user_id', $user_id);
		$builder->where($this->table_name . '.key_id', $key);

		$query = $builder->get();

		if ($query->getNumRows() == 1) {
			return $query->getRow();
		}

		return null;
	}

	/**
	 * Save data for user's autologin
	 *
	 * @param	int
	 * @param	string
	 * @return	bool
	 */
	public function set($key, $value = '', bool $escape = null)
	{
		$data = [
			'user_id' => $value['user_id'],
			'key_id' => $value['key'],
			'user_agent' => substr($this->request->getUserAgent(), 0, 149),
			'last_ip' => $this->request->getIPAddress(),
		];

		return $this->db->table($this->table_name)->insert($data);
	}



	/**
	 * Delete user's autologin data
	 *
	 * @param	int
	 * @param	string
	 * @return	void
	 */
	public function delete($id = null, bool $purge = false)
	{
		$this->db->where('user_id', $id['user_id']);
		$this->db->where('key_id', $id['key']);
		$this->db->delete($this->table_name);
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
		$session = Services::session();

		
		$config = new Database();
		

		// Connect to the database
		$db = Database::connect($config->default);

		// Use the database connection for the model
		$model = new self($db);

		// $builder = $model->builder();
		// $builder->from($this->table_name); // Set the table using from() method
		// $builder->where('user_id', $user_id);
		// $builder->where('user_agent', substr(Services::request()->getUserAgent(), 0, 149));
		// $builder->where('last_ip', Services::request()->getIPAddress());
		// $builder->delete();

		$menus = $model
			->where('user_id', $user_id)
			->where('user_agent', substr(Services::request()->getUserAgent(), 0, 149))
			->where('last_ip', Services::request()->getIPAddress())
			->delete();
	}



}

/* End of file user_autologin.php */
/* Location: ./application/models/auth/user_autologin.php */