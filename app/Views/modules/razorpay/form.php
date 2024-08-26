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

use App\Helpers\custom_name_helper;

$helper = new custom_name_helper();

?>

<?= $this->extend('layouts/users') ?>

<?= $this->section('content') ?>

<div class="box box-top">
    <div class="box-body">
        <div class="container inner">
            <div class="row">
                <div class="col-md-5">
                    <p>
                        <img src="<?= base_url() ?>images/gateways/razorpay.png" />
                    </p>

                    <div id="payment-errors"></div>

                    <input type="hidden" name="invoice_id" id="invoice_id" value="<?= $info['item_number'] ?>">
                    <input type="hidden" name="currency" id="currency" value="<?= $info['currency'] ?>">
                    <input name="amount" id="amount" value="<?= $info['amount'] + $info['tax_total'] ?>" type="hidden">
                    <div class="d-flex gap-2 align-items-center">
                    <input id="formatted_amount" class='common-input m-0' style='width: unset !important;' value="<?= Applib::format_currency($info['amount'] + $info['tax_total'], 'default_currency') ?>" type="text" readonly>
                    <input type="submit" class="btn btn-sm btn-success common-button " id="buy_now" value="<?= lang('hd_lang.pay_now') ?>" />
                    </div>

                    <p>
                    <div id="msg"></div>
                    </p>

                </div>
                <div id="loader-wrapper" style="display: none">
                    <div id="loader"></div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

    <script>
        function getCurrentDate() {
            const today = new Date();
            const yyyy = today.getFullYear();
            const mm = String(today.getMonth() + 1).padStart(2, '0'); // Months start at 0!
            const dd = String(today.getDate()).padStart(2, '0');
            return `${yyyy}-${mm}-${dd}`;
        }

        $(document).ready(function() {

            $('#buy_now').on('click', function(e) {

                e.preventDefault();

                let invoice_id = $('#invoice_id').val();
                let currency = $('#currency').val();
                let amount = $('#amount').val();
				
				console.log(invoice_id);
				console.log(currency); 
				console.log(amount);

                var paymentOption = "netbanking";

                var formData = {
                    invoice_id: invoice_id,
                    currency: currency,
                    amount: amount
                }

                $.ajax({
                    type: 'POST',
                    url: "<?php echo base_url('razorpay/process_payment') ?>",
                    data: formData,
                    dataType: 'json',
                    encode: true,
                }).done(function(data) {

                    if (data.res == 'success') {
						console.log(data);
                        var orderID = data.order_number;
                        var orderNumber = data.order_number;
                        var options = {
                            "key": data.razorpay_key, // Enter the Key ID generated from the Dashboard
                            "amount": data.userData.amount, // Amount is in currency subunits. Default currency is INR. Hence, 50000 refers to 50000 paise
                            "currency": "INR",
                            "name": " WHAT PANEL ", //your business name
                            "description": data.userData.description,
                            "image": "https://www.tutorialswebsite.com/wp-content/uploads/2022/02/cropped-logo-tw.png",
                            "order_id": data.userData.rpay_order_id, //This is a sample Order ID. Pass 
                            "handler": function(response) {
                                var paymentid = response.razorpay_payment_id;

                                $.ajax({
                                    url: "<?php echo base_url('razorpay/verify_payment') ?>",
                                    type: "POST",
                                    data: {
                                        order_no: orderID,
                                        payment_id: paymentid,
                                        invoice_id: data.invoice_id,
                                        razorpay_order_id: data.razorpay_order_id,
                                        amount: data.amount,
                                        payment_date: getCurrentDate()
                                    },
                                    dataType: 'json',
                                    success: function(finalresponse) {
                                        if (finalresponse.success == true && finalresponse.result == 'done') {
                                            window.location.href = finalresponse.url
                                        } else {
                                            alert('Please check console.log to find error');
                                            console.log(finalresponse);
                                        }
                                    }
                                })


                            },
                            "prefill": { //We recommend using the prefill parameter to auto-fill customer's contact information especially their phone number
                                "name": data.userData.name, //your customer's name
                                "email": data.userData.email,
                                "contact": data.userData.mobile //Provide the customer's phone number for better conversion rates 
                            },
                            "notes": {
                                "address": " WHAT PANEL "
                            },
                            "config": {
                                "display": {
                                    "blocks": {
                                        "banks": {
                                            "name": 'Pay using ' + paymentOption,
                                            "instruments": [

                                                {
                                                    "method": paymentOption
                                                },
                                            ],
                                        },
                                    },
                                    "sequence": ['block.banks'],
                                    "preferences": {
                                        "show_default_blocks": true,
                                    },
                                },
                            },
                            "theme": {
                                "color": "#3399cc"
                            }
                        };
                        var rzp1 = new Razorpay(options);

                        // Call open() to initiate the Razorpay checkout
                        rzp1.open();
                    }

                });
            });
        });
    </script>

    <?= $this->endSection() ?>