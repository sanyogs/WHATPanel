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
<div class="box">
    <div class="container inner">
        <div class="row">

            <div class="row">
                <div class="col-sm-6">
                    <h4><?=lang('hd_lang.enter_full_domain')?></h4>
                        <form method="post" action="<?=base_url()?>cart/existing_domain" class="panel-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <input type="text" name="domain" class="form-control" required>
                                </div>
                                <div class="col-md-3">
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