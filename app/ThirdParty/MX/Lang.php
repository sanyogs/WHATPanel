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

use CodeIgniter\Language\Language;
use App\ThirdParty\MX\Base;
use App\ThirdParty\MX\Modules;
use App\ThirdParty\MX\MY_Router;
use App\ThirdParty\MX\RouteCollectionInterface;
// use CodeIgniter\Router\RouteCollectionInterface as RouterInterface;
use App\Helpers\custom_name_helper;

class Lang
{	
	public $module;
	protected $routes;
	
	public function fetch_module()
	{
		return $this->module;
	}
	
    public function load($langfile, $lang = '', $return = false, $addSuffix = true, $altPath = '', $_module = '')
	{	
		// echo "<pre>";print_r($langfile);die;
		$is_loaded = array();
		$module = new Modules();
		$custom = new custom_name_helper();
		if (is_array($langfile)) {
			foreach ($langfile as $_lang) $this->load($_lang);			
			$system_lang = new Language('');
			return $system_lang->getLanguage();
		}

		// echo 9656;die;
		//echo $altPath;die;

		$deftLang = $custom->getconfig_item('default_language');
		$idiom = ($lang == '') ? $deftLang : $lang;
		
		$system_lang = new Language('');
		
		if (in_array($langfile.'_lang.php', $system_lang->loadedfiles(), TRUE))
		return $system_lang->getLanguage();
	
		$uri = service('uri'); // Get the URI service instance
		
		// Split the URI segments
		$segments = $uri->getSegments();
		//print_r($segments[0]);die;
		$_module OR $_module = $segments[0];
		// Specify the directory path
		$path = APPPATH . '/Language/' . $idiom . '/';

		// echo $path;die;
		
		//$values = [$langfile.'_lang', $_module, 'language/'.$idiom.'/'];
		list($path, $_langfile) = $module->find($langfile.'_lang', $_module, 'language/'.$idiom.'/');
		
		//$matches = glob($values[]);
		// echo"<pre>";print_r($_langfile);die;
		
		if (!empty($matches)) {
			$_langfile = $matches[0];
		}
		
		// Now $_langfile will contain the path to the matched file as a string
	
		$lan = new Lang();

		//echo $path;die;
		
		if ($path === false) {
			if($langfile == "hd" || $langfile == "tank_auth"){
				if ($lang = $system_lang->load($langfile . '_lang', $lang	, $return)) return $lang;
                }else{
					//echo $lang;die;
					//echo "<pre>";print_r($altPath);die;
                if ($lang = $system_lang->load($langfile, $lang	, $return)) return $lang;
					//echo "<pre>";print_r($lang);die;
                }
				// if($langfile == 'Email.php'){
				// 	echo 133;die;
				// }
			// if (is_array($lang)) {
			// 	// echo "<pre>";print_r($system_lang->language);die;
			// 	// return TRUE; 
			// }
		} else {
			// echo 120;die;
			if ($lang = $module->loadFile($_langfile, $path, 'Language')) {
				if ($return) {
					return $lang;
				}
				$system_lang = array_merge($system_lang, $lang);
				$system_lang->loadedFiles[] = $langfile . '_lang.php';
				unset($lang);
			}
		}
		return $system_lang->language;
	}

}
