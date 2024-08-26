<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Application Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

namespace Mpdf\Tag;

class IndexInsert extends Tag
{

	public function open($attr, &$ahtml, &$ihtml)
	{
		$indexCollationLocale = '';
		if (isset($attr['COLLATION'])) {
			$indexCollationLocale = $attr['COLLATION'];
		}

		$indexCollationGroup = '';
		if (isset($attr['COLLATION-GROUP'])) {
			$indexCollationGroup = $attr['COLLATION-GROUP'];
		}

		$usedivletters = 1;
		if (isset($attr['USEDIVLETTERS']) && (strtoupper($attr['USEDIVLETTERS']) === 'OFF'
				|| $attr['USEDIVLETTERS'] == -1
				|| $attr['USEDIVLETTERS'] === '0')) {
			$usedivletters = 0;
		}
		$links = isset($attr['LINKS']) && (strtoupper($attr['LINKS']) === 'ON' || $attr['LINKS'] == 1);
		$this->mpdf->InsertIndex($usedivletters, $links, $indexCollationLocale, $indexCollationGroup);
	}

	public function close(&$ahtml, &$ihtml)
	{
	}
}
