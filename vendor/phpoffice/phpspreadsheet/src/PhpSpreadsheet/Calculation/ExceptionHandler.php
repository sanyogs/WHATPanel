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

class ExceptionHandler
{
    /**
     * Register errorhandler.
     */
    public function __construct()
    {
        /** @var callable */
        $callable = [Exception::class, 'errorHandlerCallback'];
        set_error_handler($callable, E_ALL);
    }

    /**
     * Unregister errorhandler.
     */
    public function __destruct()
    {
        restore_error_handler();
    }
}
