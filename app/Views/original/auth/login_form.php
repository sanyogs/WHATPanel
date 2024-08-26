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
$custom = new custom_name_helper();

$login = array(
	'name'	=> 'login',
	'class'	=> 'form-control',
	'placeholder' => lang('hd_lang.username'),
	'value' => set_value('login'),
	'maxlength'	=> 80,
	'size'	=> 30,
);
if ($login_by_username AND $login_by_email) {
	$login_label = 'Email or Username';
} else if ($login_by_username) {
	$login_label = 'Username';
} else {
	$login_label = 'Email';
}
$password = array(
	'name'	=> 'password',
	'id'	=> 'inputPassword',
	'placeholder' => lang('hd_lang.password'),
	'size'	=> 30,
	'class' => 'form-control'
);
$remember = array(
	'name'	=> 'remember',
	'id'	=> 'remember',
	'value'	=> 1,
	'checked'	=> set_value('remember'),
);
$captcha = array(
	'name'	=> 'captcha',
	'id'	=> 'captcha',
	'class'	=> 'form-control',
	'maxlength'	=> 10,
);
?>


<div class="container inner">
    <div class="row">
        <div class="login-box">

            <section class="panel panel-default">
                <header class="panel-heading text-center"> <?=lang('hd_lang.please_enter')?>
                    <strong><?=$custom->getconfig_item('website_name')?></strong> <?=lang('hd_lang.login_details')?>
                    <?php  echo modules::run('sidebar/flash_msg');?>
                </header>
                <?php
		$attributes = array('class' => 'panel-body login');
		echo form_open($this->uri->uri_string(),$attributes); ?>

                <div class="form-group">
                    <label class="control-label"><?=lang('hd_lang.email_user')?></label>
                    <?php echo form_input($login); ?>
                    <span class="text-danger">
                        <?php echo form_error($login['name']); ?><?php echo isset($errors[$login['name']])?$errors[$login['name']]:''; ?></span>
                </div>
                <div class="form-group">
                    <label class="control-label"><?=lang('hd_lang.password')?></label>
                    <?php echo form_password($password); ?>
                    <span
                        class="text-danger"><?php echo form_error($password['name']); ?><?php echo isset($errors[$password['name']])?$errors[$password['name']]:''; ?></span>
                </div>


                <table>

                    <?php if ($show_captcha) {
		if ($use_recaptcha) { ?>

                    <?php echo $this->recaptcha->render(); ?>

                    <?php } else { ?>
                    <tr>
                        <td colspan="2">
                            <p><?=lang('hd_lang.enter_the_code_exactly')?></p>
                        </td>
                    </tr>


                    <tr>
                        <td colspan="3"><?php echo $captcha_html; ?></td>
                        <td class="pl_5"><?php echo form_input($captcha); ?></td>
                        <span class="text-danger"><?php echo form_error($captcha['name']); ?></span>
                    </tr>
                    <?php }
	} ?>
                </table>

                <div class="checkbox">
                    <label>
                        <?php echo form_checkbox($remember); ?> <?=lang('hd_lang.this_is_my_computer')?>
                    </label>

                    <span class="pull-right"><a href="<?=base_url()?>auth/forgot_password"
                            class=" m-t-xs"><?=lang('hd_lang.forgot_password')?></a></span>
                </div>

                <button type="submit" class="btn btn-<?=$custom->getconfig_item('theme_color');?>"><?=lang('hd_lang.sign_in')?></button>

                <hr>

                <?php if ($custom->getconfig_item('allow_client_registration') == 'TRUE'){ ?>
                <span class="pull-left"><?=lang('hd_lang.do_not_have_an_account')?></span>
                <a href="<?=base_url()?>auth/register"
                    class="btn btn-default pull-right"><?=lang('hd_lang.get_your_account')?></a>
                <?php } ?>

                <?php echo form_close(); ?>

                <!-- footer -->



                <!-- / footer -->

            </section>
        </div>
    </div>
</div>