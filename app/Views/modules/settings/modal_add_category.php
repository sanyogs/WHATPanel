<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

$session = \Config\Services::session();

// Connect to the database  
$db = \Config\Database::connect();

$builder = $db->table('hd_categories');

$modules = $builder->select('*')
                  ->where('parent', 0)
                  ->get()
                  ->getResult();
?>
<div class="modal-dialog my-modal">
    <div class="modal-content">
        <div class="modal-header row-reverse">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><?=lang('hd_lang.add_category')?></h4>
        </div>
            <input type="hidden" name="module" value="items">
                <?php
                $attributes = array('class' => 'bs-example form-horizontal');
                echo form_open(base_url().'settings/add_category',$attributes); ?>
                    <div class="modal-body">

                        <div class="form-group modal-input flex-wrap">
                            <label class="col-sm-4 col-12 control-label"><?=lang('hd_lang.cat_name')?> <span class="text-danger">*</span></label>
                            <div class="col-sm-8 col-12">
                                <input type="text" class="form-control" name="cat_name">
                            </div>
                        </div>

                        <div class="form-group modal-input flex-wrap">
                        <label class="col-sm-4 col-12 control-label"><?=lang('hd_lang.type')?></label>
                        <div class="col-sm-8 col-12">
                            <select class="select2-option form-control" name="parent" required>
                                <?php foreach ($modules as $m) : ?>
                                    <option value="<?=$m->id?>"><?=ucfirst($m->cat_name)?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    </div>
                    <div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('hd_lang.close')?></a>
                        <button type="submit" class="btn btn-success"><?=lang('hd_lang.save_changes')?></button>
                    </div>
                </form>
    </div>
    <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
