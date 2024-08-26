<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */
use App\Models\Plugin;
use App\Helpers\custom_name_helper;
$helper = new custom_name_helper();
?>

<div class="modal-dialog modal-lg my-modal">
    <div class="modal-content">
        <div class="modal-header row-reverse"> <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><?=lang('hd_lang.activate_order')?></h4>
        </div><?php
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'domains/activate',$attributes); ?>
        <div class="modal-body">
            <div class="row">

                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="common-h"><?=lang('hd_lang.service')?></th><?php if($item->item_name == lang('hd_lang.domain_transfer')) { ?>
                             <th class="common-h"><?=lang('hd_lang.authcode')?></th><?php } ?><th><?=lang('hd_lang.nameservers')?></th>
                            <th class="common-h"><?=lang('hd_lang.register_transfer')?></th>
                            <th class="common-h"><?=lang('hd_lang.registrar')?></th>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?=$item->item_name?></td>
                            <?php if($item->item_name == lang('hd_lang.domain_transfer')) { ?><td><input type="text"
                                    value="<?=$item->authcode?>" name="authcode"></td> <?php } ?>
                            <td><?=$item->nameservers?></td>
                            <td>
                                <input type="hidden" name="domain_status" value="<?=$item->status_id?>">
                                <input type="hidden" name="id" value="<?=$item->id?>">
                                <input type="hidden" name="domain" value="<?=$item->item_desc?>">
                                <label class="switch">
                                    <input type="hidden" value="off" name="activate_domain" />
                                    <input type="checkbox"
                                        <?php if($item->status_id == 6){ echo "checked=\"checked\""; } ?>
                                        name="activate_domain">
                                    <span></span>
                                </label>
                            </td>
                            <td>
                                <select name="registrar[]" class="form-control m-b common-select">
                                    <?php                                    
                                    $registrars = Plugin::domain_registrars();
                                    foreach ($registrars as $registrar)
                                    {?>
                                    <option value="<?=$registrar->system_name;?>"><?=ucfirst($registrar->system_name);?>
                                    </option>
                                    <?php } ?>

                                </select>
                            </td>
                        </tr>
                    </tbody>
                </table>


            </div>
            <div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('hd_lang.close')?></a>
                <button type="submit" class="btn btn-<?=$helper->getconfig_item('theme_color');?>"><?=lang('hd_lang.activate')?></button>
                </form>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->