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

<?= $this->extend('layouts/users') ?>   

<?= $this->section('content') ?>

<div class="box" style="margin-top: 31px !important;"> 
    <div class="box-body"> 
    <div class="container"> 
    <h2><?=lang('hd_lang.custom_block')?> <a href="<?=base_url()?>blocks/add" class="btn btn-info pull-right common-button"><?=lang('hd_lang.rict_text_format')?></a></h2>
		<?php
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'blocks/add',$attributes); ?>   

          		<div class="form-group">
				<label class="control-label common-label"><?=lang('hd_lang.name')?> <span class="text-danger">*</span></label>
		 			<input type="text" class="form-control common-input" name="name" required />
				</div> 

				<div class="form-group">
				<label class="control-label common-label"><?=lang('hd_lang.type')?> <span class="text-danger">*</span></label>
					<select type="text" class="form-control common-select" name="format">
						<option value="js" class="form-control">HTML & Javascript - <?=lang('hd_lang.including_tags')?></option>
						<option value="php" class="form-control">PHP - <?=lang('hd_lang.excluding_tags')?></option>						
					</select>
				</div>

				<div class="form-group">
				<label class="control-label common-label"><?=lang('hd_lang.content')?> <span class="text-danger">*</span></label>
			<?php echo form_textarea('content', set_value('content', isset($content['body']) ? $content['body'] : '', FALSE), array('class' => 'common-input form-control foeditor ', 'id' => 'body')); ?>
				</div>
				 
		<div class="box-footer"><button type="submit" class="btn btn-<?=$custom_name_helper->getconfig_item('theme_color');?> pull-right common-button"><?=lang('hd_lang.save')?></button>		
    </div>
    </form>
    </div>
  </div>
</div>
<script src="<?= base_url() ?>js/ckeditor/ckeditor.js"></script> 
<script type="text/javascript">
	$(document).ready(function() {
		var textarea = document.getElementsByClassName('foeditor')[0];
		CKEDITOR.replace(textarea, {
			height: 300,
			filebrowserUploadUrl: "<?= base_url() ?>media/upload"
		});
	});
</script>
<?= $this->endSection() ?>