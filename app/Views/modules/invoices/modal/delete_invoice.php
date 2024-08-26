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

<div class="modal-dialog sm-modal modal-lg">
    <div class="modal-content w-100">
        <div class="modal-header bg-danger row-reverse p-4"> <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title text-white"><?=lang('hd_lang.delete_invoice')?></h4>
        </div><?php
			echo form_open(base_url().'invoices/delete'); ?>
        <div class="modal-body">
            <p><?=lang('hd_lang.delete_invoice_warning')?></p>

            <input type="hidden" name="invoice" value="<?=$invoice?>">

        </div>
        <div class="modal-footer"> <a href="#" class="btn common-button btn-default " data-dismiss="modal"><?=lang('hd_lang.close')?></a>
            <button type="submit" class="btn btn-danger common-button"><?=lang('hd_lang.delete_button')?></button>
            </form>
        </div>
    </div>
    <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->