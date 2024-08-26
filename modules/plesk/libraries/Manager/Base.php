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

 namespace Modules\plesk\libraries\Manager;
 use Modules\plesk\libraries\Plesk_Registry;
 use Modules\plesk\libraries\Plesk_Api;

abstract class Base
{
    public function __call($name, $args)
    {
        $methodName = "_" . $name;
        if (!method_exists($this, $methodName)) {
            throw new \Exception($this->getVersion());
        }
        $reflection = new \ReflectionClass(get_class($this));
        $declaringClassName = $reflection->getMethod($methodName)->getDeclaringClass()->name;
        $declaringClass = new $declaringClassName();
        $version = $declaringClass->getVersion();
        $currentApiVersion = isset(Plesk_Registry::getInstance()->version) ? Plesk_Registry::getInstance()->version : NULL;
        Plesk_Registry::getInstance()->version = $version;
        $result = call_user_func_array(array($this, $methodName), $args);
        Plesk_Registry::getInstance()->version = $currentApiVersion;
        return $result;
    }
    public function getVersion()
    {
        $className = get_class($this);
        return implode(".", str_split(substr($className, strrpos($className, "V") + 1)));
    }
    public function createTableForAccountStorage()
    {
        if (Illuminate\Database\Capsule\Manager::schema()->hasTable("mod_pleskaccounts")) {
            return NULL;
        }
        Illuminate\Database\Capsule\Manager::schema()->create("mod_pleskaccounts", function ($table) {
            $table->engine = "MyISAM";
            $table->integer("userid");
            $table->string("usertype");
            $table->string("panelexternalid");
            $table->primary("userid");
            $table->index("usertype");
            $table->unique("panelexternalid");
        });
    }
    protected function _checkErrors($result)
    {
        if (Plesk_Api::STATUS_OK == (string) $result->status) {
            return NULL;
        }
        switch ((int) $result->errcode) {
            case Plesk_Api::ERROR_AUTHENTICATION_FAILED:
                $errorMessage = Plesk_Registry::getInstance()->translator->translate("ERROR_AUTHENTICATION_FAILED");
                break;
            case Plesk_Api::ERROR_AGENT_INITIALIZATION_FAILED:
                $errorMessage = Plesk_Registry::getInstance()->translator->translate("ERROR_AGENT_INITIALIZATION_FAILED");
                break;
            default:
                $errorMessage = (string) $result->errtext;
                break;
        }
        throw new Exception($errorMessage, (int) $result->errcode);
    }
}

?>