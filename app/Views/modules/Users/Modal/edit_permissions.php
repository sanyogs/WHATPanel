<?php 
use App\Libraries\AppLib;
use App\Helpers\custom_name_helper;

$helper = new custom_name_helper();
$db = \Config\Database::connect();
?>
<style>
	.custom{
		min-height: 20px;
		padding-left: 20px;
		margin-bottom: 0;
		font-weight: 400;
		cursor: pointer;
	}
</style>
<div class="modal-dialog modal-xs">
  <div class="modal-content my-modal">
    <div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button> 
    <h4 class="modal-title"><?=lang('hd_lang.permission_settings')?> <?php
                if (isset($user_id)) {
                    echo ' for '.ucfirst(Applib::get_table_field('hd_users',array('id'=>$user_id),'username'));
                }
                ?></h4>
    </div>
    <div class="modal-body">

		<?php echo form_open('users/account/permissions', ['class' => 'bs-example form-horizontal', 'style' => 'margin-left: 25%;']); ?>
        <input type="hidden" name="settings" value="permissions">
        <input type="hidden" name="user_id" value="<?=$user_id?>">

        <!-- checkbox -->
        <?php
        $permission = $db->table('hd_permissions')->where(array('status'=>'active'))->get()->getResult();

        $current_json_permissions = Applib::get_table_field(Applib::$profile_table,array('user_id'=>$user_id),'allowed_modules');

        if ($current_json_permissions == NULL) {
            $current_json_permissions = '{"settings":"permissions"}';
        }
        $current_permissions = json_decode($current_json_permissions, true);
        foreach ($permission as $key => $p) { ?>
            <div class="checkbox">
                <label class="custom">
                    <input type="hidden" value="off" name="<?=$p->name?>" />
                    <input name="<?=$p->name?>" <?php
                    if ( array_key_exists($p->name, $current_permissions) && $current_permissions[$p->name] == 'on') {
                        echo "checked=\"checked\"";
                    }
                    ?>  type="checkbox">
                    <?=lang('hd_lang.' . $p->name)?>
                </label>
            </div>
            <?php } ?>

        <div class="modal-footer"> 
    <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('hd_lang.close')?></a> 
    <button type="submit" class="btn btn-<?=$helper->getconfig_item('theme_color');?>"><?=lang('hd_lang.save_changes')?></button>
    <?php echo form_close(); ?>
        </div>
    </div>
  </div>
  <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->