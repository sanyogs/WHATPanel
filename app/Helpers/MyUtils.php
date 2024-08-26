<?php
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */


namespace App\Modules\settings\controllers;

namespace App\Helpers;
use CodeIgniter\Database\BaseUtils;
use CodeIgniter\Database\Exceptions\DatabaseException;


class MyUtils extends BaseUtils
{
    /**
     * Platform dependent version of the backup function.
     *
     * @return false|never|string
     */
    public function _backup(?array $prefs = null)
    {

    }
}