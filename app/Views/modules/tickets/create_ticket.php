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

use App\Helpers\AuthHelper;

use App\Helpers\custom_name_helper;

$session = \Config\Services::session();

$dbConn = \Config\Database::connect();

$ticket = new Ticket($dbConn);

$custom = new custom_name_helper();

?>
<!-- Start -->
<div class="box">
	<?= $this->extend('layouts/users') ?>

	<?= $this->section('content') ?>
	<div class="box-body">
		<?php //echo $this->session->flashdata('form_error'); 
		?>

		<?php if (isset($_GET['dept'])) {
			$attributes = array('class' => 'bs-example form-horizontal');
			echo form_open_multipart(base_url() . 'tickets/add/' . $_GET['dept'], $attributes);
		?>

			<input type="hidden" name="department" value="<?= $_GET['dept'] ?>">

			<div class="form-group modal-input flex-wrap align-items-center">
				<label class=" col-sm-3 col-12 control-label common-label">
					<?= lang('hd_lang.ticket_code') ?> <span class="text-danger">*</span>
				</label>
				<div class="col-sm-6 col-12 ">
					<input type="text" class="form-control w_260 common-input" value="<?php echo $ticket->generate_code(); ?>" name="ticket_code" readonly="readonly">
				</div>
			</div>

			<div class="form-group modal-input flex-wrap align-items-center">
				<label class=" col-sm-3 col-12 control-label common-label">
					<?= lang('hd_lang.subject') ?> <span class="text-danger">*</span>
				</label>
				<div class="col-sm-6 col-12 ">
					<input type="text" class="form-control w_260 common-input" placeholder="<?= lang('hd_lang.sample_ticket_subject') ?>" name="subject" required>
				</div>
			</div>
			<?php if (User::is_admin() || User::is_staff()) { ?>

				<div class="form-group modal-input flex-wrap align-items-center">
					<label class=" col-sm-3 col-12 control-label common-label">
						<?= lang('hd_lang.reporter') ?> <span class="text-danger">*</span>
					</label>
					<div class="col-sm-6 col-12 ">
						<div class="m-b">
							<select class="select2-option w_260 common-select" name="reporter" required>
								<optgroup label="<?= lang('hd_lang.users') ?>">
									<?php foreach (User::all_users() as $u) : ?>
										<option value="<?= $u->id ?>"><?= User::displayName($u->id); ?></option>
									<?php endforeach; ?>
								</optgroup>
							</select>
						</div>
					</div>
				</div>
			<?php } ?>



			<div class="form-group modal-input flex-wrap align-items-center">
				<label class=" col-sm-3 col-12 control-label common-label">
					<?= lang('hd_lang.priority') ?> <span class="text-danger">*</span>
				</label>
				<div class="col-sm-6 col-12 ">
					<div class="m-b">
						<select name="priority" class="form-control w_260 common-select">
							<?php
							$db = db_connect($dbConn);
							$priorities = $db->table('hd_priorities')->get()->getResult();

							// $priorities = $this->db->get('priorities')->result();
							foreach ($priorities as $p) : ?>
								<option value="<?= $p->priority ?>"><?= lang(strtolower($p->priority)) ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>
			</div>

			<div class="form-group modal-input flex-wrap align-items-center">
				<label class=" col-sm-3 col-12 control-label common-label">
					<?= lang('hd_lang.ticket_message') ?>
				</label>
				<div class="col-sm-6 col-12 ">
					<textarea name="body" class="form-control textarea common-input common-textarea" style='height: unset !important; ' placeholder="<?= lang('hd_lang.message') ?>"><?php echo set_value('body'); ?></textarea>

				</div>
			</div>



			<div id="file_container">
				<div class="form-group modal-input flex-wrap align-items-center">
					<label class=" col-sm-3 col-12 control-label common-label">
						<?= lang('hd_lang.attachment') ?>
					</label>
					<div class="col-sm-6 col-12 ">
						<input class="common-input" type="file" name="ticketfiles[]">
					</div>
				</div>

			</div>

			<div class='mt-3 d-flex flex-wrap gap-2'>
				<a href="#" class="btn-xs common-button " id="add-new-file">
					<?= lang('hd_lang.upload_another_file') ?>
				</a>
				<a href="#" class="btn-xs common-button " id="clear-files">
					<?= lang('hd_lang.clear_files') ?>
				</a>
			</div>
			<?php
			$dept = isset($_GET['dept']) ? $_GET['dept'] : 0;
			//$additional = $this->db->where(array('deptid' => $dept))->get("fields")->result_array();
			$db = db_connect($dbConn);
			$additional = $db->table('hd_fields')->where(array('deptid' => $dept))->get()->getResult();

			if (is_array($additional) && !empty($additional)) {
				foreach ($additional as $item) {
					$label = ($item['label'] == NULL) ? $item['name'] : $item['label'];
					echo '<div class="form-group modal-input flex-wrap align-items-center">';
					echo ' <label class="col-lg-3 control-label common-label"> ' . $label . '</label>';
					echo ' <div class="col-lg-3">';
					if ($item['type'] == 'text') {
						echo ' <input type="text" class="form-control common-input" name="' . $item['uniqid'] . '">  ';
					}
					echo ' </div>';
					echo ' </div>';
				}
			}
			?>


			<button type="submit" class="common-button mt-3 btn btn-sm btn-<?= $custom->getconfig_item('theme_color') ?>" ><i class="fa fa-ticket"></i>
				<?= lang('hd_lang.create_ticket') ?>
			</button>

			<?php echo form_close(); ?>

		<?php } else {
			$attributes = array('class' => 'bs-example form-horizontal');
			echo form_open(base_url('tickets/add'), $attributes); ?>

			<div class="form-group modal-input flex-wrap align-items-center align-items-center gap-2 flex-wrap">
				<label class=" col-sm-3 col-12 col-sm-4 col-12  control-label common-h">
					<?= lang('hd_lang.department') ?> <span class="text-danger">*</span>
				</label>
				<div class="col-sm-6 col-12  col-sm-6 col-12">
					<div class="m-b">
						<select name="dept" id="deptSelect" class="form-control common-select" required>
							<?php
							// $departments = App::get_by_where('hd_departments', array('deptid >' => '0'));
							$db = db_connect($dbConn);
							$departments = $db->table('hd_departments')->where(array('deptid >' => '0'))->get()->getResult();
							foreach ($departments as $d) : ?>
								<option value="">Select</option>
								<option value="<?= $d->deptid ?>"><?= strtoupper($d->deptname) ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>

				<?php if (AuthHelper::is_admin()) { ?>
					<a href="<?= base_url() ?>settings/?settings=departments" class="btn btn-danger btn-sm" data-toggle="tooltip" title="<?= lang('hd_lang.departments') ?>"><i class="fa fa-plus"></i>
						<?= lang('hd_lang.departments') ?>
					</a>
				<?php } ?>


			</div>
			<button type="submit" class="pull-right common-button my-2 btn btn-sm btn-<?= $custom->getconfig_item('theme_color') ?>">
				<?= lang('hd_lang.select_department') ?>
			</button>

			<?php echo form_close(); ?>
		<?php } ?>
	</div>
	<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
			<script>
				$(document).ready(function() {
					// Function to handle department selection
					$('#deptSelect').on('change', function() {
						var selectedDept = $(this).val();
						var currentUrl = window.location.href;

						// Update the URL with the selected department
						var updatedUrl = updateQueryStringParameter(currentUrl, 'dept', selectedDept);

						// Redirect to the updated URL
						window.location.href = updatedUrl;
					});

					$('#clear-files').on('click', function() {
						$('#file_container').html(
							"<div class='form-group modal-input flex-wrap align-items-center'>" +
							"<label class=' col-sm-3 col-12 control-label common-label'> <?= lang('hd_lang.attachment') ?></label>" +
							"<div class='col-sm-6 col-12 '>" +
							"<input type='file' name='ticketfiles[]'>" +
							"</div></div>"
						);
					});

					$('#add-new-file').on('click', function() {
						$('#file_container').append(
							"<div class='form-group modal-input flex-wrap align-items-center'>" +
							"<label class=' col-sm-3 col-12 control-label common-label'> <?= lang('hd_lang.attachment') ?></label>" +
							"<div class='col-sm-6 col-12 '>" +
							"<input type='file' name='ticketfiles[]'>" +
							"</div></div>"
						);
					});

					// Function to update or add a query string parameter in the URL
					function updateQueryStringParameter(uri, key, value) {
						var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
						var separator = uri.indexOf('?') !== -1 ? "&" : "?";
						if (uri.match(re)) {
							return uri.replace(re, '$1' + key + "=" + value + '$2');
						} else {
							return uri + separator + key + "=" + value;
						}
					}
				});
			</script>
	<?= $this->endSection() ?>
</div>


<!-- End create ticket -->


</section>


<!-- end -->