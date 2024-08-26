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

namespace PleskX\Api\Struct\Ip;

use PleskX\Api\AbstractStruct;

class Info extends AbstractStruct
{
    public string $ipAddress;
    public string $netmask;
    public string $type;
    public string $interface;

    public function __construct(\SimpleXMLElement $apiResponse)
    {
        $this->initScalarProperties($apiResponse, [
            'ip_address',
            'netmask',
            'type',
            'interface',
        ]);
    }
}
