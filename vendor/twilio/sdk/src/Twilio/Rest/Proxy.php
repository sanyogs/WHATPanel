<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Application Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

namespace Twilio\Rest;

use Twilio\Rest\Proxy\V1;

class Proxy extends ProxyBase {

    /**
     * @deprecated Use v1->services instead.
     */
    protected function getServices(): \Twilio\Rest\Proxy\V1\ServiceList {
        echo "services is deprecated. Use v1->services instead.";
        return $this->v1->services;
    }

    /**
     * @deprecated Use v1->services(\$sid) instead.
     * @param string $sid The unique string that identifies the resource
     */
    protected function contextServices(string $sid): \Twilio\Rest\Proxy\V1\ServiceContext {
        echo "services(\$sid) is deprecated. Use v1->services(\$sid) instead.";
        return $this->v1->services($sid);
    }
}