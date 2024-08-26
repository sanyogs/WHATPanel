<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

namespace Config;

use CodeIgniter\Config\BaseConfig;

class MyConfig extends BaseConfig
{
    public $default_currency = 'USD';

    public $default_language = 'en';

    public $site_favicon = "";

    public $site_appleicon = "";

    public $locale = 'en_US';

    public $currencyPosition = "";

    public $currencyDecimals = 0.00;

    public $decimalSeparator = "";

    public $thousandSeparator = "";

    public $invoice_start_no = "";

    public $date_php_format = "yyyy-mm-dd";

    public $invoice_prefix = "";
}