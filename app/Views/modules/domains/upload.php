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
 */ $session = \Config\Services::session(); ?>
<div class="box" style="margin-top:1%;">
     <div class="box-header">
         <h3 class="common-h">
             <?lang('hd_lang.import')?>
         </h3>
         <p>
             <small class="common-p"><?=lang('hd_lang.whmcs_export_domains')?><br /> <?=lang('hd_lang.select_all_whmcs')?></small>
         </p>
     </div>
     <div class="box-body">
         <div class="container-fluid p-0">

             <?php if($session->getFlashdata('message')): ?>
             <div class="alert alert-info alert-dismissible">
                 <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                 <?php echo $session->getFlashdata('message') ?>
             </div>
             <?php endif ?>

             <?php
			 $attributes = array('class' => 'bs-example form-horizontal', 'enctype' => 'multipart/form-data');
                echo form_open(base_url().'domains/upload', $attributes); ?>

             <input type="hidden" name="nothing" value="">
			 <div class="d-flex flex-wrap gap-3">
             <div class="form-group modal-input p-0 m-0">
                 <input class="common-input m-0" type="file" name="import">
             </div>
             <div class="form-group modal-input p-0 m-0">
                 <input class="common-input bg-success text-white m-0" type="submit" class="btn btn-warning" value="<?=lang('hd_lang.upload')?>">
             </div>
			 </div>
             </form>
         </div>
     </div>
</div>
<?= $this->endSection() ?>