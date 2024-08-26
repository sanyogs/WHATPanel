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

$helper = new custom_name_helper();
?>

<div class="modal-dialog my-modal">
    <div class="modal-content">
        <div class="modal-header row-reverse"> <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><?=lang('hd_lang.edit_item')?></h4>
        </div>
        <?php $modal = new Invoice();
        $item = $modal->view_item($id); ?>

        <?php
             $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'invoices/items/edit',$attributes); ?>
        <input type="hidden" name="item_id" value="<?=$item->item_id?>">
        <input type="hidden" name="item_order" value="<?=$item->item_order?>">
        <input type="hidden" name="invoice_id" value="<?=$item->invoice_id?>">
        <div class="modal-body">

            <div class="form-group input-modal">
                <label class="col-lg-4 control-label common-label"><?=lang('hd_lang.item_name')?> <span class="text-danger">*</span></label>
                <div class="col-lg-8">
                    <input type="text" class="form-control common-input" value="<?=$item->item_name?>" name="item_name">
                </div>
            </div>

            <div class="form-group input-modal">
                <label class="col-lg-4 control-label common-label"><?=lang('hd_lang.item_description')?> </label>
                <div class="col-lg-8">
                    <textarea class="form-control common-input ta" name="item_desc"><?=$item->item_desc?></textarea>
                </div>
            </div>

            <div class="form-group input-modal">
                <label class="col-lg-4 control-label common-label"><?=lang('hd_lang.quantity')?> <span class="text-danger">*</span></label>
                <div class="col-lg-8">
                    <input type="text" class="form-control common-input" value="<?=$item->quantity?>" name="quantity">
                </div>
            </div>

            <div class="form-group input-modal">
                <label class="col-lg-4 control-label common-label"><?=lang('hd_lang.unit_price')?> <span class="text-danger">*</span></label>
                <div class="col-lg-8">
                    <input type="text" class="form-control common-input" value="<?=$item->unit_cost?>" name="unit_cost">
                </div>
            </div>

            <div class="form-group input-modal">
                <label class="col-lg-4 control-label common-label"><?=lang('hd_lang.tax_rate')?> <span class="text-danger">*</span></label>
                <div class="col-lg-8">
                    <select name="item_tax_rate" class="form-control common-select m-b">
                        <option value="<?=$item->item_tax_rate?>"><?=$item->item_tax_rate?></option>
                        <option value="0.00"><?=lang('hd_lang.none')?></option>
                        <?php foreach (Invoice::get_tax_rates() as $key => $tax) { ?>
                        <option value="<?=$tax->tax_rate_percent?>"><?=$tax->tax_rate_name?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>


        </div>
        <div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('hd_lang.close')?></a>
            <button type="submit" class="btn btn-<?=$helper->getconfig_item('theme_color');?>"><?=lang('hd_lang.save_changes')?></button>
            </form>
        </div>

    </div>
    <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->