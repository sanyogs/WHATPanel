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

$custom_name_helper = new custom_name_helper();
$whatpanel_helper = new whatpanel_helper();


$options['total_cost'] = 0;
$item = Item::view_item($id);

if (isset($item)) {
    $options = array('total_cost', 'monthly', 'quarterly', 'semi_annually', 'annually', 'biennially', 'triennially');
} else {
    header('Location: ' . base_url());
}

?>

<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<section class="custom-section-top-pad">

</section>


</section>

<section id="pricing" class="bg-silver-light">
    <div class="container">
        <div class="col-md-6 col-md-offset-2">
            <div class="box box-solid box-default">

                <div class="box-header">

                    <h2 class="title text-uppercase text-theme-color-2 line-bottom-double-line-centered">Addons</h2>
                    <p class="font-13 mt-10">The addons below may be added to the selected package</p>
                </div>
                <div class="box-body">

                    <h2><?= $item->item_name . " " . lang('hd_lang.addons') ?></h2>
                    <hr>
                    <?php

                    $attributes = array('class' => 'bs-example form-horizontal');
                    echo form_open(base_url() . 'cart/options', $attributes);

                    // echo "<pre>";print_r($addons);die;

                    foreach ($addons as $k => $package) { ?>
                        <div class="row">
                            <div class="col-md-6">
                                <h5><?= $package->item_name ?>
                            </div>
                            <div class="col-md-6">
                                <select class="form-control" name="selected[]">
                                    <?php
                                    $interval = false;
														 
                                    foreach ($options as $key => $value) {
                                        if (isset($package->$key) && $package->$key > 0) {
                                            $interval = true;
                                        }
                                    }
									$currency = $custom_name_helper->getconfig_item('default_currency');
														 
									$currency_amt = json_decode($package->currency_amt, true);
														 
									if (isset($currency_amt[$currency])) {
										$currency_pricing = $currency_amt[$currency];
										// Your logic for when $curr is set in $currency_amt
									}
									
                                    foreach ($options as $key => $value) {
										// if(isset($currency_pricing[$value]) && $currency_pricing[$value] > 0 || $interval == false && $value == 'total_cost') {
										if(isset($currency_pricing[$value]) && $currency_pricing[$value] > 0) {
								?>
                                <option value="<?=$package->item_id?>,<?=$package->item_name?>,<?=$value?>,<?=$currency_pricing[$value]?>">
                    				<?=AppLib::format_currency($currency_pricing[$value], 'default_currency')?> - <?= lang($value) ?>										</option>

                                    <?php if($package->$value == 0) break;
										}
                                    }

                                    ?>
                                </select>
                            </div>
                        </div>
                        <hr>
                    <?php } ?>
                    	<input type="submit" class="btn btn-success btn-block" value="<?= lang('hd_lang.continue') ?>">
                    </form>
                </div>
            </div>
        </div>

    </div>
</section>

<?= $this->endSection() ?>