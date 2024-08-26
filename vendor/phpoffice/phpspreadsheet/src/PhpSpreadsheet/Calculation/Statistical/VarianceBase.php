<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Application Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

namespace PhpOffice\PhpSpreadsheet\Calculation\Statistical;

use PhpOffice\PhpSpreadsheet\Calculation\Functions;

abstract class VarianceBase
{
    /**
     * @param mixed $value
     *
     * @return mixed
     */
    protected static function datatypeAdjustmentAllowStrings($value)
    {
        if (is_bool($value)) {
            return (int) $value;
        } elseif (is_string($value)) {
            return 0;
        }

        return $value;
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    protected static function datatypeAdjustmentBooleans($value)
    {
        if (is_bool($value) && (Functions::getCompatibilityMode() == Functions::COMPATIBILITY_OPENOFFICE)) {
            return (int) $value;
        }

        return $value;
    }
}
