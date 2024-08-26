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
<div class="modal-dialog my-modal">
  <div class="modal-content">
    <div class="modal-header row-reverse"> <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h4 class="modal-title common-title"><?=lang('hd_lang.ban_user')?> - <?=strtoupper($username)?></h4>
    </div><?php
    $attributes = array('class' => 'bs-example form-horizontal');
    echo form_open(base_url().'account/ban', $attributes); ?>

    <div class="modal-body">
      <input type="hidden" name="user_id" value="<?=$user_id?>">

      <div class="form-group">
        <label class="col-lg-4 control-label common-label"><?=lang('hd_lang.ban_reason')?></label>
        <div class="col-lg-8">
          <textarea class="form-control ta common-input" name="ban_reason"><?=User::login_info($user_id)->ban_reason?></textarea>
        </div>
      </div>

    </div>
    <div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('hd_lang.close')?></a>
      <button type="submit" class="btn btn-<?=$custom->getconfig_item('theme_color');?> common-button"><?=lang('hd_lang.save_changes')?></button>
    </form>
  </div>
</div>
<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
