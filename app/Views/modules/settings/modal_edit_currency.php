<div class="modal-dialog my-modal">
    <div class="modal-content">
        <div class="modal-header row-reverse">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><?=lang('hd_lang.edit_currency')?></h4>
        </div>

                <?php
                use App\Helpers\custom_name_helper;
                // $i = $this->db->where('code',$code)->get('currencies')->row();
                $session = \Config\Services::session(); 
                $helper = new custom_name_helper();
                // Connect to the database  
                $db = \Config\Database::connect();

                $i = $db->table('hd_currencies')->where('code', $code)->get()->getRow();
                $attributes = array('class' => 'bs-example form-horizontal');
                echo form_open(base_url().'settings1/edit_currency',$attributes); ?>

                    <div class="modal-body">
                        <div class="form-group modal-input">
                            <label class="col-lg-4 control-label"><?=lang('hd_lang.currency_code')?> <span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" value="<?=$i->code?>" name="code">
                            </div>
                        </div>

                         <div class="form-group modal-input">
                            <label class="col-lg-4 control-label"><?=lang('hd_lang.currency_name')?> <span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" value="<?=$i->name?>" name="name">
                            </div>
                        </div>

                         <div class="form-group modal-input">
                            <label class="col-lg-4 control-label"><?=lang('hd_lang.currency_symbol')?> <span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" value="<?=$i->symbol?>" name="symbol">
                            </div>
                        </div>

                        <div class="form-group modal-input">
                            <label class="col-lg-4 control-label"><?=lang('hd_lang.xrate')?> <span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" value="<?=$i->xrate?>" name="xrate">
                            </div>
                        </div>

                        <div class="form-group modal-input">
                            <label class="col-lg-4 control-label"><?=lang('hd_lang.status')?> <span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                                <select name="status" id="status" class="form-control">
                                    <option value="">Select</option>
                                    <option value="1" <?php echo ($i->status == '1') ? 'selected' : ''; ?>>Active</option>
                                    <option value="0" <?php echo ($i->status == '0') ? 'selected' : ''; ?>>Disable</option>
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('hd_lang.close')?></a>
                        <button type="submit" class="btn btn-<?=$helper->getconfig_item('theme_color')?>"><?=lang('hd_lang.save_changes')?></button>
                    </div>
                </form>
    </div>
    <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
