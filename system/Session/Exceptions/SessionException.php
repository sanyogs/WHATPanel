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

namespace CodeIgniter\Session\Exceptions;

use CodeIgniter\Exceptions\FrameworkException;

class SessionException extends FrameworkException
{
    public static function forMissingDatabaseTable()
    {
        return new static(lang('hd_lang.Session.missingDatabaseTable'));
    }

    public static function forInvalidSavePath(?string $path = null)
    {
        return new static(lang('hd_lang.Session.invalidSavePath', [$path]));
    }

    public static function forWriteProtectedSavePath(?string $path = null)
    {
        return new static(lang('hd_lang.Session.writeProtectedSavePath', [$path]));
    }

    public static function forEmptySavepath()
    {
        return new static(lang('hd_lang.Session.emptySavePath'));
    }

    public static function forInvalidSavePathFormat(string $path)
    {
        return new static(lang('hd_lang.Session.invalidSavePathFormat', [$path]));
    }

    /**
     * @deprecated
     *
     * @codeCoverageIgnore
     */
    public static function forInvalidSameSiteSetting(string $samesite)
    {
        return new static(lang('hd_lang.Session.invalidSameSiteSetting', [$samesite]));
    }
}
