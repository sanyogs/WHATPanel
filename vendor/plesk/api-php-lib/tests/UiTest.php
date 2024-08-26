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

class UiTest extends AbstractTestCase
{
    private array $customButtonProperties = [
        'place' => 'admin',
        'url' => 'http://example.com',
        'text' => 'Example site',
    ];

    public function testGetNavigation()
    {
        $navigation = static::$client->ui()->getNavigation();
        $this->assertIsArray($navigation);
        $this->assertGreaterThan(0, count($navigation));
        $this->assertArrayHasKey('general', $navigation);
        $this->assertArrayHasKey('hosting', $navigation);

        $hostingSection = $navigation['hosting'];
        $this->assertArrayHasKey('name', $hostingSection);
        $this->assertArrayHasKey('nodes', $hostingSection);
        $this->assertGreaterThan(0, count($hostingSection['nodes']));
    }

    public function testCreateCustomButton()
    {
        $buttonId = static::$client->ui()->createCustomButton('admin', $this->customButtonProperties);
        $this->assertGreaterThan(0, $buttonId);

        static::$client->ui()->deleteCustomButton($buttonId);
    }

    public function testGetCustomButton()
    {
        $buttonId = static::$client->ui()->createCustomButton('admin', $this->customButtonProperties);
        $customButtonInfo = static::$client->ui()->getCustomButton($buttonId);
        $this->assertEquals('http://example.com', $customButtonInfo->url);
        $this->assertEquals('Example site', $customButtonInfo->text);

        static::$client->ui()->deleteCustomButton($buttonId);
    }

    public function testDeleteCustomButton()
    {
        $buttonId = static::$client->ui()->createCustomButton('admin', $this->customButtonProperties);
        $result = static::$client->ui()->deleteCustomButton($buttonId);
        $this->assertTrue($result);
    }
}
