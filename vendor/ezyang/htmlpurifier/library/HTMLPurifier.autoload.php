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
 * Convenience file that registers autoload handler for HTML Purifier.
 * It also does some sanity checks.
 */

if (function_exists('spl_autoload_register') && function_exists('spl_autoload_unregister')) {
    // We need unregister for our pre-registering functionality
    HTMLPurifier_Bootstrap::registerAutoload();
    if (function_exists('__autoload')) {
        // Be polite and ensure that userland autoload gets retained
        spl_autoload_register('__autoload');
    }
} elseif (!function_exists('__autoload')) {
    require dirname(__FILE__) . '/HTMLPurifier.autoload-legacy.php';
}

// phpcs:ignore PHPCompatibility.IniDirectives.RemovedIniDirectives.zend_ze1_compatibility_modeRemoved
if (ini_get('zend.ze1_compatibility_mode')) {
    trigger_error("HTML Purifier is not compatible with zend.ze1_compatibility_mode; please turn it off", E_USER_ERROR);
}

// vim: et sw=4 sts=4
