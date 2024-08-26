<?php
 /*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

namespace App\Modules\Dashboard\Controllers;

use App\ThirdParty\MX\MX_Controller;
use App\Libraries\Template;

class Layouts extends MX_Controller
{
  public function index()
  {
  	// $this->load->library('template');
    $this->template = new Template();
  }
}

//end