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
 * Sets height/width defaults for <textarea>
 */
class HTMLPurifier_AttrTransform_Textarea extends HTMLPurifier_AttrTransform
{
    /**
     * @param array $attr
     * @param HTMLPurifier_Config $config
     * @param HTMLPurifier_Context $context
     * @return array
     */
    public function transform($attr, $config, $context)
    {
        // Calculated from Firefox
        if (!isset($attr['cols'])) {
            $attr['cols'] = '22';
        }
        if (!isset($attr['rows'])) {
            $attr['rows'] = '3';
        }
        return $attr;
    }
}

// vim: et sw=4 sts=4
