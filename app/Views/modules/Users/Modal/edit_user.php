<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

use App\Models\User;
use App\Models\Client;
use App\Models\App;

use App\Helpers\custom_name_helper;

$session = \Config\Services::session();

$custom = new custom_name_helper();

?>
<?php $company = User::info_profile($id);

?>
<div class="modal-dialog modal-xl">
    <div class="modal-content my-modal">
        <div class="modal-header">
            <h4 class="modal-title"><?=lang('hd_lang.edit_user')?></h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div><?php
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open_multipart(base_url().'account/update',$attributes); ?>
        <?php $user = User::view_user($id); $info = User::info_profile($id); ?>
        <div class="modal-body">
            <input type="hidden" name="user_id" value="<?=$user->id?>">

            <div class="row">

                <div class="form-group col-sm-6">
                    <label class="common-label control-label"><?=lang('hd_lang.full_name')?> <span
                            class="text-danger">*</span></label>
                    <div class="inputDiv">
                        <input type="text" class="form-control common-input" value="<?=$info->fullname?>" name="fullname">
                    </div>
                </div>

                <div class="form-group col-sm-6">
                    <label class="common-label control-label"><?=lang('hd_lang.company')?></label>
                    <div class="inputDiv">
                        <select class="select2-option w_210 common-select mb-3" name="company">
                            <optgroup label="<?=lang('hd_lang.default_company')?>">
                                <?php if($info->company ==  $company->company){ ?>
                                <option value="<?=$company->company?>" selected="selected">
                                    <?=$custom->getconfig_item('company_name')?></option>
                                <?php }else{ ?>
                                <option value="<?=$company->company?>"><?=$custom->getconfig_item('company_name')?></option>
                                <?php } ?>
                            </optgroup>
                            <optgroup label="<?=lang('hd_lang.other_companies')?>">
                                <?php foreach (Client::get_all_clients() as $company){ ?>
                                <option value="<?=$company->co_id?>"
                                    <?=($info->company == $company->co_id ? ' selected="selected"' : '')?>>
                                    <?=$company->company_name?></option>
                                <?php } ?>
                            </optgroup>

                        </select>
                    </div>
                </div>

            </div>

            <div class="row">

                <?php
                    if (User::get_role($user->id) == 'staff' || User::get_role($user->id) == 'admin') { ?>
                <div class="form-group">
                    <label class="common-label control-label"><?=lang('hd_lang.department')?> </label>
                    <div class="inputDiv">
                        <select name="department[]" class="select2-option w_200 common-select mb-3" multiple="multiple">

                            <?php $db = \Config\Database::connect();
                        $departments = $db->table('hd_departments')->get()->getResult();
                        $dep = json_decode($info->department,TRUE);
                        if (!empty($departments)){
                            foreach ($departments as $d){ ?>

                            <option value="<?=$d->deptid?>"
                                <?=($d->deptid == $info->department || (is_array($dep) && in_array($d->deptid, $dep))) ? ' selected="selected"' : ''?>>

                                <?=$d->deptname?> </option>
                            <?php } } ?>
                        </select>
                        <a href="<?=site_url()?>settings/?settings=departments" class="btn btn-sm btn-danger">Add
                            Department</a>
                    </div>
                </div>
                <?php } ?>

            </div>

            <div class="row">

            <div class="form-group col-sm-6">
                    <label class="common-label control-label"><?=lang('hd_lang.phone')?> </label>
                    <div class="inputDiv">
                        <input type="text" class="form-control common-input" value="<?=$info->phone?>" name="phone">
                    </div>
            </div>

            

                <div class="form-group col-sm-6">
                    <label class="common-label control-label"><?=lang('hd_lang.mobile_phone')?> </label>
                    <div class="inputDiv">
                        <input type="text" class="form-control common-input" value="<?=$info->mobile?>" name="mobile">
                    </div>
                </div>

                </div>

                <div class="row">

                <div class="form-group col-sm-6">
                    <label class="common-label control-label">Skype</label>
                    <div class="inputDiv">
                        <input type="text" class="form-control common-input" value="<?=$info->skype?>" name="skype">
                    </div>
                </div>

                <div class="form-group col-sm-6">
                    <label class="common-label control-label"><?=lang('hd_lang.language')?></label>
                    <div class="inputDiv">
                        <select name="language" class=" common-select mb-3">
                            <?php foreach (App::languages() as $lang) : ?>
                            <option value="<?=$lang->name?>"
                                <?=($info->language == $lang->name ? ' selected="selected"' : '')?>>
                                <?= ucfirst($lang->name)?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                </div>

                <div class="row">

                <div class="form-group col-sm-6">
                    <label class="common-label control-label"><?=lang('hd_lang.locale')?></label>
                    <div class="inputDiv">
                        <select class="select2-option common-select mb-3" name="locale">
                            <?php foreach (App::locales() as $loc) : ?>
                            <option lang="<?=$loc->code?>" value="<?=$loc->locale?>"
                                <?=($info->locale == $loc->locale ? ' selected="selected"' : '')?>>
                                <?=$loc->name?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                </div>
        </div>
        <div class="modal-footer justify-content-center"> <a href="#" class="btn common-button text-dark btn-default"
                data-dismiss="modal"><?=lang('hd_lang.close')?></a>
            <button type="submit"
                class="btn common-button btn-<?=$custom->getconfig_item('theme_color');?>"><?=lang('hd_lang.save_changes')?></button>
            <?php echo form_close(); ?>
        </div>
    </div>
    <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
<script type="text/javascript">
(function($) {
    "use strict";
    $(".select2-option").select2();
})(jQuery);
</script>