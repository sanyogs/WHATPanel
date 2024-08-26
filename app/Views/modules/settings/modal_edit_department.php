<div class="modal-dialog modal-xl mx-auto">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button> <h4 class="modal-title"><?=lang('hd_lang.edit_department')?></h4>
        </div>
        <?php
        if (!empty($dept_info)) {
            foreach ($dept_info as $key => $d) { ?>
                <?php
                $attributes = array('class' => 'bs-example form-horizontal');
                echo form_open(base_url().'settings1/edit_dept',$attributes); ?>
                    <div class="modal-body">
                        <input type="hidden" name="deptid" value="<?=$d->deptid?>">
                        <div class="form-group">
                            <label class="col-lg-4 control-label common-label"><?=lang('hd_lang.department_name')?> <span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control common-text" value="<?=$d->deptname?>" name="deptname">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-4 control-label"><?=lang('hd_lang.delete_department')?></label>
                            <div class="col-lg-8">
                                <label class="switch">
                                    <input type="checkbox" name="delete_dept">
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('hd_lang.close')?></a>
                        <button type="submit" class="btn btn-success common-btn"><?=lang('hd_lang.save_changes')?></button>
                    </div>
                </form>
        <?php } } ?>
    </div>
    <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->