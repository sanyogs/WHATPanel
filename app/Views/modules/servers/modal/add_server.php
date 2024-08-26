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
use App\Helpers\custom_name_helper;
$custom = new custom_name_helper();
?>

<div class="modal-dialog my-modal modal-cus modal-lg">
    <div class="modal-content">
        <div class="modal-header row-reverse"> <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">
                <?= lang('hd_lang.add_server') ?>
            </h4>
        </div>
        <?php
		$attributes = array('class' => 'bs-example form-horizontal');
		echo form_open(base_url('servers/add_server'), $attributes); ?>
        <div class="modal-body">
            <input type="hidden" name="r_url" value="<?= site_url("servers") ?>">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="common-label control-label">
                            <?= lang('hd_lang.name') ?>
                        </label>
                        <div class="inputDiv">
                            <input type="text" class="form-control common-input" name="name">
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="common-label control-label">
                            <?= lang('hd_lang.type') ?>
                        </label>
                        <div class="inputDiv">
                            <select name="type" class="common-select mb-3">
                                <?php 
								$session = \Config\Services::session();
								
								// Connect to the database
								$this->dbName = \Config\Database::connect();

								$this->pluginModel = new Plugin($this->dbName);
								
								$servers = $this->pluginModel::servers();
								foreach ($servers as $server) { ?>
                                <option value="<?= $server->system_name ?>">
                                    <?= lang($server->system_name) ?>
                                </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="row">
                    <div class="col-lg-2 col-6">
                        <div class="form-group">
                            <label class="common-label control-label">
                                <?= lang('hd_lang.default_server') ?>
                            </label>
                            <div class="inputDiv">
                                <label class="switch">
                                    <input type="hidden" value="off" name="selected" />
                                    <div class="form-check form-switch input-btn-div">
                                        <input class="form-check-input switch-cus" type="checkbox" name="selected">
                                    </div>
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </div>


                    <div class="col-lg-2 col-6">
                        <div class="form-group">
                            <label class="common-label control-label">
                                <?= lang('hd_lang.use_ssl') ?>
                            </label>
                            <div class="inputDiv">
                                <label class="switch">
                                    <input type="hidden" value="off" name="use_ssl" />
                                    <div class="form-check form-switch input-btn-div">
                                        <input class="form-check-input switch-cus" type="checkbox" name="use_ssl">
                                    </div>
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="common-label control-label">
                                <?= lang('hd_lang.server_hostname') ?>
                            </label>
                            <div class="inputDiv">
                                <input type="text" id="qty" class="form-control common-input" name="hostname">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="common-label control-label">
                                <?= lang('hd_lang.port') ?>
                            </label>
                            <div class="inputDiv">
                                <input type="text" id="price" class="form-control common-input" name="port">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="common-label control-label">
                                <?= lang('hd_lang.username') ?>
                            </label>
                            <div class="inputDiv">
                                <input type="text" id="qty" class="form-control common-input" name="username">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="common-label control-label">
                                <?= lang('hd_lang.password') ?>
                            </label>
                            <div class="inputDiv">
                                <input type="text" id="qty" class="form-control common-input" name="password">
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="common-label control-label">
                                <?= lang('hd_lang.api_key') ?>
                            </label>
                            <div class="inputDiv">
                                <input
                                    type="<?= $custom->getconfig_item('demo_mode') == 'TRUE' ? 'password' : 'text'; ?>"
                                    id="price" class="form-control common-input" name="authkey">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="common-label control-label">
                                <?= lang('hd_lang.ip') ?>
                            </label>
                            <div class="inputDiv">
                                <input
                                    type="<?= $custom->getconfig_item('demo_mode') == 'TRUE' ? 'password' : 'text'; ?>"
                                    id="price" class="form-control common-input" name="ip">
                            </div>
                        </div>
                    </div>
                </div>


            </div>

            <hr>

            <div class="row">

                <div class="form-group col-md-6">
                    <label class="common-label control-label">
                        <?= lang('hd_lang.nameserver_1') ?>
                    </label>
                    <div class="inputDiv">
                        <input type="text" class="form-control common-input" name="ns1">
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <label class="common-label control-label">
                        <?= lang('hd_lang.ip_address_1') ?>
                    </label>
                    <div class="inputDiv">
                        <input type="text" class="form-control common-input" name="ip1">
                    </div>
                </div>

            </div>

            <div class="row">

                <div class="form-group col-md-6">
                    <label class="common-label control-label">
                        <?= lang('hd_lang.nameserver_2') ?>
                    </label>
                    <div class="inputDiv">
                        <input type="text" class="form-control common-input" name="ns2">
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <label class="common-label control-label">
                        <?= lang('hd_lang.ip_address_2') ?>
                    </label>
                    <div class="inputDiv">
                        <input type="text" class="form-control common-input" name="ip2">
                    </div>
                </div>

            </div>

            <div class="row">

                <div class="form-group col-md-6">
                    <label class="common-label control-label">
                        <?= lang('hd_lang.nameserver_3') ?>
                    </label>
                    <div class="inputDiv">
                        <input type="text" class="form-control common-input" name="ns3">
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <label class="common-label control-label">
                        <?= lang('hd_lang.ip_address_3') ?>
                    </label>
                    <div class="inputDiv">
                        <input type="text" class="form-control common-input" name="ip3">
                    </div>
                </div>

            </div>

            <div class="row">


                <div class="form-group col-md-6">
                    <label class="common-label control-label">
                        <?= lang('hd_lang.nameserver_4') ?>
                    </label>
                    <div class="inputDiv">
                        <input type="text" class="form-control common-input" name="ns4">
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <label class="common-label control-label">
                        <?= lang('hd_lang.ip_address_4') ?>
                    </label>
                    <div class="inputDiv">
                        <input type="text" class="form-control common-input" name="ip4">
                    </div>
                </div>

            </div>

            <div class="row">

                <div class="form-group col-md-6">
                    <label class="common-label control-label">
                        <?= lang('hd_lang.nameserver_5') ?>
                    </label>
                    <div class="inputDiv">
                        <input type="text" class="form-control common-input" name="ns5">
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <label class="common-label control-label">
                        <?= lang('hd_lang.ip_address_5') ?>
                    </label>
                    <div class="inputDiv">
                        <input type="text" class="form-control common-input" name="ip5">
                    </div>
                </div>

            </div>

            <div class="modal-footer" style='margin-top:1% ;'> <a href="#" class="btn btn-default" data-dismiss="modal">
                    <?= lang('hd_lang.close') ?>
                </a>
                <button type="submit" class="btn btn-<?= $custom->getconfig_item('theme_color'); ?>">
                    <?= lang('hd_lang.add_server') ?>
                </button>
                </form>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->