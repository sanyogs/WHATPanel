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
    use App\Models\App;
    $attributes = array('class' => 'bs-example form-horizontal');
    $custom_name = new custom_name_helper();
    echo form_open_multipart('settings/update', $attributes); ?>
    <input type="hidden" name="settings" value="<?= $load_setting ?>">
    <input type="hidden" name="categories" value="<?= $load_setting ?>">
    <input type="hidden" name="return_url" value="<?= base_url() ?>settings/system">
    <div class="form-group">
        <label class="control-label common-label"><?= lang('hd_lang.default_language') ?></label>
        <div class="inputDiv">
            <select name="default_language" id="default_language" class="form-control common-input">
                <?php foreach ($languages as $lang) : ?>
                            <option lang="<?= $lang->code ?>" value="<?= $lang->name ?>" <?= ($custom_name->getconfig_item('default_language', $temp_data) == $lang->name ? ' selected="selected"' : '') ?>><?= ucfirst($lang->name) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>


    <div class="form-group">
        <label class="control-label common-label"><?= lang('hd_lang.locale') ?></label>
        <div class="inputDiv">
            <select class="select2-option form-control common-input" name="locale" id="locale" required>
                <?php foreach ($locales as $loc) : ?>
                    <option lang="<?= $loc->code ?>" value="<?= $loc->locale ?>" <?= ($custom_name->getconfig_item('locale', $temp_data) == $loc->locale ? ' selected="selected"' : '') ?>><?= $loc->name ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label common-label"><?= lang('hd_lang.timezone') ?></label>
        <div class="inputDiv">
        <select class="select2-option form-control common-input" name="timezone" required>
        <?php
            foreach ($timezones as $timezone => $description) {
                $selected = ($custom_name->getconfig_item('timezone', $temp_data) == $timezone) ? 'selected="selected"' : '';
            ?>
            <option value="<?= $timezone ?>" <?= $selected ?>><?= $description ?></option>
        <?php } ?>
        </select>
        </div>
    </div>



    <div class="form-group">
        <label class=" control-label common-label"><?= lang('hd_lang.default_currency') ?></label>
        <div class="inputDiv">
                <?php 
                
                $curre = $custom_name->getconfig_item('default_currency', $temp_data);

                if ($curre !== null) {
                    $curreN = trim($curre);
                } else {
                    $curreN = '';
                }
                ?>
            <select name="default_currency" class="form-control common-input">
                <?php foreach ($currencies as $cur) : ?>
                    <option <?php if($curreN==$cur->code) {?> selected="selected"<?php } else {echo false; } ?> value="<?= $cur->code ?>"><?= $cur->name ?></option>
                <?php endforeach; ?>
            </select>

        </div>
        <a class="btn btn-success btn-sm common-button my-3" data-toggle="ajaxModal" href="<?= base_url() ?>settings1/add_currency"><?= lang('hd_lang.add_currency') ?></a>
    </div>

    <div class="form-group">
        <label class="control-label common-label"><?= lang('hd_lang.default_currency_symbol') ?></label>
        <div class="inputDiv">
        <?php
        
            $cur1 = $custom_name->getconfig_item('default_currency_symbol', $temp_data);

            if ($cur1 !== null) {
                $curr1N = trim($cur1);
            } else {
                $curr1N = '';
            }
        
        ?>
            <select name="default_currency_symbol" class="form-control common-input">
                <?php foreach ($currencies as $cur) : ?>
                    <option <?php if($curr1N==$cur->symbol) {?> selected="selected"<?php } ?> value="<?= $cur->symbol ?>"><?= $cur->symbol ?> - <?= $cur->name ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label common-label"><?= lang('hd_lang.currency_position') ?></label>
        <div class="inputDiv">
            <select name="currency_position" class="form-control common-input">
                <option value="before" <?= ($custom_name->getconfig_item('currency_position', $temp_data) == 'before' ? ' selected="selected"' : '') ?>><?= lang('hd_lang.before_amount') ?></option>
                <option value="after" <?= ($custom_name->getconfig_item('currency_position', $temp_data) == 'after' ? ' selected="selected"' : '') ?>><?= lang('hd_lang.after_amount') ?></option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label common-label"><?= lang('hd_lang.currency_decimals') ?></label>
        <div class="inputDiv">
            <select name="currency_decimals" class="form-control common-input">
                <option value="0" <?= ($custom_name->getconfig_item('currency_decimals', $temp_data) == 0 ? ' selected="selected"' : '') ?>>0</option>
                <option value="1" <?= ($custom_name->getconfig_item('currency_decimals', $temp_data) == 1 ? ' selected="selected"' : '') ?>>1</option>
                <option value="2" <?= ($custom_name->getconfig_item('currency_decimals', $temp_data) == 2 ? ' selected="selected"' : '') ?>>2</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label common-label"><?= lang('hd_lang.decimal_separator') ?></label>
        <div class="inputDiv">
            <input type="text" class="form-control common-input" value="<?= $custom_name->getconfig_item('decimal_separator', $temp_data) ?>" name="decimal_separator">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label common-label"><?= lang('hd_lang.thousand_separator') ?></label>
        <div class="inputDiv">
            <input type="text" class="form-control common-input" value="<?= $custom_name->getconfig_item('thousand_separator', $temp_data) ?>" name="thousand_separator">
        </div>
    </div>

    <div class="form-group">
        <label class="control-label common-label"><?= lang('hd_lang.tax') ?> %</label>
        <div class="inputDiv">
            <input type="text" class="form-control money common-input" value="<?= $custom_name->getconfig_item('default_tax', $temp_data) ?>" name="default_tax">
        </div>
    </div>

    <div class="form-group">
        <label class="control-label common-label">Tax Name</label>
        <div class="inputDiv">
            <input type="text" class="form-control common-input" value="<?= $custom_name->getconfig_item('tax_name', $temp_data) ?>" name="tax_name">
        </div>
    </div>


    <div class="form-group">
        <label class="control-label common-label"><?= lang('hd_lang.tax_decimals') ?></label>
        <div class="inputDiv">
            <select name="tax_decimals" class="form-control common-input">
                <option value="0" <?= ($custom_name->getconfig_item('tax_decimals', $temp_data) == 0 ? ' selected="selected"' : '') ?>>0</option>
                <option value="1" <?= ($custom_name->getconfig_item('tax_decimals', $temp_data) == 1 ? ' selected="selected"' : '') ?>>1</option>
                <option value="2" <?= ($custom_name->getconfig_item('tax_decimals', $temp_data) == 2 ? ' selected="selected"' : '') ?>>2</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class=" control-label common-label"><?= lang('hd_lang.quantity_decimals') ?></label>
        <div class="inputDiv">
            <select name="quantity_decimals" class="form-control common-input">
                <option value="0" <?= ($custom_name->getconfig_item('quantity_decimals', $temp_data) == 0 ? ' selected="selected"' : '') ?>>0</option>
                <option value="1" <?= ($custom_name->getconfig_item('quantity_decimals', $temp_data) == 1 ? ' selected="selected"' : '') ?>>1</option>
                <option value="2" <?= ($custom_name->getconfig_item('quantity_decimals', $temp_data) == 2 ? ' selected="selected"' : '') ?>>2</option>
            </select>
        </div>
    </div>

    <?php $date_format = $custom_name->getconfig_item('date_format', $temp_data); ?>
	<div class="form-group">
		<label class="control-label common-label"><?= lang('hd_lang.date_format') ?></label>
		<div class="inputDiv">
			<select name="date_format" class="form-control common-input">
				<option value="%d-%m-%Y" <?= ($date_format == "%d-%m-%Y" ? ' selected="selected"' : '') ?>><?= strftime("%d-%m-%Y", time()) ?> (DD-MM-YYYY)</option>
				<option value="%m-%d-%Y" <?= ($date_format == "%m-%d-%Y" ? ' selected="selected"' : '') ?>><?= strftime("%m-%d-%Y", time()) ?> (MM-DD-YYYY)</option>
				<option value="%Y-%m-%d" <?= ($date_format == "%Y-%m-%d" ? ' selected="selected"' : '') ?>><?= strftime("%Y-%m-%d", time()) ?> (YYYY-MM-DD)</option>
				<option value="%Y.%m.%d" <?= ($date_format == "%Y.%m.%d" ? ' selected="selected"' : '') ?>><?= strftime("%Y.%m.%d", time()) ?> (YYYY.MM.DD)</option>
				<option value="%d.%m.%Y" <?= ($date_format == "%d.%m.%Y" ? ' selected="selected"' : '') ?>><?= strftime("%d.%m.%Y", time()) ?> (DD.MM.YYYY)</option>
				<option value="%m.%d.%Y" <?= ($date_format == "%m.%d.%Y" ? ' selected="selected"' : '') ?>><?= strftime("%m.%d.%Y", time()) ?> (MM.DD.YYYY)</option>
			</select>
		</div>
	</div>


<!-- Switch Wrap Start -->
<div class="row">
    <div class="form-group col-md-6">
        <label class="control-label common-label"><?= lang('hd_lang.allow_js_php_blocks') ?></label>
        <div class="inputDiv form-check form-switch input-btn-div">
            
                <input type="hidden" value="off" name="allow_js_php_blocks" />
                <input type="checkbox" role="switch" class="form-check-input" <?php if ($custom_name->getconfig_item('allow_js_php_blocks', $temp_data) == 'TRUE') { echo "checked=\"checked\"";
                 } ?> name="allow_js_php_blocks">
        </div>
    </div>


    <div class="form-group col-md-6">
        <label class="control-label common-label"><?= lang('hd_lang.enable_languages') ?></label>
        <div class="inputDiv form-check form-switch input-btn-div">
            
                <input type="hidden" value="off" name="enable_languages" />
                <input type="checkbox" role="switch" class="form-check-input" <?php if ($custom_name->getconfig_item('enable_languages', $temp_data) == 'TRUE') {
                                            echo "checked=\"checked\"";
                                        } ?> name="enable_languages">
                
        </div>
    </div>

    <div class="form-group col-md-6">
        <label class="control-label common-label"><?= lang('hd_lang.allow_client_registration') ?></label>
        <div class="inputDiv form-check form-switch input-btn-div">
           
                <input type="hidden" value="off" name="allow_client_registration" />
                <input type="checkbox" role="switch" class="form-check-input" <?php if ($custom_name->getconfig_item('allow_client_registration', $temp_data) == 'TRUE') {
                                            echo "checked=\"checked\"";
                                        } ?> name="allow_client_registration">
               
        </div>
    </div>

    <div class="form-group col-md-6">
        <label class="control-label common-label"><?= lang('hd_lang.captcha_registration') ?></label>
        <div class="inputDiv form-check form-switch input-btn-div">
            
                <input type="hidden" value="off" name="captcha_registration" />
                <input type="checkbox" role="switch" class="form-check-input" <?php if ($custom_name->getconfig_item('captcha_registration', $temp_data) == 'TRUE') {
                                            echo "checked=\"checked\"";
                                        } ?> name="captcha_registration">
               
        </div>
    </div>

    <div class="form-group col-md-6">
        <label class="control-label common-label"><?= lang('hd_lang.auto_backup_db') ?></label>
        <div class="inputDiv form-check form-switch input-btn-div">
                <input type="hidden" value="off" name="auto_backup_db" />
                <input type="checkbox" role="switch" class="form-check-input" <?php if ($custom_name->getconfig_item('auto_backup_db', $temp_data) == 'TRUE') {echo "checked=\"checked\""; } ?> name="auto_backup_db">
                
        </div>
    </div>


    <div class="form-group col-md-6">
        <label class="control-label common-label"><?= lang('hd_lang.captcha_login') ?></label>
        <div class="inputDiv form-check form-switch input-btn-div">
            
                <input type="hidden" value="off" name="captcha_login" />
                <input type="checkbox" role="switch" class="form-check-input" <?php if ($custom_name->getconfig_item('captcha_login', $temp_data) == 'TRUE') {
                                            echo "checked=\"checked\"";
                                        } ?> name="captcha_login">
                
        </div>
    </div>


    <div class="form-group col-md-6">
        <label class="control-label common-label"><?= lang('hd_lang.use_recaptcha') ?></label>
        <div class="inputDiv form-check form-switch input-btn-div">
            
                <input type="hidden" value="off" name="use_recaptcha" />
                <input type="checkbox" role="switch" class="form-check-input" <?php if ($custom_name->getconfig_item('use_recaptcha', $temp_data) == 'TRUE') {
                                            echo "checked=\"checked\"";
                                        } ?> name="use_recaptcha">
                
        </div>
    </div>
    </div>
<!-- Switch Wrap End -->

    <div class="form-group">
        <label class=" control-label common-label"><?= lang('hd_lang.recaptcha_sitekey') ?></label>
        <div class="inputDiv">
            <input type="text" class="form-control common-input" value="<?= $custom_name->getconfig_item('recaptcha_sitekey', $temp_data) ?>" name="recaptcha_sitekey">
        </div>
    </div>


    <div class="form-group">
        <label class="control-label common-label"><?= lang('hd_lang.recaptcha_secretkey') ?></label>
        <div class="inputDiv">
            <input type="text" class="form-control common-input" value="<?= $custom_name->getconfig_item('recaptcha_secretkey', $temp_data) ?>" name="recaptcha_secretkey">
        </div>
    </div>


    <div class="form-group">
        <label class="control-label common-label"><?= lang('hd_lang.file_max_size') ?> <span class="text-danger">*</span> </label>
        <div class="inputDiv">
            <input type="text" class="form-control common-input" value="<?= $custom_name->getconfig_item('file_max_size', $temp_data) ?>" name="file_max_size" data-type="digits" data-required="true">
        </div>
    </div>

    <div class="form-group">
        <label class="control-label common-label"><?= lang('hd_lang.allowed_files') ?></label>
        <div class="inputDiv">
            <input type="text" class="form-control common-input" value="<?= $custom_name->getconfig_item('allowed_files', $temp_data) ?>" name="allowed_files">
        </div>
    </div>

    <div class="form-group">
        <label class=" control-label common-label"><?= lang('hd_lang.auto_close_ticket') ?></label>
        <div class="inputDiv">
            <input type="text" class="form-control common-input" value="<?= $custom_name->getconfig_item('auto_close_ticket', $temp_data) ?>" name="auto_close_ticket">

        </div>
        <span class="help-block m-b-none small text-danger myError"><?= lang('hd_lang.auto_close_ticket_after') ?></span>
    </div>

    <div class="form-group">
        <label class="control-label common-label"><?= lang('hd_lang.ticket_start_number') ?></label>
        <div class="inputDiv">
            <input type="text" name="ticket_start_no" class="form-control common-input" value="<?= $custom_name->getconfig_item('ticket_start_no', $temp_data) ?>">
        </div>
    </div>

    <div class="form-group">
        <label class="control-label common-label"><?= lang('hd_lang.default_department') ?></label>
        <div class="inputDiv">
            <select name="ticket_default_department" class="form-select common-select">
                <?php foreach (App::get_by_where('hd_departments', array()) as $key => $d) { ?>
                    <option value="<?= $d->deptid ?>" <?= ($custom_name->getconfig_item('ticket_default_department', $temp_data) == $d->deptid ? ' selected="selected"' : '') ?>><?= $d->deptname ?></option>
                <?php } ?>

            </select>

        </div>
        <span class="help-block m-b-none small text-danger myError"><?= lang('hd_lang.default_ticket_department') ?></span>
    </div>



    <div class="text-center">
        <button type="submit" class="btn btn-sm btn-success common-button"><?= lang('hd_lang.save_changes') ?></button>
    </div>

    </form>

    <!-- End Form -->