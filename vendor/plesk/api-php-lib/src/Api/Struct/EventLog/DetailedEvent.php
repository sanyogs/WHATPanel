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

namespace PleskX\Api\Struct\EventLog;

use PleskX\Api\AbstractStruct;

class DetailedEvent extends AbstractStruct
{
    public int $id;
    public string $type;
    public int $time;
    public string $class;
    public string $objectId;
    public string $user;
    public string $host;

    public function __construct(\SimpleXMLElement $apiResponse)
    {
        $this->initScalarProperties($apiResponse, [
            'id',
            'type',
            'time',
            'class',
            ['obj_id' => 'objectId'],
            'user',
            'host',
        ]);
    }
}
