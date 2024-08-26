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
use App\Libraries\AppLib;
use App\Models\Item;

$custom_helper = new custom_name_helper();
$session = \Config\Services::session();

?>
<?= $this->extend('layouts/users') ?>
<?= $this->section('content') ?>
<div class="box box-solid box-default order-select-client">
	<div class="hs-title-wrap">
		<h3><?= lang('hd_lang.new_order') ?></h3>
	</div>
	<div class="box-body p-2 pt-0">
		<div class="row">
			<div class="col-lg-6 col-md-12 ">
				<?php

				use App\Modules\items\controllers\Items;

				$applib = new AppLib();
				$categories = array();
				foreach (Item::list_items(array('deleted' => 'No')) as $item) {
					if ($item->parent > 8) {
						$categories[$item->cat_name][] = $item;
					}
				}

				$currency = $custom_helper->getconfig_item('default_currency');

				foreach ($categories as $key => $options) { ?>
					<h2 class='common-h'><?= $key ?></h2>
					<div class="table-responsive">
						<table class="hs-table mt-2 mb-4">
							<?php

							$count = 0;

							$currency_amt = [];

							// First loop to decode the currency amounts
							foreach ($options as $option) { //echo"<pre>";print_r($option);
								if (is_object($option)) {
									// Decode and store in the array to use later
									$currency_amt = json_decode($option->currency_amt, true);
								}
							}
						

							foreach ($options as $plan) {
								$price = 0;
								$period = '';
								$count++;
								
								$currency_amt = json_decode($plan->currency_amt, true);
								if ($plan->total_cost > 0) :
									$price = $plan->unit_cost;
									$period = lang('hd_lang.total_cost');
								endif;

								if (isset($currency_amt[$currency])) {

									$currency_pricing = $currency_amt[$currency];

									$count++;

									if ($currency_pricing['annually'] > 0) :
										$price = $currency_pricing['annually'];
										$period = lang('hd_lang.annually');
									endif;

									if ($currency_pricing['semi_annually'] > 0) :
										$price = $currency_pricing['semi_annually'];
										$period = lang('hd_lang.semi_annually');
									endif;

									if ($currency_pricing['quarterly'] > 0) :
										$price = $currency_pricing['quarterly'];
										$period = lang('hd_lang.quarterly');
									endif;

									if ($currency_pricing['monthly'] > 0) :
										$price = $currency_pricing['monthly'];
										$period = lang('hd_lang.monthly');
									endif;
								}
							?>
								<tr>
									<td><?= ucfirst($plan->item_name) ?></td>
									<td><?= $applib->format_currency($price, 'default_currency') ?></td>
									<td><?= ucfirst($period) ?></td>
									<td><a href="<?= site_url('cart/options/' . $plan->item_id) ?>" class="btn btn-sm btn-success common-button"><?= lang('hd_lang.select') ?></a></td>
								</tr>
							<?php
							} ?>
						</table>
					</div>
				<?php } ?>
			</div>
			<div class="col-lg-6 col-md-12 mt-5">
				<form method="post" action="<?= base_url() ?>cart/add_domain" class="panel-body" id="search_form">
					<input name="domain" type="hidden" id="domain" class='common-input'>
					<input name="price" type="hidden" id="price" class='common-input'>
					<input name="type" type="hidden" id="type" class='common-input'>
					<input name="registrar_val" type="hidden" id="registrar_val" class='common-input'>
				</form>
				<div class="row domain_search">
					<div class="col-md-12 d-flex">
						<div class="col-8 mx-2">
							<input type="text" id="searchBar" placeholder="<?= lang('hd_lang.enter_domain_name') ?>" class='common-input'>
						</div>
						<div class="col-3 mx-2">
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

				<div class="row gap-3 ps-3">
					<input type="submit" class="btn btn-info btn-block common-button col-3" data="<?= lang('hd_lang.domain_transfer') ?>" id="Transfer" value="<?= lang('hd_lang.transfer') ?>" />
					<input type="submit" class="btn btn-primary btn-block common-button col-3" data="<?= lang('hd_lang.domain_registration') ?>" id="Search" value="<?= lang('hd_lang.register') ?>" />

				</div>
				<p>
				<div class="checking">
					<img id="checking" src="<?= base_url('images/checking.gif') ?>" style="height: 19px;display: none;" />
				</div>
				<div id="response" class='orders-response-alert' style="display: none;"></div>
				<div id="continue" class="myError" style="display: none;">
					<?= lang('hd_lang.select_hosting_below') ?>
					<a href="<?= base_url() ?>cart/domain_only" class="btn btn-info common-button"><?= lang('hd_lang.domain_only') 							?></a>
				</div>
				</form>
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
					$.fn.continueOrder = function () {
         
       if (window.location.href == base_url + 'cart/domain') {
            $('.search_form').submit();
        }

        else {
            $.ajax({
                url: base_url + 'cart/add_domain',
                type: 'post',
                data: $("#search_form").serialize(),
                success: function (data) {
                    $('#response').slideUp(500);
                    $('#continue').slideDown(500);
                },
                error: function (data) {

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
			<script>
				$(document).ready(function() {
					// Add event listener for click event of the element with id "add_available"
					$('#add_available').on('click', function() {
						// Show the div with id "continue" when the element is clicked
						$('#continue').show();
					});

					// Optionally, if you want to hide the div when the element is not clicked
					// $('#add_available').on('mouseleave', function() {
					//     $('#continue').hide();
					// });
				});
			</script>
		</div>
	</div>
<?= $this->endSection() ?>