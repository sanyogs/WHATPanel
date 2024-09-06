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

$custom_name_helper = new custom_name_helper();
?>
<div class="modal-dialog my-modal">
    <div class="modal-content">
        <div class="modal-header row-reverse">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><?= lang('hd_lang.edit_slide') ?></h4>
        </div>

        <?php
        $attributes = array('class' => 'bs-example form-horizontal');
        echo form_open_multipart(base_url('sliders/edit_slide'), $attributes); ?>
        <input type="hidden" name="slide_id" value="<?= $slide->slide_id ?>">
        <input type="hidden" name="current_image" value="<?= $slide->image ?>">
        <div class="modal-body">

            <div class="form-group modal-input">
                <label class="col-3 control-label"><?= lang('hd_lang.title') ?></label>
                <div class="col-9">
                    <input name="title" class="form-control" value="<?= $slide->title ?>" />
                </div>
            </div>

            <div class="form-group modal-input">
                <label class="col-3 control-label"><?= lang('hd_lang.description') ?>
                </label>
                <div class="col-9">
                    <textarea name="description" class="form-control ta common-input" style='height: unset !important;'> <?= $slide->description ?></textarea>
                </div>
            </div>
            
            <div class="form-group my-1">
                <label class="col-lg-3 control-label"><?=lang('hd_lang.button_name1')?></label>
                <div class="col-lg-9">
                <input name="btname_one" class="form-control" value="<?= $slide->btname_one ?>"/>
                </div>
            </div>

            <div class="form-group my-1">
                <label class="col-lg-3 control-label"><?=lang('hd_lang.button_redirect1')?></label>
                <div class="col-lg-9">
                <input name="btn_redirect_one" class="form-control" value="<?= $slide->btn_redirect_one ?>"/>
                </div>
            </div>

            <div class="form-group my-1">
                <label class="col-lg-3 control-label"><?=lang('hd_lang.button_name2')?></label>
                <div class="col-lg-9">
                <input name="btname_two" class="form-control" value="<?= $slide->btname_two ?>"/>
                </div>
            </div>

            <div class="form-group my-1">
                <label class="col-lg-3 control-label"><?=lang('hd_lang.button_redirect2')?></label>
                <div class="col-lg-9">
                <input name="btn_redirect_two" class="form-control" value="<?= $slide->btn_redirect_two ?>"/>
                </div>
            </div>

            <div class="form-group modal-input">
                <?php if (!empty($slide->image)) { ?>
                    <img src="<?= base_url('uploads/' . $slide->image) ?>" class="edit_thumb" width="300" height="100" />
                <?php } ?>
            </div>

            <div id="file_container" class='modal-input'>
                <div class="form-group modal-input">
                    <div class="col-lg-offset-3 col-9">
                        <input type="file" name="images">
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <a href="#" class="btn btn-default" data-dismiss="modal"><?= lang('hd_lang.close') ?></a>
                <button type="submit" class="btn btn-<?= $custom_name_helper->getconfig_item('theme_color'); ?>"><?= lang('hd_lang.save') ?> </button>
            <?php echo form_close(); ?>
            </div>
        </div>
    </div>
    <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->