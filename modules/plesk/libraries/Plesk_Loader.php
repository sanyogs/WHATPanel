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

use Modules\plesk\libraries\Plesk_Registry;
use Modules\plesk\libraries\Plesk_Translate;
use Modules\plesk\libraries\Manager\Plesk_Manager_V1000;

class Plesk_Loader
{
    public static function init($params)
    {
    //    echo "<pre>";print_r($params);die;
        //spl_autoload_register(array("Plesk_Loader", "autoload"));
        // $port = $params["serveraccesshash"] ? $params["serveraccesshash"] : ($params["serversecure"] ? 8443 : 8880);
        
        list(, $caller) = debug_backtrace(false);
        
        //Plesk_Registry::getInstance()->actionName = $caller["function"];
        // Plesk_Registry::getInstance()->translator = new Plesk_Translate();
        
        Plesk_Registry::getInstance()->api = new Plesk_Api($params->username, $params->password, $params->hostname, $params->port, $params->use_ssl);

        //echo "<pre>";print_r(Plesk_Registry::getInstance());die;
        
        $manager = new Plesk_Manager_V1000();
        echo "<pre>";print_r($manager->getSupportedApiVersions());die;
        foreach ($manager->getSupportedApiVersions() as $version) {
            $managerClassName = "Plesk_Manager_V" . str_replace(".", "", $version);
            if (class_exists($managerClassName)) {
                Plesk_Registry::getInstance()->manager = new $managerClassName();
                break;
            }
        }
        if (!isset(Plesk_Registry::getInstance()->manager)) {
            throw new \Exception("ERROR_NO_APPROPRIATE_MANAGER");
        }
    }
    public static function autoload($className)
    {
        $filePath = dirname(__FILE__) . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . str_replace("_", DIRECTORY_SEPARATOR, $className) . ".php";
        if (file_exists($filePath)) {
            require_once $filePath;
        }
    }
}

?>