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

class NewColumn extends Tag
{

	public function open($attr, &$ahtml, &$ihtml)
	{
		$this->mpdf->ignorefollowingspaces = true;
		$this->mpdf->NewColumn();
		$this->mpdf->ColumnAdjust = false; // disables all column height adjustment for the page.
	}

	public function close(&$ahtml, &$ihtml)
	{
	}
}
