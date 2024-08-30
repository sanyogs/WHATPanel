<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */ 

use App\Models\User;
use App\Models\Client;

use App\Helpers\custom_name_helper;

$session = \Config\Services::session();

if (!$session->has('logged_in')) 
{
	// Redirect to login page
	header('Location: ' . base_url('login'));
	exit();
}

$userdata = $session->get('userdata');

$company = User::profile_info($userdata['user_id']);

$custom = new custom_name_helper();

?>

<?= $this->extend('layouts/users') ?>

<?= $this->section('content') ?>
<div class="box" style="margin-top:2%;">
    <div class="d-flex justify-content-end mb-4">
        <a href="#" id="toggleButton" data-toggle="class:show"
            class="btn common-button btn-sm btn-<?=$custom->getconfig_item('theme_color')?>"><i class="fa fa-plus"></i>
            <?=lang('hd_lang.new_user')?>
        </a>
    </div>
    <div class="box-body">

        <div id="aside" style="display: none;">
            <div class="row">

                <div class="col-md-6 mx-auto">
                    <div class="box box-primary">
                        <div class="hs-title-wrap p-0">
                            <h3><?=lang('hd_lang.new_user')?></h3>
                        </div>
                        <div class="box-body">
                            <?php
											echo form_open(base_url('auth/register_user'), ['id' => 'registerForm']); ?>
                            <p class="text-danger"><?php $session = \Config\Services::session(); 
											//echo session()->getFlashdata('form_errors'); ?></p>
                            <input type="hidden" name="r_url" value="<?=base_url('account')?>">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="common-label"><?=lang('hd_lang.full_name')?> <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="input-sm form-control common-input"
                                            value="<?=set_value('fullname')?>"
                                            placeholder="<?=lang('hd_lang.eg')?> <?=lang('hd_lang.user_placeholder_name')?>"
                                            name="fullname" pattern="[a-zA-Z ]+" required />
                                    </div>
                                    <div class="form-group">
                                        <label class="common-label"><?=lang('hd_lang.username')?> <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="username"
                                            placeholder="<?=lang('hd_lang.eg')?> <?=lang('hd_lang.user_placeholder_username')?>"
                                            value="<?=set_value('username')?>"
                                            class="input-sm form-control common-input" pattern="[a-zA-Z ]+" required />
                                    </div>

                                    <div class="form-group">
                                        <label class="common-label"><?=lang('hd_lang.password')?></label>
                                        <input type="password" placeholder="<?=lang('hd_lang.password')?>"
                                            value="<?=set_value('password')?>" name="password"
                                            class="input-sm form-control common-input" />
                                    </div>
                                    <div class="form-group">
                                        <label class="common-label"><?=lang('hd_lang.confirm_password')?></label>
                                        <input type="password" placeholder="<?=lang('hd_lang.confirm_password')?>"
                                            value="<?=set_value('confirm_password')?>" name="confirm_password"
                                            class="input-sm form-control common-input" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="common-label"><?=lang('hd_lang.email')?> <span
                                                class="text-danger">*</span></label>
                                        <input type="email"
                                            placeholder="<?=lang('hd_lang.eg')?> <?=lang('hd_lang.user_placeholder_email')?>"
                                            name="email" value="<?=set_value('email')?>"
                                            class="input-sm form-control common-input" required />
                                    </div>

                                    <div class="form-group">
                                        <label class="common-label"><?=lang('hd_lang.company')?></label>
                                        <select class="select2-option w_200 common-select mb-3" name="company">
                                            <optgroup label="<?=lang('hd_lang.default_company')?>">
                                            <option value="<?= $company->company ?>"><?= $custom->getconfig_item('company_name') ?></option>
                                            </optgroup>
                                            <optgroup label="<?=lang('hd_lang.other_companies')?>">
                                                <?php 
																
																$clients = Client::get_all_clients();

																if (!empty($clients)) {
																
																foreach ($clients as $company){ ?>
                                                <option value="<?=$company->co_id?>"><?=$company->company_name?>
                                                </option>
                                                <?php } } ?>
                                            </optgroup>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="common-label"><?=lang('hd_lang.phone')?> </label>
                                        <input type="text" class="input-sm form-control common-input"
                                            value="<?=set_value('phone')?>" name="phone"
                                            placeholder="<?=lang('hd_lang.eg')?> <?=lang('hd_lang.user_placeholder_phone')?>" pattern="[0-9]+"/>
                                    </div>
                                    <div class="form-group">
                                        <label class="common-label"><?=lang('hd_lang.role')?></label>
                                        <select name="role" class="form-control common-input">
                                            <?php foreach (User::get_roles() as $r) { ?>
                                            <option value="<?=$r->r_id?>"><?=ucfirst($r->role)?></option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                </div>
                            </div>
                            <div class="text-center mt-4">
                                <button type="submit"
                                    class="btn common-button btn-sm btn-<?=$custom->getconfig_item('theme_color')?>"><?=lang('hd_lang.register_user')?></button>
                            </div>
                           <?php form_close(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

        <script>
        $(document).ready(function() {
            // Add a click event handler to the toggle button
            $("#toggleButton").click(function(e) {
                e.preventDefault();
				$("#registerForm").find("input[type=text], input[type=email], input[type=password], textarea").val("");
                // Toggle the visibility of the #aside element
                $("#aside").toggle();
            });
        });
        </script>

        <div class="table-responsive">
            <table id="table-users" class="hs-table AppendDataTables mx-3">

                <tbody>
                    <tr>
                        <th><?=lang('hd_lang.username')?> </th>
                        <th><?=lang('hd_lang.full_name')?></th>
                        <th><?=lang('hd_lang.company')?> </th>
                        <th><?=lang('hd_lang.role')?> </th>
                        <th class="hidden-sm"><?=lang('hd_lang.date')?> </th>
                        <th class="col-options no-sort"><?=lang('hd_lang.options')?></th>
                    </tr>
                    <?php foreach (User::all_users() as $key => $user) { ?>
                    <tr>
                        <?php $info = User::profile_info($user->id); ?>
                        <td >
                            <a class="thumb-sm avatar" 
                                data-title="<?=User::login_info($user->id)->email?>" >


                                <img src="<?php //echo User::avatar_url($user->id); ?>" class="img-rounded radius_6">

                                <span
                                    class="label label-<?=($user->banned == '1') ? 'danger': 'success'?>"><?=$user->username?></span>

                            </a>
                        </td>

                        <td class="">
							<?= isset($info) ? $info->fullname : 'N/A'; ?>
						</td>

                        <td class="">
							<?php if (isset($info->company) && $info->company > 0) : ?>
								<?php $client = Client::view_by_id($info->company); ?>
								<a href="<?= base_url() ?>companies/view/<?= $info->company ?>" class="text-info">
									<?= $client ? $client->company_name : 'N/A'; ?>
								</a>
							<?php else : ?>
								N/A
							<?php endif; ?>
						</td>
                        <td>

                            <?php if (User::get_role($user->id) == 'admin') {
									  $span_badge = 'label label-danger';
								  }elseif (User::get_role($user->id) == 'staff') {
									  $span_badge = 'label label-info';
								  }elseif (User::get_role($user->id) == 'client') {
									  $span_badge = 'label label-default';
								  }else{
									  $span_badge = '';
								}
							?>
                            <span class="<?=$span_badge?>">
                                <?=lang(User::get_role($user->id))?></span>
                        </td>

                        <td class="hidden-sm">
                            <?=strftime($custom->getconfig_item('date_format'), strtotime($user->created));?>
                        </td>

                        <td>
                            <div class="d-flex gap-2 justify-content-center">
                            <a href="<?=base_url()?>account/auth/<?=$user->id?>" class="btn btn-vk btn-xs"
                                data-toggle="ajaxModal" title="<?=lang('hd_lang.user_edit_login')?>"><svg
                                    xmlns="http://www.w3.org/2000/svg" width="16" height="20" viewBox="0 0 16 20"
                                    fill="none">
                                    <path
                                        d="M13.6 6.7392V5.6C13.6 4.11479 13.01 2.69041 11.9598 1.6402C10.9096 0.589998 9.48521 0 8 0C6.51479 0 5.09041 0.589998 4.0402 1.6402C2.99 2.69041 2.4 4.11479 2.4 5.6V6.7392C1.68749 7.05016 1.08103 7.56202 0.654799 8.21217C0.228564 8.86232 0.0010227 9.62259 0 10.4V15.2C0.00127029 16.2605 0.423106 17.2772 1.17298 18.027C1.92285 18.7769 2.93952 19.1987 4 19.2H12C13.0605 19.1987 14.0772 18.7769 14.827 18.027C15.5769 17.2772 15.9987 16.2605 16 15.2V10.4C15.999 9.62259 15.7714 8.86232 15.3452 8.21217C14.919 7.56202 14.3125 7.05016 13.6 6.7392ZM4 5.6C4 4.53913 4.42143 3.52172 5.17157 2.77157C5.92172 2.02143 6.93913 1.6 8 1.6C9.06087 1.6 10.0783 2.02143 10.8284 2.77157C11.5786 3.52172 12 4.53913 12 5.6V6.4H4V5.6ZM14.4 15.2C14.4 15.8365 14.1471 16.447 13.6971 16.8971C13.247 17.3471 12.6365 17.6 12 17.6H4C3.36348 17.6 2.75303 17.3471 2.30294 16.8971C1.85286 16.447 1.6 15.8365 1.6 15.2V10.4C1.6 9.76348 1.85286 9.15303 2.30294 8.70294C2.75303 8.25286 3.36348 8 4 8H12C12.6365 8 13.247 8.25286 13.6971 8.70294C14.1471 9.15303 14.4 9.76348 14.4 10.4V15.2Z"
                                        fill="#2E0AA3" />
                                    <path
                                        d="M8.0002 11.2002C7.78802 11.2002 7.58454 11.2845 7.43451 11.4345C7.28448 11.5845 7.2002 11.788 7.2002 12.0002V13.6002C7.2002 13.8124 7.28448 14.0159 7.43451 14.1659C7.58454 14.3159 7.78802 14.4002 8.0002 14.4002C8.21237 14.4002 8.41585 14.3159 8.56588 14.1659C8.71591 14.0159 8.8002 13.8124 8.8002 13.6002V12.0002C8.8002 11.788 8.71591 11.5845 8.56588 11.4345C8.41585 11.2845 8.21237 11.2002 8.0002 11.2002Z"
                                        fill="#2E0AA3" />
                                </svg>
                            </a>
                            <?php if($user->role_id == '3') { ?>
                            <a href="<?=base_url()?>account/permissions/<?=$user->id?>" class="btn btn-xs"
                                data-toggle="ajaxModal" title="<?=lang('hd_lang.staff_permissions')?>"><i
                                    class="fa fa-shield"></i>
                            </a>
                            <?php } ?>

                            <a href="<?=base_url()?>account/update/<?=$user->id?>" class="btn btn-twitter btn-xs"
                                data-toggle="ajaxModal" title="<?=lang('hd_lang.edit')?>"><span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"
                                        fill="none">
                                        <g clip-path="url(#clip0_1514_208)">
                                            <path
                                                d="M12.4373 0.619885L4.30927 8.74789C3.99883 9.05665 3.75272 9.42392 3.58519 9.82845C3.41766 10.233 3.33203 10.6667 3.33327 11.1046V11.9999C3.33327 12.1767 3.4035 12.3463 3.52853 12.4713C3.65355 12.5963 3.82312 12.6666 3.99993 12.6666H4.89527C5.33311 12.6678 5.76685 12.5822 6.17137 12.4146C6.57589 12.2471 6.94317 12.001 7.25193 11.6906L15.3799 3.56255C15.7695 3.172 15.9883 2.64287 15.9883 2.09122C15.9883 1.53957 15.7695 1.01044 15.3799 0.619885C14.9837 0.241148 14.4567 0.0297852 13.9086 0.0297852C13.3605 0.0297852 12.8335 0.241148 12.4373 0.619885ZM14.4373 2.61989L6.30927 10.7479C5.93335 11.1215 5.42527 11.3318 4.89527 11.3332H4.6666V11.1046C4.66799 10.5745 4.87831 10.0665 5.25193 9.69055L13.3799 1.56255C13.5223 1.42652 13.7117 1.35061 13.9086 1.35061C14.1055 1.35061 14.2949 1.42652 14.4373 1.56255C14.5772 1.7029 14.6558 1.89301 14.6558 2.09122C14.6558 2.28942 14.5772 2.47954 14.4373 2.61989Z"
                                                fill="#1912D3"></path>
                                            <path
                                                d="M15.3333 5.986C15.1565 5.986 14.987 6.05624 14.8619 6.18126C14.7369 6.30629 14.6667 6.47586 14.6667 6.65267V10H12C11.4696 10 10.9609 10.2107 10.5858 10.5858C10.2107 10.9609 10 11.4696 10 12V14.6667H3.33333C2.8029 14.6667 2.29419 14.456 1.91912 14.0809C1.54405 13.7058 1.33333 13.1971 1.33333 12.6667V3.33333C1.33333 2.8029 1.54405 2.29419 1.91912 1.91912C2.29419 1.54405 2.8029 1.33333 3.33333 1.33333H9.36133C9.53815 1.33333 9.70771 1.2631 9.83274 1.13807C9.95776 1.01305 10.028 0.843478 10.028 0.666667C10.028 0.489856 9.95776 0.320286 9.83274 0.195262C9.70771 0.0702379 9.53815 0 9.36133 0L3.33333 0C2.4496 0.00105857 1.60237 0.352588 0.97748 0.97748C0.352588 1.60237 0.00105857 2.4496 0 3.33333L0 12.6667C0.00105857 13.5504 0.352588 14.3976 0.97748 15.0225C1.60237 15.6474 2.4496 15.9989 3.33333 16H10.8953C11.3333 16.0013 11.7671 15.9156 12.1718 15.7481C12.5764 15.5806 12.9438 15.3345 13.2527 15.024L15.0233 13.252C15.3338 12.9432 15.58 12.576 15.7477 12.1715C15.9153 11.767 16.0011 11.3332 16 10.8953V6.65267C16 6.47586 15.9298 6.30629 15.8047 6.18126C15.6797 6.05624 15.5101 5.986 15.3333 5.986ZM12.31 14.0813C12.042 14.3487 11.7031 14.5337 11.3333 14.6147V12C11.3333 11.8232 11.4036 11.6536 11.5286 11.5286C11.6536 11.4036 11.8232 11.3333 12 11.3333H14.6167C14.5342 11.7023 14.3493 12.0406 14.0833 12.3093L12.31 14.0813Z"
                                                fill="#1912D3"></path>
                                        </g>
                                        <defs>
                                            <clipPath id="clip0_1514_208">
                                                <rect width="16" height="16" fill="white"></rect>
                                            </clipPath>
                                        </defs>
                                    </svg>
                                </span>
                            </a>
                            <?php  if ($user->id != User::get_id()) { ?>

                            <a href="<?=base_url()?>account/ban/<?=$user->id?>"
                                class="btn btn-<?=($user->banned == '1') ? '': ''?> btn-xs"
                                data-toggle="ajaxModal" title="<?=lang('hd_lang.ban_user')?>"><svg
                                    xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"
                                    fill="none">
                                    <path
                                        d="M10.666 5.33337C10.541 5.20839 10.3714 5.13818 10.1946 5.13818C10.0179 5.13818 9.84833 5.20839 9.72331 5.33337L7.99931 7.05737L6.27532 5.33337C6.14958 5.21194 5.98118 5.14474 5.80638 5.14626C5.63158 5.14778 5.46438 5.21789 5.34077 5.3415C5.21716 5.4651 5.14705 5.63231 5.14553 5.80711C5.14401 5.9819 5.21121 6.15031 5.33265 6.27604L7.05665 8.00004L5.33265 9.72404C5.21121 9.84978 5.14401 10.0182 5.14553 10.193C5.14705 10.3678 5.21716 10.535 5.34077 10.6586C5.46438 10.7822 5.63158 10.8523 5.80638 10.8538C5.98118 10.8553 6.14958 10.7881 6.27532 10.6667L7.99931 8.94271L9.72331 10.6667C9.84905 10.7881 10.0175 10.8553 10.1922 10.8538C10.367 10.8523 10.5343 10.7822 10.6579 10.6586C10.7815 10.535 10.8516 10.3678 10.8531 10.193C10.8546 10.0182 10.7874 9.84978 10.666 9.72404L8.94198 8.00004L10.666 6.27604C10.791 6.15102 10.8612 5.98148 10.8612 5.80471C10.8612 5.62793 10.791 5.45839 10.666 5.33337Z"
                                        fill="#2E0AA3" />
                                    <path
                                        d="M8 0C6.41775 0 4.87103 0.469192 3.55544 1.34824C2.23985 2.22729 1.21447 3.47672 0.608967 4.93853C0.00346629 6.40034 -0.15496 8.00887 0.153721 9.56072C0.462403 11.1126 1.22433 12.538 2.34315 13.6569C3.46197 14.7757 4.88743 15.5376 6.43928 15.8463C7.99113 16.155 9.59966 15.9965 11.0615 15.391C12.5233 14.7855 13.7727 13.7602 14.6518 12.4446C15.5308 11.129 16 9.58225 16 8C15.9977 5.87897 15.1541 3.84547 13.6543 2.34568C12.1545 0.845885 10.121 0.00229405 8 0ZM8 14.6667C6.68146 14.6667 5.39253 14.2757 4.2962 13.5431C3.19987 12.8106 2.34539 11.7694 1.84081 10.5512C1.33622 9.33305 1.2042 7.9926 1.46143 6.6994C1.71867 5.40619 2.35361 4.2183 3.28596 3.28595C4.21831 2.3536 5.40619 1.71867 6.6994 1.46143C7.99261 1.2042 9.33305 1.33622 10.5512 1.8408C11.7694 2.34539 12.8106 3.19987 13.5431 4.2962C14.2757 5.39253 14.6667 6.68146 14.6667 8C14.6647 9.76751 13.9617 11.4621 12.7119 12.7119C11.4621 13.9617 9.76752 14.6647 8 14.6667Z"
                                        fill="#2E0AA3" />
                                </svg>
                            </a>

                            <a href="<?=base_url()?>account/delete/<?=$user->id?>" class="btn btn-google btn-xs"
                                data-toggle="ajaxModal" title="<?=lang('hd_lang.delete')?>"><span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"
                                        fill="none">
                                        <g clip-path="url(#clip0_1514_211)">
                                            <path
                                                d="M13.9999 2.66667H11.9333C11.7785 1.91428 11.3691 1.23823 10.7741 0.752479C10.179 0.266727 9.43472 0.000969683 8.66659 0L7.33325 0C6.56512 0.000969683 5.8208 0.266727 5.22575 0.752479C4.63071 1.23823 4.22132 1.91428 4.06659 2.66667H1.99992C1.82311 2.66667 1.65354 2.7369 1.52851 2.86193C1.40349 2.98695 1.33325 3.15652 1.33325 3.33333C1.33325 3.51014 1.40349 3.67971 1.52851 3.80474C1.65354 3.92976 1.82311 4 1.99992 4H2.66659V12.6667C2.66764 13.5504 3.01917 14.3976 3.64407 15.0225C4.26896 15.6474 5.11619 15.9989 5.99992 16H9.99992C10.8836 15.9989 11.7309 15.6474 12.3558 15.0225C12.9807 14.3976 13.3322 13.5504 13.3333 12.6667V4H13.9999C14.1767 4 14.3463 3.92976 14.4713 3.80474C14.5963 3.67971 14.6666 3.51014 14.6666 3.33333C14.6666 3.15652 14.5963 2.98695 14.4713 2.86193C14.3463 2.7369 14.1767 2.66667 13.9999 2.66667ZM7.33325 1.33333H8.66659C9.0801 1.33384 9.48334 1.46225 9.821 1.70096C10.1587 1.93967 10.4142 2.27699 10.5526 2.66667H5.44725C5.58564 2.27699 5.84119 1.93967 6.17884 1.70096C6.5165 1.46225 6.91974 1.33384 7.33325 1.33333ZM11.9999 12.6667C11.9999 13.1971 11.7892 13.7058 11.4141 14.0809C11.0391 14.456 10.5304 14.6667 9.99992 14.6667H5.99992C5.46949 14.6667 4.96078 14.456 4.58571 14.0809C4.21063 13.7058 3.99992 13.1971 3.99992 12.6667V4H11.9999V12.6667Z"
                                                fill="#1912D3"></path>
                                            <path
                                                d="M6.66667 11.9998C6.84348 11.9998 7.01305 11.9296 7.13807 11.8046C7.2631 11.6796 7.33333 11.51 7.33333 11.3332V7.33317C7.33333 7.15636 7.2631 6.98679 7.13807 6.86177C7.01305 6.73674 6.84348 6.6665 6.66667 6.6665C6.48986 6.6665 6.32029 6.73674 6.19526 6.86177C6.07024 6.98679 6 7.15636 6 7.33317V11.3332C6 11.51 6.07024 11.6796 6.19526 11.8046C6.32029 11.9296 6.48986 11.9998 6.66667 11.9998Z"
                                                fill="#1912D3"></path>
                                            <path
                                                d="M9.33341 11.9998C9.51023 11.9998 9.67979 11.9296 9.80482 11.8046C9.92984 11.6796 10.0001 11.51 10.0001 11.3332V7.33317C10.0001 7.15636 9.92984 6.98679 9.80482 6.86177C9.67979 6.73674 9.51023 6.6665 9.33341 6.6665C9.1566 6.6665 8.98703 6.73674 8.86201 6.86177C8.73699 6.98679 8.66675 7.15636 8.66675 7.33317V11.3332C8.66675 11.51 8.73699 11.6796 8.86201 11.8046C8.98703 11.9296 9.1566 11.9998 9.33341 11.9998Z"
                                                fill="#1912D3"></path>
                                        </g>
                                        <defs>
                                            <clipPath id="clip0_1514_211">
                                                <rect width="16" height="16" fill="white"></rect>
                                            </clipPath>
                                        </defs>
                                    </svg>
                                </span>
                            </a>
                            <?php }?>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
	<script>
	  var inputFields = document.querySelectorAll('input[name="fullname"], input[name="username"]');
	  inputFields.forEach(function(inputField) {
		inputField.addEventListener('input', function() {
		  var inputValue = this.value;
		  if (/\d/.test(inputValue)) {
			this.value = inputValue.replace(/\d/g, '');
		  }
		});
	  });

	  var phoneInputField = document.querySelector('input[name="phone"]');
	  phoneInputField.addEventListener('input', function() {
		var inputValue = this.value;
		if (/[a-zA-Z]/.test(inputValue)) {
		  this.value = inputValue.replace(/[a-zA-Z]/g, '');
		}
	  });
	</script>
    <?= $this->endSection() ?>