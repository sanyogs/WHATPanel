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
use App\Models\Plugin;

$custom_name = new custom_name_helper();

$attributes = array('class' => 'bs-example form-horizontal');
echo form_open_multipart('settings/update', $attributes);?>

<input type="hidden" name="settings" value="<?=$load_setting?>">
<input type="hidden" name="categories" value="<?=$load_setting?>">
<input type="hidden" name="return_url" value="<?=base_url()?>settings/search">

<div class="form-group mb-4">
    <label class="control-label common-label">
        <?=lang('hd_lang.domain_checker')?>
    </label>

    <div class="radioWrap row" id="default_registrar">
        <div class="col-md-3 radioCol4">
            <input type="radio" name="domain_checker" value="default" class="common-radio"
                <?=($custom_name->config_item('domain_checker', $temp_data) == 'default') ? 'checked="checked"' : ''?>>
            <span class="label label-default common-radioLabel">
                <?=lang('hd_lang.basic_checker')?>
            </span>
        </div>
        <?php $registrars = Plugin::domain_registrars();

foreach ($registrars as $registrar) {

    $configDomainChecker = $custom_name->config_item('domain_checker', $temp_data);
    $registrarSystemName = $registrar->system_name;

    ?>
        <div class="col-md-3 radioCol4">
            <input type="radio" class="common-radio" name="domain_checker" value="<?=$registrar->system_name;?>"
                <?=($configDomainChecker == $registrarSystemName) ? 'checked="checked"' : ''?>> <span
                class="label label-default common-radioLabel"><?=ucfirst($registrar->system_name);?></span>
        </div>
        <?php
}
if (Plugin::get_plugin('whoisxmlapi')) {
    $configDomainChecker = $custom_name->config_item('domain_checker', $temp_data);?>
        <div class="col-md-5 radioCol4">
            <input type="radio" class="common-radio" name="domain_checker" value="whoisxmlapi"
                <?=($configDomainChecker == 'whoisxmlapi') ? 'checked="checked"' : ''?>> <span
                class="label label-default common-radioLabel">WhoisXMLApi</span> <small class="smallText">
                <?=lang('hd_lang.whoisxmlapi_signup')?>
            </small>
        </div>
        <?php
}?>
    </div>
</div>


<div class="form-group">
    <label class="control-label common-label">
        <?=lang('hd_lang.whoisxmlapi_key')?>
    </label>
    <div class="inputDiv">
        <input type="<?=$custom_name->config_item('demo_mode', $temp_data) == 'TRUE' ? 'password' : 'text';?>"
            name="whoisxmlapi_key" class="form-control common-input"
            value="<?=$custom_name->config_item('whoisxmlapi_key', $temp_data)?>">
        <p>
            <span class="help-block m-b-none small text-danger myError">
                <?=lang('hd_lang.whoisxmlapi_description')?>
            </span>
        </p>
    </div>
</div>


<div class="text-center">
    <button type="submit" class="btn btn-sm common-button  btn-<?=$custom_name->getconfig_item('theme_color');?>">
        <?=lang('hd_lang.save_changes')?>
    </button>
</div>

</form>

<!-- End Form -->