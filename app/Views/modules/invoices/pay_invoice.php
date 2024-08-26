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
use App\Models\App;

use App\Helpers\custom_name_helper;

$helper = new custom_name_helper();

?>
<?= $this->extend('layouts/users') ?>

<?= $this->section('content') ?>
<div class="box box-top">
    <?php $inv = Invoice::view_by_id($id); ?>
    <div class="box-header b-b clearfix hidden-print">

        <div class="btn-group pull-right">

            <a href="<?=site_url()?>invoices/view/<?=$inv->inv_id?>" class="btn btn-sm btn-success">
                <?=lang('hd_lang.view_invoice')?>
            </a>

            <a href="<?=base_url()?>fopdf/invoice/<?=$inv->inv_id?>" class="btn btn-sm btn-primary pull-right">
                <i class="fa fa-file-pdf-o"></i> <?=lang('hd_lang.pdf')?>
            </a>

        </div>

    </div>
    <div class="box-body">
        <?php
	$attributes = array('class' => 'bs-example form-horizontal');
			   echo form_open_multipart(base_url().'invoices/pay',$attributes);
			   $cur = App::currencies($helper->getconfig_item('default_currency'));
		?>

        <input type="hidden" name="invoice" value="<?=$id?>">
        <input type="hidden" name="currency" value="<?=$cur->code?>">

        <div class="form-group modal-input">
            <label class="col-lg-3 control-label common-label"><?=lang('hd_lang.trans_id')?> <span class="text-danger">*</span></label>
            <div class="col-lg-8">
                <?php helper('text'); ?>
                <input type="text" class="form-control common-input" value="<?=random_string('nozero', 6);?>" name="trans_id"
                    readonly>
            </div>
        </div>
        <div class="form-group modal-input">

            <label class="col-lg-3 control-label common-label"><?=lang('hd_lang.amount')?> (<?=$cur->symbol?>)
                <span class="text-danger">*</span></label>
            <div class="col-lg-8">
                <input type="text" class="form-control common-input"
                    value="<?=Applib::format_deci(Invoice::get_invoice_due_amount($id));?>" name="amount">
            </div>
        </div>

        <div class="form-group modal-input">
            <label class="col-lg-3 control-label common-label"><?=lang('hd_lang.payment_date')?></label>
            <div class="col-lg-8">
                <input class="input-sm input-s datepicker-input form-control common-input" size="16" type="text"
                    value="<?=strftime($helper->getconfig_item('date_format'), time());?>" name="payment_date"
                    data-date-format="<?=$helper->getconfig_item('date_picker_format');?>">
            </div>
        </div>

        <div class="form-group modal-input">
            <label class="col-lg-3 control-label common-label"><?=lang('hd_lang.payment_method')?> <span class="text-danger">*</span></label>
            <div class="col-lg-8">
                <select name="payment_method" class="form-control common-select">
                    <?php
                                            foreach (Invoice::payment_methods() as $key => $m) { ?>
                    <option value="<?=$m->method_id?>"><?=$m->method_name?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="form-group modal-input">
            <label class="col-lg-3 control-label common-label"><?=lang('hd_lang.notes')?></label>
            <div class="col-lg-8">
                <textarea name="notes" class="form-control ta common-input"></textarea>
            </div>
        </div>

        <div class="form-group modal-input">
            <label class="col-lg-3 control-label common-label"><?=lang('hd_lang.payment_slip')?></label>
            <div class="col-lg-3">
                <label class="switch common-label">
                    <input type="hidden" value="off" name="attach_slip" />
                    <input type="checkbox" name="attach_slip" id="attach_slip">
                    <span></span>
                </label>
            </div>
        </div>

        <div id="attach_field" style="display:none;">
            <div class="form-group modal-input">
                <label class="col-lg-3 control-label common-label"><?=lang('hd_lang.attach_file')?></label>

                <div class="col-lg-3">
                    <input type="file" class="filestyle common-input" data-buttonText="<?=lang('hd_lang.choose_file')?>" data-icon="false"
                        data-classButton="btn btn-default" data-classInput="form-control inline input-s"
                        name="payment_slip">
                </div>

            </div>
        </div>

        <div class="form-group modal-input">
            <label class="col-lg-3 control-label common-label"><?=lang('hd_lang.send_email')?></label>
            <div class="col-lg-8">
                <label class="switch common-label">
                    <input class ="common-input" type="checkbox" name="send_thank_you">
                    <span></span>
                </label>
            </div>
        </div>
        <div class="modal-footer"> <a href="<?=base_url()?>invoices/view/<?=$id?>" class="btn btn-default"
                data-dismiss="modal"><?=lang('hd_lang.close')?></a>
            <button type="submit" class="btn btn-<?=$helper->getconfig_item('theme_color');?> common-button"><?=lang('hd_lang.add_payment')?></button>
            </form>

        </div>
    </div>
    <?= $this->endSection() ?>


    <!-- end -->