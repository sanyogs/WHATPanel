<?php

/**
 * The goal of this file is to allow developers a location
 * where they can overwrite core procedural functions and
 * replace them with their own. This file is loaded during
 * the bootstrap process and is called during the framework's
 * execution.
 *
 * This can be looked at as a `master helper` file that is
 * loaded early on, and may also contain additional functions
 * that you'd like to use throughout your entire application
 *
 * @see: https://codeigniter.com/user_guide/extending/common.html
 */


define('STATUS_ACTIVATED', '1');
define('STATUS_NOT_ACTIVATED', '0');




if (!function_exists('config_item')) {
	/**
	 * Returns the specified config item
	 *
	 * @param	string
	 * @return	mixed
	 */


	if (!function_exists('get_config')) {
		/**
		 * Loads the main config.php file
		 *
		 * This function lets us grab the config file even if the Config class
		 * hasn't been instantiated yet
		 *
		 * @param	array
		 * @return	array
		 */
		function &get_config(array $replace = array())
		{
			static $config;

			if (empty($config)) {
				$file_path = APPPATH . 'Config/App.php';
				$found = FALSE;
				if (file_exists($file_path)) {
					$found = TRUE;
					require($file_path);
				}

				// Is the config file in the environment folder?
				// if (file_exists($file_path = APPPATH.'config/'.ENVIRONMENT.'/config.php'))
				// {
				//     require($file_path);
				// }
				// elseif ( ! $found)
				// {
				//     set_status_header(503);
				//     echo 'The configuration file does not exist.';
				//     exit(3); // EXIT_CONFIG
				// }

				// Does the $config array exist in the file?
				// if (!isset($config) or !is_array($config)) {
				// 	set_status_header(503);
				// 	echo 'Your config file does not appear to be formatted correctly.';
				// 	exit(3); // EXIT_CONFIG
				// }
			}

			// Are any values being dynamically added or replaced?
			foreach ($replace as $key => $val) {
				$config[$key] = $val;
			}

			return $config;
		}
	}

	function config_item($item)
	{
		static $_config;

		if (empty($_config)) {
			// references cannot be directly assigned to static variables, so we use an array
			$_config[0] =& get_config();
		}

		return isset($_config[0][$item]) ? $_config[0][$item] : NULL;
	}
}



// ------------------------------------------------------------------------

if (!function_exists('set_status_header')) {
	/**
	 * Set HTTP Status Header
	 *
	 * @param	int	the status code
	 * @param	string
	 * @return	void
	 */
	function set_status_header($code = 200, $text = '')
	{
		if (is_cli()) {
			return;
		}

		if (empty($code) or !is_numeric($code)) {
			show_error('Status codes must be numeric', 500);
		}

		if (empty($text)) {
			is_int($code) or $code = (int) $code;
			$stati = array(
				100 => 'Continue',
				101 => 'Switching Protocols',

				200 => 'OK',
				201 => 'Created',
				202 => 'Accepted',
				203 => 'Non-Authoritative Information',
				204 => 'No Content',
				205 => 'Reset Content',
				206 => 'Partial Content',

				300 => 'Multiple Choices',
				301 => 'Moved Permanently',
				302 => 'Found',
				303 => 'See Other',
				304 => 'Not Modified',
				305 => 'Use Proxy',
				307 => 'Temporary Redirect',

				400 => 'Bad Request',
				401 => 'Unauthorized',
				402 => 'Payment Required',
				403 => 'Forbidden',
				404 => 'Not Found',
				405 => 'Method Not Allowed',
				406 => 'Not Acceptable',
				407 => 'Proxy Authentication Required',
				408 => 'Request Timeout',
				409 => 'Conflict',
				410 => 'Gone',
				411 => 'Length Required',
				412 => 'Precondition Failed',
				413 => 'Request Entity Too Large',
				414 => 'Request-URI Too Long',
				415 => 'Unsupported Media Type',
				416 => 'Requested Range Not Satisfiable',
				417 => 'Expectation Failed',
				422 => 'Unprocessable Entity',
				426 => 'Upgrade Required',
				428 => 'Precondition Required',
				429 => 'Too Many Requests',
				431 => 'Request Header Fields Too Large',

				500 => 'Internal Server Error',
				501 => 'Not Implemented',
				502 => 'Bad Gateway',
				503 => 'Service Unavailable',
				504 => 'Gateway Timeout',
				505 => 'HTTP Version Not Supported',
				511 => 'Network Authentication Required',
			);

			if (isset($stati[$code])) {
				$text = $stati[$code];
			} else {
				show_error('No status text available. Please check your status code number or supply your own message text.', 500);
			}
		}

		if (strpos(PHP_SAPI, 'cgi') === 0) {
			header('Status: ' . $code . ' ' . $text, TRUE);
			return;
		}

		$server_protocol = (isset($_SERVER['SERVER_PROTOCOL']) && in_array($_SERVER['SERVER_PROTOCOL'], array('HTTP/1.0', 'HTTP/1.1', 'HTTP/2', 'HTTP/2.0'), TRUE))
			? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.1';
		header($server_protocol . ' ' . $code . ' ' . $text, TRUE, $code);
	}
}

// --------------------------------------------------------------------


if (!function_exists('show_error')) {
	/**
	 * Error Handler
	 *
	 * This function lets us invoke the exception class and
	 * display errors using the standard error template located
	 * in application/views/errors/error_general.php
	 * This function will send the error page directly to the
	 * browser and exit.
	 *
	 * @param	string
	 * @param	int
	 * @param	string
	 * @return	void
	 */
	function show_error($message, $status_code = 500, $heading = 'An Error Was Encountered')
	{
		$status_code = abs($status_code);
		if ($status_code < 100) {
			$exit_status = $status_code + 9; // 9 is EXIT__AUTO_MIN
			$status_code = 500;
		} else {
			$exit_status = 1; // EXIT_ERROR
		}

		$_error =& load_class('Exceptions', 'core');
		echo $_error->show_error($heading, $message, 'error_general', $status_code);
		exit($exit_status);
	}
}



// ------------------------------------------------------------------------

if (!function_exists('load_class')) {
	/**
	 * Class registry
	 *
	 * This function acts as a singleton. If the requested class does not
	 * exist it is instantiated and set to a static variable. If it has
	 * previously been instantiated the variable is returned.
	 *
	 * @param	string	the class name being requested
	 * @param	string	the directory where the class should be found
	 * @param	mixed	an optional argument to pass to the class constructor
	 * @return	object
	 */
	function &load_class($class, $directory = 'libraries', $param = NULL)
	{
		static $_classes = array();

		// Does the class exist? If so, we're done...
		if (isset($_classes[$class])) {
			return $_classes[$class];
		}

		$name = FALSE;

		// Look for the class first in the local application/libraries folder
		// then in the native system/libraries folder
		foreach (array(APPPATH, BASEPATH) as $path) {
			if (file_exists($path . $directory . '/' . $class . '.php')) {
				$name = 'CI_' . $class;

				if (class_exists($name, FALSE) === FALSE) {
					require_once($path . $directory . '/' . $class . '.php');
				}

				break;
			}
		}

		// Is the request a class extension? If so we load it too
		if (file_exists(APPPATH . $directory . '/' . config_item('subclass_prefix') . $class . '.php')) {
			$name = config_item('subclass_prefix') . $class;

			if (class_exists($name, FALSE) === FALSE) {
				require_once(APPPATH . $directory . '/' . $name . '.php');
			}
		}

		// Did we find the class?
		if ($name === FALSE) {
			// Note: We use exit() rather than show_error() in order to avoid a
			// self-referencing loop with the Exceptions class
			set_status_header(503);
			echo 'Unable to locate the specified class: ' . $class . '.php';
			exit(5); // EXIT_UNK_CLASS
		}

		// Keep track of what we just loaded
		is_loaded($class);

		$_classes[$class] = isset($param)
			? new $name($param)
			: new $name();
		return $_classes[$class];
	}
}

// --------------------------------------------------------------------