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
use App\Models\Categories;
?>

<div class="row" id="department_settings">
    <!-- Start Form -->
    <div class="col-lg-12">     
        <?php
        $attributes = array('class' => 'bs-example form-horizontal');
        echo form_open_multipart('settings/departments', $attributes); ?>
      
             <div class="box-header">   <h2><?=lang('categories')?></h2> </div>
                <div class="box-body">
                    <input type="hidden" name="settings" value="<?=$load_setting?>">
                    <div class="form-group">
                    <a href="<?=base_url('settings/add_category')?>" class="btn btn-primary btn-<?=config_item('theme_color');?> btn-sm pull-right" data-toggle="ajaxModal" title="<?=lang('hd_lang.add_category')?>"><i class="fa fa-plus"></i> <?=lang('add_category')?></a>
               
                    </div>
                    <?php
                    $session = \Config\Services::session(); 

                        

                       

                    // Modify the 'default' property    
                        

                    // Connect to the database  
                    $db = \Config\Database::connect();

                    $model = new Categories($db); // You can use $this->db if it's available in the context.
                    $categories = $model->get()->getResult();
                    $core_categories = array(8,9,10);
                    // echo"<pre>";print_r($core_categories); die;
                    if (!empty($categories)) {
                        foreach ($categories as $key => $d) { if(!in_array($d->id, $core_categories)) { ?>
                             <a href="<?=base_url()?>settings/edit_category/<?=$d->id?>" data-toggle="ajaxModal" class="btn btn-warning" title = ""><?=$d->cat_name?></a> 
                        <?php } } } ?>

                </div>
  
        </form>
    </div>
    <div class="row">
        <div class="col-md-12">
            <img class="img-responsive" src="http://localhost/public/images/pricing_tables.png"
                alt="Pricing Tables" />
        </div>
    </div>
    <!-- End Form -->
</div>



<?= $this->endSection() ?>