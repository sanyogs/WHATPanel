<div class="nav-tabs-custom">
    <ul class="nav nav-tabs pb-4" role="tablist">
        <li style="border-color:transparent;"><a class="active common-button" data-toggle="tab" href="#tab-admin"><?= lang('hd_lang.admin') ?></a></li>
        <li style="border-color:transparent;"><a class="common-button mx-3" data-toggle="tab" href="#tab-staff"><?= lang('hd_lang.staff') ?></a></li>
        <li style="border-color:transparent;"><a class="common-button" data-toggle="tab" href="#tab-client"><?= lang('hd_lang.client') ?></a></li>
    </ul>
    <div class="tab-content tab-content-fix">
        <div class="tab-pane in active" id="tab-admin">
            <div class="table-responsive hs-table-overflow" style="background-color: #edf3ff;">
                <table id="menu-admin" class="hs-table">
                    
                    <tbody>
                    <tr>
                            <th></th>
                            <th class="col-xs-2"><?= lang('hd_lang.icon') ?></th>
                            <th class="col-xs-8"><?= lang('hd_lang.menu') ?></th>
                            <th class="col-xs-2"><?= lang('hd_lang.visible') ?></th>
                        </tr>
                        <?php foreach ($admin as $adm) : ?>
                        <tr class="sortable" data-module="<?= $adm->module ?>" data-access="1">
                            <td class="drag-handle"><i class="fa fa-reorder"></i></td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-default iconpicker-component" type="button"><i
                                            class="fa <?= $adm->icon ?> fa-fw"></i></button>
                                    <button data-toggle="dropdown" data-selected="<?= $adm->icon ?>"
                                        class="menu-icon icp icp-dd btn btn-default dropdown-toggle" type="button"
                                        aria-expanded="false" data-role="1"
                                        data-href="<?= base_url('settings/hook/icon/' . $adm->module) ?>">
                                        <span class="caret"></span>
                                    </button>
                                    <div class="dropdown-menu iconpicker-container"></div>
                                </div>
                            </td>
                            <td><?= lang($adm->name) ?></td>
                            <td>
                                <a data-rel="tooltip" data-original-title="<?= lang('hd_lang.toggle') ?>"
                                    class="menu-view-toggle btn btn-xs btn-<?= ($adm->visible == 1 ? 'success' : 'default') ?>"
                                    href="#" data-role="1"
                                    data-href="<?= base_url('settings/hook/visible/' . $adm->module) ?>"><i
                                        class="fa fa-eye"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="tab-pane fade in" id="tab-staff">
            <div class="table-responsive hs-table-overflow" style="background-color: #edf3ff;">
                <table id="menu-staff" class="hs-table">
                   
                    <tbody>
                    <tr>
                            <th></th>
                            <th class="col-xs-2"><?= lang('hd_lang.icon') ?></th>
                            <th class="col-xs-3"><?= lang('hd_lang.menu') ?></th>
                            <th class="col-xs-5"><?= lang('hd_lang.permission') ?></th>
                            <th class="col-xs-2"><?= lang('hd_lang.options') ?></th>
                        </tr>
                        <?php foreach ($staff as $sta) : ?>
                        <tr class="sortable" data-module="<?= $sta->module ?>" data-access="3">
                            <td class="drag-handle"><i class="fa fa-reorder"></i></td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-default iconpicker-component" type="button"><i
                                            class="fa <?= $sta->icon ?> fa-fw"></i></button>
                                    <button data-toggle="dropdown" data-selected="<?= $sta->icon ?>"
                                        class="menu-icon icp icp-dd btn btn-default dropdown-toggle" type="button"
                                        aria-expanded="false" data-role="3"
                                        data-href="<?= base_url('settings/hook/icon/' . $sta->module) ?>">
                                        <span class="caret"></span>
                                    </button>
                                    <div class="dropdown-menu iconpicker-container"></div>
                                </div>
                            </td>
                            <td><?= lang($sta->name) ?></td>
                            <?php if ($sta->permission != '') { ?>
                            <td><?= lang('hd_lang.permission_required') ?></td>
                            <?php } else { ?>
                            <td></td>
                            <?php } ?>
                            <td>
                                <a id="btn" data-rel="tooltip" data-original-title="<?= lang('hd_lang.toggle') ?>"
                                    class="menu-view-toggle btn btn-xs btn-<?= ($sta->visible == 1 ? 'success' : 'default') ?>"
                                    href="#" data-role="3"
                                    data-href="<?= base_url('settings/hook/visible/'. $sta->module) ?>"><i
                                        class="fa fa-eye"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="tab-pane fade in" id="tab-client">
            <div class="table-responsive hs-table-overflow" style="background-color: #edf3ff;">
                <table id="menu-client" class=" hs-table">
                    <tbody>
                        <tr>
                            <th></th>
                            <th class="col-xs-2"><?= lang('hd_lang.icon') ?></th>
                            <th class="col-xs-3"><?= lang('hd_lang.menu') ?></th>
                            <th class="col-xs-5"><?= lang('hd_lang.permission') ?></th>
                            <th class="col-xs-2"><?= lang('hd_lang.options') ?></th>
                        </tr>
                        <?php foreach ($client as $cli) : ?>
                        <tr class="sortable" data-module="<?= $cli->module ?>" data-access="2">
                            <td class="drag-handle"><i class="fa fa-reorder"></i></td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-default iconpicker-component" type="button"><i
                                            class="fa <?= $cli->icon ?> fa-fw"></i></button>
                                    <button data-toggle="dropdown" data-selected="<?= $cli->icon ?>"
                                        class="menu-icon icp icp-dd btn btn-default dropdown-toggle" type="button"
                                        aria-expanded="false" data-role="2"
                                        data-href="<?= base_url('settings/hook/icon/' . $cli->module) ?>">
                                        <span class="caret"></span>
                                    </button>
                                    <div class="dropdown-menu iconpicker-container"></div>
                                </div>
                            </td>
                            <td><?= lang($cli->name) ?></td>
                            <?php if ($cli->permission != '') { ?>
                            <td><?= lang('hd_lang.permission_required') ?></td>
                            <?php } else { ?>
                            <td></td>
                            <?php } ?>
                            <td>
                                <a data-rel="tooltip" data-original-title="<?= lang('hd_lang.toggle') ?>"
                                    class="menu-view-toggle btn btn-xs btn-<?= ($cli->visible == 1 ? 'success' : 'default') ?>"
                                    href="#" data-role="2"
                                    data-href="<?= base_url('settings/hook/visible/' . $cli->module) ?>"><i
                                        class="fa fa-eye"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>