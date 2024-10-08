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
    'noRuleSets'      => 'Doğrulama ayarlarında kural kümesi tanımlanmamış.',
    'ruleNotFound'    => '{0} geçerli bir kural değil.',
    'groupNotFound'   => '{0} geçerli bir kural grubu değil.',
    'groupNotArray'   => '{0} kural grubu bir dizi olmalı.',
    'invalidTemplate' => '{0} geçerli bir doğrulama şablonu değil.',

    // Rule Messages
    'alpha'                 => '{field} alanı yalnız alfabetik karakterler içerebilir.',
    'alpha_dash'            => '{field} alanı sadece alfasayısal, alt çizgi ve tire karakterleri içerebilir.',
    'alpha_numeric'         => '{field} alanı sadece alfasayısal karakterler içerebilir.',
    'alpha_numeric_punct'   => '{field} alanı sadece alfasayısal karakterler, boşluklar ve ~ ! # $ % & * - _ + = | : . karakterleri içerebilir.',
    'alpha_numeric_space'   => '{field} alanı sadece alfasayısal karakterler ve boşluk içerebilir.',
    'alpha_space'           => '{field} alanı sadece alfabetik karakterler ve boşluklar içerebilir.',
    'decimal'               => '{field} alanı bir sayı içermeli.',
    'differs'               => '{field} alanı {param} alanından farklı olmalı.',
    'equals'                => '{field} alanı {param} alanı ile eşit olmalı.',
    'exact_length'          => '{field} alanı {param} karakter uzunluğunda olmalı.',
    'greater_than'          => '{field} alanı {param} den büyük bir sayı içermeli.',
    'greater_than_equal_to' => '{field} alanı {param} den büyük veya eşit bir sayı içermeli.',
    'hex'                   => '{field} alanı sadece onaltılık (hexadecimal) karakterler içerebilir.',
    'in_list'               => '{field} alanı şunlardan biri olmalı: {param}.',
    'integer'               => '{field} alanı bir tamsayı içermeli.',
    'is_natural'            => '{field} alanı yalnız rakam içermeli.',
    'is_natural_no_zero'    => '{field} alanı yalnız rakam içermeli ve sıfırdan büyük olmalı.',
    'is_not_unique'         => '{field} alanı, veri tabanında önceden var olan bir değeri içermeli.',
    'is_unique'             => '{field} alanı eşsiz bir değer içermeli.',
    'less_than'             => '{field} alanı {param} den küçük bir sayı içermeli.',
    'less_than_equal_to'    => '{field} alanı {param} den küçük veya eşit bir sayı içermeli.',
    'matches'               => '{field} alanı ile {param} alanı aynı olmalı.',
    'max_length'            => '{field} alanı {param} karakterden fazla olamaz.',
    'min_length'            => '{field} alanı en az {param} karakter olmalı.',
    'not_equals'            => '{field} alanı {param} alanı ile eşit olmamalı.',
    'not_in_list'           => '{field} alanı şunlardan biri olmamalıdır: {param}.',
    'numeric'               => '{field} alanı yalnız sayı içermeli.',
    'regex_match'           => '{field} alanı doğru biçimde değil.',
    'required'              => '{field} alanı gerekli.',
    'required_with'         => '{param} olduğunda {field} alanı da olmalı.',
    'required_without'      => '{param} olmadığında {field} alanı gerekli.',
    'string'                => '{field} alanı geçerli bir dizgi olmalıdır.',
    'timezone'              => '{field} alanı geçerli bir saat dilimi olmalı.',
    'valid_base64'          => '{field} alanı geçerli bir base64 dizisi olmalı.',
    'valid_email'           => '{field} alanı geçerli bir email adresi içermeli.',
    'valid_emails'          => '{field} alanı geçerli email adresleri içermeli.',
    'valid_ip'              => '{field} alanı geçerli bir IP içermeli.',
    'valid_url'             => '{field} alanı geçerli bir URL içermeli.',
    'valid_url_strict'      => '{field} alanı geçerli bir URL içermeli.',
    'valid_date'            => '{field} alanı geçerli bir tarih içermeli.',
    'valid_json'            => '{field} alanı geçerli bir json içermelidir.',

    // Credit Cards
    'valid_cc_num' => '{field} geçerli bir kredi kartı numarası değil.',

    // Files
    'uploaded' => '{field} geçerli bir yüklenen dosya değil.',
    'max_size' => '{field} çok büyük dosya.',
    'is_image' => '{field} geçerli bir yüklenen resim dosyası değil.',
    'mime_in'  => '{field} alanında geçerli bir dosya türü yok.',
    'ext_in'   => '{field} alanında geçerli bir dosya uzantısı yok.',
    'max_dims' => '{field} bir resim değil veya çok geniş ya da uzun.',
];
