<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */
use App\Models\App;
use App\Models\User;
use App\Helpers\custom_name_helper;

$user_login = User::login_info($id);
$profile = User::profile_info($id);
$helper = new custom_name_helper();
?>
<div class="modal-dialog my-modal">
	<div class="modal-content">
		<div class="modal-header row-reverse"> <button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title text-danger">
				<?= lang('hd_lang.edit_user') ?> -
				<?php echo User::displayName($id); ?>
			</h4>
		</div>
		<?php
		$attributes = array('class' => 'bs-example form-horizontal');
		echo form_open(base_url() . 'contacts/update', $attributes); ?>

		<div class="modal-body">
			<input type="hidden" name="user_id" value="<?= $profile->user_id ?>">
			<input type="hidden" name="company" value="<?= $profile->company ?>">

			<div class="row">
			<div class="col-6">
				<div class="form-group input-modal">
					<label class="col-lg-6 control-label common-label">
						<?= lang('hd_lang.client_name') ?> <span class="text-danger">*</span>
					</label>
					<div class="col-lg-12">
						<input type="text" class="form-control common-input" value="<?= $profile->fullname ?>" name="fullname">
					</div>
				</div>
			</div>
			<div class="col-6">
				<div class="form-group input-modal">
					<label class="col-lg-6 control-label common-label">
						<?= lang('hd_lang.email') ?> <span class="text-danger">*</span>
					</label>
					<div class="col-lg-12">
						<input type="email" class="form-control common-input" value="<?= $user_login->email ?>" name="email" required>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-6">
				<div class="form-group input-modal">
					<label class="col-lg-6 control-label common-label">
						<?= lang('hd_lang.phone') ?>
					</label>
					<div class="col-lg-12">
						<input type="text" class="form-control common-input" value="<?= $profile->phone ?>" name="phone">
					</div>
				</div>
			</div>
			<div class="col">
				<div class="form-group input-modal">
					<label class="col-lg-6 control-label common-label">
						<?= lang('hd_lang.mobile_phone') ?>
					</label>
					<div class="col-lg-12">
						<input type="text" class="form-control common-input" value="<?= $profile->mobile ?>" name="mobile">
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-6">
				<div class="form-group input-modal">
					<label class="col-lg-6 control-label common-label">Skype</label>
					<div class="col-lg-12">
						<input type="text" class="form-control common-input" value="<?= $profile->skype ?>" name="skype">
					</div>
				</div>
			</div>
			
			<div class="col-6">
				<div class="form-group input-modal">
					<label class="col-lg-6 control-label common-label">
						<?= lang('hd_lang.language') ?>
					</label>
					<div class="col-lg-5">
						<select name="language" class="form-control common-input">
							<?php foreach (App::languages() as $lang) : ?>
								<option value="<?= $lang->name ?>" <?= ($profile->language == $lang->name ? ' selected="selected"' : '') ?>>
									<?= ucfirst($lang->name) ?>
								</option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="form-group input-modal">
				<label class="col-lg-6 control-label common-label">
					<?= lang('hd_lang.locale') ?>
				</label>
				<div class="col-lg-5">
					<select class="select2-option form-control" name="locale">
						<?php foreach (App::locales() as $loc) : ?>
							<option lang="<?= $loc->code ?>" value="<?= $loc->locale ?>" <?= ($helper->getconfig_item('locale') == $loc->locale ? ' selected="selected"' : '') ?>><?= $loc->name ?>
							</option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
		</div>
		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal">
				<?= lang('hd_lang.close') ?>
			</a>
			<button type="submit" class="btn btn-<?= $helper->getconfig_item('theme_color'); ?>">
				<?= lang('hd_lang.save_changes') ?>
			</button>
			</form>
		</div>
	</div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->