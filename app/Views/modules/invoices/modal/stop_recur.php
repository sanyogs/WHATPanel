<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header bg-warning"> <button type="button" class="close" data-dismiss="modal">&times;</button> 
		<h4 class="modal-title"><?=lang('hd_lang.stop_recurring')?></h4>
		</div><?php
			echo form_open(base_url().'invoices/stop_recur'); ?>
		<div class="modal-body">
			<p><?=lang('hd_lang.stop_recur_warning')?></p>
			
			<input type="hidden" name="invoice" value="<?=$invoice?>">

		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('hd_lang.close')?></a>
			<button type="submit" class="btn btn-success"><?=lang('hd_lang.delete_button')?></button>
		</form>
	</div>
</div>
<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->