<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */
use App\Models\User;

use App\Helpers\custom_name_helper;

$custom = new custom_name_helper();
?>
<div class="modal-dialog my-modal modal-cus">
  <div class="modal-content">
    <div class="modal-header"> 
      <h4 class="modal-title"><?=lang('hd_lang.edit_user')?></h4>
      <button type="button" class="close" data-dismiss="modal">&times;</button> 
    </div><?php
       $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'account/auth',$attributes); ?>
          <?php $user = User::view_user($id); ?>
    <div class="modal-body">
       <input type="hidden" name="user_id" value="<?=$id?>">


       <div class="form-group">
        <label class="common-label control-label"><?=lang('hd_lang.username')?></label>
        <div class="inputDiv">
          <input type="text" class="form-control common-input" value="<?=$user->username?>" name="username">
        </div>
      </div>

              <div class="form-group">
        <label class="common-label control-label"><?=lang('hd_lang.email')?> <span class="text-danger">*</span></label>
        <div class="inputDiv">
          <input type="email" class="form-control common-input" value="<?=$user->email?>" name="email" required>
        </div>
        </div>
       
       <div class="form-group">
        <label class="common-label control-label"><?=lang('hd_lang.password')?></label>
        <div class="inputDiv">
          <input type="password" class="form-control common-input" value="<?=set_value('password')?>" name="password">
        </div>
      </div>
      <div class="form-group">
        <label class="common-label control-label"><?=lang('hd_lang.confirm_password')?></label>
        <div class="inputDiv">
          <input type="password" class="form-control common-input" value="<?=set_value('confirm_password')?>" name="confirm_password">
        </div>
      </div>
              

        
        <div class="form-group">
        <label class="common-label control-label"><?=lang('hd_lang.role')?> <span class="text-danger">*</span></label>
        <div class="inputDiv">
        <select name="role_id" class="form-control common-input">
          <?php
          foreach (User::get_roles() as $key => $role) { ?>
            <option value="<?=$role->r_id?>"<?=($user->role_id == $role->r_id ? ' selected="selected"' : '')?>><?=ucfirst($role->role)?></option>
          <?php } ?>          
        </select>
        </div>
        </div>
      
    </div>
    <div class="modal-footer justify-content-center"> 
      <a href="#" class="btn common-button btn-default text-dark" data-dismiss="modal"><?=lang('hd_lang.close')?></a> 
      <button type="submit" class="btn common-button btn-warning btn-<?=$custom->getconfig_item('theme_color');?>"><?=lang('hd_lang.save_changes')?></button>
    </form>
    </div>
  </div>
  <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->