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
use App\Models\Invoice;
use App\Helpers\custom_name_helper;

$helper = new custom_name_helper();
?>

<div class="modal-dialog my-modal">
	<div class="modal-content">

		<div class="modal-header row-reverse"> <button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">
				<?= lang('hd_lang.email_invoice') ?>
			</h4>
		</div>
		<?php
		$attributes = array('class' => 'bs-example form-horizontal');
		echo form_open(base_url() . 'companies/send_invoice', $attributes); ?>
		<div class="modal-body">
			<input type="hidden" name="company" value="<?= $company ?>">
			<div class="form-group">
				<label class="col-lg-4 control-label">
					<?= lang('hd_lang.select_invoice') ?> <span class="text-danger">*</span>
				</label>
				<div class="col-lg-8">

					<select name="invoice_id" class="select2-option form-control" required="">
						<?php if (count($invoices) > 0) {
							foreach ($invoices as $key => $inv) { ?>
								<option value="<?= $inv->inv_id ?>">
									<?= $inv->reference_no ?> -
									<?= AppLib::format_currency($inv->currency, Invoice::get_invoice_due_amount($inv->inv_id)) ?>
									:
									<?= lang('hd_lang.'.Invoice::payment_status($inv->inv_id)); ?>
								</option>
							<?php } ?>
						<?php } ?>
					</select>
				</div>
			</div>
			<input type="hidden" name="user" value="<?= $user ?>">
		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal">
				<?= lang('hd_lang.close') ?>
			</a>
			<button type="submit" class="submit btn btn-<?= $helper->getconfig_item('theme_color'); ?>">
				<?= lang('hd_lang.email_invoice') ?>
			</button>
			</form>
		</div>
	</div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->