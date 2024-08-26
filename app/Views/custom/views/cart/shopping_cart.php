<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */ 
    $cart = $this->session->userdata('cart'); 
    $total = 0;
    $tax = false; 
    $tax_total = 0;   
    foreach($cart as $row) {  
        if(floatval($row->tax) > 0)
        {
            $tax_total += $row->tax;
            $tax = true;
        }
    }  
?>

<section id="pricing" class="bg-silver-light">
    <div class="container inner">

        <div class="section-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-solid box-default">
                        <div class="box-body">

                            <table class="table table-bordered shopping_cart" id="shopping_cart">
                                <thead>
                                    <tr>
                                        <th><?=lang('hd_lang.item')?></th>
                                        <th><?=lang('hd_lang.description')?></th>
                                        <th><?=lang('hd_lang.billed')?></th>
                                        <th><?=lang('hd_lang.amount')?></th>
                                        <?php if($tax){ ?>
                                        <th><?=lang('hd_lang.tax')?></th>
                                        <?php } ?>
                                        <th><?=lang('hd_lang.total')?></th>
                                        <th class="w_100"><?=lang('hd_lang.action')?></th>
                                    </tr>
                                </thead>
                                <tbody id="domains">

                                    <?php 
                    foreach($cart as $row) {  
                        $total += ($tax) ? $row->price + $row->tax : $row->price; 
                    ?>
                                    <tr>
                                        <td><?=$row->name?></td>
                                        <td><?=($row->domain == 'promo') ? $row->item : $row->domain?></td>
                                        <td><?=isset($row->years) ? $row->years . lang('hd_lang.years') : lang($row->renewal)?>
                                        </td>
                                        <td><?=($row->domain != 'promo') ? Applib::format_currency(config_item('default_currency'), $row->price) :''?>
                                        </td>
                                        <?php if($tax){ ?>
                                        <td><?=($row->domain != 'promo') ? Applib::format_currency(config_item('default_currency'), $row->tax) :''?>
                                        </td>
                                        <?php } ?>
                                        <td><?=Applib::format_currency(config_item('default_currency'), ($tax) ? $row->price + $row->tax : $row->price)?>
                                        </td>
                                        <td><a class="btn btn-block btn-sm btn-default"
                                                href="<?=base_url()?>cart/remove/<?=$row->cart_id?>"><?=lang('hd_lang.remove')?></a>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                    <tr>
                                        <td colspan="<?=($tax)?'5':'4'?>"><span
                                                class="pull-right"><strong><?=lang('hd_lang.total')?></strong></span></td>
                                        <td><?=Applib::format_currency(config_item('default_currency'), $total)?></strong>
                                        </td>
                                        <td><a class="btn btn-danger btn-sm btn-block"
                                                href="<?=base_url()?>cart/remove_all"><?=lang('hd_lang.remove_all')?></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="<?=($tax)?'6':'5'?>"><a class="btn btn-sm btn-primary"
                                                href="<?=(!User::is_logged_in()) ? base_url() .'cart/hosting_packages' : base_url() .'orders/add_order'?>"><?=lang('hd_lang.continue_shopping')?></a>
                                            <form class="pull-right" action="<?=base_url() .'cart/validate_code'?>"
                                                method="post">
                                                <label class="labels"><?=lang('hd_lang.discount_code')?></label> <input
                                                    type="text" name="code"><button
                                                    class="btn btn-sm btn-info"><?=lang('hd_lang.validate')?></button>
                                            </form>
                                        </td>
                                        <td><a href="<?=base_url()?>cart/checkout"
                                                class="btn btn-success btn-sm btn-block"
                                                id="submitOrder"><?=lang('hd_lang.submit_order')?></a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>