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
use App\Libraries\Settings;

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

$item = Item::view_item($id);
$list = array();
$server = (object) array();

$settings= new Settings();

foreach($servers as $srv) {
	 $list[$srv->type] = ucfirst($srv->type);
	 
	 if($srv->id == $item->server)
	 {
		$server = $srv;
	 }
}

?>
<?= $this->extend('layouts/users') ?>

<?= $this->section('content') ?>
<div class="box" style="margin-top: 2%;">

    <div class="box-body">
        <div class="row">
            <div class="col-md-6">

                <?php 

				echo $settings->open_form(
					array('action' => 'items/package/'.$id, 'id' => 'servers', 'method' => 'GET'));   
				
				$options = array(
					'label' => 'Server',
					'id' => 'server',
					'type' => 'dropdown',
					'options' => $list
				);

				if(isset($server->type))
				{
					$options['value'] = $server->type;
				}
				
				if(isset($_GET['server'])) {

					$options['value'] = $_GET['server'];
					foreach($servers as $srv) { 
						
						if($srv->type == $_GET['server']) 
						{
							$server = $srv;
						} 
					}
				}

				if(!isset($server->type) && !isset($_GET['server']))
				{
					$list = array_merge(array('none' => 'None'), $list); 
					$options = array(
						'label' => 'Server',
						'id' => 'server',
						'type' => 'dropdown',
						'options' => $list
					);
	 
					// $options['value'] = 'none'; 

					// echo "<pre>";print_r($options);die;
				}

				echo $settings->build_form_horizontal(array($options));
				echo $settings->close_form();


				if(isset($_GET['server']) || isset($server->type) && $server->type != '') {
					
					echo $settings->open_form(array('action' => 'items/package'));
					
					if(isset($server->type) && $server->type != '') {
						$conf = $server->type;
						// echo $conf;die;
					} 
					// echo 121;die;

					if(isset($_GET['server'])) {
						$conf = $_GET['server'];
						// echo $conf;die;
					}
					
					$package_config = unserialize($item->package_config);
					if(is_array($package_config)) {
						$package_config['package'] = $item->package_name;
					}

					else {
						$package_config = array('package' => $item->package_name);
					}					
					// echo"<pre>";print_r($conf);die;
					// $configuration = modules::run($conf.'/'.$conf.'_package_config', $package_config);	
					
					switch ($conf) {
                        case 'plesk':
                            $plesk = new Plesk();
                            $configuration = $plesk->plesk_config();
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
                        case 'razorpay':
                            $razorpay = new Razorpay();
                            $configuration = $razorpay->razorpay_config();
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
                            $configuration = $cpanel->cpanel_config();
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
                    }

					$configuration[] =  array(
						'id' => 'item_id',
						'type' => 'hidden',
						'value' => $id
					);

					$configuration[] =  array(
						'id' => 'server_id',
						'type' => 'hidden',
						'value' => $server->id
					);

					$configuration[] =  array(
						'id' => 'server',
						'type' => 'hidden',
						'value' => $conf
					);

					$configuration[] =  array(
						'id' => 'package',
						'type' => 'hidden',
						'value' => $item->package_name
					);

					$configuration[] =  array(
						'id' => 'package_config',
						'type' => 'hidden',
						'value' => $package_config
					);

					$configuration[] =  array(
						'id' => 'submit',
						'type' => 'submit',
						'label' => 'Save'
					);

					// echo "<pre>";print_r($configuration);die;

					echo $settings->build_form_horizontal($configuration);
					echo $settings->close_form();
				}				 
			  ?>

            </div>
        </div>
    </div>
</div>

<script>
	$('#server').on('change', function() {
		$('#servers').submit();
	});
</script>
<?= $this->endSection() ?>