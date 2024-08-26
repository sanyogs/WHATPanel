<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Application Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

namespace PhpOffice\PhpSpreadsheet\Calculation;

use PhpOffice\PhpSpreadsheet\Exception as PhpSpreadsheetException;

class Exception extends PhpSpreadsheetException
{
    public const CALCULATION_ENGINE_PUSH_TO_STACK = 1;

    /**
     * Error handler callback.
     *
     * @param mixed $code
     * @param mixed $string
     * @param mixed $file
     * @param mixed $line
     * @param mixed $context
     */
    public static function errorHandlerCallback($code, $string, $file, $line, /** @scrutinizer ignore-unused */ $context): void
    {
        $e = new self($string, $code);
        $e->line = $line;
        $e->file = $file;

        throw $e;
    }
}
