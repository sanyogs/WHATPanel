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
 * @file
 * Emulation layer for code that used kses(), substituting in HTML Purifier.
 */

require_once dirname(__FILE__) . '/HTMLPurifier.auto.php';

function kses($string, $allowed_html, $allowed_protocols = null)
{
    $config = HTMLPurifier_Config::createDefault();
    $allowed_elements = array();
    $allowed_attributes = array();
    foreach ($allowed_html as $element => $attributes) {
        $allowed_elements[$element] = true;
        foreach ($attributes as $attribute => $x) {
            $allowed_attributes["$element.$attribute"] = true;
        }
    }
    $config->set('HTML.AllowedElements', $allowed_elements);
    $config->set('HTML.AllowedAttributes', $allowed_attributes);
    if ($allowed_protocols !== null) {
        $config->set('URI.AllowedSchemes', $allowed_protocols);
    }
    $purifier = new HTMLPurifier($config);
    return $purifier->purify($string);
}

// vim: et sw=4 sts=4
