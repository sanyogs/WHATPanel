<div class="modal-dialog sm-modal ">
	<div class="modal-content w-100">
		<div class="modal-header bg-danger row-reverse"> <button type="button" class="close" data-dismiss="modal">&times;</button> 
		<h4 class="modal-title text-white"><?=lang('hd_lang.delete_payment')?></h4>
		</div><?php
			echo form_open(base_url().'payments/delete'); ?>
		<div class="modal-body">
			<p class="common-h"><?=lang('hd_lang.delete_payment_warning')?></p>
			
			<input type="hidden" name="id" value="<?=$id?>">

		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-default common-button" data-dismiss="modal"><?=lang('hd_lang.close')?></a>
			<button type="submit" class="btn btn-danger common-button"><?=lang('hd_lang.delete_button')?></button>
		</form>
	</div>
</div>
<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->