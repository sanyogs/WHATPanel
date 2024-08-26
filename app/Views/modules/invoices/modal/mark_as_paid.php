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
		<h4 class="modal-title"><?=lang('hd_lang.mark_as_paid')?></h4>
		</div><?php
			echo form_open(base_url().'invoices/mark_as_paid'); ?>
		<div class="modal-body">
			<p class="common-h"><?=lang('hd_lang.mark_as_paid_notice')?></p>
			
			<input type="hidden" name="invoice" value="<?=$invoice?>">
			<input type="hidden" name="domain_type" value="existingdomain" />
		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-default common-button" data-dismiss="modal" style="background-color:red"><?=lang('hd_lang.close')?></a>
			<button type="submit" class="btn btn-<?=$custom->getconfig_item('theme_color')?> common-button"><?=lang('hd_lang.mark_as_paid')?></button>
		</form>
	</div>
</div>
<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->