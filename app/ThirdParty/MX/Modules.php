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

use CodeIgniter\CodeIgniter;
use CodeIgniter\Config\Services;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Autoloader\FileLocator;
use App\Helpers\custom_name_helper;
use App\ThirdParty\MX\Config;
use App\ThirdParty\MX\Lang;

$custom = new custom_name_helper();
// Load the Config class if it's not auto-loaded
$modulesConfig = $custom->getconfig_item('Modules');

// Get module locations from config settings or use the default module location and offset
$modulesLocations = $modulesConfig->locations ?? [
    APPPATH . 'Modules/' => '../Modules/',
    FCPATH . 'Modules/' => '../../Modules/'
];

// Assign the module locations to the Modules class property
Modules::$locations = is_array($modulesLocations) ? $modulesLocations : [];


/**
 * Modular Extensions - HMVC
 *
 * Adapted from the CodeIgniter Core Classes
 * @link	http://codeigniter.com
 *
 * Description:
 * This library provides functions to load and instantiate controllers
 * and module controllers allowing use of modules and the HMVC design pattern.
 *
 * Install this file as application/third_party/MX/Modules.php
 *
 * @copyright	Copyright (c) 2015 Wiredesignz
 * @version 	5.5
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 **/
class Modules
{
    public static $routes;
    public static $registry;
    public static $locations;

    protected $config;


    /**
     * Run a module controller method
     * Output from module is buffered and returned.
     **/
    public static function run($module)
    {

        $method = 'index';

        if (($pos = strrpos($module, '/')) != FALSE) {
            $method = substr($module, $pos + 1);
            $module = substr($module, 0, $pos);
        }

        if ($class = self::load($module)) {
            if (method_exists($class, $method)) {
                ob_start();
                $args = func_get_args();
                $output = call_user_func_array(array($class, $method), array_slice($args, 1));
                $buffer = ob_get_clean();
                return ($output !== NULL) ? $output : $buffer;
            }
        }

        log_message('error', "Module controller failed to run: {$module}/{$method}");
    }

    /** Load a module controller **/
    public static function load($module)
    {
        //	(is_array($module)) ? list($module, $params) = each($module) : $params = NULL;	

        if (is_array($module)) {
            list($params, $module) = array(reset($module), key($module));
        } else {
            $params = NULL;
        }

        /* get the requested controller class name */
        $alias = strtolower(basename($module));

        /* create or return an existing controller from the registry */
        if (!isset(self::$registry[$alias])) {
            // Access the CodeIgniter 4 application instance
            $app = \Config\Services::app();

            /* find the controller */
            // list($class) = $app->router->locate(explode('/', $module));

            /* controller cannot be located */
            if (empty($class)) {
                return;
            }

            /* set the module directory */
            $path = APPPATH . 'controllers/' . $app->router->directory;

            /* load the controller class */
            $class = $class . $app->config->item('controller_suffix');
            self::load_file(ucfirst($class), $path);

            /* create and register the new controller */
            $controller = ucfirst($class);
            self::$registry[$alias] = new $controller($params);
        }

        return self::$registry[$alias];
    }

    /** Library base class autoload **/
    public static function autoload($class)
    {
        // Don't autoload CI_ prefixed classes or those using the config subclass_prefix
        if (strpos($class, 'CI\\') === 0 || strpos($class, Config::get('subclassPrefix')) === 0) {
            return;
        }

        // Autoload Modular Extensions MX core classes
        if (strpos($class, 'MX\\') === 0) {
            $location = dirname(__FILE__) . '/' . str_replace('\\', '/', substr($class, 3)) . EXT;

            if (is_file($location)) {
                include_once $location;
                return;
            }

            // show_error('Failed to load MX core class: ' . $class);
            Services::response()->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }

        // Autoload core classes
        if (is_file($location = APPPATH . 'Core/' . ucfirst($class) . EXT)) {
            include_once $location;
            return;
        }

        // Autoload library classes
        if (is_file($location = APPPATH . 'Libraries/' . ucfirst($class) . EXT)) {
            include_once $location;
            return;
        }
    }


    /** Load a module file **/
    public static function loadFile(string $file, string $path, string $type = 'other', bool $result = true)
    {
        $file = str_replace('.php', '', $file);
       
        $location = $path . $file . '.php';
        
        if ($type === 'other') {
            if (class_exists($file, false)) {
                log_message('debug', "File already loaded: {$location}");

                return $result;
            }

            include_once $location;
        } else {
            /* Load config or language array */
            include $location;

            if (!isset($$type) || !is_array($$type)) {
                // show_error("{$location} does not contain a valid {$type} array");
                Services::response()->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            $result = $$type;
        }
        log_message('debug', "File loaded: {$location}");

        return $result;
    }

    /** 
     * Find a file
     * Scans for files located within modules directories.
     * Also scans application directories for models, plugins and views.
     * Generates fatal error if file not found.
     **/
    public static function find($file, $module, $base)
    {	
		$custom = new custom_name_helper();
        $segments = explode('/', $file);

        $file = array_pop($segments);

		$fileExt = pathinfo($file, PATHINFO_EXTENSION) ? $file : $file . '.' . Services::defaultExtension();
	
        $path = ltrim(implode('/', $segments) . '/', '/');
        $modules = $module ? [$module => $path] : [];

        if (!empty($segments)) {
            $modules[array_shift($segments)] = ltrim(implode('/', $segments) . '/', '/');
        }
		
		$locations = Modules::$locations;

        foreach ($locations as $location => $offset) {
            foreach ($modules as $module => $subpath) {
                $fullpath = $location . $module . '/' . $base . $subpath;

                if ($base == 'libraries/' || $base == 'models/') {
                    if (is_file($fullpath . ucfirst($fileExt))) {
                        return [$fullpath, ucfirst($file)];
                    }
                } else {
                    // Load non-class files - Note: Adjust this part based on your specific use case
                    if (is_file($fullpath . $fileExt)) {
                        return [$fullpath, $file];
                    }
                }
            }
        }

        return [false, $file];
    }

    /** Parse module routes **/
    public static function parse_routes($module, $uri)
    {


        /* load the route file */
        if (!isset(self::$routes[$module])) {
            if (list($path) = self::find('routes', $module, 'config/')) {
                $path && self::$routes[$module] = self::load_file('routes', $path, 'route');
            }
        }

        if (!isset(self::$routes[$module]))
            return;

        /* parse module routes */
        foreach (self::$routes[$module] as $key => $val) {
            $key = str_replace(array(':any', ':num'), array('.+', '[0-9]+'), $key);

            if (preg_match('#^' . $key . '$#', $uri)) {
                if (strpos($val, '$') !== FALSE and strpos($key, '(') !== FALSE) {
                    $val = preg_replace('#^' . $key . '$#', $val, $uri);
                }
                return explode('/', $module . '/' . $val);
            }
        }
    }

    public static function load_file($file, $path, $type = 'other', $result = TRUE)
    {
        $file = str_replace(EXT, '', $file);
        $location = $path . $file . EXT;

        if ($type === 'other') {
            if (class_exists($file, FALSE)) {
                log_message('debug', "File already loaded: {$location}");
                return $result;
            }
            include_once $location;
        } else {
            /* load config or language array */
            include $location;

            if (!isset($$type) or !is_array($$type))
                show_error("{$location} does not contain a valid {$type} array");

            $result = $$type;
        }
        log_message('debug', "File loaded: {$location}");
        return $result;
    }
}