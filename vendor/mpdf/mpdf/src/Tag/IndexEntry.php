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

use Mpdf\Mpdf;

class IndexEntry extends Tag
{

	public function open($attr, &$ahtml, &$ihtml)
	{
		if (!empty($attr['CONTENT'])) {
			if (!empty($attr['XREF'])) {
				$this->mpdf->IndexEntry(htmlspecialchars_decode($attr['CONTENT'], ENT_QUOTES), $attr['XREF']);
				return;
			}
			$objattr = [];
			$objattr['CONTENT'] = htmlspecialchars_decode($attr['CONTENT'], ENT_QUOTES);
			$objattr['type'] = 'indexentry';
			$objattr['vertical-align'] = 'T';
			$e = Mpdf::OBJECT_IDENTIFIER . "type=indexentry,objattr=" . serialize($objattr) . Mpdf::OBJECT_IDENTIFIER;
			if ($this->mpdf->tableLevel) {
				$this->mpdf->cell[$this->mpdf->row][$this->mpdf->col]['textbuffer'][] = [$e];
			} // *TABLES*
			else { // *TABLES*
				$this->mpdf->textbuffer[] = [$e];
			} // *TABLES*
		}
	}

	public function close(&$ahtml, &$ihtml)
	{
	}
}
