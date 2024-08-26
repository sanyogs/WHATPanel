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
 * Primitive email validation class based on the regexp found at
 * http://www.regular-expressions.info/email.html
 */
class HTMLPurifier_AttrDef_URI_Email_SimpleCheck extends HTMLPurifier_AttrDef_URI_Email
{

    /**
     * @param string $string
     * @param HTMLPurifier_Config $config
     * @param HTMLPurifier_Context $context
     * @return bool|string
     */
    public function validate($string, $config, $context)
    {
        // no support for named mailboxes i.e. "Bob <bob@example.com>"
        // that needs more percent encoding to be done
        if ($string == '') {
            return false;
        }
        $string = trim($string);
        $result = preg_match('/^[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i', $string);
        return $result ? $string : false;
    }
}

// vim: et sw=4 sts=4
