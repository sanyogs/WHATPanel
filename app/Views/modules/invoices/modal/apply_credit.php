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
use App\Models\User;?>

<div class="modal-dialog modal-sm">
    <div class="modal-content">
        <div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><?=lang('hd_lang.pay')?></h4>
        </div>
        <?php 
            $client = Client::view_by_id(Invoice::view_by_id($invoice)->client);
            $due = Invoice::get_invoice_due_amount($invoice);
            $credit = $client->transaction_value;
            $client_cur = $client->currency;
            echo form_open(base_url().'invoices/apply_credit'); ?>
        <div class="modal-body">

            <div class="form-group">
                <label><?=lang('hd_lang.balance_due')?></label>
                <input type="text"
                    value="<?=Applib::format_currency($client_cur, Applib::client_currency($client_cur, $due));?>"
                    class="input-sm form-control" readonly="readonly">
            </div>

            <hr>

            <input type="hidden" value="<?=$invoice?>" name="invoice">
            <div class="form-group">
                <label><?=lang('hd_lang.credit_balance')?></label>
                <input type="text"
                    value="<?=Applib::format_currency($client_cur, Applib::client_currency($client_cur, $credit));?>"
                    class="input-sm form-control" readonly="readonly">
            </div>
        </div>
        <div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('hd_lang.close')?></a>
            <button type="submit" class="btn btn-<?=config_item('theme_color');?>"><?=lang('hd_lang.pay')?></button>
        </div>
        </form>
    </div>
</div>