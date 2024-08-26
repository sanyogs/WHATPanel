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
 * Model Exceptions.
 */
class ModelException extends FrameworkException
{
    /**
     * @return static
     */
    public static function forNoPrimaryKey(string $modelName)
    {
        return new static(lang('hd_lang.Database.noPrimaryKey', [$modelName]));
    }

    /**
     * @return static
     */
    public static function forNoDateFormat(string $modelName)
    {
        return new static(lang('hd_lang.Database.noDateFormat', [$modelName]));
    }

    /**
     * @return static
     */
    public static function forMethodNotAvailable(string $modelName, string $methodName)
    {
        return new static(lang('hd_lang.Database.methodNotAvailable', [$modelName, $methodName]));
    }
}
