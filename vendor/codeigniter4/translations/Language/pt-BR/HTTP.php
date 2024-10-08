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
    'missingCurl'     => 'CURL deve estar ativado para usar a classe CURLRequest.',
    'invalidSSLKey'   => 'Não é possível definir a Chave SSL. {0} não é um arquivo válido.',
    'sslCertNotFound' => 'Certificado SSL não encontrado em: {0}',
    'curlError'       => '{0} : {1}',

    // IncomingRequest
    'invalidNegotiationType' => '{0} não é um tipo de negociação válido. Deve ser um dos seguintes: media, charset, encoding, language.',
    'invalidJSON'            => 'Falha ao analisar a string JSON. Erro: {0}',
    'unsupportedJSONFormat'  => 'O formato JSON fornecido não é compatível.',

    // Message
    'invalidHTTPProtocol' => 'Versão inválida do Protocolo HTTP. Deve ser uma dessas: {0}',

    // Negotiate
    'emptySupportedNegotiations' => 'Você deve fornecer uma array de valores suportados para todas as Negociações.',

    // RedirectResponse
    'invalidRoute' => 'Rota {0} não foi encontrada ao fazer o roteamento-reverso.',

    // DownloadResponse
    'cannotSetBinary'        => 'Ao definir o caminho do arquivo não foi possível definir como binário.',
    'cannotSetFilepath'      => 'Ao definir como binário não é possível definir o caminho do arquivo: {0}',
    'notFoundDownloadSource' => 'Fonte do corpo do download não encontrado.',
    'cannotSetCache'         => 'Não suporta armazenamento em cache para download.',
    'cannotSetStatusCode'    => 'Não suporta o código de status de alteração para download. Código: {0}, Razão: {1}',

    // Response
    'missingResponseStatus' => 'Resposta HTTP está faltando um código de status',
    'invalidStatusCode'     => '{0} não é um código de status de retorno HTTP válido',
    'unknownStatusCode'     => 'Código de status HTTP desconhecido fornecido sem nenhuma mensagem: {0}',

    // URI
    'cannotParseURI'       => 'Não é possível analisar o URI: {0}',
    'segmentOutOfRange'    => 'Segmento do URI da Requisição está fora do intervalo: {0}',
    'invalidPort'          => 'Portas devem estar entre 0 e 65535. Dado: {0}',
    'malformedQueryString' => 'As strings de consulta não podem incluir fragmentos de URI.',

    // Page Not Found
    'pageNotFound'       => 'Página Não Encontrada',
    'emptyController'    => 'Nenhum Controller especificado.',
    'controllerNotFound' => 'Controller ou seu método não foi encontrado: {0}::{1}',
    'methodNotFound'     => 'Método do Controller não foi encontrado: {0}',
    'localeNotSupported' => 'Idioma não suportado: {0}',

    // CSRF
    'disallowedAction' => 'A ação que você solicitou não é permitida.',

    // Uploaded file moving
    'alreadyMoved'       => 'O arquivo enviado já foi movido.',
    'invalidFile'        => 'O arquivo original não é um arquivo válido.',
    'moveFailed'         => 'Não foi possível mover o arquivo {0} para {1} ({2})',
    'uploadErrOk'        => 'O upload do arquivo foi realizado com sucesso.',
    'uploadErrIniSize'   => 'O arquivo "%s" excede a diretiva ini upload_max_filesize.',
    'uploadErrFormSize'  => 'O arquivo "%s" excede o limite de upload definido em seu formulário.',
    'uploadErrPartial'   => 'O upload do arquivo "%s" foi realizado apenas parcialmente.',
    'uploadErrNoFile'    => 'Nenhum upload de arquivo foi realizado.',
    'uploadErrCantWrite' => 'O arquivo "%s" não pode ser escrito no disco.',
    'uploadErrNoTmpDir'  => 'Upload de arquivo não pode ser realizado: faltando diretório temporário.',
    'uploadErrExtension' => 'Upload de arquivo foi parado por uma extensão PHP.',
    'uploadErrUnknown'   => 'O upload do arquivo "%s" não foi realizado devido a um erro desconhecido.',

    // SameSite setting
    'invalidSameSiteSetting' => 'A configuração SameSite deve ser None, Lax, Strict ou uma string vazia. Dado: {0}',
];
