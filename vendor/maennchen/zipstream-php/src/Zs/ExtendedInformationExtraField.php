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

namespace ZipStream\Zs;

use ZipStream\PackField;

/**
 * @internal
 */
abstract class ExtendedInformationExtraField
{
    private const TAG = 0x5653;

    public static function generate(): string
    {
        return PackField::pack(
            new PackField(format: 'v', value: self::TAG),
            new PackField(format: 'v', value: 0x0000),
        );
    }
}
