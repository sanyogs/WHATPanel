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
 * Int Bool Cast
 *
 * DB column: int (0/1) <--> Class property: bool
 */
final class IntBoolCast extends BaseCast
{
    /**
     * @param int $value
     */
    public static function get($value, array $params = []): bool
    {
        return (bool) $value;
    }

    /**
     * @param bool|int|string $value
     */
    public static function set($value, array $params = []): int
    {
        return (int) $value;
    }
}
