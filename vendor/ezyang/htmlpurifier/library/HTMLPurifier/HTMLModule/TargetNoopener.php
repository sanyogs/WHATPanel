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
 * Module adds the target-based noopener attribute transformation to a tags.  It
 * is enabled by HTML.TargetNoopener
 */
class HTMLPurifier_HTMLModule_TargetNoopener extends HTMLPurifier_HTMLModule
{
    /**
     * @type string
     */
    public $name = 'TargetNoopener';

    /**
     * @param HTMLPurifier_Config $config
     */
    public function setup($config) {
        $a = $this->addBlankElement('a');
        $a->attr_transform_post[] = new HTMLPurifier_AttrTransform_TargetNoopener();
    }
}
