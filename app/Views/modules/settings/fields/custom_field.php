<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */
$deptid = isset($_GET['dept'])?$_GET['dept']:'';
?>

<?php
$attributes = array('class' => 'bs-example form-horizontal');
echo form_open(base_url().'settings/add_custom_field',$attributes); ?>
  <input type="hidden" name="deptid" value="<?php echo $deptid;?>">
    <div class="form-group">
      <label class="col-lg-3 control-label"><?=lang('hd_lang.custom_field_name')?> <span class="text-danger">*</span></label>
      <div class="col-lg-8">
        <input type="text" class="form-control" placeholder="<?=lang('hd_lang.eg')?> <?=lang('hd_lang.user_placeholder_username')?>" name="name" required>
      </div>
    </div>
    <div class="form-group">
      <label class="col-lg-3 control-label"><?=lang('hd_lang.field_type')?> <span class="text-danger">*</span> </label>
      <div class="col-lg-8">
        <select name="type" class="form-control">
          <option value="text"><?=lang('hd_lang.text_field')?></option>
        </select> 
      </div>
    </div>
    <button type="submit" class="btn btn-sm btn-primary"><?=lang('hd_lang.button_add_field')?></button>          
</form>
<div class="line line-dashed line-lg pull-in"></div> 
<?php
$fields = $this -> db -> where(array('deptid' => $deptid)) -> get('fields') -> result();
  if (!empty($fields)) {
      foreach ($fields as $key => $f) { ?>
<label class="label label-danger"><a class="text-white" href="<?=base_url()?>settings/edit_custom_field/<?=$f->id?>" data-toggle="ajaxModal" title = ""><?=$f->name?></a></label>
<?php } } ?>