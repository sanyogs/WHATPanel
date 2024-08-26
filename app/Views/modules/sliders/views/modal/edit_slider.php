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

$custom_name_helper = new custom_name_helper();
?>
<div class="modal-dialog my-modal">
	<div class="modal-content">
		<div class="modal-header row-reverse">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h4 class="modal-title"><?= lang('hd_lang.edit_slider') ?></h4>
		</div> 
		<?php
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'sliders/edit',$attributes); ?>
          <input type="hidden" name="slider_id" value="<?=$slider->slider_id?>">
		<div class="modal-body">
			 
          		<div class="form-group modal-input">
				<label class="col-lg-4 control-label"><?= lang('hd_lang.name') ?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
					<input type="text" class="form-control" required value="<?=$slider->name?>" name="name">
				</div>
				</div>   
		</div>
		<div class="modal-footer">
            <a href="#" class="btn btn-default" data-dismiss="modal"><?= lang('hd_lang.close') ?></a>
            <button type="submit" class="btn btn-<?= $custom_name_helper->getconfig_item('theme_color'); ?>"><?= lang('hd_lang.save_changes') ?></button>
        </div>
		</form>
	</div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
<script src="<?=base_url()?>resource/js/libs/jquery.maskMoney.min.js" type="text/javascript"></script>
	<script>
	(function($){
    		"use strict";
		    $('.money').maskMoney();
	})(jQuery);  

</script>