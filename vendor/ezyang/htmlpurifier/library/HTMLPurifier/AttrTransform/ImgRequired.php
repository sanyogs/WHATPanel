<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Application Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

// must be called POST validation

/**
 * Transform that supplies default values for the src and alt attributes
 * in img tags, as well as prevents the img tag from being removed
 * because of a missing alt tag. This needs to be registered as both
 * a pre and post attribute transform.
 */
class HTMLPurifier_AttrTransform_ImgRequired extends HTMLPurifier_AttrTransform
{

    /**
     * @param array $attr
     * @param HTMLPurifier_Config $config
     * @param HTMLPurifier_Context $context
     * @return array
     */
    public function transform($attr, $config, $context)
    {
        $src = true;
        if (!isset($attr['src'])) {
            if ($config->get('Core.RemoveInvalidImg')) {
                return $attr;
            }
            $attr['src'] = $config->get('Attr.DefaultInvalidImage');
            $src = false;
        }

        if (!isset($attr['alt'])) {
            if ($src) {
                $alt = $config->get('Attr.DefaultImageAlt');
                if ($alt === null) {
                    $attr['alt'] = basename($attr['src']);
                } else {
                    $attr['alt'] = $alt;
                }
            } else {
                $attr['alt'] = $config->get('Attr.DefaultInvalidImageAlt');
            }
        }
        return $attr;
    }
}

// vim: et sw=4 sts=4
