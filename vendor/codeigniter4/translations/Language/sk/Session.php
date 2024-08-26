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
    'missingDatabaseTable'   => '`sessionSavePath` musí mať tabuľku aby pracoval Database Session Handler.',
    'invalidSavePath'        => 'Session: Nastavená cesta uloženia "{0}" nie je zložka, neexistuje alebo nemôže byť vytvorená.',
    'writeProtectedSavePath' => 'Session: Nastavená cesta uloženia "{0}" nie je zapisovateľná cez PHP proces.',
    'emptySavePath'          => 'Session: Nie je nastavená cesta na uloženie.',
    'invalidSavePathFormat'  => 'Session: Neplatný formát Redis cesty: {0}',
    'invalidSameSiteSetting' => 'Session: SameSite nastavenie musí byť None, Lax, Strict, alebo prázdny reťazec. Zadané: {0}',
];
