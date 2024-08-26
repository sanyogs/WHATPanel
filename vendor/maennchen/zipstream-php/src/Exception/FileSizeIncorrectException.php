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

use ZipStream\Exception;

/**
 * This Exception gets invoked if a file is not as large as it was specified.
 */
class FileSizeIncorrectException extends Exception
{
    /**
     * @internal
     */
    public function __construct(
        public readonly int $expectedSize,
        public readonly int $actualSize
    ) {
        parent::__construct("File is {$actualSize} instead of {$expectedSize} bytes large. Adjust `exactSize` parameter.");
    }
}
