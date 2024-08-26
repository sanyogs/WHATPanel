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
 * Concrete comment token class. Generally will be ignored.
 */
class HTMLPurifier_Token_Comment extends HTMLPurifier_Token
{
    /**
     * Character data within comment.
     * @type string
     */
    public $data;

    /**
     * @type bool
     */
    public $is_whitespace = true;

    /**
     * Transparent constructor.
     *
     * @param string $data String comment data.
     * @param int $line
     * @param int $col
     */
    public function __construct($data, $line = null, $col = null)
    {
        $this->data = $data;
        $this->line = $line;
        $this->col = $col;
    }

    public function toNode() {
        return new HTMLPurifier_Node_Comment($this->data, $this->line, $this->col);
    }
}

// vim: et sw=4 sts=4
