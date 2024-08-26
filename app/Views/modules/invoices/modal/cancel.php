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

<?php
$i = Invoice::view_by_id($id);
?>
<div class="modal-dialog my-modal">
    <div class="modal-content">
        <div class="modal-header bg-danger row-reverse"> <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><?=lang('hd_lang.cancel')?> <?=lang('hd_lang.invoice')?> #<?=$i->reference_no?></h4>
        </div><?php
			echo form_open(base_url().'invoices/cancel'); ?>
        <div class="modal-body">
            <p class="common-h">Invoice <?=$i->reference_no?> will be marked as Cancelled.</p>

            <input type="hidden" name="id" value="<?=$id?>">

        </div>
        <div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('hd_lang.close')?></a>
            <button type="submit" class="btn btn-danger"><?=lang('hd_lang.cancelled')?></button>
            </form>
        </div>
    </div>
    <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->