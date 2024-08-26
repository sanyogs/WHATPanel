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
		<h4 class="modal-title"><?=lang('hd_lang.new_tax_rate')?></h4>
		</div>

		<?php
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url('tax_rates/add'),$attributes); ?>
		<div class="modal-body">
		<input type="hidden" name="r_url" value="<?=base_url('tax_rates')?>">
          		<div class="form-group">
				<label class="col-lg-4 control-label common-label"><?=lang('hd_lang.tax_rate_name')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
					<input type="text" class="form-control common-input" required placeholder="<?=lang('hd_lang.vat')?>" name="tax_rate_name">
				</div>
				</div>

				<div class="form-group">
				<label class="col-lg-4 control-label common-label"><?=lang('hd_lang.tax_rate_percent')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
					<input type="text" class="form-control money common-input" required placeholder="12" name="tax_rate_percent">
				</div>
				</div>
		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('hd_lang.close')?></a> 
		<button type="submit" class="btn btn-<?=$custom->getconfig_item('theme_color');?> common-button"><?=lang('hd_lang.save_changes')?></button>
		</form>
		</div>
	</div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
<script src="<?=base_url()?>resource/js/libs/jquery.maskMoney.min.js" type="text/javascript"></script>
<script>
	(function($){
	$('.money').maskMoney();
})(jQuery);  

</script>