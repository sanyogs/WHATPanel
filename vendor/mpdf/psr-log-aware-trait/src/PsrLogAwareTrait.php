<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Application Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

namespace Mpdf\PsrLogAwareTrait;

use Psr\Log\LoggerInterface;

trait PsrLogAwareTrait 
{

	/**
	 * @var \Psr\Log\LoggerInterface
	 */
	protected $logger;

	public function setLogger(LoggerInterface $logger)
	{
		$this->logger = $logger;
	}
	
}
