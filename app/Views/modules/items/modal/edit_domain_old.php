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

<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><?=lang('hd_lang.edit_item')?></h4>
        </div>
        <?php 
        
            use App\Models\Items_saved;

            $session = \Config\Services::session();

            // Modify the 'default' property	
            	

            // Connect to the database	
            $dbName = \Config\Database::connect();

            $itemModel = new Items_saved($dbName);

            $item = $itemModel::view_item($id);
        
        ?>

        <?php
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url('hosting/edit_item'),$attributes); ?>
        <input type="hidden" name="r_url" value="<?= site_url("hosting/index/domains"); ?>">
        <input type="hidden" name="item_id" value="<?=$item->item_id?>">
        <input type="hidden" name="quantity" value="1">
        <div class="modal-body">

            <div class="form-group">
                <label class="col-lg-4 control-label"><?=lang('hd_lang.category')?> <span class="text-danger">*</span></label>
                <div class="col-lg-5">
                    <select name="category" class="form-control m-b" required>
                        <?php foreach ($categories as $key => $cat) { ?>
                        <option value="<?=$cat->id?>" <?php echo ($item->category == $cat->id) ? 'selected' : ''; ?>>
                            <?=$cat->cat_name?></option>
                        <?php } ?>
                    </select>
                </div>
                <a href="<?=base_url()?>settings/add_category" class="btn btn-<?=$custom_helper->getconfig_item('theme_color');?> btn-sm"
                    data-toggle="ajaxModal" title="<?=lang('hd_lang.add_category')?>"><i class="fa fa-plus"></i>
                    <?=lang('hd_lang.add_category')?></a>
            </div>


            <div class="form-group">
                <label class="col-lg-4 control-label"><?=lang('hd_lang.item_name')?> <span class="text-danger">*</span></label>
                <div class="col-lg-8">
                    <input type="text" class="form-control" value="<?=$item->item_name?>" name="item_name" required>
                </div>
            </div>


            <div class="form-group">
                <label class="col-lg-4 control-label"><?=lang('hd_lang.registrar')?></label>
                <div class="col-lg-8">
                    <select class="form-control" name="default_registrar">
                        <option value=""><?=lang('hd_lang.none')?></option>
                        <?php 

                            use App\Models\Plugin;

                            $session = \Config\Services::session();	

                            	

                            	

                            // Modify the 'default' property	
                            	

                            // Connect to the database	
                            $dbName = \Config\Database::connect();

                            $itemModel = new Plugin($dbName);

                            $registrars = $itemModel::domain_registrars();
                            
                        foreach ($registrars as $registrar) {?>
                        <option value="<?=$registrar->system_name;?>" <?=($item->default_registrar == $registrar->system_name) ? 'selected' : ''?>><?=ucfirst($registrar->system_name);?></option><?php } ?>
                    </select>
                </div>
            </div>


            <div class="form-group">
                <label class="col-lg-4 control-label"><?=lang('hd_lang.display')?></label>
                <div class="col-lg-8">
                    <label class="switch">
                        <input type="hidden" value="off" name="display" />
                        <input type="checkbox" <?php if($item->display == 'Yes'){ echo "checked=\"checked\""; } ?>
                            name="display">
                        <span></span>
                    </label>
                </div>
            </div>


            <div class="form-group">
                <label class="col-lg-4 control-label"><?=lang('hd_lang.registration')?></label>
                <div class="col-lg-8">
                    <input type="text" class="form-control" value="<?=$item->registration?>" name="registration">
                </div>
            </div>


            <div class="form-group">
                <label class="col-lg-4 control-label"><?=lang('hd_lang.transfer')?></label>
                <div class="col-lg-8">
                    <input type="text" class="form-control" value="<?=$item->transfer?>" name="transfer">
                </div>
            </div>


            <div class="form-group">
                <label class="col-lg-4 control-label"><?=lang('hd_lang.renewal')?></label>
                <div class="col-lg-8">
                    <input type="text" class="form-control" value="<?=$item->renewal?>" name="renewal">
                </div>
            </div>


            <div class="form-group">
                <label class="col-lg-4 control-label"><?=lang('hd_lang.max_years')?></label>
                <div class="col-lg-8">
                    <input type="text" class="form-control" value="<?=$item->max_years?>" name="max_years">
                </div>
            </div>


            <div class="form-group">
                <label class="col-lg-4 control-label"><?=lang('hd_lang.tax_rate')?> </label>
                <div class="col-lg-8">
                    <select name="item_tax_rate" class="form-control m-b">
                        <option value="<?=$item->item_tax_rate?>"><?=$item->item_tax_rate?></option>
                        <option value="0.00"><?=lang('hd_lang.none')?></option>
                        <?php foreach ($rates as $key => $tax) { ?>
                        <option value="<?=$tax->tax_rate_percent?>"><?=$tax->tax_rate_name?></option>
                        <?php } ?>
                    </select>
                </div>


                <div class="form-group">
                    <label class="col-lg-4 control-label"><?=lang('hd_lang.order')?></label>
                    <div class="col-lg-8">
                        <input type="text" id="order_by" class="form-control" value="<?=$item->order_by?>"
                            name="order_by">
                    </div>
                </div>


            </div>
            <div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('hd_lang.close')?></a>
                <button type="submit"
                    class="btn btn-<?=$custom_helper->getconfig_item('theme_color');?>"><?=lang('hd_lang.save_changes')?></button>
                </form>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
    <script>
    $(this).showCategoryFields($('#item_category')[0]);
    </script>