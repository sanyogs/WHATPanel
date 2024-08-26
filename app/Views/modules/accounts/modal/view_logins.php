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

use App\Libraries\AppLib;

$custom_name_helper = new custom_name_helper();
?>
<div class="modal-dialog modal-sm my-modal">
    <div class="modal-content">
        <div class="modal-header row-reverse"> <button type="button" class="close"
                data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><?=ucfirst(lang('hd_lang.login_details'))?></h4>
        </div>
        <div class="modal-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="common-label"><?=lang('hd_lang.username')?></th>
                        <th class="common-label"><?=lang('hd_lang.password')?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="common-label"><?=$item->username?></td>
                        <td class="common-label"><?= (Applib::is_demo()) ? 'Hidden in demo' : $item->password?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('hd_lang.close')?></a>
            </form>
        </div>
    </div>
    <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->