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

use App\Helpers\custom_name_helper;

$custom_name_helper = new custom_name_helper();
$session = session();
$successResponse = $session->getFlashdata('successResponse');
if ($successResponse) {
    $successResponse = json_decode($successResponse, true);
    echo '<div class="alert alert-success">' . $successResponse['message'] . '</div>';
}

?>

<!-- Start create invoice -->
<div class="box ">
    <div class="box-body">
        <?= $this->extend('layouts/users') ?>

        <?= $this->section('content') ?>

        <?php
        $attributes = array('class' => 'bs-example form-horizontal p-4');
        echo form_open(base_url('invoices/add'), $attributes);
        ?>

        <div class="row custom-invoice-add">
            <div class="col-md-6">

                <div class="form-group my-2 row">
                    <label class="col-lg-3 control-label common-label"><?= lang('hd_lang.client') ?> <span class="text-danger">*</span>
                    </label>
                    <div class="col-lg-8 row mx-1">
                        <select class="select2-option common-select col-6 custom-invoice-width" name="client">
                            <optgroup label="<?= lang('hd_lang.clients') ?>">
                                <?php foreach (Client::get_all_clients() as $client): ?>
                                    <option value="<?= $client->co_id ?>">
                                        <?= ucfirst($client->company_name) ?></option>
                                <?php endforeach;  ?>
                            </optgroup>
                        </select>
                        <a href="<?= base_url() ?>companies/create" class="btn btn-<?= $custom_name_helper->getconfig_item('theme_color'); ?> btn-sm common-button col-auto"
                            data-toggle="ajaxModal" title="<?= lang('hd_lang.new_company') ?>" data-placement="bottom"><i
                                class="fa fa-plus"></i>
                            <?= lang('hd_lang.new_client') ?></a>
                    </div>

                </div>


                <div class="form-group mt-4 row">
                    <label class="col-lg-3 control-label common-label"><?= lang('hd_lang.tax') ?> </label>
                    <div class="col-lg-8">
                        <div class="input-group">
                            <span class="input-group-addon left-pos-border" style='width: 4% !important;'>%</span>
                            <input class="form-control money common-input" type="text" value="<?= $custom_name_helper->getconfig_item('default_tax') ?>"
                                name="tax" style='width: 90% !important;padding-left: 35px !important;'>
                        </div>
                    </div>
                </div>

                <div class="form-group my-2 row">
                    <label class="col-lg-3 control-label common-label"><?= lang('hd_lang.discount') ?></label>
                    <div class="col-lg-8">
                        <div class="input-group">
                            <span class="input-group-addon left-pos-border" style='width: 4% !important;'>%</span>
                            <input class="form-control money common-input" type="text" value="<?= set_value('discount') ?>"
                                name="discount" style='width: 90% !important;padding-left: 35px !important;'>
                        </div>
                    </div>
                </div>

                <div class="form-group my-2 row">
                    <label class="col-lg-3 control-label common-label"><?= lang('hd_lang.extra_fee') ?></label>
                    <div class="col-lg-8">
                        <div class="input-group">
                            <span class="input-group-addon left-pos-border" style='width: 4% !important;'>%</span>
                            <input class="form-control money common-input" type="text" value="<?= set_value('extra_fee') ?>"
                                name="extra_fee" style='width: 90% !important;padding-left: 35px !important;'>
                        </div>
                    </div>
                </div>


            </div>

            <div class="col-md-6">

                <div class="form-group my-2 row">
                    <label class="col-lg-4 control-label common-label"><?= lang('hd_lang.reference_no') ?> <span
                            class="text-danger">*</span></label>
                    <div class="col-lg-5">
                        <input type="text" class="form-control common-input" value="<?= $custom_name_helper->getconfig_item('invoice_prefix') ?><?php
                                                                                                                          ?>" name="reference_no">
                    </div>

                </div>


                <div class="form-group my-2 row">
                    <label class="col-lg-4 control-label common-label"><?= lang('hd_lang.due_date') ?></label>
                    <div class="col-lg-5">
                        <input class="input-sm input-s datepicker-input form-control common-input" size="16" type="text"
                            value="<?= strftime($custom_name_helper->getconfig_item('date_format'), strtotime("+" . $custom_name_helper->getconfig_item('invoices_due_after') . " days")); ?>"
                            name="due_date" data-date-format="<?= $custom_name_helper->getconfig_item('date_picker_format'); ?>">
                    </div>
                </div>


            </div>
        </div>

        <div class="row">
            <div class="col-lg-9">
                <div class="form-group my-2 terms">
                    <label class="control-label common-label"><?= lang('hd_lang.notes') ?> </label>
                    <textarea name="notes" class="form-control foeditor common-input"
                        value="notes"><?= $custom_name_helper->getconfig_item('default_terms') ?></textarea>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-sm btn-<?= $custom_name_helper->getconfig_item('theme_color'); ?> pull-right common-button"><i
                class="fa fa-plus"></i> <?= lang('hd_lang.create_invoice') ?>
        </button>

        <?php echo form_close() ?>
        <?= $this->endSection() ?>
    </div>
</div>