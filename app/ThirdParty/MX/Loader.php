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

use App\Controllers\BaseController;
use App\ThirdParty\MX\CI;
use App\ThirdParty\MX\Modules;
use App\ThirdParty\MX\MX_Controller;
use CodeIgniter\Controller;

use Config\Services;

use CodeIgniter\Loader as CILoader;

class Loader extends BaseController
{
    protected $_module;

	protected $controller;

	protected $_ci_classes;

	protected $_ci_models;

	protected $_ci_view_paths;

	protected $add_package_path;

    public $_ci_plugins = array();
    public $_ci_cached_vars = array();

    public function initialize($controller = null)
    {
        $this->_module = service('router')->getNamespace();

        if ($controller instanceof MX_Controller || $controller instanceof MX_Controller)
        {
            $this->controller = $controller;

            foreach (get_class_vars(CILoader::class) as $var => $val)
            {
                if ($var != '_ci_ob_level')
                {
                    $this->$var =& service('load')->$var;
                }
            }
        }
        else
        {

            $this->_autoloader(array());
        }

        $this->_add_module_paths($this->_module);
    }

	/** Add a module path loader variables **/
	protected function _add_module_paths($module = '')
    {
        if (empty($module)) {
            return;
        }

        foreach (Modules::$locations as $location => $offset)
        {
            if (is_dir($modulePath = $location . $module . '/') && !in_array($modulePath, $this->_ci_model_paths))
            {
                array_unshift($this->_ci_model_paths, $modulePath);
            }
        }
    }

	/** Load a module config file **/
	public function config($file, $useSections = false, $failGracefully = false)
    {
        return service('config')->load($file, $useSections, $failGracefully, $this->_module);
    }

	/** Load the database drivers **/
	public function database($params = null, $return = false, $queryBuilder = null)
{
    // Check if the database connection already exists
    if ($return === false && $queryBuilder === null && isset(service('db')->DBDriver) && service('db')->DBDriver->DBPrefix != '')
    {
        return false;
    }

    // Require the Database module
    require_once SYSTEMPATH.'Database/Database.php';

    // Create a new instance of the Database class
    $db = new \CodeIgniter\Database\Database($params, $queryBuilder);

    // If $return is true, return the new Database instance
    if ($return === true)
    {
        return $db;
    }

    // Set the new Database instance as the default
    service('db', $db);

    return $this;
}

	/** Load a module helper **/
	public function helper($helper = [])
{
    if (is_array($helper)) {
        return $this->helpers($helper);
    }

    // Check if the helper has already been loaded
    if (isset($this->helpers[$helper])) {
        return $this;
    }

    // Try to find the helper in the module
    list($path, $_helper) = Modules::find('Helpers/' . $helper, $this->module, 'helpers/');

    // If the helper is not found in the module, load it from the app
    if ($path === false) {
        return $this->helper($helper);
    }

    // Load the helper from the module
    Modules::load_file($_helper, $path);

    // Mark the helper as loaded
    $this->helpers[$_helper] = true;

    return $this;
}


	/** Load an array of helpers **/
	public function helpers(array $helpers = [])
{
    foreach ($helpers as $helper) {
        $this->helper($helper);
    }
    return $this;
}

public function language(string $langfile, string $idiom = null, bool $return = false, bool $addSuffix = true, string $altPath = null)
{
    // Check if the language file has already been loaded
    if (service('translations')->has($langfile, $idiom)) {
        return $this;
    }

    // Try to find the language file in the module
    list($path, $_langfile) = Modules::find('Language/' . $langfile, $this->module, 'language/');

    // If the language file is not found in the module, load it from the app
    if ($path === false) {
        return lang($langfile, $idiom, $return, $addSuffix, $altPath);
    }

    // Load the language file from the module
    Modules::load_file($_langfile, $path);

    return $this;
}


public function languages(array $languages)
{
    foreach ($languages as $language) {
        $this->language($language);
    }
    return $this;
}


	/** Load a module library **/
	public function library(string $library, array $params = null, string $alias = null)
{
    if (is_array($library)) {
        return $this->libraries($library);
    }

    $class = ucfirst(basename($library));

    if (isset($this->_ci_classes[$class]) && $alias = $this->_ci_classes[$class]) {
        return $this;
    }

    $alias = $alias ?? $class;

    [$path, $file] = Modules::find($library, $this->_module, 'Libraries/');

    /* load library config file as params */
    if ($params === null) {
        [$path2, $configFile] = Modules::find($alias, $this->_module, 'Config/');
        if ($path2) {
            $params = Modules::load_file($configFile, $path2, 'Config');
        }
    }

    if ($path === false) {
        $this->_ci_load_library($library, $params, $alias);
    } else {
        Modules::load_file($file, $path);

        $libraryClass = '\Modules\\' . ucfirst($this->_module) . '\Libraries\\' . $class;
        CI::$APP->$alias = new $libraryClass($params);

        $this->_ci_classes[$class] = $alias;
    }

    return $this;
}

protected function _ci_load_library($class, $params = null, $object_name = null)
{
    if (empty($class)) {
        return $this;
    }

    // Adapt the class name to follow PSR-4 namespace conventions
    $class = ucfirst($class);

    if ($params !== null) {
        $this->_ci_classes[$class] = $object_name;
        CI::$APP->$object_name = new $class($params);
    } else {
        $this->_ci_classes[$class] = $class;
        CI::$APP->$class = new $class();
    }

    return $this;
}



	/** Load an array of libraries **/
	public function libraries($libraries)
	{
		foreach ($libraries as $library => $alias) 
		{
			(is_int($library)) ? $this->library($alias) : $this->library($library, NULL, $alias);
		}
		return $this;
	}

	/** Load a module model **/
	public function model($model, $object_name = NULL, $connect = FALSE)
	{
		if (is_array($model)) return $this->models($model);

		($_alias = $object_name) OR $_alias = basename($model);

		if (in_array($_alias, $this->_ci_models, TRUE))
			return $this;

		/* check module */
		list($path, $_model) = Modules::find(strtolower($model), $this->_module, 'models/');

		if ($path == FALSE)
		{
			/* check application & packages */
			model($model, $object_name, $connect = null);
		}
		else
		{
			class_exists('CI_Model', FALSE) OR load_class('Model', 'core');

			if ($connect !== FALSE && ! class_exists('CI_DB', FALSE))
			{
				if ($connect === TRUE) $connect = '';
				$this->database($connect, FALSE, TRUE);
			}

			Modules::load_file($_model, $path);

			$model = ucfirst($_model);
			CI::$APP->$_alias = new $model();

			$this->_ci_models[] = $_alias;
		}
		return $this;
	}

	/** Load an array of models **/
	public function models($models)
	{
		foreach ($models as $model => $alias) 
		{
			(is_int($model)) ? $this->model($alias) : $this->model($model, $alias);
		}
		return $this;
	}

	/** Load a module controller **/
	public function module($module, $params = null)
{
    if (is_array($module)) {
        return $this->modules($module);
    }

    $alias = strtolower(basename($module));

    // Assuming you are using the service() function to get an instance of the Modules class
    $modules = service('modules');

    // Load the module and store it in the app container
    $moduleInstance = $modules::load([$module => $params]);
    CI::$APP->$alias = $moduleInstance;

    return $this;
}

	/** Load an array of controllers **/
	public function modules($modules)
	{
		foreach ($modules as $_module) $this->module($_module);
		return $this;
	}

	/** Load a module plugin **/
	public function plugin($plugin)
	{
		if (is_array($plugin)) return $this->plugins($plugin);

		if (isset($this->_ci_plugins[$plugin]))
			return $this;

		list($path, $_plugin) = Modules::find($plugin.'_pi', $this->_module, 'plugins/');

		if ($path === FALSE && ! is_file($_plugin = APPPATH.'plugins/'.$_plugin.EXT))
		{
			show_error("Unable to locate the plugin file: {$_plugin}");
		}

		Modules::load_file($_plugin, $path);
		$this->_ci_plugins[$plugin] = TRUE;
		return $this;
	}

	/** Load an array of plugins **/
	public function plugins($plugins)
	{
		foreach ($plugins as $_plugin) $this->plugin($_plugin);
		return $this;
	}

	/** Load a module view **/
	public function view($view, $vars = array(), $return = FALSE)
	{
		list($path, $_view) = Modules::find($view, $this->_module, 'views/');

		if ($path != FALSE)
		{
			$this->_ci_view_paths = array($path => TRUE) + $this->_ci_view_paths;
			$view = $_view;
		}

		// return $this->_ci_load(array('_ci_view' => $view, '_ci_vars' => $this->_ci_object_to_array($vars), '_ci_return' => $return));
		if (method_exists($this, '_ci_object_to_array'))
		{
		        // return $this->_ci_load(array('_ci_view' => $view, '_ci_vars' => $this->_ci_object_to_array($vars), '_ci_return' => $return));
		        return $this->_ci_load(array('_ci_view' => $view, '_ci_vars' => json_decode(json_encode($vars), true), '_ci_return' => $return));
		} else {
		        // return $this->_ci_load(array('_ci_view' => $view, '_ci_vars' => $this->_ci_prepare_view_vars($vars), '_ci_return' => $return));
		        return $this->_ci_load(array('_ci_view' => $view, '_ci_vars' => $this->view($vars), '_ci_return' => $return));
		}
	}

	protected function &_ci_get_component($component)
	{
		return CI::$APP->$component;
	}

	public function __get($class)
	{
		return (isset($this->controller)) ? $this->controller->$class : CI::$APP->$class;
	}

	public function _ci_load($_ci_data)
	{
		extract($_ci_data);

		if (isset($_ci_view))
		{
			$_ci_path = '';

			/* add file extension if not provided */
			$_ci_file = (pathinfo($_ci_view, PATHINFO_EXTENSION)) ? $_ci_view : $_ci_view.EXT;

			foreach ($this->_ci_view_paths as $path => $cascade)
			{
				if (file_exists($view = $path.$_ci_file))
				{
					$_ci_path = $view;
					break;
				}
				if ( ! $cascade) break;
			}
		}
		elseif (isset($_ci_path))
		{

			$_ci_file = basename($_ci_path);
			if( ! file_exists($_ci_path)) $_ci_path = '';
		}

		if (empty($_ci_path))
			show_error('Unable to load the requested file: '.$_ci_file);

		if (isset($_ci_vars))
			$this->_ci_cached_vars = array_merge($this->_ci_cached_vars, (array) $_ci_vars);

		extract($this->_ci_cached_vars);

		ob_start();

		// if ((bool) @ini_get('short_open_tag') === FALSE && CI::$APP->config->item('rewrite_short_tags') == TRUE)
		if ((bool) @ini_get('short_open_tag') === FALSE && Services::config()->item('rewrite_short_tags') === TRUE)
		{
			echo eval('?>'.preg_replace("/;*\s*\?>/", "; ?>", str_replace('<?=', '<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

 echo ', file_get_contents($_ci_path))));
		}
		else
		{
			include($_ci_path);
		}

		log_message('debug', 'File loaded: '.$_ci_path);

		if ($_ci_return == TRUE) return ob_get_clean();

		if (ob_get_level() > $this->_ci_ob_level + 1)
		{
			ob_end_flush();
		}
		else
		{
			// CI::$APP->output->append_output(ob_get_clean());
			$output = ob_get_clean();
			return $output;
		}
	}

	/** Autoload module items **/
	public function _autoloader($autoload)
	{
		$path = FALSE;

		if ($this->_module)
		{
			list($path, $file) = Modules::find('constants', $this->_module, 'config/');

			/* module constants file */
			if ($path != FALSE)
			{
				include_once $path.$file.EXT;
			}

			list($path, $file) = Modules::find('autoload', $this->_module, 'config/');

			/* module autoload file */
			if ($path != FALSE)
			{
				$autoload = array_merge(Modules::load_file($file, $path, 'autoload'), $autoload);
			}
		}

		/* nothing to do */
		if (count($autoload) == 0) return;

		/* autoload package paths */
		// if (isset($autoload['packages']))
		// {
		// 	foreach ($autoload['packages'] as $package_path)
		// 	{
		// 		$this->add_package_path($package_path);
		// 	}
		// }

		$autoloadConfig = config('Autoload');

		if (isset($autoloadConfig->packages)) {
			foreach ($autoloadConfig->packages as $packagePath) {
				Services::autoloader()->addNamespace('', $packagePath);
			}
		}

		/* autoload config */
		if (isset($autoload['config']))
		{
			foreach ($autoload['config'] as $config)
			{
				$this->config($config);
			}
		}

		/* autoload helpers, plugins, languages */
		foreach (array('helper', 'plugin', 'language') as $type)
		{
			if (isset($autoload[$type]))
			{
				foreach ($autoload[$type] as $item)
				{
					$this->$type($item);
				}
			}
		}

		$autoloadConfig = config('Autoload');
		
		// Autoload drivers
		if (isset($autoload['drivers']))
		{
		    // foreach ($autoload['drivers'] as $item => $alias)
		    // {
		    //     (is_int($item)) ? $this->driver($alias) : $this->driver($item, $alias);
		    // }

			foreach ($autoloadConfig->drivers as $item => $alias) {
				Services::driver($alias);
			}
		}

		/* autoload database & libraries */
		if (isset($autoload['libraries']))
		{
			if (in_array('database', $autoload['libraries']))
			{
				/* autoload database */
				// if ( ! $db = CI::$APP->config->item('database'))
				if ( ! $db = config('Database'))
				{
					$this->database();
					$autoload['libraries'] = array_diff($autoload['libraries'], array('database'));
				}
			}

			/* autoload libraries */
			foreach ($autoload['libraries'] as $library => $alias)
			{
				(is_int($library)) ? $this->library($alias) : $this->library($library, NULL, $alias);
			}
		}

		/* autoload models */
		if (isset($autoload['model']))
		{
			foreach ($autoload['model'] as $model => $alias)
			{
				(is_int($model)) ? $this->model($alias) : $this->model($model, $alias);
			}
		}

		/* autoload module controllers */
		if (isset($autoload['modules']))
		{
			foreach ($autoload['modules'] as $controller)
			{
				($controller != $this->_module) && $this->module($controller);
			}
		}
	}
}

/** load the CI class for Modular Separation **/
(class_exists('CI', FALSE)) OR require dirname(__FILE__).'/Ci.php';