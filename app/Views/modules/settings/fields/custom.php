<style>
  * {
    box-sizing: border-box;
  }

  .fb-main {
    min-height: 600px;
  }

  input[type=text] {

    margin-bottom: 3px;
  }

  select {
    margin-bottom: 5px;
    font-size: 40px;
  }
</style> 

<?= $this->extend('layouts/users') ?>

<?= $this->section('content') ?>

<?php 
use App\Helpers\custom_name_helper;

$custom_helper = new custom_name_helper();
?>
<div class="row">
  <div class="col-md-3">

    <!-- Profile Image -->
    <div class="box box-primary">
      <div class="box-body box-profile">
        <h3 class="profile-username text-center"><?= lang('hd_lang.settings_menu') ?></h3>

        <ul class="list-group" id="settings_menu">
          <?php

          use App\Libraries\AppLib;

          $live = array('system', 'email', 'theme', 'departmets', 'menu', 'crons', 'departments', 'templates', 'general');
          
          $session = \Config\Services::session();

          // Connect to the database  
          $dbName = \Config\Database::connect();
          
          $menus = $dbName->table('hd_hooks')->where('hook', 'settings_menu_admin')->where('visible', 1)->orderBy('order', 'ASC')->get()->getResult();
          foreach ($menus as $menu) {
            if ($custom_helper->getconfig_item('demo_mode') == 'TRUE' && in_array($menu->route, $live)) {
              continue;
            } ?>
            <li class="list-group-item common-button <?php echo ($load_setting == $menu->route) ? 'active' : ''; ?>">
              <a href="<?= base_url() ?>settings/?settings=<?= $menu->route ?>">
                <i class="fa fa-fw <?= $menu->icon ?>"></i>
                <?= lang($menu->name) ?>
              </a>
            </li>
          <?php } ?>
        </ul>
      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
  <!-- /.col -->
  <div class="col-md-9">
    <div class="box box-warning">
      <div class="box-body">

        <?php
        $attributes = array('id' => 'saveform');
        echo form_open(base_url('public/settings/fields/saveform'), $attributes);
        $applib = new AppLib();
        ?>

        <div class="table-head"><?= ucfirst($module) ?> custom fields
          <span class="pull-right">
            <span class="label label-warning changes">Unsaved</span>
            <input type="submit" class="btn btn-primary btn-sm save button-loader" value="Save" disabled="disabled"></span>
          <input type="hidden" name="module" value="<?= $module ?>" />
          <input type="hidden" name="deptid" value="<?= isset($department) ? $department : 0; ?>" />
          <input type="hidden" name="uniqid" value="<?= $applib->generate_unique_value() ?>" />

          
        </div>
        <div class="table-div">
          <br>
          <textarea id="formcontent" class="hidden" name="formcontent"></textarea>
        </div>
            
        </form>
        <div class='fb-main'></div>

      </div>
    </div>
  </div>
</div>


<?php if (isset($formbuilder)) {?>
  <script src="<?= base_url('public/js/apps/formbuilder_vendor.js') ?>"></script>
  <script src="<?= base_url('public/js/apps/formbuilder.js') ?>"></script>
<?php } ?>

<script>
  (function($) {
    fb = new Formbuilder({
      selector: '.fb-main',
      bootstrapData: [
        <?php foreach ($fields as $f) : ?> {
            "label": "<?= $f->label ?>",
            "field_type": "<?= $f->type ?>",
            "required": "<?= ($f->required == 1) ? true : false; ?>",
            "cid": "<?= $f->cid ?>",
            'uniqid': "<?= $f->uniqid ?>",
            'module': "<?= $f->module ?>",
            "field_options": <?= $f->field_options ?>
          },
        <?php endforeach; ?>
      ]
    });

    fb.on('save', function(payload) {
      console.log(payload);
      $("#formcontent").text(payload);
    });


    switch (window.orientation) {

      case 0:
        alert('Please turn your phone sideways in order to use this page!');
        break;

    }

  })(jQuery);
</script>
<?= $this->endSection() ?>