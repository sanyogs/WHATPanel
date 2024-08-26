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
		<h4 class="modal-title"><?=lang('hd_lang.delete_account')?></h4>
		</div><?php
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'accounts/delete',$attributes); ?>
		<div class="modal-body">

		<input type="hidden" name="id" value="<?=$item->id?>">
				<div class="form-group">
						<label class="col-lg-5 control-label"><?=lang('hd_lang.delete_controlpanel')?></label>
						<div class="col-lg-5"><label class="switch">
									<input type="hidden" value="off" name="delete_controlpanel" />
									<input type="checkbox" name="delete_controlpanel">									
								<span></span>
							</label>
					</div>	 
				</div>
		 

				<h3><?=lang('hd_lang.hosting')?></h3>

				<table class="table table-bordered table-striped">		
						<thead><tr><th><?=lang('hd_lang.package')?></th><th><?=lang('hd_lang.username')?></th><th><?=lang('hd_lang.password')?></th></thead>
						<tbody>
							<tr><td><?=$item->item_name?></td>
							<td><input type="text" value="<?=$item->username?>" name="username" class="form-control" readonly="readonly"></td>
							<td><input type="text" value="<?=$item->password?>" name="password" class="form-control" readonly="readonly"></td>
							</tr>

					</tbody>
				</table> 
				
				<div class="row"> 
				<div class="nh-noflex-wrap">
				<div class="nh-category-title"><p class="common-h"><?=lang('hd_lang.credit_account_item')?>: <label class="switch"></p></div>
					<div class="nh-input-btnwrap">
						<div class="form-check form-switch input-btn-div">
							<input type="hidden" value="off" name="credit_account" />
							<input type="checkbox" name="credit_account" class="form-check-input" style="width: 6.2rem;height: 3.3rem;">	
						</div> 
						</label>
					</div>
				</div>
				</div>

		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('hd_lang.close')?></a>
		<button <?=($custom->getconfig_item('demo_mode') == 'TRUE') ? 'disabled' : ''?> type="submit" class="btn btn-<?=$custom->getconfig_item('theme_color');?>"><?=lang('hd_lang.delete_account')?></button>
		</form>
		</div>
	</div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
