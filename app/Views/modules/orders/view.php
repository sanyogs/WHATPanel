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
use App\Models\Client;
use App\Models\Order;
use App\Models\Item;
use App\Models\Plugin;
use App\Models\User;

use App\Helpers\custom_name_helper;

$custom_name_helper = new custom_name_helper();

?>
<?php //$client = User::is_client();?>
<?= $this->extend('layouts/users') ?>

<?= $this->section('content') ?>
<style>
.selected {
	background-color: lightgrey;
	}
</style>
<div class="box custom-invoice-view">

    <div class="box-header clearfix hidden-print">
        <?php $order = Order::viewItem($id); //echo "<pre>";print_r($order);die; ?>
		<div class="row">

            <div class="col-md-6 mx-auto">
				<div class="box-header b-b clearfix hidden-print p-3 custom-ticket-header">

			<span class='custom-ticket-dropdown'>
			<button class="btn btn-sm btn-<?= $custom_name_helper->getconfig_item('theme_color') ?> dropdown-toggle btn-responsive common-button" data-toggle="dropdown">
				<?= lang('hd_lang.change_status') ?>
				<span class="caret"></span></button>
					<ul class="dropdown-menu">
						<li class="<?= ($order->order_status_id == 5) ? 'selected' : '' ?>"><a href="<?= base_url() ?>orders/status/<?= $order->order_id ?>/5">Pending</a></li>
						<li class="<?= ($order->order_status_id == 17) ? 'selected' : '' ?>"><a href="<?= base_url() ?>orders/status/<?= $order->order_id ?>/17">Fraud</a></li>
						<li class="<?= ($order->order_status_id == 6) ? 'selected' : '' ?>"><a href="<?= base_url() ?>orders/status/<?= $order->order_id ?>/6">Activate</a></li>
						<li class="<?= ($order->order_status_id == 7) ? 'selected' : '' ?>"><a href="<?= base_url() ?>orders/status/<?= $order->order_id ?>/7">Cancel</a></li>
					</ul>
			</span>
		
		<?php if (User::is_admin()) { ?>
			<a href="<?= base_url() ?>orders/delete/<?= $order->order_id ?>" class="btn btn-sm btn-danger pull-right btn-responsive common-button" data-toggle="ajaxModal">
				<i class="fa fa-trash-o"></i> <?= lang('hd_lang.delete_order') ?></a>

		<?php } ?>


	</div>
				<div class="form-group">
                    <label class="control-label common-label"><?= lang('hd_lang.client_name') ?></label>
                    <div class="inputDiv">
                        <input class="form-control common-input" type="text" name="client_name" id="client_name" value="<?= $order->company_name ?>" disabled>
                    </div>
                </div>
				
				<div class="form-group">
                    <label class="control-label common-label"><?= lang('hd_lang.processed') ?></label>
                    <div class="inputDiv">
                        <input class="form-control common-input" type="text" name="processed" id="processed" value="<?= $order->processed ?>" disabled>
                    </div>
                </div>
				
				<div class="form-group">
                    <label class="control-label common-label"><?= lang('hd_lang.renewal_date') ?></label>
                    <div class="inputDiv">
                        <input class="form-control common-input" type="text" name="renewal_date" id="renewal_date" value="<?= $order->renewal_date ?>" disabled>
                    </div>
                </div>
				
				<div class="form-group">
                    <label class="control-label common-label"><?= lang('hd_lang.domain') ?></label>
                    <div class="inputDiv">
                        <input class="form-control common-input" type="text" name="domain" id="domain" value="<?= $order->domain ?>" disabled>
                    </div>
                </div>
			</div>
		</div>
	</div>
</div>
<?= $this->endSection() ?>