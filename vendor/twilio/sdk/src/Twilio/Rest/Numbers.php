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

use Twilio\Rest\Numbers\V2;

class Numbers extends NumbersBase {
    /**
     * @deprecated Use v2->regulatoryCompliance instead.
     */
    protected function getRegulatoryCompliance(): \Twilio\Rest\Numbers\V2\RegulatoryComplianceList {
        echo "regulatoryCompliance is deprecated. Use v2->regulatoryCompliance instead.";
        return $this->v2->regulatoryCompliance;
    }

}