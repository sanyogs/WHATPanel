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
            <h1>Bank Transfer Payment</h1>
            <p class="common-label">Please transfer the payment to the following bank account:</p>
            <ul>
                <li class="common-label">Bank Name: <?= $banktransfer_config['bank_name']; ?></li>
                <li class="common-label">Bank Address: <?= $banktransfer_config['bank_address']; ?></li>
                <li class="common-label">Account Number: <?= $banktransfer_config['bank_holder_name']; ?></li>
                <li class="common-label">Account Holder: <?= $banktransfer_config['bank_holder_name']; ?></li>
                <li class="common-label">IFSC Code: <?= $banktransfer_config['ifsc_code']; ?></li>
            </ul>
            <p class="common-label">After transferring the amount, please upload the transaction receipt.</p>
            
            <!-- Form to upload the transaction receipt -->
            <form action="<?= base_url('payment/upload_receipt') ?>" method="post" enctype="multipart/form-data">
                <label for="receipt" class="common-label">Upload Receipt:</label>
                <input type="file" name="receipt" id="receipt">
                <button class="common-button" type="submit">Submit</button>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>