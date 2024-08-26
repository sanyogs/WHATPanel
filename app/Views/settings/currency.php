<div class="row">
    <!-- Start Form -->
    <div class="col-lg-12">

        <?php

        use App\Helpers\custom_name_helper;

        $attributes = array('class' => 'bs-example form-horizontal');
        echo form_open_multipart('settings/xrates', $attributes);
        $custom = new custom_name_helper();

        ?>

        <div class="form-group">
            <label class="control-label common-label"><?= lang('hd_lang.xrates_app_id') ?></label>
            <input type="hidden" name="return_url" value="<?= base_url() ?>settings/currency">
            <div class="inputDiv">
                <div class="inneRow">
                    <input type="text" name="xrates_app_id" class="form-control common-input" value="<?= $custom->getconfig_item('xrates_app_id') ?>">
                    <small class="smallText"><a target="_blank" class="" href="https://openexchangerates.org/signup/free"><?= lang('hd_lang.get_api_key') ?></a></small>
                </div>
            </div>


        </div>

        <div class="text-center mb-3">
            <button type="submit" class="btn btn-sm common-button btn-<?= $custom->getconfig_item('theme_color'); ?>"><?= lang('hd_lang.save_changes') ?></button>
        </div>

        </form>

        <div class="table-responsive ">

            <a href="<?= base_url('settings1/add_currency') ?>" data-toggle="ajaxModal" title="<?= lang('hd_lang.add_currency') ?>" class="btn btn-twitter btn-sm common-button btn-primary"><?= lang('hd_lang.add_currency') ?></a>
            <a href="#" id="updateCurrencyButton" class="btn btn-twitter btn-sm common-button btn-success"><?= lang('hd_lang.currency_cron') ?></a>
            <hr>

            <div class="alert alert-info small common-alert">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 16 16" fill="none">
                        <path d="M10.4713 6.47133L8.94267 8L10.4713 9.52867C10.732 9.78933 10.732 10.2107 10.4713 10.4713C10.3413 10.6013 10.1707 10.6667 10 10.6667C9.82933 10.6667 9.65867 10.6013 9.52867 10.4713L8 8.94267L6.47133 10.4713C6.34133 10.6013 6.17067 10.6667 6 10.6667C5.82933 10.6667 5.65867 10.6013 5.52867 10.4713C5.268 10.2107 5.268 9.78933 5.52867 9.52867L7.05733 8L5.52867 6.47133C5.268 6.21067 5.268 5.78933 5.52867 5.52867C5.78933 5.268 6.21067 5.268 6.47133 5.52867L8 7.05733L9.52867 5.52867C9.78933 5.268 10.2107 5.268 10.4713 5.52867C10.732 5.78933 10.732 6.21067 10.4713 6.47133ZM16 8C16 12.4113 12.4113 16 8 16C3.58867 16 0 12.4113 0 8C0 3.58867 3.58867 0 8 0C12.4113 0 16 3.58867 16 8ZM14.6667 8C14.6667 4.324 11.676 1.33333 8 1.33333C4.324 1.33333 1.33333 4.324 1.33333 8C1.33333 11.676 4.324 14.6667 8 14.6667C11.676 14.6667 14.6667 11.676 14.6667 8Z" fill="#888888"></path>
                    </svg></button>
                <strong class='me-1'>Notice</strong> Rates based on United States Dollar (USD)
            </div>

            <h3 class="common-h px-3 mt-5">Active Currency</h3>

            <div class="container">
                <table class=" hs-table m-0">
                    <!-- <thead>
                    <tr>

                        <th class="th-sortable" data-toggle="class">Code</th>
                        <th>Code Name</th>
                        <th>Symbol</th>
                        <th>xChange Rate</th>
                        <th width="30"></th>
                    </tr>
                </thead> -->
                    <tbody>
                        <tr>

                            <th class="th-sortable" data-toggle="class">Code</th>
                            <th>Code Name</th>
                            <th>Symbol</th>
                            <th>xChange Rate</th>
                            <th>Status</th>
                            <th width="30"></th>
                        </tr>
                        <?php
                        $session = \Config\Services::session();

                        // Connect to the database
                        $dbName = \Config\Database::connect();
                        $currencies = $dbName->table('hd_currencies')->where('status', '1')->get()->getResult();
                        foreach ($currencies as $key => $cur) { ?>

                            <tr>
                                <td><?= $cur->code ?></td>
                                <td><?= $cur->name ?></td>
                                <td><?= $cur->symbol ?></td>
                                <td><?= $cur->xrate ?></td>
                                <td><?php if($cur->status == '1') echo "Active"; else echo "Disable"; ?></td>
                                <td>
                                    <a href="<?= base_url() ?>settings1/edit_currency/<?= $cur->code ?>" data-toggle="ajaxModal" data-placement="left" title="<?= lang('hd_lang.edit_currency') ?>">
                                        <span>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                <g clip-path="url(#clip0_1514_208)">
                                                    <path d="M12.4373 0.619885L4.30927 8.74789C3.99883 9.05665 3.75272 9.42392 3.58519 9.82845C3.41766 10.233 3.33203 10.6667 3.33327 11.1046V11.9999C3.33327 12.1767 3.4035 12.3463 3.52853 12.4713C3.65355 12.5963 3.82312 12.6666 3.99993 12.6666H4.89527C5.33311 12.6678 5.76685 12.5822 6.17137 12.4146C6.57589 12.2471 6.94317 12.001 7.25193 11.6906L15.3799 3.56255C15.7695 3.172 15.9883 2.64287 15.9883 2.09122C15.9883 1.53957 15.7695 1.01044 15.3799 0.619885C14.9837 0.241148 14.4567 0.0297852 13.9086 0.0297852C13.3605 0.0297852 12.8335 0.241148 12.4373 0.619885ZM14.4373 2.61989L6.30927 10.7479C5.93335 11.1215 5.42527 11.3318 4.89527 11.3332H4.6666V11.1046C4.66799 10.5745 4.87831 10.0665 5.25193 9.69055L13.3799 1.56255C13.5223 1.42652 13.7117 1.35061 13.9086 1.35061C14.1055 1.35061 14.2949 1.42652 14.4373 1.56255C14.5772 1.7029 14.6558 1.89301 14.6558 2.09122C14.6558 2.28942 14.5772 2.47954 14.4373 2.61989Z" fill="#1912D3"></path>
                                                    <path d="M15.3333 5.986C15.1565 5.986 14.987 6.05624 14.8619 6.18126C14.7369 6.30629 14.6667 6.47586 14.6667 6.65267V10H12C11.4696 10 10.9609 10.2107 10.5858 10.5858C10.2107 10.9609 10 11.4696 10 12V14.6667H3.33333C2.8029 14.6667 2.29419 14.456 1.91912 14.0809C1.54405 13.7058 1.33333 13.1971 1.33333 12.6667V3.33333C1.33333 2.8029 1.54405 2.29419 1.91912 1.91912C2.29419 1.54405 2.8029 1.33333 3.33333 1.33333H9.36133C9.53815 1.33333 9.70771 1.2631 9.83274 1.13807C9.95776 1.01305 10.028 0.843478 10.028 0.666667C10.028 0.489856 9.95776 0.320286 9.83274 0.195262C9.70771 0.0702379 9.53815 0 9.36133 0L3.33333 0C2.4496 0.00105857 1.60237 0.352588 0.97748 0.97748C0.352588 1.60237 0.00105857 2.4496 0 3.33333L0 12.6667C0.00105857 13.5504 0.352588 14.3976 0.97748 15.0225C1.60237 15.6474 2.4496 15.9989 3.33333 16H10.8953C11.3333 16.0013 11.7671 15.9156 12.1718 15.7481C12.5764 15.5806 12.9438 15.3345 13.2527 15.024L15.0233 13.252C15.3338 12.9432 15.58 12.576 15.7477 12.1715C15.9153 11.767 16.0011 11.3332 16 10.8953V6.65267C16 6.47586 15.9298 6.30629 15.8047 6.18126C15.6797 6.05624 15.5101 5.986 15.3333 5.986ZM12.31 14.0813C12.042 14.3487 11.7031 14.5337 11.3333 14.6147V12C11.3333 11.8232 11.4036 11.6536 11.5286 11.5286C11.6536 11.4036 11.8232 11.3333 12 11.3333H14.6167C14.5342 11.7023 14.3493 12.0406 14.0833 12.3093L12.31 14.0813Z" fill="#1912D3"></path>
                                                </g>
                                                <defs>
                                                    <clipPath id="clip0_1514_208">
                                                        <rect width="16" height="16" fill="white"></rect>
                                                    </clipPath>
                                                </defs>
                                            </svg>
                                        </span>
                                    </a>
                                </td>
                            </tr>

                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <h3 class="common-h px-3 mt-5">Disabled Currency</h3>

            <div class="container">
                <table class=" hs-table m-0">
                    <!-- <thead>
                    <tr>

                        <th class="th-sortable" data-toggle="class">Code</th>
                        <th>Code Name</th>
                        <th>Symbol</th>
                        <th>xChange Rate</th>
                        <th width="30"></th>
                    </tr>
                </thead> -->
                    <tbody>
                        <tr>

                            <th class="th-sortable" data-toggle="class">Code</th>
                            <th>Code Name</th>
                            <th>Symbol</th>
                            <th>xChange Rate</th>
                            <th>Status</th>
                            <th width="30"></th>
                        </tr>
                        <?php
                        $session = \Config\Services::session();

                        // Connect to the database
                        $dbName = \Config\Database::connect();
                        $currencies = $dbName->table('hd_currencies')->where('status', '0')->get()->getResult();
                        foreach ($currencies as $key => $cur) { ?>

                            <tr>
                                <td><?= $cur->code ?></td>
                                <td><?= $cur->name ?></td>
                                <td><?= $cur->symbol ?></td>
                                <td><?= $cur->xrate ?></td>
                                <td><?php if($cur->status == '1') echo "Active"; else echo "Disable"; ?></td>
                                <td>
                                    <a href="<?= base_url() ?>settings1/edit_currency/<?= $cur->code ?>" data-toggle="ajaxModal" data-placement="left" title="<?= lang('hd_lang.edit_currency') ?>">
                                        <span>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                <g clip-path="url(#clip0_1514_208)">
                                                    <path d="M12.4373 0.619885L4.30927 8.74789C3.99883 9.05665 3.75272 9.42392 3.58519 9.82845C3.41766 10.233 3.33203 10.6667 3.33327 11.1046V11.9999C3.33327 12.1767 3.4035 12.3463 3.52853 12.4713C3.65355 12.5963 3.82312 12.6666 3.99993 12.6666H4.89527C5.33311 12.6678 5.76685 12.5822 6.17137 12.4146C6.57589 12.2471 6.94317 12.001 7.25193 11.6906L15.3799 3.56255C15.7695 3.172 15.9883 2.64287 15.9883 2.09122C15.9883 1.53957 15.7695 1.01044 15.3799 0.619885C14.9837 0.241148 14.4567 0.0297852 13.9086 0.0297852C13.3605 0.0297852 12.8335 0.241148 12.4373 0.619885ZM14.4373 2.61989L6.30927 10.7479C5.93335 11.1215 5.42527 11.3318 4.89527 11.3332H4.6666V11.1046C4.66799 10.5745 4.87831 10.0665 5.25193 9.69055L13.3799 1.56255C13.5223 1.42652 13.7117 1.35061 13.9086 1.35061C14.1055 1.35061 14.2949 1.42652 14.4373 1.56255C14.5772 1.7029 14.6558 1.89301 14.6558 2.09122C14.6558 2.28942 14.5772 2.47954 14.4373 2.61989Z" fill="#1912D3"></path>
                                                    <path d="M15.3333 5.986C15.1565 5.986 14.987 6.05624 14.8619 6.18126C14.7369 6.30629 14.6667 6.47586 14.6667 6.65267V10H12C11.4696 10 10.9609 10.2107 10.5858 10.5858C10.2107 10.9609 10 11.4696 10 12V14.6667H3.33333C2.8029 14.6667 2.29419 14.456 1.91912 14.0809C1.54405 13.7058 1.33333 13.1971 1.33333 12.6667V3.33333C1.33333 2.8029 1.54405 2.29419 1.91912 1.91912C2.29419 1.54405 2.8029 1.33333 3.33333 1.33333H9.36133C9.53815 1.33333 9.70771 1.2631 9.83274 1.13807C9.95776 1.01305 10.028 0.843478 10.028 0.666667C10.028 0.489856 9.95776 0.320286 9.83274 0.195262C9.70771 0.0702379 9.53815 0 9.36133 0L3.33333 0C2.4496 0.00105857 1.60237 0.352588 0.97748 0.97748C0.352588 1.60237 0.00105857 2.4496 0 3.33333L0 12.6667C0.00105857 13.5504 0.352588 14.3976 0.97748 15.0225C1.60237 15.6474 2.4496 15.9989 3.33333 16H10.8953C11.3333 16.0013 11.7671 15.9156 12.1718 15.7481C12.5764 15.5806 12.9438 15.3345 13.2527 15.024L15.0233 13.252C15.3338 12.9432 15.58 12.576 15.7477 12.1715C15.9153 11.767 16.0011 11.3332 16 10.8953V6.65267C16 6.47586 15.9298 6.30629 15.8047 6.18126C15.6797 6.05624 15.5101 5.986 15.3333 5.986ZM12.31 14.0813C12.042 14.3487 11.7031 14.5337 11.3333 14.6147V12C11.3333 11.8232 11.4036 11.6536 11.5286 11.5286C11.6536 11.4036 11.8232 11.3333 12 11.3333H14.6167C14.5342 11.7023 14.3493 12.0406 14.0833 12.3093L12.31 14.0813Z" fill="#1912D3"></path>
                                                </g>
                                                <defs>
                                                    <clipPath id="clip0_1514_208">
                                                        <rect width="16" height="16" fill="white"></rect>
                                                    </clipPath>
                                                </defs>
                                            </svg>
                                        </span>
                                    </a>
                                </td>
                            </tr>

                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#updateCurrencyButton').on('click', function(e) {
                e.preventDefault(); // Prevent the default action of the link

                // Make an AJAX request to call the xrate() function
                $.ajax({
                    url: '<?= base_url('xrate') ?>',
                    type: 'GET',
                    success: function(response) {
                        // Handle the response if needed
                        alert('Currency update successful');
                        // Reload the page
                        window.location.reload();
                    },
                    error: function(xhr, status, error) {
                        // Handle errors if any
                        alert('Error updating currency:', error);
                    }
                });
            });
        });
    </script>
    <!-- End Form -->
</div>