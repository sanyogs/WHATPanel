<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

/**
 * This file is part of CodeIgniter 4 framework.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace CodeIgniter\I18n\Exceptions;

use CodeIgniter\Exceptions\FrameworkException;

/**
 * I18nException
 */
class I18nException extends FrameworkException
{
    /**
     * Thrown when createFromFormat fails to receive a valid
     * DateTime back from DateTime::createFromFormat.
     *
     * @return static
     */
    public static function forInvalidFormat(string $format)
    {
        return new static(lang('hd_lang.Time.invalidFormat', [$format]));
    }

    /**
     * Thrown when the numeric representation of the month falls
     * outside the range of allowed months.
     *
     * @return static
     */
    public static function forInvalidMonth(string $month)
    {
        return new static(lang('hd_lang.Time.invalidMonth', [$month]));
    }

    /**
     * Thrown when the supplied day falls outside the range
     * of allowed days.
     *
     * @return static
     */
    public static function forInvalidDay(string $day)
    {
        return new static(lang('hd_lang.Time.invalidDay', [$day]));
    }

    /**
     * Thrown when the day provided falls outside the allowed
     * last day for the given month.
     *
     * @return static
     */
    public static function forInvalidOverDay(string $lastDay, string $day)
    {
        return new static(lang('hd_lang.Time.invalidOverDay', [$lastDay, $day]));
    }

    /**
     * Thrown when the supplied hour falls outside the
     * range of allowed hours.
     *
     * @return static
     */
    public static function forInvalidHour(string $hour)
    {
        return new static(lang('hd_lang.Time.invalidHour', [$hour]));
    }

    /**
     * Thrown when the supplied minutes falls outside the
     * range of allowed minutes.
     *
     * @return static
     */
    public static function forInvalidMinutes(string $minutes)
    {
        return new static(lang('hd_lang.Time.invalidMinutes', [$minutes]));
    }

    /**
     * Thrown when the supplied seconds falls outside the
     * range of allowed seconds.
     *
     * @return static
     */
    public static function forInvalidSeconds(string $seconds)
    {
        return new static(lang('hd_lang.Time.invalidSeconds', [$seconds]));
    }
}
