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

// Migration language settings
return [
    // Migration Runner
    'missingTable'  => 'മൈഗ്രേഷൻ പട്ടിക അവശ്യം സജ്ജമാക്കണം.',
    'disabled'      => 'മൈഗ്രേഷൻ ലോഡ് ചെയ്യപ്പെട്ടു, പക്ഷേ അപ്രാപ്തമാക്കിയിരിക്കുകയോ തെറ്റായി സജ്ജമാക്കിയിരിക്കുകയോ ചെയ്യപ്പെട്ടു.',
    'notFound'      => 'മൈഗ്രേഷൻ ഫയൽ കണ്ടെത്തിയില്ല:',
    'batchNotFound' => 'ലക്ഷ്യമാക്കിയ ബാച്ച് കണ്ടെത്തിയില്ല:',
    'empty'         => 'ഒരു മൈഗ്രേഷൻ ഫയൽ കണ്ടെത്തിയില്ല',
    'gap'           => 'സംസ്കരണ നമ്പർക്കു മുകളിൽ ഒരു ഫലം ഉണ്ടാകുന്നു:',
    'classNotFound' => 'മൈഗ്രേഷൻ ക്ലാസ് "%s" കണ്ടെത്താനായില്ല.',
    'missingMethod' => 'മൈഗ്രേഷൻ ക്ലാസിൽ "%s" പദ്ധതി അനുവദനീയമല്ല.',

    // Migration Command
    'migHelpLatest'   => "\t\t അവസാന ലഭ്യമായ മൈഗ്രേഷൻ ഉപയോഗിച്ച് ഡാറ്റാബേസ് മൈഗ്രേറ്റുചെയ്യുക।",
    'migHelpCurrent'  => "\t\t കോൺഫിഗറേഷനിലേക്ക് 'കറന്റ്' ആയി സജ്ജമാക്കിയ പതിപ്പിലേക്ക് ഡാറ്റാബേസ് കൈമാറി.",
    'migHelpVersion'  => "\t {V} പതിപ്പിലേക്ക് ഡാറ്റാബേസ് കൈമാറുക.",
    'migHelpRollback' => "\tഎല്ലാ മൈഗ്രേഷനുകളും 'താഴേക്ക്' പതിപ്പ് 0 ൽ പ്രവർത്തിക്കുന്നു.",
    'migHelpRefresh'  => "\t\tഡാറ്റാബേസ് പുതുക്കുന്നതിന് എല്ലാ മൈഗ്രേഷനുകളും അൺഇൻസ്റ്റാൾ ചെയ്യുന്നു.",
    'migHelpSeed'     => "\t [പേര്] പേരിന്റെ പേര് പ്രവർത്തിപ്പിക്കുന്നു. ",
    'migCreate'       => "\t [പേര്] വിളിച്ച ഒരു പുതിയ മൈഗ്രേഷൻ സൃഷ്ടിക്കുന്നു",
    'nameMigration'   => 'മൈഗ്രേഷൻ ഫയലിന്റെ പേര് ',
    'migNumberError'  => 'മൈഗ്രേഷൻ നമ്പർ മൂന്ന് അക്കങ്ങളായിരിക്കണം, ശ്രേണിയിൽ വിടവ് ഉണ്ടാകരുത്. ',
    'rollBackConfirm' => 'നിങ്ങൾക്ക് റോൾബാക്ക് വേണമെന്ന് ഉറപ്പാണോ? ',
    'refreshConfirm'  => 'നിങ്ങൾ പുതുക്കാൻ ആഗ്രഹിക്കുന്നുണ്ടോ? ',

    'latest'            => 'എല്ലാ പുതിയ മൈഗ്രേഷനും നടക്കുന്നു ... ',
    'generalFault'      => 'മൈഗ്രേഷൻ പരാജയപ്പെട്ടു! ',
    'migrated'          => 'മൈഗ്രേഷൻ പൂർത്തിയായി. ',
    'migInvalidVersion' => 'അസാധുവായ പതിപ്പ് നമ്പർ നൽകിയിട്ടുണ്ട്. ',
    'toVersionPH'       => '%s യുടെ പതിപ്പിലേക്ക് മാറ്റുന്നു ... ',
    'toVersion'         => 'നിലവിലെ പതിപ്പിലേക്ക് മാറ്റുന്നത് ... ',
    'rollingBack'       => 'ബാച്ചിംഗ് മൈഗ്രേഷൻ തിരികെ കൊണ്ടുവരുന്നു: ',
    'noneFound'         => 'കൈമാറ്റം കണ്ടെത്തിയില്ല. ',
    'migSeeder'         => 'വിത്തുകളുടെ പേര് ',
    'migMissingSeeder'  => 'നിങ്ങൾ ഒരു സൈഡറിന്റെ പേര് നൽകണം. ',
    'nameSeeder'        => 'സിഡാർ ഫയലിന്റെ പേര് ',
    'removed'           => 'തിരികെ ഉരുളുന്നു: ',
    'added'             => 'പ്രവർത്തിക്കുന്ന: ',

    // Migrate Status
    'namespace' => 'നെയിംസ്പെയ്സ് ',
    'filename'  => 'ഫയലിന്റെ പേര് ',
    'version'   => 'പതിപ്പ് ',
    'group'     => 'ഗ്രൂപ്പ് ',
    'on'        => 'മൈഗ്രേറ്റ് ചെയ്തത്: ',
    'batch'     => 'ബാച്ച് ',
];
