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
 * Cast Exceptions.
 *
 * @deprecated use CodeIgniter\Entity\Exceptions\CastException instead.
 *
 * @codeCoverageIgnore
 */
class CastException extends CriticalError implements HasExitCodeInterface
{
    use DebugTraceableTrait;

    public function getExitCode(): int
    {
        return EXIT_CONFIG;
    }

    /**
     * @return static
     */
    public static function forInvalidJsonFormatException(int $error)
    {
        switch ($error) {
            case JSON_ERROR_DEPTH:
                return new static(lang('hd_lang.Cast.jsonErrorDepth'));

            case JSON_ERROR_STATE_MISMATCH:
                return new static(lang('hd_lang.Cast.jsonErrorStateMismatch'));

            case JSON_ERROR_CTRL_CHAR:
                return new static(lang('hd_lang.Cast.jsonErrorCtrlChar'));

            case JSON_ERROR_SYNTAX:
                return new static(lang('hd_lang.Cast.jsonErrorSyntax'));

            case JSON_ERROR_UTF8:
                return new static(lang('hd_lang.Cast.jsonErrorUtf8'));

            default:
                return new static(lang('hd_lang.Cast.jsonErrorUnknown'));
        }
    }
}
