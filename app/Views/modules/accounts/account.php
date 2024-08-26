<?= $this->extend('layouts/users') ?>

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

use App\Helpers\custom_name_helper;
use App\Libraries\AppLib;
use App\Models\Client;
use App\Models\Order;
use App\Models\User;
use App\ThirdParty\MX\Modules;

use Modules\directadmin\controllers\Directadmin;
use Modules\ispconfig\controllers\Ispconfig;
use Modules\plesk\controllers\Plesk;
use Modules\cwp\controllers\CWP;
use Modules\cpanel\controllers\Cpanel;
use Modules\cyberpanel\controllers\Cyberpanel;


$session = \Config\Services::session();

// Connect to the database
$dbName = \Config\Database::connect();

$ordermodel = new Order($dbName);

$client = new Client($dbName);

$order = Order::get_order($id);
// echo"<pre>";print_r($order);die;
$server = Order::get_server($order->server);

$package = $ordermodel->get_package($order->item_parent);

$client_cur = $client->get_by_user(session()->get('userid'))->currency;

$disk_used = 0;
$bw_used = 0;
$disk_limit = 0;
$bw_limit = 0;


$usage = Modules::run($order->server_type . '/get_usage', $order);

if (isset($usage['disk_limit']) && isset($usage['disk_used'])) {
	$disk_limit = $usage['disk_limit'];
	$disk_used = $usage['disk_used'];
}


if (isset($usage['bw_limit']) && isset($usage['bw_used'])) {
	$bw_limit = $usage['bw_limit'];
	$bw_used = $usage['bw_used'];
}


switch ($order->order_status) {
	case 'pending':
		$label = 'label-warning';
		break;

	case 'active':
		$label = 'label-success';
		break;

	case 'suspended':
		$label = 'label-danger';
		break;

	default:
		$label = 'label-default';
		break;
}

?>

<div class="box box-top custom-acc-account">
	<div class="box-header with-border">
		<?php if ($order->status_id == 6 && $package->allow_upgrade == 'Yes') { ?><a href="<?= base_url() ?>accounts/change?plan=<?= $order->item_parent ?>&account=<?= $id ?>" class="btn btn-sm btn-twitter common-button" data-toggle="ajaxModal"><?= lang('hd_lang.upgrade_downgrade') ?></a><?php } ?>

		<?php if (User::is_admin() || User::perm_allowed(User::get_id(), 'manage_accounts')) { ?>
			<div class="acc-account-btns">
				<?php if ($order->status_id != 6 && $order->status_id != 9) { ?>
					<a href="<?= base_url() ?>accounts/activate/<?= $id ?>" class="btn btn-sm btn-success common-button" data-toggle="ajaxModal">
						<i class="fa fa-check"></i><?= lang('hd_lang.activate') ?></a>
				<?php } else { ?>

					<a href="<?= base_url() ?>accounts/manage/<?= $id ?>" class="btn btn-sm btn-primary common-button">
						<i class="fa fa-edit"></i> <?= lang('hd_lang.manage') ?></a>

				<?php } ?>

				<a href="<?= base_url() ?>accounts/cancel/<?= $id ?>" class="btn btn-sm btn-warning common-button" data-toggle="ajaxModal">
					<i class="fa fa-minus-circle"></i> <?= lang('hd_lang.cancel') ?></a>

				<a href="<?= base_url() ?>accounts/delete/<?= $id ?>" class="btn btn-sm btn-danger common-button" data-toggle="ajaxModal">
					<i class="fa fa-trash-o"></i> <?= lang('hd_lang.delete') ?></a>
			</div>
		<?php } ?>
	</div>
	<!-- /.box-header -->
	<div class="box-body">

		<?php if (session()->getFlashdata('message')) : ?>
			<div class="alert alert-info alert-dismissible">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<?php echo session()->getFlashdata('message') ?>
			</div>
		<?php endif ?>

		<div class="row">
			<div class="col-lg-6 col-md-12">
				<div class="table-responsive">
					<table class="table table-striped common-table">
						<tr>
							<td colspan="2">
								<h2><?= $order->item_name ?></h2>
							</td>
						</tr>
						<tr>
							<td><?= lang('hd_lang.domain') ?></td>
							<td><?= $order->domain ?></td>
						</tr>
						<tr>
							<td><?= lang('hd_lang.status') ?></td>
							<td><span class="label <?= $label ?>"><?= ucfirst($order->order_status) ?></span></td>
						</tr>
						<tr>
							<td><?= lang('hd_lang.order_date') ?></td>
							<td><?= $order->date ?></td>
						</tr>
						<tr>
							<td><?= lang('hd_lang.billed') ?></td>
							<td><?= ucfirst($order->renewal) ?></td>
						</tr>
						<tr>
							<td><?= lang('hd_lang.next_renewal') ?></td>
							<td><?= $order->renewal_date ?></td>
						</tr>
						<tr>
							<td><?= lang('hd_lang.amount') ?></td>
							<td><?php $library = new custom_name_helper();

								if (!User::is_admin() && !User::is_staff()) {

									echo AppLib::format_currency(AppLib::client_currency($client_cur, $order->total_cost), "default_currency");
								} else {
									echo AppLib::format_currency($order->total_cost, 'default_currency');
								}  ?></td>
						</tr>
						<tr>
							<td><?= lang('hd_lang.storage_limit') ?></td>
							<td><?= $disk_limit ?>MB</td>
						</tr>
						<tr>
							<td><?= lang('hd_lang.bandwidth_limit') ?></td>
							<td><?= $bw_limit ?>MB</td>
						</tr>
						<tr>
							<td><?= lang('hd_lang.control_panel') ?></td>
							<td><?= isset($server->type) ? ucfirst($server->type) : '' ?> </td>
						</tr>
					</table>
				</div>
			</div>



			<?php if (is_numeric($disk_limit) && is_numeric($disk_used)) { ?>
				<div class="col-lg-3 col-md-6">
					<div class="chart-responsive">
						<canvas id="storage" height="200"></canvas>
					</div>
				</div>

			<?php }
			if (is_numeric($bw_limit) && is_numeric($bw_used)) {  ?>
				<div class="col-lg-3 col-md-6">
					<div class="chart-responsive">
						<canvas id="bandwidth" height="200"></canvas>
					</div>
				</div>

			<?php } ?>

		</div>


		<?php

		if (User::is_admin() || User::perm_allowed(User::get_id(), 'manage_accounts')) { ?>

			<?php if ($order->status_id != 9) { ?>
				<a href="<?= base_url() ?>accounts/suspend/<?= $id ?>" class="btn btn-sm btn-danger common-button" data-toggle="ajaxModal">
					<i class="fa fa-lock"></i> <?= lang('hd_lang.suspend') ?></a>

			<?php }
			if ($order->status_id == 9) { ?>
				<a href="<?= base_url() ?>accounts/unsuspend/<?= $id ?>" class="btn btn-sm btn-info common-button" data-toggle="ajaxModal">
					<i class="fa fa-unlock"></i> <?= lang('hd_lang.unsuspend') ?></a>

		<?php }
		} ?>

		<?php

$configuration = '';
// echo "<pre>";print_r($order);die;
		switch ($order->server) {
			case 'plesk':
				$plesk = new Plesk();
				$configuration = $plesk->client_options($id);
				break;
			case 'ispconfig':
				$ispconfig = new Ispconfig();
				$configuration = $ispconfig->client_options($id);
				break;
			case 'directadmin':
				$directadmin = new Directadmin();
				$configuration = $directadmin->client_options($id);
				break;
			case 'cyberpanel':
				$cyberpanel = new Cyberpanel();
				$configuration = $cyberpanel->client_options($id);
				break;
			case 'cwp':
				$cwp = new Cwp();
				$configuration = $cwp->client_options($id);
				break;
			case 'cpanel':
				$cpanel = new Cpanel();
				$configuration = $cpanel->client_options($id);
				break;
		}
		echo $configuration;
		?>
	</div>
	<!-- /.box-body -->
</div>


<script type="text/javascript" src="<?= base_url('js/charts/chartjs/Chart.min.js') ?>"></script>

<?php if (is_numeric($disk_limit) && is_numeric($disk_used) && $disk_limit > 0) {
	$used = ($disk_used / $disk_limit) * 100;
	$available = 100 - $used;
?>

	<script type="text/javascript">
		(function($) {
			"use strict";
			$(document).ready(function() {
				new Chart($("#storage"), {
					type: 'doughnut',
					data: {
						labels: ['<?= lang('hd_lang.available') ?>', '<?= lang('hd_lang.used') ?>'],
						datasets: [{
							label: "",
							backgroundColor: ["#00BCD4", "#9E9E9E"],
							data: [<?= $available ?>, <?= $used ?>]
						}]
					},
					options: {
						title: {
							display: true,
							text: '<?= lang('hd_lang.disk_usage') ?>'
						},
						cutoutPercentage: 80,
						animation: {
							duration: 2000,
							animateRotate: true,
							animateScale: false,
							easing: 'easeInOutCirc'

						}
					}
				});

			});
		})(jQuery);
	</script>

<?php }
if (is_numeric($bw_limit) && is_numeric($bw_used) && $bw_limit > 0) {
	$used = ($bw_used / $bw_limit) * 100;
	$available = 100 - $used;
?>

	<script type="text/javascript">
		(function($) {
			"use strict";
			$(document).ready(function() {
				new Chart($("#bandwidth"), {
					type: 'doughnut',
					data: {
						labels: ['<?= lang('hd_lang.available') ?>', '<?= lang('hd_lang.used') ?>'],
						datasets: [{
							label: "",
							backgroundColor: ["#8BC34A", "#FF6384"],
							data: [<?= $available ?>, <?= $used ?>]
						}]
					},
					options: {
						title: {
							display: true,
							text: '<?= lang('hd_lang.bandwidth_usage') ?>'
						},
						cutoutPercentage: 80,
						animation: {
							duration: 2000,
							animateRotate: true,
							animateScale: false,
							easing: 'easeInCubic'

						}
					}
				});

			});
		})(jQuery);
	</script>

<?php } ?>

<?= $this->endSection() ?>