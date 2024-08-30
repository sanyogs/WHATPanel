<?php
/*
* This file is part of WHATPANEL.
*
* @package     WHAT PANEL – Web Hosting Access Terminal Panel.
* @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
* @license     BSL; see LICENSE.txt
* @link        https://www.version-next.com
*/

use App\Libraries\AppLib;
use App\Models\App;
use App\Models\Ticket;
use App\Models\User;

use App\Helpers\custom_name_helper;

$info = Ticket::view_by_id($id);

$session = \Config\Services::session();

$custom = new custom_name_helper();

// Connect to the database  
$db = \Config\Database::connect();

?>
<!--Start -->

<div class="box">
	<?= $this->extend('layouts/users') ?>

	<?= $this->section('content') ?>
	<div class="box-header b-b clearfix hidden-print p-3 custom-ticket-header">

		<a href="#t_info" class="btn btn-sm btn-twitter btn-responsive bg-primary common-button" id="info_btn" data-toggle="class:hide"><i class="fa fa-info-circle"></i></a>
		<?php if (!User::is_client()) { ?>
			<a href="<?= base_url() ?>tickets/edit/<?= $info->id ?>" class="btn btn-sm btn-warning btn-responsive common-button">
				<i class="fa fa-pencil"></i> <?= lang('hd_lang.edit_ticket') ?></a>
		<?php } ?>

		<span class='custom-ticket-dropdown'>
			<button class="btn btn-sm btn-<?= $custom->getconfig_item('theme_color') ?> dropdown-toggle btn-responsive common-button" data-toggle="dropdown">
				<?= lang('hd_lang.change_status') ?>
				<span class="caret"></span></button>
			<ul class="dropdown-menu">
				<?php
				$statuses = $db->table('hd_status')->whereIn('status', ['open', 'in_process', 'on_hold', 'closed', 'resolved', 'pending'])->get()->getResult();
				foreach ($statuses as $key => $s) { ?>
					<li><a href="<?= base_url() ?>tickets/status/<?= $info->id ?>/<?= $s->status ?>"><?= lang($s->name) ?></a>
					</li>
				<?php } ?>
			</ul>
		</span>

		<?php if (User::is_admin()) { ?>
			<a href="<?= base_url() ?>tickets/delete/<?= $info->id ?>" class="btn btn-sm btn-danger pull-right btn-responsive common-button" data-toggle="ajaxModal">
				<i class="fa fa-trash-o"></i> <?= lang('hd_lang.delete_ticket') ?></a>

		<?php } ?>


	</div>

	<div class="box-body p-3 pt-0 custom-ticket-view">


		<?php
		$rep = $db->table('hd_ticketreplies')
			->where('ticketid', $info->id)
			->countAllResults();

		if ($rep == 0 and $info->status != 'closed') { ?>

			<div class="alert alert-success hidden-print common-p text-success my-3">
				<button type="button" class="close border-0 p-2 rounded-5" data-dismiss="alert">×</button> <i class="fa fa-warning"></i>
				<?= lang('hd_lang.ticket_not_replied') ?>
			</div>
		<?php } ?>


		<!-- Start ticket Details -->
		<div class="row">
			<section class="row">
				<div class="col-lg-4 col-12" id="t_info">


					<?php if (!User::is_client()) { ?>

						<?php echo form_open(base_url() . 'tickets/quick_edit'); ?>

						<input class="common-input" type="hidden" name="id" value="<?= $info->id ?>">
						<div class="form-group modal-input align-items-center   my-4">
							<label class="common-label col-lg-5 col-sm-3 col-12"><?= lang('hd_lang.ticket_code') ?></label>
							<input type="text" class="form-control common-input" value="<?= $info->ticket_code ?>" required="" readonly="readonly">
						</div>

						<div class="form-group modal-input align-items-center   my-4">
							<label class="common-label col-lg-5 col-sm-3 col-12"><?= lang('hd_lang.created') ?> </label>
							<input type="text" class="form-control common-input" value="<?php echo strftime($custom->getconfig_item('date_format') . " %H:%M", strtotime($info->created)); ?>" required="" readonly="readonly">
						</div>

						<div class="form-group modal-input align-items-center   my-4">
							<label class="common-label col-lg-5 col-sm-3 col-12"><?= lang('hd_lang.reporter') ?> <span class="text-danger">*</span></label>
							<div class="m-b col-lg-7 col-sm-9 col-12">
							<select class="select2-option form-control common-select" name="reporter" required="" disabled>
									<?php foreach (User::all_users() as $user) : ?>
										<option value="<?= $user->id ?>" <?= ($info->reporter == $user->id ? ' selected="selected"' : '') ?>>
											<?php echo User::displayName($user->id); ?></option>
									<?php endforeach; ?>
								</select>
							</div>

						</div>
						<div class="form-group modal-input align-items-center   my-4">
							<label class="common-label col-lg-5 col-sm-3 col-12"><?= lang('hd_lang.department') ?> <span class="text-danger">*</span></label>
							<div class="m-b col-lg-7 col-sm-9 col-12">
								<select name="department" class="form-control common-select" required="">
									<?php
									$departments = App::get_by_where('departments', array('deptid >' => '0'));
									foreach ($departments as $d) : ?>
										<option value="<?= $d->deptid ?>" <?= ($info->department == $d->deptid ? ' selected="selected"' : '') ?>>
											<?= ucfirst($d->deptname) ?></option>
									<?php endforeach;  ?>
								</select>
							</div>
						</div>


						<div class="form-group modal-input align-items-center   my-4">
							<label class="common-label col-lg-5 col-sm-3 col-12"><?= lang('hd_lang.priority') ?> <span class="text-danger">*</span></label>
							<div class="m-b col-lg-7 col-sm-9 col-12">
								<select name="priority" class="form-control common-select" required="">
									<option value="<?= $info->priority ?>"><?= lang(strtolower($info->priority)) ?></option>
									<?php
									$priorities = $db->table('hd_priorities')->get()->getResult();
									foreach ($priorities as $p) : ?>
										<option value="<?= $p->priority ?>"><?= lang(strtolower($p->priority)) ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>

						<div class="form-group modal-input align-items-center   my-4">
							<label class="common-label col-lg-5 col-sm-3 col-12"><?= lang('hd_lang.status') ?></label>
							<input type="text" class="form-control common-input" value="<?= $info->status ?>" required="" readonly="readonly">
						</div>

						<button type="submit" class="btn btn-sm btn-dark common-button"><?= lang('hd_lang.save_changes') ?></button>
						</form>

					<?php } else { ?>


						<ul class="list-group no-radius small">
							<?php
							if ($info->status == 'open') {
								$s_label = 'danger';
							} elseif ($info->status == 'closed') {
								$s_label = 'success';
							} elseif ($info->status == 'resolved') {
								$s_label = 'primary';
							} else {
								$s_label = 'default';
							}
							?>
							<li class="list-group-item common-p"><span class="pull-right common-span">#<?= $info->ticket_code ?></span><?= lang('hd_lang.ticket_code') ?></li>
							<li class="list-group-item common-p">
								<?= lang('hd_lang.reporter') ?>
								<span class="pull-right common-span">
									<?php if ($info->reporter != NULL) { ?>

										<?php echo User::displayName($info->reporter); ?>

									<?php } else {
										echo 'NULL';
									} ?>
								</span>
							</li>
							<li class="list-group-item common-p">
								<span class="pull-right common-span">
									<?php echo App::get_dept_by_id($info->department); ?>
								</span><?= lang('hd_lang.department') ?>
							</li>
							<?php
							$status_lang = ''; // Initialize the variable
							switch ($info->status) {
								case 'open':
									$status_lang = 'open';
									break;
								case 'closed':
									$status_lang = 'closed';
									break;
								case 'pending':
									$status_lang = 'pending';
									break;
								case 'resolved':
									$status_lang = 'resolved';
									break;
								default:
									// Handle cases where $info->status doesn't match any of the above cases
									$status_lang = ''; // or assign some default value
									break;
							}
							?>
							<li class="list-group-item common-p">
								<span class="pull-right common-span">
									<label class="common-label col-lg-5 col-sm-3 col-12 label label-<?= $s_label ?>">
										<?= ucfirst(lang('hd_lang.' . $status_lang)) ?>
									</label>
								</span><?= lang('hd_lang.status') ?>
							</li>
							<li class="list-group-item common-p"><span class="pull-right common-span"><?= $info->priority ?></span><?= lang('hd_lang.priority') ?></li>

							<li class="list-group-item common-p">
								<span class="pull-right label label-success" data-title="<?= $info->created ?>">

									<?php echo strftime($custom->getconfig_item('date_format') . " %H:%M", strtotime($info->created)); ?>

								</span><?= lang('hd_lang.created') ?>
							</li>


							<?php
							$additional = json_decode($info->additional, true);
							if (is_array($additional)) {
								foreach ($additional as $key => $value) {
									$result = $db->table(Applib::$custom_fields_table)
										->where('uniqid', $key)
										->where('module', 'tickets')
										->get()
										->getResult();

									$row = $result->row_array();
									echo '<li class="list-group-item"><span class="pull-right"></span>' . $row['name'] . '</li>';
								}
							}
							?>

						</ul>


					<?php } ?>
				</div>
				<!-- End ticket details-->


				<style>
					img {
						max-width: 100%;
						height: auto;
					}
				</style>


				<div class="col-lg-8 col-12 ticket_body my-4">
					<strong class="my-4 common-h"><?= $info->subject ?></strong>
					<div class="line line-dashed line-lg pull-in my-4"></div>
					<?php $typography = \Config\Services::typography(); ?>
					<div class="my-4 common-h"><?= $typography->nl2brExceptPre($info->body) ?></div>

					<?php if ($info->attachment != NULL) {
						echo '<div class="line line-dashed line-lg pull-in my-4"></div>';
						$files = '';
						if (json_decode($info->attachment)) {
							$files = json_decode($info->attachment);
							foreach ($files as $f) { ?>
								<a class="label bg-info" href="<?= base_url() ?>resource/attachments/<?= $f ?>" target="_blank"><?= $f ?></a><br>
							<?php }
						} else { ?>
							<a class="label bg-info" href="<?= base_url() ?>resource/attachments/<?= $info->attachment ?>" target="_blank"><?= $info->attachment ?></a><br>
						<?php } ?>

					<?php } ?>
					<div class="line line-dashed line-lg pull-in my-4"></div>

					<section class="comment-list block my-4">
						<!-- ticket replies -->
						<div class="hs-table-overflow table-overflow" style='top: unset !important;'>
							<table class='common-table tickets-reply-table'>
								<?php
								if (count(Ticket::view_replies($id)) > 0) {
									foreach (Ticket::view_replies($id) as $key => $r) {
										$role = User::get_role($r->replierid);
										$role_label = ($role == 'admin') ? 'danger' : 'info';
								?>
								<tr id="comment-id-1">
									<td>
										<div class="sender-div">
											<a href="#"><?php echo User::displayName($r->replierid); ?></a>
											<span class="label bg-<?= $role_label ?>   text-white text-center"> <?php echo ucfirst(User::get_role($r->replierid)) ?></span>
										</div>
									</td>
									<td>

										<?= $r->body ?>

									</td>
									<td>
										<?php if ($r->attachment != NULL) {
											echo '<div class="ticket-attachment pull-in"></div>';
											$replyfiles = '';
											if (json_decode($r->attachment)) {
												$replyfiles = json_decode($r->attachment);
												foreach ($replyfiles as $rf) { ?>
													<a class="label bg-info" href="<?= base_url() ?>resource/attachments/<?= $rf ?>" target="_blank"><?= $rf ?></a><br>
												<?php }
											} else { ?>
												<a href="<?= base_url() ?>resource/attachments/<?= $r->attachment ?>" target="_blank"><?= $r->attachment ?></a><br>
											<?php } ?>

										<?php } ?>
									</td>
									<td>
										<span class="text-muted m-l-sm pull-right common-span">
											<i class="fa fa-clock-o"></i>

											<?php echo strftime($custom->getconfig_item('date_format') . " %H:%M:%S", strtotime($r->time)); ?>
											<?php
											if ($custom->getconfig_item('show_time_ago') == 'TRUE') {
											
											}
											?>

										</span>
									</td>
								</tr>
									<?php }
								} else { ?>

									<tr id="comment-id-1">
										<td colspan='4'>
											<p class="no-reply common-p"><?= lang('hd_lang.no_ticket_replies') ?></p>
										</td>
									</tr>

								<?php } ?>
							</table>
						</div>

						<!-- comment form -->
						<article class="comment-item media my-2" id="comment-form pb-2">
							<a class="pull-left thumb-sm avatar">

								<img src="<?php //echo User::avatar_url(User::get_id()); 
											?>" class="img-circle">

							</a>
							<section class="media-body my-2">
								<section class="panel panel-default my-2">
									<?php
									$attributes = 'class="m-b-none"';
									echo form_open_multipart(base_url() . 'tickets/reply', $attributes); ?>
									<input type="hidden" name="ticketid" value="<?= $info->id ?>">
									<input type="hidden" name="ticket_code" value="<?= $info->ticket_code ?>">
									<input type="hidden" name="replierid" value="<?= User::get_id(); ?>">
									<textarea required="required" class="form-control textarea" name="reply" rows="3" placeholder="<?= lang('hd_lang.ticket') ?> <?= $info->ticket_code ?> <?= lang('hd_lang.reply') ?>"></textarea>

									<footer class="panel-footer bg-light lter my-2">
										<div id="file-container">
											<input type="file" class="filestyle common-input" data-buttonText="<?= lang('hd_lang.choose_file') ?>" data-icon="false" data-classButton="btn btn-default" data-classInput="form-control inline input-s" name="ticketfiles[]">
										</div>
										<div class="line line-dashed line-lg pull-in my-2"></div>
										<a href="#" class="btn btn-xs common-button m-2" id="add-new-file" style="background-color:#1912d3;"><?= lang('hd_lang.upload_another_file') ?></a>
										<a href="#" class="btn btn-xs common-button m-2" id="clear-files" style="background-color:#1912d3;"><?= lang('hd_lang.clear_files') ?></a>
										<div class="line line-dashed line-lg pull-in my-2"></div>
										<button class="btn btn-<?= $custom->getconfig_item('theme_color'); ?> pull-right btn-sm common-button m-3" type="submit"><?= lang('hd_lang.reply_ticket') ?></button>
										<ul class="nav nav-pills nav-sm">
										</ul>
									</footer>
									</form>
								</section>
							</section>
						</article>

						<!-- End ticket replies -->
					</section>
				</div>
				<!-- End details -->
		</div>
		<script>
			document.getElementById('add-new-file').addEventListener('click', function(event) {
				event.preventDefault();

				var fileContainer = document.getElementById('file-container');
				var newInput = document.createElement('input');
				newInput.type = 'file';
				newInput.className = 'filestyle common-input';
				newInput.dataset.buttonText = 'Choose File';
				newInput.dataset.icon = 'false';
				newInput.dataset.classButton = 'btn btn-default';
				newInput.dataset.classInput = 'form-control inline input-s';
				newInput.name = 'ticketfiles[]';

				fileContainer.appendChild(newInput);
			});

			document.getElementById('clear-files').addEventListener('click', function(event) {
				event.preventDefault();

				var fileContainer = document.getElementById('file-container');
				var fileInputs = fileContainer.querySelectorAll('input[type="file"]');
				if (fileInputs.length > 1) {
					fileContainer.removeChild(fileInputs[fileInputs.length - 1]);
				}
			});
		</script>
		<?= $this->endSection() ?>
	</div>
	<!-- End display details -->