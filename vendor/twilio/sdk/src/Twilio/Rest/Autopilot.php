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

use Twilio\Rest\Autopilot\V1;

class Autopilot extends AutopilotBase {

    /**
     * @deprecated Use v1->assistants instead.
     */
    protected function getAssistants(): \Twilio\Rest\Autopilot\V1\AssistantList {
        echo "assistants is deprecated. Use v1->assistants instead.";
        return $this->v1->assistants;
    }

    /**
     * @deprecated Use v1->assistants(\$sid) instead.
     * @param string $sid The unique string that identifies the resource
     */
    protected function contextAssistants(string $sid): \Twilio\Rest\Autopilot\V1\AssistantContext {
        echo "assistants(\$sid) is deprecated. Use v1->assistants(\$sid) instead.";
        return $this->v1->assistants($sid);
    }

    /**
     * @deprecated Use v1->restoreAssistant instead
     */
    protected function getRestoreAssistant(): \Twilio\Rest\Autopilot\V1\RestoreAssistantList {
        echo "restoreAssistant is deprecated. Use v1->restoreAssistant instead.";
        return $this->v1->restoreAssistant;
    }
}