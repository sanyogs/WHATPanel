<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Application Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

require_once 'lib/byte_safe_strings.php';
require_once 'lib/cast_to_int.php';
require_once 'lib/error_polyfill.php';
require_once 'other/ide_stubs/libsodium.php';
require_once 'lib/random.php';

$int = random_int(0, 65536);
