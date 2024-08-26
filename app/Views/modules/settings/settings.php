<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

$session = \Config\Services::session(); 

use App\Helpers\custom_name_helper;

$custom_helper = new custom_name_helper();

// Connect to the database 
$db = \Config\Database::connect();

?>
<?= $this->extend('layouts/users') ?>

<?= $this->section('content') ?>
<section id="hosting-services-wrap">
    
        <div class="hs-topbar-wrap">
            <div class="hs-title-wrap">
                <h3 class="common-h">Settings</h3>
                <p>Showing settings listsss</p>
            </div>
        </div>
        <div class="row box-top settingsPage">
            <div class="col-md-3">

                <!-- Profile Image -->
                <div class="box box-primary">
                    <div class="box-body box-profile">
                        <h3 class="profile-username text-center common-h"><?=lang('hd_lang.settings_menu')?></h3>

                        <ul class="list-group" id="settings_menu">
                            <?php

                        $query = $db->table('hd_hooks')
                            ->where('hook', 'settings_menu_admin')
                            ->where('visible', 1)
                            ->orderBy('order', 'ASC')
                            ->get();

                        $menus = $query->getResult();
                            // $menus = $this->db->where('hook','settings_menu_admin')->where('visible',1)->order_by('order','ASC')->get('hooks')->result();
                            foreach ($menus as $menu) { ?>
                            <li class="list-group-item common-button <?php echo ($load_setting == $menu->route) ? 'active' : '';?>">
                                <a href="<?=base_url('settings/'.$menu->route)?>">
                                    <i class="fa fa-fw <?=$menu->icon?>"></i>
                                    <?=lang('hd_lang.' . $menu->name)?>
                                </a>
                            </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
            <div class="col-md-9">
                <div class="box box-warning">
                    <div class="box-header clearfix">
                        <div class="row m-t-sm">
                            <div class="col-sm-10 m-b-xs">
                                <?php if($custom_helper->getconfig_item('demo_mode') != 'TRUE') {?>
                                <?php if($load_setting == 'templates'){  ?>
                                <div class="btn-group myBtnGroup">
                                    <a class="btn btn-warning common-button"
                                        href="<?=base_url()?>settings/templates/user"><?=lang('hd_lang.account_emails')?></a>
                                    <a class="btn btn-primary common-button"
                                        href="<?=base_url()?>settings/templates/invoice"><?=lang('hd_lang.invoicing_emails')?></a>
                                    <a class="btn btn-warning common-button"
                                        href="<?=base_url()?>settings/templates/ticket"><?=lang('hd_lang.ticketing_emails')?></a>
                                    <a class="btn btn-primary common-button"
                                        href="<?=base_url()?>settings/templates/signature"><?=lang('hd_lang.email_signature')?></a>
                                </div>
                                <?php } ?>

                                <?php $set = array('system', 'validate');
                            if( in_array($load_setting, $set) && $custom_helper->getconfig_item('demo_mode') != 'TRUE'){  ?>

                                <a href="<?=base_url('settings/database')?>"
                                    class="btn btn-<?=$custom_helper->getconfig_item('theme_color');?> btn-sm common-button"><i
                                        class="fa fa-cloud-download text"></i>
                                    <span class="text"><?=lang('hd_lang.database_backup')?></span>
                                </a>
                                <?php } ?>

                                <?php if($load_setting == 'email'){  ?>
                                <a href="<?=base_url()?>settings/alerts"
                                    class="btn btn-sm common-button btn-<?=$custom_helper->getconfig_item('theme_color');?>"><i
                                        class="fa fa-inbox text"></i>
                                    <span class="text"><?=lang('hd_lang.alert_settings')?></span>
                                </a>
                                <?php } ?>

                                <?php
                            } ?>

                            </div>
                        </div>
                    </div>
                    <?php //echo $load_setting;die; ?>
                    <div class="box-body" id="settings">
                        <?= view('modules/settings/'.$load_setting); ?>
                    </div>
                </div>
                <!-- /.nav-tabs-custom -->
            </div>
            <!-- /.col -->
        </div>
    
</section>
<?= $this->endSection() ?>
<!-- /.row -->