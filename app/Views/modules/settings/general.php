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
        $attributes = array('class' => 'bs-example form-horizontal');
        echo form_open_multipart('settings/update', $attributes);
        $custom_name = new custom_name_helper();
        ?>
    
    <input type="hidden" name="settings" value="<?=$load_setting?>">
    <input type="hidden" name="categories" value="<?=$load_setting?>">
                    <!-- <input type="hidden" name="languages" value="<?//=implode(",",$translations)?>"> -->
                    <input type="hidden" name="return_url" value="<?= base_url() ?>settings/general">
                            <div class="form-group">
                                <label class=" control-label common-label"><?=lang('hd_lang.website_name')?> <span class="text-danger">*</span></label>
                                <div class="inputDiv">
                                    <input type="text" name="website_name" class="form-control common-input" value="<?=$custom_name->getconfig_item('website_name', $temp_data)?>" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class=" control-label common-label"><?=lang('hd_lang.site_desc')?> <span class="text-danger">*</span></label>
                                <div class="inputDiv">
                                    <textarea type="text" name="site_desc" class="form-control common-input" value="<?=$custom_name->config_item('site_desc', $temp_data)?>" required><?=$custom_name->getconfig_item('site_desc', $temp_data)?></textarea>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class=" control-label common-label"><?=lang('hd_lang.company_name')?> <span class="text-danger">*</span></label>
                                <div class="inputDiv">
                                    <input type="text" name="company_name" class="form-control common-input" value="<?=$custom_name->getconfig_item('company_name', $temp_data)?>" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class=" control-label common-label"><?=lang('hd_lang.company_legal_name')?> <span class="text-danger">*</span></label>
                                <div class="inputDiv">
                                    <input type="text" name="company_legal_name" class="form-control common-input" value="<?=$custom_name->getconfig_item('company_legal_name', $temp_data)?>" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class=" control-label common-label"><?=lang('hd_lang.contact_person')?> </label>
                                <div class="inputDiv">
                                    <input type="text" class="form-control common-input"  value="<?=$custom_name->getconfig_item('contact_person', $temp_data)?>" name="contact_person">
                                    <span class="help-block m-b-none myError"><?=lang('hd_lang.company_representative')?></strong>.</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class=" control-label common-label"><?=lang('hd_lang.company_address')?> <span class="text-danger">*</span></label>
                                <div class="inputDiv">
                                    <textarea class="form-control common-input ta" name="company_address" required><?=$custom_name->getconfig_item('company_address', $temp_data)?></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class=" control-label common-label"><?=lang('hd_lang.zip_code')?> </label>
                                <div class="inputDiv">
                                    <input type="text" class="form-control common-input"  value="<?=$custom_name->getconfig_item('company_zip_code', $temp_data)?>" name="company_zip_code">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class=" control-label common-label"><?=lang('hd_lang.city')?></label>
                                <div class="inputDiv">
                                    <input type="text" class="form-control common-input" value="<?=$custom_name->getconfig_item('company_city', $temp_data)?>" name="company_city">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class=" control-label common-label"><?=lang('hd_lang.state_province')?></label>
                                <div class="inputDiv">
                                    <input type="text" class="form-control common-input" value="<?=$custom_name->getconfig_item('company_state', $temp_data)?>" name="company_state">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class=" control-label common-label"><?=lang('hd_lang.country')?></label>
                                <div class="inputDiv">
                                        <select class="select2-option w_210 common-select" name="company_country" >
                                            <optgroup label="<?=lang('hd_lang.selected_country')?>">
                                                <option value="<?=$custom_name->getconfig_item('company_country', $temp_data)?>"><?=$custom_name->getconfig_item('company_country', $temp_data)?></option>
                                            </optgroup>
                                            <optgroup label="<?=lang('hd_lang.other_countries')?>">
                                                <?php foreach ($countries as $country): ?>
                                                    <option value="<?=$country->value?>"><?=$country->value?></option>
                                                <?php endforeach; ?>
                                            </optgroup>
                                        </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class=" control-label common-label"><?=lang('hd_lang.company_email')?></label>
                                <div class="inputDiv">
                                    <input type="email" class="form-control common-input" value="<?=$custom_name->getconfig_item('company_email', $temp_data)?>" name="company_email">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class=" control-label common-label"><?=lang('hd_lang.company_phone')?></label>
                                <div class="inputDiv">
                                    <input type="text" class="form-control common-input" value="<?=$custom_name->getconfig_item('company_phone', $temp_data)?>" name="company_phone">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class=" control-label common-label"><?=lang('hd_lang.company_phone')?> 2</label>
                                <div class="inputDiv">
                                    <input type="text" class="form-control common-input" value="<?=$custom_name->getconfig_item('company_phone_2', $temp_data)?>" name="company_phone_2">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class=" control-label common-label"><?=lang('hd_lang.mobile_phone')?></label>
                                <div class="inputDiv">
                                    <input type="text" class="form-control common-input" value="<?=$custom_name->getconfig_item('company_mobile', $temp_data)?>" name="company_mobile">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class=" control-label common-label"><?=lang('hd_lang.fax')?> </label>
                                <div class="inputDiv">
                                    <input type="text" class="form-control common-input" value="<?=$custom_name->getconfig_item('company_fax', $temp_data)?>" name="company_fax">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class=" control-label common-label"><?=lang('hd_lang.company_domain')?></label>
                                <div class="inputDiv">
                                    <input type="text" class="form-control common-input" value="<?=$custom_name->getconfig_item('company_domain', $temp_data)?>" name="company_domain">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class=" control-label common-label"><?=lang('hd_lang.company_registration')?></label>
                                <div class="inputDiv">
                                    <input type="text" class="form-control common-input" value="<?=$custom_name->getconfig_item('company_registration', $temp_data)?>" name="company_registration">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class=" control-label common-label"><?=lang('hd_lang.company_vat')?></label>
                                <div class="inputDiv">
                                    <input type="text" class="form-control common-input" value="<?=$custom_name->getconfig_item('company_vat', $temp_data)?>" name="company_vat">
                                </div>
                            </div>
                            
         
                            <div class="text-center"> 
                            <button type="submit" class="btn btn-sm common-button  mt-3 btn-success btn-<?=$custom_name->getconfig_item('theme_color', $temp_data);?>   "><?=lang('hd_lang.save_changes')?></button>
                         </div>
        </form> 
    <!-- End Form -->
 