<?php 

namespace Config;
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

use CodeIgniter\Config\BaseConfig;

class Locale extends BaseConfig
{
    /**
     * --------------------------------------------------------------------------
     * Default Locale
     * --------------------------------------------------------------------------
     *
     * The Locale determines the set of language-related settings, including
     * the language that should be used for translations and number formatting.
     * This value can be any of the locales provided by the framework.
     *
     * @var string
     */
    public $defaultLocale = 'en';

    /**
     * --------------------------------------------------------------------------
     * Supported Locales
     * --------------------------------------------------------------------------
     *
     * An array of all locales supported by your application. This helps
     * determine the set of languages and locales that your application can
     * work with.
     *
     * @var string[]
     */
    public $supportedLocales = ['en', 'fr', 'de', 'es'];

    /**
     * --------------------------------------------------------------------------
     * Locale Auto Detection
     * --------------------------------------------------------------------------
     *
     * Determines if the Locale should be automatically detected from the
     * user's browser settings.
     *
     * @var bool
     */
    public $negotiateLocale = true;

    /**
     * --------------------------------------------------------------------------
     * Locale Identifier
     * --------------------------------------------------------------------------
     *
     * Determines where the current Locale should be stored. If false, no
     * session data will be stored for the user's chosen Locale.
     *
     * @var string
     */
    public $supportedLocales = ['en', 'fr', 'de', 'es'];

    /**
     * --------------------------------------------------------------------------
     * Locale Auto Detection
     * --------------------------------------------------------------------------
     *
     * Determines if the Locale should be automatically detected from the
     * user's browser settings.
     *
     * @var bool
     */
    public $negotiateLocale = true;

    /**
     * --------------------------------------------------------------------------
     * Locale Identifier
     * --------------------------------------------------------------------------
     *
     * Determines where the current Locale should be stored. If false, no
     * session data will be stored for the user's chosen Locale.
     *
     * @var string
     */
    public $localeIdentifier = 'locale';

    /**
     * --------------------------------------------------------------------------
     * Locale Redirect
     * --------------------------------------------------------------------------
     *
     * Determines if the Locale should be redirected when the user changes
     * language preference.
     *
     * @var bool
     */
    public $redirectLocale = false;
}
