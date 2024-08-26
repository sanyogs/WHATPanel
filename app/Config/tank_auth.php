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

use Config\Services;

use CodeIgniter\Config\BaseConfig;

class tank_auth extends BaseConfig
{

/*
|--------------------------------------------------------------------------
| Website details
|
| These details are used in emails sent by the authentication library.
|--------------------------------------------------------------------------
*/
public $config = [
    'website_name' => 'Hosting Domain',
    'webmaster_email' => 'info@hostingdomain.co.za',

    /*
    |--------------------------------------------------------------------------
    | Security settings
    |--------------------------------------------------------------------------
    */
    'phpass_hash_portable' => true,
    'phpass_hash_strength' => 8,

    /*
    |--------------------------------------------------------------------------
    | Registration settings
    |--------------------------------------------------------------------------
    */
    'allow_registration' => true,
    'captcha_login' => true,
    'email_activation' => false,
    'email_activation_expire' => 60 * 60 * 24 * 2,
    'email_account_details' => true,
    'use_username' => true,

    'username_min_length' => 4,
    'username_max_length' => 20,
    'password_min_length' => 4,
    'password_max_length' => 20,

    /*
    |--------------------------------------------------------------------------
    | Login settings
    |--------------------------------------------------------------------------
    */
    'login_by_username' => true,
    'login_by_email' => true,
    'login_record_ip' => true,
    'login_record_time' => true,
    'login_count_attempts' => true,
    'login_max_attempts' => 15,
    'login_attempt_expire' => 60 * 60 * 24,

    /*
    |--------------------------------------------------------------------------
    | Auto login settings
    |--------------------------------------------------------------------------
    */
    'autologin_cookie_name' => '_fo_autologin',
    'autologin_cookie_life' => 60 * 60 * 24 * 31 * 2,

    /*
    |--------------------------------------------------------------------------
    | Forgot password settings
    |--------------------------------------------------------------------------
    */
    'forgot_password_expire' => 60 * 60 * 24,

    /*
    |--------------------------------------------------------------------------
    | Captcha
    |--------------------------------------------------------------------------
    */
    'captcha_path' => 'resource/captcha/',
    'captcha_fonts_path' => 'resource/captcha.fonts/4.ttf',
    'captcha_width' => 200,
    'captcha_height' => 40,
    'captcha_font_size' => 18,
    'captcha_grid' => false,
    'captcha_expire' => 180,
    'captcha_case_sensitive' => false,

    /*
    |--------------------------------------------------------------------------
    | reCAPTCHA
    |--------------------------------------------------------------------------
    */
    // 'use_recaptcha' => true,

    /*
    |--------------------------------------------------------------------------
    | Database settings
    |--------------------------------------------------------------------------
    */
    'db_table_prefix' => 'hd_',
];

}
