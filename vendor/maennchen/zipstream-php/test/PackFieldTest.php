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

namespace ZipStream\Test;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use ZipStream\PackField;

class PackFieldTest extends TestCase
{
    public function testPacksFields(): void
    {
        $this->assertSame(
            bin2hex(PackField::pack(new PackField(format: 'v', value: 0x1122))),
            '2211',
        );
    }

    public function testOverflow2(): void
    {
        $this->expectException(RuntimeException::class);

        PackField::pack(new PackField(format: 'v', value: 0xFFFFF));
    }

    public function testOverflow4(): void
    {
        $this->expectException(RuntimeException::class);

        PackField::pack(new PackField(format: 'V', value: 0xFFFFFFFFF));
    }

    public function testUnknownOperator(): void
    {
        $this->assertSame(
            bin2hex(PackField::pack(new PackField(format: 'a', value: 0x1122))),
            '34',
        );
    }
}
