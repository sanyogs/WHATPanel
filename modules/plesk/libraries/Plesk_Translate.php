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
 
class Plesk_Translate
{
    private $_keys = array();
    public function __construct()
    {
        $dir = base_url() . 'app/Language/en/';
        $englishFile = $dir . "hd_lang.php";
        $currentFile = $dir . "/" . $this->_getLanguage() . ".php";
        if (file_exists($englishFile)) {
            require $englishFile;
            $this->_keys = $keys;
        }
        if (file_exists($currentFile)) {
            require $currentFile;
            $this->_keys = array_merge($this->_keys, $keys);
        }
    }
    public function translate($msg, $placeholders = array())
    {
        if (isset($this->_keys[$msg])) {
            $msg = $this->_keys[$msg];
            foreach ($placeholders as $key => $val) {
                $msg = str_replace("@" . $key . "@", $val, $msg);
            }
        }
        return $msg;
    }
    private function _getLanguage()
    {
        $language = "english";
        if (isset($GLOBALS["CONFIG"]["Language"])) {
            $language = $GLOBALS["CONFIG"]["Language"];
        }
        if (isset($_SESSION["adminid"])) {
            $language = $this->_getUserLanguage("tbladmins", "adminid");
        } else {
            if ($_SESSION["uid"]) {
                $language = $this->_getUserLanguage("tblclients", "uid");
            }
        }
        return strtolower($language);
    }
    private function _getUserLanguage($table, $field)
    {
        $language = Illuminate\Database\Capsule\Manager::table($table)->where("id", $_SESSION[$field])->first();
        return is_null($language) ? "" : $language->language;
    }
}

?>