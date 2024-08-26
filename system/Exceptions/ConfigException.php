<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

/**
 * This file is part of CodeIgniter 4 framework.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace CodeIgniter\Exceptions;

/**
 * Exception for automatic logging.
 */
class ConfigException extends CriticalError implements HasExitCodeInterface
{
    use DebugTraceableTrait;

    public function getExitCode(): int
    {
        return EXIT_CONFIG;
    }

    /**
     * @return static
     */
    public static function forDisabledMigrations()
    {
        return new static(lang('hd_lang.Migrations.disabled'));
    }
}
