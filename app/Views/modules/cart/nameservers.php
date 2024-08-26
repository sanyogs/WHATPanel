<?= $this->extend('layouts/users') ?>

<?= $this->section('content') ?>
<div class="box mt-3">
    <div class="container-fluid">
        <div class="row">

            <section class="panel panel-default bg-white m-t-lg radius_3">
                <header class="panel-heading ">
                    <h3 class="common-h"><?= lang('hd_lang.nameservers') ?></h3>
                </header>

                <div class="panel-body">
                    <?php

                    use App\Helpers\custom_name_helper;

                    $custom_name_helper = new custom_name_helper();

                    $attributes = array('class' => 'bs-example form-horizontal');
                    echo form_open(base_url() . 'cart/add_nameservers', $attributes); ?>
                    <p class="common-h"><?= lang('hd_lang.nameserver_help') ?></p>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">

                            <div class="form-group modal-input flex-wrap align-items-center m-0">
                                <label class="control-label col-md-2 col-sm-3 col-12 common-label">
                                    <?= lang('hd_lang.nameserver_1') ?>
                                </label>
                                <div class="col-md-7 col-sm-7 col-12">
                                    <input type="text" name="nameserver_1" class="form-control common-input" id="name_one" required>
                                </div>
                            </div>

                            <div class="form-group modal-input flex-wrap align-items-center m-0">
                                <label class="control-label col-md-2 col-sm-3 col-12 common-label">
                                    <?= lang('hd_lang.nameserver_2') ?>
                                </label>
                                <div class="col-md-7 col-sm-7 col-12">
                                    <input type="text" name="nameserver_2" class="form-control common-input" id="name_two" required>
                                </div>
                            </div>

                            <div class="form-group modal-input flex-wrap align-items-center m-0">
                                <label class="control-label col-md-2 col-sm-3 col-12 common-label">
                                    <?= lang('hd_lang.nameserver_3') ?>
                                </label>
                                <div class="col-md-7 col-sm-7 col-12">
                                    <input type="text" name="nameserver_3" class="form-control common-input" id="name_three">
                                </div>
                            </div>

                            <div class="form-group modal-input flex-wrap align-items-center m-0">
                                <label class="control-label col-md-2 col-sm-3 col-12 common-label">
                                    <?= lang('hd_lang.nameserver_4') ?>
                                </label>
                                <div class="col-md-7 col-sm-7 col-12">
                                    <input type="text" name="nameserver_4" class="form-control common-input" id="name_four">
                                </div>
                            </div>

                            <div class="input-group pull-right">
                                <a href="<?= base_url() ?>cart/default_nameservers" class="btn btn-info common-button button-cus p-3 m-3 ms-0"><?= lang('hd_lang.default_nameservers') ?></a>
                                <button class="button-cus common-button btn btn-<?= $custom_name_helper->getconfig_item('theme_color'); ?> p-3 m-3 ms-0"><?= lang('hd_lang.custom_nameservers') ?></button>
                            </div>
                        </div>
                        </form>

                    </div>
            </section>
        </div>
    </div>
</div>
<?= $this->endSection() ?>