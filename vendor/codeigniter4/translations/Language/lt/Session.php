<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Application Terminal Panel.
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

// Session language settings
return [
    'missingDatabaseTable'   => 'Norint, kad duomenų bazės doroklė veiktų, `sessionSavePath` turi turėti duomenų bazės lentelės pavadinimą.',
    'invalidSavePath'        => 'Sesija: konfigūracijoje nustatytas įrašymo kelias „{0}“ nėra direktorija, neegzistuoja arba negali būti sukurtas.',
    'writeProtectedSavePath' => 'Sesija: konfigūracijoje nustatytas įrašymo kelias „{0}“ nėra prieinamas php procesui įrašymui.',
    'emptySavePath'          => 'Sesija: konfigūracijoje nenustatytas įrašymo kelias.',
    'invalidSavePathFormat'  => 'Sesija: negaliojantis Redis įrašymo kelio formatas: {0}',

    // @deprecated
    'invalidSameSiteSetting' => 'Sesija: SameSite nustatymas turi būti None, Lax, Strict arba tuščia eilutė. Pateikta: {0}',
];
