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


class InstanceResource {
    protected $version;
    protected $context;
    protected $properties = [];
    protected $solution = [];

    public function __construct(Version $version) {
        $this->version = $version;
    }

    public function toArray(): array {
        return $this->properties;
    }

    public function __toString(): string {
        return '[InstanceResource]';
    }

    public function __isset($name): bool {
        return \array_key_exists($name, $this->properties);
    }
}
