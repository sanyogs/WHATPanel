<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button> <h4 class="modal-title"><?=$info['item_name']?> <?=lang('payment')?></h4>
		</div>		
		<div class="modal-body">
		
			<p><?=lang('coinpayments_alert')?></p>

		<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */
			$attributes = array('name'=>'coinpayments_form','class' => 'bs-example form-horizontal');
                        echo form_open(base_url().'coinpayments/process');
                        $cur = App::currencies($info['currency']);
                ?>
					<input name="invoice" value="<?=$info['item_number']?>" type="hidden">
	 
				<div class="form-group row">
					<label class="col-lg-3 control-label"><?=lang('due_amount')?> </label>
					<div class="col-lg-4">
						<input type="text" class="form-control" value="<?=$cur->symbol?><?=number_format($info['amount'],2)?>" readonly>
					</div>
				</div>
           

				<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a> 
		<button type="submit" class="btn btn-success"><?=lang('continue')?></button>
		</div>
				
			
		</div>
		
		</form>
	</div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->