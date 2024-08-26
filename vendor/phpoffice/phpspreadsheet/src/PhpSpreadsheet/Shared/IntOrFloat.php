<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Application Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

namespace PhpOffice\PhpSpreadsheet\Shared;

class IntOrFloat
{
    /**
     * Help some functions with large results operate correctly on 32-bit,
     * by returning result as int when possible, float otherwise.
     *
     * @param float|int $value
     *
     * @return float|int
     */
    public static function evaluate($value)
    {
        $iValue = (int) $value;

        return ($value == $iValue) ? $iValue : $value;
    }
}
