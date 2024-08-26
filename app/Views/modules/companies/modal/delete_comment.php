<div class="modal-dialog my-modal">
	<div class="modal-content">
		<div class="modal-header bg-danger"> <button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title"><?=lang('hd_lang.delete_comment')?></h4>
		</div><?php
			echo form_open(base_url().'companies/comment/'.$info->comment_id.'/delete'); ?>
		<div class="modal-body">
			<p><?=lang('hd_lang.delete_comment')?></p>

			<input type="hidden" name="id" value="<?=$info->comment_id?>">

		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('hd_lang.close')?></a>
			<button type="submit" class="btn btn-danger"><?=lang('hd_lang.delete_button')?></button>
		</form>
	</div>
</div>
<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
