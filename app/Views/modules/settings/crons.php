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
$custom = new custom_name_helper();
?>

<p class="myError">
    <strong>GENERAL CRON: </strong>
    <code>wget -O /dev/null <?=base_url()?>crons/run/<?=$custom->getconfig_item('cron_key')?></code>
</p>
<p class="myError">
    <strong>EMAIL PIPING: </strong> <code>wget -O /dev/null <?=base_url()?>crons/email_piping</code>
</p>
<div class="box">

    <div class="box-body">
        <div class="row">
            <!-- Start Form -->
            <div class="col-lg-12">
                <div class="table-responsive hs-table-overflow" style='top: unset !important;'>
                    <table id="cron-jobs" class="hs-table table-menu">
                        <tbody>
                            <tr>
                                <th><?=lang('hd_lang.job')?></th>
                                <th><?=lang('hd_lang.cron_last_run')?></th>
                                <th><?=lang('hd_lang.result')?></th>
                                <th><?=lang('hd_lang.active')?></th>
                            </tr>
                            <?php 
                                error_reporting(0);
                                $result = unserialize($custom->getconfig_item('cron_last_run')); ?>
                            <?php foreach($crons as $cron) : ?>
                            <tr>
                                <td><i class="fa fa-fw m-r-sm <?=($cron->icon != '' ? $cron->icon : 'cog')?>"></i>
                                    <?=lang($cron->name)?></td>
                                <td><?=date('Y-m-d H:i',strtotime($cron->last_run))?></td>
                                <td><?php   
                                                if ($result) { 
                                                    if (is_array($result[$cron->module])) {
                                                        echo $result[$cron->module]['result'];
                                                    } else {
                                                        echo ($result[$cron->module] ? lang('hd_lang.success'): lang('hd_lang.error'));
                                                    }
                                                } 
                                    ?></td>
                                <td>
                                    <a data-rel="tooltip" data-original-title="<?=lang('hd_lang.toggle_enabled')?>"
                                        class="cron-enabled-toggle btn btn-xs btn-<?=($cron->enabled == 1 ? 'success':'default')?> m-r-xs"
                                        href="#" data-role="1"
                                        data-href="<?=base_url()?>settings/hook/enabled/<?=$cron->module?>"><i
                                            class="fa fa-check"></i></a>
                                    <?php
                                         $session = \Config\Services::session(); 

                                         $db = \Config\Database::connect();

                                            $cron_set = $db->table('hd_hooks')->where("hook","cron_job_settings")->where("parent",$cron->module)->get()->getResultArray();
                                            if (count($cron_set) == 1) { $cron_set = $cron_set[0]; 
                                        ?>
                                    <a data-rel="tooltip" data-original-title="<?=lang('hd_lang.settings')?>"
                                        data-toggle="ajaxModal" class="cron-settings btn btn-xs btn-default"
                                        href="<?=base_url()?><?=$cron_set["route"]?>/<?=$cron->module?>"><i
                                            class="fa fa-cog"></i></a>
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <?php
        $attributes = array('class' => 'bs-example form-horizontal');
        echo form_open_multipart('settings/update', $attributes); ?>

                <?php //echo validation_errors(); ?>
                <input type="hidden" name="settings" value="<?=$load_setting?>">
                <div class="row mt-5">

                    <div class="col-md-8">
                        <div class="form-group">
                            <label class="control-label common-label"><?=lang('hd_lang.cron_key')?></label>
                            <div class="inputDiv">
                                <input type="text" class="form-control common-input" value="<?=$custom->getconfig_item('cron_key')?>"
                                    name="cron_key">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label common-label"><?=lang('hd_lang.auto_backup_db')?></label>
                            <div class="inputDiv">
                                <div class="inputDiv form-check form-switch input-btn-div">
                                    <input type="hidden" value="off" name="auto_backup_db" />
                                    <input type="checkbox" role="switch" class="form-check-input"
                                        <?php if($custom->getconfig_item('auto_backup_db') == 'TRUE'){ echo "checked=\"checked\""; } ?>
                                        name="auto_backup_db">
                                    <span></span>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="text-center mt-5">
                        <button type="submit"
                            class="btn btn-sm common-button btn-<?=$custom->getconfig_item('theme_color')?>"><?=lang('hd_lang.save_changes')?></button>
                    </div>

                    </form>
                </div>
            </div>
            <!-- End Form -->