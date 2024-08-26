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

use RuntimeException;

/**
 * Class DownloadException
 */
class DownloadException extends RuntimeException implements ExceptionInterface
{
    use DebugTraceableTrait;

    /**
     * @return static
     */
    public static function forCannotSetFilePath(string $path)
    {
        return new static(lang('hd_lang.HTTP.cannotSetFilepath', [$path]));
    }

    /**
     * @return static
     */
    public static function forCannotSetBinary()
    {
        return new static(lang('hd_lang.HTTP.cannotSetBinary'));
    }

    /**
     * @return static
     */
    public static function forNotFoundDownloadSource()
    {
        return new static(lang('hd_lang.HTTP.notFoundDownloadSource'));
    }

    /**
     * @return static
     */
    public static function forCannotSetCache()
    {
        return new static(lang('hd_lang.HTTP.cannotSetCache'));
    }

    /**
     * @return static
     */
    public static function forCannotSetStatusCode(int $code, string $reason)
    {
        return new static(lang('hd_lang.HTTP.cannotSetStatusCode', [$code, $reason]));
    }
}
