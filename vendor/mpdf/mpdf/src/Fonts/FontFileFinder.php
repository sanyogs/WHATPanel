<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Application Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

namespace Mpdf\Fonts;

class FontFileFinder
{

	private $directories;

	public function __construct($directories)
	{
		$this->setDirectories($directories);
	}

	public function setDirectories($directories)
	{
		if (!is_array($directories)) {
			$directories = [$directories];
		}

		$this->directories = $directories;
	}

	public function findFontFile($name)
	{
		foreach ($this->directories as $directory) {
			$filename = $directory . '/' . $name;
			if (file_exists($filename)) {
				return $filename;
			}
		}

		throw new \Mpdf\MpdfException(sprintf('Cannot find TTF TrueType font file "%s" in configured font directories.', $name));
	}
}
