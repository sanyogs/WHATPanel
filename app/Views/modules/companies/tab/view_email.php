<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */
use App\Models\Client;

$request = service('request');
$uri = $request->uri;
 $id = $uri->getSegment(3);

$company = Client::view_by_id($company);
$email = $company->company_email;

$attributes = array('class' => 'bs-example form-horizontal');
echo form_open(base_url() . 'companies/send_email', $attributes); ?>
<div class="modal-body p-3">
<?php if (!empty(session()->getFlashdata('message'))) {?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo session()->getFlashdata('message'); ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    </div>
<?php } ?>
<input type="hidden" class="form-control common-input" name="com_id" value="<?= $id ?>" >
    <div class="form-group">
        <label class="col-lg-2 control-label common-label">
            <?= lang('hd_lang.email') ?> <span class="text-danger">*</span>
        </label>
        <div class="col-lg-10">
            <input type="text" class="form-control common-input" name="email" value="<?= $email ?>" required>
        </div>
    </div>


    <div class="form-group">
        <label class="col-lg-2 control-label common-label">CC</label>
        <div class="col-lg-10">
            <input type="text" class="form-control common-input" name="cc">
        </div>
    </div>


    <div class="form-group">
        <label class="col-lg-2 control-label common-label">
            <?= lang('hd_lang.subject') ?> <span class="text-danger">*</span>
        </label>
        <div class="col-lg-10">
            <input type="text" class="form-control common-input" name="subject" required>
        </div>
    </div>


    <div class="form-group">
        <label class="col-lg-2 control-label common-label">
            <?= lang('hd_lang.message') ?> <span class="text-danger">*</span>
        </label>
        <div class="col-lg-10">
            <textarea class="form-control common-input foeditor" rows="10" style='height: unset !important;' name="message" required></textarea>
        </div>
    </div>


</div>
<div class="modal-footer p-3"> <a href="#" class="btn btn-default common-button" data-dismiss="modal">
        <?= lang('hd_lang.close') ?>
    </a>
    <button type="submit" class="btn btn-success common-button">
        <?= lang('hd_lang.send') ?>
    </button>
</div>
</form>