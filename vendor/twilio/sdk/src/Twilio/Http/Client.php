<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Application Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */


namespace Twilio\Http;


interface Client {
    public function request(string $method, string $url,
                            array $params = [], array $data = [], array $headers = [],
                            string $user = null, string $password = null,
                            int $timeout = null): Response;
}
