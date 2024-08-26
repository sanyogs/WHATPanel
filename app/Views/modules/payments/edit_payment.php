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

use App\Libraries\AppLib;
use App\Models\App;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\Payment;
use App\Models\User;

use App\Helpers\custom_name_helper;

$custom = new custom_name_helper();
?>
<!-- Start -->
<div class="box box-top">

    <div class="box-header b-b d-flex justify-content-between mb-5 flex-wrap">
        <?php $i = Payment::view_by_id($id); ?>
        <div class="hs-title-wrap">
            <h3><i class="fa fa-info-circle"></i> <?= lang('hd_lang.payment') ?> - <?= $i->trans_id ?> </h3>
        </div>
        <a href="<?= base_url() ?>payments/view/<?= $i->p_id ?>"
            data-original-title="<?= lang('hd_lang.view_details') ?>"
            class="btn btn-<?= $custom->getconfig_item('theme_color'); ?> btn-sm common-button"><i
                class="fa fa-info-circle"></i>
            <?= lang('hd_lang.payment_details') ?></a>
    </div>
    <div class="box-body">

        <div class="row">
            <div class="col-md-5 mx-auto">
                <?php
        $attributes = array('class' => 'bs-example form-horizontal');
        echo form_open(base_url() . 'payments/edit', $attributes); ?>
                <input type="hidden" name="p_id" value="<?= $i->p_id ?>">

                <div class="form-group">
                    <label class="common-label control-label"><?= lang('hd_lang.amount') ?> <span
                            class="text-danger">*</span></label>
                    <div class="inputDiv">
                        <input type="text" class="form-control common-input" value="<?= $i->amount ?>" name="amount">
                    </div>
                </div>

                <div class="form-group">
                    <label class="common-label control-label"><?= lang('hd_lang.payment_method') ?> <span
                            class="text-danger">*</span></label>
                    <div class="inputDiv">
                        <select name="payment_method" class="common-select mb-3">
                            <?php foreach (App::list_payment_methods() as $key => $p_method) { ?>
                            <option value="<?= $p_method->method_id ?>"
                                <?= ($i->payment_method == $p_method->method_id ? ' selected="selected"' : '') ?>>
                                <?= $p_method->method_name ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <?php $currency = App::currencies($i->currency); ?>
                <div class="form-group">
                    <label class="common-label control-label"><?= lang('hd_lang.currency') ?> <span
                            class="text-danger">*</span></label>
                    <div class="inputDiv">
                        <select name="currency" class="common-select mb-3">
                            <?php foreach (App::currencies() as $cur) : ?>
                            <option value="<?= $cur->code ?>"
                                <?= (is_object($currency) && $currency->code == $cur->code ? ' selected="selected"' : '') ?>>
                                <?= $cur->name ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>


                <div class="form-group">
                    <label class="common-label control-label"><?= lang('hd_lang.payment_date') ?> <span
                            class="text-danger">*</span></label>
                    <div class="inputDiv">
                        <input class="input-sm input-s datepicker-input form-control mb-3 common-input" size="16" type="date"
                            value="<?= strftime($custom->getconfig_item('date_format'), strtotime($i->payment_date)); ?>"
                            name="payment_date"
                            data-date-format="<?= $custom->getconfig_item('date_picker_format'); ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="common-label control-label"><?= lang('hd_lang.notes') ?> </label>
                    <div class="inputDiv">
                        <textarea name="notes" class="form-control ta common-input"><?= $i->notes ?></textarea>
                    </div>
                </div>
                <div class="text-center mt-4">
                    <button type="submit"
                        class="btn common-button btn-sm btn-<?= $custom->getconfig_item('theme_color'); ?>">
                        <?= lang('hd_lang.save_changes') ?></button>
                </div>
            </div>
        </div>
        </form>
    </div>

</div>
<?= $this->endSection() ?>

<!-- end -->