<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Application Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */


namespace Twilio\Jwt\Grants;


interface Grant {
    /**
     * Returns the grant type
     *
     * @return string type of the grant
     */
    public function getGrantKey(): string;

    /**
     * Returns the grant data
     *
     * @return array data of the grant
     */
    public function getPayload(): array;
}
