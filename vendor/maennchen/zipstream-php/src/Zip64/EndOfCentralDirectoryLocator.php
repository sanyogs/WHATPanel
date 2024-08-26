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
abstract class EndOfCentralDirectoryLocator
{
    private const SIGNATURE = 0x07064b50;

    public static function generate(
        int $numberOfTheDiskWithZip64CentralDirectoryStart,
        int $zip64centralDirectoryStartOffsetOnDisk,
        int $totalNumberOfDisks,
    ): string {
        /** @psalm-suppress MixedArgument */
        return PackField::pack(
            new PackField(format: 'V', value: static::SIGNATURE),
            new PackField(format: 'V', value: $numberOfTheDiskWithZip64CentralDirectoryStart),
            new PackField(format: 'P', value: $zip64centralDirectoryStartOffsetOnDisk),
            new PackField(format: 'V', value: $totalNumberOfDisks),
        );
    }
}
