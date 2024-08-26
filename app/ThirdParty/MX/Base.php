<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */



namespace App\ThirdParty\MX;

use CodeIgniter\Controller;
use Config\Services;
use App\Helpers\custom_name_helper;

use App\ThirdParty\MX\Lang as MXLang; // Corrected class name
use App\ThirdParty\MX\Config as MXConfig; // Corrected class name



class Base extends Controller
{
    public static $APP;

    public function __construct()
    {	
        // Assign the application instance
        self::$APP = $this;

        // Reassign language and config for modules
        $LANG = Services::language();
		$custom = new custom_name_helper();
        $CFG = $custom->getconfig_item('Config');

        if (!$LANG instanceof MXLang) {
            $LANG = new MXLang();
        }

        if (!$CFG instanceof Config) {
            $CFG = new MXConfig();
        }
    }
}

// Create the application object
new Base();
