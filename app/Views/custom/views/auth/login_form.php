<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

use App\ThirdParty\MX\Modules;
use function App\Helpers\form_error;
use App\Helpers\custom_name_helper;

$helper = new custom_name_helper();

$login = array(
	'name' => 'login',
	'class' => 'login-text',
	'placeholder' => lang('hd_lang.username'),
	'value' => set_value('login'),
	'maxlength' => 80,
	'size' => 30,
);
if ($login_by_username and $login_by_email) {
	$login_label = 'Email or Username';
} else if ($login_by_username) {
	$login_label = 'Username';
} else {
	$login_label = 'Email';
}
$password = array(
	'name' => 'password',
	'id' => 'inputPassword',
	'placeholder' => lang('hd_lang.password'),
	'size' => 30,
	'class' => 'login-password'
);
$remember = array(
	'name' => 'remember',
	'id' => 'remember',
	'value' => 1,
	'checked' => set_value('remember'),
);
$captcha = array(
	'name' => 'captcha',
	'id' => 'captcha',
	'class' => '',
	'maxlength' => 10,
);
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>Hosting Bill | Madpopo</title>

	<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css' rel="stylesheet" />
	<link rel="stylesheet" href="<?= site_url('assets/css/main.css'); ?>" />
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css" />
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" />
</head>

<body>
	<!-- Topbar Start -->
	<section id="topbarSection" class="innerHeader">
		<?= view('custom/views/sections/header.php'); ?>
		<!-- Navbar End -->

		<!-- TopbarText Start -->
		<section id="bannerSection">
			<div class="bannerWrapper">
				<div class="col-xl-12 col-lg-12 col-md-12">
					<div class="bannerContentWrapper bannerText">
						<h1>Login to your account</h1>
						<p>
							All our hosting accounts allow you to install popular software
							such
						</p>
					</div>
				</div>
			</div>
		</section>
		<!-- TopBarText End -->

		<!-- BannerImg start -->
		<div class="bannerArrow"></div>
		<div class="bannerServer">
			<img src="<?= base_url() ?>assets/images/solutions/sever.png" alt="">
		</div>
		<!-- BannerImg End -->
	</section>
	<!-- Topbar End -->

	<!-- Product and Services Section Start -->
	<section id="loginSection">
		<div class="loginRow">
			<div class="col-md-3 col-sm-3">
				<div class="left-side-wrap">
					<img src="<?= base_url() ?>assets/images/login-page-banner/human.png" alt="human-icon">
				</div>
			</div>
			<div class="col-md-6 col-sm-6">
				<div class="login-form-wrap">
					<div class="login-title-wrap">
						<h2 class="sectionTitle">Login <span>Now</span></h2>
						<p class="secText">
							<?= lang('hd_lang.please_enter') ?> <span style="color:#5479f7;"><?= $helper->getconfig_item('website_name') ?></span> <?= lang('hd_lang.login_details') ?>
							<?php echo Modules::run('sidebar/flash_msg'); ?>
						</p>
					</div>
					<div class="login-input-wrap">
						<?php
						$attributes = array('class' => 'panel-body login');
						echo form_open(base_url('login/loginaaaa'), $attributes);
						?>
						<div>
							<?php echo form_input($login); ?>
							<span class="text-danger">
								<?php //echo \App\Libraries\form_error($login['name']); ?>
								<?php echo isset($errors[$login['name']]) ? $errors[$login['name']] : ''; ?>
							</span>
						</div>
						<div class="password-wrap">
							<?php echo form_password($password); ?>
							<i class="bi bi-eye-slash" id="togglePassword"></i>
							<span class="text-danger">
								<?php //echo \App\Libraries\form_error($password['name']); ?>
								<?php echo isset($errors[$password['name']]) ? $errors[$password['name']] : ''; ?>
							</span>
						</div>
						<table>

							<?php if ($show_captcha) {
				if ($use_recaptcha) { ?>

							<?php echo $this->recaptcha->render(); ?>

							<?php } else { ?>
							<tr>
								<td colspan="2">
									<p><?=lang('enter_the_code_exactly')?></p>
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
						<button type="submit" class="btn btn-<?= $helper->getconfig_item('theme_color'); ?> login-button">Login</button>
						<div class="login-checkbox-wrap">
							<p><?=lang('hd_lang.this_is_my_computer')?></p>
							<?php echo form_checkbox($remember); ?>
						</div>
						<p class="forgot-pass-text">
							<?=lang('hd_lang.forgot_password')?><a href="<?=base_url()?>auth/forgot_password"> Click Here</a>
						</p>
						<?php //if ($helper->getconfig_item('allow_client_registration') == 'TRUE'){ ?>
						<div class="register-btn-wrap">
			<a href="<?=base_url()?>auth/register" type="submit" class="register-btn"><?=lang('hd_lang.get_your_account')?></a>
						</div>
						<?php // } ?>
						<?php echo form_close(); ?>
					</div>
				</div>
			</div>
			<div class="col-md-3 col-sm-3">
				<div class="right-side-wrap">
					<img src="<?= base_url() ?>assets/images/login-page-banner/server.jpg" alt="server-icon">
				</div>
			</div>
		</div>
	</section>
	
	<!-- Product and Services Section End -->

	<!-- Footer Section Start -->
	<?= view('custom/views/sections/footer.php'); ?>