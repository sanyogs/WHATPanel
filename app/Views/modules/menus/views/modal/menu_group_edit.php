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
            <h2 class="modal-title">Edit Menu Group Item</h2>
        </div>
        <form method="post" action="<?php echo site_url('menus/edit_menu_group'); ?>">
            <div class="modal-body">

                <div class="form-group">
                    <label for="edit-menu-title">Title</label>
                    <input required type="text" name="menu_group_title" id="menu_group_title" class="form-control" style="width: 100%" value="<?= htmlentities($row->title) ?>" />
                </div>

                <input type="hidden" name="menu_group_id" value="<?php echo $row->id; ?>">

            </div>
            <div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?= lang('hd_lang.close') ?></a>
                <button type="submit" class="btn btn-<?= $custom_helper->getconfig_item('theme_color'); ?>"><?= lang('hd_lang.save') ?></button>
        </form>
    </div>
</div>
</div>