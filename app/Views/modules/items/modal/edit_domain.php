<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

error_reporting(E_ALL);
ini_set("display_errors", "1");

use App\Helpers\custom_name_helper;

$helper = new custom_name_helper();
?>
<div class="modal-dialog my-modal modal-cus modal-xl">
    <div class="modal-content">
        <div class="modal-header row-reverse"> <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><?= lang('hd_lang.edit_item') ?></h4>
        </div>
        <?php

        use App\Modules\items\controllers\Items;

        $session = \Config\Services::session();

        // Connect to the database	
        $dbName = \Config\Database::connect();

        $itemModel = new Items();

        $item = $itemModel::view_item($id);
		//print_r($id);die;
        $category = $dbName->table('hd_categories')->where('id', $item->category)->get()->getRow();

        ?>

        <?php
        $attributes = array('class' => 'bs-example form-horizontal');
        echo form_open(base_url('items/add_domains'), $attributes); ?>
        <input type="hidden" name="item_id" value="<?= $item->id ?>">
        <input type="hidden" name="edit_id" value="<?= $item->id ?>">
		
        <input type="hidden" name="quantity" value="1">
        <input type="hidden" class="common-input" name="parent_cat" value="14">
        <div class="modal-body">

        <div class="row">

            <div class="form-group modal-input flex-wrap col-sm-6 col-12">
                <label class="col-sm-4 col-12 control-label"><?= lang('hd_lang.category') ?> <span class="text-danger">*</span></label>
                <div class="col-sm-5 col-6 flex-fill">
                    <select name="category" class="form-control m-b" required>
                        <option value="<?= $item->category ?>" <?php echo ($item->category == $item->id) ? 'selected' : ''; ?>>
                            <?= $category->cat_name ?></option>
                    </select>
                </div>
                <a href="<?= base_url() ?>settings/add_category" class="btn btn-<?= $helper->getconfig_item('theme_color'); ?> btn-sm button-cus" data-toggle="ajaxModal" title="<?= lang('hd_lang.add_category') ?>"><i class="fa fa-plus"></i>
                    <?= lang('hd_lang.add_category') ?></a>
            </div>


            <div class="form-group modal-input flex-wrap col-sm-6 col-12">
                <label class="col-sm-4 col-12 control-label"><?= lang('hd_lang.item_name') ?> <span class="text-danger">*</span></label>
                <div class="col-sm-8 col-12">
                    <input type="text" class="form-control" value="<?= $item->ext_name ?>" name="item_name" required>
                </div>
            </div>

        </div>

        <div class="row">

            <div class="form-group modal-input flex-wrap col-sm-6 col-12">
                <label class="col-sm-4 col-12 control-label"><?= lang('hd_lang.registrar') ?></label>
                <div class="col-sm-8 col-12">
                    <select class="form-control" name="default_registrar">
                        <option value=""><?= lang('hd_lang.none') ?></option>
                        <option value="<?= $item->registrar; ?>" <?= ($item->registrar == $item->registrar) ? 'selected' : '' ?>><?= ucfirst($item->registrar); ?></option>
                    </select>
                </div>
            </div>

			<div class="form-group max_years modal-input flex-wrap col-sm-6 col-12">
                <label class="col-sm-4 col-12 control-label"><?= lang('hd_lang.max_years') ?></label>
                <div class="col-sm-8 col-12">
					<input type="text" class="form-control common-input" id="max_years" value="<?= $item->max_years ?>" name="max_years" oninput="addFields()">
					<div id="error-msg" style="color: red; display: none;">Only values up to 10 are allowed.</div>
				</div>
            </div>

        </div>

            <?php
            $countYear = $item->max_years;

            for ($i = 1; $i <= $countYear; $i++) { ?>
                <div class="row">
                    <div class="form-group modal-input flex-wrap col-lg-4 col-md-6 col-12 ">
                        <label class="col-sm-4 col-12 control-label"><?= lang('hd_lang.registration') . ' ' . $i ?></label>
                        <div class="col-sm-8 col-12">
                            <input type="text" class="form-control" value="<?= $item->{"registration_" . $i} ?>" name="registration_<?= $i ?>" oninput="validateInput(this)">
                        </div>
                    </div>

                    <div class="form-group modal-input flex-wrap col-lg-4 col-md-6 col-12">
                        <label class="col-sm-4 col-12 control-label"><?= lang('hd_lang.transfer') . ' ' . $i ?></label>
                        <div class="col-sm-8 col-12">
                            <input type="text" class="form-control" value="<?= $item->{"transfer_" . $i} ?>" name="transfer_<?= $i ?>" oninput="validateInput(this)">
                        </div>
                    </div>

                    <div class="form-group modal-input flex-wrap col-lg-4 col-md-6 col-12">
                        <label class="col-sm-4 col-12 control-label"><?= lang('hd_lang.renewal') . ' ' . $i ?></label>
                        <div class="col-sm-8 col-12">
                            <input type="text" class="form-control" value="<?= $item->{"renewal_" . $i} ?>" name="renewal_<?= $i ?>" oninput="validateInput(this)">
                        </div>
                    </div>
                </div>
                <hr>
            <?php } ?>
			
            <div id="fields_container"></div>
			
			<div class="form-group modal-input flex-wrap">
				<div class="nh-category-title">
					<p><?= lang('hd_lang.tax_rate') ?></p>
				</div>
				<div class="nh-input-btnwrap">
					<div class="form-check form-switch input-btn-div">
						<input class="form-check-input" type="checkbox" role="switch" <?php if($item->tax_rate == 'Yes'){ echo "checked=\"checked\""; } ?> name="item_tax_rate"/>
					</div>
				</div>
			</div>
			
            <div class="row align-items-center">

				<div class="col-6 ">
					<div class="form-group modal-input flex-wrap m-2 align-items-center">
						<label class="col-sm-8 col-5 control-label"><?= lang('hd_lang.order') ?></label>
						<div class="col-sm-4 col-5 m-0">
							<input type="text" id="order_by" class="form-control" value="<?= $item->ext_order ?>" name="order_by" oninput="validateInput(this)">
						</div>
					</div>
				</div>
				<div class="col-6 ">
					<div class="form-group modal-input flex-wrap m-4 align-items-center">
						<label class="col-sm-4 col-6 control-label"><?= lang('hd_lang.display') ?></label>
						<div class="col-sm-8 col-6">
							<label class="switch">
								<input type="hidden" value="off" name="display" />
								<div class="form-check form-switch input-btn-div">
									<input class="form-check-input switch-cus" type="checkbox" value="<?= $item->display ?>" <?php if ($item->display == 'yes') {echo "checked=\"checked\"";} ?> name="display">
								</div>
								<span></span>
							</label>
						</div>
				</div>
	
			</div>

            <div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?= lang('hd_lang.close') ?></a>
                <button type="submit" class="btn btn-<?= $helper->getconfig_item('theme_color'); ?>"><?= lang('hd_lang.save_changes') ?></button>
                </form>
            </div>
        </div>
        <!-- /.modal-content -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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
    var container = document.getElementById("fields_container");

    // Check if there are already fields in the container
    var existingFieldsCount = container.getElementsByClassName("form-group").length;

    // If fields already exist, just return
    if (existingFieldsCount > 0) {
        return;
    }

    // Limit the maximum number of years to 10
    if (maxYears > 10) {
        maxYears = 10;
    }

    // Create fields based on the number of years
    for (var i = 1; i <= maxYears; i++) {
        container.innerHTML += `
            <div class="form-group modal-input">
                <label class="col-sm-4 control-label"><?= lang('hd_lang.registration') ?> ${i}</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" placeholder="0.00" name="registration_${i}" oninput="validateInput(this)">
                </div>
            </div>
            <div class="form-group modal-input">
                <label class="col-sm-4 control-label"><?= lang('hd_lang.transfer') ?> ${i}</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" placeholder="0.00" name="transfer_${i}" oninput="validateInput(this)">
                </div>
            </div>
            <div class="form-group modal-input">
                <label class="col-sm-4 control-label"><?= lang('hd_lang.renewal') ?> ${i}</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" placeholder="0.00" name="renewal_${i}" oninput="validateInput(this)">
                </div>
            </div>
        `;
    }
}



    // Call validateMaxYears() when the page loads
    window.onload = validateMaxYears;
</script>

    </div>
    <!-- /.modal-dialog -->
    <script>
        $(this).showCategoryFields($('#item_category')[0]);
    </script>