<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

use App\Helpers\custom_name_helper;

$custom = new custom_name_helper();

?>
<div class="modal-dialog my-modal">
	<div class="modal-content">
		<div class="modal-header row-reverse"> <button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title"><?=lang('hd_lang.activate_account')?></h4>
		</div><?php
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url('accounts/activate'),$attributes); ?>
		<div class="modal-body">
			
				<div class="form-group">
						<label class="col-lg-5 control-label"><?=lang('hd_lang.server')?></label>
						<div class="col-lg-5">
						<select id="server" name="server" class="form-control m-b">
							<?php
							$session = \Config\Services::session(); 

							    
					
							   
					
							// Modify the 'default' property    
							    
					
							// Connect to the database  
							$db = \Config\Database::connect();

							$default_server = $db->table('hd_servers')->where(array('id'=> $item->server))->get()->getRow();

							foreach ($servers as $server) { ?>
							<option value="<?=$server->id?>" <?=(isset($default_server->id) && $default_server->id == $server->id) ? 'selected' : ''?>><?=$server->name?> (<?=$server->type?>)</option>
							<?php } ?>

						</select>
						</div>

						<label class="col-lg-5 control-label"><?=lang('hd_lang.send_details_to_client')?></label>
						<div class="col-lg-5">
							<label class="switch">
									<input type="hidden" value="off" name="send_details" />
									<input type="checkbox" name="send_details">									
								<span></span>
							</label>
						</div>

						<label class="col-lg-5 control-label"><?=lang('hd_lang.create_controlpanel')?></label>
						<div class="col-lg-5">
						<label class="switch">
									<input type="hidden" value="off" name="create_controlpanel" />
									<input type="checkbox" name="create_controlpanel">									
								<span></span>
							</label>
						</div>
 
						<input type="hidden" name="id" value="<?=$item->id?>">						
					
				</div>
				<h3><?=$item->item_name?> - <?=$item->domain?></h3>
				<table class="table table-bordered table-striped">		
						<thead><tr><th><?=lang('hd_lang.username')?></th><th><?=lang('hd_lang.password')?></th></thead>
						<tbody>						
							<tr>
							<td><input type="text" value="<?=$item->username?>" name="username" class="form-control"></td>
							<td><input type="text" value="<?=$item->password?>" name="password" class="form-control"></td>
							 </tr>
						<?php  ?>
					</tbody>
				</table>
				
				 
		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('hd_lang.close')?></a>
		<button type="submit" class="btn btn-<?=$custom->getconfig_item('theme_color');?>"><?=lang('hd_lang.activate')?></button>
		</form>
		</div>
	</div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
