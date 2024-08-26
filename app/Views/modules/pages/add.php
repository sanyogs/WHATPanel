<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */
use function App\Libraries\form_error; ?>
<?= $this->extend('layouts/users') ?>

<?= $this->section('content') ?>
<div class="row" style="margin-top: 31px !important;">

    <?php if (session()->getFlashdata('success')): ?>
    <div id="flash-message" class="alert alert-success" role="alert">
        <?= session()->getFlashdata('success') ?>
    </div>
    <?php endif; ?>


    <!-- Form start -->
    <?= form_open_multipart(base_url('pages/store'), ['class' => 'common-css d-flex justify-content-between px-4']) ?>


    <div class="col-md-8 col-sm-12">

    
        <div class="box">

            <div class="box-body">
                <div class="form-group">
                    <label class='common-label'>
                        <?= lang('hd_lang.title') ?>
                    </label>
                    <?php echo form_input('title', set_value('title'), array('class' => 'form-control common-input', 'id' => 'titleInput')); ?>
                    <?php echo form_error('title', '<p class="text-danger">', '</p>'); ?>
                </div><!-- /.form-group -->

                <div class="form-group">
                    <label class='common-label'>
                        <?= lang('hd_lang.slug') ?>
                    </label>
                    <?php echo form_input('slug', set_value('slug'), array('class' => 'form-control common-input', 'id' => 'slugInput')); ?>
                    <?php echo form_error('slug', '<p class="text-danger">', '</p>'); ?>
                </div><!-- /.form-group -->

                <div class="form-group">
                    <label class='common-label'>
                        <?= lang('hd_lang.body') ?>
                    </label>
                    <?php echo form_textarea('body', set_value('body'), array('class' => 'form-control foeditor common-input', 'id' => 'body')); ?>
                    <?php echo form_error('body', '<p class="text-danger">', '</p>'); ?>
                </div><!-- /.form-group -->


                <hr>
                <div class="box collapsed-box">
                    <div class="box-header with-border">
                        <h3 class="box-title common-h pb-3">
                            <?= lang('hd_lang.page_seo') ?>
                        </h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool common-button" data-widget="collapse" data-toggle="tooltip"
                                title="Collapse"><i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">

                        <div class="form-group">
                            <label class='common-label'>
                                <?= lang('hd_lang.meta_title') ?>
                            </label>
                            <?php echo form_input('meta_title', set_value('meta_title'), array('class' => 'form-control common-input', 'placeholder' => lang('hd_lang.use_page_title'))); ?>
                            <?php echo form_error('meta_title', '<p class="text-danger">', '</p>'); ?>
                        </div><!-- /.form-group -->

                        <div class="form-group">
                            <label class='common-label'>
                                <?= lang('hd_lang.meta_desc') ?>
                            </label>
                            <?php echo form_textarea(array(
                                'name' => 'meta_desc',
                                'id' => 'meta_desc',
                                'value' => set_value('meta_desc'),
                                'rows' => '3',
                                'class' => 'form-control common-input',
                                'placeholder' => lang('hd_lang.use_site_desc')
                            )); ?>
                            <?php echo form_error('meta_desc', '<p class="text-danger">', '</p>'); ?>
                        </div><!-- /.form-group -->
                    </div>
                </div>
            </div>

            <!-- /.box-body -->

        </div><!-- /.box -->
    
    </div><!-- /.col-md8 -->

    <div class="col-md-3 col-sm-12">

        
        <div class="accordianContainer">

            <div class="commonAccordian">
              <div class="accordianBtn">
                <h3 class="box-title common-h">
                    <?= lang('hd_lang.page') . ' ' . lang('hd_lang.settings') ?>
                </h3>
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
                            <label class="col-md-6 common-label">
                                <?= lang('hd_lang.publish') ?>
                            </label>
                            <div class="col-md-6">
                                <label class="switch common-label">
                                    <input type="hidden" value="off" name="status" />
                                    <input type="checkbox" name="status">
                                    <span></span>
                                </label>
                            </div>
                        </div>

                        <div class="row">
                            <label class="col-md-6 common-label">
                                <?= lang('hd_lang.right_sidebar') ?>
                            </label>
                            <div class="col-md-6">
                                <label class="switch common-label">
                                    <input type="hidden" value="off" name="sidebar_right" />
                                    <input type="checkbox" name="sidebar_right">
                                    <span></span>
                                </label>
                            </div>
                        </div>

                        <div class="row">
                            <label class="col-md-6 common-label">
                                <?= lang('hd_lang.left_sidebar') ?>
                            </label>
                            <div class="col-md-6">
                                <label class="switch common-label">
                                    <input type="hidden" value="off" name="sidebar_left" />
                                    <input type="checkbox" name="sidebar_left">
                                    <span></span>
                                </label>
                            </div>
                        </div>

                        <div class="row">
                            <label class="col-md-6 common-label">
                                <?= lang('hd_lang.show_in_menu') ?>
                            </label>
                            <div class="col-md-6">
                                <select name="menu" class="form-control common-select">
                                    <option value="0">
                                        <?= lang('hd_lang.none') ?>
                                    </option>
                                    <?php  
                                            foreach($menu_groups AS $menu) { ?>
                                    <option value="<?=$menu->id?>" <?=(isset($content->menu) && $menu->id == $content->menu) ? 'selected': '' ?>>
                                        <?=$menu->title?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
              </div>
            </div>

            <div class="commonAccordian">
              <div class="accordianBtn">
                <h3 class="box-title common-h">
                    <?= lang('hd_lang.faq_block') ?>
                </h3>
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
                            <label class="col-md-6 common-label">
                                <?= lang('hd_lang.display') ?>
                            </label>
                            <div class="col-md-6">
                                <label class="switch common-label">
                                    <input type="hidden" value="off" name="faq" />
                                    <input type="checkbox" <?php if (isset($content->faq) && $content->faq == 1) {
                                        echo "checked=\"checked\"";
                                    } ?> name="faq">
                                    <span></span>
                                </label>
                            </div>
                        </div>

                        <label>
                            <?= lang('hd_lang.category') ?>
                        </label>
                        <select name="faq_id" class="form-control common-select">
                            <option value="0">
                                <?= lang('hd_lang.none') ?>
                            </option>
                            <!-- Add other options as needed -->
                        </select>
                    </div>
                </div>
              </div>
            </div>

            <div class="commonAccordian">
              <div class="accordianBtn">
                <h3 class="box-title common-h">
                    <?= lang('hd_lang.knowledgebase') ?>
                </h3>
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
                            <label class="col-md-6 common-label">
                                <?= lang('hd_lang.display') ?>
                            </label>
                            <div class="col-md-6">
                                <label class="switch common-label">
                                    <input type="hidden" value="off" name="knowledge" />
                                    <input type="checkbox" name="knowledge">
                                    <span></span>
                                </label>
                            </div>
                        </div>

                        <label class='common-label'>
                            <?= lang('hd_lang.category') ?>
                        </label>
                        <select name="knowledge_id" class="form-control common-select">
                            <option value="0">
                                <?= lang('hd_lang.none') ?>
                            </option>
                            <!-- Add other options as needed -->
                        </select>

                        <br />

                        <div class="form-group">
                            <label class='common-label'>Video URL</label>
                            <?php echo form_input('video', '', array('class' => 'form-control', 'placeholder' => 'https://www.youtube.com/embed/QJHqLJLQLQ8')); ?>
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
                <button type="submit" class="btn btn-success btn-block common-button"><i class="fa fa-save"></i>
                    <?= lang('hd_lang.save') ?>
                </button>

            </div><!-- /.box-body -->
        </div>
        <!-- /.box -->
        <!-- Form close -->
        <?= form_close() ?>

    </div><!-- /.row -->
    <?= $this->endSection() ?>

    <script>
    $('#titleInput').on('keyup', function() {
        var path = $(this).val();
        path = path.replace(/ /g, "_").replace("/", "_").toLowerCase();
        $('#slugInput').val(path);
    });

    //accordian js start 
        $('.commonAccordian').click(function(){
        $(this).toggleClass('active');
        $(this).find('.accordianBtn .accordianArrow').toggleClass('active');
        var accordianContent = $(this).find('.accordianContent');
        if(accordianContent.height() > 0) {
            accordianContent.css('height', '0px');
            $(this).find('.accordianBtn').removeClass('open');
        } else {
            var autoHeight = accordianContent.prop('scrollHeight') + 'px';
            accordianContent.css('height', autoHeight);
            $(this).find('.accordianBtn').addClass('open');
        }
    });
    //accordian js end 
    </script>
