<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */ use App\Models\User;
use App\Models\Plugin;
use App\Helpers\custom_name_helper;
$custom = new App\Helpers\custom_name_helper();
?>
<div class="modal-dialog modal-lg my-modal">
	<div class="modal-content">
		<div class="modal-header row-reverse"> <button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title"><?=lang('hd_lang.activate_order')?></h4>
		</div><?php
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'orders/activate',$attributes); ?>
		<div class="modal-body">

		<input type="hidden" name="o_id" value="<?=$order[0]->o_id?>"> 
		
				<?php if($order[0]->o_id > 0) { ?>
					<h3><?=lang('hd_lang.upgrade_downgrade')?> (<?=$order[0]->domain?>)</h3>
					  <h5> <?=$order[0]->item_desc?> </h5>	  

				<?php } else { ?>
			 
						<input type="hidden" name="client_id" value="<?=$order[0]->client_id?>">
						<input type="hidden" name="inv_id" value="<?=$order[0]->invoice_id?>">
			 
			 
				<h3 class='common-h'><?=lang('hd_lang.hosting')?></h3>
				<div class="table-responsive">
				<table class="table table-bordered table-striped hs-table my-3">		
						<thead><tr><th><?=lang('hd_lang.package')?></th><th><?=lang('hd_lang.username')?></th><th><?=lang('hd_lang.password')?></th><th><?=lang('hd_lang.create_controlpanel')?></th><th style="width:180px;text-align:center;"><?=lang('hd_lang.server')?></th><th><?=lang('hd_lang.send_details')?></th></thead>
						<tbody>
						<?php foreach($order as $item) { 
							if($item->type == 'hosting') { ?>	 
							<tr>
							<td><?=$item->item_name?> - <?=$item->domain?></td>
							<td>							
							<input type="hidden" name="service[]" value="<?=$item->item_name?>">
							<input type="hidden" name="hosting[]" value="<?=$item->id?>">
							<input type="hidden" name="hosting_status[]" value="<?=$item->status_id?>">
							<input type="hidden" name="hosting_domain[]" value="<?=$item->domain?>">
							<input type="hidden" name="hosting_item_id[]" value="<?=$item->item_parent?>">
							<input type="text" value="<?=$item->username?>" name="username[]" class="form-control common-input"></td>
							<td><input type="text" value="<?=$item->password?>" name="password[]" class="form-control common-input">
							</td>					
							<td><?php if(User::is_admin() || User::perm_allowed(User::get_id(),'manage_accounts')) { ?>
								<label class="switch">
									<input type="hidden" value="off" name="<?=$item->username?>_controlpanel" />
									<div class="form-check form-switch input-btn-div">
									<input type="checkbox" class='form-check-input switch-cus' name="<?=$item->username?>_controlpanel">
									</div>									
								<span></span>
							</label>
							<?php } ?>
							</td>
							<td>
							<select id="server" name="server[]" class="form-control common-select m-b">							
								<?php
								$db = \Config\Database::connect();
								$parent = $db->table('hd_items_saved')->where('item_id', $item->item_parent)->get()->getRow(); 
								$default_server = $db->table('hd_servers')->where('id', $parent->server)->get()->getRow(); 
								foreach ($servers as $server) { if($default_server){?>
								<option value="<?=$server->id?>" <?=($default_server->id == $server->id) ? 'selected' : ''?>><?=$server->name?></option>
								<?php } else {?>
									<option value="<?=$server->id?>"><?=$server->name?></option>
								<?php }} ?>
							</select>
							</td>
							<td><?php if(User::is_admin() || User::perm_allowed(User::get_id(),'manage_accounts')) { ?><label class="switch">
									<input type="hidden" value="off" name="<?=$item->username?>_send_details[]" />
									<div class="form-check form-switch input-btn-div">
									<input type="checkbox" class='form-check-input switch-cus' name="<?=$item->username?>_send_details[]">	
									</div>								
								<span></span>
							</label>
							<?php } ?></td>
						</tr>
						<?php } } ?>
					</tbody>
				</table>
				</div>
				
				<h3 class='common-h'><?=lang('hd_lang.domains')?></h3>
				<div class="table-responsive">
					<table class="table table-bordered table-striped hs-table my-3">		
						<thead><tr><th><?=lang('hd_lang.service')?></th><th><?=lang('hd_lang.domain')?></th><th><?=lang('hd_lang.authcode')?></th><th><?=lang('hd_lang.nameservers')?></th><th><?=lang('hd_lang.register')?></th><th><?=lang('hd_lang.registrar')?></th></thead>
						<tbody>
								<?php foreach($order as $item) { 
									if($item->type == 'domain' || $item->type == 'domain_only') { ?>
									<tr><td><?=$item->item_name?></td>
									<td><?=$item->domain?></td>
									<td><input type="text" class='common-input' value="<?=$item->authcode?>" name="authcode[]" <?php if($item->item_name != lang('hd_lang.domain_transfer')) { ?> readonly <?php } ?>> </td>
									<td><?=$item->nameservers?></td>
									<td>
									<input type="hidden" name="domain_status[]" value="<?=$item->status_id?>">
									<input type="hidden" name="domain[]" value="<?=$item->id?>">
									<input type="hidden" name="domain_name[]" value="<?=$item->domain?>">
									<input type="hidden" name="domain_item_id[]" value="<?=$item->item_parent?>">
									<?php if(User::is_admin() || User::perm_allowed(User::get_id(),'manage_accounts')) { ?>
									<label class="switch">
									<?php $domain = explode('.', $item->domain, 2); ?>
									<input type="hidden" value="off" name="<?=$domain[0]?>_activate" />
									<div class="form-check form-switch input-btn-div">
									<input type="checkbox" class='form-check-input switch-cus' name="<?=$domain[0]?>_activate">
									</div>
								<span></span>
							</label>
							<?php } ?> 
							</td>
							<td>
							<select name="registrar[]" class="form-control common-select m-b">
							<?php
                                    
                                    $registrars = Plugin::domain_registrars();
                                    foreach ($registrars as $registrar)
                                    {?> 
									<option value="<?=$registrar->system_name;?>"><?=ucfirst($registrar->system_name);?></option>
                                    <?php } ?>
	
							</select></td>
							</tr>
							<?php } } ?>
						</tbody>
					</table>
					</div>

				<?php } ?>
			</div>
		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('hd_lang.close')?></a>
		<button type="submit" style='border:none;' class="btn btn-<?=$custom->getconfig_item('theme_color');?>"><?=lang('hd_lang.activate')?></button>
		</form>
		</div>
	</div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
