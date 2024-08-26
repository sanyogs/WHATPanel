<?php
 /*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

namespace App\Modules\clients\controllers;

use App\Models\User;
use App\ThirdParty\MX\WhatPanel;




class Clients extends WhatPanel
{
	function __construct()
	{
		// parent::__construct();
		User::logged_in();

		// $this->applib->set_locale();
	}

	function index()
	{
		// $this->load->module('layouts');
		// $this->load->library('template');
		// $this->template->title(lang('hd_lang.welcome') . ' - ' . config_item('company_name'));
		$data['page'] = lang('hd_lang.dashboard');
		// $this->template
		// 	->set_layout('users')
		// 	->build('client_area', isset($data) ? $data : NULL);
		echo view('modules/clients/client_area', $data);
	}
}

/* End of file clients.php */