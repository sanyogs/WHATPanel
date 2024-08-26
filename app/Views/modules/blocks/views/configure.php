<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */
use App\Helpers\custom_name_helper;
$custom_name_helper = new custom_name_helper();
?>
<div class="modal-dialog modal-xl my-modal modal-cus">
	<div class="modal-content">
	<?php
		 $attributes = array('class' => 'bs-example form-horizontal');
		  echo form_open(base_url().'blocks/config',$attributes); ?>	
		<div class="modal-header" style="justify-content: space-between; flex-direction: row-reverse;"> 
		<button type="button" class="close" data-dismiss="modal" style="background: transparent; border: 0;">&times;</button>
		<div class="row">
			<div class="col-md-9"><h2 class="modal-title common-h"><?=$_block->name?></h2></div>
			<div class="col-md-3">
			<?php if($_block->type == 'Module') {
					if(!empty($_block->settings)) 
					{
						$settings = unserialize($_block->settings); 
					}
					else {
						$settings = array('title' => 'no');
					} ?>
					<?=lang('hd_lang.display_title')?>
				 <input type='radio' name='title' value='no' <?=($settings['title'] == 'no') ? 'checked' : '';?>/> <?=lang('hd_lang.no')?> &nbsp;
				 <input type='radio' name='title' value='yes' <?=($settings['title'] == 'yes') ? 'checked' : '';?>/> <?=lang('hd_lang.yes')?>				
				<?php } ?>	
			</div>
			</div>				 
		</div> 
			  
		  <input type="hidden" value="<?=$_block->module?>" name="module">
		  <input type="hidden" value="<?=$_block->type?>" name="type">
		  <input type="hidden" value="<?=$_block->name?>" name="name">
		  <input type="hidden" value="<?=(isset($_block->param)) ? $_block->param : $_block->id?>" name="id">
		<div class="modal-body">
			  <div class="row">
			  	<div class="col-lg-9">
				  <img class="img-responsive" src="<?=base_url()?><?=$custom_name_helper->getconfig_item('active_theme')?>/sections.png" alt="Theme Sections" style="display: block;max-width: 100%;height: auto;" />
				</div>

				  <div class="col-lg-3 common-p my-3">
				  	<h4 class='common-h'><?=lang('hd_lang.display')?></h4>
				  	<select name="section" class="form-control common-select">
					  <option value=""><?=lang('hd_lang.none')?></option>
					  <?php foreach($blocks as $block) { ?>
						<option value="<?=$block->section?>" 
						<?php if(count($config) > 0) {
							foreach($config as $conf) { 
								if($conf->section == $block->section) {
									echo 'selected';
									break;
								}
							}} ?>><?=$block->name?></option>
					  <?php } ?>
					  </select>

					  <hr>
					  <h4 class='common-h'><?=lang('hd_lang.pages')?></h4>	
					  <input type='radio' name='mode' value='show' required <?=(count($config) > 0 && $config[0]->mode == 'show') ? 'checked' : '';?>/> <?=lang('hd_lang.show_in_selected')?><br />
					  <input type='radio' name='mode' value='hide' required <?=(count($config) > 0 && $config[0]->mode == 'hide') ? 'checked' : '';?>/> <?=lang('hd_lang.hide_in_selected')?>				
					  <div id="page_selection">	 
						<?php 

						$pages[] = (object) array('slug' => 'contact', 'title' => lang('hd_lang.contact'));
						foreach ($pages as $key => $p) { ?>
							<div class="checkbox">
								<label class="common-label m-0">
									<input type="hidden" value="off" name="<?=$p->slug?>" />
									<input <?php 
										if(count($config) > 0) {
											foreach($config as $conf) { 
												if($conf->page == $p->slug) {
													echo 'checked';
												}
											}
										}
									?>
									 name="pages[]" value="<?=$p->slug?>" type="checkbox">
									<?=$p->title?>
								</label>
							</div>
							<?php } ?>

							
						  </div>							

							<h4 class='common-h'><?=lang('hd_lang.weight')?></h4>
							<select name="weight" class="form-control common-select"> 
								<?php $weight = 0;
								while($weight < 11) { ?>
									<option value="<?=$weight?>" <?php 
										if(count($config) > 0) {
											foreach($config as $conf) { 
												if($conf->weight == $weight) {
													echo 'selected';
													break;
												}
											}
										}
									?>
									><?=$weight?></option>
								<?php $weight++; } ?>
							</select>
						<hr>
			  </row>			
		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('hd_lang.close')?></a> 
		<button type="submit" class="btn btn-<?=$custom_name_helper->getconfig_item('theme_color');?>"><?=lang('hd_lang.save_changes')?></button>
		</form>
		</div>
	</div>
	<!-- /.modal-content -->
</div>
 