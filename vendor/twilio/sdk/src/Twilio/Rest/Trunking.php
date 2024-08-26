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

use Twilio\Rest\Trunking\V1;
class Trunking extends TrunkingBase {

    /**
     * @deprecated Use v1->trunks instead.
     */
    protected function getTrunks(): \Twilio\Rest\Trunking\V1\TrunkList {
        echo "trunks is deprecated. Use v1->trunks instead.";
        return $this->v1->trunks;
    }

    /**
     * @deprecated Use v1->trunks(\$sid) instead.
     * @param string $sid The unique string that identifies the resource
     */
    protected function contextTrunks(string $sid): \Twilio\Rest\Trunking\V1\TrunkContext {
        echo "trunks(\$sid) is deprecated. Use v1->trunks(\$sid) instead.";
        return $this->v1->trunks($sid);
    }
}