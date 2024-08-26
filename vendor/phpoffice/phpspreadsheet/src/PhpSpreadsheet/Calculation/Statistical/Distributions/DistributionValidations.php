<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Application Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

namespace PhpOffice\PhpSpreadsheet\Calculation\Statistical\Distributions;

use PhpOffice\PhpSpreadsheet\Calculation\Exception;
use PhpOffice\PhpSpreadsheet\Calculation\Information\ExcelError;
use PhpOffice\PhpSpreadsheet\Calculation\Statistical\StatisticalValidations;

class DistributionValidations extends StatisticalValidations
{
    /**
     * @param mixed $probability
     */
    public static function validateProbability($probability): float
    {
        $probability = self::validateFloat($probability);

        if ($probability < 0.0 || $probability > 1.0) {
            throw new Exception(ExcelError::NAN());
        }

        return $probability;
    }
}
