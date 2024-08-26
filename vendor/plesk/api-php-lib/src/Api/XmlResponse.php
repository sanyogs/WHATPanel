<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Application Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */
// Copyright 1999-2023. Plesk International GmbH.

namespace PleskX\Api;

/**
 * XML wrapper for responses.
 */
class XmlResponse extends \SimpleXMLElement
{
    /**
     * Retrieve value by node name.
     *
     * @param string $node
     *
     * @return string
     */
    public function getValue(string $node): string
    {
        $result = $this->xpath('//' . $node);
        if (is_array($result) && isset($result[0])) {
            return (string) $result[0];
        }

        return '';
    }
}
