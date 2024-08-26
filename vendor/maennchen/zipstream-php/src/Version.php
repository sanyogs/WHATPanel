<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Application Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

declare(strict_types=1);

namespace ZipStream;

enum Version: int
{
    case STORE = 0x000A; // 1.00
    case DEFLATE = 0x0014; // 2.00
    case ZIP64 = 0x002D; // 4.50
}
