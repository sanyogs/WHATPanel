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

<div class="modal-dialog sm-modal">
	<div class="modal-content w-100">
		<div class="modal-header bg-danger row-reverse p-4"> 
		<button type="button" class="close" data-dismiss="modal">&times;</button> 
		<h4 class="modal-title text-white"><?=lang('hd_lang.delete_rate')?></h4>
		</div><?php
			echo form_open(base_url().'tax_rates/delete'); ?>
		<div class="modal-body">
			<p class="common-h"><?=lang('hd_lang.delete_rate_warning')?></p>
			
			<input type="hidden" name="tax_rate_id" value="<?=$tax_rate_id?>">
			<input type="hidden" name="r_url" value="<?=base_url('tax_rates')?>">

		</div>
		<div class="modal-footer p-4"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('hd_lang.close')?></a>
			<button type="submit" class="btn btn-<?=$custom->getconfig_item('theme_color')?> common-button"><?=lang('hd_lang.delete_button')?></button>
		</form>
	</div>
</div>
<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->