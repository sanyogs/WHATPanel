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

namespace Modules\plesk\Object;

class Plesk_Object_Customer
{
    const STATUS_ACTIVE = 0;
    const STATUS_SUSPENDED_BY_ADMIN = 16;
    const STATUS_SUSPENDED_BY_RESELLER = 32;
    const TYPE_CLIENT = "hostingaccount";
    const TYPE_RESELLER = "reselleraccount";
    const EXTERNAL_ID_PREFIX = "whmcs_plesk_";
    public static function getCustomerExternalId($params)
    {
        if (isset($params["clientsdetails"]["panelExternalId"]) && "" != $params["clientsdetails"]["panelExternalId"]) {
            return $params["clientsdetails"]["panelExternalId"];
        }
        return $params["clientsdetails"]["uuid"];
    }
}

?>