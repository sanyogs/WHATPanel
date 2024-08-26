<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Application Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

namespace Twilio\TwiML;

class GenericNode extends TwiML {

    /**
     * GenericNode constructor.
     *
     * @param string $name XML element name
     * @param string $value XML value
     * @param array $attributes XML attributes
     */
    public function __construct(string $name, ?string $value, array $attributes) {
        parent::__construct($name, $value, $attributes);
        $this->name = $name;
        $this->value = $value;
    }
}
