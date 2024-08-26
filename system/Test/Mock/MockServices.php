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

use CodeIgniter\Autoloader\FileLocator;
use CodeIgniter\Config\BaseService;

class MockServices extends BaseService
{
    public $psr4 = [
        'Tests/Support' => TESTPATH . '_support/',
    ];
    public $classmap = [];

    public function __construct()
    {
        // Don't call the parent since we don't want the default mappings.
        // parent::__construct();
    }

    public static function locator(bool $getShared = true)
    {
        return new FileLocator(static::autoloader());
    }
}
