<style type="text/css">
.btn-twitter:active,
.btn-twitter.active {
    color: #000 !important;
    background-color: #fff;
    border-color: #1ab394;
}
</style>
<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */
// $this->load->helper('app');  
use App\Models\App;
use App\Helpers\app_helper;

use App\Helpers\custom_name_helper;

$custom = new custom_name_helper();

$session = \Config\Services::session(); 





// Modify the 'default' property    


// Connect to the database  
$db = \Config\Database::connect();

$templates = $db->table('hd_sms_templates')->get()->getResult();

$template = isset($load_sub_setting) ? $load_sub_setting : 'invoice'; 
$attributes = array('class' => 'bs-example form-horizontal');
echo form_open('settings/update'); ?>

<div class="row">
    <div class="col-lg-12">
        <section class="panel panel-default">
            <h4 class="common-boxTitle text-primary"><i class="fa fa-cogs"></i> <?=lang('hd_lang.sms_templates')?></h4>
            <div class="panel-body">


                <div class="m-b-sm panelBtnWrap">
                    <?php foreach ($templates as $temp){ ?>
                    <a href="<?=base_url()?>settings/sms_templates/<?=$temp->type;?>"
                        class="<?php if($template == $temp->type){ echo "active"; } ?> btn btn-s-xs btn-sm btn-twitter common-button text-white" style="color:white !important;"><?=lang($temp->type)?></a>
                    <?php } ?>

                </div>
                <input type="hidden" name="settings" value="<?=$load_setting?>">
                <input type="hidden" name="template" value="<?=$template;?>">
                <input type="hidden" name="return_url"
                    value="<?=base_url()?>settings/sms_templates/<?=$template;?>">
                <div class="form-group">
                    <label class="control-label common-label"><?=lang('hd_lang.message')?></label>
                    <div class="inputDiv">
                        <textarea class="form-control common-input foeditor" name="sms_template"><?php echo App::sms_template($template);?></textarea>
                    </div>
                </div>

            </div>
            <div class="panel-footer">
                <div class="text-center mt-4">
                    <button type="submit"
                        class="btn btn-sm common-button btn-<?= $custom->getconfig_item('theme_color');?>"><?=lang('hd_lang.save_changes')?></button>
                </div>

                <div class="tagsWrap mt-5">
                    <h4 class="common-boxTitle text-primary"><?=lang('hd_lang.template_tags')?></h4>
                    <ul>
                        <?php 
                    
                    $appHelper = new app_helper();
                    
                    $tags = $appHelper->get_tags('sms'); foreach ($tags as $key => $value) { echo '<li class="common-p">{'.$value.'}</li>'; } ?>
                    </ul>
                </div>

            </div>

        </section>
    </div>
</div>
</form>
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