<?= $this->extend('layouts/users') ?>

<?= $this->section('content') ?>
<div class="box">
    <div class="box-header">
        <h3 class='common-h'><?php echo lang('hd_lang.import'); ?></h3>
        <p>
            <small class="common-p"><?php echo lang('hd_lang.whmcs_export_services'); ?><br /> <?php echo lang('hd_lang.select_all_whmcs'); ?></small>
        </p>
    </div>
    <div class="box-body">
        <div class="container-fluid p-0">

            <?php if(isset($_SESSION['message'])): ?>
            <div class="alert alert-info alert-dismissible">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <?php echo $_SESSION['message']; ?>
            </div>
            <?php endif; ?>

            <?php
            $attributes = array('class' => 'bs-example form-horizontal', 'enctype' => 'multipart/form-data');
            echo form_open(base_url().'accounts/upload', $attributes);
            ?>
			<div class="d-flex flex-wrap">
            <input type="hidden" name="nothing" value="">

            <div class="form-group modal-input p-2 m-0">
                <input class="common-input m-0" type="file" name="import">
            </div>

            <div class="form-group modal-input p-2 m-0">
                <input class="common-input bg-success text-white m-0" type="submit" class="btn btn-warning" value="<?php echo lang('hd_lang.upload'); ?>">
            </div>
			</div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>