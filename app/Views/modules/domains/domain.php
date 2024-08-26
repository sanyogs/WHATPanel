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
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\Plugin;
use App\Models\User;

use App\Helpers\custom_name_helper;

$custom_name_helper = new custom_name_helper();
$session = session();
$successResponse = $session->getFlashdata('successResponse');
if ($successResponse) {
    $successResponse = json_decode($successResponse, true);
    echo '<div class="alert alert-success">' . $successResponse['message'] . '</div>';
}

// echo "<pre>";print_r($order);die;

?>

<!-- Start create invoice -->
<div class="box">
    <div class="box-body">
        <?= $this->extend('layouts/users') ?>

        <?= $this->section('content') ?>

        <?php
        $attributes = array('class' => 'bs-example form-horizontal');
        echo form_open(base_url('domains/edit_domain'), $attributes);
        ?>
        <?php //echo validation_errors(); 
        ?>

        <div class="box-header with-border">
            <h2 class="common-h"><?= $order->domain ?></h2>
        </div>


        <div class="row">

            <div class="col-md-6 mx-auto">

                <input class="form-control" type="hidden" name="co_id" id="co_id" value="<?= $order->client_id; ?>">
                <input class="form-control" type="hidden" name="order_id" id="order_id" value="<?= $order->id; ?>">
                <input class="form-control" type="hidden" name="order_registarar" id="order_registarar" value="<?= $order->registrar; ?>">

                <div class="form-group d-flex align-items-center mb-4">
                    <label class="control-label common-label mb-0"><?= lang('hd_lang.order') ?> :
                        <?= $order->id ?></label>
                    <div class="inputDiv ms-4">
                        <a href="<?php echo base_url('invoices/view/' . $order->invoice_id); ?>" class="btn common-button btn-primary">View</a>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label common-label"><?= lang('hd_lang.order_type') ?></label>
                    <div class="inputDiv">
                        <input class="form-control common-input" type="text" name="order_type" value="<?= $order->type ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label common-label"><?= lang('hd_lang.domain') ?></label>
                    <div class="inputDiv">
                        <input class="form-control common-input" type="text" name="domain" id="domain" value="<?= $order->domain ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label common-label"><?= lang('hd_lang.registrar') ?></label>
                    <div class="inputDiv">
                        <select name="registrar" id="registrar" class="domain_ext common-select form-control mb-3">
                            <option value="none">None</option>
                            <?php
                            $pluginModel = new Plugin();
                            $result = $pluginModel->domain_registrars();
                            foreach ($result as $registrar) { ?>
                                <option value="<?= $registrar->system_name; ?>"><?= $registrar->name; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label common-label"><?= lang('hd_lang.first_amt') ?></label>
                    <div class="inputDiv">
                        <input class="form-control common-input" type="text" name="first_payment_amt" value="<?= $order->total_cost ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label common-label"><?= lang('hd_lang.recurring_amt') ?></label>
                    <div class="inputDiv">
                        <input class="form-control common-input" type="text" name="recurring_amt" value="<?= $order->total_cost ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label common-label"><?= lang('hd_lang.promo_code') ?></label>
                    <div class="inputDiv">
                        <input class="form-control common-input" type="text" name="promo_code" value="<?= $order->promo ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label common-label"><?= lang('hd_lang.subscription_id') ?></label>
                    <div class="inputDiv">
                        <input class="form-control common-input" type="text" name="subscription_id" value="<?= $order->item_id ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label common-label"><?= lang('hd_lang.nameserver_one') ?></label>
                    <div class="inputDiv">
                        <input class="form-control common-input" type="text" name="nameserver_one" value="<?= $order->ns1 ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label common-label"><?= lang('hd_lang.nameserver_two') ?></label>
                    <div class="inputDiv">
                        <input class="form-control common-input" type="text" name="nameserver_two" value="<?= $order->ns2 ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label common-label"><?= lang('hd_lang.nameserver_three') ?></label>
                    <div class="inputDiv">
                        <input class="form-control common-input" type="text" name="nameserver_three" value="<?= $order->ns3 ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label common-label"><?= lang('hd_lang.nameserver_four') ?></label>
                    <div class="inputDiv">
                        <input class="form-control common-input" type="text" name="nameserver_four" value="<?= $order->ns4 ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label common-label"><?= lang('hd_lang.nameserver_five') ?></label>
                    <div class="inputDiv">
                        <input class="form-control common-input" type="text" name="nameserver_five" value="<?= $order->ns5 ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label common-label"><?= lang('hd_lang.registration_per') ?></label>
                    <div class="inputDiv">
                        <input class="form-control common-input" type="number" name="registration_per" value="">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label common-label"><?= lang('hd_lang.registration_date') ?></label>
                    <div class="inputDiv">
                        <input class="form-control common-input" type="date" name="registration_date" value="<?= date('Y-m-d', strtotime($order->date_saved)) ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label common-label"><?= lang('hd_lang.expiry_date') ?></label>
                    <div class="inputDiv">
                        <input class="form-control common-input" type="date" name="expiry_date" value="<?= date('Y-m-d', strtotime($order->renewal_date)) ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label common-label"><?= lang('hd_lang.next_due_date') ?></label>
                    <div class="inputDiv">
                        <input class="form-control common-input" type="date" name="next_due_date" value="<?= date('Y-m-d', strtotime($order->renewal_date)) ?>">
                    </div>
                </div>


            </div>


        </div>

        <div class="row px-2" id="domPage">


            <div class="col-md-6 mx-auto">

                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-check-label common-label" for="flexSwitchCheckChecked">Reset to default
                                nameserver</label>
                            <div class="inputDiv form-check form-switch input-btn-div">

                                <input class="form-check-input" name="reset_domain" type="checkbox" role="switch" id="flexSwitchCheckChecked">

                            </div>
                        </div>


                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label common-label"><?= lang('hd_lang.registrar_lock') ?></label>
                            <div class="inputDiv">
                                <div class="form-check form-switch inputDiv form-check form-switch input-btn-div">
                                    <input class="form-check-input " name="enable" type="checkbox" role="switch" id="flexSwitchCheckChecked">
                                    <label class="form-check-label common-label ms-4 mb-0" for="flexSwitchCheckChecked">Check to
                                        Enable</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label common-label"><?= lang('hd_lang.registrar_cmd') ?></label>
                    <div class="inputDiv">
                        <div class="row btnflexWrap">

                            <!-- <button type="button" class="btn btn-primary common-button" id="register">Register</button> -->


                            <button type="button" class="btn btn-primary common-button" id="transfer">Transfer</button>


                            <button type="button" class="btn btn-primary common-button" id="renew">Renew</button>


                            <button type="button" class="btn btn-primary common-button" id="modify_contact">Modify Contact
                                Details</button>

                            <button type="button" class="btn btn-primary common-button" id="eep_code">Get EEP
                                Code</button>

                            <button type="button" class="btn btn-primary common-button" id="delete">Request Delete</button>

                            <button type="button" class="btn btn-primary common-button" id="release_domain">Release
                                Domain</button>

                            <button type="button" class="btn btn-primary common-button" id="id_protection">Enable ID
                                Protection</button>

                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label common-label"><?= lang('hd_lang.mngmnt_tools') ?></label>
                    <div class="inputDiv">
                        <div class="row row-gap-4">
                            <div class="col-lg-4">
                                <label class="control-label common-label" for="dns_management">DNS
                                    Management</label>
                                <div class="inputDiv form-check form-switch input-btn-div">
                                    <input role="switch" name="dns_management" class="form-check-input" type="checkbox" id="dns_management">

                                </div>
                            </div>
                            <div class="col-lg-4">
                                <label class="control-label common-label" for="email_forwarding">Email
                                    Forwarding</label>
                                <div class="inputDiv form-check form-switch input-btn-div">
                                    <input role="switch" name="email_forwarding" class="form-check-input" type="checkbox" id="email_forwarding">

                                </div>
                            </div>
                            <div class="col-lg-4">
                                <label class="control-label common-label" for="id_protection">ID
                                    Protection</label>
                                <div class="inputDiv form-check form-switch input-btn-div">
                                    <input role="switch" name="id_protection" class="form-check-input" type="checkbox" id="id_protection">

                                </div>
                            </div>
                            <div class="col-lg-4">
                                <label class="control-label common-label" for="disable_auto_renew">Disable Auto
                                    Renew</label>
                                <div class="inputDiv form-check form-switch input-btn-div">
                                    <input role="switch" name="auto_renew" class="form-check-input" type="checkbox" id="disable_auto_renew">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label common-label"><?= lang('hd_lang.mngmnt_tools') ?></label>
                    <div class="table-responsive">
                    <table class="hs-table">
                        <tbody>
                            <tr>
                                <th>Date</th>
                                <th>Reminder</th>
                                <th>To</th>
                                <th>Sent</th>
                            </tr>
                            <tr>
                                <td><?= date('Y-m-d', strtotime($order->renewal_date)); ?></td>
                                <td>Third Reminder</td>
                                <td>mail#version-next.com</td>
                                <td>10 days after expiry</td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn common-button btn-<?= $custom_name_helper->getconfig_item('theme_color'); ?>">Save
                        Changes</button>
                </div>

            </div>
			
			<!-- EPP Code Modal -->
			<div class="modal fade" id="eppCodeModal" tabindex="-1" role="dialog" aria-labelledby="eppCodeModalLabel" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="eppCodeModalLabel">EPP Code</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<input type="text" id="epp_code" class="form-control" readonly>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						</div>
					</div>
				</div>
			</div>

                </form>
        </div>
    </div>

    <script>
        $(document).ready(function(e) {
            $('#modify_contact').on('click', function(e) {

                var co_id = $('#co_id').val();

                var registrar = $('#order_registarar').val();

                var viewfile = registrar + '_modify_details';

                e.preventDefault();

                $.ajax({
                    url: '<?php echo base_url('domains/'); ?>' + viewfile,
                    data: {
                        co_id: co_id
                    },
                    type: 'POST',
                    dataType: 'json',
                    success: function(data) {
                        // Populate modal fields with data
                        $('#co_id_modal').val(data.co_id);
                        $('#contact_id_modal').val(data.contact_id);
                        $('#db_contact_id_modal').val(data.contact_id);
                        $('#user_name_modal').val(data.user_name);
                        $('#company_modal').val(data.company);
                        $('#email_modal').val(data.user_name);
                        $('#phone-cc_modal').val(data.telnocc);
                        $('#phone_modal').val(data.tel_no);
                        $('#address-line-1_modal').val(data.address1);
                        $('#address-line-2_modal').val(data.address2);
                        $('#city_modal').val(data.city);
                        $('#state_modal').val(data.state);
                        $('#country_modal').val(data.country);
                        $('#zipcode_modal').val(data.zip);

                        // Show the modal
                        $('#contactModal').modal('show');
                        $('#contactModal').addClass('show');
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            });

            $('#renew').on('click', function(e) {
                var domain = $('#domain').val();

                e.preventDefault();

                $.ajax({
                    url: '<?php echo base_url('resellerclub_renewal_info'); ?>',
                    data: {
                        domain: domain
                    },
                    type: 'POST',
                    dataType: 'json',
                    success: function(response) {
                        // Populate your modal select dropdown
                        var select = $('#renewalSelect');
                        var year = 1;
                        $.each(response.data, function(key, value) {
                            select.append($('<option></option>').attr(
                                'value', year).text(year +
                                ' Year'));
                            year++;
                        });

                        $('#renewModal').modal('show');
                        $('#renewModal').addClass('show');
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            });

            // AJAX request to save changes
            $('#save_changes_btn').on('click', function() {
                $.ajax({
                    url: "<?php echo base_url('domains/edit_contact_details'); ?>",
                    type: "POST",
                    data: $('form').serialize(),
                    success: function(response) {
                        // Handle success
                        console.log(response);
                        // $('#contactModal').modal('hide');
                    },
                    error: function(xhr, status, error) {
                        // Handle error
                        console.error(error);
                    }
                });
            });

            // $('#renewalSelect').on('change', function(e){
            //     e.preventDefault();

            //     var renewal_year = $('#renewalSelect').val();

            //     var domain = $('#domain').val();

            //     $.ajax({
            //         url: "<?php echo base_url('submit_renewal_details'); ?>",
            //         type: "POST",
            //         data: {
            //             renewal_year: renewal_year,
            //             domain: domain
            //         },
            //         success: function(response) {
            //             // Handle success
            //             console.log(response);
            //             // $('#renewModal').modal('hide');
            //         },
            //         error: function(xhr, status, error) {
            //             // Handle error
            //             console.error(error);
            //         }
            //     });
            // });

            $('#eep_code').on('click', function(e) {
                var domain = $('#domain').val();
                $.ajax({
                    url: "<?php echo base_url('get_eep_code'); ?>",
                    type: "POST",
                    data: {
                        domain: domain
                    },
                    dataType: 'json',
                    success: function(response) {
						
						if (response.status === 'error') {
							$('#epp_code').val(response.message);
							$('#eppCodeModal').modal('show');
							$('#eppCodeModal').addClass('show');
							console.error(response.message);
						}
						else {
							// Handle success
							// console.log(response);
							$('#epp_code').val(response.data.domsecret);

							$('#eppCodeModal').modal('show');
							$('#eppCodeModal').addClass('show');
						}
                    },
                    error: function(xhr, status, error) {
                        // Handle error
						$('#epp_code').val(error);
						
						$('#eppCodeModal').modal('show');
                        $('#eppCodeModal').addClass('show');
                        console.error(error);
                    }
                });
            });

            $('#transfer').on('click', function(e) {
                var domain = $('#domain').val();
                $.ajax({
                    url: "<?php echo base_url('transfer_page'); ?>",
                    type: "POST",
                    data: {
                        domain: domain
                    },
                    dataType: 'json',
                    success: function(response) {
                        // Handle success
                        // console.log(response);
                        var select = $('#userSelect');

                        $.each(response.data, function(key, value) {
                            select.append($('<option></option>').attr(
                                'value', value.co_id).text(value
                                .company_name));
                        });

                        $('#transferModal').modal('show');
                        $('#transferModal').addClass('show');
                    },
                    error: function(xhr, status, error) {
                        // Handle error
                        console.error(error);
                    }
                });
            });

            // $('#userSelect').on('change', function(e){

            //     var domain = $('#domain').val();
            //     var users = $('#userSelect').val();
            //     var co_id = $('#co_id').val();

            //     $.ajax({
            //         url: "<?php echo base_url('transfer'); ?>",
            //         type: "POST",
            //         data: {
            //             domain: domain,
            //             users_co_id: users,
            //             co_id: co_id
            //         },
            //         success: function(response) {

            //             //$('#transferModal').modal('show');
            //         },
            //         error: function(xhr, status, error) {
            //             // Handle error
            //             console.error(error);
            //         }
            //     });
            // });
        });
    </script>
<?= $this->endSection() ?>