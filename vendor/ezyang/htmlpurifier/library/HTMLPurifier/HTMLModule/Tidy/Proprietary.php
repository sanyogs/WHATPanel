<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Application Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

class HTMLPurifier_HTMLModule_Tidy_Proprietary extends HTMLPurifier_HTMLModule_Tidy
{

    /**
     * @type string
     */
    public $name = 'Tidy_Proprietary';

    /**
     * @type string
     */
    public $defaultLevel = 'light';

    /**
     * @return array
     */
    public function makeFixes()
    {
        $r = array();
        $r['table@background'] = new HTMLPurifier_AttrTransform_Background();
        $r['td@background']    = new HTMLPurifier_AttrTransform_Background();
        $r['th@background']    = new HTMLPurifier_AttrTransform_Background();
        $r['tr@background']    = new HTMLPurifier_AttrTransform_Background();
        $r['thead@background'] = new HTMLPurifier_AttrTransform_Background();
        $r['tfoot@background'] = new HTMLPurifier_AttrTransform_Background();
        $r['tbody@background'] = new HTMLPurifier_AttrTransform_Background();
        $r['table@height']     = new HTMLPurifier_AttrTransform_Length('height');
        return $r;
    }
}

// vim: et sw=4 sts=4
