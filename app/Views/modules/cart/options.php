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

use App\Helpers\custom_name_helper;
use App\Helpers\whatpanel_helper;

$custom_helper = new custom_name_helper();
$helper = new whatpanel_helper();
$package = $package[0];
if (isset($item)) {
	$options = array('total_cost', 'monthly', 'quarterly', 'semi_annually', 'annually', 'biennially', 'triennially');
} else {
	header('Location: ' . base_url());
}
//echo"<pre>";print_r($package->total_cost);die;
?>


<?= $this->extend('layouts/users') ?>

<?= $this->section('content') ?>
<div class="box">
	<div class="container inner">
		<div class="row">
			<div class="col-md-6">
				<h2 class='common-h my-3'><?= $package->item_name ?></h2>
				<?php
				$attributes = array('class' => 'bs-example form-horizontal');
				echo form_open(base_url('cart/options'), $attributes);?>
				<input type="hidden" name="id" value="<?= $package->item_id ?>">
				<div class="row gap-3">
					<div class="col-md-7 col-sm-7">
						<?php
						$currency = $custom_helper->getconfig_item('default_currency');

						$currency_amt = json_decode($package->currency_amt, true);

						//  if(!empty($currency_pricing))
						//  {
						// 	$currency_pricing = $currency_amt[$currency];
						//  }
						//  else
						//  {
						//  	$currency_pricing = [
						//  		'monthly' =>  0.00,
						//  		'quarterly' =>  0.00,
						//  		'semi_annually' =>  0.00,
						//  		'annually' =>  0.00,
						//  		'biennially' =>  0.00,
						//  		'triennially' =>  0.00,
						// 	];
						// }
						$currency_pricing = $currency_amt[$currency];
					   	//print_r($currency_pricing);die;
					   
					   //echo $package->total_cost;die;
						?>
							<select class="form-control common-select" name="selected">
							<?php
							$totalCostPrice = null;
							$hasTotalCost = false;
								
								$hasOtherCost = true;

							// Identify the total cost price
							foreach ($options as $key => $value) {
								if (isset($currency_pricing[$value]) && $currency_pricing[$value] > 1) {
									if ($value === 'total_cost') { // Adjust this condition to match your logic for identifying total cost
										$totalCostPrice = $currency_pricing[$value];
										$hasTotalCost = true;
										break; // Stop searching as we've found the total cost
									}
								}
							}

							if ($hasTotalCost) {
								// If total cost is present, only show that option
								foreach ($options as $key => $value) {
									if($package->total_cost != 0.00) {
										if (isset($currency_pricing[$value]) && $currency_pricing[$value] == $totalCostPrice) {
										//if (isset($currency_pricing[$value])) {
											?>
							<option value="<?= $package->item_id ?>,<?= $package->item_name ?>,<?= $value ?>,<?= $currency_pricing[$value] ?>">
											<?= AppLib::format_currency($currency_pricing[$value], 'default_currency') ?> - <?= lang('hd_lang.'.$value) ?>							</option>
											<?php
											break; // Exit loop after showing the total cost price option
										}
									}
									else {
										if (isset($currency_pricing[$value])) {
										//if (isset($currency_pricing[$value])) {
											?>
							<option value="<?= $package->item_id ?>,<?= $package->item_name ?>,<?= $value ?>,<?= $currency_pricing[$value] ?>">
											<?= AppLib::format_currency($currency_pricing[$value], 'default_currency') ?> - <?= lang('hd_lang.'.$value) ?>							</option>
											<?php
											
										}
									}
								}
							} else {
								// If no total cost is present, show all available options
								foreach ($options as $key => $value) {
									if (isset($currency_pricing[$value]) && $currency_pricing[$value]) {
										?>
										<option value="<?= $package->item_id ?>,<?= $package->item_name ?>,<?= $value ?>,<?= $currency_pricing[$value] ?>">
											<?= AppLib::format_currency($currency_pricing[$value], 'default_currency') ?> - <?= lang('hd_lang.'.$value) ?>
										</option>
										<?php
									}
								}
							}
							?>

						</select>
					</div>
					<div class="col-md-3 col-sm-3">
						<input type="submit" class="btn btn-success common-button btn-block" value="<?= lang('hd_lang.continue') ?>">
					</div>
				</div>
				</form>
			</div>
		</div>
	</div>
	<div class="h_100"></div>
</div>
</div>
</div>
<?= $this->endSection() ?>