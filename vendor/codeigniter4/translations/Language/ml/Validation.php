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

// Validation language settings
return [
    // Core Messages
    'noRuleSets'      => 'മൂല്യനിർണ്ണയ കോൺഫിഗറേഷനിൽ റൂൾസെറ്റും വ്യക്തമാക്കിയിട്ടില്ല. ',
    'ruleNotFound'    => '{0} സാധുവായ ഒരു നിയമമല്ല. ',
    'groupNotFound'   => '{0} മൂല്യനിർണ്ണയ നിയമ ഗ്രൂപ്പ് അല്ല. ',
    'groupNotArray'   => '{0} റൂൾ ഗ്രൂപ്പ് ഒരു അറേ ആയിരിക്കണം. ',
    'invalidTemplate' => '{0} സാധുവായ നിയമപരമായ ടെംപ്ലേറ്റായല്ല. ',

    // Rule Messages
    'alpha'                 => '{field} ഫീൽഡിൽ അക്ഷരമാലാക്രമങ്ങൾ മാത്രമേയുള്ളൂ. ',
    'alpha_dash'            => '{field} ഫീൽഡിൽ ആൽഫാന്യൂമെറിക്, അടിവരയിടുന്നതും തകർന്നതുമായ പ്രതീകങ്ങൾ മാത്രമേ അടങ്ങിയിട്ടുള്ളൂ. ',
    'alpha_numeric'         => '{field} ഫീൽഡിൽ ആൽഫാന്യൂമെറിക് പ്രതീകങ്ങൾ മാത്രം അടങ്ങിയിരിക്കാം. ',
    'alpha_numeric_punct'   => '{field} ഫീൽഡിന് ആൽഫാന്യൂമെറിക് പ്രതീകങ്ങളും സ്പെയ്സുകളും ~!# $%, * - _ + = |: പ്രതീകം. ',
    'alpha_numeric_space'   => '{field} ഫീൽഡിന് ആൽഫാന്യൂമെറിക്, സ്പേസ് പ്രതീകങ്ങൾ മാത്രമേ അടങ്ങിയിട്ടുള്ളൂ. ',
    'alpha_space'           => '{field} ഫീൽഡിൽ അക്ഷരമാലാക്രമങ്ങളും സ്പെയ്സുകളും മാത്രം അടങ്ങിയിരിക്കാം. ',
    'decimal'               => '{field} വയലിൽ ഒരു ദശാംശ നമ്പർ ഉണ്ടായിരിക്കണം. ',
    'differs'               => '{field} ഫീൽഡ് {param} ഫീൽഡിൽ നിന്ന് വ്യത്യസ്തമായിരിക്കണം. ',
    'equals'                => '{field} ഫീൽഡ് ആയിരിക്കണം: {param}. ',
    'exact_length'          => '{field} ഫീൽഡ് കൃത്യമായി {param} പ്രതീകങ്ങൾ ആയിരിക്കണം. ',
    'greater_than'          => '{field} ഫീൽഡിന് {param} നേക്കാൾ വലുതാണ്}. ',
    'greater_than_equal_to' => '{field} കേസുകളിൽ {param} എന്നതിനേക്കാൾ വലുതോ തുല്യമോ ഉണ്ടായിരിക്കണം. ',
    'hex'                   => '{field} ഫീൽഡിൽ ഹെക്സിഡേസെമൽ പ്രതീകങ്ങൾ മാത്രമേ അടങ്ങിയിട്ടുള്ളൂ. ',
    'in_list'               => '{field} ഫീൽഡ് ഒരു ആയിരിക്കണം: {param}. ',
    'integer'               => '{field} വയലിൽ ഒരു പൂർണ്ണ സംഖ്യ ഉണ്ടായിരിക്കണം. ',
    'is_natural'            => '{field} ഫീൽഡിൽ അക്കങ്ങൾ മാത്രം അടങ്ങിയിരിക്കണം. ',
    'is_natural_no_zero'    => '{field} ഫീൽഡിൽ അക്കങ്ങൾ മാത്രം അടങ്ങിയിരിക്കണം, പൂജ്യത്തേക്കാൾ വലുതായിരിക്കും. ',
    'is_not_unique'         => '{field} ഫീൽഡിന് മുമ്പ് ഡാറ്റാബേസിൽ നിലവിലുള്ള ഒരു മൂല്യം ഉണ്ടായിരിക്കണം. ',
    'is_unique'             => '{field} കേസിൽ ഒരു അദ്വിതീയ മൂല്യം ഉണ്ടായിരിക്കണം. ',
    'less_than'             => '{field} ഫീൽഡിന് param}-ൽ കുറവായിരിക്കണം}. ',
    'less_than_equal_to'    => '{field} ഫീൽഡിന് {param} ന് കുറവോ തുല്യമോ ഉണ്ടായിരിക്കണം. ',
    'matches'               => '{field} ഫീൽഡ് {പരമ} ഫീൽഡിനുമായി പൊരുത്തപ്പെടുന്നില്ല. ',
    'max_length'            => '{field} വയലിന്റെ ദൈർഘ്യം param} പ്രതീകങ്ങളേക്കാൾ കൂടുതൽ കഴിയില്ല. ',
    'min_length'            => '{field} ഫീൽഡ് ദൈർഘ്യം കുറഞ്ഞത് {param} അക്ഷരങ്ങളായിരിക്കണം. ',
    'not_equals'            => '{field} ഫീൽഡ് ആകാൻ കഴിയില്ല: {param}. ',
    'not_in_list'           => '{field} ഫീൽഡ് ഒന്നായിരിക്കരുത്: {param}. ',
    'numeric'               => '{field} ഫീൽഡിൽ അക്കങ്ങൾ മാത്രം അടങ്ങിയിരിക്കണം. ',
    'regex_match'           => '{field} ഫീൽഡ് ശരിയായ ഫോർമാറ്റിലല്ല. ',
    'required'              => '{field} ഫീൽഡ് ആവശ്യമാണ്. ',
    'required_with'         => '{param} ഉള്ളപ്പോൾ {field} ഫീൽഡ് ആവശ്യമാണ്.',
    'required_without'      => '{param} ഇല്ലാത്തപ്പോൾ {field} ഫീൽഡ് ആവശ്യമാണ്.',
    'string'                => '{field} ഫീൽഡ് സാധുവായ ഒരു സ്ട്രിംഗ് ആയിരിക്കണം. ',
    'timezone'              => '{field} ഫീൽഡ് ഒരു സാധുവായ ടിംസോൺ ആയിരിക്കണം. ',
    'valid_base64'          => '{field} ഫീൽഡ് ഒരു സാധുവായ ബേസ് 64 സ്ട്രിംഗ് ആയിരിക്കണം. ',
    'valid_email'           => '{field} ഫീൽഡിൽ സാധുവായ ഒരു ഇമെയിൽ വിലാസം അടങ്ങിയിരിക്കണം. ',
    'valid_emails'          => '{field} ഫീൽഡിലും സാധുവായ എല്ലാ ഇമെയിൽ വിലാസങ്ങളും അടങ്ങിയിരിക്കണം. ',
    'valid_ip'              => '{field} ഫീൽഡിൽ സാധുവായ ഐപി ഉണ്ടായിരിക്കണം. ',
    'valid_url'             => '{field} വയലിൽ സാധുവായ ഒരു URL ഉണ്ടായിരിക്കണം. ',
    'valid_url_strict'      => '{field} വയലിൽ സാധുവായ ഒരു URL ഉണ്ടായിരിക്കണം. ',
    'valid_date'            => '{field} ഫീൽഡിൽ സാധുവായ ഒരു തീയതി ഉണ്ടായിരിക്കണം. ',
    'valid_json'            => '{field} വയലിൽ സാധുവായ JSN ഉണ്ടായിരിക്കണം. ',

    // Credit Cards
    'valid_cc_num' => '{ক্ষেত্র} സാധുവായ ക്രെഡിറ്റ് കാർഡ് നമ്പറാണെന്ന് തോന്നുന്നില്ല.',

    // Files
    'uploaded' => '{field} സാധുവായ അപ്ലോഡ് ചെയ്ത ഫയലല്ല.',
    'max_size' => '{field} ഒരു ഫയലിനേക്കാൾ വലുതാണ്.',
    'is_image' => '{field} സാധുവായ, അപ്ലോഡ് ചെയ്ത ചിത്രം ഫയൽ അല്ല.',
    'mime_in'  => '{field} ഇതിന് സാധുവായ MIME തരം ഇല്ല.',
    'ext_in'   => '{field} ഇതിന് സാധുവായ ഫയൽ വിപുലീകരണമൊന്നുമില്ല.',
    'max_dims' => '{field} ഒരു ഇമേജല്ല, അല്ലെങ്കിൽ അത് വളരെ വിശാലമോ ഉയരമോ ആണ്',
];
