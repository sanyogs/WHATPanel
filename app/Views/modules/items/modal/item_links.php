<div class="modal-dialog my-modal">
	<div class="modal-content">
		<div class="modal-header bg-success row-reverse"> <button type="button" class="close" data-dismiss="modal">&times;</button> 
		<h4 class="modal-title common-h text-white"><?=lang('hd_lang.links')?></h4>
		</div> 
		<div class="modal-body">
		
			 <p class="common-p"><?=base_url()?><strong>cart/options?item=<?=$id?></strong></p>

			 <hr>		

			 <h5 class="common-h"><?=lang('hd_lang.add_to_cart')?></h5>
			 <textarea class="form-control common-input" style='height: unset; !important' readonly><a href="<?=base_url()?>cart/options?item=<?=$id?>"><?=lang('hd_lang.add_to_cart')?></a></textarea>

		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('hd_lang.close')?></a>
		 
		</form>
	</div>
</div>
<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->