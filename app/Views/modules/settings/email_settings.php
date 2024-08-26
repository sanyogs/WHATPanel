<div class="row">
    <!-- Start Form test -->
    <div class="col-lg-12">
        <?php

use App\Helpers\custom_name_helper;
$custom_name = new custom_name_helper();

$attributes = array('class' => 'bs-example form-horizontal', 'data-validate' => 'parsley');
	echo form_open('settings/update', $attributes);?>
        <section class="box common-box">

            <div class="box-body">
                <?php //echo validation_errors(); ?>
                <input type="hidden" name="settings" value="<?=$load_setting?>">
                <input type="hidden" name="categories" value="<?=$load_setting?>">
                <input type="hidden" name="return_url" value="<?=base_url()?>settings/email">
                <div class="form-group">
                    <label class="control-label common-label"><?=lang('hd_lang.company_email')?> <span
                            class="text-danger">*</span></label>
                    <div class="inputDiv">
                        <input type="email" class="form-control common-input"
                            value="<?=$custom_name->getconfig_item('company_email', $temp_data)?>" name="company_email"
                            data-type="email" data-required="true">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label common-label"><?=lang('hd_lang.use_alternate_emails')?></label>
                    <div class="inputDiv form-check form-switch input-btn-div">

                        <input type="hidden" value="off" name="use_alternate_emails" />
                        <input type="checkbox" role="switch" class="form-check-input"
                            <?php if ($custom_name->getconfig_item('use_alternate_emails', $temp_data) == 'TRUE') {echo "checked=\"checked\"";}?>
                            name="use_alternate_emails" id="use_alternate_emails">

                    </div>
                </div>
                <div id="alternate_emails"
                    <?php echo ($custom_name->getconfig_item('use_alternate_emails', $temp_data) != 'TRUE') ? 'class="hidden"' : '' ?>>
                    <div class="form-group">
                        <label class="control-label common-label"><?=lang('hd_lang.billing_email')?></label>
                        <div class="inputDiv">
                            <input type="email" class="form-control common-input"
                                value="<?=$custom_name->getconfig_item('billing_email', $temp_data)?>" name="billing_email"
                                data-type="email">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label common-label"><?=lang('hd_lang.billing_email_name')?></label>
                        <div class="inputDiv">
                            <input type="text" class="form-control common-input"
                                value="<?=$custom_name->getconfig_item('billing_email_name', $temp_data)?>"
                                name="billing_email_name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label common-label"><?=lang('hd_lang.support_email')?></label>
                        <div class="inputDiv">
                            <input type="email" class="form-control common-input"
                                value="<?=$custom_name->getconfig_item('support_email', $temp_data)?>" name="support_email"
                                data-type="email">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label common-label"><?=lang('hd_lang.support_email_name')?></label>
                        <div class="inputDiv">
                            <input type="text" class="form-control common-input"
                                value="<?=$custom_name->getconfig_item('support_email_name', $temp_data)?>"
                                name="support_email_name">
                        </div>
                    </div>
                </div>


                <div class="form-group">
                    <label class="control-label common-label"><?=lang('hd_lang.email_protocol')?> <span
                            class="text-danger">*</span></label>
                    <div class="inputDiv">
                        <select name="protocol" class="form-control common-select" onchange="showMe(this);">
                            <?php $prot = $custom_name->getconfig_item('protocol', $temp_data);?>
                            <option value="mail" <?=($prot == "mail" ? ' selected="selected"' : '')?>>
                                <?=lang('hd_lang.php_mail')?></option>
                            <option value="smtp" <?=($prot == "smtp" ? ' selected="selected"' : '')?>>
                                <?=lang('hd_lang.smtp')?>
                            </option>
                        </select>
                    </div>
                </div>
                <div class="form-group" style="display:none;" id="idShowMe">
                    <label class="control-label common-label"><?=lang('hd_lang.smtp_host')?> </label>
                    <div class="inputDiv">
                        <input type="text" class="form-control common-input"
                            value="<?=$custom_name->getconfig_item('smtp_host', $temp_data)?>" name="smtp_host">
                        <span class="help-block m-b-none myError">SMTP Server Address</strong>.</span>
                    </div>
                </div>
                <div class="form-group" style="display:none;" id="ShowMe">
                    <label class="control-label common-label"><?=lang('hd_lang.smtp_user')?></label>
                    <div class="inputDiv">
                        <input type="text" class="form-control common-input"
                            value="<?=$custom_name->getconfig_item('smtp_user', $temp_data)?>" name="smtp_user">
                    </div>
                </div>
                <div class="form-group" style="display:none;" id="idShow">
                    <?php $encryption = \Config\Services::encryption();?>
                    <label class="control-label common-label"><?=lang('hd_lang.smtp_pass')?></label>
                    <div class="inputDiv">
                        <!-- <input type="password" class="form-control"
                            value="<?//=$encryption->decrypt($custom_name->config_item('smtp_pass', $temp_data));?>" name="smtp_pass"> -->
                        <input type="password" class="form-control common-input"
                            value="<?=$custom_name->getconfig_item('smtp_pass', $temp_data);?>" name="smtp_pass">
                    </div>
                </div>
                <div class="form-group" style="display:none;" id="idMe">
                    <label class="control-label common-label"><?=lang('hd_lang.smtp_port')?></label>
                    <div class="inputDiv">
                        <input type="text" class="form-control common-input"
                            value="<?=$custom_name->getconfig_item('smtp_port', $temp_data)?>" name="smtp_port">
                    </div>
                </div>


                <div class="form-group">
                    <label class="control-label common-label"><?=lang('hd_lang.email_encryption')?></label>
                    <div class="inputDiv">
                        <select name="smtp_encryption" class="form-control common-select">
                            <?php $crypt = $custom_name->getconfig_item('smtp_encryption', $temp_data);?>
                            <option value="" <?=($crypt == "" ? ' selected="selected"' : '')?>><?=lang('hd_lang.none')?>
                            </option>
                            <option value="ssl" <?=($crypt == "ssl" ? ' selected="selected"' : '')?>>SSL</option>
                            <option value="tls" <?=($crypt == "tls" ? ' selected="selected"' : '')?>>TLS</option>
                        </select>
                    </div>
                </div>
                <div class="box-footer">
                    <div class="text-center">
                        <!-- <button type="submit" class="btn btn-sm common-button btn-<?=$custom_name->getconfig_item('theme_color', $temp_data);?>"><?=lang('hd_lang.save_changes')?></button> -->
                        <button type="submit"
                            class="btn btn-sm common-button btn-primary"><?=lang('hd_lang.save_changes')?></button>
                    </div>
                </div>
        </section>
        </form>

        <?php
$attributes = array('class' => 'bs-example form-horizontal');
echo form_open_multipart('settings/update', $attributes);?>
        <section class="box box-primary box-solid">
            <h4 class="common-boxTitle"><i class="fa fa-random"></i> <?=lang('hd_lang.email_piping_settings')?>
            </h4>
            <div class="box-body pt-3">
                <?php //echo validation_errors(); ?>
                <input type="hidden" name="settings" value="<?=$load_setting?>">
                <input type="hidden" name="categories" value="<?=$load_setting?>">

                <input type="hidden" name="return_url" value="<?=base_url()?>settings/email">


                <div class="row">
                    <div class="form-group col-md-4">
                        <label class="control-label common-label"><?=lang('hd_lang.activate_email_tickets')?></label>
                        <div class="inputDiv form-check form-switch input-btn-div">

                            <input type="hidden" value="off" name="email_piping" />
                            <input type="hidden" name="categories" value="<?=$load_setting?>">
                            <input type="checkbox" role="switch" class="form-check-input"
                                <?php if ($custom_name->getconfig_item('email_piping', $temp_data) == 'TRUE') {echo "checked=\"checked\"";}?>
                                name="email_piping">

                        </div>
                    </div>

                    <div class="form-group col-md-4">
                        <label class="control-label common-label">IMAP</label>
                        <div class="inputDiv form-check form-switch input-btn-div">

                            <input type="hidden" value="off" name="mail_imap" />
                            <input type="checkbox" role="switch" class="form-check-input"
                                <?php if ($custom_name->getconfig_item('mail_imap', $temp_data) == 'TRUE') {echo "checked=\"checked\"";}?>
                                name="mail_imap">

                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class=" control-label common-label">IMAP Host</label>
                    <div class="inputDiv">
                        <input type="text" class="form-control common-input"
                            value="<?=$custom_name->getconfig_item('mail_imap_host', $temp_data)?>" name="mail_imap_host">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label common-label">IMAP Username</label>
                    <div class="inputDiv">
                        <input type="text" autocomplete="off" class="form-control common-input"
                            value="<?=$custom_name->getconfig_item('mail_username', $temp_data)?>" name="mail_username">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label common-label">IMAP Password</label>
                    <div class="inputDiv">
                        <?php
$encryption = \Config\Services::encryption();
// $pass = $encryption->decrypt($custom_name->config_item('mail_password', $temp_data));
$pass = $custom_name->getconfig_item('mail_password', $temp_data);
?>
                        <input type="password" autocomplete="off" class="form-control common-input" value="<?=$pass?>"
                            name="mail_password">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label common-label">Mail Port</label>
                    <div class="inputDiv">
                        <input type="text" class="form-control common-input"
                            value="<?=$custom_name->getconfig_item('mail_port', $temp_data)?>" name="mail_port">
                    </div>

                    <span class="help-block m-b-none small text-danger myError">Port (143 or 110) (Gmail: 993)</span>
                </div>

                <div class="form-group">
                    <label class="control-label common-label">Mail Flags</label>
                    <div class="inputDiv">
                        <input type="text" class="form-control common-input"
                            value="<?=$custom_name->getconfig_item('mail_flags', $temp_data)?>" name="mail_flags">
                    </div>

                    <span class="help-block m-b-none small text-danger myError">/notls or /novalidate-cert</span>
                </div>

                <div class="form-group">
                    <label class="control-label common-label">Mail SSL</label>
                    <div class="inputDiv form-check form-switch input-btn-div">

                        <input type="hidden" value="off" name="mail_ssl" />
                        <input type="checkbox" role="switch" class="form-check-input"
                            <?php if ($custom_name->getconfig_item('mail_ssl', $temp_data) == 'TRUE') {echo "checked=\"checked\"";}?>
                            name="mail_ssl">

                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label common-label">Mailbox</label>
                    <div class="inputDiv">
                        <input type="text" class="form-control common-input"
                            value="<?=$custom_name->getconfig_item('mailbox', $temp_data)?>" name="mailbox">
                    </div>

                </div>
                <div class="form-group">
                    <label class="control-label common-label">IMAP Search</label>
                    <div class="inputDiv">
                        <input type="text" class="form-control common-input"
                            value="<?=$custom_name->getconfig_item('mail_search', $temp_data)?>" name="mail_search">
                    </div>

                    <span class="help-block m-b-none small text-danger myError">UNSEEN</span>
                </div>

            </div>

            <div class="box-footer">
                <div class="text-center">
                    <button type="submit"
                        class="btn btn-sm common-button btn-primary"><?=lang('hd_lang.save_changes')?></button>
                </div>
            </div>
        </section>
        </form>
    </div>
	<script>
		function showMe(e) {
			var strdisplay = e.options[e.selectedIndex].value;
			var e = document.getElementById("idShowMe");
			var f = document.getElementById("ShowMe");
			var g = document.getElementById("idShow");
			var h = document.getElementById("idMe");
			if(strdisplay == "smtp") {
				e.style.display = "block";
				f.style.display = "block";
				g.style.display = "block";
				h.style.display = "block";
			} else {
				e.style.display = "none";
			}
		}
	</script>
    <!-- End Form -->
</div>
