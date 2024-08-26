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

class SessionTest extends AbstractTestCase
{
    public function testCreate()
    {
        $sessionToken = static::$client->session()->create('admin', '127.0.0.1');

        $this->assertIsString($sessionToken);
        $this->assertGreaterThan(10, strlen($sessionToken));
    }

    public function testGet()
    {
        $sessionId = static::$client->server()->createSession('admin', '127.0.0.1');
        $sessions = static::$client->session()->get();
        $this->assertArrayHasKey($sessionId, $sessions);

        $sessionInfo = $sessions[$sessionId];
        $this->assertEquals('admin', $sessionInfo->login);
        $this->assertEquals('127.0.0.1', $sessionInfo->ipAddress);
        $this->assertEquals($sessionId, $sessionInfo->id);
    }

    public function testTerminate()
    {
        $sessionId = static::$client->server()->createSession('admin', '127.0.0.1');
        static::$client->session()->terminate($sessionId);
        $sessions = static::$client->session()->get();
        $this->assertArrayNotHasKey($sessionId, $sessions);
    }
}
