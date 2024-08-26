<div class="modal-dialog sm-modal">
	<div class="modal-content w-100">
		<div class="modal-header p-3 bg-danger row-reverse"> <button type="button" class="close" data-dismiss="modal">&times;</button> 
		<h4 class="modal-title text-white"><?=lang('hd_lang.delete_server')?></h4>
		</div><?php
			echo form_open(base_url('servers/delete_server')); ?>
		<div class="modal-body">

			<p><?=lang('hd_lang.delete_server_warning')?></p>
			<input type="hidden" name="id" value="<?=$id?>">
			<input type="hidden" name="r_url" value="<?= site_url("servers") ?>">

		</div>
		<div class="modal-footer p-3"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('hd_lang.close')?></a>
			<button type="submit" class="btn btn-danger"><?=lang('hd_lang.delete')?></button>
		</form>
	</div>
</div>
<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->