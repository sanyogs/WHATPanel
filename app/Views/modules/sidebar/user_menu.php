<div class="col-md-3 col-sm-4 col-10 px-0 sideBarDiv">
	<section id="sidebar">
		<div class="sidebarMenuWrap">
			<div class="sideMenuListWrap">
				<div class="sideMenuTitle">
					<span>
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="12" viewBox="0 0 16 12" fill="none">
							<path d="M15 5.22217H1C0.447719 5.22217 0 5.66989 0 6.22217C0 6.77445 0.447719 7.22217 1 7.22217H15C15.5523 7.22217 16 6.77445 16 6.22217C16 5.66989 15.5523 5.22217 15 5.22217Z" fill="#C0D6FF"></path>
							<path d="M1 2.55566H15C15.5523 2.55566 16 2.10795 16 1.55566C16 1.00338 15.5523 0.555664 15 0.555664H1C0.447719 0.555664 0 1.00338 0 1.55566C0 2.10795 0.447719 2.55566 1 2.55566Z" fill="#C0D6FF"></path>
							<path d="M15 9.88916H1C0.447719 9.88916 0 10.3369 0 10.8892C0 11.4414 0.447719 11.8892 1 11.8892H15C15.5523 11.8892 16 11.4414 16 10.8892C16 10.3369 15.5523 9.88916 15 9.88916Z" fill="#C0D6FF"></path>
						</svg>
					</span>
					<p>Main Menu</p>
				</div>
				<ul class="sidebarMenuList">
					<?php

					use App\Libraries\AppLib;
					use App\Models\Client;
					use App\Models\User;

					use Config\Database;

					$session = \Config\Services::session();

					$config = new Database();

					// Connect to the database
					$db = Database::connect($config->default);

					$user = new User();
					$client = new Client($db);

					$user_id = $user::get_id();
					// echo $user_id;die;
					// echo "<pre>";print_r($user::profile_info($user_id));die;
					$client_co = $user::profile_info($user_id)->company;
					$client = $client::view_by_id($client_co);
					//$cur = $client::client_currency($client_co);
					$badge = array();

					$page = "";

					// $menus = $this->db->where('access', 2)->where('visible', 1)->where('parent', '')->where('hook', 'main_menu_client')->order_by('order', 'ASC')->get('hooks')->result();

					$builder = $db->table('hd_hooks');
					$menus = $builder
						->where('access', 2)
						->where('visible', 1)
						->where('parent', '')
						->where('hook', 'main_menu_client')
						->orderBy('order', 'ASC')
						->get()
						->getResult();

					foreach ($menus as $menu) {
						// $sub = $this->db->where('access', 2)->where('visible', 1)->where('parent', $menu->module)->where('hook', 'main_menu_client')->order_by('order', 'ASC')->get('hooks');

						$builder = $db->table('hd_hooks');
						$sub = $builder
							->where('access', 2)
							->where('visible', 1)
							->where('parent', $menu->module)
							->where('hook', 'main_menu_client')
							->orderBy('order', 'ASC')
							->get();

					?>
						<?php if ($sub->getNumRows() > 0) {
							$submenus = $sub->getResult(); ?>
							<li class="<?php
										foreach ($submenus as $submenu) {

											if ($page == lang($submenu->name)) {
												echo "active";
											}
										}
										?>" style="color:white;" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Pages">

								<a href="<?= base_url() . '' ?><?= $menu->route ?>">
									<div class=' text-white'>
										<span>
											<svg xmlns="http://www.w3.org/2000/svg" width="16" height="20" viewBox="0 0 16 20" fill="none">
												<path d="M12 11.4074C12 11.6235 11.9157 11.8308 11.7657 11.9836C11.6156 12.1364 11.4122 12.2222 11.2 12.2222H4.79999C4.58782 12.2222 4.38434 12.1364 4.23431 11.9836C4.08428 11.8308 3.99999 11.6235 3.99999 11.4074C3.99999 11.1913 4.08428 10.9841 4.23431 10.8313C4.38434 10.6784 4.58782 10.5926 4.79999 10.5926H11.2C11.4122 10.5926 11.6156 10.6784 11.7657 10.8313C11.9157 10.9841 12 11.1913 12 11.4074ZM8.79999 13.8519H4.79999C4.58782 13.8519 4.38434 13.9377 4.23431 14.0905C4.08428 14.2433 3.99999 14.4506 3.99999 14.6667C3.99999 14.8828 4.08428 15.09 4.23431 15.2428C4.38434 15.3956 4.58782 15.4815 4.79999 15.4815H8.79999C9.01216 15.4815 9.21564 15.3956 9.36567 15.2428C9.5157 15.09 9.59999 14.8828 9.59999 14.6667C9.59999 14.4506 9.5157 14.2433 9.36567 14.0905C9.21564 13.9377 9.01216 13.8519 8.79999 13.8519ZM16 8.54334V15.4815C15.9987 16.5616 15.5769 17.5971 14.827 18.3609C14.0771 19.1246 13.0605 19.5543 12 19.5556H3.99999C2.93952 19.5543 1.92284 19.1246 1.17297 18.3609C0.423105 17.5971 0.00127028 16.5616 0 15.4815V4.07409C0.00127028 2.99398 0.423105 1.95847 1.17297 1.19472C1.92284 0.430959 2.93952 0.00131259 3.99999 1.87804e-05H7.61199C8.34768 -0.00190982 9.07646 0.14472 9.75617 0.43143C10.4359 0.718139 11.053 1.13924 11.572 1.67039L14.3592 4.51083C14.881 5.03902 15.2947 5.66742 15.5763 6.35962C15.858 7.05182 16.002 7.79406 16 8.54334ZM10.4408 2.82253C10.189 2.57415 9.90633 2.36047 9.59999 2.18698V5.70372C9.59999 5.91982 9.68427 6.12707 9.8343 6.27988C9.98433 6.43268 10.1878 6.51853 10.4 6.51853H13.8528C13.6823 6.20661 13.4723 5.91895 13.228 5.66298L10.4408 2.82253ZM14.4 8.54334C14.4 8.4089 14.3744 8.28016 14.3624 8.14816H10.4C9.76347 8.14816 9.15302 7.89062 8.70293 7.4322C8.25285 6.97378 7.99999 6.35202 7.99999 5.70372V1.66794C7.87039 1.65572 7.74319 1.62965 7.61199 1.62965H3.99999C3.36348 1.62965 2.75303 1.88719 2.30294 2.34561C1.85285 2.80403 1.6 3.42578 1.6 4.07409V15.4815C1.6 16.1298 1.85285 16.7515 2.30294 17.21C2.75303 17.6684 3.36348 17.9259 3.99999 17.9259H12C12.6365 17.9259 13.247 17.6684 13.697 17.21C14.1471 16.7515 14.4 16.1298 14.4 15.4815V8.54334Z" fill="#C0D6FF" />
											</svg>
										</span>
										<p><?= lang($menu->name) ?></p>
									</div>
								</a>

								<ul>
									<?php foreach ($submenus as $submenu) { ?>
										<li class="<?php if ($page == lang('hd_lang.' . $submenu->name)) {
														echo "active";
													} ?>" style="color:white;">

											<a href="<?= base_url() ?><?= $submenu->route ?>">
												<?php if (isset($badge[$submenu->module])) {
													echo $badge[$menu->module];
												} ?>
												<div class=' text-white'>
													<span><i class="fa <?= $submenu->icon ?> icon"> </i></span>
													<p><?= lang($submenu->name) ?></p>
												</div>
											</a>
										</li>
									<?php } ?>
								</ul>
							</li>
						<?php } else { ?>
							<li class="<?php if ($page == lang($menu->name)) {
											echo "active";
										} ?>">
								<a href="<?= base_url() . '' ?><?= $menu->route ?>">
									<?php if (isset($badge[$menu->module])) {
										echo $badge[$menu->module];
									} ?>
									<div class=' text-white'>
										<span><i class="fa <?= $menu->icon ?> icon">
											</i></span>
										<p><?= lang('hd_lang.' . $menu->name) ?></p>
									</div>
								</a>
							</li>
						<?php } ?>
					<?php } ?>

					<li><a href="<?= base_url() ?>invoices/add_funds/<?= $client_co ?>" data-toggle="ajaxModal">
							<div class=' text-white'>
								<span><i class="fa fa-bank icon"></i></span>
								<p>
									<?= lang('hd_lang.credit_balance') ?>
								</p> <span class="pull-right">
									<?php //echo AppLib::format_currency($cur->code, AppLib::client_currency($cur->code, $client->transaction_value)); 
									?>
								</span>
							</div>
						</a> </li>

					<?php
					if (isset($session->get('userdata')['is_admin']) && $session->get('userdata')['is_admin'] == 1) {   ?>
							<div class="client-switch-back">
								<div class="header   text-white ">
									<p>ADMIN</p>
								</div>
								<div class='  text-white'><a class="btn btn-lg btn-primary btn-block" href="<?= base_url() ?>profile/switch_back">Switch Back</a></div>
							</div>
						<?php } ?>
				</ul>
			</div>
		</div>
	</section>
</div>