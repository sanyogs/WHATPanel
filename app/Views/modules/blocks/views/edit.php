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
		<?php
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url('blocks/edit'),$attributes); ?>
                 <input class="common-input" type="hidden" name="format" value="rich_text">
				 <input class="common-input" type="hidden" name="id" value="<?=$block->id?>">
                 
          		<div class="form-group">
				<label class="control-label common-label"><?=lang('hd_lang.name')?> <span class="text-danger">*</span></label>
		 			<input class="common-input" type="text" class="form-control" name="name" value="<?=$block->name?>">
				</div> 

				<div class="form-group">
				<label class="control-label common-label"><?=lang('hd_lang.content')?> <span class="text-danger">*</span></label>
						<textarea class="form-control foeditor common-input" style='height: unset !important;' name="content"><?=$block->code?></textarea>
				</div>
				 
		<div class="box-footer"><button type="submit" class="btn bg-<?=$custom_name_helper->getconfig_item('theme_color');?> pull-right" style="font-size: 1.6rem;background-color: #1912d3;border-radius: 0.5rem;padding: 0.8rem 1.2rem;cursor: pointer;color: #ffffff;margin-left: 94%;margin-top: 5px;"><?=lang('hd_lang.update')?></button>		
    </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>