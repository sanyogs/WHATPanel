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

// Core language settings
return [
    'copyError'                    => 'ファイル "{0}" を置換しようとしたときにエラーが発生しました。ファイルまたはディレクトリが書き込み可能であることを確認してください。', // 'An error was encountered while attempting to replace the file "{0}". Please make sure your file directory is writable.'
    'enabledZlibOutputCompression' => 'zlib.output_compression がonに設定されているため、出力バッファが正常に機能しません。', // 'Your zlib.output_compression ini directive is turned on. This will not work well with output buffers.'
    'invalidFile'                  => '無効なファイル: "{0}"', // 'Invalid file: "{0}"'
    'invalidDirectory'             => 'ディレクトリが存在しません: "{0}"', // 'Directory does not exist: "{0}"'
    'invalidPhpVersion'            => 'CodeIgniterを実行するにはPHP {0}以上が必要です。現在のバージョン: {1}', // 'Your PHP version must be {0} or higher to run CodeIgniter. Current version: {1}'
    'missingExtension'             => '"{0}" 拡張モジュールはロードされませんでした。', // 'The framework needs the following extension(s) installed and loaded: "{0}".'
    'noHandlers'                   => '"{0}" は少なくとも1つのハンドラを指定する必要があります。', // '"{0}" must provide at least one Handler.'
];
