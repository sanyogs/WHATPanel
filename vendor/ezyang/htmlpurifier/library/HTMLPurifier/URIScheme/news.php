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
 * Validates news (Usenet) as defined by generic RFC 1738
 */
class HTMLPurifier_URIScheme_news extends HTMLPurifier_URIScheme
{
    /**
     * @type bool
     */
    public $browsable = false;

    /**
     * @type bool
     */
    public $may_omit_host = true;

    /**
     * @param HTMLPurifier_URI $uri
     * @param HTMLPurifier_Config $config
     * @param HTMLPurifier_Context $context
     * @return bool
     */
    public function doValidate(&$uri, $config, $context)
    {
        $uri->userinfo = null;
        $uri->host = null;
        $uri->port = null;
        $uri->query = null;
        // typecode check needed on path
        return true;
    }
}

// vim: et sw=4 sts=4
