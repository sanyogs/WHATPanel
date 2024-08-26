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

<div class="modal-dialog">
    <div class="modal-content">
        <?php $invoice = Invoice::view_by_id($id); ?>
        <div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><?=lang('hd_lang.invoice_reminder')?></h4>
        </div><?php
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'invoices/remind',$attributes); ?>
        <div class="modal-body">
            <input type="hidden" name="invoice_id" value="<?=$invoice->inv_id?>">
            <input type="hidden" name="client_name" value="<?=Client::view_by_id($invoice->client)->company_name?>">
            <input type="hidden" name="amount"
                value="<?=Applib::format_quantity(Invoice::get_invoice_due_amount($id))?>">

            <div class="form-group">
                <label class="col-lg-4 control-label"><?=lang('hd_lang.subject')?> <span class="text-danger">*</span></label>
                <div class="col-lg-8">
                    <input type="text" class="form-control"
                        value="<?php echo App::email_template('invoice_reminder','subject');?> <?=$invoice->reference_no?>"
                        name="subject">
                </div>
            </div>

            <input type="hidden" name="message" class="hiddenmessage">

            <div class="message" contenteditable="true">

                <?php echo App::email_template('invoice_reminder','template_body');?>
            </div>



        </div>
        <div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('hd_lang.close')?></a>
            <button type="submit"
                class="submit btn btn-<?=$custom->getconfig_item('theme_color');?>"><?=lang('hd_lang.send_reminder')?></button>
            </form>
        </div>
    </div>
    <!-- /.modal-content -->
</div>

<script type="text/javascript">
(function($) {
    "use strict";
    $('.submit').on('click', function() {
        var mysave = $('.message').html();
        $('.hiddenmessage').val(mysave);
    });
})(jQuery);
</script>


<!-- /.modal-dialog -->