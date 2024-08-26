<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Application Terminal Panel.
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

$custom = new custom_name_helper();

$payment_statuses = [
    lang('hd_lang.paid') => lang('hd_lang.paid'),
    lang('hd_lang.unpaid') => lang('hd_lang.unpaid'),
	'Cancelled' => lang('hd_lang.cancel'),
    lang('hd_lang.pending') => lang('hd_lang.pending'),
];

$current_status = Invoice::payment_status($invoice);

?>

<div class="modal-dialog my-modal">
    <div class="modal-content">
        <div class="modal-header row-reverse">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><?=lang('hd_lang.payment_status')?></h4>
        </div>
        <?php
			$attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'invoices/payment_status',$attributes); ?>
        <input type="hidden" name="invoice" value="<?=$invoice?>">

        <div class="modal-body">

            <div class="form-group">
                <label class="col-lg-4 control-label"><?=lang('hd_lang.status')?> <span class="text-danger">*</span></label>
                <div class="col-lg-8">
                    <select name="payment_status" class="form-control" required="required">
                        <?php foreach ($payment_statuses as $key => $item):?>
                        <option value="<?= $key ?>" <?= $key === $current_status ? 'selected' : '' ?>><?= $item ?></option>
                        <?php endforeach;?>
                    </select>
                </div>
            </div>
        </div>
        <div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('hd_lang.close')?></a>
            <button style='border:none !important;' type="submit" class="btn btn-<?=$custom->getconfig_item('theme_color')?>"><?=lang('hd_lang.add_item')?></button>
            <?php echo form_close(); ?>
        </div>
    </div>
    <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->