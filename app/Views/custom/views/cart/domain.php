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
?>

<section id="pricing" class="bg-silver-light">
    <div class="container inner">
        <div class="box box-solid box-default">

            <div class="box-body">
                <div class="section-title text-center mb-40">
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                            <h2 class="title text-uppercase text-theme-color-2 line-bottom-double-line-centered">
                                <?=lang('hd_lang.domain_options')?>
                            </h2>
                            <p class="font-13 mt-10"><?=lang('hd_lang.new_or_existing_domain')?></p>
                            <hr>
                            <a class="btn btn-primary"
                                href="<?=base_url()?>cart/add_existing"><?=lang('hd_lang.use_existing_domain')?></a>
                            <hr>
                        </div>
                    </div>
                </div>
                <div class="section-content">
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                            <div class="row">
                                <div class="col-sm-6">
                                    <form action="<?=base_url()?>cart/add_domain" class="search_form" method="post"
                                        id="search_form cart">
                                        <input name="domain" type="hidden" id="domain">
                                        <input name="price" type="hidden" id="price">
                                        <input name="type" type="hidden" id="type">
                                        <input id="searchBar" type="text" placeholder="Enter your domain name..."
                                            class="form-control" style="width:100%;">
                                </div>
                                <div class="col-sm-2">
                                    <span class="input-group-btn">
                                        <select class="btn btn-default" name="ext" id="ext">
                                            <?php foreach($domains as $domain) { ?>
                                            <option value="<?=$domain->item_name;?>">.<?=$domain->item_name;?></option>
                                            <?php } ?>
                                        </select>
                                    </span>
                                </div>
                                <div class="col-md-4">
                                    <div class="btn-group">
                                        <button class="btn btn-warning" type="submit" id="Transfer"
                                            data="<?=lang('hd_lang.domain_transfer')?>"><?=lang('hd_lang.transfer')?></button>
                                        <button class="btn btn-success" type="submit" id="btnSearch"
                                            data="<?=lang('hd_lang.domain_registration')?>"><?=lang('hd_lang.register')?></button>
                                    </div>

                                    <img id="checking" src="<?=base_url()?>images/checking.gif" />
                                </div>
                            </div>

                            <hr>

                            </form>
                        </div>

                    </div>

                    <div id="response"></div>

                </div>
            </div>
        </div>

    </div>
</section>