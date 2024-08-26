   <!-- Start Form -->
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
   $custom_name = new custom_name_helper();

        $attributes = array('class' => 'bs-example form-horizontal');
        echo form_open_multipart('settings/update', $attributes); ?>

   <input type="hidden" name="settings" value="<?=$load_setting?>">
   <input type="hidden" name="categories" value="<?=$load_setting?>">
   <input type="hidden" name="return_url" value="<?= base_url() ?>settings/sms">

   <div class="row">
       <div class="col-md-12">
           <?php
        $attributes = array('class' => 'bs-example form-horizontal');
                                echo form_open(base_url('affiliates/config'), $attributes); ?>
           <p class="text-danger"><?php $session = session(); echo $session->getFlashdata('form_errors'); ?></p>

           <div class="form-group">
               <label class="control-label common-label"><?=lang('hd_lang.sms_gateway_active')?></label>
               <label class="inputDiv form-check form-switch input-btn-div">
                   <input type="hidden" value="off" name="sms_gateway" />
                   <input type="checkbox" role="switch" class="form-check-input"
                       <?php if($custom_name->config_item('sms_gateway', $temp_data) == 'TRUE'){ echo "checked=\"checked\""; } ?>
                       name="sms_gateway">
                   <span></span>
               </label>
           </div>



           <div class="form-group">
               <label class="control-label common-label"><?=lang('hd_lang.request_method')?></label>
               <div class="inputDiv">
                   <div class="input-group">
                       <select name="request_method" class="input-sm form-control common-select" id="method">
                           <option value=""><?=lang('hd_lang.select')?></option>
                           <option value="get"
                               <?=($custom_name->config_item('request_method', $temp_data) == 'get') ? "selected" : ''?>>
                               GET
                           </option>
                           <option value="post"
                               <?=($custom_name->config_item('request_method', $temp_data) == 'post') ? "selected" : ''?>>
                               POST
                           </option>
                           <option value="twilio"
                               <?=($custom_name->config_item('request_method', $temp_data) == 'twilio') ? "selected" : ''?>>
                               Twilio
                           </option>
                       </select>
                   </div>
               </div>
           </div>

           <div id="post_fields">
               <div class="form-group">
                   <label class="control-label common-label"><?=lang('hd_lang.encoding')?></label>
                   <div class="inputDiv">
                       <select name="encoding" class="input-sm form-control common-select">
                           <option value="none"
                               <?=($custom_name->config_item('encoding', $temp_data) == 'none') ? "selected" : ''?>>None
                           </option>
                           <option value="json"
                               <?=($custom_name->config_item('encoding', $temp_data) == 'json') ? "selected" : ''?>>JSON
                           </option>
                       </select>
                   </div>
               </div>

               <div class="form-group">
                   <div class="col-lg-12">
                       <label class="control-label common-label"><?=lang('hd_lang.custom_parameters')?></label><br>
                       <small class="myError"> <?=lang('hd_lang.example')?>:
                           uid=1234,auth=1234,somekey=somevalue</small>
                       <textarea class="col-lg-12 input-sm form-control common-input" rows="1"
                           name="custom_parameters"><?=$custom_name->config_item('custom_parameters', $temp_data)?></textarea>
                   </div>
               </div>
           </div>


           <div id="twilio_fields" class="my-5">
               <div class="form-group">
                   <label class="control-label common-label">SID</label>
                   <div class="inputDiv">
                       <input name="twilio_sid" type="text" class="input-sm common-input"
                           value="<?=$custom_name->config_item('twilio_sid', $temp_data)?>">
                   </div>
               </div>

               <div class="form-group">
                   <label class="control-label common-label">Token</label>
                   <div class="inputDiv">
                       <input name="twilio_token" type="text" class="input-sm common-input"
                           value="<?=$custom_name->config_item('twilio_token', $temp_data)?>">
                   </div>
               </div>

               <div class="form-group">
                   <label class="control-label common-label">Twilio Phone Number</label>
                   <div class="inputDiv">
                       <input name="twilio_phone" type="text" class="input-sm common-input"
                           value="<?=$custom_name->config_item('twilio_phone', $temp_data)?>">
                   </div>
               </div>

           </div>


       </div>

       <div class="col-md-12">
           <div class="form-group">
               <label class="control-label common-label"><?=lang('hd_lang.renewal_invoice')?></label>
               <div class="inputDiv form-check form-switch input-btn-div">
                   <input type="hidden" value="off" name="sms_invoice" />
                   <input type="checkbox" role="switch" class="form-check-input"
                       <?php if($custom_name->config_item('sms_invoice', $temp_data) == 'TRUE'){ echo "checked=\"checked\""; } ?>
                       name="sms_invoice">
                   <span></span>
               </div>
           </div>

           <div class="form-group">
               <label class="control-label common-label"><?=lang('hd_lang.invoice_reminder')?></label>
               <div class="inputDiv form-check form-switch input-btn-div">
                   <input type="hidden" value="off" name="sms_invoice_reminder" />
                   <input type="checkbox" role="switch" class="form-check-input"
                       <?php if($custom_name->config_item('sms_invoice_reminder', $temp_data) == 'TRUE'){ echo "checked=\"checked\""; } ?>
                       name="sms_invoice_reminder">
                   <span></span>
               </div>
           </div>

           <div class="form-group">
               <label class="control-label common-label"><?=lang('hd_lang.payments_received')?></label>
               <div class="inputDiv form-check form-switch input-btn-div">
                   <input type="hidden" value="off" name="sms_payment_received" />
                   <input type="checkbox" role="switch" class="form-check-input"
                       <?php if($custom_name->config_item('sms_payment_received', $temp_data) == 'TRUE'){ echo "checked=\"checked\""; } ?>
                       name="sms_payment_received">
                   <span></span>
               </div>
           </div>

           <div class="form-group">
               <label class="control-label common-label"><?=lang('hd_lang.service_suspended')?></label>
               <div class="inputDiv form-check form-switch input-btn-div">
                   <input type="hidden" value="off" name="sms_service_suspended" />
                   <input type="checkbox" role="switch" class="form-check-input"
                       <?php if($custom_name->config_item('sms_service_suspended', $temp_data) == 'TRUE'){ echo "checked=\"checked\""; } ?>
                       name="sms_service_suspended">
                   <span></span>
               </div>
           </div>

           <div class="form-group">
               <label class="control-label common-label"><?=lang('hd_lang.service_unsuspended')?></label>
               <div class="inputDiv form-check form-switch input-btn-div">
                   <input type="hidden" value="off" name="sms_service_unsuspended" />
                   <input type="checkbox" role="switch" class="form-check-input"
                       <?php if($custom_name->config_item('sms_service_unsuspended', $temp_data) == 'TRUE'){ echo "checked=\"checked\""; } ?>
                       name="sms_service_unsuspended">
                   <span></span>
               </div>
           </div>


       </div>
   </div>

   <div id="url_fields">
       <div class="form-group">
           <label class="control-label common-label"><?=lang('hd_lang.sms_gateway_url')?></label>
           <div class="col-lg-12">
               <textarea class="col-lg-12 input-sm form-control common-input" rows="2"
                   name="sms_gateway_url"><?=$custom_name->config_item('sms_gateway_url', $temp_data)?></textarea>
           </div>
       </div>


       <div class="alert alert-info col-lg-12">
           <strong class="myError"><?=lang('hd_lang.variables')?></strong> %NUMBER%, %MESSAGE%.
           <br><br>
           <strong class="myError"><?=lang('hd_lang.sms_gateway_url_example')?></strong>
           <span style='word-break: break-all;'>http://SMS_GATEWAY/sendsms.php?username=USERNAME&password=PASSWORD&number=%NUMBER%&message=%MESSAGE%</span>
       </div>
   </div>



   <div class="text-center mt-4">
   <a href="<?=base_url()?>settings/send_test" data-toggle="ajaxModal"
       class="btn btn-warning btn-sm common-button"><?=lang('hd_lang.send_test')?></a>
   <button class="btn btn-success btn-sm common-button"><?=lang('hd_lang.save_settings')?></button>
   </div>

   </div>
   </form>


   </div>

   </form>

   <!-- End Form -->

   <script type="text/javascript">
$(document).ready(function() {

    <?php if($custom_name->config_item('request_method', $temp_data) == 'post') 
        { ?>
    $('#post_fields').show();
    $('#url_fields').show();
    <?php }
        else
        { ?>
    $('#post_fields').hide();
    <?php } 
        
        if($custom_name->config_item('request_method', $temp_data) == 'get') 
        { ?>
    $('#url_fields').show();
    <?php }


        if($custom_name->config_item('request_method', $temp_data) != 'get' && $custom_name->config_item('request_method', $temp_data) != 'post') 
        { ?>
    $('#url_fields').hide();
    <?php }
   
        if($custom_name->config_item('request_method', $temp_data) == 'twilio') 
        { ?>
    $('#twilio_fields').show();
    <?php }
        else
        { ?>
    $('#twilio_fields').hide();
    <?php } ?>

    $('#method').on('change', function() {
        var val = $(this).find('option:selected').val();
        if (val == 'post') {
            $('#post_fields').show(500);
            $('#url_fields').show(500);
        } else {
            $('#post_fields').hide(500);
        }

        if (val == 'get') {
            $('#url_fields').show(500);
        }

        if (val != 'get' && val != 'post') {
            $('#url_fields').hide(500);
        }


        if (val == 'twilio') {
            $('#twilio_fields').show(500);
        } else {
            $('#twilio_fields').hide(500);
        }
    });

});
   </script>