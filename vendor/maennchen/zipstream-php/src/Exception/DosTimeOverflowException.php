<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Application Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

declare(strict_types=1);

namespace ZipStream\Exception;

use DateTimeInterface;
use ZipStream\Exception;

/**
 * This Exception gets invoked if a file wasn't found
 */
class DosTimeOverflowException extends Exception
{
    /**
     * @internal
     */
    public function __construct(
        public readonly DateTimeInterface $dateTime
    ) {
        parent::__construct('The date ' . $dateTime->format(DateTimeInterface::ATOM) . " can't be represented as DOS time / date.");
    }
}
