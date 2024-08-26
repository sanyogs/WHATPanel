<?= $this->extend('layouts/users') ?>

<?= $this->section('content') ?>

<div class="box" style="margin-top:2%;">
    <div class="box-body">
        <div class="table-responsive">
            <?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */
        use App\Models\Plugin;
        $session = \Config\Services::session();
        $attributes = array('class' => 'bs-example form-horizontal');
    echo form_open(base_url().'domains/import_domains', $attributes); ?>

            <div class="row">
                <div class="form-group">
                    <label class="col-sm-3 control-label"><?=lang('hd_lang.registrar')?></label>
                    <div class="col-md-3">
                        <select name="registrar" class="form-control m-b">
                            <option value=""><?=lang('hd_lang.none')?></option>
                            <?php
                            
                            $registrars = Plugin::domain_registrars();
                            foreach ($registrars as $registrar)
                            {?>
                            <option value="<?=$registrar->system_name;?>"><?=ucfirst($registrar->system_name);?>
                            </option>
                            <?php } ?>

                        </select>
                    </div>
                </div>
            </div>

            <table id="table-rates" class="table table-striped b-t">
                <thead>
                    <tr>
                        <th><?=lang('hd_lang.type')?></th>
                        <th><?=lang('hd_lang.domain')?></th>
                        <th><?//=lang('hd_lang.period')?></th>
                        <th><?=lang('hd_lang.registration')?> <?=lang('hd_lang.date')?></th>
                        <th><?=lang('hd_lang.expires')?></th>
                        <th><?=lang('hd_lang.status')?></th>
                        <th><?=lang('hd_lang.notes')?></th>
                        <th><input type="checkbox" id="select-all" checked> <?=lang('hd_lang.select')?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
            
            $data = $session->get('import_domains') ? $session->get('import_domains') : array();
            foreach ($data as $acc) { ?>
                    <tr>
                        <td><?=$acc->type?></td>
                        <td><?=$acc->domain?></td>
                        <td><?//=$acc->period?></td>
                        <td><?=$acc->registrar?></td>
                        <td><?=$acc->renewal_date?></td>
                        <td><?=$acc->status_id?></td>
                        <td><?=$acc->notes?></td>
                        <td><input type="checkbox" checked name="<?=$acc->id?>"></td>
                    </tr>
                    <?php }  ?>

                </tbody>
                <tfoot>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><button class="btn btn-success btn-block btn-sm"><?=lang('hd_lang.import')?></button></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>


<script>
$(document).ready(function() {
    $('#select-all').click(function() {
        var checked = this.checked;
        $('input[type="checkbox"]').each(function() {
            this.checked = checked;
        });
    })
});
</script>