<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Application Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

if (!function_exists('dd')) {
	function dd(...$args)
	{
		if (function_exists('dump')) {
			dump(...$args);
		} else {
			var_dump(...$args);
		}
		die;
	}
}
