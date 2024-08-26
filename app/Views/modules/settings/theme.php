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

		$attributes = array('class' => 'bs-example form-horizontal');
		echo form_open_multipart('settings/update', $attributes); ?>

	<?php //echo validation_errors(); ?>
	<input type="hidden" name="settings" value="<?=$load_setting?>">
	<input type="hidden" name="categories" value="<?=$load_setting?>">
	<input type="hidden" name="return_url" value="<?= base_url() ?>settings/theme">

	<input type="hidden" name="top_bar-color" value="<?=$custom_name->getconfig_item('top_bar_color', $temp_data)?>">


	<div class="form-group">
	    <label class="control-label common-label"><?=lang('hd_lang.skin')?></label>
	    <div class="inputDiv">
	        <ul id="skins" class="list-unstyled clearfix"></ul>
	    </div>
	</div>

	<!-- <div class="form-group">
		<label class="control-label common-label"><?//=lang('hd_lang.website_theme')?> </label>
		<div class="inputDiv">
			<select name="active_theme" class="common-input">
				<option value="original"
					<?//=($custom_name->getconfig_item('active_theme', $temp_data) == 'original') ? 'selected' : ''?>>Original
				</option>
				<option value="custom"
					<?//=($custom_name->getconfig_item('active_theme', $temp_data) == 'custom') ? 'selected' : ''?>>Custom
				</option>
			</select>
		</div>
	</div> -->

	<div class="form-group">
	    <label class="control-label common-label"><?=lang('hd_lang.system_font')?> </label>
	    <div class="inputDiv">
	        <?php $font = $custom_name->getconfig_item('system_font', $temp_data); ?>
	        <select name="system_font" class="common-input">
	            <option value="open_sans" <?=($font == "open_sans" ? ' selected="selected"' : '')?>>Open Sans</option>
	            <option value="open_sans_condensed" <?=($font == "open_sans_condensed" ? ' selected="selected"' : '')?>>
	                Open Sans Condensed</option>
	            <option value="roboto" <?=($font == "roboto" ? ' selected="selected"' : '')?>>Roboto</option>
	            <option value="roboto_condensed" <?=($font == "roboto_condensed" ? ' selected="selected"' : '')?>>Roboto
	                Condensed</option>
	            <option value="ubuntu" <?=($font == "ubuntu" ? ' selected="selected"' : '')?>>Ubuntu</option>
	            <option value="lato" <?=($font == "lato" ? ' selected="selected"' : '')?>>Lato</option>
	            <option value="oxygen" <?=($font == "oxygen" ? ' selected="selected"' : '')?>>Oxygen</option>
	            <option value="pt_sans" <?=($font == "pt_sans" ? ' selected="selected"' : '')?>>PT Sans</option>
	            <option value="source_sans" <?=($font == "source_sans" ? ' selected="selected"' : '')?>>Source Sans Pro
	            </option>
	        </select>
	    </div>
	</div>

	<div class="form-group">
	    <label class="control-label common-label"><?=lang('hd_lang.theme_color')?></label>
	    <div class="inputDiv">
	        <?php $theme = $custom_name->getconfig_item('theme_color', $temp_data); ?>
	        <select name="theme_color" class="common-input">
	            <option value="success" <?=($theme == "success" ? ' selected="selected"' : '')?>>Success</option>
	            <option value="info" <?=($theme == "info" ? ' selected="selected"' : '')?>>Info</option>
	            <option value="danger" <?=($theme == "danger" ? ' selected="selected"' : '')?>>Danger</option>
	            <option value="warning" <?=($theme == "warning" ? ' selected="selected"' : '')?>>Warning</option>
	            <option value="dark" <?=($theme == "dark" ? ' selected="selected"' : '')?>>Dark</option>
	            <option value="primary" <?=($theme == "primary" ? ' selected="selected"' : '')?>>Primary</option>
	        </select>
	    </div>
	</div>



	<div class="form-group">
	    <label class="control-label common-label"><?=lang('hd_lang.logo_or_icon')?></label>
	    <div class="inputDiv">
	        <select name="logo_or_icon" class="common-input">
	            <?php $logoicon = $custom_name->getconfig_item('logo_or_icon', $temp_data); ?>
	            <option value="icon_title" <?=($logoicon == "icon_title" ? ' selected="selected"' : '')?>>
	                <?=lang('hd_lang.icon')?> & <?=lang('hd_lang.site_name')?></option>
	            <option value="icon" <?=($logoicon == "icon" ? ' selected="selected"' : '')?>><?=lang('hd_lang.icon')?>
	            </option>
	            <option value="logo_title" <?=($logoicon == "logo_title" ? ' selected="selected"' : '')?>>
	                <?=lang('hd_lang.logo')?> & <?=lang('hd_lang.site_name')?></option>
	            <option value="logo" <?=($logoicon == "logo" ? ' selected="selected"' : '')?>><?=lang('hd_lang.logo')?>
	            </option>
	        </select>
	    </div>
	</div>

	<div class="form-group">
	    <label class="control-label common-label"><?=lang('hd_lang.site_icon')?></label>
	    <div class="input-group iconpicker-container col-lg-3">
	        <span class="input-group-addon"><i
	                class="fa <?=$custom_name->getconfig_item('site_icon', $temp_data)?>"></i></span>
	        <input id="site-icon" name="site_icon" type="text"
	            value="<?=$custom_name->getconfig_item('site_icon', $temp_data)?>"
	            class="form-control common-input icp icp-auto iconpicker-element iconpicker-input" data-placement="bottomRight">
	    </div>
	</div>


	<div class="form-group">
	    <label class="control-label common-label"><?=lang('hd_lang.company_logo')?></label>
	    <div class="col-lg-2">
	        <input type="file" class="filestyle" data-buttonText="<?=lang('hd_lang.choose_file')?>" data-icon="false"
	            data-classButton="btn btn-default" data-classInput="form-control inline input-s" name="logofile">
	    </div>
	    <div class="col-lg-7">
	        <?php if ($custom_name->getconfig_item('company_logo', $temp_data) != '') : ?>
	        <div class="settings-image"><img
	                src="<?=base_url()?>uploads/files/<?=$custom_name->getconfig_item('company_logo', $temp_data)?>" />
	        </div>
	        <?php endif; ?>
	    </div>
	</div>



	<div class="form-group">
	    <label class="control-label common-label"><?=lang('hd_lang.favicon')?></label>
	    <div class="col-lg-2">
	        <input type="file" class="filestyle" data-buttonText="<?=lang('hd_lang.choose_file')?>" data-icon="false"
	            data-classButton="btn btn-default" data-classInput="form-control inline input-s" name="iconfile">
	    </div>
	    <div class="col-lg-7">
	        <?php if ($custom_name->getconfig_item('site_favicon', $temp_data) != '') : ?>
	        <div class="settings-image"><img
	                src="<?=base_url()?>uploads/files/<?=$custom_name->getconfig_item('site_favicon', $temp_data)?>" />
	        </div>
	        <?php endif; ?>
	    </div>
	</div>

	<div class="form-group">
	    <label class="control-label common-label"><?=lang('hd_lang.apple_icon')?></label>
	    <div class="col-lg-2">
	        <input type="file" class="filestyle" data-buttonText="<?=lang('hd_lang.choose_file')?>" data-icon="false"
	            data-classButton="btn btn-default" data-classInput="form-control inline input-s" name="appleicon">
	    </div>
	    <div class="col-lg-7">
	        <?php if ($custom_name->getconfig_item('site_appleicon', $temp_data) != '') : ?>
	        <div class="settings-image"><img
	                src="<?=base_url()?>uploads/files/<?=$custom_name->getconfig_item('site_appleicon', $temp_data)?>" />
	        </div>
	        <?php endif; ?>
	    </div>
	</div>



	<div class="text-center">
	    <button type="submit"
	        class="btn btn-sm common-button  btn-<?=$custom_name->getconfig_item('theme_color', $temp_data);?>"><?=lang('hd_lang.save_changes')?></button>
	</div>

	</form>


	<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
	    crossorigin="anonymous"></script>
	<script>
$(document).ready(function() {
    $('[data-skin]').on('click', function(e) {
        e.preventDefault();
        changeSkin($(this).data('skin'));
    });
});

function changeSkin(cls) {
    $.each(mySkins, function(i) {
        $('body').removeClass(mySkins[i])
    })

    $('body').addClass(cls);

    $.ajax({
        url: '<?= base_url('settings/skin') ?>',
        type: 'POST',
        data: {
            skin: cls,
            categories: 'theme'
        },
        dataType: 'json',
        success: function(data) {
            console.log(data);
        },


        error: function(data) {}
    });
}
	</script>

	<!-- End Form -->