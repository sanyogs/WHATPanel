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

$custom_helper = new custom_name_helper();

?>
<div class="modal-dialog my-modal">
    <div class="modal-content">
        <div class="modal-header row-reverse">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><?=lang('hd_lang.add_currency')?></h4>
        </div>

                <?php
                $attributes = array('class' => 'bs-example form-horizontal');
                echo form_open(base_url().'settings1/add_currency',$attributes); ?>

                    <div class="modal-body">
                        <div class="form-group modal-input">
                            <label class="col-lg-4 control-label"><?=lang('hd_lang.currency_code')?> <span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" placeholder="e.g USD" name="code" required>
                            </div>
                        </div>

                         <div class="form-group modal-input">
                            <label class="col-lg-4 control-label"><?=lang('hd_lang.currency_name')?> <span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" placeholder="e.g Mexican Peso" name="name" required>
                            </div>
                        </div>

                         <div class="form-group modal-input">
                            <label class="col-lg-4 control-label"><?=lang('hd_lang.currency_symbol')?> <span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" placeholder="e.g $" name="symbol" required>
                            </div>
                        </div>

                        <div class="form-group modal-input">
                            <label class="col-lg-4 control-label"><?=lang('hd_lang.xrate')?> <span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" placeholder="e.g 200" name="xrate" required>
                            </div>
                        </div>

                        <div class="form-group modal-input">
                            <label class="col-lg-4 control-label"><?=lang('hd_lang.status')?> <span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                                <select name="status" id="status" class="form-control" required>
                                    <option value="">Select</option>
                                    <option value="1">Active</option>
                                    <option value="0">Disable</option>
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('hd_lang.close')?></a>
                        <button type="submit" class="btn btn-<?=$custom_helper->getconfig_item('theme_color')?>"><?=lang('hd_lang.save_changes')?></button>
                    </div>
                </form>
    </div>
    <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
