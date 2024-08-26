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
    'missingDatabaseTable'   => '`sessionSavePath` tartalmaznia kell táblanevet az adatbázis munkamenet-kezelő működéséhez.',
    'invalidSavePath'        => 'Munkamenet: A beállított mentési útvonal "{0}" nem könyvtár, nem létezik vagy nem lehet létrehozni.',
    'writeProtectedSavePath' => 'Munkamenet: A beállított mentési útvonal "{0}" nem írható a PHP folyamat számára.',
    'emptySavePath'          => 'Munkamenet: Nincs beállított mentési útvonal.',
    'invalidSavePathFormat'  => 'Munkamenet: Érvénytelen Redis mentési útvonal formátum: {0}',

    // @deprecated
    'invalidSameSiteSetting' => 'Munkamenet: A SameSite beállításnak None, Lax, Strict vagy üres karakterláncnak kellene lennie. Megadva: {0}',
];
