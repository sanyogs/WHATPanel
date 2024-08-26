<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */ 
use App\Models\Client;

use App\Helpers\custom_name_helper;

$db = \Config\Database::connect();

$custom_helper = new custom_name_helper();

$account = $db->table('hd_orders')
                    ->where('id', $id)
                    ->get()
                    ->getRow();

$query = $db->table('hd_items_saved')
    ->select('hd_items_saved.*')
    ->join('hd_item_pricing', 'hd_items_saved.item_id = hd_item_pricing.item_id', 'INNER')
    ->join('hd_categories', 'hd_categories.id = hd_item_pricing.category', 'LEFT')
    ->where('parent >', 8)
    ->get()->getResult();

$packages = $db->table('hd_items_saved')
                     ->get()
                     ->getResult();
?>
<?= $this->extend('layouts/users') ?>

<?= $this->section('content') ?>
<div class="box box-top">
    <div class="box-body">

        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="box-body">

                    <section class="panel panel-default bg-white m-t-lg radius_3 p-3">
                        <header class="panel-heading text-center">
                            <h3 class='common-h text-start'><?= lang('hd_lang.manage'). " " .lang('hd_lang.account')?></h3>
                        </header>

                        <div class="panel-body">
                            <div class="box-body">
                                <?php
                        $attributes = array('class' => 'bs-example form-horizontal');
                    echo form_open(base_url().'accounts/manage',$attributes); ?>
                                <input type="hidden" value="<?=$id?>" name="id">

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mt-3">
                                            <label class="col-lg-4 control-label m-0 common-label"><?=lang('hd_lang.client')?></label>
                                            <div class="col-lg-8">
                                                <select class="select2-option common-select w_200" name="client_id">
                                                    <?php foreach (Client::get_all_clients() as $client): ?>
                                                    <option value="<?=$client->co_id?>"
                                                        <?=($account->client_id == $client->co_id) ? 'selected' : ''?>>
                                                        <?=ucfirst($client->company_name)?></option>
                                                    <?php endforeach;  ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group mt-3">
                                            <label class="col-lg-4 control-label m-0 common-label"><?=lang('hd_lang.status')?></label>
                                            <div class="col-lg-8">
                                                <select name="status_id" class="select2-option common-select w_200">
                                                    <option value="5" <?=($account->status_id == 5) ? 'selected' : ''?>>
                                                        <?=lang('hd_lang.pending')?></option>
                                                    <option value="6" <?=($account->status_id == 6) ? 'selected' : ''?>>
                                                        <?=lang('hd_lang.active')?></option>
                                                    <option value="7" <?=($account->status_id == 7) ? 'selected' : ''?>>
                                                        <?=lang('hd_lang.cancelled')?></option>
                                                    <option value="9" <?=($account->status_id == 9) ? 'selected' : ''?>>
                                                        <?=lang('hd_lang.suspended')?></option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group mt-3">
                                            <label class="col-lg-4 control-label m-0 common-label"><?=lang('hd_lang.server')?></label>
                                            <div class="col-lg-8">
                                                <select id="server" name="server" class="select2-option common-select w_200">
                                                    <?php $servers = $db->table('hd_servers')->get()->getResult();
                                            foreach ($servers as $server) { ?>
                                                    <option value="<?=$server->id?>"
                                                        <?=($account->server == $server->id) ? 'selected' : ''?>>
                                                        <?=$server->name?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group mt-3">
                                            <label class="col-lg-4 control-label m-0 common-label"><?=lang('hd_lang.package')?></label>
                                            <div class="col-lg-8">
                                                <select name="item_parent" class="select2-option common-select w_200">
                                                    <?php foreach ($packages as $package) { ?>
                                                    <option value="<?=$package->item_id?>"
                                                        <?=($package->item_id == $account->item_parent) ? 'selected' : ''?>>
                                                        <?=$package->item_name?> (<?=$package->package_name?>)</option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group mt-3">
                                            <label class="col-lg-4 control-label m-0 common-label"><?=lang('hd_lang.domain')?></label>
                                            <div class="col-lg-8">
                                                <input class="input form-control common-input m-0" type="text"
                                                    value="<?=$account->domain?>" name="domain">
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-md-6">

                                        <div class="form-group mt-3">
                                            <label class="col-lg-4 control-label m-0 common-label"><?=lang('hd_lang.created')?></label>
                                            <div class="col-lg-8">
                                                <input class="input-sm input-s datepicker form-control common-input m-0" size="16"
                                                    type="text" value="<?=$account->date?>" name="date"
                                                    data-date-format="<?=$custom_helper->getconfig_item('date_picker_format');?>">
                                            </div>
                                        </div>

                                        <div class="form-group mt-3">
                                            <label class="col-lg-4 control-label m-0 common-label"><?=lang('hd_lang.next_due_date')?></label>
                                            <div class="col-lg-8">
                                                <input class="input-sm input-s datepicker form-control common-input m-0" size="16"
                                                    type="text" value="<?=$account->renewal_date?>" name="renewal_date"
                                                    data-date-format="<?=$custom_helper->getconfig_item('date_picker_format');?>">
                                            </div>
                                        </div>


                                        <div class="form-group mt-3">
                                            <label class="col-lg-4 control-label m-0 common-label"><?=lang('hd_lang.renewal')?></label>
                                            <div class="col-lg-8">
                                                <select name="renewal" class="select2-option common-select w_200">
                                                    <?php $list = array('monthly', 'quarterly', 'semi_annually', 'annually'); ?>
                                                    <?php foreach($list as $li) { ?>
                                                    <option value="<?=$li?>"
                                                        <?=($li == $account->renewal) ? 'selected' : ''?>><?=lang($li)?>
                                                    </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>


                                        <div class="form-group mt-3">
                                            <label class="col-lg-4 control-label m-0 common-label"><?=lang('hd_lang.username')?></label>
                                            <div class="col-lg-8">
                                                <input class="input form-control common-input m-0" type="text"
                                                    value="<?=$account->username?>" name="username">
                                            </div>
                                        </div>


                                        <div class="form-group mt-3">
                                            <label class="col-lg-4 control-label m-0 common-label"><?=lang('hd_lang.password')?></label>
                                            <div class="col-lg-8">
                                                <input class="input form-control common-input m-0" type="text"
                                                    value="<?=$account->password?>" name="password">
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="form-group mt-3">
                                    <label class="col-lg-2 control-label m-0 common-label"><?=lang('hd_lang.notes')?></label>
                                    <div class="col-lg-10">
                                        <input class="input form-control common-input m-0" type="text" value="<?=$account->notes?>"
                                            name="notes">
                                    </div>
                                </div>


                                <div class="form-group mt-3">
                                    <div class="col-lg-10">
                                        <input class="btn btn-primary pull-right common-button" type="submit"
                                            value="<?=lang('hd_lang.save')?>">
                                    </div>
                                </div>


                                </form>
                            </div>
                        </div>
                    </section>

                </div>
            </div>
        </div>

    </div>
</div>
<?= $this->endSection() ?>