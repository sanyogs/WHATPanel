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

$custom_helper = new custom_name_helper();

$db = \Config\Database::connect();
?>
<?= $this->extend('layouts/users') ?>

<?= $this->section('content') ?>

    <!-- dashboard-started -->
    <section id="pt-wrap">
        <div class="container px-0">
            <div class="pt-topbar-wrap">
                <div class="pt-title-wrap">
                    <h3>Pricing Tables</h3>
                    <p>Choose the pricing style which you want</p>
                </div>
                <div class="pt-last-wrap">
                    <a href="<?=base_url()?>settings/add_category" class="pt-add-item-div" data-toggle="ajaxModal">
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <path d="M10.6667 10.3333C10.6667 10.5101 10.5964 10.6797 10.4714 10.8047C10.3464 10.9298 10.1768 11 10 11H8.66667V12.3333C8.66667 12.5101 8.59643 12.6797 8.47141 12.8047C8.34638 12.9298 8.17681 13 8 13C7.82319 13 7.65362 12.9298 7.5286 12.8047C7.40357 12.6797 7.33333 12.5101 7.33333 12.3333V11H6C5.82319 11 5.65362 10.9298 5.5286 10.8047C5.40357 10.6797 5.33333 10.5101 5.33333 10.3333C5.33333 10.1565 5.40357 9.98695 5.5286 9.86193C5.65362 9.73691 5.82319 9.66667 6 9.66667H7.33333V8.33333C7.33333 8.15652 7.40357 7.98695 7.5286 7.86193C7.65362 7.73691 7.82319 7.66667 8 7.66667C8.17681 7.66667 8.34638 7.73691 8.47141 7.86193C8.59643 7.98695 8.66667 8.15652 8.66667 8.33333V9.66667H10C10.1768 9.66667 10.3464 9.73691 10.4714 9.86193C10.5964 9.98695 10.6667 10.1565 10.6667 10.3333ZM16 5.66667V12.3333C15.9989 13.2171 15.6474 14.0643 15.0225 14.6892C14.3976 15.3141 13.5504 15.6656 12.6667 15.6667H3.33333C2.4496 15.6656 1.60237 15.3141 0.97748 14.6892C0.352588 14.0643 0.00105857 13.2171 0 12.3333L0 4.33333C0.00105857 3.4496 0.352588 2.60237 0.97748 1.97748C1.60237 1.35259 2.4496 1.00106 3.33333 1H5.01867C5.32893 1.00026 5.63493 1.07236 5.91267 1.21067L8.01667 2.26667C8.10963 2.31128 8.21155 2.33408 8.31467 2.33333H12.6667C13.5504 2.33439 14.3976 2.68592 15.0225 3.31081C15.6474 3.93571 15.9989 4.78294 16 5.66667ZM1.33333 4.33333V5H14.544C14.4066 4.61139 14.1525 4.27473 13.8165 4.03606C13.4804 3.79739 13.0788 3.66838 12.6667 3.66667H8.31467C8.0044 3.66641 7.6984 3.5943 7.42067 3.456L5.31667 2.40333C5.22398 2.35757 5.12204 2.33362 5.01867 2.33333H3.33333C2.8029 2.33333 2.29419 2.54405 1.91912 2.91912C1.54405 3.29419 1.33333 3.8029 1.33333 4.33333ZM14.6667 12.3333V6.33333H1.33333V12.3333C1.33333 12.8638 1.54405 13.3725 1.91912 13.7475C2.29419 14.1226 2.8029 14.3333 3.33333 14.3333H12.6667C13.1971 14.3333 13.7058 14.1226 14.0809 13.7475C14.456 13.3725 14.6667 12.8638 14.6667 12.3333Z" fill="white" />
                            </svg>
                        </span>
                        <p>Add Item</p>
                    </a>
                </div>
            </div>
            <div class="pt-btn-list">
                <?php
                    $categories = $db->table('hd_categories')->get()->getResult();
                    $core_categories = array(6,7,8,9,10);
                    if (!empty($categories)) {
                        foreach ($categories as $key => $d) { if(!in_array($d->id, $core_categories)) { ?>
                <a href="<?=base_url()?>settings/edit_category/<?=$d->id?>" data-toggle="ajaxModal"><?=$d->cat_name?></a>
                <?php } } } ?>
            </div>
			<div class="pt-card-wraps">
                    <div class="card-wrap-div">
                    <h2 class="common-h">ONE</h2>
                    <img src="<?= base_url('backend_assets/img/card1.png') ?>" alt="card-1">
                    </div>
                    
                    <div class="card-wrap-div">
                    <h2 class="common-h">TWO</h2>
                    <img src="<?= base_url('backend_assets/img/card2.png') ?>" alt="card-2">
                    </div>
					
                    <div class="card-wrap-div">
                    <h2 class="common-h">THREE</h2>
                    <img src="<?= base_url('backend_assets/img/card3.png') ?>" alt="card-3">
                    </div>
					
                    <div class="card-wrap-div">
                    <h2 class="common-h">FOUR</h2>
                    <img src="<?= base_url('backend_assets/img/card4.png') ?>" alt="card-4">
                    </div>
					
            </div>
        </div>
    </section>
    <!--dashboard-ended-->

<?= $this->endSection() ?>