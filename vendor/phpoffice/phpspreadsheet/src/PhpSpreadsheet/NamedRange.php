<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Application Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

namespace PhpOffice\PhpSpreadsheet;

use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class NamedRange extends DefinedName
{
    /**
     * Create a new Named Range.
     */
    public function __construct(
        string $name,
        ?Worksheet $worksheet = null,
        string $range = 'A1',
        bool $localOnly = false,
        ?Worksheet $scope = null
    ) {
        if ($worksheet === null && $scope === null) {
            throw new Exception('You must specify a worksheet or a scope for a Named Range');
        }
        parent::__construct($name, $worksheet, $range, $localOnly, $scope);
    }

    /**
     * Get the range value.
     */
    public function getRange(): string
    {
        return $this->value;
    }

    /**
     * Set the range value.
     */
    public function setRange(string $range): self
    {
        if (!empty($range)) {
            $this->value = $range;
        }

        return $this;
    }

    public function getCellsInRange(): array
    {
        $range = $this->value;
        if (substr($range, 0, 1) === '=') {
            $range = substr($range, 1);
        }

        return Coordinate::extractAllCellReferencesInRange($range);
    }
}
