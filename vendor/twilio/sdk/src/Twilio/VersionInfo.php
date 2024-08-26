<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Application Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */


namespace Twilio;


class VersionInfo {
    const MAJOR = "7";
    const MINOR = "16";
    const PATCH = "1";

    public static function string() {
        return implode('.', array(self::MAJOR, self::MINOR, self::PATCH));
    }
}
