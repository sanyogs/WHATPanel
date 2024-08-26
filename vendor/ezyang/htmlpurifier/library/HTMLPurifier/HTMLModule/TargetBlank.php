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
 * Module adds the target=blank attribute transformation to a tags.  It
 * is enabled by HTML.TargetBlank
 */
class HTMLPurifier_HTMLModule_TargetBlank extends HTMLPurifier_HTMLModule
{
    /**
     * @type string
     */
    public $name = 'TargetBlank';

    /**
     * @param HTMLPurifier_Config $config
     */
    public function setup($config)
    {
        $a = $this->addBlankElement('a');
        $a->attr_transform_post[] = new HTMLPurifier_AttrTransform_TargetBlank();
    }
}

// vim: et sw=4 sts=4
