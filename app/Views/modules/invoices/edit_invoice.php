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


<div class="box">
    <?= $this->extend('layouts/users') ?>

    <?= $this->section('content') ?>
    <div class="box-header custom-invoice-edit-header p-3">
        <?php $i = Invoice::view_by_id($id); ?> <?php $inv = Invoice::view_by_id($id); ?>
		<?php $showtax = $custom->getconfig_item('show_invoice_tax') == 'TRUE' ? TRUE : FALSE; ?>
        <strong class='common-h'><i class="fa fa-info-circle"></i> <?=lang('hd_lang.invoice_details')?> - <?=$i->reference_no?></strong>
        <a href="<?=base_url()?>invoices/view/<?=$i->inv_id?>"
            data-original-title="<?=lang('hd_lang.view_items')?>" data-toggle="tooltip" data-placement="bottom"
            class="btn btn-<?=$custom->getconfig_item('theme_color');?> btn-sm pull-right common-button"><i
                class="fa fa-info-circle"></i>
            <?=lang('hd_lang.invoice_items')?></a>
    </div>

    <div class="box-body p-3 custom-invoice-edit">
        <?php
                                $attributes = array('class' => 'bs-example form-horizontal');
                                echo form_open(base_url().'invoices/edit',$attributes); ?>
        <input type="hidden" name="inv_id" value="<?=$i->inv_id?>">

        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-3 row first-row">
                    <label class="col-lg-3 control-label common-label"><?=lang('hd_lang.reference_no')?> <span
                            class="text-danger">*</span></label>
                    <div class="col-lg-8 row custom-invoice-edit-row">
                        <input type="text" class="form-control common-input m-0 custom-60" value="<?=$i->reference_no?>" name="reference_no" >
                        <a href="#recurring" class="btn btn-xs custom-40 btn-<?=$custom->getconfig_item('theme_color');?> common-button"
                        data-toggle="class:show"><?=lang('hd_lang.recurring')?></a>
                    </div>
                    
                </div>
                <!-- Start discount fields -->
                <div id="recurring" class="hide" style="display:none;background-color:white !important;">
                    <div class="form-group mb-3 row">
                        <label class="col-lg-3 control-label common-label"><?=lang('hd_lang.recur_frequency')?> </label>
                        <div class="col-lg-8">
                            <select name="r_freq" class="form-control common-select">
                                <option value="none"><?=lang('hd_lang.none')?></option>
                                <option value="7D" <?=($i->recur_frequency == "7D" ? ' selected="selected"' : '')?>>
                                    <?=lang('hd_lang.week')?></option>
                                <option value="1M" <?=($i->recur_frequency == "1M" ? ' selected="selected"' : '')?>>
                                    <?=lang('hd_lang.month')?></option>
                                <option value="3M" <?=($i->recur_frequency == "3M" ? ' selected="selected"' : '')?>>
                                    <?=lang('hd_lang.quarter')?></option>
                                <option value="6M" <?=($i->recur_frequency == "6M" ? ' selected="selected"' : '')?>>
                                    <?=lang('hd_lang.six_months')?></option>
                                <option value="1Y" <?=($i->recur_frequency == "1Y" ? ' selected="selected"' : '')?>>
                                    <?=lang('hd_lang.year')?></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group mb-3 row">
                        <label class="col-lg-3 control-label common-label"><?=lang('hd_lang.start_date')?></label>
                        <div class="col-lg-8">
                            <?php if ($i->recurring == 'Yes') {
                                                        $recur_start_date = date('d-m-Y',strtotime($i->recur_start_date));
                                                        $recur_end_date = date('d-m-Y',strtotime($i->recur_end_date));
                                                    }else{
                                                        $recur_start_date = date('d-m-Y');
                                                        $recur_end_date = date('d-m-Y');
                                                    }
                                                    ?>
                            <input class="input-sm input-s datepicker-input form-control common-input m-0" size="16" type="text"
                                value="<?=strftime($custom->getconfig_item('date_format'), strtotime($recur_start_date));?>"
                                name="recur_start_date"
                                data-date-format="<?=$custom->getconfig_item('date_picker_format');?>">
                        </div>
                    </div>
                    <div class="form-group mb-3 row">
                        <label class="col-lg-3 control-label common-label"><?=lang('hd_lang.end_date')?></label>
                        <div class="col-lg-8">
                            <input class="input-sm input-s datepicker-input form-control common-input m-0" size="16" type="text"
                                value="<?=strftime($custom->getconfig_item('date_format'), strtotime($recur_end_date));?>"
                                name="recur_end_date"
                                data-date-format="<?=$custom->getconfig_item('date_picker_format');?>">
                        </div>
                    </div>
                </div>
                <!-- End discount Fields -->
                <div class="form-group mb-3 row">
                    <label class="col-lg-3 control-label common-label"><?=lang('hd_lang.client')?> <span class="text-danger">*</span>
                    </label>
                    <div class="col-lg-8">
                        <select class="select2-option w_280 common-select" name="client">
                            <optgroup label="<?=lang('hd_lang.clients')?>">
                                <option value="<?=$i->client?>">
                                    <?=ucfirst(Client::view_by_id($i->client)->company_name)?></option>
                                <?php foreach ($clients as $client): ?>
                                <option value="<?=$client->co_id?>">
                                    <?=ucfirst($client->company_name)?></option>
                                <?php endforeach;  ?>
                            </optgroup>
                        </select>
                    </div>
                </div>

                <div class="form-group mb-3 row">
                    <label class="col-lg-3 control-label common-label"><?=lang('hd_lang.currency')?></label>
                    <div class="col-lg-8">
                        <select name="currency" class="form-control common-select">
                            <?php $cur = App::currencies($i->currency); ?>
                            <?php foreach ($currencies as $cur) : ?>
                            <option value="<?=$cur->code?>"
                                <?=($i->currency == $cur->code ? ' selected="selected"' : '')?>><?=$cur->name?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group mb-3 row">
                    <label class="col-lg-3 control-label common-label"><?=lang('hd_lang.due_date')?></label>
                    <div class="col-lg-8">
                        <input class="input-sm input-s datepicker-input form-control common-input m-0" size="16" type="text"
                            value="<?=strftime($custom->getconfig_item('date_format'), strtotime($i->due_date));?>"
                            name="due_date" data-date-format="<?=$custom->getconfig_item('date_picker_format');?>">
                    </div>
                </div>

                <div class="form-group mb-3 row">
                    <label class="col-lg-3 control-label common-label"><?=lang('hd_lang.created')?></label>
                    <div class="col-lg-8">
                        <input class="input-sm input-s datepicker-input form-control common-input m-0" size="16" type="text"
                            value="<?=strftime($custom->getconfig_item('date_format'), strtotime($i->date_saved));?>"
                            name="date_saved" data-date-format="<?=$custom->getconfig_item('date_picker_format');?>">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-3 row">
                    <label class="col-lg-3 control-label common-label"><?=lang('hd_lang.tax')?></label>
                    <div class="col-lg-8">
                        <div class="input-group">
                            <span class="input-group-addon left-pos-border" >%</span>
                            <input class="form-control money common-input m-0" type="text" value="<?=$i->tax?>" name="tax" >
                        </div>
                    </div>
                </div>

                <!-- Start discount fields -->
                <div class="form-group mb-3 row">
                    <label class="col-lg-3 control-label common-label"><?=lang('hd_lang.discount')?> </label>
                    <div class="col-lg-8">
                        <div class="input-group">
                            <span class="input-group-addon left-pos-border" >%</span>
                       <input class="form-control money common-input m-0" type="text" value="<?=$i->discount_percentage?>" name="discount" >
                        </div>
                    </div>
                </div>
                <!-- End discount Fields -->

                <div class="form-group mb-3 row">
                    <label class="col-lg-3 control-label common-label"><?=lang('hd_lang.extra_fee')?></label>
                    <div class="col-lg-8">
                        <div class="input-group">
                            <span class="input-group-addon left-pos-border" >%</span>
                            <input class="form-control money common-input m-0" type="text" value="<?=$i->extra_fee?>" name="extra_fee" >
                        </div>
                    </div>
                </div>


            </div>
        </div>

        <div class="form-group mb-3 row last-row" >
            <label class="col-lg-2 control-label common-label"><?=lang('hd_lang.notes')?> </label>
            <div class="col-lg-10">
                <textarea name="notes" class="form-control foeditor common-input m-0"><?=$i->notes?></textarea>
            </div>
        </div>
        <button type="submit" class="btn btn-sm btn-<?=$custom->getconfig_item('theme_color');?> pull-right common-button">
            <?=lang('hd_lang.save_changes')?></button>
            <br />
            <br />
            <br />
        <?php echo form_close(); ?>
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
				<?php foreach (Invoice::has_items($id) as $key => $item) { ?>
					<tr class="sortable" data-name="<?= $item->item_name ?>" data-id="<?= $item->item_id ?>">
						<td class="drag-handle"><i class="fa fa-reorder"></i></td>
						<td>

							<?php //if (User::perm_allowed(User::get_id(),'edit_all_invoices')) { 
							?>
							<a class="text-info" href="<?= base_url() ?>invoices/items/edit/<?= $item->item_id ?>"
								data-toggle="ajaxModal"><?= $item->item_name ?>
							</a>
							<?php //} else { 
							?>
							<?php //$item->item_name ?>
							<?php //} 
							?>
						</td>
						<td class="text-muted"><?= nl2br($item->item_desc) ?></td>

						<td class="text-right"><?= Applib::format_quantity($item->quantity); ?></td>
						<?php if ($showtax) : ?>
							<td class="text-right"><?= Applib::format_tax($item->item_tax_rate) . '%'; ?></td>
						<?php endif; ?>
						<td class="text-right">

							<?php if (!User::is_admin() && !User::is_staff()) {
								echo Applib::format_currency(Applib::client_currency($client_cur->currency, $item->unit_cost), 'default_currency');
							} else {
								echo Applib::format_currency($item->unit_cost, 'default_currency');
							}
							?>
						</td>
						<?php if ($showtax) : ?>
							<td class="text-right">
								<?php if (!User::is_admin() && !User::is_staff()) {
									//echo Applib::format_currency($client_cur, Applib::client_currency($client_cur, $item->item_tax_total));
									echo Applib::format_currency(Applib::client_currency($client_cur->currency, $item->item_tax_total), 'default_currency');
								} else {
									//echo Applib::format_currency($item->item_tax_total, 'default_currency');
									echo Applib::format_currency($item->item_tax_total, 'default_currency');
								}
								?>
							</td>
						<?php endif; ?>
						<td class="text-right">
							<?php if (!User::is_admin() && !User::is_staff()) {
								//echo Applib::format_currency($client_cur, Applib::client_currency($client_cur, $item->total_cost));
								echo Applib::format_currency(Applib::client_currency($client_cur->currency, $item->total_cost), 'default_currency');
							} else {
								//echo Applib::format_currency($inv->currency, $item->total_cost);
								echo Applib::format_currency($item->total_cost, 'default_currency');
							}
							?>
						</td>

						<td>
							<?php //if (User::perm_allowed(User::get_id(),'edit_all_invoices')) { 
							?>
							<a class="hidden-print"
								href="<?= base_url() ?>invoices/items/delete/<?= $item->item_id ?>/<?= $item->invoice_id ?>"
								data-toggle="ajaxModal"><i class="fa fa-trash-o text-danger"></i>
							</a>
							<?php //} 
							?>
						</td>
					</tr>
				<?php } ?>

				<?php //if (User::perm_allowed(User::get_id(),'edit_all_invoices')) { ?>

				<?php //if (User::is_admin() && $inv->status != 'Paid') { ?>
					<tr class="hidden-print">
						<?php $attributes = array('class' => 'bs-example form-horizontal');
						echo form_open(base_url() . 'invoices/items/add', $attributes);
						?>
						<input class='common-input' type="hidden" name="invoice_id" value="<?= $inv->inv_id ?>">
					<input class='common-input' type="hidden" name="item_order" value="<?= count(Invoice::has_items($inv->inv_id)) + 1 ?>">
						<input class='common-input' id="hidden-item-name" type="hidden" name="item_name">
						<td></td>
						<td><input id="auto-item-name" data-scope="invoices" type="text" name="item_name"
								placeholder="<?= lang('hd_lang.item_name') ?>" class="typeahead form-control common-input"></td>

						<td><textarea id="auto-item-desc" rows="1" name="item_desc"
								placeholder="<?= lang('hd_lang.item_description') ?>"
								class="form-control common-input js-auto-size"></textarea>
						</td>

						<td><input id="auto-quantity" type="text" name="quantity" value="1" class="form-control common-input"></td>
						<?php if ($showtax) : ?>
							<td>
								<select name="item_tax_rate" class="form-control m-b common-select">
									<option value="0.00"><?= lang('hd_lang.none') ?></option>
									<?php
									foreach (Invoice::get_tax_rates() as $key => $tax) {
									?>
										<option value="<?= $tax->tax_rate_percent ?>"
											<?= $custom->getconfig_item('default_tax') == $tax->tax_rate_percent ? ' selected="selected"' : '' ?>>
											<?= $tax->tax_rate_name ?></option>
									<?php } ?>
								</select>
							</td>
						<?php endif; ?>
						<td><input id="auto-unit-cost" type="text" name="unit_cost" required placeholder="50.56"
								class="form-control common-input"></td>
						<?php if ($showtax) : ?>
							<td><input type="text" name="tax" placeholder="0.00" readonly="" class="form-control common-input"></td>
						<?php endif; ?>
						<td></td>
						<td><button type="submit" class="btn btn-<?= $custom->getconfig_item('theme_color') ?> common-button"><i
									class="fa fa-check"></i> <?= lang('hd_lang.save') ?></button></td>
						<?php echo form_close(); ?>
					</tr>
				<?php //} ?>
				<?php //} 
				?>
			</tbody>
		</table>
		</div>
    </div>
    <?= $this->endSection() ?>
</div>