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
use PhpOffice\PhpSpreadsheet\Calculation\Information\ExcelError;

class Size
{
    /**
     * LARGE.
     *
     * Returns the nth largest value in a data set. You can use this function to
     *        select a value based on its relative standing.
     *
     * Excel Function:
     *        LARGE(value1[,value2[, ...]],entry)
     *
     * @param mixed $args Data values
     *
     * @return float|string The result, or a string containing an error
     */
    public static function large(...$args)
    {
        $aArgs = Functions::flattenArray($args);
        $entry = array_pop($aArgs);

        if ((is_numeric($entry)) && (!is_string($entry))) {
            $entry = (int) floor($entry);

            $mArgs = self::filter($aArgs);
            $count = Counts::COUNT($mArgs);
            --$entry;
            if ($count === 0 || $entry < 0 || $entry >= $count) {
                return ExcelError::NAN();
            }
            rsort($mArgs);

            return $mArgs[$entry];
        }

        return ExcelError::VALUE();
    }

    /**
     * SMALL.
     *
     * Returns the nth smallest value in a data set. You can use this function to
     *        select a value based on its relative standing.
     *
     * Excel Function:
     *        SMALL(value1[,value2[, ...]],entry)
     *
     * @param mixed $args Data values
     *
     * @return float|string The result, or a string containing an error
     */
    public static function small(...$args)
    {
        $aArgs = Functions::flattenArray($args);

        $entry = array_pop($aArgs);

        if ((is_numeric($entry)) && (!is_string($entry))) {
            $entry = (int) floor($entry);

            $mArgs = self::filter($aArgs);
            $count = Counts::COUNT($mArgs);
            --$entry;
            if ($count === 0 || $entry < 0 || $entry >= $count) {
                return ExcelError::NAN();
            }
            sort($mArgs);

            return $mArgs[$entry];
        }

        return ExcelError::VALUE();
    }

    /**
     * @param mixed[] $args Data values
     */
    protected static function filter(array $args): array
    {
        $mArgs = [];

        foreach ($args as $arg) {
            // Is it a numeric value?
            if ((is_numeric($arg)) && (!is_string($arg))) {
                $mArgs[] = $arg;
            }
        }

        return $mArgs;
    }
}
