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
 * Implements safety checks for safe iframes.
 *
 * @warning This filter is *critical* for ensuring that %HTML.SafeIframe
 * works safely.
 */
class HTMLPurifier_URIFilter_SafeIframe extends HTMLPurifier_URIFilter
{
    /**
     * @type string
     */
    public $name = 'SafeIframe';

    /**
     * @type bool
     */
    public $always_load = true;

    /**
     * @type string
     */
    protected $regexp = null;

    // XXX: The not so good bit about how this is all set up now is we
    // can't check HTML.SafeIframe in the 'prepare' step: we have to
    // defer till the actual filtering.
    /**
     * @param HTMLPurifier_Config $config
     * @return bool
     */
    public function prepare($config)
    {
        $this->regexp = $config->get('URI.SafeIframeRegexp');
        return true;
    }

    /**
     * @param HTMLPurifier_URI $uri
     * @param HTMLPurifier_Config $config
     * @param HTMLPurifier_Context $context
     * @return bool
     */
    public function filter(&$uri, $config, $context)
    {
        // check if filter not applicable
        if (!$config->get('HTML.SafeIframe')) {
            return true;
        }
        // check if the filter should actually trigger
        if (!$context->get('EmbeddedURI', true)) {
            return true;
        }
        $token = $context->get('CurrentToken', true);
        if (!($token && $token->name == 'iframe')) {
            return true;
        }
        // check if we actually have some whitelists enabled
        if ($this->regexp === null) {
            return false;
        }
        // actually check the whitelists
        return preg_match($this->regexp, $uri->toString());
    }
}

// vim: et sw=4 sts=4
