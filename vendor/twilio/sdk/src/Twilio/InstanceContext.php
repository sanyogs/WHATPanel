<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Application Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */


namespace Twilio;


class InstanceContext {
    protected $version;
    protected $solution = [];
    protected $uri;

    public function __construct(Version $version) {
        $this->version = $version;
    }

    public function __toString(): string {
        return '[InstanceContext]';
    }
}
