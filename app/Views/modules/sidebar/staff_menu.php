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
                    use App\Models\User;

                    use Config\Database;

                    $session = \Config\Services::session();

                    

                    $config = new Database();

                    // Modify the 'default' property
                    

                    // Connect to the database
                    $db = Database::connect($config->default);

                    $user = new User($db);

                    //$user_id = User::get_id();
                    $badge = array();


                    // $menus = $this->db->where('access', 3)->where('visible', 1)->where('parent', '')->where('hook', 'main_menu_staff')->order_by('order', 'ASC')->get('hooks')->result();
                    $dbName      = \Config\Database::connect($db); // Use the default database connection
                    $builder = $dbName->table('hd_hooks');

                    $menus = $builder
                        ->where('access', 3)
                        ->where('visible', 1)
                        ->where('parent', '')
                        ->where('hook', 'main_menu_staff')
                        ->orderBy('order', 'ASC')
                        ->get()
                        ->getResult();

                    foreach ($menus as $menu) {
                        // $sub = $this->db->where('access', 3)->where('visible', 1)->where('parent', $menu->module)->where('hook', 'main_menu_staff')->order_by('order', 'ASC')->get('hooks');

                        $builder = $dbName->table('hd_hooks');

                        $sub = $builder
                            ->where('access', 3)
                            ->where('visible', 1)
                            ->where('parent', $menu->module)
                            ->where('hook', 'main_menu_staff')
                            ->orderBy('order', 'ASC')
                            ->get();

                        $perm = TRUE;

                        ?>

                        <?php if ($menu->permission != '') {
                            $perm = $user::perm_allowed($user_id, $menu->permission);
                        } ?>

                        <?php if ($perm) { ?>
                            <?php if (count($sub->getResult()) > 0) {
                                $submenus = $sub->getResult(); ?>
                                <li class=" text-white <?php
                                foreach ($submenus as $submenu) {
                                    if ($page == lang($submenu->name)) {
                                        echo "active";
                                    }
                                }
                                ?>"
                                
                                
                                >
                                    <a href="<?= base_url() ?><?= $menu->route ?>">
                                    <div class='common-p text-white'>
                                        <span><i class="fa <?= $menu->icon ?> icon"> </i></span>
                                        <span > <i class="fa fa-angle-down text"></i> <i
                                                class="fa fa-angle-up text-active"></i></span>
                                        <p><?= lang($menu->name) ?></p> </a>
                                        <ul class="sidebarMenuList">
                                            <?php foreach ($submenus as $submenu) { ?>
                                                <li class="<?php if ($page == lang($submenu->name)) {
                                                    echo "active";
                                                } ?>"  style="color:white;">
                                                    <a href="<?= base_url() ?><?= $submenu->route ?>">
                                                        <?php if (isset($badge[$submenu->module])) {
                                                            echo $badge[$menu->module];
                                                        } ?>
                                                        <div class='common-p text-white'>
                                                        <span><i class="fa <?= $submenu->icon ?> icon"> </i></span>
                                                        <p><?= lang($submenu->name) ?></p>
                                                        <div>
                                                    </a>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                </li>
                            <?php } else {  ?>
                                <li class="text-white <?php if ($page == lang($menu->name)) {
                                    echo "active";
                                } ?>">
                                    <a href="<?= base_url(). '' ?><?= $menu->route ?>">
                                    <div class='common-p text-white'>
                                        <?php  //if (isset($menu->module)) {
                                            //echo $menu->module;
                                        //} ?>
                                        <span><i class="fa <?= $menu->icon ?> icon">
                                        </i></span>
                                        <p><?= lang($menu->name) ?></p>
                                        </div>
                                    </a>
                                </li>
                            <?php } ?>
                        <?php } ?>
                    <?php }
                    ?>


                </ul>
            </div>
		</div>
	</section>
</div>
