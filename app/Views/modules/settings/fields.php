		<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */
		$attributes = array('class' => 'bs-example form-horizontal');
		echo form_open(base_url('settings/fields/module'), $attributes);
		?>
			<div class="form-group">
						<label class="control-label common-label"><?= lang('hd_lang.module') ?> <span class="text-danger">*</span> </label>
						<div class="inputDiv">
							<div class="m-b">
								<select name="module" class="form-control common-select" required id="module">
									<option value="clients">Clients</option>
									<option value="tickets">Tickets</option>					
								</select>
							</div>
						</div>
					</div>

					<div class="select_department hidden">
						<div class="form-group">
							<label class="control-label common-label"><?= lang('hd_lang.department') ?> <span class="text-danger">*</span> </label>
							<div class="inputDiv">
								<div class="m-b">
									<select name="department" class="form-control common-select">
										<?php 
										use App\Helpers\custom_name_helper;
										$session = \Config\Services::session(); 
										
										$custom = new custom_name_helper();
										// Connect to the database  
										$db = \Config\Database::connect();

										$dept = $db->table('hd_departments')->get()->getResult(); ?>
											
										<?php foreach($dept as $d) : ?>
											<option value="<?=$d->deptid?>"><?=$d->deptname?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
						</div>

					</div>
		 
				<div class="text-center mt-5">
					<button type="submit" class="btn btn-sm common-button btn-<?=$custom->getconfig_item('theme_color');?>"><?=lang('hd_lang.save_changes')?></button>
				</div>
		 
		</form>
 
<script type="text/javascript">
	(function($){
	"use strict";
		$(document).ready(function(){
			$("#module").change(function(){
				$(this).find("option:selected").each(function(){
					if($(this).attr("value")=="tickets"){
						$(".select_department").show();
					}
					else{
						$(".select_department").hide();
					}
				});
			}).change();
		});
	})(jQuery);
</script>
