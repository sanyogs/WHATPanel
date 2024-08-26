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
 * Injector that displays the URL of an anchor instead of linking to it, in addition to showing the text of the link.
 */
class HTMLPurifier_Injector_DisplayLinkURI extends HTMLPurifier_Injector
{
    /**
     * @type string
     */
    public $name = 'DisplayLinkURI';

    /**
     * @type array
     */
    public $needed = array('a');

    /**
     * @param $token
     */
    public function handleElement(&$token)
    {
    }

    /**
     * @param HTMLPurifier_Token $token
     */
    public function handleEnd(&$token)
    {
        if (isset($token->start->attr['href'])) {
            $url = $token->start->attr['href'];
            unset($token->start->attr['href']);
            $token = array($token, new HTMLPurifier_Token_Text(" ($url)"));
        } else {
            // nothing to display
        }
    }
}

// vim: et sw=4 sts=4
