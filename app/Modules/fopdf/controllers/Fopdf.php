<?php
 /*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

namespace App\Modules\fopdf\controllers;

use App\Models\FAQS;
use App\Models\User;
use App\ThirdParty\MX\WhatPanel;




class Fopdf extends WhatPanel{
	function __construct()
	{
		// parent::__construct();
		// User::logged_in();
		 
		// $this->load->helper('invoicer');		
		// $this->applib->set_locale();
		
	}

	function invoice($invoice_id = NULL){			
		$data['id'] = $invoice_id;
		echo view('modules/fopdf/invoice_pdf', $data);
	}
 

	function attach_invoice($invoice){			
			$data['id'] = $invoice['inv_id'];
			$data['attach'] = TRUE;
			echo view('modules/invoices/invoice_pdf', $data);		
	}
	 



}