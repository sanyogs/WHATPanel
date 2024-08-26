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

trait MpdfPsrLogAwareTrait
{

	/**
	 * @var \Psr\Log\LoggerInterface
	 */
	protected $logger;

	public function setLogger(LoggerInterface $logger)
	{
		$this->logger = $logger;
		if (property_exists($this, 'services') && is_array($this->services)) {
			foreach ($this->services as $name) {
				if ($this->$name && $this->$name instanceof \Psr\Log\LoggerAwareInterface) {
					$this->$name->setLogger($logger);
				}
			}
		}
	}

}
