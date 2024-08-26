<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */



namespace App\ThirdParty\MX;

use CodeIgniter\Controller;
use Config\Services;
use CodeIgniter\Autoloader\Autoloader;

use App\ThirdParty\MX\Base;
use App\ThirdParty\MX\Modules;
use App\ThirdParty\MX\Loader;

use Config\App;

use App\Models\UserModel;
use App\Models\TicketModel;
use App\Models\OrderModel;
use App\Models\ClientModel;
use App\Models\AppModel;
use App\Models\DomainModel;
use App\Models\ItemModel;
use App\Models\InvoiceModel;
use App\Models\PageModel;
use App\Models\BlockModel;
use App\Models\MenuModel;
use App\Models\PaymentModel;
use App\Models\ReportModel;
use App\Models\AddonModel;


class MX_Controller extends Controller
{
    public $autoload = array();

    protected $config;

    protected $load;

    public function __construct()
    {
        // $class = str_replace($config->controllerSuffix, '', get_class($this));
        // log_message('debug', $class . " MX_Controller Initialized");

        // Modules::$registry[strtolower($class)] = $this;

        // Obtain the current class name
        $class = get_class($this);

        // Access the Config service to get the controller suffix
        // $controllerSuffix = config('Controller')->suffix;
        // $controllerSuffix = $config->controllerSuffix;

        // Remove the controller suffix from the class name
        // $class = str_replace($controllerSuffix, '', $class);

        // Log the initialization message
        log_message('debug', $class . " Controller Initialized");

        // // Get an instance of the Loader service using dependency injection
        // $loader = service('Loader');

        // // Clone the Loader instance
        // $clonedLoader = clone $loader;

        // // Autoload module items
        // $clonedLoader->_autoloader($this->autoload);

        // Get an instance of the Autoloader service using dependency injection
        $autoloader = service('autoloader');

        // Clone the Autoloader instance (if necessary)
        $clonedAutoloader = clone $autoloader;

        // Autoload module items (assuming $this->autoload is an array of autoloading configurations)
        foreach ($this->autoload as $item => $config) {
            $clonedAutoloader->addNamespace($config['namespace'], $config['path']);
        }
    }

    public function __get($class)
    {
        return Services::app()->$class;
    }
}