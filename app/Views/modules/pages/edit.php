<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */
if($data['mode'] == 'edit'){
    $content = (array)$data['content'][0];
}else{
    $content = NULL;
}

//print_r($data['content'][0]);
//die;
use function App\Libraries\form_error;?>

<?= $this->extend('layouts/users') ?>

<?= $this->section('content') ?>

<div class="row" style="margin-top: 31px !important;">
    <?php if (session()->getFlashdata('success')): ?>
    <div id="flash-message" class="alert alert-success" role="alert">
        <?= session()->getFlashdata('success') ?>
    </div>
    <?php endif; ?>

    <!-- Form start -->
    <?php if($data['mode'] == 'add'){
		echo form_open(base_url("pages/create"), ['class'=>'d-flex flex-wrap']);
	}else { 
		echo form_open(base_url("pages/edit/{$content['id']}"), ['class'=>'d-flex flex-wrap']);
	}
	?>
    <div class="col-md-6 col-12 px-5">
        <div class="box">

            <div class="box-body">
                <div class="form-group">
                    <label class='common-label'><?=lang('hd_lang.title')?></label>
                    <?php echo form_input('title', set_value('title', isset($content['title']) ? $content['title'] : ''), array('class' => 'form-control common-input', 'id' => 'titleInput')); ?>
                    <?php // echo form_error('title', '<p class="text-danger">', '</p>'); ?>
                </div><!-- /.form-group -->
                <div class="form-group">
                    <label class='common-label'><?=lang('hd_lang.slug')?></label>
                    <?php echo form_input('slug', set_value('slug', isset($content['slug']) ? $content['slug'] : ''), array('class' => 'form-control common-input', 'id' => 'slugInput')); ?>
                    <?php // echo form_error('slug', '<p class="text-danger">', '</p>'); ?>
                </div><!-- /.form-group -->
                <div class="form-group">
                    <label class='common-label'><?=lang('hd_lang.body')?></label>
                    <?php echo form_textarea('body', set_value('body', isset($content['body']) ? $content['body'] : '', FALSE), array('class' => 'common-input form-control foeditor ', 'id' => 'body')); ?>
                    <?php // echo form_error('body', '<p class="text-danger">', '</p>'); ?>
                </div><!-- /.form-group -->

                <hr>
            </div><!-- /.box-body -->

        </div><!-- /.box -->
    </div><!-- /.col-md8 -->

    <div class="col-md-6 col-12 px-5">

        <div class="accordianContainer p-0 h-auto">

            <div class="commonAccordian">
                <div class="accordianBtn">
                <h3 class="box-title common-h"><?=lang('hd_lang.page') . ' ' . lang('hd_lang.settings')?></h3>
                    <span class="accordianArrow">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="11"
                        height="20"
                        viewBox="0 0 11 20"
                        fill="none"
                    >
                        <path
                        d="M1.37793 18.1824L9.5972 9.92578L1.37793 1.6692"
                        stroke="#4A3AFF"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        />
                    </svg>
                    </span>
                </div>
                <div class="accordianContent">
                    <div class="accordianText">
                        <div class="box-body">


                            <div class="row">
                                <label class="col-md-6 common-label"><?=lang('hd_lang.publish')?></label>
                                <div class="col-md-6">
                                    <label class="switch common-label">
                                        <input type="hidden" value="off" name="status" class='common-input' />
                                        <div class='form-check form-switch input-btn-div'>
                                        <input type="checkbox" class='form-check-input switch-cus'
                                            <?php if(isset($content['status']) && $content['status'] == 1){ echo "checked=\"checked\""; } ?>
                                            name="status">
                                            </div>
                                        <span class='common-span'></span>
                                    </label>
                                </div>
                            </div>

                            <div class="row">
                                <label class="col-md-6 common-label"><?=lang('hd_lang.right_sidebar')?></label>
                                <div class="col-md-6">
                                    <label class="switch common-label">
                                        <input type="hidden" value="off" name="sidebar_right" class='common-input' />
                                        <div class='form-check form-switch input-btn-div'>
                                        <input type="checkbox" class='form-check-input switch-cus'
                                            <?php if(isset($content['sidebar_right']) && $content['sidebar_right'] == 1){ echo "checked=\"checked\""; } ?>
                                            name="sidebar_right">
                                        </div>
                                        <span class='common-p'></span>
                                    </label>
                                </div>
                            </div>


                            <div class="row">
                                <label class="col-md-6 common-label"><?=lang('hd_lang.left_sidebar')?></label>
                                <div class="col-md-6">
                                    <label class="switch common-label">
                                        <input type="hidden" value="off" name="sidebar_left" />
                                        <div class="form-check form-switch input-btn-div">
                                        <input type="checkbox" class='form-check-input switch-cus'
                                            <?php if(isset($content['sidebar_left']) && $content['sidebar_left'] == 1){ echo "checked=\"checked\""; } ?>
                                            name="sidebar_left">
                                            </div>
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="commonAccordian">
                        <div class="accordianBtn">
                        <h3 class="box-title common-h"><?=lang('hd_lang.page_seo')?></h3>
                            <span class="accordianArrow">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="11"
                                height="20"
                                viewBox="0 0 11 20"
                                fill="none"
                            >
                                <path
                                d="M1.37793 18.1824L9.5972 9.92578L1.37793 1.6692"
                                stroke="#4A3AFF"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                />
                            </svg>
                            </span>
                        </div>
                        <div class="accordianContent">
                            <div class="accordianText">
                            <div class="box-body">

                                <div class="form-group mt-4">
                                    <label class='common-label m-0'><?=lang('hd_lang.meta_title')?></label>
                                    <?php echo form_input('meta_title', set_value('meta_title', isset($content['meta_title']) ? $content['meta_title'] : ''), array('class' => 'form-control common-input', 'placeholder' => lang('hd_lang.use_page_title'))); ?>
                                    <?php // echo form_error('meta_title', '<p class="text-danger">', '</p>'); ?>
                                </div><!-- /.form-group -->

                                <div class="form-group">
                                    <label class='common-label'><?=lang('hd_lang.meta_desc')?></label>
                                    <?php echo form_textarea(array(
                                    'name' => 'meta_desc',
                                    'id' => 'notes',
                                    'value' => set_value(isset($content['meta_desc']) ? $content['meta_desc'] : ''),
                                    'rows' => '3',
                                    'class' => 'common-input form-control ',
                                    'placeholder' => lang('hd_lang.use_site_desc')
                                )); ?>
                                    <?php // echo form_error('meta_desc', '<p class="text-danger">', '</p>'); ?>
                                </div><!-- /.form-group -->
                                </div>
                            </div>
                        </div>
                    </div>
        </div>
        <!-- Custom options -->
        <div class="box">
            <div class="box-body">
                <hr>
                <button type="submit" class="btn btn-success btn-block common-button w-100"><i class="fa fa-save"></i>
                    <?=lang('hd_lang.save')?></button>

            </div><!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
    <!-- /.col-md-4 -->

    <!-- Form close -->
    <?=form_close()?>
</div><!-- /.row -->
<script src="<?= base_url() ?>js/ckeditor/ckeditor.js"></script> 
<script type="text/javascript">
	$(document).ready(function() {
		var textarea = document.getElementsByClassName('foeditor')[0];
		CKEDITOR.replace(textarea, {
			height: 300,
			filebrowserUploadUrl: "<?= base_url() ?>media/upload"
		});
	});
</script>
<?= $this->endSection() ?>
