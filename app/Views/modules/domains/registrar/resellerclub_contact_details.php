<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

use App\Libraries\AppLib;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\Plugin;
use App\Models\User;

// echo "<pre>";print_r($contact_detail);die;

?>

<?= $this->extend('layouts/users') ?>

<?= $this->section('content') ?>

<div class="box">
    <div class="box-body">
        <?php
        $attributes = array('class' => 'bs-example form-horizontal', 'method' => 'POST');
        echo form_open(base_url('domains/edit_contact_details'), $attributes);
        ?>
        <div class="row">

            <div class="col-md-6">

                <input class="form-control" type="hidden" name="co_id" id="co_id" value="<?= $contact_detail->co_id; ?>">
                <input class="form-control" type="hidden" name="contact_id" id="contact_id" value="<?= $contact_detail->contact_id; ?>">
                <input class="form-control" type="hidden" name="db_contact_id" id="db_contact_id" value="<?= $contact_detail->con_id; ?>">

                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= lang('hd_lang.name') ?></label>
                    <div class="col-lg-8">
                        <input class="form-control" type="text" name="user_name" id="user_name" value="<?= $contact_detail->contact_id; ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= lang('hd_lang.company') ?></label>
                    <div class="col-lg-8">
                        <input class="form-control" type="text" name="company" id="company" value="<?= $contact_detail->company; ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= lang('hd_lang.email') ?></label>
                    <div class="col-lg-8">
                        <input class="form-control" type="text" name="email" id="email" value="<?= $contact_detail->email; ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= lang('hd_lang.phone-cc') ?></label>
                    <div class="col-lg-8">
                        <input class="form-control" type="number" name="phone-cc" id="phone-cc" value="<?= $contact_detail->telnocc; ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= lang('hd_lang.phone') ?></label>
                    <div class="col-lg-8">
                        <input class="form-control" type="text" name="phone" id="phone" value="<?= $contact_detail->tel_no; ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= lang('hd_lang.address-line-1') ?></label>
                    <div class="col-lg-8">
                        <input class="form-control" type="text" name="address-line-1" id="address-line-1" value="<?= $contact_detail->address1; ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= lang('hd_lang.address-line-2') ?></label>
                    <div class="col-lg-8">
                        <input class="form-control" type="text" name="address-line-2" id="address-line-2" value="<?= $contact_detail->address2; ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= lang('hd_lang.city') ?></label>
                    <div class="col-lg-8">
                        <input class="form-control" type="text" name="city" id="city" value="<?= $contact_detail->city; ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= lang('hd_lang.state') ?></label>
                    <div class="col-lg-8">
                        <input class="form-control" type="text" name="state" id="state" value="<?= $contact_detail->state; ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= lang('hd_lang.country') ?></label>
                    <div class="col-lg-8">
                        <input class="form-control" type="text" name="country" id="country" value="<?= $contact_detail->country; ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= lang('hd_lang.zipcode') ?></label>
                    <div class="col-lg-8">
                        <input class="form-control" type="number" name="zipcode" id="zipcode" value="<?= $contact_detail->zip; ?>">
                    </div>
                </div>

            </div>
        </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>