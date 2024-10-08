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

// HTTP language settings
return [
    // CurlRequest
    'missingCurl'     => 'Norint naudoti CURLRequest klasę CURL turi būti įgalintas.',
    'invalidSSLKey'   => 'Nepavyksta nustatyti SSL rakto. „{0}“ nėra taisyklingas failas.',
    'sslCertNotFound' => 'SSL sertifikatas narastas adresu: „{0}“',
    'curlError'       => '{0} : {1}',

    // IncomingRequest
    'invalidNegotiationType' => '„{0}“ nėra taisyklingas derybų tipas. Turi būti vienas iš: media, charset, encoding, language.',
    'invalidJSON'            => 'Nepavyko JSON eilutės nuskaitymas. Klaida: {0}',
    'unsupportedJSONFormat'  => 'Pateiktas JSON formatas nėra palaikomas.',

    // Message
    'invalidHTTPProtocol' => 'Neteisinga HTTP protokolo versija: {0}',

    // Negotiate
    'emptySupportedNegotiations' => 'Visoms deryboms turite pateikti palaikomas reikšmes.',

    // RedirectResponse
    'invalidRoute' => 'Nepavyksta rasti „{0}“ atitinkančio maršruto.',

    // DownloadResponse
    'cannotSetBinary'        => 'Nustatant failo kelią negali būti naudojama dvejetainė reikšmė.',
    'cannotSetFilepath'      => 'Nustatant dvejetainę reikšmę negalima nustatyti failo kelio: „{0}“',
    'notFoundDownloadSource' => 'Nerastas atsiuntimo turinio šaltinis.',
    'cannotSetCache'         => 'Nepalaiko padėjimo (caching) atsiuntimui.',
    'cannotSetStatusCode'    => 'Nepalaiko pakeisto statuso kodo atsiuntimo tvarkymui. Kodas: {0}, priežastis: {1}',

    // Response
    'missingResponseStatus' => 'HTTP atsakui trūksta statuso kodo',
    'invalidStatusCode'     => '{0} nėra teisingas HTTP atsako statuso kodas',
    'unknownStatusCode'     => 'Nežinomas HTTP statuso kodas pateiktas be pranešimo: {0}',

    // URI
    'cannotParseURI'       => 'Nepavyko išnagrinėti URI: {0}',
    'segmentOutOfRange'    => 'Užklausos URI segmentas ne reikšmių aibės ribose: {0}',
    'invalidPort'          => 'Prievadų numeriai turi būti tarp 0 ir 65535. Gautasis: {0}',
    'malformedQueryString' => 'Užklausų eilutės turi neįtraukti URI fragmentų.',

    // Page Not Found
    'pageNotFound'       => 'Puslapis nerastas',
    'emptyController'    => 'Nenurodytas kontroleris.',
    'controllerNotFound' => 'Kontroleris arba jo metodas nerastas: {0}::{1}',
    'methodNotFound'     => 'Kontrolerio metodas nerastas: {0}',
    'localeNotSupported' => 'Ši lokalė nėra palaikoma: {0}',

    // CSRF
    // @deprecated use 'Security.disallowedAction'
    'disallowedAction' => 'Veiksmas, kurio prašote, neleistinas.',

    // Uploaded file moving
    'alreadyMoved' => 'Įkeltasis failas jau perkeltas.',
    'invalidFile'  => 'Pirminis failas nėra teisingas failas.',
    'moveFailed'   => 'Nepavyko perkelti failo {0} į {1} ({2})',

    'uploadErrOk'        => 'Failas įkeltas sėkmingai.',
    'uploadErrIniSize'   => 'Failo „%s“ dydis viršija jūsų upload_max_filesize ini direktyvą.',
    'uploadErrFormSize'  => 'Failo „%s“ dydis viršija formoje nurodytą maksimalų failo dydį.',
    'uploadErrPartial'   => 'Failas „%s“ buvo tik dalinai įkeltas.',
    'uploadErrNoFile'    => 'Nebuvo įkeltas joks failas.',
    'uploadErrCantWrite' => 'Failo„%s“ nepavyko įrašyti į diską.',
    'uploadErrNoTmpDir'  => 'Failo įkelti nepavyko: nėra laikinosios direktorijos.',
    'uploadErrExtension' => 'Failo įkėlimą sustabdė PHP išplėtimas.',
    'uploadErrUnknown'   => 'Failo „%s“ nepavyko įkelti dėl nežinomos klaidos.',

    // SameSite setting
    // @deprecated
    'invalidSameSiteSetting' => 'Nustatymas SameSite turi būti None, Lax, Strict arba tuščia eilutė. Pateikta: {0}',
];
