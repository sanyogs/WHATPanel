<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Application Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

namespace PhpOffice\PhpSpreadsheet\Style\NumberFormat\Wizard;

use NumberFormatter;
use PhpOffice\PhpSpreadsheet\Exception;

final class Locale
{
    /**
     * Language code: ISO-639 2 character, alpha.
     * Optional script code: ISO-15924 4 alpha.
     * Optional country code: ISO-3166-1, 2 character alpha.
     * Separated by underscores or dashes.
     */
    public const STRUCTURE = '/^(?P<language>[a-z]{2})([-_](?P<script>[a-z]{4}))?([-_](?P<country>[a-z]{2}))?$/i';

    private NumberFormatter $formatter;

    public function __construct(?string $locale, int $style)
    {
        if (class_exists(NumberFormatter::class) === false) {
            throw new Exception();
        }

        $formatterLocale = str_replace('-', '_', $locale ?? '');
        $this->formatter = new NumberFormatter($formatterLocale, $style);
        if ($this->formatter->getLocale() !== $formatterLocale) {
            throw new Exception("Unable to read locale data for '{$locale}'");
        }
    }

    public function format(): string
    {
        return $this->formatter->getPattern();
    }
}
