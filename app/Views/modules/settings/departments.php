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

$custom_helper = new custom_name_helper();

?>
<style>
.textbox {
            width: 122px;
            height: 27;
            padding: 10px -10px 10px 10px;
            border: 0.5px solid black;
            margin: 0;
			text-align: center;
        }
</style>
<div class="row" id="department_settings">
    <!-- Start Form -->
    <div class="col-lg-12">
        <?php
        $attributes = array('class' => 'bs-example form-horizontal');
        echo form_open_multipart('settings/update', $attributes); ?>
        <section class="box">

            <?php
            $view = isset($load_setting) ? $load_setting : '';
            $data['load_setting'] = $load_setting;
            switch ($view) {
            
                case 'categories':
                        // $this->load->view('categories',$data);
                        echo view('modules/settings/categories', $data);
                        break; 
                
                default: ?>

            <div class="box-body">
                <input type="hidden" name="settings" value="<?=$load_setting?>">
                <input type="hidden" name="return_url" value="<?= base_url() ?>settings/departments">
                <div class="form-group">
                    <label class="control-label common-label"><?=lang('hd_lang.add_department_name')?> <span
                            class="text-danger">*</span></label>
                    <div class="inputDiv">
                        <input type="text" name="deptname" class="form-control common-input"
                            placeholder="<?=lang('hd_lang.department_name')?>" required>
                    </div>
                </div>
                <?php
                    $session = \Config\Services::session(); 
                    // Connect to the database  
                    $db = \Config\Database::connect();

                    $departments = $db->table('hd_departments')->get()->getResult();
                    
                    if (!empty($departments)) {
                        foreach ($departments as $key => $d) { ?>
                <label class="control-label common-label textbox"><a href="<?=base_url()?>settings1/edit_dept/<?=$d->deptid?>"
                        data-toggle="ajaxModal" title=""><?=$d->deptname?></a></label>
                <?php } } ?>

            </div>
            <div class="box-footer">
                <div class="text-center">
                    <button type="submit"
                        class="btn btn-sm common-button btn-<?=$custom_helper->getconfig_item('theme_color');?>"><?=lang('hd_lang.save_changes')?></button>
                </div>
            </div>


            <?php
            break;
    }
    ?>

        </section>
        </form>
    </div>
    <!-- End Form -->
</div>