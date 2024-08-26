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
 * XHTML 1.1 Iframe Module provides inline frames.
 *
 * @note This module is not considered safe unless an Iframe
 * whitelisting mechanism is specified.  Currently, the only
 * such mechanism is %URL.SafeIframeRegexp
 */
class HTMLPurifier_HTMLModule_Iframe extends HTMLPurifier_HTMLModule
{

    /**
     * @type string
     */
    public $name = 'Iframe';

    /**
     * @type bool
     */
    public $safe = false;

    /**
     * @param HTMLPurifier_Config $config
     */
    public function setup($config)
    {
        if ($config->get('HTML.SafeIframe')) {
            $this->safe = true;
        }
        $this->addElement(
            'iframe',
            'Inline',
            'Flow',
            'Common',
            array(
                'src' => 'URI#embedded',
                'width' => 'Length',
                'height' => 'Length',
                'name' => 'ID',
                'scrolling' => 'Enum#yes,no,auto',
                'frameborder' => 'Enum#0,1',
                'longdesc' => 'URI',
                'marginheight' => 'Pixels',
                'marginwidth' => 'Pixels',
            )
        );
    }
}

// vim: et sw=4 sts=4
