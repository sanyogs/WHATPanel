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

$item = Item::view_item($id);
$list = array();
$server = (object) array();

$settings= new Settings();

foreach($servers as $srv) {
	 $list[$srv->type] = ucfirst($srv->type);
	 
	//  if($srv->id == $item->server)
	//  {
	// 	$server = $srv;
	//  }
}

?>
<?= $this->extend('layouts/users') ?>

<?= $this->section('content') ?>
<div class="box" style="margin-top: 2%;">

    <div class="box-body">
        <div class="row">
            <div class="col-md-6">
				<form id="servers" method="GET">
				<label class="common-label" for="server">Select Server</label>

					<select class="common-select" name="server" id="server">
					<option value="">None</option>
					<?php foreach($servers as $server) {
						if(isset($server->type))
						{  ?>
							<option value="<?php echo $server->type; ?>"><?php echo $server->type; ?>	</option>
						<?php }
						if(isset($_GET['server']))
						{ if($srv->type == $_GET['server']) 
							{ ?>
						
							<option value="<?php echo $_GET['server']; ?>"><?php echo $_GET['server']; ?>	</option>
						<?php }}} ?>
					</select>
					<div id="form-container"></div>
					</form>

                <?php 


				if(isset($_GET['server']) || isset($server->type) && $server->type != '') { ?>
				<form action="<?php echo base_url('items/package/'); ?>" id="form-config" method="POST">
				
				<?php 
					
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

					?>

					<?php
				}				 
			  ?>

            </div>
        </div>
    </div>
</div>

<script>
	$(document).ready(function() {

		$(document).on("change", "#server", function() {
			var s1 = $('#server').val();

			// Make the AJAX request
			$.ajax({
				url: '<?php echo base_url('items/get_config_items/'); ?>' + s1,
				type: "GET",
				success: function(response) {
					$('#form-container').html(response);
				},
				error: function(xhr, status, error) {
					// Handle errors here
					console.error(xhr.responseText);
				}
			});
		});


		$(document).on("click", "#btnCode", function(e) {
			e.preventDefault();
			var package = $('#package').val();
			var server = $('#server').val();
			//var server_name = $('#server_name').val();
			var item_id = <?php echo $id; ?>

			// Make the AJAX request
			$.ajax({
				url: '<?php echo base_url('items/save_config_items'); ?>',
				type: "POST",
				data: {
					'package' : package,
					'server' : server,
					'item_id' : item_id
				},
				dataType: 'json',
				success: function(response) {
					if(response.statusCode == 200) {
						alert(response.msg)
						window.history.back();
					}
					//alert('Server Added Successfully');
				},
				error: function(xhr, status, error) {
					// Handle errors here
					console.error(xhr.responseText);
				}
			});
		});
	});
</script>
<?= $this->endSection() ?>