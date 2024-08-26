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
use App\Modules\invoices\controllers\Invoices;
use App\Models\Item;
use App\Models\Plugin;
use App\Models\User;

use App\Helpers\custom_name_helper;

$custom_name_helper = new custom_name_helper();

$total = 0;
$tax_total = 0;

?>
<?php //$client = User::is_client();?>
<?= $this->extend('layouts/users') ?>

<?= $this->section('content') ?>
<div class="box custom-invoice-view">

    <div class="box-header clearfix hidden-print">
        <?php $inv = Invoice::view_by_id($id); ?>	
        <?php $l = Client::view_by_id($inv->client); ?>
        <?php $client_cur = Client::view_by_id($inv->client); ?>
        <?php $use_modal = array('bitcoin', 'paypal', 'coinpayments', 'checkout'); ?>
        <?php $colors = array('btn-google', 'btn-twitter', 'btn-default', 'btn-primary', 
                    'btn-google', 'btn-twitter', 'btn-linkedin', 'btn-warning', 'btn-foursquare', 
                    'btn-dropbox','btn-google', 'btn-twitter', 'btn-default', 'btn-primary', 'btn-google', 
                    'btn-twitter', 'btn-linkedin', 'btn-warning', 'btn-foursquare', 'btn-dropbox'); 
                    ?>
        <div class="row">
            <div class="col-11 ms-auto">
                <div class="btn-group gap-3 invoice-btn-group pull-right">
                        <div class='grouper'>
                        <button
                 class="btn btn-sm common-button btn-<?=$custom_name_helper->getconfig_item('theme_color')?>  btn-responsive dropdown-toggle"
                            data-toggle="dropdown"><?= lang('hd_lang.options') ?> <span class="caret"></span>
                        </button>

                        <ul class="dropdown-menu">

                            <?php if(User::is_admin() || User::perm_allowed(User::get_id(),'email_invoices')): ?>

                            <li>
                                <a href="<?=base_url()?>invoices/send_invoice/<?=$inv->inv_id?>" class='common-p text-primary p-3'
                                    data-toggle="ajaxModal" title="<?= lang('hd_lang.email_invoice') ?>"><i
                                        class="fa fa-paper-plane-o"></i>
                                    <?=lang('hd_lang.email_invoice')?>
                                </a>
                            </li>
                            <?php endif; ?>


                            <?php if(User::is_admin() || User::perm_allowed(User::get_id(),'send_email_reminders')) : ?>

                            <li>
                                <a href="<?=base_url()?>invoices/remind/<?=$inv->inv_id?>" class='common-p text-primary p-3'
                                    data-toggle="ajaxModal" title="<?=lang('hd_lang.send_reminder')?>"><i
                                        class="fa fa-envelope-o"></i>
                                    <?= lang('hd_lang.send_reminder') ?>
                                </a>
                            </li>
                            <?php endif; ?>


                            <li>
                                <a href="<?= base_url() ?>invoices/transactions/<?= $inv->inv_id ?>" class='common-p text-primary p-3' >
                                    <i class="fa fa-credit-card"></i> <?= lang('hd_lang.payments') ?>
                                </a>
                            </li>

                            <?php if(User::is_admin() || Invoice::get_invoice_due_amount($inv->inv_id) > 0) : ?>

                            <li>
                                <a href="<?=base_url() ?>invoices/mark_as_paid/<?=$inv->inv_id?>" class='common-p text-primary p-3'
                                    data-toggle="ajaxModal">
                                    <i class="fa fa-money"></i> <?=lang('hd_lang.mark_as_paid') ?>
                                </a>
                            </li>

                            <li>
                                <a href="<?=base_url() ?>invoices/cancel/<?=$inv->inv_id?>" class='common-p text-primary p-3'
                                    data-toggle="ajaxModal">
                                    <i class="fa fa-ban"></i> <?=lang('hd_lang.cancelled') ?>
                                </a>
                            </li>
							
							<li>
                                <a href="<?=base_url() ?>invoices/payment_status/<?=$inv->inv_id?>" class='common-p text-primary p-3'
                                    data-toggle="ajaxModal">
                                    <i class="fa fa-credit-card"></i> <?=lang('hd_lang.payment_status') ?>
                                </a>
                            </li>

                            <?php endif; ?>

                            <?php if ($inv->recurring == 'Yes') { ?>
                            <?php if(User::is_admin() || User::perm_allowed(User::get_id(),'edit_all_invoices')){ ?>
                            <li>
                                <a href="<?= base_url() ?>invoices/stop_recur/<?=$inv->inv_id?>" class='common-p text-primary p-3'
                                    title="<?= lang('hd_lang.stop_recurring') ?>" data-toggle="ajaxModal">
                                    <i class="fa fa-retweet"></i> <?= lang('hd_lang.stop_recurring') ?>
                                </a>
                            </li>
                            <?php }  
                            } ?>

                            <?php if (User::is_admin() || User::perm_allowed(User::get_id(),'edit_all_invoices')) { ?>
                            <li>
                                <a href="<?= base_url() ?>invoices/edit/<?=$inv->inv_id?>" class='common-p text-primary p-3'
                                    data-original-title="<?=lang('hd_lang.edit_invoice')?>" 
                                    ><i class="fa fa-pencil"></i> <?=lang('hd_lang.edit')?>
                                </a>
                            </li>

                            <li>
                                <a href="<?= base_url() ?>invoices/items/insert/<?=$inv->inv_id ?>" class='common-p text-primary p-3'
                                    title="<?= lang('hd_lang.item_quick_add') ?>" data-toggle="ajaxModal">
                                    <i class="fa fa-plus"></i> <?= lang('hd_lang.add_item') ?>
                                </a>
                            </li>

                            <li>
                                <?php } if (User::is_admin() || User::perm_allowed(User::get_id(),'delete_invoices')) { ?>

                                <a href="<?= base_url() ?>invoices/delete/<?=$inv->inv_id?>" class='common-p text-primary p-3'
                                    title="<?=lang('hd_lang.delete_invoice')?>" data-toggle="ajaxModal"><i
                                        class="fa fa-trash"></i> <?=lang('hd_lang.delete')?>
                                </a>
                            </li>

                            <?php }  if (User::is_admin() || User::perm_allowed(User::get_id(),'edit_all_invoices')) : ?>

                            <?php if ($inv->show_client == 'Yes') { ?>
                            <li>
                                <a href="<?= base_url() ?>invoices/hide/<?=$inv->inv_id ?>" class='common-p text-primary p-3'> <i
                                        class="fa fa-eye-slash"></i> <?=lang('hd_lang.hide_to_client') ?></a>
                            </li>

                            <?php } else { ?>
                            <li>
                                <a href="<?= base_url() ?>invoices/show/<?= $inv->inv_id ?>" class='common-p text-primary p-3'
                                     data-original-title="<?= lang('hd_lang.show_to_client') ?>"
                                    >
                                    <i class="fa fa-eye"></i> <?= lang('hd_lang.show_to_client') ?>
                                </a>
                            </li>
                            <?php } ?>

                            <?php endif; ?>


                        </ul>
                        </div>
                    

                    <?php if (Invoice::get_invoice_due_amount($inv->inv_id) > 0) : ?>

                    <?php if (User::is_admin() || User::perm_allowed(User::get_id(),'pay_invoice_offline')
            				&& (Invoice::get_invoice_due_amount($inv->inv_id) > 0) ) {
                
                if(User::is_admin()) {
                ?>

                    <a class="btn btn-sm btn-success btn-responsive common-button"
                        href="<?=base_url()?>invoices/pay/<?=$inv->inv_id?>" 
                        data-original-title="<?=lang('hd_lang.pay_invoice')?>" >
                        <i class="fa fa-money"></i> <?= lang('hd_lang.add_payment') ?>
                    </a>

                    <?php } }

					if(User::is_client()) {
						$credit = Client::view_by_id($inv->client)->transaction_value;
						if($credit > 0)
						{ ?>
								<a class="btn btn-sm btn-success btn-responsive common-button"
									href="<?= base_url() ?>invoices/apply_credit/<?= $inv->inv_id ?>"
									data-toggle="ajaxModal">
									<?=lang('hd_lang.credit_balance') . ' (' .AppLib::format_currency( Applib::client_currency($client_cur, $credit), $client_cur) . ') ' . lang('hd_lang.pay')?>
								</a>
								<?php }
					}


								foreach(Plugin::payment_gateways() as $k => $gateway) 
								{ ?>
								<a class="btn btn-sm btn-primary btn-responsive common-button"
									<?php if(in_array($gateway->system_name, $use_modal)) {echo 'data-toggle="ajaxModal"';} ?>
									href="<?= base_url() . '' . $gateway->system_name ?>/pay/<?= $inv->inv_id ?>">
									<?= $gateway->name ?>
								</a>
								<?php } endif; ?>

								<?php if ($custom_name_helper->getconfig_item('pdf_engine') == 'invoicr') : ?>
								<a href="<?= base_url() ?>invoices/pdf/<?= $inv->inv_id ?>"
						class="btn btn-sm common-button btn-<?=$custom_name_helper->getconfig_item('theme_color')?> btn-responsive">
									<i class="fa fa-file-pdf-o"></i> <?=lang('hd_lang.pdf') ?>
								</a>
							</div>
							<?php endif; ?>
						</div>
					</div>
				</div>

				<?php
				$session = session();
				$serializedSuccessResponse = $session->getFlashdata('successResponse');
				if ($serializedSuccessResponse) {
					$successResponse = unserialize($serializedSuccessResponse);
					echo '<div class="alert alert-success"> This domain is already register </div>';
				}
				?>

		<div class="box-body ie-details">
			<?php if(Invoice::payment_status($inv->inv_id) == 'fully_paid'){ ?>
			<div id="ember2686" disabled="false" class="ribbon ember-view popovercontainer" data-original-title="" title="">
				<div class="ribbon-inner ribbon-success">
					<span class="cr-inner">
						<span class="cr-text">
							<?=lang('hd_lang.paid')?>
						</span>
					</span>
				</div>
			</div>
			<?php } ?>

        <?php if(Invoice::payment_status($inv->inv_id) != 'fully_paid' && $inv->status != 'Cancelled'){ ?>
        <div id="ember2686" disabled="false" class="ribbon ember-view popovercontainer" data-original-title="" title="">
            <div class="ribbon-inner ribbon-danger">
                <span class="cr-inner">
                    <span class="cr-text">
                        
                            <?=lang('hd_lang.unpaid')?>
                        
                    </span>
            </div>
        </div>
        <?php } ?>

        <!-- Start Display Details -->
        <?php if($inv->status != 'Cancelled') : ?>
        <?php
                        if (!session()->getFlashdata('message')) :
                            if (strtotime($inv->due_date) < time() && Invoice::get_invoice_due_amount($inv->inv_id) > 0) :
                                ?>
        <div class="alert alert-warning hidden-print">
            <button type="button" class="close" data-dismiss="alert">×</button> <i class="fa fa-warning"></i>
            <?= lang('hd_lang.invoice_overdue') ?>
        </div>
        <?php endif; ?>
        <?php endif; ?>
        <?php else: ?>
        <div class="alert alert-danger hidden-print">
            <button type="button" class="close" data-dismiss="alert">×</button> <i class="fa fa-warning"></i>
            This Invoice is Cancelled!
        </div>

        <?php endif; ?>


        <div class="invTopWrap">
            <div class="row">
                <div class="col-md-3">
                    <div class="logoWrpps">
                        <img class="ie-logo img-responsive"
                            src="https://www.madpopo.com/image/header/madpopo-dark-logo.svg" alt="logo">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="leftDataWrap">
                        <?=lang('hd_lang.reference')?>
                        <span class="">
                            <strong>
                                <?=$inv->reference_no?>
                            </strong>
                        </span>
                    </div>


                    <div class="leftDataWrap">
                        <?=lang('hd_lang.invoice_date')?>
                        <span class="">
                            <strong>
                                <?=strftime($custom_name_helper->getconfig_item('date_format'), strtotime($inv->date_saved)); ?>
                            </strong>
                        </span>
                    </div>

                    <?php if ($inv->recurring == 'Yes') { ?>
                    <div class="leftDataWrap">
                        <?= lang('hd_lang.recur_next_date') ?>
                        <span class="">
                            <strong>
                                <?=strftime($custom_name_helper->getconfig_item('date_format'), strtotime($inv->recur_next_date)); ?>
                            </strong>
                        </span>
                    </div>
                    <?php } ?>

                    <div class="leftDataWrap">
                        <?= lang('hd_lang.payment_due') ?>
                        <span class="">
                            <strong>
                                <?=strftime($custom_name_helper->getconfig_item('date_format'), strtotime($inv->due_date)); ?>
                            </strong>
                        </span>
                    </div>
					
					<div class="leftDataWrap">
                        <?= lang('hd_lang.payment_status') ?>
                        
                            <span class="label bg-success statusBar common-p text-white">
                                <?=lang('hd_lang.'.Invoice::payment_status($inv->inv_id))?>
                            </span>
                        
                    </div>
                </div>
            </div>
        </div>

        <div class="mainInvDataWrap">
            <div class="row">

                <div class="col-md-5">
                    <div class="invLeftMainBox">
                        <h4 class="titleName">
                            <?=($custom_name_helper->getconfig_item('company_legal_name_' . $l->company_name)
								? $custom_name_helper->getconfig_item('company_legal_name_' . $l->company_name)
								: $custom_name_helper->getconfig_item('company_legal_name'))
							?>
                        </h4>
                        <p class="invAdd">
                            <span>
                                <?=($custom_name_helper->getconfig_item('company_address_1' . $l->company_address)
                                    ? $custom_name_helper->getconfig_item('company_address_1' . $l->company_address)
                                    : $custom_name_helper->getconfig_item('company_address'))
                                ?><br>

                                

                                <?=($custom_name_helper->getconfig_item('company_city_' . $l->city)
                                        ? $custom_name_helper->getconfig_item('company_city_' . $l->city)
                                        : $custom_name_helper->getconfig_item('company_city'))
                                    ?>

                                <?php if ($custom_name_helper->getconfig_item('company_zip_code_' . $l->zip) != '' || $custom_name_helper->getconfig_item('company_zip_code') != '') : ?>
                                , <?=($custom_name_helper->getconfig_item('company_zip_code_' . $l->zip)
                                            ? $custom_name_helper->getconfig_item('company_zip_code_' . $l->zip)
                                            : $custom_name_helper->getconfig_item('company_zip_code'))
                                        ?>
                                <?php endif; ?><br>

                                <?php if ($custom_name_helper->getconfig_item('company_state_' . $l->state) != '' || $custom_name_helper->getconfig_item('company_state') != '') : ?>

                                <?=($custom_name_helper->getconfig_item('company_state_' . $l->state)
                                            ? $custom_name_helper->getconfig_item('company_state_' . $l->state)
                                            : $custom_name_helper->getconfig_item('company_state')) ?>,
                                <?php endif; ?>

                                <?=($custom_name_helper->getconfig_item('company_country_' . $l->country)
                                        ? $custom_name_helper->getconfig_item('company_country_' . $l->country)
                                        : $custom_name_helper->getconfig_item('company_country')) ?>
                            </span>
                            <br>

                            <span>
                                <?= lang('hd_lang.phone') ?>:
                                <a href="tel:<?= ($custom_name_helper->getconfig_item('company_phone_' . $l->company_phone)
                                    ? $custom_name_helper->getconfig_item('company_phone_' . $l->company_phone)
                                    : $custom_name_helper->getconfig_item('company_phone')) ?>">

                                    <?=($custom_name_helper->getconfig_item('company_phone_' . $l->company_phone)
                                        ? $custom_name_helper->getconfig_item('company_phone_' . $l->company_phone)
                                        : $custom_name_helper->getconfig_item('company_phone')) ?>
                                </a>

                                <?php if ($custom_name_helper->getconfig_item('company_phone_2_'.$l->company_mobile) != '' || $custom_name_helper->getconfig_item('company_phone_2') != '') : ?>
                                , <a href="tel:<?= ($custom_name_helper->getconfig_item('company_phone_2_' . $l->company_mobile)
                                            ? $custom_name_helper->getconfig_item('company_phone_2_' . $l->company_mobile)
                                            : $custom_name_helper->getconfig_item('company_phone_2')) ?>">

                                    <?=($custom_name_helper->getconfig_item('company_phone_2_' . $l->company_mobile)
                                                ? $custom_name_helper->getconfig_item('company_phone_2_' . $l->company_mobile)
                                                : $custom_name_helper->getconfig_item('company_phone_2')) ?>
                                </a><br>
                                <?php endif; ?>
                            </span>


                            <?php if ($custom_name_helper->getconfig_item('company_fax_'.$l->company_fax) != '' || $custom_name_helper->getconfig_item('company_fax') != '') : ?>
                            <span><?= lang('hd_lang.fax') ?>:
                                <a
                                    href="tel:<?= ($custom_name_helper->getconfig_item('company_fax_' . $l->company_fax) ? $custom_name_helper->getconfig_item('company_fax_' . $l) : $custom_name_helper->getconfig_item('company_fax')) ?>">
                                    <?=($custom_name_helper->getconfig_item('company_fax_' . $l->company_fax)
                                        ? $custom_name_helper->getconfig_item('company_fax_' . $l->company_fax)
                                        : $custom_name_helper->getconfig_item('company_fax')) ?>
                                </a>
                            </span><br>
                            <?php endif; ?>
                            <?php if ($custom_name_helper->getconfig_item('company_registration_'.$l->company_ref) != '' || $custom_name_helper->getconfig_item('company_registration') != '') : ?>

                            <span>
                                <?= lang('hd_lang.company_registration') ?>:

                                <a
                                    href="tel:<?= ($custom_name_helper->getconfig_item('company_registration_' . $l->company_ref) ? $custom_name_helper->getconfig_item('company_registration_' . $l) : $custom_name_helper->getconfig_item('company_registration')) ?>">
                                    <?=($custom_name_helper->getconfig_item('company_registration_' . $l->company_ref)
                                        ? $custom_name_helper->getconfig_item('company_registration_' . $l->company_ref)
                                        : $custom_name_helper->getconfig_item('company_registration')) ?>
                                </a>
                            </span><br>
                            <?php endif; ?>

                            <?php if ($custom_name_helper->getconfig_item('company_vat_'.$l->VAT) != '' || $custom_name_helper->getconfig_item('company_vat') != '') : ?>

                            <span>
                                <?=lang('hd_lang.company_vat')?>:
                                <?=($custom_name_helper->getconfig_item('company_vat_' . $l->VAT)
                                ? $custom_name_helper->getconfig_item('company_vat_' . $l->VAT)
                                : $custom_name_helper->getconfig_item('company_vat')) ?><br>
                                <span>

                                    <?php endif; ?>
                        </p>
                    </div>
                </div>

                <div class="col-md-5" id="invoice_client">
                    <h4 class="titleName"><?=Client::view_by_id($inv->client)->company_name;?></h4>
                    <p class="invAdd">
                        <span>
                            <?=Client::view_by_id($inv->client)->first_name?>
                            <?=Client::view_by_id($inv->client)->last_name;?><br>
                            <?php if (Client::view_by_id($inv->client) != '') {
                                                   echo " ".Client::view_by_id($inv->client)->company_address;  } ?><br>

                            <?php if (Client::view_by_id($inv->client) != '') {
                                                    echo Client::view_by_id($inv->client)->company_address_two." ";  } ?>
                            <?=Client::view_by_id($inv->client)->company_email; ?>
                        </span>
                        <br>
                        <span>
                            <?=lang('hd_lang.phone')?>:
                            <a href="tel:<?=Client::view_by_id($inv->client)->company_phone;?>">
                                <?=ucfirst(Client::view_by_id($inv->client)->company_phone) ?>
                            </a>&nbsp;
                        </span>
                        <br>

                        <?php if (Client::view_by_id($inv->client) != '') : ?>
                        <span>
                            <?=lang('hd_lang.fax')?>:
                            <a href="tel:<?=Client::view_by_id($inv->client)->company_fax;?>">
                                <?=ucfirst(Client::view_by_id($inv->client)->company_fax) ?>
                            </a>&nbsp;
                        </span><br>
                        <?php endif; ?>

                        <?php if(Client::view_by_id($inv->client)->VAT != ''){ ?>
                        <span>
                            <?=lang('hd_lang.company_vat')?>:
                            <?=Client::view_by_id($inv->client)->VAT;?>
                        </span>
                        <?php } ?>
                    </p>
                </div>

            </div>
        </div>
        <?php $showtax = $custom_name_helper->getconfig_item('show_invoice_tax') == 'TRUE' ? TRUE : FALSE; ?>
        <div class="line"></div>
        <div class="table-responsive">
        <table id="inv-details" class="hs-table" type="invoices">

            <tbody>
                <tr>
                    <th></th>
                    <?php if ($showtax) : ?>
                    <th width="20%"><?= lang('hd_lang.item_name') ?> </th>
                    <th width="25%"><?= lang('hd_lang.description') ?> </th>
                    <th width="7%" class="text-right"><?= lang('hd_lang.qty') ?> </th>
                    <th width="10%" class="text-right"><?= lang('hd_lang.tax_rate') ?> </th>
                    <th width="12%" class="text-right"><?= lang('hd_lang.unit_price') ?> </th>
                    <th width="12%" class="text-right"><?= lang('hd_lang.tax') ?> </th>
                    <th width="12%" class="text-right"><?= lang('hd_lang.total') ?> </th>
                    <?php else : ?>
                    <th width="25%"><?= lang('hd_lang.item_name') ?> </th>
                    <th width="35%"><?= lang('hd_lang.description') ?> </th>
                    <th width="7%" class="text-right"><?= lang('hd_lang.qty') ?> </th>
                    <th width="12%" class="text-right"><?= lang('hd_lang.unit_price') ?> </th>
                    <th width="12%" class="text-right"><?= lang('hd_lang.total') ?> </th>
                    <?php endif; ?>
                    <th class="text-right inv-actions"></th>
                </tr>
                <?php foreach (Invoice::has_items($inv->inv_id) as $key => $item) { 
	
				$totall_cost = !User::is_admin() &&!User::is_staff()? Applib::client_currency($client_cur->currency, 
								$item->total_cost) : $item->total_cost;
	
				$taxx_total = !User::is_admin() &&!User::is_staff()? Applib::client_currency($client_cur->currency, 
								$item->item_tax_total) : $item->item_tax_total;
	
	
				$total += $totall_cost; $tax_total += $taxx_total; 
				?>
                <tr class="sortable" data-name="<?=$item->item_name?>" data-id="<?=$item->item_id?>">
                    <td class="drag-handle"><i class="fa fa-reorder"></i></td>
                    <td>

                        <?php if (User::perm_allowed(User::get_id(),'edit_all_invoices')) { ?>
                        <a class="text-info" href="<?=base_url()?>invoices/items/edit/<?=$item->item_id?>"
                            data-toggle="ajaxModal"><?=$item->item_name?>
                        </a>
                        <?php } else { ?>
                        <?=$item->item_name?>
                        <?php } ?>
						</td>
						<td class="text-muted"><?=nl2br($item->item_desc)?></td>

						<td class="text-right"><?=Applib::format_quantity($item->quantity);?></td>
						<?php if ($showtax) :?>
						<td class="text-right" id="tax-rate-display"><?=Applib::format_tax($item->item_tax_rate)?>%</td>
						<?php endif;?>
						<td class="text-right">
							<?php $unit_cost =!User::is_admin() &&!User::is_staff()? Applib::client_currency($client_cur->currency, 
								$item->unit_cost) : $item->unit_cost; 
																				   
								echo !User::is_admin() && !User::is_staff() 
  									? Applib::format_currency_client($unit_cost, $client_cur->currency) 
    								: Applib::format_currency($unit_cost, 'default_currency');
										
							?>
						</td>
						<?php if ($showtax) :?>
						<td class="text-right" id="tax-total-display">
						<?php //$tax_rate = $inv->tax / 100; // Convert tax rate to decimal
							//$item_tax = $unit_cost * $tax_rate; // Calculate tax
							//if (!User::is_admin() &&!User::is_staff()) {
							//$item_tax = Applib::client_currency($client_cur->currency, $item_tax);
							//}
							// echo $item->item_tax_total; 
	
							$item_tax_total =!User::is_admin() &&!User::is_staff()? Applib::client_currency($client_cur->currency, 
								$item->item_tax_total) : $item->item_tax_total;
	
								echo !User::is_admin() && !User::is_staff() 
  									? Applib::format_currency_client($item_tax_total, $client_cur->currency) 
    								: Applib::format_currency($item_tax_total, 'default_currency');
							
							
							?>
					</td>
					<?php endif;?>
                    <td class="text-right">
                        <?php 
						if(!User::is_admin() && !User::is_staff()) {
                        //echo Applib::format_currency($client_cur, Applib::client_currency($client_cur, $item->total_cost));
							$total_cost = Applib::client_currency($client_cur->currency, $item->total_cost);
                       echo Applib::format_currency_client($total_cost, $client_cur->currency);
                         }else{ //echo Applib::format_currency($inv->currency, $item->total_cost);
                          echo Applib::format_currency($item->total_cost, 'default_currency');
                          } 
                          ?>
                    </td>

                    <td>
                        <?php if (User::perm_allowed(User::get_id(),'edit_all_invoices')) { ?>
						<?php if (User::is_admin() && $inv->status != 'Paid') { ?>
                        <a class="hidden-print"
                            href="<?= base_url() ?>invoices/items/delete/<?=$item->item_id?>/<?=$item->invoice_id ?>"
                            data-toggle="ajaxModal"><i class="fa fa-trash-o text-danger"></i>
                        </a>
                        <?php } }?>
                    </td>
                </tr>
                <?php } ?>
                <?php if (User::perm_allowed(User::get_id(),'edit_all_invoices')) { ?>

                <?php if (User::is_admin() && $inv->status != 'Paid') { ?>
                <tr class="hidden-print">
                    <?php $attributes = array('class' => 'bs-example form-horizontal');
					echo form_open(base_url() . 'invoices/items/add', $attributes);
					?>
                    <input class='common-input' type="hidden" name="invoice_id" value="<?=$inv->inv_id ?>">
                    <input class='common-input' type="hidden" name="item_order" value="<?=count(Invoice::has_items($inv->inv_id)) + 1?>">
                    <input class='common-input' id="hidden-item-name" type="hidden" name="item_name">
                    <td></td>
                    <td><input id="auto-item-name" data-scope="invoices" type="text"
                            placeholder="<?=lang('hd_lang.item_name') ?>" class="typeahead form-control common-input"></td>

                    <td><textarea id="auto-item-desc" rows="1" name="item_desc"
                            placeholder="<?= lang('hd_lang.item_description') ?>"
                            class="form-control common-input js-auto-size"></textarea>
                    </td>

                    <td><input id="auto-quantity" type="text" name="quantity" value="1" class="form-control common-input"></td>
                    <?php if ($showtax) : ?>
                    <td>
						<strong><?=$custom_name_helper->getconfig_item('tax_name')?>
								(<?=Applib::format_tax($inv->tax)?>%)</strong>
					</td>
                    <?php endif; ?>
                    <td><input id="auto-unit-cost" type="text" name="unit_cost" required placeholder="50.56"
                            class="form-control common-input"></td>
                    <?php if ($showtax) : ?>
                    <td><input type="text" name="tax" placeholder="0.00" readonly="" class="form-control common-input"></td>
                    <?php endif; ?>
                    <td></td>
                    <td><button type="submit" class="btn btn-<?=$custom_name_helper->getconfig_item('theme_color')?> common-button"><i
                                class="fa fa-check"></i> <?= lang('hd_lang.save') ?></button></td>
                    <?php echo form_close(); ?>
                </tr>
                <?php } ?>
                <?php } ?>
                <tr>
                    <td colspan="<?= $showtax ? '7' : '5' ?>" class="text-right no-border">
                        <strong><?= lang('hd_lang.sub_total') ?></strong>
                    </td>
                    <td class="text-right">
						<?php if(!User::is_admin() && !User::is_staff()) {
						echo Applib::format_currency_client($total, $client_cur->currency);
						}
						else{
							echo Applib::format_currency($total, 'default_currency');
						} 
						?>
                    </td>

                    <td></td>
                </tr>
                <?php if ($inv->tax > 0.00): ?>
                <tr>
                    <td colspan="<?= $showtax ? '7' : '5' ?>" class="text-right no-border">
						<strong><?=$custom_name_helper->getconfig_item('tax_name')?>
							(<?=Applib::format_tax($inv->tax)?>%)</strong>
					</td>
                    <td class="text-right" id="invoice-tax-display">
						<?php if(!User::is_admin() && !User::is_staff()) {
						// echo Applib::format_currency((($total) * $inv->tax) / 100,'default_currency');
						echo Applib::format_currency_client((($total) * $inv->tax) / 100, $client_cur->currency);
						} else {
							//echo Applib::format_currency(Invoice::get_invoice_tax($inv->inv_id), 'default_currency');
						echo Applib::format_currency((($total) * $inv->tax) / 100,'default_currency');
						} ?>
					</td>

                    <td></td>

                </tr>
                <?php endif ?>

                <?php if ($inv->tax2 > 0.00): ?>
                <tr>
                    <td colspan="<?= $showtax ? '7' : '5' ?>" class="text-right no-border">
                        <strong><?= lang('hd_lang.tax') ?> 2 (<?=Applib::format_tax($inv->tax2)?>%)</strong>
                    </td>
                    <td class="text-right">
                        <?php if(!User::is_admin() && !User::is_staff()) {      
                        echo Applib::format_currency(Applib::client_currency($client_cur->currency, Invoice::get_invoice_tax($inv->inv_id,'tax2')), 'default_currency');
                         }else{ echo Applib::format_currency(Invoice::get_invoice_tax($inv->inv_id,'tax2'), 'default_currency');
                          } ?>
                    </td>

                    <td></td>

                </tr>
                <?php endif ?>
			
			<tr>
                    <td colspan="<?= $showtax ? '7' : '5' ?>" class="text-right no-border">
                        <strong><?= lang('hd_lang.product_tax') ?> (<?=Applib::format_tax($item->item_tax_rate)?>%)</strong>
                    </td>
                    <td class="text-right">
                        <?php if(!User::is_admin() && !User::is_staff()) {
								// echo Applib::format_currency($tax_total, 'default_currency');
								echo Applib::format_currency_client($tax_total, $client_cur->currency);
                         }else{
								echo Applib::format_currency($tax_total, 'default_currency');
                          } ?>
                    </td>

                    <td></td>

                </tr>

                <?php if ($inv->discount > 0) { ?>
                <tr>
                    <td colspan="<?= $showtax ? '7' : '5' ?>" class="text-right no-border">
                        <strong><?= lang('hd_lang.discount') ?>-<?php echo Applib::format_tax($inv->discount_percentage); ?>%</strong>
                    </td>
                    <td class="text-right">
                    <?php if(!User::is_admin() && !User::is_staff()) {   
						// echo Applib::format_currency((($total) * $inv->discount_percentage) / 100,'default_currency');
						echo Applib::format_currency_client((($total) * $inv->discount_percentage) / 100, $client_cur->currency);
					}
					else{ 
						echo Applib::format_currency((($total) * $inv->discount_percentage) / 100,'default_currency');
					} 
					?> 
                    </td>

                    <td></td>

                </tr>
                <?php } ?>

                <?php if ($inv->extra_fee > 0) { ?>
                <tr>
                    <td colspan="<?= $showtax ? '7' : '5' ?>" class="text-right no-border">
                        <strong><?= lang('hd_lang.extra_fee') ?> - <?php echo $inv->extra_fee; ?>%</strong>
                    </td>
                    <td class="text-right">
                        <?php if(!User::is_admin() && !User::is_staff()) {                                         
                          //echo Applib::format_currency((($total) * $inv->extra_fee) / 100,'default_currency'); 
							echo Applib::format_currency_client((($total) * $inv->extra_fee) / 100, $client_cur->currency);
							}
                          else
						  {
						  echo Applib::format_currency((($total) * $inv->extra_fee) / 100,'default_currency');
                          } ?>
                    </td>

                    <td></td>

                </tr>
                <?php } ?>
			
			<?php $disc = ($total) * $inv->discount_percentage / 100; ?>
			
			<?php $extra_fee = ($total) * $inv->extra_fee / 100; ?>
			
			 <tr>
                    <td colspan="<?= $showtax ? '7' : '5' ?>" class="text-right no-border">
                        <strong><?= lang('hd_lang.total_amount') ?></strong>
                    </td>
                    <td class="text-right text-success">
                        <?php if(!User::is_admin() && !User::is_staff()) {
							$total_cost = ($total + $tax_total + $extra_fee) - $disc;
							//echo Applib::format_currency($total_cost, 'default_currency');
							echo Applib::format_currency_client($total_cost, $client_cur->currency);
                         }else{
							$total_cost = ($total + $tax_total + $extra_fee);
							$aft_disc = $total_cost - $disc;
							echo Applib::format_currency($aft_disc, 'default_currency');
						} ?>
                    </td>
                    <td></td>
                </tr>

                <?php if (Invoice::get_invoice_paid($inv->inv_id) > 0) { ?>
                <tr>
                    <td colspan="<?= $showtax ? '7' : '5' ?>" class="text-right no-border">
                        <strong><?= lang('hd_lang.payment_made') ?></strong>
                    </td>
                    <td class="text-right text-danger">
                        (-)
                        <?php if(!User::is_admin() && !User::is_staff()) {                                         
    $payment_made = Applib::client_currency($client_cur->currency, Invoice::get_invoice_paid($inv->inv_id));
							echo Applib::format_currency_client($payment_made, $client_cur->currency);
                         }else{
							echo Applib::format_currency(Invoice::get_invoice_paid($inv->inv_id), 'default_currency'); } ?>
                    </td>
                    <td></td>
                </tr>
                <?php } ?>


                <tr>
                    <td colspan="<?= $showtax ? '7' : '5' ?>" class="text-right no-border"><strong>
                            <?=lang('hd_lang.due_amount')?></strong></td>
                    <td class="text-right" id="invoice-due-amount-display">
						<?php 
	
	
	if(!User::is_admin() && !User::is_staff()) {
							$due_amt = Applib::client_currency($client_cur->currency, Invoice::get_invoice_due_amount($inv->inv_id));
		
							echo Applib::format_currency_client($due_amt + $tax_total, $client_cur->currency);
						} else {
							echo Applib::format_currency(Invoice::get_invoice_due_amount($inv->inv_id) + $tax_total, 'default_currency');
						} ?>
					</td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        </div>

    </div>
    <br>
    <br>
</div>

<?= $this->endSection() ?>