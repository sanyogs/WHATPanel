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
		<div class="modal-header bg-<?=$custom->getconfig_item('theme_color');?>"> <button type="button" class="close" data-dismiss="modal">&times;</button> 
		<h4 class="modal-title"><?=lang('hd_lang.unsuspend_account')?></h4>
		</div><?php
			echo form_open(base_url().'accounts/unsuspend'); ?>
		<div class="modal-body">
			<h4><?=lang('hd_lang.unsuspend_warning')?></h4>   
			<input type="hidden" name="id" value="<?=$id?>">

		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('hd_lang.close')?></a>
			<button type="submit" class="btn btn-<?=$custom->getconfig_item('theme_color');?>"><?=lang('hd_lang.unsuspend')?></button>
		</form>
	</div>
</div>
<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->