<?= $this->extend('layouts/users') ?>

<?= $this->section('content') ?>

<div class="box" style="margin-top: 31px !important;">
    <div class="box-header common-h"><h2>Create</h2></div>
    <div class="box-body">
        <form method="post" action="<?php echo site_url('menus/add_menu'); ?>">
            <div class="row">
                <div class="col-md-5">
                <label class="label-control common-label" for="menu-group-title">Menu Name</label>
                <input class="form-control common-input m-0" type="text" name="title" id="menu-group-title">
                </div>
            </div>
            <br>
            <button type="submit" class="common-button">Submit</button>
        </form>
    </div>
</div>

<?= $this->endSection() ?>