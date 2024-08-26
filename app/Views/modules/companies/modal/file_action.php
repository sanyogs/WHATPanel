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
$helper = new custom_name_helper();	
?>


<?php $action = (isset($action)) ? $action : ''; ?>

<?php if($action == 'add_file') { ?>


<div class="modal-dialog my-modal">
	<div class="modal-content">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button> <h4 class="modal-title"><?=lang('hd_lang.upload_file')?></h4>
		</div>

	<?php
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open_multipart(base_url().'companies/file/add',$attributes); ?>
          <input type="hidden" name="company" value="<?=$company?>">
		<div class="modal-body">

                <div class="form-group">
                    <label class="col-lg-3 control-label common-label"><?=lang('hd_lang.file_title')?> <span class="text-danger">*</span></label>
                    <div class="col-lg-9">
                    <input name="title" class="form-control common-input" required placeholder="<?=lang('hd_lang.file_title')?>"/>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-3 control-label common-label"><?=lang('hd_lang.description')?></label>
                    <div class="col-lg-9">
                    <textarea name="description" class="form-control common-input ta" placeholder="<?=lang('hd_lang.description')?>" ></textarea>
                    </div>
                </div>

                <div id="file_container">
                    <div class="form-group">
                        <div class="col-lg-offset-3 col-lg-9">
                            <input type="file" name="clientfiles[]" required="">
                        </div>
                    </div>
                </div>

		<div class="modal-footer">
                    <a href="#" class="btn btn-<?=$helper->getconfig_item('theme_color');?> pull-left" id="add-new-file"><?=lang('hd_lang.upload_another_file')?></a>
                    <a href="#" class="btn btn-default pull-left" id="clear-files"><?=lang('hd_lang.clear_files')?></a>
                    <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('hd_lang.close')?></a>
                    <button type="submit" class="btn btn-<?=$helper->getconfig_item('theme_color');?>"><?=lang('hd_lang.upload_file')?></button>
		</form>
		</div>
	        </div>
        </div>

    <script type="text/javascript">
        $('#clear-files').on('click', function(){
            $('#file_container').html(
                "<div class='form-group'>" +
                    "<div class='col-lg-offset-3 col-lg-9'>" +
                    "<input type='file' name='clientfiles[]'>" +
                    "</div></div>"
            );
        });

        $('#add-new-file').on('click', function(){
            $('#file_container').append(
                "<div class='form-group'>" +
                "<div class='col-lg-offset-3 col-lg-9'>" +
                "<input type='file' name='clientfiles[]'>" +
                "</div></div>"
            );
        });
    </script>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->



<?php } ?>

<?php if($action == 'delete_file') { ?>

<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><?=lang('hd_lang.delete_file')?></h4>
        </div><?php
            echo form_open(base_url().'companies/file/delete'); ?>
        <div class="modal-body">
            <p><?=lang('hd_lang.delete_file_warning')?></p>

            <input type="hidden" name="file" value="<?=$file_id?>">

        </div>
        <div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('hd_lang.close')?></a>
            <button type="submit" class="btn btn-danger"><?=lang('hd_lang.delete_button')?></button>
        </form>
    </div>
</div>
<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->


<?php } ?>
