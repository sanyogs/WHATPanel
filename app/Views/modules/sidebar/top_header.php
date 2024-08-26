<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

$up = count($updates);

use App\Models\User;
use App\Helpers\custom_name_helper;

use App\Modules\auth\controllers\Auth;

$custom_name_helper = new custom_name_helper();

$authCon = new Auth();

$session = \Config\Services::session();
// echo "<pre>";print_r(session()->get());die;
// Connect to the database  
$db = \Config\Database::connect();

$userModel = new User();

if (!$session->has('logged_in')) 
{
	// Redirect to login page
	header('Location: ' . base_url('login'));
	exit();
}

//echo "<pre>";print_r($session->get('userdata'));die;

$user_data = $session->get('userdata');
	
$user = $user_data['user_id'];

$user_email = $userModel::login_info($user)->email;

$user_info = $userModel::login_info($user);

$custom_name_helper = new custom_name_helper();

?>

<header class="dashboardHeader">
	<div class="dashboardDiv">
		<div class="col-md-4 col-sm-7 col-7">
			<!-- <b> <h1>Version-Next.</h1></b> -->
			<a target="_blank" href="<?= base_url() ?>" class="logo">
				<div class="logo-lg">
				<?php $display = $custom_name_helper->getconfig_item('logo_or_icon'); ?>
				<?php if ($display == 'logo' || $display == 'logo_title') { ?>
					<img src="<?= base_url() ?>uploads/files/<?= $custom_name_helper->getconfig_item('company_logo') ?>">
				<?php } elseif ($display == 'icon' || $display == 'icon_title') { ?>
					<i class="fa <?= $custom_name_helper->getconfig_item('site_icon') ?>"></i>
				<?php } ?>
				<?php
				if ($display == 'logo_title' || $display == 'icon_title') {
					if ($custom_name_helper->getconfig_item('website_name') == '') {
					echo $custom_name_helper->getconfig_item('company_name');
					} else {
					echo $custom_name_helper->getconfig_item('website_name');
					}
				} ?>
				</div>
			</a>
		</div>
		<div class="col-md-8 col-sm-5 col-5">
			<div class="headerNav">
      	<div class="headerInputWrap">
				<span id='unique-search-opener' class="headerInputSVG" data-toggle="dropdown" aria-expanded="false" >
					<svg
						 xmlns="http://www.w3.org/2000/svg"
						 width="18"
						 height="15"
						 viewBox="0 0 18 15"
						 fill="none"
						 >
						<path
							  d="M1.36083 13.5869L4.9727 11.4839C6.06246 12.9679 7.67959 13.9784 9.4916 14.3078C11.3036 14.6371 13.1727 14.2602 14.7147 13.2545C16.2566 12.2488 17.3542 10.6908 17.7816 8.90083C18.2091 7.11086 17.934 5.22499 17.0129 3.63098C16.0918 2.03698 14.5947 0.856004 12.8295 0.330892C11.0642 -0.19422 9.16497 -0.0235551 7.52228 0.807794C5.87959 1.63914 4.61831 3.06798 3.99777 4.80052C3.37723 6.53306 3.44461 8.4376 4.18605 10.1225L0.580387 12.2024C0.488711 12.2548 0.408327 12.3248 0.343873 12.4084C0.279418 12.492 0.232171 12.5875 0.204853 12.6895C0.177535 12.7914 0.170689 12.8978 0.18471 13.0024C0.198731 13.107 0.233342 13.2079 0.286544 13.2991C0.39336 13.4769 0.565107 13.6063 0.765541 13.66C0.965975 13.7137 1.17942 13.6875 1.36083 13.5869ZM5.34694 5.79003C5.63343 4.72083 6.23079 3.76066 7.06346 3.03094C7.89614 2.30122 8.92674 1.83472 10.0249 1.69044C11.1231 1.54616 12.2396 1.73058 13.2332 2.22037C14.2267 2.71016 15.0528 3.48332 15.6068 4.4421C16.1608 5.40087 16.418 6.50218 16.3457 7.60677C16.2735 8.71136 15.8751 9.76962 15.201 10.6477C14.5268 11.5258 13.6071 12.1843 12.5583 12.5399C11.5094 12.8956 10.3785 12.9323 9.3085 12.6456C7.87365 12.2612 6.65019 11.3229 5.90726 10.0372C5.16432 8.75154 4.96277 7.22378 5.34694 5.79003Z"
							  fill="white"
							  />
					</svg>
				</span>
				<ul class="dropdown-menu search-drop" role="menu">
					<li>
						<input type="text" placeholder="Search..." class='common-input'/>
					</li>
				</ul>
				</div>
					<ul class="headerNavList">
						<?php
						if ($user_data['role_id'] == 1 || $user_data['role_id'] == 3) {
							//$menus = $this->db->where('access', 1)->where('visible', 1)->where('parent', '')->where('hook', 'main_menu_admin')->order_by('order', 'ASC')->get('hooks')->result();

							$menus = $db->table('hd_hooks')
								->where('access', 1)
								->where('visible', 1)
								->where('parent', '')
								->where('hook', 'main_menu_admin')
								->orderBy('order', 'ASC')
								->get()
								->getResult();

							foreach ($menus as $menu) {
								// $sub = $this->db->where('access', 1)->where('visible', 1)->where('parent', $menu->module)->where('hook', 'main_menu_admin')->order_by('order', 'ASC')->get('hooks'); 

								$sub = $db->table('hd_hooks')
									->where('access', 1)
									->where('visible', 1)
									->where('parent', $menu->module)
									->where('hook', 'main_menu_admin')
									->orderBy('order', 'ASC')
									->get();

								$submenuCount = $sub->getResultArray();

								$numRows = count($submenuCount);
						?>
						<?php if ($numRows > 0) {
							$submenus = $sub->getResult(); ?>
						<li class=" dropdown  <?php if (lang('hd_lang.'.$menu->name) == lang('hd_lang.website')) {
								echo 'website active';
							}
							foreach ($submenus as $submenu) {
								// if ($page == lang($submenu->name)) {
								//   echo "active";
								// }
							}
								?>">
				<a class=" dropdown-toggle" data-toggle="dropdown"> <?= lang('hd_lang.'.$menu->name) ?> <span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								<?php foreach ($submenus as $sub) { ?>
								<li>
									<a href="<?= base_url() ?><?= $sub->route ?>">
										<?= lang('hd_lang.'.$sub->name) ?></a>
								</li>
								<?php } ?>
							</ul>

						</li>
						<?php } else { ?>
						<li class="<?php //if ($page == lang($menu->name)) {
							// echo "active";
							//} ?>">
							<a href="<?= base_url()?><?= $menu->route ?>">
								<span><?= lang('hd_lang.'.$menu->name) ?></span>
								<?php if (lang('hd_lang.'.$menu->name) == lang('hd_lang.support')) { ?>  <span class="pull-right-container">
								<?php } ?>
							</a>
						</li>

						<?php } ?>
						<?php }
						} ?>
					</ul>
					<div class="navLastBlock">
						<div class="cart-icon-div">
							<a href="<?php echo base_url('cart/shopping_cart'); ?>">
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
							<path d="M14 4H12C12 2.93913 11.5786 1.92172 10.8284 1.17157C10.0783 0.421427 9.06087 0 8 0C6.93913 0 5.92172 0.421427 5.17157 1.17157C4.42143 1.92172 4 2.93913 4 4H2C1.46957 4 0.960859 4.21071 0.585786 4.58579C0.210714 4.96086 0 5.46957 0 6L0 12.6667C0.00105857 13.5504 0.352588 14.3976 0.97748 15.0225C1.60237 15.6474 2.4496 15.9989 3.33333 16H12.6667C13.5504 15.9989 14.3976 15.6474 15.0225 15.0225C15.6474 14.3976 15.9989 13.5504 16 12.6667V6C16 5.46957 15.7893 4.96086 15.4142 4.58579C15.0391 4.21071 14.5304 4 14 4ZM8 1.33333C8.70724 1.33333 9.38552 1.61429 9.88562 2.11438C10.3857 2.61448 10.6667 3.29276 10.6667 4H5.33333C5.33333 3.29276 5.61428 2.61448 6.11438 2.11438C6.61448 1.61429 7.29276 1.33333 8 1.33333ZM14.6667 12.6667C14.6667 13.1971 14.456 13.7058 14.0809 14.0809C13.7058 14.456 13.1971 14.6667 12.6667 14.6667H3.33333C2.8029 14.6667 2.29419 14.456 1.91912 14.0809C1.54405 13.7058 1.33333 13.1971 1.33333 12.6667V6C1.33333 5.82319 1.40357 5.65362 1.5286 5.5286C1.65362 5.40357 1.82319 5.33333 2 5.33333H4V6.66667C4 6.84348 4.07024 7.01305 4.19526 7.13807C4.32029 7.2631 4.48986 7.33333 4.66667 7.33333C4.84348 7.33333 5.01305 7.2631 5.13807 7.13807C5.2631 7.01305 5.33333 6.84348 5.33333 6.66667V5.33333H10.6667V6.66667C10.6667 6.84348 10.7369 7.01305 10.8619 7.13807C10.987 7.2631 11.1565 7.33333 11.3333 7.33333C11.5101 7.33333 11.6797 7.2631 11.8047 7.13807C11.9298 7.01305 12 6.84348 12 6.66667V5.33333H14C14.1768 5.33333 14.3464 5.40357 14.4714 5.5286C14.5964 5.65362 14.6667 5.82319 14.6667 6V12.6667Z" fill="white"/>
						</svg>
						</a>
						</div>
						<div class="headerUserBlock">
							<div class="headerUserIconWrap" data-toggle="dropdown" aria-expanded="false">
								<span class="headerUserIcon">
									<img src="<?= base_url('backend_assets/img/user-icon.png') ?>" alt="user-icon" />
								</span>
							</div>
							<ul class="dropdown-menu user-dropdown" style='right:0px !important;' >
								<li>
									<a class="dropdown-item" href="<?= base_url() ?>profile/settings">
									<span>
										<svg
										xmlns="http://www.w3.org/2000/svg"
										width="12"
										height="16"
										viewBox="0 0 12 16"
										fill="none"
										>
										<path
											d="M5.99951 8C6.79064 8 7.564 7.7654 8.22179 7.32588C8.87959 6.88635 9.39228 6.26164 9.69503 5.53074C9.99778 4.79983 10.077 3.99556 9.92265 3.21964C9.76831 2.44372 9.38735 1.73098 8.82794 1.17157C8.26853 0.612164 7.5558 0.231202 6.77987 0.0768607C6.00395 -0.0774802 5.19968 0.00173314 4.46878 0.304484C3.73787 0.607234 3.11316 1.11992 2.67363 1.77772C2.23411 2.43552 1.99951 3.20888 1.99951 4C2.00057 5.06054 2.42234 6.07734 3.17225 6.82726C3.92217 7.57717 4.93897 7.99894 5.99951 8ZM5.99951 1.33333C6.52693 1.33333 7.0425 1.48973 7.48103 1.78275C7.91956 2.07577 8.26136 2.49224 8.46319 2.97951C8.66502 3.46678 8.71783 4.00296 8.61494 4.52024C8.51205 5.03752 8.25807 5.51268 7.88513 5.88562C7.51219 6.25856 7.03704 6.51253 6.51975 6.61543C6.00247 6.71832 5.46629 6.66551 4.97902 6.46368C4.49175 6.26185 4.07528 5.92005 3.78226 5.48152C3.48924 5.04299 3.33285 4.52742 3.33285 4C3.33285 3.29276 3.6138 2.61448 4.11389 2.11438C4.61399 1.61429 5.29227 1.33333 5.99951 1.33333Z"
											fill="#5F5F5F"
										/>
										<path
											d="M6 9.33301C4.40924 9.33477 2.88414 9.96748 1.75931 11.0923C0.634472 12.2172 0.00176457 13.7422 0 15.333C0 15.5098 0.0702379 15.6794 0.195262 15.8044C0.320286 15.9294 0.489856 15.9997 0.666667 15.9997C0.843478 15.9997 1.01305 15.9294 1.13807 15.8044C1.2631 15.6794 1.33333 15.5098 1.33333 15.333C1.33333 14.0953 1.825 12.9083 2.70017 12.0332C3.57534 11.158 4.76232 10.6663 6 10.6663C7.23768 10.6663 8.42466 11.158 9.29983 12.0332C10.175 12.9083 10.6667 14.0953 10.6667 15.333C10.6667 15.5098 10.7369 15.6794 10.8619 15.8044C10.987 15.9294 11.1565 15.9997 11.3333 15.9997C11.5101 15.9997 11.6797 15.9294 11.8047 15.8044C11.9298 15.6794 12 15.5098 12 15.333C11.9982 13.7422 11.3655 12.2172 10.2407 11.0923C9.11586 9.96748 7.59076 9.33477 6 9.33301Z"
											fill="#5F5F5F"
										/>
										</svg>
									</span>
									<?= lang('hd_lang.profile') ?>
									</a>
								</li>
								<li>
									<a class="dropdown-item" href="<?= base_url() ?>profile/activities">
									<span>
										<svg
										xmlns="http://www.w3.org/2000/svg"
										width="12"
										height="12"
										viewBox="0 0 12 12"
										fill="none"
										>
										<path
											d="M9.5 0H2.5C1.1215 0 0 1.1215 0 2.5V9.5C0 10.8785 1.1215 12 2.5 12H9.5C10.8785 12 12 10.8785 12 9.5V2.5C12 1.1215 10.8785 0 9.5 0ZM11 9.5C11 10.327 10.327 11 9.5 11H2.5C1.673 11 1 10.327 1 9.5V2.5C1 1.673 1.673 1 2.5 1H9.5C10.327 1 11 1.673 11 2.5V9.5ZM6.5 9C6.5 9.2765 6.276 9.5 6 9.5C5.724 9.5 5.5 9.2765 5.5 9C5.5 7.6215 4.3785 6.5 3 6.5C2.724 6.5 2.5 6.2765 2.5 6C2.5 5.7235 2.724 5.5 3 5.5C4.93 5.5 6.5 7.0705 6.5 9ZM9.5 9C9.5 9.2765 9.276 9.5 9 9.5C8.724 9.5 8.5 9.2765 8.5 9C8.5 5.9675 6.0325 3.5 3 3.5C2.724 3.5 2.5 3.2765 2.5 3C2.5 2.7235 2.724 2.5 3 2.5C6.584 2.5 9.5 5.416 9.5 9ZM4 8.75C4 9.164 3.664 9.5 3.25 9.5C2.836 9.5 2.5 9.164 2.5 8.75C2.5 8.336 2.836 8 3.25 8C3.664 8 4 8.336 4 8.75Z"
											fill="#5F5F5F"
										/>
										</svg>
									</span>
									<?= lang('hd_lang.activities') ?>
									</a>
								</li>
								<li>
									<a class="dropdown-item logout-btn" href="<?= base_url() ?>logout">
									<span>
										<svg
										xmlns="http://www.w3.org/2000/svg"
										width="12"
										height="12"
										viewBox="0 0 12 12"
										fill="none"
										>
										<path
											d="M5.34928 7.44583C5.22566 7.44583 5.10709 7.49769 5.01968 7.59C4.93226 7.68231 4.88315 7.80751 4.88315 7.93805V9.41472C4.88315 9.80636 4.73582 10.182 4.47358 10.4589C4.21133 10.7358 3.85564 10.8914 3.48477 10.8914H2.33064C1.95976 10.8914 1.60408 10.7358 1.34183 10.4589C1.07958 10.182 0.932255 9.80636 0.932255 9.41472V2.52361C0.932255 2.13197 1.07958 1.75638 1.34183 1.47945C1.60408 1.20252 1.95976 1.04694 2.33064 1.04694H3.48477C3.85564 1.04694 4.21133 1.20252 4.47358 1.47945C4.73582 1.75638 4.88315 2.13197 4.88315 2.52361V4.00028C4.88315 4.13082 4.93226 4.25602 5.01968 4.34833C5.10709 4.44064 5.22566 4.4925 5.34928 4.4925C5.4729 4.4925 5.59147 4.44064 5.67888 4.34833C5.7663 4.25602 5.81541 4.13082 5.81541 4.00028V2.52361C5.81467 1.87112 5.56888 1.24558 5.13196 0.784206C4.69504 0.322827 4.10267 0.0632816 3.48477 0.0625H2.33064C1.71274 0.0632816 1.12036 0.322827 0.683445 0.784206C0.246526 1.24558 0.000740144 1.87112 0 2.52361L0 9.41472C0.000740144 10.0672 0.246526 10.6927 0.683445 11.1541C1.12036 11.6155 1.71274 11.8751 2.33064 11.8758H3.48477C4.10267 11.8751 4.69504 11.6155 5.13196 11.1541C5.56888 10.6927 5.81467 10.0672 5.81541 9.41472V7.93805C5.81541 7.80751 5.7663 7.68231 5.67888 7.59C5.59147 7.49769 5.4729 7.44583 5.34928 7.44583Z"
											fill="white"
										/>
										<path
											d="M10.6594 4.92518L8.5217 2.66785C8.4787 2.62084 8.42726 2.58334 8.37039 2.55754C8.31352 2.53174 8.25236 2.51816 8.19047 2.5176C8.12857 2.51703 8.06719 2.52948 8.00991 2.55423C7.95262 2.57898 7.90058 2.61553 7.85681 2.66175C7.81305 2.70796 7.77844 2.76292 7.755 2.82341C7.73156 2.8839 7.71977 2.94872 7.72031 3.01408C7.72084 3.07943 7.7337 3.14402 7.75813 3.20408C7.78256 3.26413 7.81807 3.31844 7.86259 3.36385L9.84923 5.46219L2.79718 5.47696C2.67356 5.47696 2.555 5.52882 2.46758 5.62113C2.38016 5.71344 2.33105 5.83864 2.33105 5.96918C2.33105 6.09973 2.38016 6.22493 2.46758 6.31724C2.555 6.40954 2.67356 6.4614 2.79718 6.4614L9.87673 6.44615L7.86166 8.57451C7.81714 8.61992 7.78163 8.67423 7.7572 8.73429C7.73277 8.79434 7.71991 8.85893 7.71937 8.92429C7.71884 8.98964 7.73063 9.05446 7.75407 9.11495C7.7775 9.17544 7.81212 9.2304 7.85588 9.27662C7.89965 9.32283 7.95169 9.35938 8.00898 9.38413C8.06626 9.40888 8.12764 9.42134 8.18953 9.42077C8.25143 9.4202 8.31259 9.40662 8.36946 9.38082C8.42633 9.35503 8.47776 9.31753 8.52076 9.27052L10.6584 7.01319C10.9207 6.7364 11.0681 6.36094 11.0683 5.96939C11.0685 5.57783 10.9214 5.20222 10.6594 4.92518Z"
											fill="white"
										/>
										</svg>
									</span>
									<?= lang('hd_lang.logout') ?>
									</a>
								</li>
							</ul>
						</div>
					</div>
					<div class="responsiveHeaderMenu">
					<div class="hamburger-icon">
						<span></span>
						<span></span>
						<span></span>
					</div>
					<div class="responsiveMenu-wrap">
						<a target="_blank" href="<?= base_url() ?>" class="logo">
							<div class="logo-lg">
							<?php $display = $custom_name_helper->getconfig_item('logo_or_icon'); ?>
							<?php if ($display == 'logo' || $display == 'logo_title') { ?>
								<img src="<?= base_url() ?>uploads/files/<?= $custom_name_helper->getconfig_item('company_logo') ?>" class='col-6'>
							<?php } elseif ($display == 'icon' || $display == 'icon_title') { ?>
								<i class="fa <?= $custom_name_helper->getconfig_item('site_icon') ?>"></i>
							<?php } ?>
							
								<?php
								if ($display == 'logo_title' || $display == 'icon_title') {
									if ($custom_name_helper->getconfig_item('website_name') == '') {
									echo $custom_name_helper->getconfig_item('company_name');
									} else {
									echo $custom_name_helper->getconfig_item('website_name');
									}
								} ?>
							
							</div>
						</a>
						<ul >
							<?php
							if ($user_data['role_id'] == 1 || $user_data['role_id'] == 3) {
								//$menus = $this->db->where('access', 1)->where('visible', 1)->where('parent', '')->where('hook', 'main_menu_admin')->order_by('order', 'ASC')->get('hooks')->result();

								$menus = $db->table('hd_hooks')
									->where('access', 1)
									->where('visible', 1)
									->where('parent', '')
									->where('hook', 'main_menu_admin')
									->orderBy('order', 'ASC')
									->get()
									->getResult();

								foreach ($menus as $menu) {
									// $sub = $this->db->where('access', 1)->where('visible', 1)->where('parent', $menu->module)->where('hook', 'main_menu_admin')->order_by('order', 'ASC')->get('hooks'); 

									$sub = $db->table('hd_hooks')
										->where('access', 1)
										->where('visible', 1)
										->where('parent', $menu->module)
										->where('hook', 'main_menu_admin')
										->orderBy('order', 'ASC')
										->get();

									$submenuCount = $sub->getResultArray();

									$numRows = count($submenuCount);


							?>
							<?php if ($numRows > 0) {
								$submenus = $sub->getResult(); ?>
							<li class=" dropdown  <?php if (lang('hd_lang.'.$menu->name) == lang('hd_lang.website')) {
									echo 'website active';
								}
								foreach ($submenus as $submenu) {
									// if ($page == lang($submenu->name)) {
									//   echo "active";
									// }
								}
									?>">

								<a class=" dropdown-toggle" data-toggle="dropdown"> <?= lang('hd_lang.'.$menu->name) ?> <span class="caret"></span></a>
								<ul class="dropdown-menu" role="menu">
									<?php foreach ($submenus as $sub) { ?>
									<li>
										<a href="<?= base_url() ?><?= $sub->route ?>">
											<?= lang('hd_lang.'.$sub->name) ?></a>
									</li>
									<?php } ?>
								</ul>

							</li>
							<?php } else { ?>
							<li class="<?php //if ($page == lang($menu->name)) {
								// echo "active";
								//} ?>">
								<a href="<?= base_url() ?><?= $menu->route ?>">
									<span><?= lang('hd_lang.'.$menu->name) ?></span>
									<?php if (lang('hd_lang.'.$menu->name) == lang('hd_lang.support')) { ?>  <span class="pull-right-container">
									<?php } ?>
								</a>
							</li>

							<?php } ?>
							<?php }
							} ?>
						</ul>
					</div>
					<div class="responsive-search-wrap">
						<div class="resSearchClose">
							<span class="resSearchClosebtn">
								<svg
								xmlns="http://www.w3.org/2000/svg"
								viewBox="0 0 24 24"
								width="24"
								height="24"
								style="fill: white;"
								>
								<path fill="none" d="M0 0h24v24H0z" />
								<path
									d="M18.3 5.71a.996.996 0 0 0-1.41 0L12 10.59 7.11 5.7A.996.996 0 1 0 5.7 7.11L10.59 12 5.7 16.89a.996.996 0 1 0 1.41 1.41L12 13.41l4.89 4.89a.996.996 0 1 0 1.41-1.41L13.41 12l4.89-4.89c.38-.38.38-1.02 0-1.4z"
								/>
								</svg>
							</span>
						</div>
						<a target="_blank" href="<?= base_url() ?>" class="logo">
							<div class="logo-lg">
							<?php $display = $custom_name_helper->getconfig_item('logo_or_icon'); ?>
							<?php if ($display == 'logo' || $display == 'logo_title') { ?>
								<img src="<?= base_url() ?>uploads/files/<?= $custom_name_helper->getconfig_item('company_logo') ?>" class='col-6'>
							<?php } elseif ($display == 'icon' || $display == 'icon_title') { ?>
								<i class="fa <?= $custom_name_helper->getconfig_item('site_icon') ?>"></i>
							<?php } ?>
							<span class='col-6'>
								<?php
								if ($display == 'logo_title' || $display == 'icon_title') {
									if ($custom_name_helper->getconfig_item('website_name') == '') {
									echo $custom_name_helper->getconfig_item('company_name');
									} else {
									echo $custom_name_helper->getconfig_item('website_name');
									}
								} ?>
							</span>
							</div>
						</a>
						<div class="resHeaderInputWrap">
						<span>
							<svg
							xmlns="http://www.w3.org/2000/svg"
							width="18"
							height="15"
							viewBox="0 0 18 15"
							fill="none"
							>
							<path
								d="M1.36083 13.5869L4.9727 11.4839C6.06246 12.9679 7.67959 13.9784 9.4916 14.3078C11.3036 14.6371 13.1727 14.2602 14.7147 13.2545C16.2566 12.2488 17.3542 10.6908 17.7816 8.90083C18.2091 7.11086 17.934 5.22499 17.0129 3.63098C16.0918 2.03698 14.5947 0.856004 12.8295 0.330892C11.0642 -0.19422 9.16497 -0.0235551 7.52228 0.807794C5.87959 1.63914 4.61831 3.06798 3.99777 4.80052C3.37723 6.53306 3.44461 8.4376 4.18605 10.1225L0.580387 12.2024C0.488711 12.2548 0.408327 12.3248 0.343873 12.4084C0.279418 12.492 0.232171 12.5875 0.204853 12.6895C0.177535 12.7914 0.170689 12.8978 0.18471 13.0024C0.198731 13.107 0.233342 13.2079 0.286544 13.2991C0.39336 13.4769 0.565107 13.6063 0.765541 13.66C0.965975 13.7137 1.17942 13.6875 1.36083 13.5869ZM5.34694 5.79003C5.63343 4.72083 6.23079 3.76066 7.06346 3.03094C7.89614 2.30122 8.92674 1.83472 10.0249 1.69044C11.1231 1.54616 12.2396 1.73058 13.2332 2.22037C14.2267 2.71016 15.0528 3.48332 15.6068 4.4421C16.1608 5.40087 16.418 6.50218 16.3457 7.60677C16.2735 8.71136 15.8751 9.76962 15.201 10.6477C14.5268 11.5258 13.6071 12.1843 12.5583 12.5399C11.5094 12.8956 10.3785 12.9323 9.3085 12.6456C7.87365 12.2612 6.65019 11.3229 5.90726 10.0372C5.16432 8.75154 4.96277 7.22378 5.34694 5.79003Z"
								fill="white"
							></path>
							</svg>
						</span>
						<input type="text" placeholder="Search..." />
						</div>
					</div>
					</div>
				</div>
		</div>
	</div>
</header>