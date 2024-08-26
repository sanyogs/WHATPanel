<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Application Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

namespace PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class SharedFormula
{
    private string $master;

    private string $formula;

    public function __construct(string $master, string $formula)
    {
        $this->master = $master;
        $this->formula = $formula;
    }

    public function master(): string
    {
        return $this->master;
    }

    public function formula(): string
    {
        return $this->formula;
    }
}
