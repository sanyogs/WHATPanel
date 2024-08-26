<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Application Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

declare(strict_types=1);

namespace ZipStream\Test\Zs;

use PHPUnit\Framework\TestCase;
use ZipStream\Zs\ExtendedInformationExtraField;

class ExtendedInformationExtraFieldTest extends TestCase
{
    public function testSerializesCorrectly(): void
    {
        $extraField = ExtendedInformationExtraField::generate();

        $this->assertSame(
            bin2hex((string) $extraField),
            '5356' . // 2 bytes; Tag for this "extra" block type
            '0000' // 2 bytes; TODO: Document
        );
    }
}
