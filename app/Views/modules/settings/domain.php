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
$view = isset($load_setting) ? $load_setting : '';
$data['load_setting'] = $load_setting;
switch ($view) {
    case 'currency':
        //$this->load->view('currency',$data);
        echo view('modules/settings/currency', $data);
        break;
    default: ?>
    <?php $session = session();
        $session->getFlashdata('form_error');?>
    <?php
$attributes = array('class' => 'bs-example form-horizontal');
        echo form_open('settings/update', $attributes);?>
    <input type="hidden" name="settings" value="<?=$load_setting?>">
    <input type="hidden" name="categories" value="<?=$load_setting?>">

    <input type="hidden" name="return_url" value="<?=base_url()?>settings/domain">


    <div class="common-box">
        <h4 class="common-boxTitle" style="color:black !important;"><?=lang('hd_lang.domain_admin_contact')?></h4>

        <div class="box">

            <div class="box-body">
                <div class="form-group">
                    <label class="control-label common-label"><?=lang('hd_lang.first_name')?></label>
                    <div class="inputDiv">
                        <input type="text" class="form-control common-input"
                            value="<?=$custom_name->config_item('domain_admin_firstname', $temp_data)?>"
                            name="domain_admin_firstname">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label common-label"><?=lang('hd_lang.last_name')?></label>
                    <div class="inputDiv">
                        <input type="text" class="form-control common-input"
                            value="<?=$custom_name->config_item('domain_admin_lastname', $temp_data)?>"
                            name="domain_admin_lastname">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label common-label"><?=lang('hd_lang.company_name')?></label>
                    <div class="inputDiv">
                        <input type="text" class="form-control common-input"
                            value="<?=$custom_name->config_item('domain_admin_company', $temp_data)?>"
                            name="domain_admin_company">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label common-label"><?=lang('hd_lang.address_line_1')?></label>
                    <div class="inputDiv">
                        <input type="text" class="form-control common-input"
                            value="<?=$custom_name->config_item('domain_admin_address_1', $temp_data)?>"
                            name="domain_admin_address_1">
                    </div>
                </div>

                <div class="form-group">
                    <label class=" control-label common-label"><?=lang('hd_lang.address_line_2')?></label>
                    <div class="inputDiv">
                        <input type="text" class="form-control common-input"
                            value="<?=$custom_name->config_item('domain_admin_address_2', $temp_data)?>"
                            name="domain_admin_address_2">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label common-label"><?=lang('hd_lang.city')?></label>
                    <div class="inputDiv">
                        <input type="text" class="form-control common-input"
                            value="<?=$custom_name->config_item('domain_admin_city', $temp_data)?>"
                            name="domain_admin_city">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label common-label"><?=lang('hd_lang.zip_code')?></label>
                    <div class="inputDiv">
                        <input type="text" class="form-control common-input"
                            value="<?=$custom_name->config_item('domain_admin_zip', $temp_data)?>"
                            name="domain_admin_zip">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label common-label"><?=lang('hd_lang.state_province')?></label>
                    <div class="inputDiv">
                        <input type="text" class="form-control common-input"
                            value="<?=$custom_name->config_item('domain_admin_state', $temp_data)?>"
                            name="domain_admin_state">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label common-label"><?=lang('hd_lang.country')?></label>
                    <div class="inputDiv">
                        <select class="select2-option w_210 common-select" name="domain_admin_country">
                            <optgroup label="<?=lang('hd_lang.selected_country')?>">
                                <option value="<?=$custom_name->config_item('domain_admin_country', $temp_data)?>">
                                    <?=$custom_name->config_item('domain_admin_country', $temp_data)?></option>
                            </optgroup>
                            <optgroup label="<?=lang('hd_lang.other_countries')?>">
                                <?php foreach ($countries as $country): ?>
                                <option value="<?=$country->value?>"><?=$country->value?></option>
                                <?php endforeach;?>
                            </optgroup>
                        </select>
                    </div>
                </div>


                <div class="form-group">
                    <label class="control-label common-label"><?=lang('hd_lang.phone')?></label>
                    <div class="inputDiv">
                        <input type="text" class="form-control common-input"
                            value="<?=$custom_name->config_item('domain_admin_phone', $temp_data)?>"
                            name="domain_admin_phone">
                    </div>
                </div>


                <div class="form-group">
                    <label class="control-label common-label"><?=lang('hd_lang.email')?></label>
                    <div class="inputDiv">
                        <input type="text" class="form-control common-input"
                            value="<?=$custom_name->config_item('domain_admin_email', $temp_data)?>"
                            name="domain_admin_email">
                    </div>
                </div>

            </div>
        </div>
    </div>


    <div class="common-box">
        <h4 class="common-boxTitle" style="color:black !important;"><?=lang('hd_lang.defaultnameservers')?></h4>

        <div class="box box-success">

            <div class="box-body">
                <div class="form-group">
                    <label class="control-label common-label"><?=lang('hd_lang.nameserver_1')?></label>
                    <div class="inputDiv">
                        <input type="text" class="form-control common-input"
                            value="<?=$custom_name->config_item('nameserver_one', $temp_data)?>" name="nameserver_one">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label common-label"><?=lang('hd_lang.nameserver_2')?></label>
                    <div class="inputDiv">
                        <input type="text" class="form-control common-input"
                            value="<?=$custom_name->config_item('nameserver_two', $temp_data)?>" name="nameserver_two">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label common-label"><?=lang('hd_lang.nameserver_3')?></label>
                    <div class="inputDiv">
                        <input type="text" class="form-control common-input"
                            value="<?=$custom_name->config_item('nameserver_three', $temp_data)?>"
                            name="nameserver_three">
                    </div>
                </div>


                <div class="form-group">
                    <label class="control-label common-label"><?=lang('hd_lang.nameserver_4')?></label>
                    <div class="inputDiv">
                        <input type="text" class="form-control common-input"
                            value="<?=$custom_name->config_item('nameserver_four', $temp_data)?>"
                            name="nameserver_four">
                    </div>
                </div>


                <div class="form-group">
                    <label class="control-label common-label"><?=lang('hd_lang.nameserver_5')?></label>
                    <div class="inputDiv">
                        <input type="text" class="form-control common-input"
                            value="<?=$custom_name->config_item('nameserver_five', $temp_data)?>"
                            name="nameserver_five">
                    </div>
                </div>


            </div>
        </div>
    </div>



    <div class="text-center">
        <button type="submit"
            class="btn btn-sm btn-success common-button btn-<?=$custom_name->config_item('theme_color', $temp_data);?>"><?=lang('hd_lang.save_changes')?></button>
    </div>

    </form>

    <?php
break;
}
?>

    <!-- End Form -->