<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Application Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

if (!function_exists('str_starts_with')) {
	function str_starts_with($haystack, $needle)
	{
		return 0 === strncmp($haystack, $needle, \strlen($needle));
	}
}

if (!function_exists('str_ends_with')) {
	function str_ends_with($haystack, $needle)
	{
		if ('' === $needle || $needle === $haystack) {
			return true;
		}

		if ('' === $haystack) {
			return false;
		}

		$needleLength = \strlen($needle);

		return $needleLength <= \strlen($haystack) && 0 === substr_compare($haystack, $needle, -$needleLength);
	}
}
