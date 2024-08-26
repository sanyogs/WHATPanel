<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Application Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

namespace Psr\Http\Message;

interface ResponseFactoryInterface
{
    /**
     * Create a new response.
     *
     * @param int $code HTTP status code; defaults to 200
     * @param string $reasonPhrase Reason phrase to associate with status code
     *     in generated response; if none is provided implementations MAY use
     *     the defaults as suggested in the HTTP specification.
     *
     * @return ResponseInterface
     */
    public function createResponse(int $code = 200, string $reasonPhrase = ''): ResponseInterface;
}
