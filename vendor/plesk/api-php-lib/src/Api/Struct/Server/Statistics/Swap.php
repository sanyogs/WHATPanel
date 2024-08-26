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

namespace PleskX\Api\Struct\Server\Statistics;

use PleskX\Api\AbstractStruct;

class Swap extends AbstractStruct
{
    public int $total;
    public int $used;
    public int $free;

    public function __construct(\SimpleXMLElement $apiResponse)
    {
        $this->initScalarProperties($apiResponse, [
            'total',
            'used',
            'free',
        ]);
    }
}
