<?= $this->extend('layouts/users') ?>

<?= $this->section('content') ?>

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
use App\Models\Plugin;
use App\Helpers\custom_name_helper;

$helper = new custom_name_helper();
$db = \Config\Database::connect();
$account = $db->table('hd_orders')->where('id', $id)->get()->getRow(); 

$packages = $db->table('hd_items_saved')->select('items_saved.*')->join('item_pricing','items_saved.item_id = item_pricing.item_id','INNER')->join('categories','categories.id = item_pricing.category','LEFT')->where('parent >', 8)->get()->getResult();
?>
<div class="box">
    <div class="box-body">

        <div class="row">
            <div class="col-md-8">
                <div class="box-body">

                    <section class="panel panel-default bg-white m-t-lg radius_3">
                        <header class="panel-heading text-center">
                            <h3><?= lang('hd_lang.manage'). " " .lang('hd_lang.account')?></h3>
                        </header>

                        <div class="panel-body">
                            <div class="box-body">
                                <?php
                        $attributes = array('class' => 'bs-example form-horizontal');
                    echo form_open(base_url().'domains/manage',$attributes); ?>
                                <input type="hidden" value="<?=$id?>" name="id">

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group modal-input">
                                            <label class="col-lg-4 control-label common-label"><?=lang('hd_lang.client')?></label>
                                            <div class="col-lg-8">
                                                <select class="select2-option w_200 common-select" name="client_id">
                                                    <?php foreach (Client::get_all_clients() as $client): ?>
                                                    <option value="<?=$client->co_id?>"
                                                        <?=($account->client_id == $client->co_id) ? 'selected' : ''?>>
                                                        <?=ucfirst($client->company_name)?></option>
                                                    <?php endforeach;  ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group modal-input">
                                            <label class="col-lg-4 control-label common-label"><?=lang('hd_lang.status')?></label>
                                            <div class="col-lg-8">
                                                <select name="status_id" class="select2-option w_200 common-select">
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



                                        <div class="form-group modal-input">
                                            <label class="col-lg-4 control-label common-label"><?=lang('hd_lang.registrar')?></label>
                                            <div class="col-lg-8">
                                                <select class="form-control common-select" name="registrar">
                                                    <option value="">None</option>
                                                    <?php   
                                                        $registrars = Plugin::domain_registrars();
                                                        foreach ($registrars as $registrar)
                                                        {?>
                                                    <option value="<?=$registrar->system_name;?>">
                                                        <?=ucfirst($registrar->system_name);?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-md-6">

                                        <div class="form-group modal-input">
                                            <label class="col-lg-4 control-label common-label"><?=lang('hd_lang.created')?></label>
                                            <div class="col-lg-8">
                                                <input class="input-sm input-s datepicker form-control common-input" size="16"
                                                    type="text" value="<?=$account->date?>" name="date"
                                                    data-date-format="<?=$helper->getconfig_item('date_picker_format');?>">
                                            </div>
                                        </div>

                                        <div class="form-group modal-input">
                                            <label class="col-lg-4 control-label common-label"><?=lang('hd_lang.next_due_date')?></label>
                                            <div class="col-lg-8">
                                                <input class="input-sm input-s datepicker form-control common-input" size="16"
                                                    type="text" value="<?=$account->renewal_date?>" name="renewal_date"
                                                    data-date-format="<?=$helper->getconfig_item('date_picker_format');?>">
                                            </div>
                                        </div>

                                        <div class="form-group modal-input">
                                            <label class="col-lg-4 control-label common-label"><?=lang('hd_lang.domain')?></label>
                                            <div class="col-lg-8">
                                                <input class="input form-control common-input" type="text"
                                                    value="<?=$account->domain?>" name="domain">
                                            </div>
                                        </div>

                                        <div class="form-group modal-input">
                                            <label class="col-lg-4 control-label common-label"><?=lang('hd_lang.authcode')?></label>
                                            <div class="col-lg-8">
                                                <input class="input form-control common-input" type="text"
                                                    value="<?=$account->authcode?>" name="authcode">
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="form-group modal-input">
                                    <label class="col-lg-2 control-label common-label"><?=lang('hd_lang.notes')?></label>
                                    <div class="col-lg-10">
                                        <input class="input form-control common-input" type="text" value="<?=$account->notes?>"
                                            name="notes">
                                    </div>
                                </div>


                                <div class="form-group modal-input">
                                    <label class="col-lg-4 control-label common-label"> </label>
                                    <div class="col-lg-8">
                                        <input class="btn btn-primary pull-right" type="submit"
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