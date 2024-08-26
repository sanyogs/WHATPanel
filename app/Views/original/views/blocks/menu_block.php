<ul <?=($menu->id == 1) ? 'class="navbar-nav ms-auto"' : 'class="navbar-nav ms-auto menu-'.$menu->id.'"'?>>
    <?php		 
              for ($i = 0; $i < count($menu->main_menu, true); $i++) {

                    if(strpos($menu->main_menu[$i]->url, 'http') !== false){ 
                        $url = $menu->main_menu[$i]->url;
                    } else{
                        if($menu->main_menu[$i]->title == 'Home'){
							$url = ($menu->main_menu[$i]->url == '/') ? base_url() : base_url().''.$menu->main_menu[$i]->url;
						}
						else{
							$url = ($menu->main_menu[$i]->url == '/') ? base_url() : base_url().'pages/'.$menu->main_menu[$i]->url;
						}
                    } 

                   if (count($menu->main_menu[$i]->parent_menu, true) == 0): ?>
    <li class="nav-item dropdown"><a class="nav-link dropdown-toggle" href="<?=($url != '/') ? $url : base_url(); ?>">
            <?php echo
                              $menu->main_menu[$i]->title?></a></li>
    <?php else: ?>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="<?php echo base_url() . $url ?>" ><?php
                              echo $menu->main_menu[$i]->title ?></a>
        <ul class="navbar-nav ms-auto">
            <?php for ($b = 0; $b < count($menu->main_menu[$i]->parent_menu, true); $b++):

                            if(strpos($menu->main_menu[$i]->parent_menu[$b]->url, 'http') !== false){
                                    $url = $menu->main_menu[$i]->parent_menu[$b]->url;
                                } else{
                                    $url = base_url().$menu->main_menu[$i]->parent_menu[$b]->url;
                                } 

                                  if (!isset($menu->main_menu[$i]->parent_menu[$b]->parent_submenu)): ?>
            <li class="nav-item dropdown"><a class="nav-link dropdown-toggle" href="<?=$url?>"><?php echo
                                              $menu->main_menu[$i]->parent_menu[$b]->title ?></a></li>
            <?php else: ?>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="<?php echo base_url() .
                                              $url ?>"><?php echo
                                              $menu->main_menu[$i]->parent_menu[$b]->title ?></a>
                <?php if (isset($menu->main_menu[$i]->parent_menu[$b]->parent_submenu)):
                                        ?>
                <ul class="navbar-nav ms-auto">
                    <?php foreach
                                            ($menu->main_menu[$i]->parent_menu[$b]->parent_submenu
                                            as $par_sub) :

                                            if(strpos($par_sub->url, 'http') !== false){
                                            $url = $par_sub->url;
                                            } else{
                                                $url = base_url().$par_sub->url;
                                            } 

                                            ?>
                    <li class="nav-item dropdown"><a class="nav-link dropdown-toggle" href="<?php echo
                                             $url ?>"><?php echo
                                             $par_sub->title ?>
                        </a></li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>

            </li>
            <?php endif; ?>
            <?php endfor; ?>
        </ul>
    </li>
    <?php endif; ?>
    <?php } ?>
</ul>