<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Application Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */


namespace Twilio\Jwt\TaskRouter;

/**
 * Twilio TaskRouter TaskQueue Capability assigner
 *
 * @author Justin Witz <justin.witz@twilio.com>
 * @license  http://creativecommons.org/licenses/MIT/ MIT
 */
class TaskQueueCapability extends CapabilityToken {
    public function __construct(string $accountSid, string $authToken, string $workspaceSid, string $taskQueueSid,
                                string $overrideBaseUrl = null, string $overrideBaseWSUrl = null) {
        parent::__construct($accountSid, $authToken, $workspaceSid, $taskQueueSid, null, $overrideBaseUrl, $overrideBaseWSUrl);
    }

    protected function setupResource(): void {
        $this->resourceUrl = $this->baseUrl . '/TaskQueues/' . $this->channelId;
    }
}
