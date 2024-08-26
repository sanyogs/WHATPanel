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

$custom = new custom_name_helper();


?>

<div class="modal-dialog my-modal">
    <div class="modal-content">
        <div class="modal-header row-reverse">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><?=lang('hd_lang.add_item')?></h4>
        </div>
        <?php
			$attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'invoices/items/insert',$attributes); ?>
        <input type="hidden" name="invoice" value="<?=$invoice?>">

        <div class="modal-body">

            <div class="form-group">
                <label class="col-lg-4 control-label"><?=lang('hd_lang.item_name')?> <span class="text-danger">*</span></label>
                <div class="col-lg-8">
                    <select name="item" class="form-control" required="required">
                        <option value=""><?=lang('hd_lang.choose_template')?></option>
                        <?php foreach (Invoice::saved_items() as $key => $item) { ?>
                        <option value="<?=$item->item_id?>"><?=$item->item_name?> - <?=$item->unit_cost?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('hd_lang.close')?></a>
            <button style='border:none !important;' type="submit" class="btn btn-<?=$custom->getconfig_item('theme_color')?>"><?=lang('hd_lang.add_item')?></button>
            </form>
        </div>
    </div>
    <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->