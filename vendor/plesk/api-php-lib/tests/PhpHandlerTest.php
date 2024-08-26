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

namespace PleskXTest;

class PhpHandlerTest extends AbstractTestCase
{
    public function testGet()
    {
        $handler = static::$client->phpHandler()->get();

        $this->assertTrue(property_exists($handler, 'type'));
    }

    public function testGetAll()
    {
        $handlers = static::$client->phpHandler()->getAll();

        $this->assertIsArray($handlers);
        $this->assertNotEmpty($handlers);

        $handler = current($handlers);

        $this->assertIsObject($handler);
        $this->assertTrue(property_exists($handler, 'type'));
    }

    public function testGetUnknownHandlerThrowsException()
    {
        $this->expectException(\PleskX\Api\Exception::class);
        $this->expectExceptionMessage('Php handler does not exists');

        static::$client->phpHandler()->get('id', 'this-handler-does-not-exist');
    }
}
