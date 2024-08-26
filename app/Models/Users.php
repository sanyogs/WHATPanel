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

/**
 * Users
 *
 * This model represents user authentication data. It operates the following tables:
 * - user account data,
 * - user profiles
 *
 * @package	Tank_auth
 * @author	Ilya Konyukhov (http://konyukhov.com/soft/)
 */
class Users extends Model
{
	private $table_name = 'users';			// user accounts
	private $profile_table_name = 'account_details';	// user profiles

	/**
	 * Get user record by Id
	 *
	 * @param	int
	 * @param	bool
	 * @return	object
	 */


	protected $DBGroup;

	public function __construct($db = null)
	{
		parent::__construct($db);

		if ($db !== null) {
			$this->DBGroup = $db->getDatabase();
		}

		$this->db = \Config\Database::connect();
	}

	function get_user_by_id($user_id, $activated)
	{	
		$db = \Config\Database::connect();
		$builder = $db->table('hd_users')->where('id', $user_id)->where('activated', $activated ? 1 : 0);
		
		$query = $builder->get();
		return $query->getNumRows() == 1 ? $query->getRow() : null;
	}

	/**
	 * Get user record by login (username or email)
	 *
	 * @param	string
	 * @return	object
	 */
	function get_user_by_login($login)
	{
		$builder = $this->db($this->table);
		$builder->select('*');
		$builder->where('LOWER(username)', strtolower($login));
		$builder->orwhere('LOWER(email)', strtolower($login));

		$query = $builder->get();

		if ($query->getNumRows() == 1) {
			return $query->getRow();
		}

		return null;
	}

	/**
	 * Get user record by username
	 *
	 * @param	string
	 * @return	object
	 */
	function get_user_by_username($username)
	{
		$builder = $this->db->table($this->table);
		$builder->where('LOWER(username)', strtolower($username));

		$query = $builder->get();

		return $query->getRow(); // This will return null if no user is found
	}

	/**
	 * Get user record by email
	 *
	 * @param	string
	 * @return	object
	 */
	function get_user_by_email($email)
	{
		// $builder = $this->db->table($this->table);
		// $builder->where('LOWER(email)', strtolower($email));

		// $query = $builder->get();

		// if ($query->getNumRows() == 1) {
		// 	return $query->getRow();
		// }

		// return null;

		// return $this->DBGroup->select('*')->from('hd_users')->where('LOWER(email)', strtolower($email))->get();
		$builder = $this->db->table('hd_users');  // Assuming $this->db is an instance of the database
		$result = $builder->select('*')
			->where('LOWER(email)', strtolower($email))
			->get();
		// echo $result;
		// die;
		return $result->getRow();

	}

	/**
	 * Check if username available for registering
	 *
	 * @param	string
	 * @return	bool
	 */
	function is_username_available($username)
	{
		$builder = $this->db->table($this->table);
		$builder->select('1', false);
		$builder->where('LOWER(username)', strtolower($username));

		$query = $builder->get();

		return $query->getNumRows() === 0;
	}

	/**
	 * Check if email available for registering
	 *
	 * @param	string
	 * @return	bool
	 */
	function is_email_available($email)
	{
		$builder = $this->db->table($this->table);
		$builder->select('1', false);
		$builder->where('LOWER(email)', strtolower($email));
		$builder->orWhere('LOWER(new_email)', strtolower($email));

		$query = $builder->get();
		return $query->getNumRows() === 0;
	}

	/**
	 * Create new user record
	 *
	 * @param	array
	 * @param	bool
	 * @return	array
	 */
	function create_user($data, $profile, $activated = TRUE, $company)
	{
		$data['created'] = date('Y-m-d H:i:s');
		$data['activated'] = $activated ? 1 : 0;

		$builder = $this->db->table($this->table);

		if ($builder->insert($data)) {
			$userId = $this->db->insertID();

			if ($activated) {
				$this->createProfile($userId, $profile);
			}

			return ['user_id' => $userId];
		}

		return null;
	}

	/**
	 * Activate user if activation key is valid.
	 * Can be called for not activated users only.
	 *
	 * @param	int
	 * @param	string
	 * @param	bool
	 * @return	bool
	 */
	function activate_user($user_id, $activation_key, $activate_by_email)
	{
		$builder = $this->db->table($this->table);

		$builder->select('1', false);
		$builder->where('id', $user_id);

		if ($activate_by_email) {
			$builder->where('new_email_key', $activation_key);
		} else {
			$builder->where('new_password_key', $activation_key);
		}

		$builder->where('activated', 0);

		$query = $builder->get();

		if ($query->getNumRows() == 1) {
			$builder->set('activated', 1);
			$builder->set('new_email_key', null);
			$builder->where('id', $user_id);
			$builder->update();

			$this->createProfile($user_id, null);
			return true;
		}

		return false;
	}

	/**
	 * Purge table of non-activated users
	 *
	 * @param	int
	 * @return	void
	 */
	function purge_na($expire_period = 172800)
	{
		$builder = $this->db->table($this->table);

		$builder->where('activated', 0);
		$builder->where('UNIX_TIMESTAMP(created) <', time() - $expire_period);
		$builder->delete();
	}

	/**
	 * Delete user record
	 *
	 * @param	int
	 * @return	bool
	 */
	function delete_user($user_id)
	{
		$builder = $this->db->table($this->table);

		$builder->where('id', $user_id);
		$builder->delete();

		if ($this->db->affectedRows() > 0) {
			$this->deleteProfile($user_id);
			return true;
		}

		return false;
	}

	/**
	 * Set new password key for user.
	 * This key can be used for authentication when resetting user's password.
	 *
	 * @param	int
	 * @param	string
	 * @return	bool
	 */
	function set_password_key($user_id, $new_pass_key)
	{
		$builder = $this->db->table($this->table);

		$builder->set('new_password_key', $new_pass_key);
		$builder->set('new_password_requested', date('Y-m-d H:i:s'));
		$builder->where('id', $user_id);

		$builder->update();

		return $this->db->affectedRows() > 0;
	}

	/**
	 * Check if given password key is valid and user is authenticated.
	 *
	 * @param	int
	 * @param	string
	 * @param	int
	 * @return	void
	 */
	function can_reset_password($user_id, $new_pass_key, $expire_period = 900)
	{

		$builder = $this->db->table($this->table);

		$builder->select('1', false);
		$builder->where('id', $user_id);
		$builder->where('new_password_key', $new_pass_key);
		$builder->where('UNIX_TIMESTAMP(new_password_requested) >', time() - $expire_period);

		$query = $builder->get();

		return $query->getNumRows() == 1;
	}

	/**
	 * Change user password if password key is valid and user is authenticated.
	 *
	 * @param	int
	 * @param	string
	 * @param	string
	 * @param	int
	 * @return	bool
	 */
	function reset_password($user_id, $new_pass, $new_pass_key, $expire_period = 900)
	{
		$builder = $this->db->table($this->table);

		$builder->set('password', $new_pass);
		$builder->set('new_password_key', null);
		$builder->set('new_password_requested', null);
		$builder->where('id', $user_id);
		$builder->where('new_password_key', $new_pass_key);
		$builder->where('UNIX_TIMESTAMP(new_password_requested) >=', time() - $expire_period);

		$builder->update();

		return $this->db->affectedRows() > 0;
	}

	/**
	 * Change user password
	 *
	 * @param	int
	 * @param	string
	 * @return	bool
	 */
	function change_password($user_id, $new_pass)
	{
		$db = \Config\Database::connect();

		$builder = $db->table('hd_users');

		$builder->where('id', $user_id);

		$builder->update(['password' => $new_pass]);

		return $db->affectedRows() > 0;
	}

	/**
	 * Set new email for user (may be activated or not).
	 * The new email cannot be used for login or notification before it is activated.
	 *
	 * @param	int
	 * @param	string
	 * @param	string
	 * @param	bool
	 * @return	bool
	 */
	function set_new_email($user_id, $new_email, $new_email_key = null, $activated = null)
	{
		$db = \Config\Database::connect();

		$builder = $db->table('hd_users');

		$data = [
			$activated ? 'new_email' : 'email' => $new_email,
			'new_email_key' => $new_email_key,
		];
		
		$builder->where('id', $user_id);
		$builder->where('activated', $activated ? 1 : 0);

		$builder->update($data);

		return $db->affectedRows() > 0;
	}

	/**
	 * Activate new email (replace old email with new one) if activation key is valid.
	 *
	 * @param	int
	 * @param	string
	 * @return	bool
	 */
	function activate_new_email($user_id, $new_email_key)
	{
		$builder = $this->db->table($this->table);

		$data = [
			'email' => 'new_email',
			'new_email' => null,
			'new_email_key' => null,
		];

		$builder->set($data);
		$builder->where('id', $user_id);
		$builder->where('new_email_key', $new_email_key);

		$builder->update();

		return $this->db->affectedRows() > 0;
	}

	/**
	 * Update user login info, such as IP-address or login time, and
	 * clear previously generated (but not activated) passwords.
	 *
	 * @param	int
	 * @param	bool
	 * @param	bool
	 * @return	void
	 */
	function update_login_info($user_id, $record_ip, $record_time)
	{
		$builder = $this->db->table($this->table);

		$data = [
			'new_password_key' => null,
			'new_password_requested' => null,
		];

		if ($record_ip) {
			$data['last_ip'] = $this->request->getIPAddress();
		}

		if ($record_time) {
			$data['last_login'] = date('Y-m-d H:i:s');
		}

		$builder->set($data);
		$builder->where('id', $user_id);

		$builder->update();
	}

	/**
	 * Ban user
	 *
	 * @param	int
	 * @param	string
	 * @return	void
	 */
	function ban_user($user_id, $reason = NULL)
	{
		$builder = $this->db->table($this->table);

		$data = [
			'banned' => 1,
			'ban_reason' => $reason,
		];

		$builder->where('id', $user_id);
		$builder->update($data);
	}

	/**
	 * Unban user
	 *
	 * @param	int
	 * @return	void
	 */
	function unban_user($user_id)
	{
		$builder = $this->db->table($this->table);

		$data = [
			'banned' => 0,
			'ban_reason' => null,
		];

		$builder->where('id', $user_id);
		$builder->update($data);
	}

	/**
	 * Create an empty profile for a new user
	 *
	 * @param	int
	 * @return	bool
	 */
	private function create_profile($user_id, $profile)
	{
		$profile['user_id'] = $user_id;

		$builder = $this->db->table($this->profile_table);
		$builder->insert($profile);

		return $this->db->affectedRows() > 0;
	}


	/**
	 * Delete user profile
	 *
	 * @param	int
	 * @return	void
	 */
	private function delete_profile($user_id)
	{
		$builder = $this->db->table($this->profile_table);
		$builder->where('user_id', $user_id);
		$builder->delete();

		return $this->db->affectedRows() > 0;
	}
}

/* End of file users.php */
/* Location: ./application/models/auth/users.php */