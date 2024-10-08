<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Application Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\TwiML\Voice;

use Twilio\TwiML\TwiML;

class SsmlSub extends TwiML {
    /**
     * SsmlSub constructor.
     *
     * @param string $words Words to be substituted
     * @param array $attributes Optional attributes
     */
    public function __construct($words, $attributes = []) {
        parent::__construct('sub', $words, $attributes);
    }

    /**
     * Add Alias attribute.
     *
     * @param string $alias Substitute a different word (or pronunciation) for
     *                      selected text such as an acronym or abbreviation
     */
    public function setAlias($alias): self {
        return $this->setAttribute('alias', $alias);
    }
}