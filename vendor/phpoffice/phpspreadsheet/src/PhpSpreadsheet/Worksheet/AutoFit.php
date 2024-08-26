<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Application Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

namespace PhpOffice\PhpSpreadsheet\Worksheet;

use PhpOffice\PhpSpreadsheet\Cell\CellAddress;
use PhpOffice\PhpSpreadsheet\Cell\CellRange;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class AutoFit
{
    protected Worksheet $worksheet;

    public function __construct(Worksheet $worksheet)
    {
        $this->worksheet = $worksheet;
    }

    public function getAutoFilterIndentRanges(): array
    {
        $autoFilterIndentRanges = [];
        $autoFilterIndentRanges[] = $this->getAutoFilterIndentRange($this->worksheet->getAutoFilter());

        foreach ($this->worksheet->getTableCollection() as $table) {
            /** @var Table $table */
            if ($table->getShowHeaderRow() === true && $table->getAllowFilter() === true) {
                $autoFilter = $table->getAutoFilter();
                if ($autoFilter !== null) {
                    $autoFilterIndentRanges[] = $this->getAutoFilterIndentRange($autoFilter);
                }
            }
        }

        return array_filter($autoFilterIndentRanges);
    }

    private function getAutoFilterIndentRange(AutoFilter $autoFilter): ?string
    {
        $autoFilterRange = $autoFilter->getRange();
        $autoFilterIndentRange = null;

        if (!empty($autoFilterRange)) {
            $autoFilterRangeBoundaries = Coordinate::rangeBoundaries($autoFilterRange);
            $autoFilterIndentRange = (string) new CellRange(
                CellAddress::fromColumnAndRow($autoFilterRangeBoundaries[0][0], $autoFilterRangeBoundaries[0][1]),
                CellAddress::fromColumnAndRow($autoFilterRangeBoundaries[1][0], $autoFilterRangeBoundaries[0][1])
            );
        }

        return $autoFilterIndentRange;
    }
}
