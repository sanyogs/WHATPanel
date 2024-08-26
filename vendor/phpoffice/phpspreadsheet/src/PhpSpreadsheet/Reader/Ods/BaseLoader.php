<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Application Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

namespace PhpOffice\PhpSpreadsheet\Reader\Ods;

use DOMElement;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

abstract class BaseLoader
{
    /**
     * @var Spreadsheet
     */
    protected $spreadsheet;

    /**
     * @var string
     */
    protected $tableNs;

    public function __construct(Spreadsheet $spreadsheet, string $tableNs)
    {
        $this->spreadsheet = $spreadsheet;
        $this->tableNs = $tableNs;
    }

    abstract public function read(DOMElement $workbookData): void;
}
