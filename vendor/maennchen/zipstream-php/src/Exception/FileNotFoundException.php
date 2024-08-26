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
 * This Exception gets invoked if a file wasn't found
 */
class FileNotFoundException extends Exception
{
    /**
     * @internal
     */
    public function __construct(
        public readonly string $path
    ) {
        parent::__construct("The file with the path $path wasn't found.");
    }
}
