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

class Tts extends SubstituteTag
{

	public function open($attr, &$ahtml, &$ihtml)
	{
		$this->mpdf->tts = true;
		$this->mpdf->InlineProperties['TTS'] = $this->mpdf->saveInlineProperties();
		$this->mpdf->setCSS(['FONT-FAMILY' => 'csymbol', 'FONT-WEIGHT' => 'normal', 'FONT-STYLE' => 'normal'], 'INLINE');
	}

}
