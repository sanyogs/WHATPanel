<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Application Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

use Matrix\Matrix;
use Matrix\Decomposition\QR;

include __DIR__ . '/../vendor/autoload.php';

$grid = [
    [0, 1],
    [-1, 0],
];

$targetGrid = [
    [-1],
    [2],
];

$matrix = new Matrix($grid);
$target = new Matrix($targetGrid);

$decomposition = new QR($matrix);

$X = $decomposition->solve($target);

echo 'X', PHP_EOL;
var_export($X->toArray());
echo PHP_EOL;

$resolve = $matrix->multiply($X);

echo 'Resolve', PHP_EOL;
var_export($resolve->toArray());
echo PHP_EOL;
