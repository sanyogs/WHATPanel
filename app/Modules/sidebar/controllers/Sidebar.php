<?php
 /*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

namespace App\Modules\sidebar\controllers;

use App\ThirdParty\MX\WhatPanel;

class Sidebar extends WhatPanel
{
	protected $DBGroup;

	public function __construct()
	{
		parent::__construct();
	}

	public function admin_menu()
	{	
		$active_menu = array(
			'menu1' => 'pages', // For example, 'pages' is the active menu item for menu1
			'menu2' => 'sliders',
		);

		// Pass the $active_menus variable to the view
		$data['active_menus'] = $active_menu;
		echo view('modules/sidebar/admin_menu', $data);
		//echo view('modules/sidebar/admin_menu');
	}

	public function staff_menu()
	{	
		
		echo view('modules/sidebar/staff_menu');
	}

	public function client_menu()
	{
		echo view('modules/sidebar/user_menu');
	}

	public function top_header()
	{
		$data['updates'] = array();

		//$this->load->view('top_header', isset($data) ? $data : NULL);
		echo view('modules/sidebar/top_header', isset($data) ? $data : NULL);
	}

	public function scripts()
	{
		// $this->load->view('scripts/app_scripts', isset($data) ? $data : NULL);
		echo view('scripts/app_scripts');
	}

	public function flash_msg()
	{
		$this->load->view('flash_msg', isset($data) ? $data : NULL);
	}
}
/* End of file sidebar.php */