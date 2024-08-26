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

namespace CodeIgniter\Entity\Cast;

/**
 * Class BaseCast
 */
abstract class BaseCast implements CastInterface
{
    /**
     * Get
     *
     * @param array|bool|float|int|object|string|null $value  Data
     * @param array                                   $params Additional param
     *
     * @return array|bool|float|int|object|string|null
     */
    public static function get($value, array $params = [])
    {
        return $value;
    }

    /**
     * Set
     *
     * @param array|bool|float|int|object|string|null $value  Data
     * @param array                                   $params Additional param
     *
     * @return array|bool|float|int|object|string|null
     */
    public static function set($value, array $params = [])
    {
        return $value;
    }
}
