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
abstract class ExtendedInformationExtraField
{
    private const TAG = 0x0001;

    public static function generate(
        ?int $originalSize = null,
        ?int $compressedSize = null,
        ?int $relativeHeaderOffset = null,
        ?int $diskStartNumber = null,
    ): string {
        return PackField::pack(
            new PackField(format: 'v', value: self::TAG),
            new PackField(
                format: 'v',
                value:
                    ($originalSize === null ? 0 : 8) +
                    ($compressedSize === null ? 0 : 8) +
                    ($relativeHeaderOffset === null ? 0 : 8) +
                    ($diskStartNumber === null ? 0 : 4)
            ),
            ...($originalSize === null ? [] : [
                new PackField(format: 'P', value: $originalSize),
            ]),
            ...($compressedSize === null ? [] : [
                new PackField(format: 'P', value: $compressedSize),
            ]),
            ...($relativeHeaderOffset === null ? [] : [
                new PackField(format: 'P', value: $relativeHeaderOffset),
            ]),
            ...($diskStartNumber === null ? [] : [
                new PackField(format: 'V', value: $diskStartNumber),
            ]),
        );
    }
}
