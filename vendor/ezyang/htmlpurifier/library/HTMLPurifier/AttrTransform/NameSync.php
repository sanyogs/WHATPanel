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
 * Post-transform that performs validation to the name attribute; if
 * it is present with an equivalent id attribute, it is passed through;
 * otherwise validation is performed.
 */
class HTMLPurifier_AttrTransform_NameSync extends HTMLPurifier_AttrTransform
{

    /**
     * @type HTMLPurifier_AttrDef_HTML_ID
     */
    public $idDef;

    public function __construct()
    {
        $this->idDef = new HTMLPurifier_AttrDef_HTML_ID();
    }

    /**
     * @param array $attr
     * @param HTMLPurifier_Config $config
     * @param HTMLPurifier_Context $context
     * @return array
     */
    public function transform($attr, $config, $context)
    {
        if (!isset($attr['name'])) {
            return $attr;
        }
        $name = $attr['name'];
        if (isset($attr['id']) && $attr['id'] === $name) {
            return $attr;
        }
        $result = $this->idDef->validate($name, $config, $context);
        if ($result === false) {
            unset($attr['name']);
        } else {
            $attr['name'] = $result;
        }
        return $attr;
    }
}

// vim: et sw=4 sts=4
