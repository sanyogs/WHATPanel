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
 * Legacy autoloader for systems lacking spl_autoload_register
 *
 */

spl_autoload_register(function($class)
{
     return HTMLPurifier_Bootstrap::autoload($class);
});

// vim: et sw=4 sts=4
