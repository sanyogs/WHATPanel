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

use Twilio\Rest\Serverless\V1;

class Serverless extends ServerlessBase {
    /**
     * @deprecated Use v1->services instead.
     */
    protected function getServices(): \Twilio\Rest\Serverless\V1\ServiceList {
        echo "services is deprecated. Use v1->services instead.";
        return $this->v1->services;
    }

    /**
     * @deprecated Use v1->services(\$sid) instead.
     * @param string $sid The SID of the Service resource to fetch
     */
    protected function contextServices(string $sid): \Twilio\Rest\Serverless\V1\ServiceContext {
        echo "services(\$sid) is deprecated. Use v1->services(\$sid) instead.";
        return $this->v1->services($sid);
    }
}