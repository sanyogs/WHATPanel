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

namespace PleskX\Api\Struct\Ui;

use PleskX\Api\AbstractStruct;

class CustomButton extends AbstractStruct
{
    public int $id;
    public int $sortKey;
    public bool $public;
    public bool $internal;
    public bool $noFrame;
    public string $place;
    public string $url;
    public string $text;

    public function __construct(\SimpleXMLElement $apiResponse)
    {
        $this->initScalarProperties($apiResponse, ['id']);
        $this->initScalarProperties($apiResponse->properties, [
            'sort_key',
            'public',
            'internal',
            ['noframe' => 'noFrame'],
            'place',
            'url',
            'text',
        ]);
    }
}
