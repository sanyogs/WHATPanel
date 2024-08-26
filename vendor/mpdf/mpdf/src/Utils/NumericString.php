<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Application Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

namespace Mpdf\Utils;

class NumericString
{

	public static function containsPercentChar($string)
	{
		return strstr($string, '%');
	}

	public static function removePercentChar($string)
	{
		return str_replace('%', '', $string);
	}

}
