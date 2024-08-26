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

namespace CodeIgniter\Router\Exceptions;

use CodeIgniter\Exceptions\FrameworkException;

/**
 * RouterException
 */
class RouterException extends FrameworkException
{
    /**
     * Thrown when the actual parameter type does not match
     * the expected types.
     *
     * @return RouterException
     */
    public static function forInvalidParameterType()
    {
        return new static(lang('hd_lang.Router.invalidParameterType'));
    }

    /**
     * Thrown when a default route is not set.
     *
     * @return RouterException
     */
    public static function forMissingDefaultRoute()
    {
        return new static(lang('hd_lang.Router.missingDefaultRoute'));
    }

    /**
     * Throw when controller or its method is not found.
     *
     * @return RouterException
     */
    public static function forControllerNotFound(string $controller, string $method)
    {
        return new static(lang('hd_lang.HTTP.controllerNotFound', [$controller, $method]));
    }

    /**
     * Throw when route is not valid.
     *
     * @return RouterException
     */
    public static function forInvalidRoute(string $route)
    {
        return new static(lang('hd_lang.HTTP.invalidRoute', [$route]));
    }

    /**
     * Throw when dynamic controller.
     *
     * @return RouterException
     */
    public static function forDynamicController(string $handler)
    {
        return new static(lang('hd_lang.Router.invalidDynamicController', [$handler]));
    }

    /**
     * Throw when controller name has `/`.
     *
     * @return RouterException
     */
    public static function forInvalidControllerName(string $handler)
    {
        return new static(lang('hd_lang.Router.invalidControllerName', [$handler]));
    }
}
