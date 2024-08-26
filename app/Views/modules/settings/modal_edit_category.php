

// Connect to the database  
$db = \Config\Database::connect();

$modules = $db->table('hd_categories')->where('parent', 0)->get()->getResult();
// $pricing_tables = array('one', 'two', 'three', 'four', 'five', 'six', 'seven');
$pricing_tables = array('one', 'two', 'three', 'four');
?>

<div class="modal-dialog my-modal">
    <div class="modal-content">
        <div class="modal-header row-reverse">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><?=lang('hd_lang.edit_category')?></h4>
        </div>

                <?php
                $i = $db->table('hd_categories')->where('id',$cat)->get()->getRow();
                $attributes = array('class' => 'bs-example form-horizontal');
                echo form_open(base_url().'settings/edit_category',$attributes); ?>
                <input type="hidden" name="id" value="<?=$i->id?>">
                <input type="hidden" name="module" value="items">
                    <div class="modal-body">

                <div class="form-group modal-input flex-wrap">
                            <label class="col-sm-4 col-12 control-label"><?=lang('hd_lang.cat_name')?> <span class="text-danger">*</span></label>
                            <div class="col-sm-8 col-12">
                                <input type="text" class="form-control" value="<?=$i->cat_name?>" name="cat_name">
                            </div>
                </div>

                <div class="form-group modal-input flex-wrap">
                    <label class="col-sm-4 col-12 control-label"><?=lang('hd_lang.type')?></label>
                    <div class="col-sm-8 col-12">
                        <select class="select2-option form-control" name="parent" required>
                            <?php foreach ($modules as $m) : ?>
                    <option value="<?=$m->id?>" <?=($m->id == $i->parent) ? 'selected="selected"' : '' ;?>><?=ucfirst($m->cat_name)?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <?php if($i->parent == 9 || $i->parent == 10) { ?>
                <div class="form-group modal-input flex-wrap">
                    <label class="col-sm-4 col-12 control-label"><?=lang('hd_lang.pricing_table')?></label>
                    <div class="col-sm-8 col-12">
                        <select class="select2-option form-control" name="pricing_table">
                            <?php foreach($pricing_tables as $table) { ?>
                                <option value="<?=$table?>" <?=($table == $i->pricing_table) ? 'selected' : ''?>><?=ucfirst($table)?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

               <?php } ?>

                <div class="form-group modal-input flex-wrap">
                      <label class="col-sm-4 col-12 control-label"><?=lang('hd_lang.delete_category')?></label>
                      <div class="col-sm-8 col-12">
                        <label class="switch">
						<div class="form-check form-switch input-btn-div">
                          <input class="form-check-input switch-cus" type="checkbox" name="delete_cat">
						</div>
                          <span></span>
                        </label>
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
