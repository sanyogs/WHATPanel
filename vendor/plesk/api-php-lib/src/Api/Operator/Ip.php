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

namespace PleskX\Api\Operator;

use PleskX\Api\Struct\Ip as Struct;

class Ip extends \PleskX\Api\Operator
{
    /**
     * @return Struct\Info[]
     */
    public function get(): array
    {
        $ips = [];
        $packet = $this->client->getPacket();
        $packet->addChild($this->wrapperTag)->addChild('get');
        $response = $this->client->request($packet);

        foreach ($response->addresses->ip_info as $ipInfo) {
            $ips[] = new Struct\Info($ipInfo);
        }

        return $ips;
    }
}
