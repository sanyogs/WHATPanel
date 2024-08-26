<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Application Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

/**
 * Concrete empty token class.
 */
class HTMLPurifier_Token_Empty extends HTMLPurifier_Token_Tag
{
    public function toNode() {
        $n = parent::toNode();
        $n->empty = true;
        return $n;
    }
}

// vim: et sw=4 sts=4
