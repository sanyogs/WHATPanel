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
<div class="box box-top">

    <div class="box-header clearfix d-flex flex-wrap gap-2">
        <?php $i = Payment::view_by_id($id); ?>
        <?php if(User::is_admin()){ ?>

        <a href="<?=base_url()?>payments/edit/<?=$i->p_id?>" title="<?=lang('hd_lang.edit_payment')?>"
            class="btn btn-sm common-button btn-<?=$custom->getconfig_item('theme_color');?>">
            <i class="fa fa-pencil text-white"></i> <?=lang('hd_lang.edit_payment')?></a>

        <?php if($i->refunded == 'No'){ ?>
        <a href="<?=base_url()?>payments/refund/<?=$i->p_id?>" title="<?=lang('hd_lang.refund')?>"
            class="btn btn-sm common-button btn-<?=$custom->getconfig_item('theme_color');?>" data-toggle="ajaxModal">
            <i class="fa fa-warning text-white"></i> <?=lang('hd_lang.refund')?></a>
        <?php } ?>

        <?php } ?>

        <a href="<?=base_url()?>payments/pdf/<?=$i->p_id?>" title="<?=lang('hd_lang.pdf')?>"
            class="btn btn-sm common-button btn-<?=$custom->getconfig_item('theme_color');?>">
            <i class="fa fa-file-pdf-o text-white"></i> <?=lang('hd_lang.pdf')?> <?=lang('hd_lang.receipt')?></a>
    </div>

    <div class="box-body">

        <!-- Start Payment -->
        <?php if($i->refunded == 'Yes') { ?>
        <div class="alert alert-danger hidden-print">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <i class="fa fa-warning"></i> <?=lang('hd_lang.transaction_refunded')?>
        </div>
        <?php } ?>


        <div class="column content-column mt-5">
            <div class="details-page">
                <div class="details-container clearfix mb_20" id="payment_view">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="table-responsive">
                            <table class="hs-table mt-0">
                                <tbody>
                                    <tr>
                                        <td class="line_label"><?=lang('hd_lang.payment_date')?></td>
                                        <td><?=strftime($custom->getconfig_item('date_format')." %H:%M:%S", strtotime($i->created_date));?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="line_label"><?=lang('hd_lang.transaction_id')?></td>
                                        <td><?=$i->trans_id?></td>
                                    </tr>
                                    <tr>
                                        <td class="line_label"><?=lang('hd_lang.received_from')?></td>
                                        <td><strong><a href="<?=base_url()?>companies/view/<?=$i->paid_by?>">
                                                    <?=ucfirst(Client::view_by_id($i->paid_by)->company_name);?></a></strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="line_label"><?=lang('hd_lang.payment_mode')?></td>
                                        <td><?=App::get_method_by_id($i->payment_method)?></td>
                                    </tr>
                                    <tr>
                                        <td class="line_label"><?=lang('hd_lang.notes')?></td>
                                        <td><?=($i->notes) ? $i->notes : 'NULL'; ?></td>
                                    </tr>
                                    <?php if($i->attached_file) : ?>
                                    <tr>
                                        <td class="line_label"><?=lang('hd_lang.attachment')?></td>
                                        <td><a href="<?=base_url()?>resource/uploads/<?=$i->attached_file?>"
                                                target="_blank">
                                                <?=$i->attached_file?>
                                            </a></td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bg-<?=$custom->getconfig_item('theme_color')?> payment_received">
                                <span> <?=lang('hd_lang.amount_received')?></span>
                                <?php $cur = Invoice::view_by_id($i->invoice)->currency; ?>
                                <span><?=AppLib::format_currency($i->amount, 'default_currency')?></span>
                            </div>
                        </div>
                    </div>



                    <div class="hs-topbar-wrap mt-5">
                        <div class="hs-title-wrap">
                            <h3><?=lang('hd_lang.payment_for')?></h3>
                        </div>
                    </div>

                    <div class="table-responsive">
                    <table class="payment_details hs-table mt-0">

                        <tbody>
                            <tr class="h_40">
                                <td class="p_item">
                                    <?=lang('hd_lang.invoice_code')?>
                                </td>
                                <td class="p_item_r">
                                    <?=lang('hd_lang.invoice_date')?>
                                </td>
                                <td class="p_item_r">
                                    <?=lang('hd_lang.due_amount')?>
                                </td>
                                <td class="p_item_r">
                                    <?=lang('hd_lang.paid_amount')?>
                                </td>
                            </tr>
                            <tr class="p_border">
                                <td class="pp_10" valign="top">
                                    <a
                                        href="<?=base_url()?>invoices/view/<?=$i->invoice?>"><?=Invoice::view_by_id($i->invoice)->reference_no;?></a>
                                </td>
                                <td class="p_td" valign="top">
                                    <?=strftime($custom->getconfig_item('date_format'), strtotime(Invoice::view_by_id($i->invoice)->date_saved));?>
                                </td>
                                <td class="p_td" valign="top">
                                    <span>
                                        <?=Applib::format_currency(Invoice::get_invoice_due_amount($i->invoice), 'default_currency')?>
                                    </span>
                                </td>
                                <td class="p_td_r" valign="top">
                                    <span><?=Applib::format_currency($i->amount, 'default_currency')?></span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
        <br>
        
    </div>

</div>
<?= $this->endSection() ?>
<!-- End Payment -->