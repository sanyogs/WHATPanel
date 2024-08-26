<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Application Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

use Complex\Complex as Complex;
use Complex\Operations;

include(__DIR__ . '/../vendor/autoload.php');

$values = [
    new Complex(123),
    new Complex(456, 123),
    new Complex(0.0, 456),
];

foreach ($values as $value) {
    echo $value, PHP_EOL;
}

echo 'Addition', PHP_EOL;

$result = Operations::add(...$values);
echo '=> ', $result, PHP_EOL;

echo PHP_EOL;

echo 'Subtraction', PHP_EOL;

$result = Operations::subtract(...$values);
echo '=> ', $result, PHP_EOL;

echo PHP_EOL;

echo 'Multiplication', PHP_EOL;

$result = Operations::multiply(...$values);
echo '=> ', $result, PHP_EOL;
