<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */
use App\ThirdParty\MX\Modules;
?>

<section id="content">
    <section class="hbox stretch">
        <!-- .aside -->
        <aside>
            <section class="vbox">
                <header class="header bg-white b-b b-light">
                    <a href="#aside" data-toggle="class:show" class="btn btn-sm btn-primary pull-right"><i
                            class="fa fa-plus"></i> <?=lang('hd_lang.new_user')?></a>
                    <p><?=lang('hd_lang.registered_clients')?></p>
                </header>
                <section class="scrollable wrapper">

                    <div class="row">
                        <div class="col-lg-12">
                            <?php  echo Modules::run('sidebar/flash_msg');?>
                            <section class="panel panel-default">

                                <div class="table-responsive">
                                    <table id="clients" class="table table-striped m-b-none">
                                        <thead>
                                            <tr>
                                                <th><?=lang('hd_lang.avatar_image')?></th>
                                                <th><?=lang('hd_lang.username')?> </th>
                                                <th><?=lang('hd_lang.full_name')?></th>
                                                <th><?=lang('hd_lang.company')?> </th>
                                                <th><?=lang('hd_lang.role')?> </th>
                                                <th><?=lang('hd_lang.registered_on')?> </th>
                                                <th class="col-options no-sort"><?=lang('hd_lang.options')?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                              if (!empty($users)) {
                                              foreach ($users as $key => $user) { ?>
                                            <tr>
                                                <td><a class="pull-left thumb-sm avatar">
                                                        <img src="<?=base_url()?>resource/avatar/<?=$user->avatar?>"
                                                            class="img-circle"></a>
                                                </td>
                                                <td><a href="<?=base_url()?>contacts/view/details/<?=$user->user_id*1200?>"
                                                        class="text-info"><?=ucfirst($user->username)?></a></td>
                                                <td><?=$user->fullname?></td>
                                                <td><?=$user->company?></td>
                                                <td><?php
                                                    if ($this->user_profile->role_by_id($user->role_id) == 'admin') {
                                                      $span_badge = 'label label-danger';
                                                    }elseif ($this->user_profile->role_by_id($user->role_id) == 'staff') {
                                                      $span_badge = 'label label-primary';
                                                    }
                                                    else{
                                                      $span_badge = '';
                                                    }
                                                    ?><span class="<?=$span_badge?>">
                                                        <?=ucfirst($this->user_profile->role_by_id($user->role_id))?></span>
                                                </td>
                                                <td><?=strftime(config_item('date_format'), strtotime($user->created));?>
                                                </td>
                                                <td>
                                                    <a href="<?=base_url()?>users/view/update/<?=$user->user_id?>"
                                                        class="btn btn-default btn-xs" data-toggle="ajaxModal"
                                                        title="<?=lang('hd_lang.edit')?>"><i class="fa fa-pencil"></i> </a>
                                                    <?php
					                                                if ($user->username != $this->tank_auth->get_username()) { ?>
                                                    <a href="<?=base_url()?>users/account/delete/<?=$user->user_id?>"
                                                        class="btn btn-danger btn-xs" data-toggle="ajaxModal"
                                                        title="<?=lang('hd_lang.delete')?>"><i class="fa fa-trash-o"></i></a>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <?php } } ?>


                                        </tbody>
                                    </table>
                                </div>
                            </section>
                        </div>
                    </div>

                </section>
            </section>
        </aside>
        <!-- /.aside -->
        <!-- .aside -->
        <aside class="aside-lg bg-white b-l hide" id="aside">
            <div class="scrollable wrapper">
                <h4 class="m-t-none"><?=lang('hd_lang.new_client')?></h4>
                <?php
                echo form_open(base_url().'auth/register_user'); ?>
                <?php echo $this->session->flashdata('form_errors'); ?>
                <input type="hidden" name="r_url" value="<?=base_url()?>contacts">
                <div class="form-group">
                    <label><?=lang('hd_lang.username')?> <span class="text-danger">*</span></label>
                    <input type="text" name="username" placeholder="<?=lang('hd_lang.eg')?> johndoe"
                        value="<?=set_value('username')?>" class="input-sm form-control">
                </div>
                <div class="form-group">
                    <label><?=lang('hd_lang.email')?> <span class="text-danger">*</span></label>
                    <input type="email" placeholder="johndoe@me.com" name="email" value="<?=set_value('email')?>"
                        class="input-sm form-control">
                </div>
                <div class="form-group">
                    <label><?=lang('hd_lang.password')?> <span class="text-danger">*</span></label>
                    <input type="password" placeholder="<?=lang('hd_lang.password')?>" value="<?=set_value('password')?>"
                        name="password" class="input-sm form-control">
                </div>
                <div class="form-group">
                    <label><?=lang('hd_lang.confirm_password')?> <span class="text-danger">*</span></label>
                    <input type="password" placeholder="<?=lang('hd_lang.confirm_password')?>"
                        value="<?=set_value('confirm_password')?>" name="confirm_password"
                        class="input-sm form-control">
                </div>
                <div class="form-group">
                    <label><?=lang('hd_lang.company')?> </label>
                    <input type="text" value="<?=set_value('company')?>" name="company" class="input-sm form-control">
                </div>
                <div class="form-group">
                    <label><?=lang('hd_lang.role')?></label>
                    <div>
                        <select name="role" class="form-control">
                            <?php
                      if (!empty($roles)) {
                      foreach ($roles as $r) { ?>
                            <option value="<?=$r->r_id?>"><?=ucfirst($r->role)?></option>
                            <?php } } ?>
                        </select>
                    </div>
                </div>
                <div class="m-t-lg"><button class="btn btn-sm btn-success"><?=lang('hd_lang.register_user')?></button></div>
                </form>
            </div>
        </aside>
        <!-- /.aside -->
    </section>
    <a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen, open" data-target="#nav,html"></a>
</section>