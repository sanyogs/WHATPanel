<?php
 /*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

namespace app\Modules\categories\controllers;

use App\Libraries\AppLib;
use App\Models\App;
use App\Models\User;
use App\Modules\Layouts\Libraries\Template;
use App\ThirdParty\MX\WhatPanel;
use App\ThirdParty\MX\Modules;


class Categories extends WhatPanel
{

	protected $db;
	protected $applib;
	protected $template;

	function __construct()
	{

		error_reporting(E_ALL);
        ini_set("display_errors", "1");

		parent::__construct();

		$this->db = \Config\Database::connect();

		$session = \Config\Services::session();

		// Modify the 'default' property	
		

		// Assuming User model is in the Models namespace
		// Adjust the namespace accordingly
		// User::logged_in();

		// Load the User model using the service container
		$this->userModel = new User();

		$request = \Config\Services::request();

		// Check if the user is logged in
		$this->userModel->logged_in();

		if ($this->userModel->is_admin()) {
			return redirect()->to('dashboard');
		}

		$this->applib = new AppLib();

		$this->applib->set_locale();

		$this->template = new Template();
	}
	
	public function index()
	{
		error_reporting(E_ALL);
        ini_set("display_errors", "1");

		$template = new Template;

		// $this->template->title(lang('hd_lang.welcome') . ' - ' . config_item('company_name'));

		$data['page'] = lang('hd_lang.dashboard');
		$data['load_setting'] = 'categories';

		return view('modules/clients/client_area', $data);
		// return view('App\Modules\clients\Views\client_area', $data);

	}

}

/* End of file clients.php */