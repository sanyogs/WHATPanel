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
 * XHTML 1.1 Edit Module, defines editing-related elements. Text Extension
 * Module.
 */
class HTMLPurifier_HTMLModule_StyleAttribute extends HTMLPurifier_HTMLModule
{
    /**
     * @type string
     */
    public $name = 'StyleAttribute';

    /**
     * @type array
     */
    public $attr_collections = array(
        // The inclusion routine differs from the Abstract Modules but
        // is in line with the DTD and XML Schemas.
        'Style' => array('style' => false), // see constructor
        'Core' => array(0 => array('Style'))
    );

    /**
     * @param HTMLPurifier_Config $config
     */
    public function setup($config)
    {
        $this->attr_collections['Style']['style'] = new HTMLPurifier_AttrDef_CSS();
    }
}

// vim: et sw=4 sts=4
