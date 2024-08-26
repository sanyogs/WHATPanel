<?= $this->extend('layouts/users') ?>

<?= $this->section('content') ?>

<div class="box order-select-client-section" style="margin-top: 2%;">
<div class="box box-solid box-default">
    <header class="box-header common-h p-3"><?= lang('hd_lang.new_order') ?></header>
    <div class="box-body inner">
        <div class="row">
            <div class="col-lg-10 col-md-10 col-sm-10">
                <?php

                use App\Models\Client;

                $attributes = array('class' => 'bs-example form-horizontal');
                echo form_open(base_url('orders/select_client'), $attributes); ?>
                <div class="container">
                    <div class="row ">
                        <div class="col-lg-7 col-md-12 col-sm-12">
                            <select class="select2-option  common-select" id="modal_client" name="co_id" required>
                                <option value="" selected>Select</option>
                                <?php foreach (Client::get_all_clients() as $client) : ?>
								<?php if($client->company_name != '') : ?>
                                    <option value="<?= $client->co_id ?>"><?= ucfirst($client->company_name) ?></option>
								<?php endif; ?>
                                <?php endforeach;  ?>
                            </select>
                        </div>
                        <div class="col-lg-2 col-sm-6 col-12 ">
                            <button type="submit" class="btn btn-success  common-button"><?= lang('hd_lang.continue') ?></button>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12 ">
                            <a href="<?= base_url('companies/create') ?>" class="btn btn-secondary btn-sm common-button" data-toggle="ajaxModal" title="<?= lang('hd_lang.new_company') ?>" data-placement="bottom" style="margin-right: -99px;"><i class="fa fa-plus"></i> <?= lang('hd_lang.new_client') ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<?= $this->endSection() ?>