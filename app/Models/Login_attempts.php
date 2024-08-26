<?php
 /*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */


// app/Models/LoginAttemptsModel.php
namespace App\Models;

use CodeIgniter\Model;

class Login_attempts extends Model
{
	protected $DBGroup;
	protected $table = 'login_attempts'; // Adjust this based on your actual table name
	protected $primaryKey = 'id'; // Adjust this based on your actual primary key field

	protected $allowedFields = ['ip_address', 'login', 'time'];

	public function __construct()
    {
        
    }

	public function getAttemptsNum($ipAddress, $login)
	{
		$result = $this->selectCount('id')
			->where('ip_address', $ipAddress)
			->orWhere('login', $login)
			->get();

		if ($result !== false) {
			$row = $result->getRow();
			return ($row !== null) ? $row->id : 0;
		} else {
			// Handle the error or return a default value
			return 0;
		}
	}


	public function increaseAttempt($ipAddress, $login)
	{
		$this->insert(['ip_address' => $ipAddress, 'login' => $login]);
	}

	public function clearAttempts($ipAddress, $login, $expirePeriod = 86400)
	{
		$this->where(['ip_address' => $ipAddress, 'login' => $login])
			->orWhere('UNIX_TIMESTAMP(time) <', time() - $expirePeriod)->delete();
	}
}