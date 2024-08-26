<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */
use App\Models\App;
use App\Models\Client;
use App\Models\User;
use App\ThirdParty\MX\Modules;
?>
<div class="modal-dialog modal-md">
    <div class="modal-content">
        <div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><?=ucfirst($config->system_name) . " " . lang('hd_lang.settings')?></h4>
        </div>
        <div class="modal-body">
            <?php  
					echo $this->settings->open_form(array('action' => ''));
					$configuration = modules::run($config->system_name.'/'.$config->system_name.'_config', unserialize($config->config));	
					
					$configuration[] =  array(
						'id' => 'id',
						'type' => 'hidden',
						'value' => $config->plugin_id
					);

					$configuration[] =  array(
						'id' => 'system_name',
						'type' => 'hidden',
						'value' => $config->system_name
					);

					$configuration[] =  array(
						'id' => 'submit',
						'type' => 'submit',
						'label' => 'Save' 
					);

					echo $this->settings->build_form_horizontal($configuration);
					echo $this->settings->close_form(); 
			  ?>
        </div>
        <div class="modal-footer" style="border-top:none;"></div>
    </div>
    <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->