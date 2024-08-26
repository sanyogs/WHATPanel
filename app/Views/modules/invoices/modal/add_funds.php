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
use App\Models\User;

use App\Helpers\custom_name_helper;

  $user = User::get_id();            
  $user_company = User::profile_info($user)->company;
  $cur = Client::client_currency($user_company);

  $custom = new custom_name_helper();

  ?>
<div class="modal-dialog modal-sm my-modal">
    <div class="modal-content">
        <div class="modal-header row-reverse"> <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><?=lang('hd_lang.add_funds')?></h4>
        </div><?php
            echo form_open(base_url().'invoices/add_funds_invoice'); ?>
        <div class="modal-body">
            <div class="row py-3">
                <input type="hidden" name="company_id" value="<?php echo $company; ?>">
                <div class="col-md-6">
                    <div class="form-group">
                        <input type="text" placeholder="0.00" name="amount" class="input-sm form-control" required>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <input type="text" name="currency" value="<?=$custom->getconfig_item('default_currency')?>"
                            class="input-sm form-control" readonly>
                    </div>
                </div>
            </div>

            <hr>

            <div class="row py-3">
                <div class="col-md-12">
                    <div class="align-items-center d-flex form-group gap-3">
                        <label><?=lang('hd_lang.create_invoice')?></label>
                        <input type="checkbox" name="create_invoice">
                    </div>
                </div>
            </div>

        </div>
        <div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('hd_lang.close')?></a>
            <button type="submit" class="btn btn-<?=$custom->getconfig_item('theme_color');?>" style='border:none;' ><?=lang('hd_lang.add_funds')?></button>
            </form>
        </div>
    </div>
</div>