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

namespace PleskX\Api\Struct\Webspace;

use PleskX\Api\AbstractStruct;

class LimitDescriptor extends AbstractStruct
{
    public array $limits;

    public function __construct(\SimpleXMLElement $apiResponse)
    {
        $this->limits = [];

        foreach ($apiResponse->descriptor->property as $propertyInfo) {
            $this->limits[(string) $propertyInfo->name] = new LimitInfo($propertyInfo);
        }
    }
}
