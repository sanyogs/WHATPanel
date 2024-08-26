<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Application Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

namespace Mpdf\File;

class LocalContentLoader implements \Mpdf\File\LocalContentLoaderInterface
{

	public function load($path)
	{
		return file_get_contents($path);
	}

}
