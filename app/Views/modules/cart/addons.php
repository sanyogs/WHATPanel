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
use App\Helpers\whatpanel_helper;

use App\Models\Item;

use App\Libraries\AppLib;

$custom_helper = new custom_name_helper();
$helper = new whatpanel_helper();

$item = Item::view_item($id);

// echo "<pre>";print_r($item);die;

if ($item) {
    $options = ['total_cost', 'monthly', 'quarterly', 'semi_annually', 'annually', 'biennially', 'triennially'];
} else {
    header('Location: ' . base_url());
    exit();
}
?>

<?= $this->extend('layouts/users') ?>

<?= $this->section('content') ?>

<section id="pricing" class="bg-silver-light">
    <div class="container">
        <div class="col-md-6 col-md-offset-2">
            <div class="box box-solid box-default">

                <div class="box-header">

                    <h2 class="title text-uppercase text-theme-color-2 line-bottom-double-line-centered">Addons</h2>
                    <p class="font-13 mt-10">The addons below may be added to the selected package</p>
                </div>
                <div class="box-body">

                    <h2><?php echo $item->item_name . " " . lang('hd_lang.addons') ?></h2>
                    <hr>
                    <?php

                    $attributes = ['class' => 'bs-example form-horizontal'];
                    echo form_open(base_url() . 'cart/options', $attributes);

                    foreach ($addons as $k => $package) { ?>
                        <div class="row">
                            <div class="col-md-6">
                                <h5><?php echo $package->item_name ?></h5>
                            </div>
                            <div class="col-md-6">
                                <select class="form-control" name="selected[]">
                                    <?php
                                    $interval = false;

                                    $currency_pricing = null;

                                    $currency = $custom_helper->getconfig_item('default_currency');

                                    $currency_amt = json_decode($package->currency_amt, true);

                                    $currency_pricing = $currency_amt[$currency];

                                    // echo "<pre>";print_r($options);die;
														 
														 $totalCostPrice = null;
							$hasTotalCost = false;
								
								$hasOtherCost = true;

							// Identify the total cost price
							foreach ($options as $key => $value) {
								if (isset($currency_pricing[$value]) && $currency_pricing[$value] > 1) {
									if ($value === 'total_cost') { // Adjust this condition to match your logic for identifying total cost
										$totalCostPrice = $currency_pricing[$value];
										$hasTotalCost = true;
										break; // Stop searching as we've found the total cost
									}
								}
							}
														 
														 //echo $hasTotalCost;die;
														 if ($hasTotalCost) {
								// If total cost is present, only show that option
								foreach ($options as $key => $value) {
									//echo 123;die;
									if($package->total_cost != 0.00) {
										//echo 456;die;
										if (isset($currency_pricing[$value]) && $currency_pricing[$value] == $totalCostPrice) { 
										//if (isset($currency_pricing[$value])) {
											?>
							<option value="<?= $package->item_id ?>,<?= $package->item_name ?>,<?= $value ?>,<?= $currency_pricing[$value] ?>">
											<?= AppLib::format_currency($currency_pricing[$value], 'default_currency') ?> - <?= lang('hd_lang.'.$value) ?>							</option>
											<?php
											break; // Exit loop after showing the total cost price option
										}
									}
									else {
										//echo 111;die;
										if (isset($currency_pricing[$value])) {
										//if (isset($currency_pricing[$value])) {
											?>
							<option value="<?= $package->item_id ?>,<?= $package->item_name ?>,<?= $value ?>,<?= $currency_pricing[$value] ?>">
											<?= AppLib::format_currency($currency_pricing[$value], 'default_currency') ?> - <?= lang('hd_lang.'.$value) ?>							</option>
											<?php
											
										}
									}
								}
							}
														 else {
															 //echo "<pre>";print_r($currency_pricing);die;

                                    foreach ($options as $value) { if(isset($currency_pricing[$value])) {  ?>
                                            <option value="<?=$package->item_id?>,<?=$package->item_name?>,<?=$value?>,<?=$currency_pricing[$value]?>">
                                                <?=AppLib::format_currency($currency_pricing[$value], 'default_currency')?> - <?= lang($value) ?>
                                            </option>
                                        <?php 
                                        if($currency_pricing[$value] == '') break;
                                    }
                                }
														 }
                                    ?> 
                                </select>
                            </div>
                        </div>
                        <hr>
                    <?php } ?>
                    <a href="<?=base_url('cart/shopping_cart')?>">
                        <input class="btn btn-success btn-block" value="<?= lang('hd_lang.skip') ?>">
                    </a>
                    <input type="submit" class="btn btn-success btn-block" value="<?= lang('hd_lang.continue') ?>">
                    </form>
                </div>
            </div>
        </div>

    </div>
</section>

<?= $this->endSection() ?>