<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */
/*
 * @ PHP 5.6
 * @ Decoder version : 1.0.0.1
 * @ Release on : 24.03.2018
 * @ Website    : http://EasyToYou.eu
 */

namespace Modules\plesk\libraries;

class Plesk_Config
{
    private static $_settings = NULL;
    private static function _init()
    {
        if (!is_null(static::$_settings)) {
            return NULL;
        }
        static::$_settings = json_decode(json_encode(array_merge(static::getDefaults(), static::_getConfigFileSettings())));
    }
    public static function get()
    {
        self::_init();
        return static::$_settings;
    }
    public static function getDefaults()
    {
        return array("account_limit" => 0);
    }
    private static function _getConfigFileSettings()
    {
        $filename = dirname(dirname(dirname(__FILE__))) . "/config.ini";
        if (!file_exists($filename)) {
            return array();
        }
        $result = parse_ini_file($filename, true);
        return !$result ? array() : $result;
    }
}

?>