<div class="modal-dialog sm-modal">
	<div class="modal-content w-100">
		<div class="modal-header p-4 bg-danger row-reverse"> <button type="button" class="close" data-dismiss="modal">&times;</button> 
		<h4 class="modal-title text-white"><?=lang('hd_lang.delete_item')?></h4>
		</div><?php
			echo form_open(base_url().'invoices/items/delete'); ?>
		<div class="modal-body">
			<p><?=lang('hd_lang.delete_item_warning')?></p>
			
			<input type="hidden" name="item" value="<?=$item_id?>">
			<input type="hidden" name="invoice" value="<?=$invoice?>">

		</div>
		<div class="modal-footer p-4 "> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('hd_lang.close')?></a>
			<button type="submit" class="btn btn-danger"><?=lang('hd_lang.delete_button')?></button>
		</form>
	</div>
</div>
<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->