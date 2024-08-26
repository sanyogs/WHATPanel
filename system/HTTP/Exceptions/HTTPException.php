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

namespace CodeIgniter\HTTP\Exceptions;

use CodeIgniter\Exceptions\FrameworkException;

/**
 * Things that can go wrong with HTTP
 */
class HTTPException extends FrameworkException
{
    /**
     * For CurlRequest
     *
     * @return HTTPException
     *
     * @codeCoverageIgnore
     */
    public static function forMissingCurl()
    {
        return new static(lang('hd_lang.HTTP.missingCurl'));
    }

    /**
     * For CurlRequest
     *
     * @return HTTPException
     */
    public static function forSSLCertNotFound(string $cert)
    {
        return new static(lang('hd_lang.HTTP.sslCertNotFound', [$cert]));
    }

    /**
     * For CurlRequest
     *
     * @return HTTPException
     */
    public static function forInvalidSSLKey(string $key)
    {
        return new static(lang('hd_lang.HTTP.invalidSSLKey', [$key]));
    }

    /**
     * For CurlRequest
     *
     * @return HTTPException
     *
     * @codeCoverageIgnore
     */
    public static function forCurlError(string $errorNum, string $error)
    {
        return new static(lang('hd_lang.HTTP.curlError', [$errorNum, $error]));
    }

    /**
     * For IncomingRequest
     *
     * @return HTTPException
     */
    public static function forInvalidNegotiationType(string $type)
    {
        return new static(lang('hd_lang.HTTP.invalidNegotiationType', [$type]));
    }

    /**
     * For Message
     *
     * @return HTTPException
     */
    public static function forInvalidHTTPProtocol(string $invalidVersion)
    {
        return new static(lang('hd_lang.HTTP.invalidHTTPProtocol', [$invalidVersion]));
    }

    /**
     * For Negotiate
     *
     * @return HTTPException
     */
    public static function forEmptySupportedNegotiations()
    {
        return new static(lang('hd_lang.HTTP.emptySupportedNegotiations'));
    }

    /**
     * For RedirectResponse
     *
     * @return HTTPException
     */
    public static function forInvalidRedirectRoute(string $route)
    {
        return new static(lang('hd_lang.HTTP.invalidRoute', [$route]));
    }

    /**
     * For Response
     *
     * @return HTTPException
     */
    public static function forMissingResponseStatus()
    {
        return new static(lang('hd_lang.HTTP.missingResponseStatus'));
    }

    /**
     * For Response
     *
     * @return HTTPException
     */
    public static function forInvalidStatusCode(int $code)
    {
        return new static(lang('hd_lang.HTTP.invalidStatusCode', [$code]));
    }

    /**
     * For Response
     *
     * @return HTTPException
     */
    public static function forUnkownStatusCode(int $code)
    {
        return new static(lang('hd_lang.HTTP.unknownStatusCode', [$code]));
    }

    /**
     * For URI
     *
     * @return HTTPException
     */
    public static function forUnableToParseURI(string $uri)
    {
        return new static(lang('hd_lang.HTTP.cannotParseURI', [$uri]));
    }

    /**
     * For URI
     *
     * @return HTTPException
     */
    public static function forURISegmentOutOfRange(int $segment)
    {
        return new static(lang('hd_lang.HTTP.segmentOutOfRange', [$segment]));
    }

    /**
     * For URI
     *
     * @return HTTPException
     */
    public static function forInvalidPort(int $port)
    {
        return new static(lang('hd_lang.HTTP.invalidPort', [$port]));
    }

    /**
     * For URI
     *
     * @return HTTPException
     */
    public static function forMalformedQueryString()
    {
        return new static(lang('hd_lang.HTTP.malformedQueryString'));
    }

    /**
     * For Uploaded file move
     *
     * @return HTTPException
     */
    public static function forAlreadyMoved()
    {
        return new static(lang('hd_lang.HTTP.alreadyMoved'));
    }

    /**
     * For Uploaded file move
     *
     * @return HTTPException
     */
    public static function forInvalidFile(?string $path = null)
    {
        return new static(lang('hd_lang.HTTP.invalidFile'));
    }

    /**
     * For Uploaded file move
     *
     * @return HTTPException
     */
    public static function forMoveFailed(string $source, string $target, string $error)
    {
        return new static(lang('hd_lang.HTTP.moveFailed', [$source, $target, $error]));
    }

    /**
     * For Invalid SameSite attribute setting
     *
     * @return HTTPException
     *
     * @deprecated Use `CookieException::forInvalidSameSite()` instead.
     *
     * @codeCoverageIgnore
     */
    public static function forInvalidSameSiteSetting(string $samesite)
    {
        return new static(lang('hd_lang.Security.invalidSameSiteSetting', [$samesite]));
    }
}
