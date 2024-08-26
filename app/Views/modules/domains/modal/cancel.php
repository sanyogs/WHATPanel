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
$helper = new custom_name_helper();
?>

<div class="modal-dialog my-modal">
	<div class="modal-content">
		<div class="modal-header row-reverse"> <button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title"><?=lang('hd_lang.cancel_order')?></h4>
		</div><?php
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'domains/cancel',$attributes); ?>
		<div class="modal-body">

		<input type="hidden" name="id" value="<?=$item->id?>">
		<input type="hidden" name="domain" value="<?=$item->item_desc?>">
		<input type="hidden" name="order" value="<?=$item->type?>">
		<input type="hidden" name="inv_id" value="<?=$item->invoice_id?>">
			
		<h3 class="common-h"><?=$item->domain?></h3>
					<table class="table table-bordered table-striped">		
						<thead><tr><th class="common-h"><?=lang('hd_lang.service')?></th><th><?=lang('hd_lang.nameservers')?></th><th><?=lang('hd_lang.cancel')?></th></thead>
						<tbody> 
							<tr class="common-h"><td><?=$item->item_name?></td>
							<td class="common-h"><?=$item->nameservers?></td>
							<td class="common-h">							
							<label class="switch common-label">
									<input type="hidden" value="off" name="cancel_domain" />
									<input type="checkbox" <?php if($item->status_id == 6){ echo "checked=\"checked\""; } ?> name="cancel_domain">
								<span></span>
							</label></td></tr>				 
					</tbody>
				</table>
				<div class="row"> 
				<div class="col-md-12">
					<span class="pull-right"><?=lang('hd_lang.credit_account_item')?>: <label class="switch">
						<input type="hidden" value="off" name="credit_account" />
						<input type="checkbox" name="credit_account">									
						<span></span>
						</label>
					</span>
				</div>
				</div>	
				 
		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('hd_lang.close')?></a>
		<button <?=($helper->getconfig_item('demo_mode') == 'TRUE') ? 'disabled' : ''?> type="submit" class="btn btn-<?=$helper->getconfig_item('theme_color');?>"><?=lang('hd_lang.cancel_domain')?></button>
		</form>
		</div>
	</div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
