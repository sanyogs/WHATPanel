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


class Response {
    protected $headers;
    protected $content;
    protected $statusCode;

    public function __construct(int $statusCode, ?string $content, ?array $headers = []) {
        $this->statusCode = $statusCode;
        $this->content = $content;
        $this->headers = $headers;
    }

    /**
     * @return mixed
     */
    public function getContent() {
        return \json_decode($this->content, true);
    }

    public function getStatusCode(): int {
        return $this->statusCode;
    }

    public function getHeaders(): array {
        return $this->headers;
    }

    public function ok(): bool {
        return $this->getStatusCode() < 400;
    }

    public function __toString(): string {
        return '[Response] HTTP ' . $this->getStatusCode() . ' ' . $this->content;
    }
}
