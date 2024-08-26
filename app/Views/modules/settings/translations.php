<?php

use App\Helpers\custom_name_helper;

$custom = new custom_name_helper();
if (!isset($language)) : ?>

    <style>
        .progress {
            position: relative;
        }

        .spanTag {
            position: absolute;
            left: 50%;
            transform: translate(-50%, -50%);
            color: black;
            top: 50%;
            font-size: 1.6rem;
            font-weight: 600;
        }
    </style>
    <div class="add-translation row mb-5">
        <div class="selectBoxWrap col-md-4">
            <select id="add-language" class="select2-option form-control common-select" name="language">
                <?php foreach ($available as $loc) : ?>
                    <!-- <option value="<?= str_replace(" ", "_", $loc->language) . '|' . $loc->locale ?>"><?= ucwords($loc->name) ?></option> -->
                    <option value="<?= $loc->locale ?>"><?= ucwords($loc->name) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button id="add-translation" class="col-md-2 btn common-button btn-sm btn-<?= $custom->getconfig_item('theme_color'); ?>"><?= lang('hd_lang.add_language') ?></button>
    </div>


    <div class="table-responsive hs-table-overflow">
        <table id="table-translations" class="hs-table AppendDataTables">
            <tbody>
                <tr>
                    <th class="no-sort"><?= lang('hd_lang.icon') ?></th>
                    <th><?= lang('hd_lang.language') ?></th>
                    <th class="col-options no-sort"><?= lang('hd_lang.action') ?></th>
                    <th><?= lang('hd_lang.progress') ?></th>
                    <th><?= lang('hd_lang.remaining') ?></th>
                    <th><?= lang('hd_lang.total') ?></th>
                </tr>
                <?php

                // echo "<pre>";print_r($languages);die;

                foreach ($languages as $l) :
                    $st = $translation_stats;
                    // echo "<pre>";print_r($st);die;
                    //$total = $st[$l->name]['total'];
                    $total = $st[$l->language]['total'];
                    //$translated = $st[$l->name]['translated'];
                    $translated = $st[$l->language]['translated'];
                    $pc = round(intval(($translated / $total) * 1000) / 10);
                    $remaining = $total - $translated;
                    if ($l->name1 != 'english') {
                ?>

                        <tr>
                            <td class=""><img src="<?= base_url('images/flags/' . $l->icon) ?>.gif" /></td>
                            <td class="">
                                <?= ucwords(str_replace("_", " ", $l->name)) ?>
                            </td>
                            <td class="tableBtnWrap">
                                <a data-rel="tooltip" data-original-title="<? //=($l->active == 1 ? lang('hd_lang.deactivate') : lang('hd_lang.activate') )
                                                                            ?>" class="active-translation btn btn-sm common-button bg-<?= ($l->active == 0 ? 'danger' : 'success') ?>" href="#" data-href="<?= base_url() ?>settings/translations/active/<?= $l->language ?>"><i class="fa fa-power-off"></i></a>
                                <a data-rel="tooltip" data-original-title="<?= lang('hd_lang.edit') ?>" class="btn btn-sm btn-primary common-button" href="<?= base_url() ?>settings/translations/view/<?= $l->language ?>/?settings=translations"><i class="fa fa-edit"></i> <?= lang('hd_lang.edit_translation') ?></a>
                                <a data-rel="tooltip" data-original-title="<?= lang('hd_lang.backup') ?>" class="backup-translation btn btn-sm btn-default common-button bg-success" href="" data-href="<?= base_url() ?>settings/translations/backup/<?= $l->lang_id ?>"><i class="fa fa-download"></i> <?= lang('hd_lang.backup') ?></a>
                                <a data-rel="tooltip" data-original-title="<?= lang('hd_lang.restore') ?>" class="restore-translation btn btn-sm btn-default common-button bg-warning" href="" data-href="<?= base_url() ?>settings/translations/restore/<?= $l->lang_id ?>"><i class="fa fa-upload"></i> <?= lang('hd_lang.restore') ?></a>
                                <a data-rel="tooltip" data-original-title="<?= lang('hd_lang.delete') ?>" class="delete-translation btn btn-sm btn-default common-button bg-danger" id="delete-translation" href="#" data-href="<?= base_url() ?>settings/translations/delete" data-lang-id="<?= $l->lang_id ?>"><i class="fa fa-trash"></i> <?= lang('hd_lang.delete') ?></a>
                            </td>
                            <td>
                                <div class="progress progress-sm">
                                    <?php $bar = 'danger';
                                    if ($pc > 20) {
                                        $bar = 'warning';
                                    }
                                    if ($pc > 50) {
                                        $bar = 'info';
                                    }
                                    if ($pc > 80) {
                                        $bar = 'success';
                                    } ?>
                                    <div class="progress-bar progress-bar-<?= $bar ?>" role="progressbar" aria-valuenow="<?= $pc ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= $pc ?>%;">

                                    </div>
                                    <span class="spanTag"><?= $pc ?>%</span>
                                </div>
                            </td>
                            <td class="">
                                <?= $remaining ? $remaining : 0 ?>
                            </td>
                            <td class="">
                                <?= $total ? $total : 0 ?>
                            </td>

                        </tr>
                <?php }
                endforeach; ?>
            </tbody>
        </table>
    </div>


<?php elseif (!isset($language_file)) :  ?>



    <header class="box-header font-bold common-h"><i class="fa fa-cogs px-3"></i><?= lang('hd_lang.translations') ?> -
        <?php
        // echo $language;die; 
        $db = \Config\Database::connect();
        $query = $db->table('hd_locales')
            ->select('hd_locales.*, hd_locales.code as locale_code, hd_languages.code, hd_languages.name as name1')
            ->join('hd_languages', 'hd_languages.code = hd_locales.code', 'left')
            ->where('hd_languages.name', $language)
            ->get()
            ->getRow();
        echo ucwords($query->language) ?></header>
    <div class="table-responsive">
        <table id="table-translations-files" class="hs-table common-table">
            <thead>
                <tr>
                    <th class="col-xs-2 no-sort"><?= lang('hd_lang.type') ?></th>
                    <th class="col-xs-3"><?= lang('hd_lang.file') ?></th>
                    <th class="col-xs-4"><?= lang('hd_lang.translated') ?></th>
                    <th class="col-xs-1"><?= lang('hd_lang.done') ?></th>
                    <th class="col-xs-1"><?= lang('hd_lang.total') ?></th>
                    <th class="col-options no-sort col-xs-1"><?= lang('hd_lang.action') ?></th>
                </tr>
            </thead>
            <tbody>

                <?php foreach ($language_files as $file => $altpath) :
                    if ($file == 'hd_lang.php' || $file == 'tank_auth_lang.php') {
                        $shortfile = str_replace("_lang.php", "", $file);
                    } elseif ($file == 'Calendar.php' || $file == 'Date.php' || $file == 'Database.php' || $file == 'Email.php' || $file == 'Validation.php' || $file == 'FTP.php' || $file == 'Files.php' || $file == 'Images.php' || $file == 'Migrations.php' || $file == 'Number.php' || $file == 'Profiler.php' || $file == 'Test.php' || $file == 'Upload.php') {
                        $shortfile = str_replace(".php", "", $file);
                    }

                    //echo"<pre>";print_r($translation_stats);die;
                    $st = $translation_stats[$language]['files'][$shortfile];
                    // echo"<pre>";print_r($st);die;
                    $fn = ucwords(str_replace("_", " ", $shortfile));
                    if ($shortfile == 'hd') {
                        $fn = 'Main Application';
                    }
                    if ($shortfile == 'tank_auth') {
                        $fn = 'Authenication';
                    }
                    $total = $st['total'];
                    $translated = $st['translated'];
                    $pc = intval(($translated / $total) * 1000) / 10;

                    // echo $pc;die;
                ?>
                    <tr>
                        <td class=""><?= ($altpath == ROOTPATH . 'system/' ? 'System' : 'Application') ?></td>
                        <td class=""><a class="text-primary bg-transparent" href="<?= base_url() ?>settings/translations/edit/<?= $language ?>/<?= $shortfile ?>/?settings=translations"><?= $fn ?></a>
                        </td>
                        <td>
                            <div class="progress progress-sm">
                                <?php $bar = 'danger';
                                if ($pc > 20) {
                                    $bar = 'warning';
                                }
                                if ($pc > 50) {
                                    $bar = 'info';
                                }
                                if ($pc > 80) {
                                    $bar = 'success';
                                } ?>
                                <div class="progress-bar progress-bar-<?= $bar ?>" role="progressbar" aria-valuenow="<?= $pc ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= $pc ?>%;">
                                    <?= $pc ?>%
                                </div>
                            </div>
                        </td>
                        <td class=""><?= $translated ?></td>
                        <td class=""><?= $total ?></td>
                        <td class="">
                            <a class="btn btn-xs btn-primary text-white" href="<?= base_url() ?>settings/translations/edit/<?= $language ?>/<?= $shortfile ?>/?settings=translations"><i class="fa fa-edit"></i> <?= lang('hd_lang.edit') ?></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>


<?php else : ?>

    <?php $attributes = array('class' => 'bs-example form-horizontal w-100', 'id' => 'form-strings');
    echo form_open_multipart('settings/translations/save/' . $language . '/' . $language_file . '/?settings=translations', $attributes); ?>
    <input type="hidden" name="_language" value="<?= $language ?>">
    <input type="hidden" name="_file" value="<?= $language_file ?>">

    <section class="box box-default">
        <header class="box-header font-bold common-h d-flex align-items-center justify-content-between">
            <div>
                <i class="fa fa-cogs px-3"></i>
                <?php
                $fn = ucwords(str_replace("_", " ", $language_file));
                if ($language_file == 'hd') {
                    $fn = 'Main Application';
                }
                if ($language_file == 'tank_auth') {
                    $fn = 'Authenication';
                }
                // print_r($english);die;
                $total = count($english);
                $translated = 0;
                if ($language == 'english') {
                    $percent = 100;
                } else {
                    // $translation = (array) $translation;
                    // echo "<pre>";print_r($translation);die;
                    foreach ($english as $key => $value) {
                        if (isset($translation[$key]) && $translation[$key] != $value) {
                            $translated++;
                        }
                    }
                    $percent = intval(($translated / $total) * 100);
                }
                ?>
                <?= lang('hd_lang.translations') ?> | <a class='text-primary' href="<?= base_url() ?>settings/translations/view/<?= $language ?>/?settings=translations"><?= ucwords(str_replace("_", " ", $language)) ?></a>
                | <?= $fn ?> | <?= $percent ?>% <?= mb_strtolower(lang('hd_lang.done')) ?>
            </div>
            <button type="submit" id="save-translation" class="btn btn-xs btn-primary pull-right common-button"><?= lang('hd_lang.save_translation') ?></button>
        </header>
        <div class="table-responsive">
            <table id="table-strings" class="hs-table common-table">
                <thead>
                    <tr>
                        <th class="col-7">English</th>
                        <th class="col-5"><?= ucwords(str_replace("_", " ", $language)) ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($english as $key => $value) : ?>
                        <tr>
                            <td><?= $value ?></td>
                            <td><input class="form-control common-input" width="100%" type="text" value="<?= (isset($translation[$key]) ? $translation[$key] : $value) ?>" name="<?= $key ?>" />
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- End details -->
    </section>
    </form>
<?php endif; ?>
<script>
    var base_url = '<?= base_url() ?>';

    $(document).ready(function() {
        $('#add-translation').on('click', function() {
            var lang = $('#add-language').val();
            $.ajax({
                url: '<?php echo base_url(); ?>settings/translations/add/' + lang,
                type: 'GET',
                success: function(response) {
                    console.log("Translation added successfully!", response.msg);
                    window.location.href = base_url + 'settings/translations';
                },
                error: function(xhr, status, error) {
                    // Handle errors here
                    console.error("Error adding translation:", error);
                }
            });
        });

        $('#save-translation').on('click', function(e) {
            e.preventDefault();
            // oTable1.fnResetAllFilters();
            $.ajax({
                url: base_url + 'settings/translations/save',
                type: 'POST',
                data: {
                    json: JSON.stringify($('#form-strings').serializeArray())
                },
                success: function() {
                    toastr.success("<?= lang('translation_updated_successfully') ?>", "<?= lang('response_status') ?>");
                },
                error: function(xhr) {
                    alert('Error: ' + JSON.stringify(xhr));
                }
            });
        });

        $(".active-translation").on('click', function(e) {
            e.preventDefault();
            var target = $(this).attr('data-href');
            var isActive = 0;
            if (!$(this).hasClass('btn-success')) {
                isActive = 1;
            }
            $(this).toggleClass('btn-success').toggleClass('btn-default');
            $.ajax({
                url: target,
                type: 'POST',
                data: {
                    active: isActive
                },
                success: function() {
                    //toastr.success("<?= lang('hd_lang.translation_updated_successfully') ?>", "<?= lang('hd_lang.response_status') ?>");
                    window.location.href = base_url + 'settings/translations';
                    window.location.reload();
                },
                error: function(xhr) {
                    alert('Error: ' + JSON.stringify(xhr));
                }
            });
        });

        $(".delete-translation").on('click', function(e) {
            e.preventDefault();
            var target = $(this).attr('data-href');
            var lang_id = $(this).attr('data-lang-id');
            $.ajax({
                url: target,
                type: 'POST',
                data: {
                    lang_id: lang_id
                },
                success: function() {
                    //toastr.success("<?= lang('hd_lang.translation_deleted_successfully') ?>", "<?= lang('hd_lang.response_status') ?>");
                    window.location.href = base_url + 'settings/translations';
                    window.location.reload();
                },
                error: function(xhr) {
                    alert('Error: ' + JSON.stringify(xhr));
                }
            });
        });

        $('.backup-translation').on('click', function(e) {
            e.preventDefault();
            var target = $(this).attr('data-href');
            $.ajax({
                url: target,
                type: 'GET',
                data: {},
                success: function() {
                    //toastr.success("<?= lang('operation_successful') ?>", "<?= lang('response_status') ?>");
                    window.location.href = base_url + 'settings/translations';
                    window.location.reload();
                },
                error: function(xhr) {
                    alert('Error: ' + JSON.stringify(xhr));
                }
            });
        });
    });
</script>