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

class IpTest extends AbstractTestCase
{
    public function testGet()
    {
        $ips = static::$client->ip()->get();
        $this->assertGreaterThan(0, count($ips));

        $ip = reset($ips);
        $this->assertMatchesRegularExpression('/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/', $ip->ipAddress);
    }
}
