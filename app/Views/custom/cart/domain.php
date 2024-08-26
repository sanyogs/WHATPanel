<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */
$session = \Config\Services::session();

use App\Models\Item;

use App\Modules\items\controllers\Items;

$cart = $session->get('cart');
$total = 0;
?>

<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<section class='custom-section-top-pad' >
 
</section>
<!-- TopBarText End -->

</section>

<div class="row custom-cart-domain" >
    <div class="col-md-6 inner-order my-5  mx-auto">
        <form method="post" action="<?= base_url() ?>carts/add_domain" class="panel-body" id="search_form">
            <input name="domain" type="hidden" id="domain" class='common-input'>
            <input name="price" type="hidden" id="price" class='common-input'>
            <input name="type" type="hidden" id="type" class='common-input'>
            <input name="registrar_val" type="hidden" id="registrar_val" class='common-input'>
        </form>
        <div class="row domain_search">
            <div class="col-sm-4">
                <a href="<?= base_url('carts/add_existing') ?>"><button class=" bg-warning" type="submit" id="Existing" ><?= lang('hd_lang.use_existing_domain') ?></button></a>
            </div>
            <div class="col-sm-12 d-flex  align-items-center">
                <div class="col-8" style='margin-right: .5rem !important;'>
                    <input type="text" id="searchBar" placeholder="<?= lang('hd_lang.enter_domain_name') ?>" >
                </div>
                <div class="col-2 mx-2">
                    <select name="ext" id="ext" class="domain_ext ">
                        <?php
                        $controller = new Items();
                        $result = $controller->get_domains();
                        foreach ($result as $domain) { ?>
                            <option value="<?= $domain; ?>"><?= $domain; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        </div>
        
        <div class="row m-0">
            <div class="col-6">
                <input type="submit" class="btn btn-info btn-block  w-100" data="<?= lang('hd_lang.domain_transfer') ?>" id="Transfer" value="<?= lang('hd_lang.transfer') ?>" />
            </div>
            <div class="col-6">
                <input type="submit" class="btn btn-primary btn-block w-100" data="<?= lang('hd_lang.domain_registration') ?>" id="Search" value="<?= lang('hd_lang.register') ?>" style="background-color: green !important;color: white !important;" />
            </div>
        </div>
       
        <div class="checking mx-2">
            <img id="checking" src="<?= base_url('images/checking.gif') ?>" style="height: 19px;display: none;" />
        </div>
        <div id="response" class='mx-2 orders-response-alert'></div>
        <div id="continue" class=' mx-2'> <?= lang('hd_lang.select_hosting_below') ?><a href="<?= base_url() ?>cart/domain_only" class="btn btn-info "><?= lang('hd_lang.domain_only') ?></a></div>
        
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script>
        var base_url = '<?= base_url() ?>';
    </script>
    <script>
        function checkName(name) {
            if (name.indexOf('.') !== -1) {
                swal("Invalid Domain!", "Please enter the name only and select the extension", "warning");
                $('#checking').hide();
                $('#btnSearch').show();
                $('#Transfer').show();
                return false;
            }
            return true;
        }


        $(document).ready(function() {

            $(document).on('change', '#domain_price', function() {
                console.log(2131);
                var price = $(this).find('option:selected').val();
                $('#price').val(price);
            });

            $.fn.continueOrder = function() {

                if (window.location.href == base_url + 'carts/domain') {
                    $('#search_form').submit();
                } else {
                    $.ajax({
                        url: base_url + 'carts/add_domain',
                        type: 'post',
                        data: $("#search_form").serialize(),
                        success: function(data) {
                            $('#response').slideUp(500);
                            $('#continue').slideDown(500);
                        },
                        error: function(data) {

                        }
                    });
                }
            }

            $('#Transfer').on('click', function(e) {
                e.preventDefault();
                name = $('#searchBar').val();
                if (checkName(name)) {
                    type = $('#Transfer').attr('data');
                    if (name != '') {
                        var ext = $('#ext').find('option:selected').val();
                        domain_name = name + ext;
                        $(this).hide();
                        $('#checking').show();
                        checkAvailability();
                    } else {
                        swal("Empty Search!", "Please enter a domain name", "warning");
                    }
                }
            });
            $('#btnSearch, #Search').on('click', function(e) {
                e.preventDefault();
                name = $('#searchBar').val();
                if (checkName(name)) {
                    type = $('#Search').attr('data');
                    if (type == undefined) {
                        type = $('#btnSearch').attr('data');
                    }
                    if (name != '') {
                        var ext = $('#ext').find('option:selected').val();
                        domain_name = name + ext;
                        tlds = ext;
                        $(this).hide();
                        $('#checking').show();
                        checkAvailability();
                    } else {
                        swal("Empty Search!", "Please enter a domain name", "warning");
                    }
                }
            });
        });

        function checkAvailability() {
            var registrar = $('#registrar').find('option:selected').val();
			//alert(123);
            $.ajax({
                url: '<?= base_url('domainsss/check_availability') ?>',
                type: 'POST',
                data: {
                    domain: domain_name,
                    type: type,
                    ext: tlds
                },
                dataType: 'json',
                success: function(data) {
                    console.log(data);
                    $('#domain').val(data.domain);
                    $('#price').val(data.price);
                    $('#type').val(type);
                    $('#registrar').val(registrar);
                    $('#new_domain').val(1);
                    $('#checking').hide();
                    $('#btnSearch').show();
                    $('#Search').show();
                    $('#continue').hide();
                    $('#searchBar').val('');
                    $('#Transfer').show();
                    $('#textBar').val('');
                    $('#response').html(data.result).slideDown(500);
                },
                error: function(data) {
                    console.log(data);
                    $('#checking').hide();
                    $('#btnSearch').show();
                    $('#Search').show();
                    $('#Search').show();
                    $('#Transfer').show();
                }
            });
        }
    </script>
</div>

<?= $this->endSection() ?>