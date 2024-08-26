<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */ 

use App\Models\User; 

?>
<div class="modal-dialog sm-modal">
	<div class="modal-content w-100">
		<div class="modal-header bg-danger row-reverse p-3"> <button type="button" class="close" data-dismiss="modal">&times;</button> 
		<h4 class="modal-title text-white"><?=lang('hd_lang.delete_user')?></h4>
		</div><?php
			echo form_open(base_url().'account/delete'); ?>
		<div class="modal-body p-3">
			<p><?=lang('hd_lang.delete_user_warning')?></p>

			<ul class='common-p'>
				<li><?=lang('hd_lang.tickets')?></li>
				<li><?=lang('hd_lang.activities')?></li>
			</ul>
			
			<input type="hidden" name="user_id" value="<?=$user_id?>">
			<?php
			$company = User::profile_info($user_id)->company;
			if ($company >= 1) {
				$redirect = 'companies/view/'.$company;
			}else{
				$redirect = 'account';				
			}
			?>
			<input type="hidden" name="r_url" value="<?=base_url()?><?=$redirect?>">

		</div>
		<div class="modal-footer p-3"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('hd_lang.close')?></a>
			<button type="submit" class="btn btn-danger common-button"><?=lang('hd_lang.delete_button')?></button>
		</form>
	</div>
</div>
<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->