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

namespace ZipStream\Zip64;

use ZipStream\PackField;

/**
 * @internal
 */
abstract class DataDescriptor
{
    private const SIGNATURE = 0x08074b50;

    public static function generate(
        int $crc32UncompressedData,
        int $compressedSize,
        int $uncompressedSize,
    ): string {
        return PackField::pack(
            new PackField(format: 'V', value: self::SIGNATURE),
            new PackField(format: 'V', value: $crc32UncompressedData),
            new PackField(format: 'P', value: $compressedSize),
            new PackField(format: 'P', value: $uncompressedSize),
        );
    }
}
