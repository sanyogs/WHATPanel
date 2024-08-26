<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Application Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

namespace Mpdf;

class PageBox implements \ArrayAccess
{

	private $container = [];

	public function __construct()
	{
		$this->container = [
			'current' => null,
			'outer_width_LR' => null,
			'outer_width_TB' => null,
			'using' => null,
		];
	}

	#[\ReturnTypeWillChange]
	public function offsetSet($offset, $value)
	{
		if (!$this->offsetExists($offset)) {
			throw new \Mpdf\MpdfException('Invalid key to set for PageBox');
		}

		$this->container[$offset] = $value;
	}

	#[\ReturnTypeWillChange]
	public function offsetExists($offset)
	{
		return array_key_exists($offset, $this->container);
	}

	#[\ReturnTypeWillChange]
	public function offsetUnset($offset)
	{
		if (!$this->offsetExists($offset)) {
			throw new \Mpdf\MpdfException('Invalid key to set for PageBox');
		}

		$this->container[$offset] = null;
	}

	#[\ReturnTypeWillChange]
	public function offsetGet($offset)
	{
		if (!$this->offsetExists($offset)) {
			throw new \Mpdf\MpdfException('Invalid key to set for PageBox');
		}

		return $this->container[$offset];
	}

}
