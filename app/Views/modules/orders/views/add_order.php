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

$session = \Config\Services::session();

?>


<div class="box box-solid box-default">
    <header class="box-header "><?= lang('hd_lang.new_order') ?></header>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-6">
                <?php

                use App\Libraries\AppLib;
                use App\Models\Item;

                $applib = new AppLib();

                $categories = array();

                foreach (Item::list_items(array('deleted' => 'No')) as $item) {
                    if ($item->parent > 8) {
                        $categories[$item->cat_name][] = $item;
                    }
                }
                foreach ($categories as $key => $options) { ?>
                    <h2><?= $key ?></h2>
                    <table class="table table-striped table-bordered">
                        <?php $count = 0;
                        foreach ($options as $plan) {

                            $price = 0;
                            $period = '';
                            $count++;

                            if ($plan->total_cost > 0) :
                                $price = $plan->total_cost;
                                $period = lang('hd_lang.total_cost');
                            endif;


                            if ($plan->annually > 0) :
                                $price = $plan->annually;
                                $period = lang('hd_lang.annually');
                            endif;

                            if ($plan->semi_annually > 0) :
                                $price = $plan->semi_annually;
                                $period = lang('hd_lang.semi_annually');
                            endif;

                            if ($plan->quarterly > 0) :
                                $price = $plan->quarterly;
                                $period = lang('hd_lang.quarterly');
                            endif;

                            if ($plan->monthly > 0) :
                                $price = $plan->monthly;
                                $period = lang('hd_lang.monthly');
                            endif;
                        ?>


                            <tr>
                                <td><?= ucfirst($plan->item_name) ?></td>
                                <td><?= $applib->format_currency($price, $custom_helper->getconfig_item('default_currency')) ?></td>
                                <td><?= ucfirst($period) ?></td>
                                <td><a href="<?= base_url('cart/options/') ?><?= $plan->item_id ?>" class="btn btn-sm btn-success pull-right"><?= lang('hd_lang.select') ?></a></td>
                            </tr>
                        <?php } ?>
                    </table>
                <?php } ?>

            </div>

            <div class="col-md-6 inner-order">

                <form method="post" action="<?= base_url() ?>cart/add_domain" class="panel-body" id="search_form">
                    <input name="domain" type="hidden" id="domain">
                    <input name="price" type="hidden" id="price">
                    <input name="type" type="hidden" id="type">
                </form>

                <div class="row domain_search">
                    <div class="col-md-12">
                        <input type="text" id="searchBar" placeholder="<?= lang('hd_lang.enter_domain_name') ?>">
                        <select name="ext" id="ext" class="domain_ext">
                            <?php foreach (Item::get_domains() as $domain) { ?>
                                <option value="<?= $domain->item_name; ?>">.<?= $domain->item_name; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col-md-6">
                        <a id="existing" href="<?= base_url('cart/add_existing') ?>" class="btn btn-warning btn-block"><?= lang('hd_lang.existing_domain') ?></a>
                    </div>
                    <div class="col-md-3">
                        <input type="submit" class="btn btn-info btn-block" data="<?= lang('hd_lang.domain_transfer') ?>" id="Transfer" value="<?= lang('hd_lang.transfer') ?>" />
                    </div>
                    <div class="col-md-3">
                        <input type="submit" class="btn btn-primary btn-block" data="<?= lang('hd_lang.domain_registration') ?>" id="Search" value="<?= lang('hd_lang.register') ?>" />
                    </div>
                </div>
                <p>
                <div class="checking">
                    <img id="checking" src="<?= base_url() ?>resource/images/checking.gif" />
                </div>
                <div id="response"></div>
                <div id="continue"> <?= lang('hd_lang.select_hosting_below') ?><a href="<?= base_url() ?>cart/domain_only" class="btn btn-info"><?= lang('hd_lang.domain_only') ?></a> </span></div>

                </p>
                </form>

            </div>
        </div>
    </div>
</div>