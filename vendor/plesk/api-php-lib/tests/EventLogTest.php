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

class EventLogTest extends AbstractTestCase
{
    public function testGet()
    {
        $events = static::$client->eventLog()->get();
        $this->assertGreaterThan(0, $events);

        $event = reset($events);
        $this->assertGreaterThan(0, $event->time);
    }

    public function testGetDetailedLog()
    {
        $events = static::$client->eventLog()->getDetailedLog();
        $this->assertGreaterThan(0, $events);

        $event = reset($events);
        $this->assertGreaterThan(0, $event->time);
    }

    public function testGetLastId()
    {
        $lastId = static::$client->eventLog()->getLastId();
        $this->assertGreaterThan(0, $lastId);
    }
}
