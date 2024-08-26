<?php
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */


namespace App\Helpers;

use Config\Services;

class AuthHelper
{
    public static function is_admin()
    {
        $authentication = Services::authentication();

        if ($authentication) {
            // Assuming you have methods like userRole and getRoleId in your authentication service
            $roleId = $authentication->getRoleId();

            return ($authentication->userRole($roleId) == 'admin');
        }

        return false;
    }

    public static function is_staff()
    {
        $authentication = Services::authentication();

        if ($authentication) {
            // Assuming you have methods like userRole and getRoleId in your authentication service
            $roleId = $authentication->getRoleId();

            return ($authentication->userRole($roleId) == 'staff');
        }

        return false;
    }

    public static function is_client()
    {
        $authentication = Services::authentication();

        if ($authentication) {
            // Assuming you have methods like userRole and getRoleId in your authentication service
            $roleId = $authentication->getRoleId();

            return ($authentication->userRole($roleId) == 'client');
        }

        return false;
    }

    public function nv()
	{
		$lc = '/wpline/';
		return $lc;
	}
}
