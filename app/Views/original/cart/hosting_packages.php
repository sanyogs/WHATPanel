<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

use App\Models\Item;
use App\Models\App;

use App\Helpers\custom_name_helper;

use App\Libraries\AppLib;

$custom_name_helper = new custom_name_helper();

?>

<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- TopbarText Start -->
<section class='custom-section-top-pad' >
 
</section>
<!-- TopBarText End -->

</section>
<!-- Topbar End -->

<div id="plansSection" class="p-0" >
	<div class="container inner">
		<?php
		$categories = array();

		foreach (Item::list_items(array('deleted' => 'No', 'display' => 'Yes')) as $item) {
			if ($item->cat_name !== 'Domains') {
				$categories[$item->cat_name][] = $item;
			}
		}

		foreach ($categories as $key => $items) { ?>

			<h2 class='text-center m-5 p-0'><?= $key ?></h2>

			<div class="row pricing-row pb-5">
				<?php
				$count = 0;
				foreach ($items as $plan) {

					$style = $plan->pricing_table;

					$price = 0;
					$period = '';
					$count++;

				?>
					<div class="plansWrapper m-0">
						<div class="planSlider">
							<?php

							if ($style == '') {
								$style = 'default';
							}

							switch ($style) {
								case 'one':

							?>

										<div class="col-sm-4 ">
											<div class="planningWrapper firstCard">
												<div class="planContainer">
													<h3 class="planTitle"><?= $plan->package_name ?></h3>

													<?php

													$custom_name = new custom_name_helper();

													$curr = $custom_name->getconfig_item('default_currency');

													$currency_amt = json_decode($plan->currency_amt, true);
													
													if (!empty($currency_amt[$curr])) {
														$currency_pricing = $currency_amt[$curr];
													} else{
														$currency_pricing = [
															'monthly' => 0.00,
															'quarterly' => 0.00,
															'semi_annually' => 0.00,
															'annually' => 0.00,
															'biennially' => 0.00,
															'triennially' => 0.00
														];	
													}
													?>
													<div class="planPrice">
														<span class="planPriceUnit"><?= AppLib::format_currency($currency_pricing['monthly'] ? $currency_pricing['monthly'] : 0.00, 'default_currency') ?> </span>
														<span class="planPriceMonth"> / Mon </span>
													</div>

													<div class="planInfo">
														<h3>Plan</h3>
													</div>

													<!-- <div class="planInputDiv">
                                        <select class="plan-select">
                                            <option value="<?= $currency_pricing['monthly'] ? $currency_pricing['monthly'] : 0.00 ?>">1 Month</option>
                                            <option value="<?= $currency_pricing['quarterly'] ? $currency_pricing['quarterly'] : 0.00 ?>">3 Month</option>
                                            <option value="<?= $currency_pricing['semi_annually'] ? $currency_pricing['semi_annually'] : 0.00 ?>">6 Months</option>
                                            <option value="<?= $currency_pricing['annually'] ? $currency_pricing['annually'] : 0.00 ?>">1 Year</option>
                                            <option value="<?= $currency_pricing['biennially'] ? $currency_pricing['biennially'] : 0.00 ?>">Biennially</option>
                                            <option value="<?= $currency_pricing['triennially'] ? $currency_pricing['triennially'] : 0.00 ?>">Triennially</option>
                                        </select>
                                    </div> -->

													<div class="planDetailsList">
														<ul>
															<?php
															$features = explode("\n", $plan->item_features);

															foreach ($features as $feature) {
															?>
																<li><?= $feature; ?></li>

															<?php
															} ?>
														</ul>
													</div>


												</div>
												<div class="firstCardBottomSvg"></div>
												<div class="planCutImg">
													<a href="<?= site_url('cart/options/' . $plan->item_id) ?>" class="btn planBuyBtn">Buy</a>
												</div>
											</div>
										</div>
								<?php break;
								case 'two':
								?>
										<div class="col-sm-4">
											<div class="planningWrapper secondCard">
												<div class="planContainer">


													<?php

													$custom_name = new custom_name_helper();

													$curr = $custom_name->getconfig_item('default_currency');

													$currency_amt = json_decode($plan->currency_amt, true);
													if (!empty($currency_amt[$curr])) {
														$currency_pricing = $currency_amt[$curr];
													} else{
														$currency_pricing = [
															'monthly' => 0.00,
															'quarterly' => 0.00,
															'semi_annually' => 0.00,
															'annually' => 0.00,
															'biennially' => 0.00,
															'triennially' => 0.00
														];	
													}
													?>

													<div class="planPrice">
														<span class="planPriceUnit"> <?= Applib::format_currency($currency_pricing['monthly'] ? $currency_pricing['monthly'] : 0.00, 'default_currency') ?> </span>
														<span class="planPriceMonth"> / Mon </span>
													</div>

													<h3 class="planTitle"><?= $plan->package_name ?></h3>


													<!-- <div class="planInputDiv">
                                <select class="plan-select">
                                    <option value="<?= $currency_pricing['monthly'] ? $currency_pricing['monthly'] : 0.00 ?>">1 Month</option>
                                    <option value="<?= $currency_pricing['quarterly'] ? $currency_pricing['quarterly'] : 0.00 ?>">3 Month</option>
                                    <option value="<?= $currency_pricing['semi_annually'] ? $currency_pricing['semi_annually'] : 0.00 ?>">6 Months</option>
                                    <option value="<?= $currency_pricing['annually'] ? $currency_pricing['annually'] : 0.00 ?>">1 Year</option>
                                    <option value="<?= $currency_pricing['biennially'] ? $currency_pricing['biennially'] : 0.00 ?>">Biennially</option>
                                    <option value="<?= $currency_pricing['triennially'] ? $currency_pricing['triennially'] : 0.00 ?>">Triennially</option>
                                </select>
                            </div> -->

													<div class="planDetailsList">
														<ul>
															<?php
															$features = explode("\n", $plan->item_features);

															foreach ($features as $feature) {
															?>
																<li><?= $feature; ?></li>

															<?php
															} ?>
														</ul>
													</div>

													<div class="text-center">
														<a href="<?= site_url('cart/options/' . $plan->item_id) ?>" class="second-card-btn">Buy</a>
													</div>
												</div>
												<div class="secondCardBottomSvg"></div>
											</div>
										</div>
									<?php
									break;
								case 'three':
									?>
										<div class="col-md-3">
											<div class="planningWrapper thirdCard">
												<div class="planContainer">
													<h3 class="planTitle"><?= $plan->package_name ?></h3>
													<?php

													$custom_name = new custom_name_helper();

													$curr = $custom_name->getconfig_item('default_currency');

													$currency_amt = json_decode($plan->currency_amt, true);
													if (!empty($currency_amt[$curr])) {
														$currency_pricing = $currency_amt[$curr];
													} else{
														$currency_pricing = [
															'monthly' => 0.00,
															'quarterly' => 0.00,
															'semi_annually' => 0.00,
															'annually' => 0.00,
															'biennially' => 0.00,
															'triennially' => 0.00
														];	
													}
													?>
													<div class="planPrice">
														<span class="planPriceUnit"> <?= Applib::format_currency($currency_pricing['monthly'] ? $currency_pricing['monthly'] : 0.00, 'default_currency') ?> </span>
														<span class="planPriceMonth"> / Mon </span>
													</div>
													<!-- <div class="planInputDiv">
                                        <select class="plan-select">
                                            <option value="<?= $currency_pricing['monthly'] ? $currency_pricing['monthly'] : 0.00 ?>">1 Month</option>
                                            <option value="<?= $currency_pricing['quarterly'] ? $currency_pricing['quarterly'] : 0.00 ?>">3 Month</option>
                                            <option value="<?= $currency_pricing['semi_annually'] ? $currency_pricing['semi_annually'] : 0.00 ?>">6 Months</option>
                                            <option value="<?= $currency_pricing['annually'] ? $currency_pricing['annually'] : 0.00 ?>">1 Year</option>
                                            <option value="<?= $currency_pricing['biennially'] ? $currency_pricing['biennially'] : 0.00 ?>">Biennially</option>
                                            <option value="<?= $currency_pricing['triennially'] ? $currency_pricing['triennially'] : 0.00 ?>">Triennially</option>
                                        </select>
                                    </div> -->

													<div class="planDetailsList">
														<ul>
															<?php
															$features = explode("\n", $plan->item_features);

															foreach ($features as $feature) {
															?>
																<li><?= $feature; ?></li>

															<?php
															} ?>
														</ul>
													</div>

													<div class="planCutImg">
														<div class="planBuyBtn">
															<a href="<?= site_url('cart/options/' . $plan->item_id) ?>" class="btn planBuyBtn">Buy</a>
														</div>
													</div>
												</div>
												<div class="thirdCardBottomSvg"></div>
											</div>
										</div>
									<?php
									break;
								case "four":
									?>
										<div class="col-md-3">
											<div class="planningWrapper fourthCard">
												<div class="planContainer">
													<div class="planLogo">
														<span class="planLogoSVG">
															<svg xmlns="http://www.w3.org/2000/svg" width="38" height="37" viewBox="0 0 38 37" fill="none">
																<path d="M25.1079 21.7857C30.4961 21.7857 35.0283 17.5574 35.0283 12.1429C35.0283 6.72829 30.4961 2.5 25.1079 2.5C19.7197 2.5 15.1875 6.72829 15.1875 12.1429C15.1875 17.5574 19.7197 21.7857 25.1079 21.7857Z" fill="#5479F7" stroke="white" stroke-width="5" />
																<path d="M13.9771 36C21.0502 36 26.8497 30.4711 26.8497 23.5714C26.8497 16.6717 21.0502 11.1429 13.9771 11.1429C6.90403 11.1429 1.10449 16.6717 1.10449 23.5714C1.10449 30.4711 6.90403 36 13.9771 36Z" fill="#2E0AA3" stroke="white" stroke-width="2" />
															</svg>
														</span>
														<h3></h3>
													</div>

													<?php

													$custom_name = new custom_name_helper();

													$curr = $custom_name->getconfig_item('default_currency');

													$currency_amt = json_decode($plan->currency_amt, true);
													if (!empty($currency_amt[$curr])) {
														$currency_pricing = $currency_amt[$curr];
													} else{
														$currency_pricing = [
															'monthly' => 0.00,
															'quarterly' => 0.00,
															'semi_annually' => 0.00,
															'annually' => 0.00,
															'biennially' => 0.00,
															'triennially' => 0.00
														];	
													}
													?>
													<div class="planPrice">
														<span class="planPriceUnit"> <?= Applib::format_currency($currency_pricing['monthly'] ? $currency_pricing['monthly'] : 0.00, 'default_currency') ?> </span>
														<span class="planPriceMonth"> / Mon </span>
													</div>

													<h3 class="planTitle"><?= $plan->package_name ?></h3>

													<!-- <div class="planInputDiv">
                                        <select class="plan-select">
                                            <option value="<?= $currency_pricing['monthly'] ? $currency_pricing['monthly'] : 0.00 ?>">1 Month</option>
                                            <option value="<?= $currency_pricing['quarterly'] ? $currency_pricing['quarterly'] : 0.00 ?>">3 Month</option>
                                            <option value="<?= $currency_pricing['semi_annually'] ? $currency_pricing['semi_annually'] : 0.00 ?>">6 Months</option>
                                            <option value="<?= $currency_pricing['annually'] ? $currency_pricing['annually'] : 0.00 ?>">1 Year</option>
                                            <option value="<?= $currency_pricing['biennially'] ? $currency_pricing['biennially'] : 0.00 ?>">Biennially</option>
                                            <option value="<?= $currency_pricing['triennially'] ? $currency_pricing['triennially'] : 0.00 ?>">Triennially</option>
                                        </select>
                                    </div> -->

													<h3 class="featuresTag">Features:</h3>

													<div class="planDetailsList">
														<ul>
															<?php
															$features = explode("\n", $plan->item_features);

															foreach ($features as $feature) {
															?>
																<li><?= $feature; ?></li>

															<?php
															} ?>
														</ul>
													</div>

													<div class="planCutImg">
														<div class="planBuyBtn">
															<a href="<?= site_url('cart/options/' . $plan->item_id) ?>" class="btn planBuyBtn">Buy</a>
														</div>
													</div>
												</div>
											</div>
										</div>
									<?php
									break;
								case 'default':
									?>
									<!-- <div class="row"> -->

										<div class="col-sm-4 ">
											<div class="planningWrapper firstCard">
												<div class="planContainer">
													<h3 class="planTitle"><?= $plan->package_name ?></h3>

													<?php

													$custom_name = new custom_name_helper();

													$curr = $custom_name->getconfig_item('default_currency');

													$currency_amt = json_decode($plan->currency_amt, true);
													if (!empty($currency_amt[$curr])) {
														$currency_pricing = $currency_amt[$curr];
													} else{
														$currency_pricing = [
															'monthly' => 0.00,
															'quarterly' => 0.00,
															'semi_annually' => 0.00,
															'annually' => 0.00,
															'biennially' => 0.00,
															'triennially' => 0.00
														];	
													}
													?>
													<div class="planPrice">
														<span class="planPriceUnit"><?= Applib::format_currency($currency_pricing['monthly'] ? $currency_pricing['monthly'] : 0.00, 'default_currency') ?> </span>
														<span class="planPriceMonth"> / Mon </span>
													</div>

													<div class="planInfo">
														<h3>Plan</h3>
													</div>

													<!-- <div class="planInputDiv">
                                        <select class="plan-select">
                                            <option value="<?= $currency_pricing['monthly'] ? $currency_pricing['monthly'] : 0.00 ?>">1 Month</option>
                                            <option value="<?= $currency_pricing['quarterly'] ? $currency_pricing['quarterly'] : 0.00 ?>">3 Month</option>
                                            <option value="<?= $currency_pricing['semi_annually'] ? $currency_pricing['semi_annually'] : 0.00 ?>">6 Months</option>
                                            <option value="<?= $currency_pricing['annually'] ? $currency_pricing['annually'] : 0.00 ?>">1 Year</option>
                                            <option value="<?= $currency_pricing['biennially'] ? $currency_pricing['biennially'] : 0.00 ?>">Biennially</option>
                                            <option value="<?= $currency_pricing['triennially'] ? $currency_pricing['triennially'] : 0.00 ?>">Triennially</option>
                                        </select>
                                    </div> -->

													<div class="planDetailsList">
														<ul>
															<?php
															$features = explode("\n", $plan->item_features);

															foreach ($features as $feature) {
															?>
																<li><?= $feature; ?></li>

															<?php
															} ?>
														</ul>
													</div>


												</div>
												<div class="firstCardBottomSvg"></div>
												<div class="planCutImg">
													<a href="<?= site_url('cart/options/' . $plan->item_id) ?>" class="btn planBuyBtn">Buy</a>
												</div>
											</div>
										</div>
							<?php
									}
									break;
							} ?>
						</div>
					</div>
				<?php } ?>
				
			</div>
	</div>
	
</div>

<?= $this->endSection() ?>