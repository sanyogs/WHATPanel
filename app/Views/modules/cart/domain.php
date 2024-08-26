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
use App\Models\Item;
use App\Models\Plugin;
use App\Helpers\custom_name_helper;
use App\Modules\items\controllers\Items;
use App\Modules\sidebar\controllers\Sidebar;

$custom_helper = new custom_name_helper();
$session = \Config\Services::session();

$applib = new AppLib();
$categories = array();
foreach (Item::list_items(array('deleted' => 'No')) as $item) {
    if ($item->parent > 8) {
        $categories[$item->cat_name][] = $item;
    }
}

$currency = $custom_helper->getconfig_item('default_currency');
$session = session();
// Retrieve 'cart' data from the session
$cart = $session->get('cart');
$total = 0;
?>
<?= $this->extend('layouts/users') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-12 p-4">
        <form method="post" action="<?= base_url() ?>cart/add_domain" class="panel-body" id="search_form">
            <input name="domain" type="hidden" id="domain" class='common-input'>
            <input name="price" type="hidden" id="price" class='common-input'>
            <input name="type" type="hidden" id="type" class='common-input'>
            <input name="registrar_val" type="hidden" id="registrar_val" class='common-input'>
        </form>
        <div class="row domain_search">
            <div class="col-sm-6 mb-3 ">
                <a href="<?= base_url('cart/add_existing') ?>"><button class="common-button" type="submit" id="Existing"><?= lang('hd_lang.use_existing_domain') ?></button></a>
            </div>
            <div class="col-md-12 d-flex">
                <div class="col-sm-6 col-8">
                    <input type="text" id="searchBar" placeholder="<?= lang('hd_lang.enter_domain_name') ?>" class='common-input'>
                </div>
                <div class="col-sm-2 col-3 ">
                    <select name="ext" id="ext" class="domain_ext common-select">
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
        <div class="row">
            <div class="col-sm-3 col-6 ">
                <input type="submit" class="btn btn-info btn-block common-button w-100" data="<?= lang('hd_lang.domain_transfer') ?>" id="Transfer" value="<?= lang('hd_lang.transfer') ?>" />
            </div>
            <div class="col-sm-3 col-6 ">
                <input type="submit" class="btn btn-primary btn-block common-button w-100 bg-success text-white" data="<?= lang('hd_lang.domain_registration') ?>" id="Search" value="<?= lang('hd_lang.register') ?>" />
            </div>
        </div>

        <div class="checking">
            <img id="checking" src="<?= base_url('images/checking.gif') ?>" style="height: 19px;display: none;" />
        </div>
        <div id="response"></div>
        <div id="continue" class='common-h mt-3'> <?= lang('hd_lang.select_hosting_below') ?><a href="<?= base_url() ?>cart/domain_only" class="btn btn-info common-button"><?= lang('hd_lang.domain_only') ?></a></div>

    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
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
                var price = $(this).find('option:selected').val();
                $('#price').val(price);
            });

            $.fn.continueOrder = function() {

                if (window.location.href == base_url + 'cart/domain') {
                    $('#search_form').submit();
                } else {
                    $.ajax({
                        url: base_url + 'cart/add_domain',
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
            $.ajax({
                url: '<?= base_url('domains/check_availability') ?>',
                type: 'POST',
                data: {
                    domain: domain_name,
                    type: type,
                    ext: tlds
                },
                dataType: 'json',
                success: function(data) {
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