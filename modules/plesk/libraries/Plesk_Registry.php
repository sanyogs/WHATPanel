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

class Plesk_Registry
{
    private static $_instance = NULL;
    private $_instances = array();
    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    public function __get($name)
    {
        if (isset($this->_instances[$name])) {
            return $this->_instances[$name];
        }
        throw new \Exception("There is no object \"" . $name . "\" in the registry.");
    }
    public function __set($name, $value)
    {
        $this->_instances[$name] = $value;
    }
    public function __isset($name)
    {
        return isset($this->_instances[$name]) ? true : false;
    }
}

?>