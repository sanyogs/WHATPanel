<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */
use App\Models\Client;

use App\Helpers\custom_name_helper;
use App\Libraries\AppLib;

$custom = new custom_name_helper();

?>
<div class="box-body">
    <h4 class="subheader text-muted h3 m-3">
        <?= lang('hd_lang.files') ?>

        <a href="<?= base_url() ?>companies/file/add/<?= $i->co_id ?>"
            class="btn btn-<?= $custom->getconfig_item('theme_color'); ?> btn-xs common-button pull-right" data-toggle="ajaxModal"
            data-placement="left" title="<?= lang('hd_lang.upload_file') ?>">
            <i class="fa fa-plus-circle"></i>
            <?= lang('hd_lang.upload_file') ?>
        </a>
    </h4>

    <ul class="list-unstyled p-files" style="padding-top: 20px; border-top: 2px solid #ccc; margin-top: 50px;"> 
        <?php //$this->load->helper('file');
        helper('files');
		$applib = new Applib();
        foreach (Client::has_files($i->co_id) as $key => $f) {
            $icon = $applib->file_icon($f->ext);
            $real_url = base_url() . 'resource/uploads/' . $f->file_name;
            ?>
            <div class="line"></div>
            <li class="list-group-item d-flex justify-content-between align-items-center" style="margin-bottom: 15px;">
                <?php if ($f->is_image == 1): ?>
                    <?php if ($f->image_width > $f->image_height) {
                        $ratio = round(((($f->image_width - $f->image_height) / 2) / $f->image_width) * 100);
                        $style = 'height:100%; margin-left: -' . $ratio . '%';
                    } else {
                        $ratio = round(((($f->image_height - $f->image_width) / 2) / $f->image_height) * 100);
                        $style = 'width:100%; margin-top: -' . $ratio . '%';
                    } ?>
				
				  <div class="file-icon icon-small">
                        <a href="<?= base_url() ?>companies/file/<?= $f->file_id ?>"><img style="<?= $style ?>"
                                src="<?= $real_url ?>" /></a>
                    </div>
                <?php else: ?>
                    <div class="file-icon icon-small" style="font-size: large;"><i class="fa <?= $icon ?> fa-lg"></i>
						<a data-toggle="tooltip" data-placement="right" data-original-title="<?= $f->description ?>"
                    class="text-muted" href="<?= base_url() ?>companies/file/<?= $f->file_id ?>">
                    <?= (empty($f->title) ? $f->file_name : $f->title) ?>
                </a>
				</div>
                <?php endif; ?>
                <div class="pull-right" style="font-size: large;">
                    <a href="<?= base_url() ?>companies/file/delete/<?= $f->file_id ?>" data-toggle="ajaxModal"><i
                            class="fa fa-trash-o text-danger"></i>
                    </a>

                </div>

            </li>


        <?php } ?>
    </ul>

</div>
<!-- End File section -->