<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
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

namespace CodeIgniter\Test\Mock;

use CodeIgniter\Log\Handlers\FileHandler;

/**
 * Class MockFileLogger
 *
 * Extends FileHandler, exposing some inner workings
 */
class MockFileLogger extends FileHandler
{
    /**
     * Where would the log be written?
     */
    public $destination;

    public function __construct(array $config)
    {
        parent::__construct($config);
        $this->handles     = $config['handles'] ?? [];
        $this->destination = $this->path . 'log-' . date('Y-m-d') . '.' . $this->fileExtension;
    }
}
