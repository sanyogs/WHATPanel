<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

use App\Libraries\AppLib;

use App\Helpers\custom_name_helper;
use App\Helpers\whatpanel_helper;
use App\Models\User;

$custom_helper = new custom_name_helper();

$package = $package[0];
if (isset($item)) {
    $options = array('total_cost', 'monthly', 'quarterly', 'semi_annually', 'annually', 'biennially', 'triennially');
} else {
    header('Location: ' . base_url());
}
?>

<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<section class='custom-section-top-pad' >
 
</section>
<!-- TopBarText End -->

</section>

<div class="container inner custom-option-service">
    <div class="box box-solid box-default">
        <div class="box-body">
            <div class="row">
                <div class="col-sm-12">

                    <section id="pricing" class="bg-silver-light">
                        <div class="container">
                            <div class="section-title text-center mb-40">
                                <div class="row">
                                    <div class="col-md-8 col-md-offset-2">
                                        <h2 class="title text-uppercase text-theme-color-2 line-bottom-double-line-centered">
                                        </h2>
                                        <p class="font-13 mt-10"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="section-content ">
                                <div class="row justify-content-center align-items-center">
                                    <div class="col-10">
                                        <h2 class='service-heading'><?= $package->item_name ?></h2>
                                        <?php
                                        $attributes = array('class' => 'bs-example form-horizontal');
                                        echo form_open(base_url() . 'carts/options', $attributes); ?>
                                        <div class="row">
                                            <div class="col-8">
                                                <?php

                                                $custom_helper = new custom_name_helper();

                                                $currency = $custom_helper->getconfig_item('default_currency');

                                                $currency_amt = json_decode($package->currency_amt, true);

                                               // $currency_pricing = $currency_amt[$currency];
												
												if (isset($currency_amt[$currency])) {
													$currency_pricing = $currency_amt[$currency];
													// Your logic for when $curr is set in $currency_amt
												}
										
                                                ?>
                                                <select class="form-control service-select" name="selected">
                                                    <?php

                                                    $count = 0;
                                                    foreach ($options as $key => $value) {
                                                            if (isset($currency_pricing[$value]) && $currency_pricing[$value] > 0) {
                                                                $count++; ?>
                <option value="<?= $package->item_id ?>,<?= $package->item_name ?>,<?= $value ?>,<?= $currency_pricing[$value] ?>">
					<?= AppLib::format_currency($currency_pricing[$value], 'default_currency') ?> - <?= lang($value) ?>
				</option>
                                                                <?php }
                                                    }

                                                    if ($count == 0) {
                                                        foreach ($options as $key => $value) {
                                                                if (isset($currency_pricing[$value]) && $currency_pricing[$value] !== 0) { ?>
                                                                    <option value="<?= $package->item_id ?>,<?= $package->item_name ?>,<?= $value ?>,<?= $currency_pricing[$value] ?>"><?= AppLib::format_currency($currency_pricing[$value], 'default_currency') ?> - <?= lang($value) ?></option>
                                                    <?php if ($currency_pricing[$value] == 0) break;
                                                                }
                                                        }
                                                    }
                                                    ?>

                                                </select>
                                            </div>
                                            <div class="col-4">
                                                <input type="submit" class="btn btn-success btn-block service-submit" value="<?= lang('continue') ?>">
                                            </div>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>

            </div>
        </div>
        </section>


    </div>
</div>
</div>
</div>
</div>

<?= $this->endSection() ?>