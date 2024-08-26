<?= $this->extend('layouts/users') ?>   

<?= $this->section('content') ?>
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
use App\Models\Client;
use App\Models\User;
use App\ThirdParty\MX\Modules;
use App\Helpers\form_helper;

$db = \Config\Database::connect();
?>
<div class="box">
  <div class="box-body">

    <div class="row">
      <div class="col-lg-6 p-3">
        <!-- Profile Form -->
        <section class="panel panel-default">
          <header class="panel-heading font-bold common-h m-0"><?= lang('hd_lang.profile_details') ?></header>
          <div class="panel-body">
            <?php
            $profile = User::profile_info(User::get_id());
            // echo "<pre>";print_r(User::profile_info(User::get_id()));die;
            $login = User::login_info(User::get_id());
            $attributes = array('class' => 'bs-example form-horizontal');
           	//$helper = new form_helper();
			//echo $helper->form('post', uri_string(), $attributes);

      $attributes = array('class' => 'bs-example form-horizontal', 'method' => 'post');
                        echo form_open(base_url().'profile/settings',$attributes); 
      
      ?>
            <?php //echo validation_errors(); ?>

            <div class="form-group my-3 row align-items-center">
              <label class="col-lg-4 control-label common-label m-0 text-end"><?= lang('hd_lang.full_name') ?> <span class="text-danger">*</span></label>
              <div class="col-lg-8">
                <input type="text" class="form-control common-input m-0" name="fullname" value="<?= isset($profile) ? $profile->fullname : '' ?>" required>
              </div>
            </div>

            

            <?php if (User::is_staff()) { ?>

              <div class="form-group my-3 row align-items-center">
                <label class="col-lg-4 control-label common-label m-0 text-end"><?= lang('hd_lang.hourly_rate') ?> <span class="text-danger">*</span></label>
                <div class="col-lg-8">
                  <input type="text" class="form-control common-input m-0" name="hourly_rate" value="<?= $profile->hourly_rate ?>" required>
                </div>
              </div>

            <?php } ?>
            <input type="hidden" value="<?= isset($profile) ? $profile->company : '' ?>" name="co_id">

            <?php
            if (isset($profile) && isset($profile->company) && $profile->company > 0) {
              // echo $profile->company;die;
              $comp = Client::view_by_id($profile->company);
            ?>
              <div class="form-group my-3 row align-items-center">
                <label class="col-lg-4 control-label common-label m-0 text-end"><?= lang('hd_lang.company') ?> </label>
                <div class="col-lg-8">
                  <input type="text" class="form-control common-input m-0" name="company_data[company_name]" value="<?= (isset($comp->company_name)) ? $comp->company_name : "" ?>" required>
                </div>
              </div>
              <div class="form-group my-3 row align-items-center">
                <label class="col-lg-4 control-label common-label m-0 text-end"><?= lang('hd_lang.company_email') ?> </label>
                <div class="col-lg-8">
                  <input type="text" class="form-control common-input m-0" name="company_data[company_email]" value="<?= (isset($comp->company_email)) ? $comp->company_email : "" ?>" required>
                </div>
              </div>
              <div class="form-group my-3 row align-items-center">
                <label class="col-lg-4 control-label common-label m-0 text-end"><?= lang('hd_lang.company_address') ?> </label>
                <div class="col-lg-8">
                  <input type="text" class="form-control common-input m-0" name="company_data[company_address]" value="<?= (isset($comp->company_address)) ? $comp->company_address : "" ?>" required>
                </div>
              </div>
              <div class="form-group my-3 row align-items-center">
                <label class="col-lg-4 control-label common-label m-0 text-end"><?= lang('hd_lang.company_vat') ?> </label>
                <div class="col-lg-8">
                  <input type="text" class="form-control common-input m-0" name="company_data[vat]" value="<?= (isset($comp->VAT)) ? $comp->VAT : "" ?>">
                </div>
              </div>
            <?php } ?>
            <div class="form-group my-3 row align-items-center">
              <label class="col-lg-4 control-label common-label m-0 text-end"><?= lang('hd_lang.phone') ?></label>
              <div class="col-lg-8">
                <input type="text" class="form-control common-input m-0" name="phone" value="<?= isset($profile) ? $profile->phone : '' ?>">
              </div>
            </div>

            <div class="form-group my-3 row align-items-center">
              <label class="col-lg-4 control-label common-label m-0 text-end"><?= lang('hd_lang.language') ?></label>
              <div class="col-lg-8">
                <select name="language" class="form-control common-select ">
                  <option value="" <?= (!$profile || !$profile->language ? ' selected="selected"' : '') ?>><?= lang('hd_lang.select_language') 						?></option>
					<?php foreach (App::languages() as $lang) : ?>
						<option value="<?= $lang->name ?>" <?= ($profile && $profile->language == $lang->name ? ' selected="selected"' : '') ?>>
							<?= ucfirst($lang->name); ?>
						</option>
					<?php endforeach; ?>
                </select>
              </div>
            </div>


            <div class="form-group my-3 row align-items-center">
              <label class="col-lg-4 control-label common-label m-0 text-end"><?= lang('hd_lang.locale') ?></label>
              <div class="col-lg-8">
                <select class="select2-option form-control common-select " name="locale">
                  <option value="" <?= (!$profile || !$profile->locale ? ' selected="selected"' : '') ?>><?= lang('hd_lang.select_locale') ?>						</option>
					<?php foreach (App::locales() as $loc) : ?>
						<option value="<?= $loc->locale ?>" <?= ($profile && $profile->locale == $loc->locale ? ' selected="selected"' : '') ?>>
							<?= $loc->name; ?>
						</option>
					<?php endforeach; ?>
                </select>
              </div>
            </div>


           
            <?php $fields = $db->table('hd_fields')->orderBy('order', 'DESC')->where('module', 'clients')->get()->getResult(); ?>
            <?php foreach ($fields as $f) : ?>
              <?php $val = App::field_meta_value($f->name, $profile->company); ?>
              <?php $options = json_decode($f->field_options, true); ?>
              <?php if ($f->type == 'dropdown') : ?>

                <div class="form-group my-3 row align-items-center">
                  <label class="col-lg-4 control-label common-label m-0 text-end"><?= $f->label ?> <?= ($f->required) ? '<span class="text-danger">*</span>' : ''; ?></label>
                  <div class="col-lg-8">
                    <select class="form-control common-select" name="<?= 'cust_' . $f->name ?>" <?= ($f->required) ? 'required' : ''; ?>>
                      <option value="<?= $val ?>"><?= $val ?></option>
                      <?php foreach ($options['options'] as $opt) : ?>
                        <option value="<?= $opt['label'] ?>" <?= ($opt['checked']) ? 'selected="selected"' : ''; ?>><?= $opt['label'] ?></option>
                      <?php endforeach; ?>
                    </select>
                    <span class="help-block"><?= isset($options['description']) ? $options['description'] : '' ?></span>
                  </div>
                </div>

              <?php elseif ($f->type == 'text') : ?>

                <div class="form-group my-3 row align-items-center">
                  <label class="col-lg-4 control-label common-label m-0 text-end"><?= $f->label ?> <?= ($f->required) ? '<span class="text-danger">*</span>' : ''; ?></label>
                  <div class="col-lg-8">
                    <input type="text" name="<?= 'cust_' . $f->name ?>" class="form-control common-input m-0" value="<?= $val ?>" <?= ($f->required) ? 'required' : ''; ?>>
                    <span class="help-block"><?= isset($options['description']) ? $options['description'] : '' ?></span>
                  </div>
                </div>

              <?php elseif ($f->type == 'paragraph') : ?>

                <div class="form-group my-3 row align-items-center">
                  <label class="col-lg-4 control-label common-label m-0 text-end"><?= $f->label ?> <?= ($f->required) ? '<span class="text-danger">*</span>' : ''; ?></label>
                  <div class="col-lg-8">
                    <textarea name="<?= 'cust_' . $f->name ?>" class="form-control ta common-input " style='height: unset !important; width: unset !important;'  <?= ($f->required) ? 'required' : ''; ?>><?= $val ?></textarea>
                    <span class="help-block"><?= isset($options['description']) ? $options['description'] : '' ?></span>
                  </div>
                </div>

              <?php elseif ($f->type == 'radio') : ?>
                <div class="form-group my-3 row align-items-center">
                  <label class="col-lg-4 control-label common-label m-0 text-end"><?= $f->label ?> <?= ($f->required) ? '<span class="text-danger">*</span>' : ''; ?></label>
                  <div class="col-lg-8">
                    <?php foreach ($options['options'] as $opt) : ?>
                      <?php $sel_val = json_decode($val); ?>
                      <label class="radio-custom common-input">
                        <input type="radio" name="<?= 'cust_' . $f->name ?>[]" <?= ($opt['checked'] || $sel_val[0] == $opt['label']) ? 'checked="checked"' : ''; ?> value="<?= $opt['label'] ?>" <?= ($f->required) ? 'required' : ''; ?>> <?= $opt['label'] ?> </label>
                    <?php endforeach; ?>
                    <span class="help-block"><?= isset($options['description']) ? $options['description'] : '' ?></span>
                  </div>
                </div>

              <?php elseif ($f->type == 'checkboxes') : ?>
                <div class="form-group my-3 row align-items-center">
                  <label class="col-lg-4 control-label common-label m-0 text-end"><?= $f->label ?> <?= ($f->required) ? '<span class="text-danger">*</span>' : ''; ?></label>
                  <div class="col-lg-8">
                    <?php foreach ($options['options'] as $opt) : ?>
                      <?php $sel_val = json_decode($val); ?>
                      <div class="checkbox">
                        <label class="checkbox-custom">
                          <?php if (is_array($sel_val)) : ?>
                            <div class='form-check form-switch input-btn-div'>
                            <input class='form-check-input switch-cus' type="checkbox" name="<?= 'cust_' . $f->name ?>[]" <?= ($opt['checked'] || in_array($opt['label'], $sel_val)) ? 'checked="checked"' : ''; ?> value="<?= $opt['label'] ?>">
                            </div>
                            <?php else : ?>
                              <div class='form-check form-switch input-btn-div'>
                            <input class='form-check-input switch-cus' type="checkbox" name="<?= 'cust_' . $f->name ?>[]" <?= ($opt['checked']) ? 'checked="checked"' : ''; ?> value="<?= $opt['label'] ?>">
                            </div>
                            <?php endif; ?>
                          <?= $opt['label'] ?>
                        </label>
                      </div>
                    <?php endforeach; ?>
                    <span class="help-block"><?= isset($options['description']) ? $options['description'] : '' ?></span>
                  </div>
                </div>

              <?php elseif ($f->type == 'email') : ?>

                <div class="form-group my-3 row align-items-center">
                  <label class="col-lg-4 control-label common-label m-0 text-end"><?= $f->label ?> <?= ($f->required) ? '<span class="text-danger">*</span>' : ''; ?></label>
                  <div class="col-lg-8">
                    <input type="email" name="<?= 'cust_' . $f->name ?>" value="<?= $val ?>" class="input-sm form-control common-input m-0" <?= ($f->required) ? 'required' : ''; ?>>
                    <span class="help-block"><?= isset($options['description']) ? $options['description'] : '' ?></span>
                  </div>
                </div>
              <?php elseif ($f->type == 'section_break') : ?>
                <hr />
              <?php endif; ?>
            <?php endforeach; ?>

            <button type="submit" class="btn btn-sm btn-dark common-button"><?= lang('hd_lang.update_profile') ?></button>
            </form>


            <h4 class="page-header common-h py-4"><?= lang('hd_lang.change_email_subject') ?></h4>

            <?php
            $attributes = array('class' => 'bs-example form-horizontal');
            echo form_open(base_url() . 'auth/change_email', $attributes); ?>
            <input type="hidden" name="r_url" value="<?= base_url('profile/settings'); ?>">

            <div class="form-group my-3 row align-items-center">
              <label class="col-lg-4 control-label common-label m-0 text-end"><?= lang('hd_lang.current_email') ?></label>
              <div class="col-lg-8">
                <input type="text" class="form-control common-input m-0" name="" value="<?= User::login_info(User::get_id())->email; ?>" readonly="readonly">
              </div>
            </div>


            <div class="form-group my-3 row align-items-center">
              <label class="col-lg-4 control-label common-label m-0 text-end"><?= lang('hd_lang.password') ?></label>
              <div class="col-lg-8">
                <input type="password" class="form-control common-input m-0" name="password" placeholder="<?= lang('hd_lang.password') ?>" required>
              </div>
            </div>
            <div class="form-group my-3 row align-items-center">
              <label class="col-lg-4 control-label common-label m-0 text-end"><?= lang('hd_lang.new_email') ?></label>
              <div class="col-lg-8">
                <input type="email" class="form-control common-input m-0" name="email" placeholder="<?= lang('hd_lang.new_email') ?>" required>
              </div>
            </div>

            <button type="submit" class="btn btn-sm btn-success common-button"><?= lang('hd_lang.change_email_subject') ?></button>
            </form>


          </div>
        </section>
        <!-- /profile form -->
      </div>
      <div class="col-lg-6 p-3">

        <!-- Account Form -->
        <section class="panel panel-default">
          <header class="panel-heading font-bold common-h  m-0" ><?= lang('hd_lang.account_details') ?></header>
          <div class="panel-body">
            <?php
            echo form_open(base_url() . 'auth/change_password'); ?>
            <input type="hidden" name="r_url" value="<?= base_url('profile/settings'); ?>">
            <div class="form-group my-3">
              <label class='common-label m-0' ><?= lang('hd_lang.old_password') ?> <span class="text-danger">*</span></label>
              <input type="password" class="form-control common-input m-0" name="old_password" placeholder="<?= lang('hd_lang.old_password') ?>" required>
            </div>
            <div class="form-group my-3">
              <label class='common-label m-0'><?= lang('hd_lang.new_password') ?> <span class="text-danger">*</span></label>
              <input type="password" class="form-control common-input m-0" name="new_password" placeholder="<?= lang('hd_lang.new_password') ?>" required>
            </div>
            <div class="form-group my-3">
              <label class='common-label m-0'><?= lang('hd_lang.confirm_password') ?> <span class="text-danger">*</span></label>
              <input type="password" class="form-control common-input m-0" name="confirm_new_password" placeholder="<?= lang('hd_lang.confirm_password') ?>" required>
            </div>

            <button type="submit" class="btn btn-sm btn-dark common-button my-3"><?= lang('hd_lang.change_password') ?></button>
            </form>

            <h4 class="page-header common-h py-4"><?= lang('hd_lang.avatar_image') ?></h4>

            <?php
            $attributes = array('class' => 'bs-example form-horizontal');
            echo form_open_multipart(base_url() . 'profile/changeavatar', $attributes); ?>
            <input type="hidden" name="r_url" value="<?= base_url('profile/settings'); ?>">

            <div class="form-group align-items-center row my-3">
              <label class="col-lg-3 control-label text-end common-label m-0"><?= lang('hd_lang.use_gravatar') ?></label>
              <div class="col-lg-8">
                <label class="switch">
                  <div class='form-check form-switch input-btn-div'>
                  <input class='form-check-input switch-cus' type="checkbox" <?php //if ($profile->use_gravatar == 'Y') {
                                           // echo "checked=\"checked\"";
                                          //} ?> name="use_gravatar">
                                          </div>
                  <span></span>
                </label>
              </div>
            </div>

            <div class="form-group row my-3">
              <label class="col-lg-3 control-label text-end common-label m-0"><?= lang('hd_lang.avatar_image') ?></label>
              <div class="col-lg-3 unique-input-file">
                <input type="file" class="filestyle" data-buttonText="<?= lang('hd_lang.choose_file') ?>" data-icon="false" data-classButton="btn btn-default" data-classInput="form-control inline input-s" name="userfile">
              </div>
            </div>
            <button type="submit" class="btn btn-sm btn-success common-button my-3"><?= lang('hd_lang.change_avatar') ?></button>
            </form>

            <h4 class="page-header common-h py-4"><?= lang('hd_lang.change_username') ?></h4>

            <?php
            $attributes = array('class' => 'bs-example form-horizontal');
            echo form_open(base_url() . 'auth/change_username', $attributes); ?>
            <input type="hidden" name="r_url" value="<?= base_url('profile/settings'); ?>">

            <div class="form-group my-3 row align-items-center">
              <label class="col-lg-4 control-label common-label text-end m-0"><?= lang('hd_lang.new_username') ?></label>
              <div class="col-lg-7">
                <input type="text" class="form-control common-input m-0" name="username" placeholder="<?= lang('hd_lang.new_username') ?>" required>
              </div>
            </div>

            <button type="submit" class="btn btn-sm btn-danger common-button my-3"><?= lang('hd_lang.change_username') ?></button>
            </form>


          </div>
        </section>
        <!-- /Account form -->
      </div>
    </div>
  </div>
<?= $this->endSection() ?>