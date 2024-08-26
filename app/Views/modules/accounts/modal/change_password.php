
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

use App\Libraries\AppLib;

$custom_name_helper = new custom_name_helper();
?>
<div class="modal-dialog my-modal">
	<div class="modal-content">
		<div class="modal-header row-reverse"> <button type="button" class="close" data-dismiss="modal">&times;</button> 
		<h4 class="modal-title"><?=lang('hd_lang.change_password')?></h4>
		</div><?php
			echo form_open(base_url().'accounts/change_password'); ?>
		<div class="modal-body">
		<div class="row">
					<label class="control-label col-lg-3 common-label"><?=lang('hd_lang.new_password')?></label>
					<div class="col-lg-6">
						<input type="text" class="form-control common-input" name="password" required="required">
						<input type="hidden" name="id" value="<?=$id?>">
					</div>
				</div>		
		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('hd_lang.close')?></a>
			<button type="submit" class="btn btn-<?=$custom_name_helper->getconfig_item('theme_color');?>"><?=lang('hd_lang.change_password')?></button>
		</form>
	</div>
</div>
<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->