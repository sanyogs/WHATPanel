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

class LoadAverage extends AbstractStruct
{
    public float $load1min;
    public float $load5min;
    public float $load15min;

    public function __construct(\SimpleXMLElement $apiResponse)
    {
        $this->load1min = $apiResponse->l1 / 100.0;
        $this->load5min = $apiResponse->l5 / 100.0;
        $this->load15min = $apiResponse->l15 / 100.0;
    }
}
