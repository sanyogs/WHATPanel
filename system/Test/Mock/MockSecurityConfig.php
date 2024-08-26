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

namespace CodeIgniter\Test\Mock;

use Config\Security;

/**
 * @deprecated
 *
 * @codeCoverageIgnore
 */
class MockSecurityConfig extends Security
{
    public string $tokenName  = 'csrf_test_name';
    public string $headerName = 'X-CSRF-TOKEN';
    public string $cookieName = 'csrf_cookie_name';
    public int $expires       = 7200;
    public bool $regenerate   = true;
    public bool $redirect     = false;
    public string $samesite   = 'Lax';
    public $excludeURIs       = ['http://example.com'];
}
