	<!-- Start Form -->
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
        $custom_name = new custom_name_helper();
 
		$attributes = array('class' => 'bs-example form-horizontal invoiceWrap');
		echo form_open_multipart('settings/update', $attributes); ?>

	<input type="hidden" name="settings" value="<?=$load_setting?>">
	<input type="hidden" name="categories" value="<?=$load_setting?>">
	<input type="hidden" name="return_url" value="<?= base_url() ?>settings/invoice">

 
	<div class="row xxxx">
	<div class="form-group col-lg-6 col-md-12 col-12">
	    <label class="control-label common-label"><?=lang('hd_lang.automatic_activation')?></label>
	    <div class="inputDiv form-check form-switch input-btn-div">

	        <input type="hidden" value="off" name="automatic_activation" />
	        <input type="checkbox" role="switch" class="form-check-input"
	            <?php if($custom_name->getconfig_item('automatic_activation', $temp_data) == 'TRUE'){ echo "checked=\"checked\""; } ?>
	            name="automatic_activation">

	    </div> 
	</div>


	<div class="form-group col-lg-6 col-md-12 col-12">
	    <label class="control-label common-label"><?=lang('hd_lang.suspend_due')?></label>
	    <div class="inputDiv form-check form-switch input-btn-div">

	        <input type="hidden" value="off" name="suspend_due" />
	        <input type="checkbox" role="switch" class="form-check-input"
	            <?php if($custom_name->getconfig_item('suspend_due', $temp_data) == 'TRUE'){ echo "checked=\"checked\""; } ?>
	            name="suspend_due">

	    </div>
	</div>


	<div class="form-group col-lg-6 col-md-12 col-12">
	    <label class="control-label common-label"><?=lang('hd_lang.terminate_due')?></label>
	    <div class="inputDiv form-check form-switch input-btn-div">

	        <input type="hidden" value="off" name="terminate_due" />
	        <input type="checkbox" role="switch" class="form-check-input"
	            <?php if($custom_name->getconfig_item('terminate_due', $temp_data) == 'TRUE'){ echo "checked=\"checked\""; } ?>
	            name="terminate_due">

	    </div>
	</div>


	<div class="form-group col-lg-6 col-md-12 col-12">
	    <label class="control-label common-label"><?=lang('hd_lang.apply_credit')?></label>
	    <div class="inputDiv form-check form-switch input-btn-div">

	        <input type="hidden" value="off" name="apply_credit" />
	        <input type="checkbox" role="switch" class="form-check-input"
	            <?php if($custom_name->getconfig_item('apply_credit', $temp_data) == 'TRUE'){ echo "checked=\"checked\""; } ?>
	            name="apply_credit">

	    </div>
	</div>


	<div class="form-group col-lg-6 col-md-12 col-12">
	    <label class="control-label common-label"><?=lang('hd_lang.notify_admin_payment_received')?></label>
	    <div class="inputDiv form-check form-switch input-btn-div">

	        <input type="hidden" value="off" name="notify_payment_received" />
	        <input type="checkbox" role="switch" class="form-check-input"
	            <?php if($custom_name->getconfig_item('notify_payment_received', $temp_data) == 'TRUE'){ echo "checked=\"checked\""; } ?>
	            name="notify_payment_received">

	    </div>
	</div>


	<div class="form-group col-lg-6 col-md-12 col-12">
	    <label class="control-label common-label"><?=lang('hd_lang.display_invoice_badge')?></label>
	    <div class="inputDiv form-check form-switch input-btn-div">

	        <input type="hidden" value="off" name="display_invoice_badge" />
	        <input type="checkbox" role="switch" class="form-check-input"
	            <?php if($custom_name->getconfig_item('display_invoice_badge', $temp_data) == 'TRUE'){ echo "checked=\"checked\""; } ?>
	            name="display_invoice_badge">

	    </div>
	</div>

	<div class="form-group col-lg-6 col-md-12 col-12">
	    <label class="control-label common-label"><?=lang('hd_lang.automatic_email_on_recur')?></label>
	    <div class="inputDiv form-check form-switch input-btn-div">

	        <input type="hidden" value="off" name="automatic_email_on_recur" />
	        <input type="checkbox" role="switch" class="form-check-input"
	            <?php if($custom_name->getconfig_item('automatic_email_on_recur', $temp_data) == 'TRUE'){ echo "checked=\"checked\""; } ?>
	            name="automatic_email_on_recur">

	    </div>
	</div>


	<div class="form-group col-lg-6 col-md-12 col-12">
	    <label class="control-label common-label">Order Tax</label>
	    <div class="inputDiv form-check form-switch input-btn-div">

	        <input type="hidden" value="off" name="order_tax" />
	        <input type="checkbox" role="switch" class="form-check-input"
	            <?php if($custom_name->getconfig_item('order_tax', $temp_data) == 'TRUE'){ echo "checked=\"checked\""; } ?>
	            name="order_tax">

	    </div>
	</div>


	<div class="form-group col-lg-6 col-md-12 col-12">
	    <label class="control-label common-label"><?=lang('hd_lang.show_item_tax')?></label>
	    <div class="inputDiv form-check form-switch input-btn-div">

	        <input type="hidden" value="off" name="show_invoice_tax" />
	        <input type="checkbox" role="switch" class="form-check-input"
	            <?php if($custom_name->getconfig_item('show_invoice_tax', $temp_data) == 'TRUE'){ echo "checked=\"checked\""; } ?>
	            name="show_invoice_tax">

	    </div>
	</div>


	<div class="form-group col-lg-6 col-md-12 col-12">
	    <label class="control-label common-label"><?=lang('hd_lang.round_off_value')?></label>
	    <div class="inputDiv form-check form-switch input-btn-div">

	        <input type="hidden" value="off" name="round_off_value" />
	        <input type="checkbox" role="switch" class="form-check-input"
	            <?php if($custom_name->getconfig_item('round_off_value', $temp_data) == 'TRUE'){ echo "checked=\"checked\""; } ?>
	            name="round_off_value">

	    </div>
	</div>


	<div class="form-group col-lg-6 col-md-12 col-12">
	    <label class="control-label common-label"><?=lang('hd_lang.invoice_color')?> <span
	            class="text-danger">*</span></label>
	    <div class="col-lg-6">
	        <input type="text" name="invoice_color" class="form-control common-input"
	            value="<?=$custom_name->getconfig_item('invoice_color', $temp_data)?>" required>
	    </div>
	</div>

	<div class="form-group col-lg-6 col-md-12 col-12">
	    <label class="control-label common-label"><?=lang('hd_lang.invoice_prefix')?> <span
	            class="text-danger">*</span></label>
	    <div class="col-lg-6">
	        <input type="text" name="invoice_prefix" class="form-control common-input"
	            value="<?=$custom_name->getconfig_item('invoice_prefix', $temp_data)?>" required>
	    </div>
	</div>

	<div class="form-group col-lg-6 col-md-12 col-12">
	    <label class="control-label common-label"><?=lang('hd_lang.invoices_due_before')?></label>
	    <div class="col-lg-6 smallBoxWrap">
	        <input type="text" name="invoices_due_before" class="form-control common-input"
	            value="<?=$custom_name->getconfig_item('invoices_due_before', $temp_data)?>" required>
	        <span class="help-block m-b-none myDaysMsg"><?=lang('hd_lang.days')?></span>
	    </div>

	</div>


	<div class="form-group col-lg-6 col-md-12 col-12">
	    <label class="control-label common-label"><?=lang('hd_lang.invoices_due_after')?> <span
	            class="text-danger">*</span></label>
	    <div class="col-lg-6 smallBoxWrap">
	        <input type="text" name="invoices_due_after" class="form-control common-input"
	            value="<?=$custom_name->getconfig_item('invoices_due_after', $temp_data)?>" required>
	        <span class="help-block m-b-none myDaysMsg"><?=lang('hd_lang.days')?></span>
	    </div>

	</div>


	<div class="form-group col-lg-6 col-md-12 col-12">
	    <label class="control-label common-label"><?=lang('hd_lang.suspend_after')?></label>
	    <div class="col-lg-6 smallBoxWrap">
	        <input type="text" name="suspend_after" class="form-control common-input"
	            value="<?=$custom_name->getconfig_item('suspend_after', $temp_data)?>" required>
	        <span class="help-block m-b-none myDaysMsg"><?=lang('hd_lang.days')?></span>
	    </div>

	</div>


	<div class="form-group col-lg-6 col-md-12 col-12">
	    <label class="control-label common-label"><?=lang('hd_lang.terminate_after')?></label>
	    <div class="col-lg-6 smallBoxWrap">
	        <input type="text" name="terminate_after" class="form-control common-input"
	            value="<?=$custom_name->getconfig_item('terminate_after', $temp_data)?>" required>
	        <span class="help-block m-b-none myDaysMsg"><?=lang('hd_lang.days')?></span>
	    </div>
	</div>

	<div class="form-group col-lg-6 col-md-12 col-12">
	    <label class="control-label common-label"><?=lang('hd_lang.invoice_start_number')?></label>
	    <div class="col-lg-6">
	        <input type="text" name="invoice_start_no" class="form-control common-input"
	            value="<?=$custom_name->getconfig_item('invoice_start_no', $temp_data)?>">
	    </div>
	</div>

	<div class="form-group col-lg-6 col-md-12 col-12 terms">
	    <label class="control-label common-label"><?=lang('hd_lang.invoice_footer')?></label>
	    <div class="inputDiv">
	        <input type="text" class="form-control common-input" name="invoice_footer"
	            value="<?=$custom_name->getconfig_item('invoice_footer', $temp_data)?>">
	    </div>
	</div>
	
	<div class="form-group col-lg-6 col-md-12 col-12">
	    <label class="control-label common-label"><?=lang('hd_lang.invoice_logo')?></label>
	    
	        <div class="row">
	            <div class="col-lg-8">
	                <input type="file" class="filestyle" data-buttonText="<?=lang('hd_lang.choose_file')?>"
	                    data-icon="false" data-classButton="btn btn-default" data-classInput="form-control inline input-s"
													  name="invoicelogo">
	            </div>
	        </div>
	        <?php if ($custom_name->getconfig_item('invoice_logo', $temp_data) != '') : ?>
	        <div class="row">
				<div class="col-lg-7">
					<div id="invoice-logo-slider"></div>
				</div>
				<div class="col-lg-5">
					<div id="invoice-logo-dimensions">
						<p>120px x 350.712px</p>
					</div>
				</div>
			</div>
	        <input id="invoice-logo-height" type="hidden"
	            value="<?=$custom_name->getconfig_item('invoice_logo_height', $temp_data)?>" name="invoice_logo_height" />
	        <input id="invoice-logo-width" type="hidden"
	            value="<?=$custom_name->getconfig_item('invoice_logo_width', $temp_data)?>" name="invoice_logo_width" />
	        <div class="row h_150 mb_15">
	            <div class="col-lg-6">
	                <div class="invoice_image" style="height: 120px">
	                    <img src="<?=base_url()?>public/uploads/files/<?=$custom_name->getconfig_item('invoice_logo', $temp_data)?>"
	                        class="invoice_images" />
	                </div>
	            </div>
	        </div>
	    
	    <?php endif; ?>
	</div>


	<div class="form-group col-lg-12 col-md-12 col-12 terms">
	    <label class="control-label common-label"><?=lang('hd_lang.default_terms')?></label>
	    <div class="inputDiv">
	        <textarea class="form-control foeditor common-input"
	            name="default_terms"><?=$custom_name->getconfig_item('default_terms', $temp_data)?></textarea>
	    </div>
	</div>

	


	<div class="text-center">
	    <!-- <button type="submit" class="btn btn-sm common-button btn-<?=$custom_name->getconfig_item('theme_color', $temp_data);?>"><?=lang('hd_lang.save_changes')?></button> -->
	    <button type="submit" class="btn btn-sm common-button btn-primary"><?=lang('hd_lang.save_changes')?></button>
	</div>
	</div>

	</form>
<script src="<?= base_url() ?>js/ckeditor/ckeditor.js"></script>
<script>
	$(document).ready(function() {
		var textarea = document.getElementsByClassName('foeditor')[0];
		CKEDITOR.replace(textarea, {
			height: 300,
			filebrowserUploadUrl: "<?= base_url() ?>media/upload"
		});
	});
	// Get the range slider element
	var slider = document.getElementById("logo-slider");

	// Get the dimensions display elements
	var heightDisplay = document.getElementById("logo-height");
	var widthDisplay = document.getElementById("logo-width");
	var invoiceImage = document.querySelector(".invoice_images");
	// Display initial values
	heightDisplay.innerText = slider.value;
	widthDisplay.innerText = slider.value;

	// Update dimensions display when slider value changes
	slider.oninput = function() {
		heightDisplay.innerText = this.value;
		widthDisplay.innerText = this.value;
		// invoiceImage.style.height = this.value + "px";
		invoiceImage.style.width = this.value + "px";

	};
</script>
	<!-- End Form -->