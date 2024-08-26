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

use CodeIgniter\Config\BaseConfig;
use Config\Services;
use Modules;

class Config extends BaseConfig
{
    protected $item;
    
    public function load($file = '', $useSections = false, $failGracefully = false, $module = '')
    {
        // Check if the configuration file is already loaded
        if (in_array($file, $this->loaded, true)) {
            return $this->item($file);
        }

        // Determine the module based on the request URI
        $module or $module = Services::request()->uri->getSegment(1);

        // Find the path and file for the specified configuration file in the module
        list($path, $file) = Modules::find($file, $module, 'Config/');

        // If the file is not found in the module, load it from the default location
        if ($path === false) {
            parent::load($file, $useSections, $failGracefully);
            return $this->item($file);
        }

        // Load the configuration file from the module
        if ($config = Modules::loadFile($file, $path, 'Config')) {
            // Reference to the config array
            $currentConfig = &$this->config;

            // Merge the loaded configuration into the current config array
            if ($useSections === true) {
                if (isset($currentConfig->$file)) {
                    $currentConfig->$file = array_merge($currentConfig->$file, $config);
                } else {
                    $currentConfig->$file = $config;
                }
            } else {
                $currentConfig = array_merge($currentConfig, $config);
            }

            // Mark the configuration file as loaded
            $this->loaded[] = $file;
            unset($config);

            // Return the loaded configuration item
            return $this->item($file);
        }
    }
}
