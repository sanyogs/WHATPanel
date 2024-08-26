<div class="modal-dialog sm-modal ">
	<div class="modal-content w-100">
		<div class="modal-header bg-danger row-reverse p-4"> <button type="button" class="close" data-dismiss="modal">&times;</button> 
		<h4 class="modal-title text-white"><?=lang('hd_lang.delete_company')?></h4>
		</div><?php
			echo form_open(base_url().'companies/delete'); ?>
		<div class="modal-body">
			<p><?=lang('hd_lang.delete_company_warning')?></p>
			<ul class='common-p'>
				<li><?=lang('hd_lang.invoices')?></li>
				<li><?=lang('hd_lang.payments')?></li>
				<li><?=lang('hd_lang.activities')?></li>
			</ul>
			
			<input type="hidden" name="company" value="<?=$company_id?>">

		</div>
		<div class="modal-footer p-3"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('hd_lang.close')?></a>
			<button type="submit" class="btn btn-danger"><?=lang('hd_lang.delete_button')?></button>
		</form>
	</div>
</div>
<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->