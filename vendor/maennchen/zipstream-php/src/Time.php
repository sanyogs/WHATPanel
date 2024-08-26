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

namespace ZipStream;

use DateInterval;
use DateTimeImmutable;
use DateTimeInterface;
use ZipStream\Exception\DosTimeOverflowException;

/**
 * @internal
 */
abstract class Time
{
    private const DOS_MINIMUM_DATE = '1980-01-01 00:00:00Z';

    public static function dateTimeToDosTime(DateTimeInterface $dateTime): int
    {
        $dosMinimumDate = new DateTimeImmutable(self::DOS_MINIMUM_DATE);

        if ($dateTime->getTimestamp() < $dosMinimumDate->getTimestamp()) {
            throw new DosTimeOverflowException(dateTime: $dateTime);
        }

        $dateTime = DateTimeImmutable::createFromInterface($dateTime)->sub(new DateInterval('P1980Y'));

        ['year' => $year,
            'mon' => $month,
            'mday' => $day,
            'hours' => $hour,
            'minutes' => $minute,
            'seconds' => $second
        ] = getdate($dateTime->getTimestamp());

        return
            ($year << 25) |
            ($month << 21) |
            ($day << 16) |
            ($hour << 11) |
            ($minute << 5) |
            ($second >> 1);
    }
}
