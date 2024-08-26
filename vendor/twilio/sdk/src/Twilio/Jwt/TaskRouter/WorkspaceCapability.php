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


class WorkspaceCapability extends CapabilityToken {
    public function __construct(string $accountSid, string $authToken, string $workspaceSid,
                                string $overrideBaseUrl = null, string $overrideBaseWSUrl = null) {
        parent::__construct($accountSid, $authToken, $workspaceSid, $workspaceSid, null, $overrideBaseUrl, $overrideBaseWSUrl);
    }

    protected function setupResource(): void {
        $this->resourceUrl = $this->baseUrl;
    }
}
