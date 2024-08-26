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

namespace CodeIgniter\Cache\Exceptions;

use CodeIgniter\Exceptions\DebugTraceableTrait;
use CodeIgniter\Exceptions\ExceptionInterface;
use RuntimeException;

/**
 * CacheException
 */
class CacheException extends RuntimeException implements ExceptionInterface
{
    use DebugTraceableTrait;

    /**
     * Thrown when handler has no permission to write cache.
     *
     * @return CacheException
     */
    public static function forUnableToWrite(string $path)
    {
        return new static(lang('hd_lang.Cache.unableToWrite', [$path]));
    }

    /**
     * Thrown when an unrecognized handler is used.
     *
     * @return CacheException
     */
    public static function forInvalidHandlers()
    {
        return new static(lang('hd_lang.Cache.invalidHandlers'));
    }

    /**
     * Thrown when no backup handler is setup in config.
     *
     * @return CacheException
     */
    public static function forNoBackup()
    {
        return new static(lang('hd_lang.Cache.noBackup'));
    }

    /**
     * Thrown when specified handler was not found.
     *
     * @return CacheException
     */
    public static function forHandlerNotFound()
    {
        return new static(lang('hd_lang.Cache.handlerNotFound'));
    }
}
