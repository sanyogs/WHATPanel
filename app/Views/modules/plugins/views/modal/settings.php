<?php

use App\ThirdParty\MX\Modules;
use Modules\cyberpanel\controllers\Cyberpanel;
use Modules\bitcoin\controllers\Bitcoin;
use Modules\cwp\controllers\CWP;
use Modules\cpanel\controllers\Cpanel;
use Modules\coinpayments\controllers\Coinpayments;
use Modules\checkout\controllers\Checkout;
use Modules\directadmin\controllers\Directadmin;
use Modules\ispconfig\controllers\Ispconfig;
use Modules\plesk\controllers\Plesk;
use Modules\whoisxmlapi\controllers\Whoisxmlapi;
use Modules\razorpay\controllers\Razorpay;
use Modules\stripepay\controllers\Stripepay;
use Modules\paypal\controllers\Paypal;
use Modules\payfast\controllers\Payfast;
use Modules\resellerclub\controllers\Resellerclub;
use Modules\banktransfer\controllers\Banktransfer;
use Modules\virtualmin\controllers\Virtualmin;
use Modules\customerprice\controllers\Customerprice;

?>

<div class="modal-dialog modal-md">
	<div class="modal-content">
		<div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title"><?= ucfirst($plugin) . " " . lang('hd_lang.settings') ?></h4>
		</div>
		<div class="modal-body">
			<?php
			helper('form');
			?>

			<?php
			// echo $plugin;die;
			
			$db = \Config\Database::connect();
			
			$plugin_details = $db->table('hd_plugins')->where('system_name', $plugin)->get()->getRow();
			
			$config_decoded = json_decode($plugin_details->config, true);
			
			if (json_last_error() === JSON_ERROR_NONE) {
				switch ($plugin) {
					case 'customerprice':
						$customerprice = new CustomerPrice();
						$configuration = $customerprice->customerprice_config($config_decoded);
					break;
					case 'virtualmin':
						$virtualmin = new Virtualmin();
						$configuration = $virtualmin->virtualmin_config($config_decoded);
						break;
					case 'plesk':
						$plesk = new Plesk();
						$configuration = $plesk->plesk_package_config($config_decoded);
						break;
					case 'whoisxmlapi':
						$whoisxmlapi = new Whoisxmlapi();
						$configuration = $whoisxmlapi->whoisxmlapi_config($config_decoded);
						break;
					case 'stripepay':
						$stripepay = new Stripepay();
						$configuration = $stripepay->stripepay_config($config_decoded);
						break;
					case 'paypal':
						$paypal = new Paypal();
						$configuration = $paypal->paypal_config($config_decoded);
						break;
					case 'resellerclub':
						$resellerclub = new Resellerclub();
						$configuration = $resellerclub->resellerclub_config($config_decoded, $plugin_details);
						break;
					case 'razorpay':
						$razorpay = new Razorpay();
						$configuration = $razorpay->razorpay_config($config_decoded, $plugin_details);
						break;
					case 'payfast':
						$payfast = new Payfast();
						$configuration = $payfast->payfast_config($config_decoded);
						break;
					case 'ispconfig':
						$ispconfig = new Ispconfig();
						$configuration = $ispconfig->ispconfig_config($config_decoded);
						break;
					case 'directadmin':
						$directadmin = new Directadmin();
						$configuration = $directadmin->directadmin_config($config_decoded);
						break;
					case 'cyberpanel':
						$cyberpanel = new Cyberpanel();
						$configuration = $cyberpanel->cyberpanel_config($config_decoded);
						break;
					case 'cwp':
						$cwp = new Cwp();
						$configuration = $cwp->cwp_config($config_decoded);
						break;
					case 'cpanel':
						$cpanel = new Cpanel();
						$configuration = $cpanel->cpanel_config_old($config_decoded);
						break;
					case 'coinpayments':
						$coinpayments = new Coinpayments();
						$configuration = $coinpayments->coinpayments_config($config_decoded);
						break;
					case 'checkout':
						$checkout = new Checkout();
						$configuration = $checkout->checkout_config($config_decoded);
						break;
					case 'bitcoin':
						$bitcoins = new Bitcoin();
						$configuration = $bitcoins->bitcoin_config($config_decoded);
						break;
					case 'banktransfer':
						$banktransfer = new Banktransfer();
						$configuration = $banktransfer->banktransfer_config($config_decoded);
						break;
				}
				
				//echo "<pre>";print_r($configuration);die;
				
				$fields = $configuration['fields'];
				$form_id = $configuration['form_id'];
			}
			else {
				switch ($plugin) {
					case 'customerprice':
						$customerprice = new CustomerPrice();
						$configuration = $customerprice->customerprice_config();
					break;
					case 'virtualmin':
						$virtualmin = new Virtualmin();
						$configuration = $virtualmin->virtualmin_config();
						break;
					case 'plesk':
						$plesk = new Plesk();
						$configuration = $plesk->plesk_package_config();
						break;
					case 'whoisxmlapi':
						$whoisxmlapi = new Whoisxmlapi();
						$configuration = $whoisxmlapi->whoisxmlapi_config();
						break;
					case 'stripepay':
						$stripepay = new Stripepay();
						$configuration = $stripepay->stripepay_config();
						break;
					case 'paypal':
						$paypal = new Paypal();
						$configuration = $paypal->paypal_config();
						break;
					case 'resellerclub':
						$resellerclub = new Resellerclub();
						$configuration = $resellerclub->resellerclub_config_no_data($plugin_details);
						break;
					case 'razorpay':
						$razorpay = new Razorpay();
						$configuration = $razorpay->razorpay_config_no_data($plugin_details);
						break;
					case 'payfast':
						$payfast = new Payfast();
						$configuration = $payfast->payfast_config();
						break;
					case 'ispconfig':
						$ispconfig = new Ispconfig();
						$configuration = $ispconfig->ispconfig_config();
						break;
					case 'directadmin':
						$directadmin = new Directadmin();
						$configuration = $directadmin->directadmin_config();
						break;
					case 'cyberpanel':
						$cyberpanel = new Cyberpanel();
						$configuration = $cyberpanel->cyberpanel_config();
						break;
					case 'cwp':
						$cwp = new Cwp();
						$configuration = $cwp->cwp_config();
						break;
					case 'cpanel':
						$cpanel = new Cpanel();
						$configuration = $cpanel->cpanel_config_old();
						break;
					case 'coinpayments':
						$coinpayments = new Coinpayments();
						$configuration = $coinpayments->coinpayments_config();
						break;
					case 'checkout':
						$checkout = new Checkout();
						$configuration = $checkout->checkout_config();
						break;
					case 'bitcoin':
						$bitcoins = new Bitcoin();
						$configuration = $bitcoins->bitcoin_config();
						break;
					case 'banktransfer':
						$banktransfer = new Banktransfer();
						$configuration = $banktransfer->banktransfer_config();
						break;
				}
				
				//echo "<pre>";print_r($configuration);die;
				
				$fields = $configuration['fields'];
				$form_id = $configuration['form_id'];
			}
			?>
			
			<?= form_open('plugins/config', ['id' => $form_id, 'class' => '', 'method' => 'post']); ?>

			<?php foreach ($fields as $field) : ?>
				<?php if ($field['type'] === 'hidden') : ?>
					<?= form_hidden($field['id'], $field['value']); ?>
				<?php elseif ($field['type'] === 'submit') : ?>
					<?= form_submit($field['id'], $field['label'], 'class="common-button"'); ?>
				<?php elseif ($field['type'] === 'textarea') : ?>
					<div>
						<?= form_label($field['label'], $field['id'], ['class' => 'common-label']); ?>
						<?php
						$placeholder = isset($field['placeholder']) ? $field['placeholder'] : '';
						$value = isset($field['value']) ? $field['value'] : '';
						$id = isset($field['id']) ? $field['id'] : '';
						?>
						<?= form_textarea(['name' => $id, 'id' => $id, 'placeholder' => $placeholder], $value, 'class="common-input"'); ?>
					</div>
				<?php elseif ($field['type'] === 'radio') : ?>
					<div>
						<?= form_label($field['label'], $field['id'], ['class' => 'common-label']); ?>
						<?php
						if (array_key_exists('radio_options', $field)) :
							foreach ($field['radio_options'] as $option) : ?>
								<?php
								$value = isset($option['value']) ? $option['value'] : '';
								$checked = isset($field['value']) && $field['value'] === $option['value'] ? true : false;
								?>
								<?= form_radio(['name' => $field['id'], 'id' => $option['id'], 'class' => $option['class'], 'value' => $value, 'checked' => $checked]); ?>
								<?= form_label($option['label'], $option['id'], ['class' => 'common-label']); ?>
						<?php endforeach;

						endif; ?>
					</div>
					<div class="form-group percentage" style="display: none;">
						<input type="text" name="percentage" class="common-input">
					</div>
				<?php else : ?>
					<div>
						<?= form_label($field['label'], $field['id'], ['class' => 'common-label']); ?>

						<?php
						// Check if 'placeholder' key exists before using it
						$placeholder = isset($field['placeholder']) ? $field['placeholder'] : '';
						$value = isset($field['value']) ? $field['value'] : '';
						$id = isset($field['id']) ? $field['id'] : '';
						$onclick = isset($field['onclick']) ? $field['onclick'] : '';
						?>
<?= form_input(['name' => $id, 'id' => $id, 'placeholder' => $placeholder, 'value' => $value, 'onclick' => $onclick], '', 'class="common-input"'); ?>

					</div>
				<?php endif; ?>
			<?php endforeach; ?>
			<?= form_close(); ?>

		</div>
		<div class="modal-footer" style="border-top:none;"></div>
		<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
        <script>
            $(document).ready(function(){
            $(".API").on('click',function(){
                $(".percentage").show();
            });
            $(".Manual").on('click',function(){
                $(".percentage").hide();
            });
            });
        </script>
	</div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->