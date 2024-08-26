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
use App\Models\Plugin;
use App\Models\User;

use App\Helpers\custom_name_helper;

$custom = new custom_name_helper();

?>
<div class="box box-top p-3 custom-invoice-payments">

    <?php $inv = Invoice::view_by_id($id); ?>
    <div class="box-header ">

        <strong class='common-h'><?=lang('hd_lang.invoice')?> <?=$inv->reference_no?></strong>
        <div class="btn-group gap-3">
            <a href="<?=site_url()?>invoices/view/<?=$inv->inv_id?>" class="btn btn-sm btn-success common-button">
                <?=lang('hd_lang.view_invoice')?>
            </a>


            <?php if(User::is_admin() || User::perm_allowed(User::get_id(),'edit_all_invoices')) { ?>

            <?php } ?>

            <?php if (Invoice::payment_status($inv->inv_id) != 'fully_paid') : ?>


            <?php endif; ?>


            <?php if(User::is_admin() || User::perm_allowed(User::get_id(),'email_invoices')) { ?>

            <a class="btn btn-sm btn-secondary common-button" href="<?= base_url() ?>invoices/send_invoice/<?= $inv->inv_id ?>"
                data-toggle="ajaxModal" title="<?= lang('hd_lang.email_invoice') ?>"><?= lang('hd_lang.email_invoice') ?></a>

            <?php } if (User::is_admin() || User::perm_allowed(User::get_id(),'send_email_reminders')) { ?>

            <a class="btn btn-sm btn-danger common-button" href="<?= base_url() ?>invoices/remind/<?= $inv->inv_id ?>"
                data-toggle="ajaxModal" title="<?= lang('hd_lang.send_reminder') ?>"><?= lang('hd_lang.send_reminder') ?></a>

            <?php } ?>


            <?php if ($custom->getconfig_item('pdf_engine') == 'invoicr') { ?>
            <a href="<?= base_url() ?>invoices/pdf/<?=$inv->inv_id ?>" class="btn btn-sm btn-primary common-button"><i
                    class="fa fa-file-pdf-o"></i> <?=lang('hd_lang.pdf') ?></a>
            <?php } ?>

        </div>
    </div>

    <div class="box-body pt-3">

        <div class="table-responsive">
            <table id="table-payments" class="table table-striped b-t b-light AppendDataTables common-table">
                <thead>
                    <tr>
                        <th class="col-options no-sort  col-sm-2 text-center"><?=lang('hd_lang.trans_id')?></th>
                        <th class="col-sm-3 text-center"><?=lang('hd_lang.client')?></th>
                        <th class="col-date col-sm-2 text-center"><?=lang('hd_lang.payment_date')?></th>
                        <th class="col-currency col-sm-2 text-center"><?=lang('hd_lang.amount')?></th>
                        <th class="col-sm-2 text-center"><?=lang('hd_lang.payment_method')?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($payments as $key => $p) { ?>


                    <tr>

                        <td>

                            <a href="<?=base_url()?>payments/view/<?=$p->p_id?>" class="small text-info text-white">
                                <?=$p->trans_id?>
                            </a>
                        </td>


                        <td>
                            <?php echo Client::view_by_id($p->paid_by)->company_name; ?>
                        </td>


                        <td><?=strftime($custom->getconfig_item('date_format'), strtotime($p->payment_date));?></td>


                        <td class="col-currency"><?=Applib::format_currency($p->amount, 'default_currency')?></td>


                        <td><?php echo App::get_method_by_id($p->payment_method); ?>
                        </td>


                    </tr>


                    <?php } ?>

                    

                </tbody>
            </table>
        </div>
    </div>
<?= $this->endSection() ?>