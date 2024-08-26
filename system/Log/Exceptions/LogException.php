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

namespace CodeIgniter\Log\Exceptions;

use CodeIgniter\Exceptions\FrameworkException;

class LogException extends FrameworkException
{
    /**
     * @return static
     */
    public static function forInvalidLogLevel(string $level)
    {
        return new static(lang('hd_lang.Log.invalidLogLevel', [$level]));
    }

    /**
     * @return static
     */
    public static function forInvalidMessageType(string $messageType)
    {
        return new static(lang('hd_lang.Log.invalidMessageType', [$messageType]));
    }
}
