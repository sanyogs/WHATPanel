<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */ 
    $cart = session()->get('cart'); 
    $total = 0;
?>

<section class='custom-section-top-pad' >
 
 </section>
 <!-- TopBarText End -->
 
 </section>

<section id="pricing" class="bg-silver-light custom-cart-existing-domain">
    <div class="container inner">
    <div class="box box-solid box-default">    
        <div class="box-body p-3">
        <div class="section-title  mb-40">
            <div class="row">
                <div class="col-md-8 mx-auto my-3 mt-5">
                    <h2 class="title text-uppercase text-theme-color-2 line-bottom-double-line-centered cc-heading"><?=lang('hd_lang.domain')?></h2>
                   
                </div>
            </div>
        </div>
        <div class="section-content mb-5">
            <div class="row"> 
                <div class="col-md-8 mx-auto">
                    <h4 class='custom-h4'><?=lang('hd_lang.enter_full_domain')?></h4>
                        <form method="post" action="<?=base_url()?>cart/existing_domain" class="panel-body">
                            <div class="row">
                                <div class="col-8">
                                    <input type="text" name="domain" class="form-control" required>
                                </div>
                                <div class="col-3">
                                    <input type="submit" class="btn btn-primary btn-block" name="type"
                                        value="<?= lang('hd_lang.continue') ?>" />
                                </div>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
<?= $this->endSection('content') ?>