<!-- START TEMPLATES -->
<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */
// $view = isset($load_setting) ? $load_setting : '';

$template_group = isset($load_setting) ? $load_setting:'';
if($template_group == 'alerts'){
    // $this->load->view($template_group);
    echo view('modules/settings/'.$template_group);
}else{ 
    // $this->load->view('email_settings');
    echo view('modules/settings/email_settings');
}
?>