<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Application Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

namespace PhpOffice\PhpSpreadsheet\Writer\Ods;

class Mimetype extends WriterPart
{
    /**
     * Write mimetype to plain text format.
     *
     * @return string XML Output
     */
    public function write(): string
    {
        return 'application/vnd.oasis.opendocument.spreadsheet';
    }
}
