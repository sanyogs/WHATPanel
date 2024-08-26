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

class Plesk_Manager_V1660 extends Plesk_Manager_V1640
{
    protected function _getAddAccountParams($params)
    {
        $result = parent::_getAddAccountParams($params);
        $result["powerUser"] = "on" === $params["configoption4"] ? "true" : "false";
        return $result;
    }
    protected function _addAccount($params)
    {
        return parent::_addAccount($params);
    }
}

?>