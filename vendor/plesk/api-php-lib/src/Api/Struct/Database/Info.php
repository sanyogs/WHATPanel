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

namespace PleskX\Api\Struct\Database;

use PleskX\Api\AbstractStruct;

class Info extends AbstractStruct
{
    public int $id;
    public string $name;
    public string $type;
    public int $webspaceId;
    public int $dbServerId;
    public int $defaultUserId;

    public function __construct(\SimpleXMLElement $apiResponse)
    {
        $this->initScalarProperties($apiResponse, [
            'id',
            'name',
            'type',
            'webspace-id',
            'db-server-id',
            'default-user-id',
        ]);
    }
}
