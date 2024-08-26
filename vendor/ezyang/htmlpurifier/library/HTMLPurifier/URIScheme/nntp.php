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
 * Validates nntp (Network News Transfer Protocol) as defined by generic RFC 1738
 */
class HTMLPurifier_URIScheme_nntp extends HTMLPurifier_URIScheme
{
    /**
     * @type int
     */
    public $default_port = 119;

    /**
     * @type bool
     */
    public $browsable = false;

    /**
     * @param HTMLPurifier_URI $uri
     * @param HTMLPurifier_Config $config
     * @param HTMLPurifier_Context $context
     * @return bool
     */
    public function doValidate(&$uri, $config, $context)
    {
        $uri->userinfo = null;
        $uri->query = null;
        return true;
    }
}

// vim: et sw=4 sts=4
