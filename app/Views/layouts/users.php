	<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */ //if (config_item('timezone')) { date_default_timezone_set(config_item('timezone')); }

use App\ThirdParty\MX\Modules;
use App\Models\User;
use App\Helpers\AuthHelper;
use App\Helpers\custom_name_helper;
use App\Modules as app_modules;
use App\Modules\sidebar\controllers\Sidebar;

$custom_name_helper = new custom_name_helper();

?>
<!DOCTYPE html>
<html lang="en">

	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title>DashBoard</title>
		<link rel="stylesheet" href="<?= base_url('backend_assets/css/main.css') ?>" />
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
		<link rel="stylesheet" href="<?= site_url('assets/css/main.css'); ?>" />
		<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css" />
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<!-- <link rel="stylesheet" href="<?=base_url()?>css/AdminLTE.min.css" type="text/css" /> -->
		<link rel="stylesheet" href="<?= base_url('css/custom.css') ?>" />
		<!-- <link rel="stylesheet" href="<?= base_url('css/skins/_all-skins.min.css') ?>" type="text/css" /> -->
		<link rel="stylesheet" href="<?= base_url('css/common-skin.css') ?>" type="text/css" />
		<!-- <link rel="stylesheet" href="<?= base_url('css/style.css') ?>" type="text/css" /> -->
		<link rel="stylesheet" href="<?= base_url('css/plugins/fuelux.min.css') ?>" type="text/css" />
		<link href="<?=base_url()?>js/nouislider/jquery.nouislider.min.css" rel="stylesheet"  type="text/css">
		<?php if (isset($editor)) { ?>
		<link rel="stylesheet" href="<?= base_url('css/plugins/summernote.css') ?>" type="text/css" />
		<?php } ?>
		<?php if (isset($datepicker)) { ?>
		<link rel="stylesheet" href="<?= base_url('js/slider/slider.css') ?>" type="text/css" />
		<link rel="stylesheet" href="<?= base_url('js/datepicker/datepicker.css') ?>" type="text/css" />
		<?php } ?>
		<?php if (isset($iconpicker)) { ?>
		<link rel="stylesheet" href="<?= base_url('js/iconpicker/fontawesome-iconpicker.min.css') ?>" type="text/css" />
		<?php } ?>
		<?php
		$family = 'Lato';
		$font = $custom_name_helper->getconfig_item('system_font');
		switch ($font) {
			case "open_sans": $family="Open Sans";  echo "<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=latin,latin-ext,greek-ext,cyrillic-ext' rel='stylesheet' type='text/css'>"; break;
			case "open_sans_condensed": $family="Open Sans Condensed";  echo "<link href='https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300,700&subset=latin,greek-ext,cyrillic-ext,latin-ext' rel='stylesheet' type='text/css'>"; break;
			case "roboto": $family="Roboto";  echo "<link href='https://fonts.googleapis.com/css?family=Roboto:400,300,500,700&subset=latin,greek-ext,cyrillic-ext,latin-ext' rel='stylesheet' type='text/css'>"; break;
			case "roboto_condensed": $family="Roboto Condensed";  echo "<link href='https://fonts.googleapis.com/css?family=Roboto+Condensed:400,300,700&subset=latin,greek-ext,cyrillic-ext,latin-ext' rel='stylesheet' type='text/css'>"; break;
			case "ubuntu": $family="Ubuntu";  echo "<link href='https://fonts.googleapis.com/css?family=Ubuntu:400,300,500,700&subset=latin,greek-ext,cyrillic-ext,latin-ext' rel='stylesheet' type='text/css'>"; break;
			case "lato": $family="Lato";  echo "<link href='https://fonts.googleapis.com/css?family=Lato:100,300,400,700&subset=latin,latin-ext' rel='stylesheet' type='text/css'>"; break;
			case "oxygen": $family="Oxygen";  echo "<link href='https://fonts.googleapis.com/css?family=Oxygen:400,300,700&subset=latin,latin-ext' rel='stylesheet' type='text/css'>"; break;
			case "pt_sans": $family="PT Sans";  echo "<link href='https://fonts.googleapis.com/css?family=PT+Sans:400,700&subset=latin,cyrillic-ext,latin-ext' rel='stylesheet' type='text/css'>"; break;
			case "source_sans": $family="Source Sans Pro";  echo "<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700&subset=latin,cyrillic-ext,latin-ext' rel='stylesheet' type='text/css'>"; break;
		}
		?>

		<style type="text/css">
			body { font-family: '<?=$family?>'; }
			.datepicker{ z-index:99999 !important; }

		</style>
	</head>

	<body style='height: 100vh;'>
		<section id="dashboardSection">
			<div class="container-fluid px-0">
				<?php  //echo Modules::run('sidebar/top_header');

				$sidebar = new Sidebar();
				$sidebar->top_header();

				?>
				<div class="BodyWrapper">
					<?php

					$custom = new custom_name_helper();
					$role = 'admin';

					if (User::is_admin()) {
						//echo Modules::run('sidebar/admin_menu');
						$sidebar = new Sidebar();
						$sidebar->admin_menu();
					} elseif (User::is_staff()) {
						$sidebar = new Sidebar();
						$sidebar->staff_menu();
					} elseif (User::is_client()) {
						$sidebar = new Sidebar();
						$sidebar->client_menu();
					} else {
						redirect('login');
					}
					?>
					<div class="col-md-9 col-sm-8 col-2 px-0 BodyDiv">
						<section id="menu-wrap" style='background-color:white; padding:10px;'>
						<div class="custom-opacity">
							<?= $this->renderSection('content') ?>
							</div>
						</section>
					</div>
				</div>
			</div>
			<p class='powere_d'> <a href=""  > Powered By What Panel </a> </p>
		</section>

		<script>
			var base_url = '<?= base_url() ?>';
		</script>
		<?php echo $sidebar->scripts(); ?>
		<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
		<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
		<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
		<script src="https://cdn.jsdelivr.net/jquery.validation/1.19.3/jquery.validate.min.js"></script>

		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"></script>

		<script src="<?= base_url('backend_assets/js/main.js') ?>"></script>
		<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
		<script src="<?= base_url('js/app.js') ?>" type="text/javascript"></script>
		<script src="<?= base_url('js/app.min.js') ?>" type="text/javascript"></script>
		<script src="<?= base_url('js/custom.js') ?>" type="text/javascript"></script>
		<script src="<?= base_url('js/bootstrap.min.js'); ?>" type="text/javascript"></script>
		<script src="<?= base_url('js/assets/script.js'); ?>" type="text/javascript"></script>
		<script src="<?= base_url('js/scroll/smoothscroll.js'); ?>" type="text/javascript"></script>
		<script src="<?= base_url('js/scroll/sortable/jquery-sortable.js'); ?>" type="text/javascript"></script>
		<script src="<?= base_url('js/scroll/datatables/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>
		<script src="<?= base_url('js/scroll/datatables/dataTables.bootstrap.min.js'); ?>" type="text/javascript"></script>
		<script src="<?= base_url('js/scroll/datatables/datetime-moment.js'); ?>" type="text/javascript"></script>
		<script src="<?= base_url('js/scroll/datatables/jquery.dataTables.min.js'); ?>" type="text/javascript"></script>
		<?php if (isset($nouislider)) { ?>
		<script type="text/javascript" src="<?=base_url()?>js/nouislider/jquery.nouislider.min.js"></script>
		<script type="text/javascript">
		(function($){
		"use strict";
		$(document).ready(function () {     

			var invoiceHeight = $('#invoice-logo-height').val();
			$('#invoice-logo-slider').noUiSlider({
					start: [ invoiceHeight ],
					step: 1,
					connect: "lower",
					range: {
						'min': 30,
						'max': 150
					},
					format: {
						to: function ( value ) {
							return Math.floor(value);
						},
						from: function ( value ) {
							return Math.floor(value);
						}
					}
			});
			$('#invoice-logo-slider').on('slide', function() {
				var invoiceHeight = $(this).val();
				var invoiceWidth = $('.invoice_image img').width();
				$('#invoice-logo-height').val(invoiceHeight);
				$('#invoice-logo-width').val(invoiceWidth);
				$('.noUi-handle').attr('title', invoiceHeight+'px').tooltip('fixTitle').parent().find('.tooltip-inner').text(invoiceHeight+'px');
				$('.invoice_image img').css('height',invoiceHeight+'px');
				$('#invoice-logo-dimensions').html(invoiceHeight+'px x '+invoiceWidth+'px');
			});

			$('#invoice-logo-slider').on('change', function() {
				var invoiceHeight = $(this).val();
				var invoiceWidth = $('.invoice_image img').width();
				$('#invoice-logo-height').val(invoiceHeight);
				$('#invoice-logo-width').val(invoiceWidth);
				$('.invoice_image').css('height',invoiceHeight+'px');
				$('#invoice-logo-dimensions').html(invoiceHeight+'px x '+invoiceWidth+'px');
			});

			$('#invoice-logo-slider').on('mouseover', function() {
				var invoiceHeight = $(this).val();
				$('.noUi-handle').attr('title', invoiceHeight+'px').tooltip('fixTitle').tooltip('show');
			});

		});
		})(jQuery);
		</script>
		<?php } ?>
		<?php if (isset($fuelux)) { ?>
		<script src="<?= base_url('js/fuelux/fuelux.min.js') ?>" type="text/javascript"></script>
		<?php } ?>
		<script>
			document.addEventListener('DOMContentLoaded', function() {
				// Find the flash message element
				var flashMessage = document.getElementById('flash-message');

				// Check if the element exists
				if (flashMessage) {
					// Set a timeout to remove the element after 5 seconds
					setTimeout(function() {
						flashMessage.style.display = 'none';
					}, 5000);
				}
			});
		</script>
		<script>
			$('#titleInput').on('keyup', function() {
				var path = $(this).val();
				path = path.replace(/ /g, "_").replace("/", "_").toLowerCase();
				$('#slugInput').val(path);
			});

			//accordian js start 
			$('.accordianArrow').click(function() {
				var accordianContainer = $(this).closest('.commonAccordian');
				accordianContainer.toggleClass('active');
				var accordianContent = accordianContainer.find('.accordianContent');
				if (accordianContent.height() > 0) {
					accordianContent.css('height', '0px');
					accordianContainer.find('.accordianBtn').removeClass('open');
				} else {
					var autoHeight = accordianContent.prop('scrollHeight') + 'px';
					accordianContent.css('height', autoHeight);
					accordianContainer.find('.accordianBtn').addClass('open');
				}
			});
			//accordian js end
		</script>


	</body>

</html>