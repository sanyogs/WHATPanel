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

namespace CodeIgniter\Router;

/**
 * Expected behavior of a AutoRouter.
 */
interface AutoRouterInterface
{
    /**
     * Returns controller, method and params from the URI.
     *
     * @return array [directory_name, controller_name, controller_method, params]
     */
    public function getRoute(string $uri, string $httpVerb): array;
}
