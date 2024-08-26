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

$helper = new custom_name_helper();
?>
<div class="row">
    <!-- Start Form -->
        <div class="col-lg-12">
            <section class="panel panel-default">
                <header class="panel-heading font-bold common-h"><i class="fa fa-inbox"></i> <?=lang('hd_lang.alert_settings')?></header>
                <div class="panel-body">
                    <?php
                    $attributes = array('class' => 'bs-example form-horizontal','data-validate'=>'parsley');
                    echo form_open('settings/update', $attributes); ?>
                    <?php //echo validation_errors(); ?>
                    <input type="hidden" name="settings" value="<?=$load_setting?>">

                    <div class="form-group modal-input gap-2 align-items-center text-danger">
                        <label class="col-md-5 common-label m-0 control-label" 
                        data-title="DISABLE ALL EMAILS" data-placement="right"><?=lang('hd_lang.disable_emails')?></label>
                        <div class="col-md-6">
                            <label class="switch form-switch input-btn-div">
                                <input type="hidden" value="off" name="disable_emails" />
                                <input type="checkbox" class="form-check-input" <?php if($helper->getconfig_item('disable_emails') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="disable_emails">
                                <span></span>
                            </label>

                        </div>
                    </div>

                     <div class="line line-dashed line-lg pull-in"></div>

                    <div class="form-group modal-input gap-2 align-items-center">
                        <label class="col-md-5 common-label m-0 control-label" 
                        data-title="An email containing user login credentials will be sent to new users" data-placement="right"><?=lang('hd_lang.email_account_details')?></label>
                        <div class="col-lg-6">
                            <label class="switch form-switch input-btn-div">
                                <input type="hidden" value="off" name="email_account_details" />
                                <input type="checkbox" class="form-check-input" <?php if($helper->getconfig_item('email_account_details') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="email_account_details">
                                <span></span>
                            </label>

                        </div>
                    </div>

                    <div class="form-group modal-input gap-2 align-items-center">
                        <label class="col-md-5 common-label m-0 control-label" data-title="Send email to admins when a new payment is received" data-placement="right" ><?=lang('hd_lang.notify_payment_received')?></label>
                        <div class="col-md-6">
                            <label class="switch form-switch input-btn-div">
                                <input type="hidden" value="off" name="notify_payment_received" />
                                <input type="checkbox" class="form-check-input" <?php if($helper->getconfig_item('notify_payment_received') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="notify_payment_received">
                                <span></span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group modal-input gap-2 align-items-center">
                        <label class="col-md-5 common-label m-0 control-label"  data-title="Send email to admins/staff when a new ticket created" data-placement="right"><?=lang('hd_lang.email_staff_tickets')?></label>
                        <div class="col-md-6">
                            <label class="switch form-switch input-btn-div">
                                <input type="hidden" value="off" name="email_staff_tickets" />
                                <input type="checkbox" class="form-check-input" <?php if($helper->getconfig_item('email_staff_tickets') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="email_staff_tickets">
                                <span></span>
                            </label>
                        </div>
                    </div>
                    
                    <div class="form-group modal-input gap-2 align-items-center">
                        <label class="col-md-5 common-label m-0 control-label"  data-title="Send email to reporter/staff when a ticket is replied to" data-placement="right"><?=lang('hd_lang.notify_ticket_reply')?></label>
                        <div class="col-md-6">
                            <label class="switch form-switch input-btn-div">
                                <input type="hidden" value="off" name="notify_ticket_reply" />
                                <input type="checkbox" class="form-check-input" <?php if($helper->getconfig_item('notify_ticket_reply') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="notify_ticket_reply">
                                <span></span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group modal-input gap-2 align-items-center">
                        <label class="col-md-5 common-label m-0 control-label"  data-title="Send email to ticket reporter when ticket closed" data-placement="right"><?=lang('hd_lang.notify_ticket_closed')?></label>
                        <div class="col-md-6">
                            <label class="switch form-switch input-btn-div">
                                <input type="hidden" value="off" name="notify_ticket_closed" />
                                <input type="checkbox" class="form-check-input" <?php if($helper->getconfig_item('notify_ticket_closed') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="notify_ticket_closed">
                                <span></span>
                            </label>
                        </div>
                    </div>

                     <div class="form-group modal-input gap-2 align-items-center">
                        <label class="col-md-5 common-label m-0 control-label"  data-title="Send email to staff or client when ticket re-opened" data-placement="right"><?=lang('hd_lang.notify_ticket_reopened')?></label>
                        <div class="col-md-6">
                            <label class="switch form-switch input-btn-div">
                                <input type="hidden" value="off" name="notify_ticket_reopened" />
                                <input type="checkbox" class="form-check-input" <?php if($helper->getconfig_item('notify_ticket_reopened') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="notify_ticket_reopened">
                                <span></span>
                            </label>
                        </div>
                    </div>


                    <div class="form-group modal-input gap-2 align-items-center">
                        <div class="col-lg-offset-6 col-lg-10">
                            <button type="submit" class="btn btn-sm btn-<?=$helper->getconfig_item('theme_color')?> common-button"><i class="fa fa-check"></i> <?=lang('hd_lang.save_changes')?></button>
                        </div>
                    </div>
                    </form>
                </div> </section>
        </div>
    <!-- End Form -->
</div>