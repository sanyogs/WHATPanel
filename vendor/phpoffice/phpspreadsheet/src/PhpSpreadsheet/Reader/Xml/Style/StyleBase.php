<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Application Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

namespace PhpOffice\PhpSpreadsheet\Reader\Xml\Style;

use SimpleXMLElement;

abstract class StyleBase
{
    protected static function identifyFixedStyleValue(array $styleList, string &$styleAttributeValue): bool
    {
        $returnValue = false;

        $styleAttributeValue = strtolower($styleAttributeValue);
        foreach ($styleList as $style) {
            if ($styleAttributeValue == strtolower($style)) {
                $styleAttributeValue = $style;
                $returnValue = true;

                break;
            }
        }

        return $returnValue;
    }

    protected static function getAttributes(?SimpleXMLElement $simple, string $node): SimpleXMLElement
    {
        return ($simple === null) ? new SimpleXMLElement('<xml></xml>') : ($simple->attributes($node) ?? new SimpleXMLElement('<xml></xml>'));
    }
}
