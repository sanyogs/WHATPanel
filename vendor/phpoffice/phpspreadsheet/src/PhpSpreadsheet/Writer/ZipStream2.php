<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Application Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

namespace PhpOffice\PhpSpreadsheet\Writer;

use ZipStream\Option\Archive;
use ZipStream\ZipStream;

class ZipStream2
{
    /**
     * @param resource $fileHandle
     */
    public static function newZipStream($fileHandle): ZipStream
    {
        $options = new Archive();
        $options->setEnableZip64(false);
        $options->setOutputStream($fileHandle);

        return new ZipStream(null, $options);
    }
}
