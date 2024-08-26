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

use PleskX\Api\Struct\Locale as Struct;

class Locale extends \PleskX\Api\Operator
{
    /**
     * @param string|null $id
     *
     * @return Struct\Info|Struct\Info[]
     */
    public function get($id = null)
    {
        $locales = [];
        $packet = $this->client->getPacket();
        $filter = $packet->addChild($this->wrapperTag)->addChild('get')->addChild('filter');

        if (!is_null($id)) {
            $filter->addChild('id', $id);
        }

        $response = $this->client->request($packet, \PleskX\Api\Client::RESPONSE_FULL);

        foreach ($response->locale->get->result as $localeInfo) {
            $locales[(string) $localeInfo->info->id] = new Struct\Info($localeInfo->info);
        }

        return !is_null($id) ? reset($locales) : $locales;
    }
}
