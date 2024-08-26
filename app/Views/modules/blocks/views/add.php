<?= $this->extend('layouts/users') ?>   

<?= $this->section('content') ?>
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
<div class="box" style="margin-top: 31px !important;"> 
    <div class="box-body"> 
    <div class="container"> 
	<h2 class='common-h my-3' ><?=lang('hd_lang.custom_block')?>
	<?php if($custom_name_helper->getconfig_item('allow_js_php_blocks') == "TRUE") { ?>
	 <a href="<?=base_url('blocks/add-code')?>" class="btn btn-warning pull-right common-button"><?=lang('hd_lang.code_format')?></a>
	<?php } ?>

	</h2> 
		<?php
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url('blocks/add'),$attributes); ?>
                 <input class="common-input" type="hidden" name="format" value="rich_text" required />
                 
          		<div class="form-group">
				<label class="control-label common-label"><?=lang('hd_lang.name')?> <span class="text-danger">*</span></label>
		 			<input class="common-input" type="text" class="form-control" name="name" required />
				</div> 

				<div class="form-group">
				<label class="control-label common-label"><?=lang('hd_lang.content')?> <span class="text-danger">*</span></label>
				<?php echo form_textarea('content', set_value('content', isset($content['body']) ? $content['body'] : '', FALSE), array('class' => 'common-input form-control foeditor ', 'id' => 'body')); ?>
				</div>
				 
		<div class="box-footer"><button type="submit" class="btn bg-<?=$custom_name_helper->getconfig_item('theme_color');?> pull-right" style="font-size: 1.6rem;background-color: #1912d3;border-radius: 0.5rem;padding: 0.8rem 1.2rem;cursor: pointer;color: #ffffff;margin-left: 94%;margin-top: 5px;"><?=lang('hd_lang.save')?></button>		
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