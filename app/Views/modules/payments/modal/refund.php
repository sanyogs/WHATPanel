<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */
use App\Models\Payment;
$info = Payment::view_by_id($id);
?>
<div class="modal-dialog my-modal modal-cus">
    <div class="modal-content">
        <div class="modal-header bg-danger row-reverse"> <button type="button" class="close text-white" style='border-color: white !important;' data-dismiss="modal">&times;</button>
            <h4 class="modal-title text-white"><?=lang('hd_lang.refunded')?> - <?=$info->currency?> <?=$info->amount?></h4>
        </div><?php
			echo form_open(base_url().'payments/refund'); ?>
        <div class="modal-body">
            <p class='common-label'><?=lang('hd_lang.refund_payment_warning')?></p>

            <input type="hidden" name="id" value="<?=$id?>">

        </div>
        <div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('hd_lang.close')?></a>
            <button type="submit" class="btn btn-danger" style='border:none !important; background: #dc3545 !important; '><?=lang('hd_lang.delete_button')?></button>
            </form>
        </div>
    </div>
    <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->