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
* Name: Page Bottom
*/
// echo "<pre>";print_r($blocks);die;
foreach($blocks as $key => $block) {
    echo $block->content;
} ?>