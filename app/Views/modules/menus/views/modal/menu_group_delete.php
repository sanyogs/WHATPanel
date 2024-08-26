<div class="modal-dialog sm-modal">
	<div class="modal-content w-100">
		<div class="modal-header bg-danger row-reverse p-3"> <button type="button" class="close" data-dismiss="modal">&times;</button> 
		<h4 class="modal-title text-white"><?=lang('hd_lang.delete_menu_group')?></h4>
		</div><?php
			echo form_open(base_url().'menus/delete_menu_group'); ?>
		<div class="modal-body">

			<p><?=lang('hd_lang.delete_menu_group_warning')?></p>
			<input type="hidden" name="menu_group_id" value="<?=$id?>">

		</div>
		<div class="modal-footer p-3"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('hd_lang.close')?></a>
			<button type="submit" class="btn btn-danger"><?=lang('hd_lang.delete')?></button>
		</form>
	</div>
</div>
<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->