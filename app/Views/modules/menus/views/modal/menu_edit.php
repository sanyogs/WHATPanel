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

$custom_helper = new custom_name_helper;

?>
<div class="modal-dialog my-modal">
    <div class="modal-content">
        <div class="modal-header row-reverse"> <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h2 class="modal-title">Edit Menu Item</h2>
        </div>
        <form method="post" action="<?php echo site_url('menus/save'); ?>">
            <div class="modal-body">

                <div class="form-group">
                    <label for="edit-menu-title">Title</label>
                    <input required type="text" name="title" id="edit-menu-title" class="form-control" style="width: 100%" value="<?= htmlentities($row->title) ?>" />
                </div>
                <div class="form-group">
                    <label for="edit-menu-url">URL</label>
                    <input <?= ($row->page > 0) ? 'readonly' : ''; ?> type="text" name="url" class="form-control" style="width: 100%" id="edit-menu-url" value="<?= $row->url; ?>" />
                </div>

                <?php if ($row->parent_id == 0) : //only top level menu can be moved 
                ?>
                    <div class="form-group">
                        <label for="select_group_id">Group</label>
                        <select name="group_id" id="select_group_id" class="form-control">
						<?php foreach ($menu_groups as $group): ?>
                    	<option value="<?php echo $group->id; ?>" <?php if ($group->id == $row->group_id) {
                        echo 'selected';
							} ?>><?php echo $group->title; ?></option>
						<?php endforeach;
						?>
                        </select>
                    </div>
                    <input type="hidden" name="old_group_id" value="<?php echo $row->group_id; ?>" />
                <?php endif; ?>
                <input type="hidden" name="menu_id" value="<?php echo $row->id; ?>">

            </div>
            <div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?= lang('hd_lang.close') ?></a>
                <button type="submit" class="btn btn-<?= $custom_helper->getconfig_item('theme_color'); ?>"><?= lang('hd_lang.save') ?></button>
        </form>
    </div>
</div>
</div>