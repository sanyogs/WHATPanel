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
	'id'	=> 'login',
	'class'	=> 'form-control',
	'value' => set_value('login'),
	'maxlength'	=> 80,
	'size'	=> 30,
);
if ($custom->getconfig_item('use_username', 'tank_auth')) {
	$login_label = 'Email or login';
} else {
	$login_label = 'Email';
}
?>
<div class="container inner">
	<div class="row">
		<div class="login-box">
			<section class="panel panel-default">
				<header class="panel-heading text-center"> <strong><?= lang('hd_lang.forgot_password') ?></strong> </header>

				<?php
				$attributes = array('class' => 'panel-body wrapper-lg');
				$currentURL = current_url();
				$currentURI = service('uri')->getPath();

				echo form_open($currentURI, $attributes); ?>
				<div class="form-group">
					<label class="control-label"><?= lang('hd_lang.email') ?>/<?= lang('hd_lang.username') ?></label>
					<?php echo form_input($login); ?>
					<span class="text-hidden">
					<?= service('validation')->getError($login['name']) ?>
					<?= isset($errors[$login['name']]) ? $errors[$login['name']] : '' ?>
					</span>
				</div>
				<button type="submit" class="btn btn-danger"><?= lang('hd_lang.get_new_password') ?></button>
				<div class="line line-dashed">
				</div>
				<div class="row">
					<div class="col-md-6">
						<?php if ($custom->getconfig_item('allow_client_registration') == 'TRUE') { ?>
							<p class="text-muted text-center"><small><?= lang('hd_lang.do_not_have_an_account') ?></small></p>
							<a href="<?= base_url() ?>auth/register/" class="btn btn-info btn-block"><?= lang('hd_lang.get_your_account') ?></a>
						<?php } ?>
					</div>
					<div class="col-md-6">
						<p class="text-muted text-center"><small><?= lang('hd_lang.already_have_an_account') ?></small></p>
						<a href="<?= base_url() ?>login" class="btn btn-<?= $custom->getconfig_item('theme_color'); ?> btn-block"><?= lang('hd_lang.sign_in') ?></a>
						<?php echo form_close(); ?>
					</div>
				</div>
			</section>
		</div>
	</div>

</div>