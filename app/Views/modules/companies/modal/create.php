<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

use App\Models\App;
use App\Models\Client;

use App\Helpers\custom_name_helper;

$session = \Config\Services::session();
$custom = new custom_name_helper();
// Connect to the database
$db = \Config\Database::connect();

$appModel = new App();

?>
<div class="modal-dialog my-modal modal-xl" style='height: 60rem !important;'>
    <div class="modal-content">
        <div class="modal-header row-reverse"> <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><?=lang('hd_lang.new_client')?></h4>
        </div>

        <?php echo form_open(base_url('companies/create')); ?>
        <input class="hidden" style="display: none;">
        <input type="password" class="hidden" style="display: none;">
        <div class="modal-body">
            <ul class="nav nav-tabs" role="tablist">
                <li class='common-h px-3 active'><a class="active" data-toggle="tab" href="#tab-client-general"><?= lang('hd_lang.details') ?></a></li>
                <li class='common-h px-3'><a data-toggle="tab" href="#tab-client-contact"><?= lang('hd_lang.address') ?></a></li>
                <li class='common-h px-3'><a data-toggle="tab" href="#tab-client-custom"><?= lang('hd_lang.custom_fields') ?></a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade in active show " id="tab-client-general">
                    <input type="hidden" name="company_ref" value="">
                    <input type="hidden" name="co_id" value="">
                    <div class="row">
                        <div class="col-md-6">

                            <div class="form-group my-3">
								<label><?= lang('hd_lang.first_name') ?><span class="text-danger">*</span></label>
								<input type="text" name="first_name" value="" class="input-sm form-control" required tabindex="1" 
								pattern="[A-Za-z]+" title="Only alphabets are allowed">
							</div>

                            <div class="form-group my-3">
                                <label><?= lang('hd_lang.last_name') ?><span class="text-danger">*</span></label>
               <input type="text" name="last_name" value="" class="input-sm form-control" required tabindex="3" pattern="[A-Za-z]+" title="Only alphabets are allowed">
                            </div>

                            <div class="form-group my-3">
           <label><?= lang('hd_lang.vat') ?> <?= lang('hd_lang.number') ?> / <?= lang('hd_lang.gst') ?> <?= lang('hd_lang.number') ?> </label>
           <input type="text" value="" name="VAT" class="input-sm form-control" tabindex="5" pattern="\d*" title="Only numbers are allowed">
                            </div>

                            <div class="form-group my-3">
                                <label><?= lang('hd_lang.mobile_phone') ?> <span class="text-danger">*</span></label>
        <input type="tel" name="company_mobile" class="input-sm form-control" tabindex="7" required value="" value="" pattern="\d*" title="Only numbers, spaces, and dashes are allowed">
                            </div>

                            <div class="form-group my-3">
                                <label><?= lang('hd_lang.email') ?> <span class="text-danger">*</span></label>
      							<input type="email" name="company_email" value="" class="input-sm form-control" required tabindex="9" >
                            </div>

                            <div class="form-group my-3">
                                <label><?= lang('hd_lang.password') ?> </label>
                                <input type="password" value="" name="password" class="input-sm form-control" tabindex="11">
                            </div>

                            <div class="form-group my-3">
                                <label><?= lang('hd_lang.currency') ?></label>
                                <select name="currency" class="form-control" tabindex="13">
                                    <?php 
	
							$custom_name = new custom_name_helper();

							$curr = $custom_name->getconfig_item('default_currency');

							$currencies = $db->table('hd_currencies')->where('status', '1')->get()->getResult();
							
							// Create a new array with $curr at the top
							$defaultCurrency = (object) ['code' => $curr];

							// Merge $defaultCurrency with $currencies
							$currencies = array_merge([$defaultCurrency], $currencies);
							
							// echo "<pre>";print_r($currencies);die;
	
										foreach ($currencies as $cur) : ?>
                                        <option value="" <?= ($custom->getconfig_item('default_currency') == $cur->code ? ' selected="selected"' : '') ?>><?= $cur->code ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                           <div class="form-group my-3">
								<label><?= lang('hd_lang.company_name') ?><span class="text-danger">*</span></label>
								<input type="text" name="company_name" class="input-sm form-control" required tabindex="4" value=""
									   pattern="[A-Za-z\s]+" title="Only alphabets and spaces are allowed">
							</div>

							<div class="form-group my-3">
								<label><?= lang('hd_lang.phone') ?> </label>
								<input type="text" name="company_phone" class="input-sm form-control" tabindex="6" value=""
									   pattern="\+?[0-9]{10,15}" title="Please enter a valid phone number (only digits, optionally starting with +, 10 to 15 digits)">
							</div>

							<div class="form-group my-3">
								<label><?= lang('hd_lang.fax') ?> </label>
								<input type="text" name="company_fax" class="input-sm form-control" tabindex="8" value=""
									   pattern="\+?[0-9]{10,15}" title="Please enter a valid fax number (only digits, optionally starting with +, 10 to 15 digits)">
							</div>


                            <div class="form-group my-3">
                                <label><?= lang('hd_lang.fax') ?> </label>
                                <input type="text" name="company_fax" class="input-sm form-control" tabindex="8" value="" pattern="\+?[0-9]{10,15}" title="Please enter a valid fax number (only digits, optionally starting with +, 10 to 15 digits)">
                            </div>

                            <div class="form-group my-3">
                                <label><?= lang('hd_lang.username') ?> <span class="text-danger">*</span></label>
                        <input type="text" name="username" class="input-sm form-control" required tabindex="10" value="" pattern="[A-Za-z0-9]+" title="Only alphanumeric characters are allowed">
                            </div>

                            <div class="form-group my-3">
                                <label><?= lang('hd_lang.confirm_password') ?> </label>
                           <input type="password" name="confirm_password" class="input-sm form-control" tabindex="12" pattern=".{6,}" title="Password must be at least 6 characters long">
                            </div>

                            <div>
                                <label class='common-label m-0' ><?= lang('hd_lang.language') ?></label>
                                <select name="language" class="form-control common-select" style='height:3rem !important;' tabindex="14">
                                    <?php foreach ($appModel::languages() as $lang) : ?>
                                        <option value="" <?= ($custom->getconfig_item('default_language') == $lang->name ? ' selected="selected"' : '') ?>><?= ucfirst($lang->name) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group my-3">
                        <label><?= lang('hd_lang.notes') ?></label>
                        <textarea name="notes" class="form-control ta common-input" style='height: unset !important;' ></textarea>
                    </div>

                </div>
                <div class="tab-pane fade in " id="tab-client-contact">

                    <div class="clearfix"></div>
                    <div class="form-group my-3">
                        <label><?= lang('hd_lang.address1') ?></label>
                        <textarea name="company_address1"  class="form-control ta common-input" pattern="[A-Za-z0-9\s,-]+" title="Only alphanumeric characters, spaces, commas, and dashes are allowed" style='height: unset !important;'></textarea>
                    </div>
                    <div class="form-group my-3">
                        <label><?= lang('hd_lang.address2') ?></label>
                        <textarea name="company_address2"  class="form-control ta common-input" pattern="[A-Za-z0-9\s,-]+" title="Only alphanumeric characters, spaces, commas, and dashes are allowed" style='height: unset !important;'></textarea>
                    </div>
                    <div class="row">
                    <div class="form-group my-3 col-md-6 no-gutter-left">
                        <label><?= lang('hd_lang.city') ?> </label>
                        <input type="text" value="" name="city" class="input-sm form-control">
                    </div>
                    <div class="form-group my-3 col-md-6 no-gutter-right">
                        <label><?= lang('hd_lang.zip_code') ?> </label>
                        <input type="text" value="" name="zip" class="input-sm form-control">
                    </div>
                    </div>
                    <div class="row">
                        <div class="form-group my-3 col-md-6">
                            <label><?= lang('hd_lang.state_province') ?> </label>
                            <input type="text" value="" name="state" class="input-sm form-control">
                        </div>
                        <div class="form-group my-3 col-md-6 no-gutter-right">

                            <label><?= lang('hd_lang.language') ?></label>
                            <select name="language" class="form-control">
                                    <?php foreach ($appModel::languages() as $lang) : ?>
                                                <option value="<?=$lang->name?>"<?=($custom->getconfig_item('default_language') == $lang->name ? ' selected="selected"' : '')?>><?=  ucfirst($lang->name)?></option>
                                                <?php endforeach; ?>
                            </select>

                            <label class='mt-3'><?= lang('hd_lang.country') ?> </label>
                            <select class="form-control w_180" name="country">
                                <optgroup label="<?=lang('hd_lang.selected_country')?>">
                                                        <option value="<?=$custom->getconfig_item('company_country')?>"><?=$custom->getconfig_item('company_country')?></option>
                                                </optgroup>
                                <optgroup label="<?= lang('hd_lang.other_countries') ?>">
                                    <?php foreach (App::countries() as $country) : ?>
                                        <option value="<?=$country->value?>"><?= $country->value ?></option>
                                    <?php endforeach; ?>
                                </optgroup>
                            </select>
                        </div>
                    </div>
                </div>


                <!-- START CUSTOM FIELDS -->
                <div class="tab-pane fade in " id="tab-client-custom">
                    <?php $fields = $db->table('hd_fields')
                        ->orderBy('order', 'DESC')
                        ->where('module', 'clients')
                        ->get()
                        ->getResult();
                    ?>
                    <?php foreach ($fields as $f) : ?>
                        <?php $options = json_decode($f->field_options, true); ?>
                        <!-- check if dropdown -->
                        <?php if ($f->type == 'dropdown') : ?>

                            <div class="form-group my-3">
                                <label><?= $f->label ?> <?= ($f->required) ? '<abbr title="required">*</abbr>' : ''; ?></label>
                                <select class="form-control" name="<?= 'cust_' . $f->name ?>" <?= ($f->required) ? 'required' : ''; ?>>
                                    <option value=""><?= $val ?></option>
                                    <?php foreach ($options['options'] as $opt) : ?>
                                        <option value="<?=$opt['label']?>" <?= ($opt['checked']) ? 'selected="selected"' : ''; ?>>
                                            <?= $opt['label'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="help-block"><?= isset($options['description']) ? $options['description'] : '' ?></span>

                            </div>

                            <!-- Text field -->
                        <?php elseif ($f->type == 'text') : ?>

                            <div class="form-group my-3">
                                <label><?= $f->label ?> <?= ($f->required) ? '<abbr title="required">*</abbr>' : ''; ?></label>
                                <input type="text" name="<?= 'cust_' . $f->name ?>" class="input-sm form-control" value="" <?= ($f->required) ? 'required' : ''; ?>>
                                <span class="help-block"><?= isset($options['description']) ? $options['description'] : '' ?></span>
                            </div>

                            <!-- Textarea field -->
                        <?php elseif ($f->type == 'paragraph') : ?>

                            <div class="form-group my-3">
                                <label><?= $f->label ?> <?= ($f->required) ? '<abbr title="required">*</abbr>' : ''; ?></label>
                                <textarea name="<?= 'cust_' . $f->name ?>" class="form-control ta" <?= ($f->required) ? 'required' : ''; ?>><?= $val ?></textarea>
                                <span class="help-block"><?= isset($options['description']) ? $options['description'] : '' ?></span>
                            </div>

                            <!-- Radio buttons -->
                        <?php elseif ($f->type == 'radio') : ?>
                            <div class="form-group my-3">
                                <label><?= $f->label ?> <?= ($f->required) ? '<abbr title="required">*</abbr>' : ''; ?></label>
                                <?php foreach ($options['options'] as $opt) : ?>
                                    <?php $sel_val = json_decode($val); ?>
                                    <label class="radio-custom">
                                        <input type="radio" name="<?= 'cust_' . $f->name ?>[]" <?= ($opt['checked'] || $sel_val[0] == $opt['label']) ? 'checked="checked"' : ''; ?> value="" <?= ($f->required) ? 'required' : ''; ?>> <?= $opt['label'] ?>
                                    </label>
                                <?php endforeach; ?>
                                <span class="help-block"><?= isset($options['description']) ? $options['description'] : '' ?></span>
                            </div>

                            <!-- Checkbox field -->
                        <?php elseif ($f->type == 'checkboxes') : ?>
                            <div class="form-group my-3">
                                <label><?= $f->label ?> <?= ($f->required) ? '<abbr title="required">*</abbr>' : ''; ?></label>

                                <?php foreach ($options['options'] as $opt) : ?>
                                    <?php $sel_val = json_decode($val); ?>
                                    <div class="checkbox">
                                        <label class="checkbox-custom">
                                            <?php if (is_array($sel_val)) : ?>
                                                <input type="checkbox" name="<?= 'cust_' . $f->name ?>[]" <?= ($opt['checked'] || in_array($opt['label'], $sel_val)) ? 'checked="checked"' : ''; ?> value="">
                                            <?php else : ?>
                                                <input type="checkbox" name="<?= 'cust_' . $f->name ?>[]" <?= ($opt['checked']) ? 'checked="checked"' : ''; ?> value="">
                                            <?php endif; ?>
                                            <?= $opt['label'] ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                                <span class="help-block"><?= isset($options['description']) ? $options['description'] : '' ?></span>

                            </div>
                            <!-- Email Field -->
                        <?php elseif ($f->type == 'email') : ?>

                            <div class="form-group my-3">
                                <label><?= $f->label ?> <?= ($f->required) ? '<abbr title="required">*</abbr>' : ''; ?></label>
                                <input type="email" name="<?= 'cust_' . $f->name ?>" value="" class="input-sm form-control" <?= ($f->required) ? 'required' : ''; ?>>
                                <span class="help-block"><?= isset($options['description']) ? $options['description'] : '' ?></span>
                            </div>

                        <?php elseif ($f->type == 'section_break') : ?>
                            <hr />
                        <?php endif; ?>


                    <?php endforeach; ?>
                </div>

            </div>
        </div>
        <div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?= lang('hd_lang.close') ?></a>
            <button type="submit" class="btn btn-<?= $custom->getconfig_item('theme_color'); ?>"><?= lang('hd_lang.save_changes') ?></button>
          <?php echo form_close() ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    $('.nav-tabs li a').first().tab('show');
</script>