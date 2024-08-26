<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

/* Module Name: Virtualmin
 * Module URI: https://whatpanel.com
 * Version: 1.0
 * Category: Servers
 * Description: Virtualmin API Integration.
 * Author:  WHAT PANEL 
 * Author URI: https://whatpanel.com
 */

namespace Modules\virtualmin\controllers;

use App\ThirdParty\MX\WhatPanel;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class Virtualmin extends WhatPanel
{
    public function virtualmin_config($values = null)
    {
        $config = array( 
            array(
                'label' => lang('virtualmin_api_username'), 
                'id' => 'virtualmin_api_username',
                'value' => isset($values) ? $values['virtualmin_api_username'] : '',
				'type' => ''
			),
			array(
                'label' => lang('virtualmin_api_password'), 
                'id' => 'virtualmin_api_password',
                'value' => isset($values) ? $values['virtualmin_api_password'] : '',
				'type' => ''
            ) 
        ); 
        
        return $config;        
    } 
}

?>