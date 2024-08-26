<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */
use App\Models\Ticket;
use App\Models\User;
use App\Models\App;

use App\Helpers\custom_name_helper;

$custom = new custom_name_helper();

$info = Ticket::view_by_id($id);
?>
<div class="box">
	<?= $this->extend('layouts/users') ?>   

	<?= $this->section('content') ?>
	<div class="box-header b-b clearfix common-h p-3 pb-0">
		<?= lang('hd_lang.ticket_details') ?> - <?= $info->ticket_code ?>
		<a href="<?= base_url() ?>tickets/view/<?= $info->id ?>" data-original-title="<?= lang('hd_lang.view_details') ?>"
			data-toggle="tooltip" data-placement="bottom"
			class="btn btn-<?= $custom->getconfig_item('theme_color'); ?> btn-sm pull-right common-button"><i class="fa fa-info-circle"></i>
			<?= lang('hd_lang.ticket_details') ?>
		</a>

	</div>
	<div class="box-body p-3 pt-0">
		<?php

		$session = \Config\Services::session();

		echo $session->getFlashdata('form_error'); ?>

		<?php
		$attributes = array('class' => 'bs-example form-horizontal', 'method' => 'post');
		echo form_open_multipart(base_url() . 'tickets/edit', $attributes);
		?>

		<input type="hidden" name="id" value="<?= $info->id ?>">

		<div class="form-group modal-input flex-wrap mt-3">
			<label class="col-md-2 col-sm-4 col-12 control-label common-label">
				<?= lang('hd_lang.ticket_code') ?> <span class="text-danger">*</span>
			</label>
			<div class="col-md-6 col-sm-8 col-12">
				<input type="text" class="form-control common-input" value="<?= $info->ticket_code ?>" name="ticket_code">
			</div>
		</div>

		<div class="form-group modal-input flex-wrap m-0">
			<label class="col-md-2 col-sm-4 col-12 control-label common-label">
				<?= lang('hd_lang.subject') ?> <span class="text-danger">*</span>
			</label>
			<div class="col-md-6 col-sm-8 col-12">
				<input type="text" class="form-control common-input" value="<?= $info->subject ?>" name="subject">
			</div>
		</div>

		<div class="form-group modal-input flex-wrap m-0">
			<label class="col-md-2 col-sm-4 col-12 control-label common-label">
				<?= lang('hd_lang.priority') ?> <span class="text-danger">*</span>
			</label>
			<div class="col-md-6 col-sm-8 col-12">
				<div class="m-b">
					<select name="priority" class="form-control common-select mb-3">
						<option value="<?= $info->priority ?>">
							<?= lang('hd_lang.use_current') ?>
						</option>
						<?php

						$session = \Config\Services::session(); 

						// Connect to the database  
						$db = \Config\Database::connect();

						$priorities = $db->table('hd_priorities')->get()->getResult();
						foreach ($priorities as $p): ?>
							<option value="<?= $p->priority ?>"><?= lang(strtolower($p->priority)) ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
		</div>

		<div class="form-group modal-input flex-wrap m-0">
			<label class="col-md-2 col-sm-4 col-12 control-label common-label">
				<?= lang('hd_lang.department') ?>
			</label>
			<div class="col-md-6 col-sm-8 col-12">
				<div class="m-b">
					<select name="department" class="form-control common-select mb-3">
						<?php
						$departments = App::get_by_where('departments', array('deptid >' => '0'));
						foreach ($departments as $d): ?>
							<option value="<?= $d->deptid ?>" <?= ($info->department == $d->deptid ? ' selected="selected"' : '') ?>>
								<?= strtoupper($d->deptname) ?>
							</option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
		</div>


		<div class="form-group modal-input flex-wrap m-0">
			<label class="col-md-2 col-sm-4 col-12 control-label common-label">
				<?= lang('hd_lang.reporter') ?> <span class="text-danger">*</span>
			</label>
			<div class="col-md-6 col-sm-8 col-12">
				<div class="m-b">
					<select class="select2-option w_260 common-select mb-3" name="reporter">
						<optgroup label="<?= lang('hd_lang.users') ?>">
							<?php foreach (User::all_users() as $user): ?>
								<option value="<?= $user->id ?>" <?= ($info->reporter == $user->id ? ' selected="selected"' : '') ?>>
									<?php echo User::displayName($user->id); ?>
								</option>
							<?php endforeach; ?>
						</optgroup>
					</select>
				</div>
			</div>
		</div>

		<div class="form-group modal-input flex-wrap m-0">
			<label class="col-md-2 col-sm-4 col-12 control-label common-label">
				<?= lang('hd_lang.ticket_message') ?>
			</label>
			<div class="col-md-6 col-sm-8 col-12">
				<textarea name="body" class="form-control textarea common-input" style='height: unset !important;'><?= $info->body ?></textarea>
			</div>
		</div>

		<div id="file_container">
			<div class="form-group modal-input flex-wrap m-0">
				<label class="col-md-2 col-sm-4 col-12 control-label common-label">
					<?= lang('hd_lang.attachment') ?>
				</label>
				<div class="col-md-6 col-sm-8 col-12">
					<input class="common-input" type="file" name="ticketfiles[]">
				</div>
			</div>

		</div>

		<a href="#" class="btn btn-primary btn-xs common-button m-2" id="add-new-file">
			<?= lang('hd_lang.upload_another_file') ?>
		</a>
		<a href="#" class="btn btn-secondary btn-xs common-button m-2" id="clear-files">
			<?= lang('hd_lang.clear_files') ?>
		</a>
		<button type="submit" class="btn btn-sm btn-<?= $custom->getconfig_item('theme_color') ?> common-button m-2"><i class="fa fa-ticket"></i>
			<?= lang('hd_lang.edit_ticket') ?>
		</button>

		<div class="line line-dashed line-lg pull-in"></div>



		</form>

		<!-- End ticket -->

	</div>
	<?= $this->endSection() ?>
</div>


<!-- End edit ticket -->


<script type="text/javascript">
	(function ($) {
		"use strict";
		$('#clear-files').on('click', function () {
			$('#file_container').html(
				"<div class='form-group'>" +
				"<label class='col-md-2 col-sm-4 col-12 control-label'> <?= lang('hd_lang.attachment') ?></label>" +
				"<div class='col-md-6 col-sm-8 col-12'>" +
				"<input type='file' name='ticketfiles[]'>" +
				"</div></div>"
			);
		});

		$('#add-new-file').on('click', function () {
			$('#file_container').append(
				"<div class='form-group'>" +
				"<label class='col-md-2 col-sm-4 col-12 control-label'> <?= lang('hd_lang.attachment') ?></label>" +
				"<div class='col-md-6 col-sm-8 col-12'>" +
				"<input type='file' name='ticketfiles[]'>" +
				"</div></div>"
			);
		});
	})(jQuery);
</script>




<!-- end -->