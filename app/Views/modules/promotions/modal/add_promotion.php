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
use App\ThirdParty\MX\Modules;
use App\Helpers\custom_name_helper;
$custom = new custom_name_helper();
?>

<?php 

$options = $intervals;?>
<div class="modal-dialog my-modal modal-cus modal-xl" >
    <div class="modal-content">
        <div class="modal-header row-reverse"> <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><?=lang('hd_lang.promotions')?></h4>
        </div><?php
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url('promotions/add_promotion'),$attributes); ?>
        <div class="modal-body">
        <!-- <input type="hidden" name="r_url" value="<?//= site_url("promotions"); ?>"> -->

            <div class="form-group modal-input gap-2 flex-wrap" style="display: flex; margin-top: 1%; ">
                <label class="col-lg-2 col-12 control-label"><?=lang('hd_lang.discount_type')?> <span class="text-danger">*</span></label>
				
                <div class="col-lg-3 col-5">
                    <select name="type" id="select" type="select" class="form-control m-b" required>
                        <option value="1"><?=lang('hd_lang.amount')?></option>
                        <option value="2"><?=lang('hd_lang.percentage')?></option>
                    </select>
                </div>
                <div class="col-lg-3 col-5">
					<input type="text" class="form-control" name="value" id="numericInput" placeholder="0.00" required>
				</div>
                <div class="col-lg-1 col-1" id="type" style='font-size: 2.1rem; color: #172f78'></div>
            </div>

            <div class="form-group modal-input flex-wrap" style="display: flex; margin-top: 1%; ">
                <label class="col-lg-2 col-12 control-label"><?=lang('hd_lang.code')?> <span class="text-danger">*</span></label>
                <div class="col-lg-4 col-7">
                    <input type="text" class="form-control" name="code" id="code" required>
                </div>
                <div class="col-lg-4 col-5">
                    <span class="btn btn-sm btn-warning btn-block" id="generate" onclick="generateCode()"><?=lang('hd_lang.generate')?></span>
                </div>
            </div>
			
            <div class="form-group modal-input flex-wrap" style="display: flex; margin-top: 1%;">
                <label class="col-lg-2 col-12 control-label"><?=lang('hd_lang.description')?></label>
                <div class="col-lg-8 col-12">
                    <input type="text" class="form-control" name="description">
                    </div>
            </div>


            <div class="d-flex gap-2 flex-wrap" style='margin-top: 1%;'>
                <div class="form-group modal-input flex-wrap col-sm-5 col-12" style="display: flex;  margin-top: 1%;">
                        <label class="col-lg-4 col-12 control-label"><?=lang('hd_lang.apply_to')?> <span class="text-danger">*</span></label>
                        <div class="col-lg-6 col-12">
                            <select class="select2 form-control" style="width: 100%;" name="apply_to[]" required>
                                <?php $item = new Item(); 
                                
                                foreach($item::get_items() as $item)
                                {?>
                                <option value="<?=$item->item_id?>"><?=$item->item_name?></option>
                                <?php } ?>
                            </select>
                        </div>
                </div>


                <div class="form-group modal-input flex-wrap col-sm-5 col-12" style="display: flex;  margin-top: 1%;">
                        <label class="col-lg-4 col-12 control-label"><?=lang('hd_lang.require')?> (<?=lang('hd_lang.optional')?>)</label>
                        <div class="col-lg-6 col-12">
                            <select class="select2 form-control" style="width: 100%;" name="required[]">
                                <?php $item = new Item();
                                
                                foreach($item::get_items() as $item)
                                {?>
                                <option value="<?=$item->item_id?>"><?=$item->item_name?></option>
                                <?php } ?>
                            </select>
                        </div>
                </div>
            </div>

            <div class="d-flex gap-2 flex-wrap" style='margin-top: 1%;'>
    
                <div class="form-group modal-input flex-wrap col-sm-5 col-12" style="display: flex;  margin-top: 1%;"> 
                    <label class="col-lg-4 col-12 control-label"><?=lang('hd_lang.billing_cycle')?> (<?=lang('hd_lang.optional')?>)</label>
                    <div class="col-lg-6 col-12">
                        <select class="form-control select2options" style="width: 100%;" name="billing_cycle[]">
                            <?php foreach ($options as $key => $value)
                            {?>
                            <option value="<?=$key?>"><?=ucfirst(str_replace('_', ' ', $key))?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>



                <div class="form-group modal-input flex-wrap col-sm-5 col-12" style="display: flex;  margin-top: 1%;">
                    <label class="col-lg-4 col-12 control-label"><?=lang('hd_lang.payment')?> <span class="text-danger">*</span></label>
                    <div class="col-lg-6 col-12">
                        <select class="form-control" name="payment" required>
                            <option value="1"><?=lang('hd_lang.apply_in_first_payment')?></option>
                            <option value="2"><?=lang('hd_lang.apply_in_payment_renewals')?></option>
                        </select>
                    </div>
                </div>

            </div>

            <div class="d-flex gap-2 flex-wrap" style='margin-top: 1%;'>

                <div class="form-group modal-input flex-wrap col-sm-5 col-12" style="display: flex;  margin-top: 1%;">
                    <label class="col-lg-4 col-12 control-label"><?=lang('hd_lang.once_per_order')?></label>
                    <div class="col-lg-6 col-12">
                        <select class="form-control" name="per_order">
                            <option value="0"><?=lang('hd_lang.no')?></option>
                            <option value="1"><?=lang('hd_lang.yes')?></option>
                        </select>
                    </div>
                </div>


                <div class="form-group modal-input flex-wrap col-sm-5 col-12 gap-2" style="display: flex;  margin-top: 1%;">
                    <label class="col-lg-4 col-12 control-label"><?=lang('hd_lang.new_customers_only')?></label>
                    <div class="col-lg-6 col-12">
                        <select class="form-control" name="new_customers">
                            <option value="0"><?=lang('hd_lang.no')?></option>
                            <option value="1"><?=lang('hd_lang.yes')?></option>
                        </select>
                    </div>
                </div>

            </div>

            <div class="d-flex gap-2 flex-wrap" style='margin-top: 1%;'>

            <div class="form-group modal-input flex-wrap col-sm-5 col-12" style="display: flex;  margin-top: 1%;">
                <label class="col-lg-4 col-12 control-label"><?=lang('hd_lang.use_start_end_date')?></label>
                <div class="col-lg-6 col-12">
                    <select class="form-control" name="use_date">
                        <option value="0"><?=lang('hd_lang.no')?></option>
                        <option value="1"><?=lang('hd_lang.yes')?></option>
                    </select>
                </div>
            </div>


		<div class="form-group modal-input flex-wrap col-sm-5 col-12" style="display: flex;  margin-top: 1%;">
			<label class="col-lg-4 col-12 control-label"><?=lang('hd_lang.start_date')?></label>
			<div class="col-lg-6 col-12 input-group date">
				<input class="input-sm input-s datepicker-input form-control" size="16" type="date"
					value="<?=date('Y-m-d')?>" name="start_date"
					data-date-format="<?=$custom->getconfig_item('date_picker_format');?>">
				<span class="input-group-addon">
					<i class="glyphicon glyphicon-calendar"></i>
				</span>
			</div>
		</div>

            </div>            

            <div class="form-group modal-input flex-wrap" style="display: flex; margin-top: 1%; ">
                <label class="col-lg-2 col-12 control-label"><?=lang('hd_lang.end_date')?></label>
                <div class="col-lg-3 col-12">
                    <input class="input-sm input-s datepicker-input form-control" size="16" type="date"
                        value="<?=date('Y-m-d')?>" name="end_date"
                        data-date-format="<?=$custom->getconfig_item('date_picker_format');?>">
                </div>
            </div>



        </div>
        <div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('hd_lang.close')?></a>
            <button type="submit" class="btn btn-<?=$custom->getconfig_item('theme_color');?>"><?=lang('hd_lang.add_promotion')?></button>
            </form>
        </div>
    </div>
    <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<script>
    // Function to generate a random code
    function generateCode() {
        var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        var code = '';
        var length = 8; // Length of the generated code

        // Generate random characters
        for (var i = 0; i < length; i++) {
            code += characters.charAt(Math.floor(Math.random() * characters.length));
        }

        // Set the generated code to the input field
        document.getElementById('code').value = code;
    }

    // Attach click event listener to the button
    document.getElementById('generate').addEventListener('click', generateCode);
	
	 // Get the input element
    var numericInput = document.getElementById('numericInput');

    // Add an event listener to listen for input changes
    numericInput.addEventListener('input', function() {
        // Remove non-numeric characters using a regular expression
        this.value = this.value.replace(/[^0-9.]/g, '');

        // Ensure there's only one decimal point
        if ((this.value.match(/\./g) || []).length > 1) {
            this.value = this.value.slice(0, -1);
        }
    });
</script>
<script>
var currency = '<?=$custom->getconfig_item('default_currency')?>';
var percent = '%';

$('#type').text(currency);

$('#select').on('change', function() {
    var option = $(this).find('option:selected');
    if (option.val() == 1) {
        $('#type').text(currency);
    } else {
        $('#type').text(percent);
    }
});

$('.select2, .select2domains, .select2options').select2();

$('#generate').on('click', function() {
	alert("Helloe");
    $('#code').val(generate(8));
});

function generate(length) {
    var result = '';
    var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    var charactersLength = characters.length;
    for (var i = 0; i < length; i++) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return result;
}
</script>
