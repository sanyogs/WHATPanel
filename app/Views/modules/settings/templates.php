<!-- <style type="text/css">
.btn-twitter:active,
.btn-twitter.active {
    color: #000 !important;
    background-color: #fff;
    border-color: #1ab394;
}
</style> -->
<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */
use App\Models\App;
use App\Helpers\app_helper;

use App\Helpers\custom_name_helper;

$custom = new custom_name_helper();

$template_group = isset($load_sub_setting) ? $load_sub_setting : 'user';

switch ($template_group) {
    case "invoice":
        $default = "invoice_message";
        break;
    case "ticket":
        $default = "ticket_client_email";
        break;
    case "user":
        $default = "hosting_account";
        break;
    case "signature":
        $default = "email_signature";
        break;
}
$setting_email = isset($load_sub_group) ? $load_sub_group : $default;

$email['invoice'] = array("invoice_message", "invoice_reminder", "payment_email");
$email['ticket'] = array("ticket_client_email", "ticket_closed_email", "ticket_reply_email", "ticket_staff_email", "auto_close_ticket", "ticket_reopened_email");
$email['user'] = array("hosting_account", "service_suspended", "service_unsuspended", "activate_account", "change_email", "forgot_password", "registration", "reset_password");
$email['signature'] = array("email_signature");

$attributes = array('class' => 'bs-example form-horizontal');
//echo form_open('settings/templates/'.$template_group.'/'.$setting_email, $attributes); 
echo form_open('settings/update');

?>

<div class="row">
    <div class="col-lg-12">
        <section class="panel panel-default">
            <h4 class="common-boxTitle text-primary"><i class="fa fa-cogs"></i> <?= lang('hd_lang.email_templates') ?></h4>
            <div class="panel-body">


                <div class="m-b-sm panelBtnWrap">
                    <?php foreach ($email[$template_group] as $temp):
                        $lang = $temp;
                        switch ($temp) {
                            case "registration":
                                $lang = 'register_email';
                                break;
                            case "email_signature":
                                $lang = 'email_signature';
                                break;
                        } ?>
       <a href="<?= base_url() ?>settings/templates/<?= $template_group; ?>/<?= $temp; ?>" class="<?php if ($setting_email == $temp) {
                                echo "active";
                            } ?> btn btn-s-xs btn-sm btn-twitter common-button" style="color:white !important;" >
                        <?= lang('hd_lang.'.$lang) ?>
                    </a>
                    <?php endforeach; ?>

                </div>

                <input type="hidden" name="email_group" value="<?= $setting_email; ?>">

                <input type="hidden" name="settings" value="<?=$load_setting?>">

                <input type="hidden" name="return_url"
                    value="<?= base_url() ?>settings/templates/<?= $template_group; ?>/<?= $setting_email; ?>">
                <?php if ($template_group != 'signature'): ?>
                <div class="form-group">
                    <label class="control-label common-label">
                        <?= lang('hd_lang.subject') ?>
                    </label>
                    <div class="inputDiv">
                        <input class="form-control common-input" name="subject"
                            value="<?php echo App::email_template($setting_email, 'subject'); ?>" />
                    </div>
                </div>
                <?php endif; ?>
                <div class="form-group">
                    <label class="col-lg-12">
                        <?= lang('hd_lang.message') ?>
                    </label>
                    <div class="col-lg-12">
                        <textarea class="form-control form-control foeditor" name="email_template">
                    <?php echo App::email_template($setting_email, 'template_body'); ?></textarea>
                    </div>
                </div>

            </div>
            <div class="panel-footer">
                <div class="text-center mt-4">
                    <button type="submit"
                        class="btn btn-sm common-button  btn-<?= $custom->getconfig_item('theme_color'); ?>">
                        <?= lang('hd_lang.save_changes') ?>
                    </button>
                </div>

                <div class="tagsWrap mt-5">
                    <h4 class="common-boxTitle text-primary"><?= lang('hd_lang.template_tags') ?></h4>
                    <ul>
                        <?php 

                    $appHelper = new app_helper();
                    
                    $tags = $appHelper->get_tags($setting_email);
                    foreach ($tags as $key => $value) {
                        echo '<li class="common-p">{' . $value . '}</li>';
                    } ?>
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