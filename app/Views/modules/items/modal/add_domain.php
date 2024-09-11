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
use App\Models\Plugin;
$helper = new custom_name_helper(); 

$session = \Config\Services::session();

// Connect to the database	
$dbName = \Config\Database::connect();

$itemModel = new Plugin();

$registrars = $itemModel::domain_registrars();
?>
<div class="modal-dialog my-modal modal-cus modal-xl">
    <div class="modal-content">
        <div class="modal-header row-reverse"> <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><?= lang('hd_lang.new_domain') ?></h4>
        </div><?php
                $attributes = array('class' => 'bs-example form-horizontal');
                echo form_open(base_url('items/add_domains'), $attributes); ?>
        <div class="modal-body">
            <input type="hidden" class="common-input" name="r_url" value="<?= site_url("hosting/index/domains"); ?>">
            <input type="hidden" class="common-input" name="quantity" value="1">
            <input type="hidden" class="common-input" name="type_of" value="add">
			<input type="hidden" class="common-input" name="item_type" value="manually">
            <div class="form-group modal-input">
                <label class="col-sm-2 control-label common-label"><?= lang('hd_lang.category') ?> <span class="text-danger">*</span></label>
                <div class="col-sm-5">
                    <select name="category" class="form-control m-b" required>
                        <?php foreach ($categories as $key => $cat) {
                            if ($cat->parent == 8) { ?>
                                <option value="<?= $cat->id ?>"><?= $cat->cat_name ?></option>
                        <?php }
                        } ?>
                    </select>
                </div>
                <a href="<?= site_url('settings1/add_category') ?>" class="btn btn-<?= $helper->getconfig_item('theme_color'); ?> btn-sm button-cus" data-toggle="ajaxModal" title="<?= lang('hd_lang.add_category') ?>"><i class="fa fa-plus"></i>
                    <?= lang('hd_lang.add_category') ?></a>
            </div>

            <div class="form-group modal-input">
                <label class="col-sm-2 control-label"><?= lang('hd_lang.extension') ?> <span class="text-danger">*</span></label>
                <div class="col-sm-8">
					<input type="text" class="form-control" placeholder="Enter extension" name="item_name" id="item_name" required>
				</div>
            </div>
            <div class="form-group modal-input">
                <label class="col-sm-2 control-label"><?= lang('hd_lang.registrar') ?></label>
                <div class="col-sm-8">
                    <select class="form-control" name="default_registrar">
                        <option value=""><?= lang('hd_lang.none') ?></option>
                        <?php foreach ($registrars as $registrar) { ?>
                            <option value="<?= $registrar->system_name; ?>"><?= ucfirst($registrar->system_name); ?></option>
						<?php } ?>
                    </select>
                </div>
            </div>

            <div class="form-group max_years modal-input">
                <label class="col-sm-2 control-label"><?= lang('hd_lang.max_years') ?></label>
                <div class="col-sm-8">
					<input type="text" class="form-control common-input" placeholder="Enter number of years" id="max_years" name="max_years" maxlength="2" oninput="addFields()">
					<div id="error-msg" style="color: red; display: none;">Only values up to 10 are allowed.</div>
				</div>
            </div>

            <div id="price">
            </div>

			<div class="form-group modal-input">
				
					<label class="nh-category-title col-sm-2 control-label"><?= lang('hd_lang.tax_rate') ?> </label>
				
				<div class="nh-input-btnwrap">
						<label class="switch">
                             <input type="hidden" value="off" name="tax_rate">
                              <div class="form-check form-switch input-btn-div">
                             <input class="form-check-input switch-cus" type="checkbox" name="tax_rate">
                        	</div>
                           <span></span>
                        </label>
				</div>
			</div>
			
            <div class="row">
                    <div class="col-4">
                        <div class="form-group modal-input m-2">
                            <label class="col-sm-6 control-label"><?= lang('hd_lang.order') ?></label>
                            <div class="col-sm-6 my-4">
                                <input type="text" id="order_by" class="form-control common-input" placeholder="1" name="order_by" oninput="validateInput(this)">
                            </div>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-group modal-input m-4">
                            <label class="col-sm-2 control-label"><?= lang('hd_lang.display') ?></label>
                            <div class="col-sm-4">
                                <label class="switch">
                                    <input type="hidden" value="off" name="display" />
                                    <div class="form-check form-switch input-btn-div">
                                    <input class="form-check-input switch-cus" type="checkbox" name="display">
                        			</div>
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
			            <div id="fields_container"></div>
            <div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?= lang('hd_lang.close') ?></a>
                <button type="submit" class="btn btn-<?= $helper->getconfig_item('theme_color'); ?>"><?= lang('hd_lang.add_item') ?></button>
                <?php echo form_close(); ?>
            </div>
        </div>
        <!-- /.modal-content -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <!-- <script>
            $(document).ready(function() {
                $("#max_years").on('keyup', function() {
                    $("#price").hide(); // Hide the default fields container when input is given
                });
            });
        </script> -->
<script>
    function validateMaxYears() {
        var input = document.getElementById('max_years');
        var errorMsg = document.getElementById('error-msg');

        // Convert input value to a number
        var value = parseInt(input.value, 10);

        // Check if the value is greater than 10
        if (value > 10) {
            errorMsg.style.display = 'block'; // Display error message
            input.value = input.value.slice(0, -1); // Remove the last character (if input exceeds 10)
        } else {
            errorMsg.style.display = 'none'; // Hide error message if value is valid
            addFields(); // Call addFields() to dynamically add input fields based on max_years
        }
    }

    function validateInput(input) {
        // Regular expression to allow partial and complete matches
        const partialRegex = /^[1-9][0-9]{0,5}(\.[0-9]{0,2})?$/;
        // Regular expression to strictly validate the complete input
        const strictRegex = /^[1-9][0-9]{5}(\.[0-9]{2})?$/;

        if (!partialRegex.test(input.value)) {
            input.value = input.value.slice(0, -1); // Remove last character if it doesn't match
        } else if (strictRegex.test(input.value)) {
            // If the complete value matches strict validation, do nothing
        } else if (input.value && parseFloat(input.value) > 999999.00) {
            input.value = input.value.slice(0, -1); // Remove last character if it exceeds the max range
        }
    }


	// Function to dynamically add fields based on max_years
	function addFields() {
		var maxYears = parseInt(document.getElementById("max_years").value);
		var container = document.getElementById("price");
		container.innerHTML = ""; // Clear previous fields

		if (maxYears > 10) {
			maxYears = 10;
		}

		for (var i = 1; i <= maxYears; i++) {
			container.innerHTML += `
				<div class="rowGenerats">
					<div class="form-group modal-input">
						<label class="col-sm-4 control-label">Registration ${i}</label>
						<div class="col-sm-7">
							<input type="text" class="form-control" placeholder="0.00" id="registration_${i}" name="registration_${i}" oninput="updateValues()">
						</div>
					</div>
					<div class="form-group modal-input">
						<label class="col-sm-4 control-label">Transfer ${i}</label>
						<div class="col-sm-7">
							<input type="text" class="form-control" placeholder="0.00" id="transfer_${i}" name="transfer_${i}" oninput="updateValues()">
						</div>
					</div>
					<div class="form-group modal-input">
						<label class="col-sm-4 control-label">Renewal ${i}</label>
						<div class="col-sm-7">
							<input type="text" class="form-control" placeholder="0.00" id="renewal_${i}" name="renewal_${i}" oninput="updateValues()">
						</div>
					</div>
				</div>
			`;
		}
	}

	// Function to update the subsequent fields based on the first fields
	function updateValues() {
		// Get the values of Registration 1, Transfer 1, and Renewal 1
		var registration1 = parseFloat(document.getElementById('registration_1').value) || 0;
		var transfer1 = parseFloat(document.getElementById('transfer_1').value) || 0;
		var renewal1 = parseFloat(document.getElementById('renewal_1').value) || 0;

		// Loop to update registration, transfer, and renewal fields for indices 2 to 10
		for (var i = 2; i <= 10; i++) {
			var registrationField = document.getElementById(`registration_${i}`);
			var transferField = document.getElementById(`transfer_${i}`);
			var renewalField = document.getElementById(`renewal_${i}`);

			// If the fields exist, update them with the multiplied values
			if (registrationField) {
				registrationField.value = (registration1 * i).toFixed(2);
			}
			if (transferField) {
				transferField.value = (transfer1 * i).toFixed(2);
			}
			if (renewalField) {
				renewalField.value = (renewal1 * i).toFixed(2);
			}
		}
	}

	// Call addFields() when the page loads
	window.onload = function() {
		document.getElementById("max_years").addEventListener("change", addFields);
		addFields(); // Initialize fields
	};

	
	//
	const itemNameInput = document.getElementById('item_name');

	itemNameInput.addEventListener('input', function() {
		const value = this.value.trim();
		if (!value.startsWith('.')) {
			this.value = '.' + value;
		}
	});
</script>
    </div>
    <!-- /.modal-dialog -->