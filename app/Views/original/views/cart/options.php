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

$package = $package[0];
if (isset($item)) {
    $options = array('total_cost', 'monthly', 'quarterly', 'semi_annually', 'annually');
} else {
    header('Location: ' . base_url());
}
?>

<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container inner">
    <div class="box box-solid box-default">
        <div class="box-body">
            <div class="row">
                <div class="col-sm-6">

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
                            <div class="section-content">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h2><?= $package->item_name ?></h2>
                                        <?php
                                        $attributes = array('class' => 'bs-example form-horizontal');
                                        echo form_open(base_url() . 'cart/options', $attributes); ?>
                                        <div class="row">
                                            <div class="col-md-8">
                                                <?php

                                                $custom_helper = new custom_name_helper();

                                                $currency = $custom_helper->getconfig_item('default_currency');

                                                $currency_amt = json_decode($package->currency_amt, true);

                                                $currency_pricing = $currency_amt[$currency];

                                                ?>
                                                <select class="form-control" name="selected">
                                                    <?php

                                                    $interval = false;

                                                    $count = 0;
                                                    foreach ($options as $key => $value) {
                                                        foreach ($currency_pricing as $curr_pricing) {

                                                            if (isset($package->$value) && $package->$value > 0) {
                                                                $count++; ?>
                                                                <option value="<?= $package->item_id ?>,<?= $package->item_name ?>,<?= $value ?>,<?= $curr_pricing ?>"><?= AppLib::format_currency($curr_pricing, 'default_currency') ?> - <?= lang($value) ?></option>
                                                                <?php }
                                                        }
                                                    }

                                                    if ($count == 0) {
                                                        foreach ($options as $key => $value) {
                                                            foreach ($currency_pricing as $curr_pricing) {
                                                                if (isset($package->$value) && $package->$value == 0) { ?>
                                                                    <option value="<?= $package->item_id ?>,<?= $package->item_name ?>,<?= $value ?>,<?= $curr_pricing ?>"><?= AppLib::format_currency($curr_pricing, 'default_currency') ?> - <?= lang($value) ?></option>
                                                                    - <?= lang($key) ?></option>
                                                    <?php if ($package->$value == 0) break;
                                                                }
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <input type="submit" class="btn btn-success btn-block" value="<?= lang('hd_lang.continue') ?>">
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