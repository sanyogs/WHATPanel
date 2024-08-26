<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

// $domains = modules::run('domains/domain_pricing', ''); 

use App\Modules\domains\controllers\Domains;

use App\Libraries\AppLib;

use App\Models\Item;

use App\Helpers\custom_name_helper;

$domainController = new Domains();
$domains = $domainController->domain_pricing();

echo "<pre>";print_r(Item::get_domains());die;

$custom_helpers = new custom_name_helper();

?>
<section id="stickySearchBox" class="custom-sticky-head">
    <div class="container">
        <div class="stickySearchBoxWrap">
            <form>
                <div class="formWrap">
                    <input name="domain" type="hidden" id="domain">
                    <input name="price" type="hidden" id="price">
                    <input name="type" type="hidden" id="type">
                    <input type="text" name="domain" id="searchBar" placeholder="<?= lang('hd_lang.enter_domain_name') ?>" />
                    <div class="searchbtnWrap">
                        <div class="domainWrap" onclick="showHideDomainBox()">
                            <span class="domNameText">.com</span>
                            <span class="iconA">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="9" viewBox="0 0 12 9" fill="none">
                                    <path d="M6 0L11.1962 9H0.803848L6 0Z" fill="#FF6A00" />
                                </svg>
                            </span>
                            <div class="domainDropdownWrap" id="mydropdown" style="display: none;">
                                <ul>
                                    <li>
                                        <span class="radioBtn custom_radio">
                                            <?php foreach (Item::get_domains() as $domain) { ?>
                                                <input type="radio" id="<?php echo $domain->item_name; ?>" name="ext" value="<?php echo $domain->item_name; ?>" <?php if ($domain->item_name == '.in') echo 'checked'; ?> />
                                                <label for="<?php echo $domain->item_name; ?>"><?php echo $domain->item_name; ?></label>
                                            <?php } ?>
                                        </span>
                                        <input name="ext_name" type="hidden" id="ext_name" />
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <button type="submit" class="searchBtn">Search</button>
                    </div>

                </div>
            </form>
            <div class="checking">
                <img id="checking" src="<?= base_url('images/checking.gif') ?>" />
            </div>
            <div id="response"></div>
            <div class="domainExtenstionWrap">
                <ul>
                    <?php
                    $db = \Config\Database::connect();
                    // Define an array of services
                    $services = $db->table('hd_items_saved')
                        ->select('hd_items_saved.*, hd_item_pricing.*, hd_categories.id AS category_id, hd_categories.cat_name AS category_name')
                        ->join('hd_item_pricing', 'hd_item_pricing.item_id = hd_items_saved.item_id', 'left')
                        ->join('hd_categories', 'hd_categories.id = hd_item_pricing.category', 'left')
                        ->get()
                        ->getResult();
                    $custom_name_helper = new custom_name_helper();
                    foreach ($services as $service) {
                        if ($service->category_name == "Domains") { // Check if category_name is not equal to "Domain"
                    ?>
                            <li>
                                <span class="extnsText"><?= $service->item_name ?></span>
                                <span class="priceExt"><?= $custom_name_helper->getconfig_item('default_currency_symbol') ?><?= $service->registration ?></span>
                            </li>
                    <?php
                        } // End of if statement
                    } // End of foreach loop
                    ?>
                </ul>
                <div class="domainSerchMsgWrap">
                    <p class="availDomName d-none">
                        Congratulations! <span>demo.com</span> is available!
                    </p>
                    <p class="unavailDomName d-none">
                        <span>madpopo.com</span> is unavailable
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
<section id="secondBestChoiceSection">
    <div class="container">
        <div class="choiceWrap">
            <div class="col-lg-6 col-md-12">
                <div class="choiceImgWrap">
                    <img src="<?= base_url('assets/images/choice/why-us.png'); ?>" alt="" class="img-fluid" />
                </div>
            </div>
            <div class="col-lg-6 col-md-12">
                <div class="choiceContentWrp">
                    <div class="sectionTitleWrap">
                        <h2 class="secTitle">
                            Why Our <span>WordPress Hosting</span> Is the Best Choice
                        </h2>
                        <p class="secPara">
                            Lorem Ipsum is simply dummy text of the printing and
                            typesetting industry.
                        </p>
                    </div>
                    <div class="gridWrap">
                        <div class="gridBox">
                            <div class="leftIcon">
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="24" viewBox="0 0 20 24" fill="none">
                                        <path d="M16.581 2.14L10.316 0.0509967C10.1109 -0.0173244 9.88913 -0.0173244 9.684 0.0509967L3.419 2.14C2.42291 2.47088 1.55642 3.10725 0.942646 3.95871C0.328874 4.81016 -0.000961674 5.83338 2.10612e-06 6.883V12C2.10612e-06 19.563 9.2 23.74 9.594 23.914C9.72182 23.9708 9.86014 24.0001 10 24.0001C10.1399 24.0001 10.2782 23.9708 10.406 23.914C10.8 23.74 20 19.563 20 12V6.883C20.001 5.83338 19.6711 4.81016 19.0574 3.95871C18.4436 3.10725 17.5771 2.47088 16.581 2.14ZM18 12C18 17.455 11.681 21.033 10 21.889C8.317 21.036 2 17.469 2 12V6.883C2.00006 6.25327 2.19828 5.63954 2.56657 5.12874C2.93486 4.61794 3.45455 4.23599 4.052 4.037L10 2.054L15.948 4.037C16.5455 4.23599 17.0651 4.61794 17.4334 5.12874C17.8017 5.63954 17.9999 6.25327 18 6.883V12Z" fill="#3F38FC" />
                                        <path d="M13.3 8.30008L9.11204 12.5001L6.86804 10.1601C6.77798 10.0616 6.66913 9.98217 6.5479 9.92642C6.42666 9.87068 6.2955 9.83976 6.16213 9.83549C6.02877 9.83122 5.89589 9.85368 5.77134 9.90155C5.64679 9.94943 5.53308 10.0217 5.43691 10.1142C5.34074 10.2067 5.26405 10.3176 5.21137 10.4402C5.15869 10.5627 5.13107 10.6946 5.13015 10.8281C5.12923 10.9615 5.15502 11.0938 5.20601 11.2171C5.257 11.3404 5.33215 11.4523 5.42704 11.5461L7.73304 13.9461C7.90501 14.1318 8.11287 14.2808 8.34405 14.3839C8.57523 14.487 8.82493 14.5422 9.07804 14.5461H9.11104C9.35909 14.5469 9.60484 14.4984 9.83401 14.4035C10.0632 14.3086 10.2712 14.1691 10.446 13.9931L14.718 9.72107C14.8113 9.62797 14.8854 9.51739 14.936 9.39567C14.9865 9.27394 15.0126 9.14345 15.0128 9.01164C15.0129 8.87982 14.9871 8.74927 14.9368 8.62744C14.8865 8.50561 14.8126 8.39488 14.7195 8.30158C14.6264 8.20827 14.5159 8.13422 14.3941 8.08365C14.2724 8.03308 14.1419 8.00698 14.0101 8.00684C13.8783 8.0067 13.7477 8.03252 13.6259 8.08284C13.5041 8.13315 13.3933 8.20697 13.3 8.30008Z" fill="#3F38FC" />
                                    </svg>
                                </span>
                            </div>
                            <div class="rightContent">
                                <h5>Server level Protection</h5>
                                <p>
                                    We provide protection for your website from DDoS attacks.
                                </p>
                            </div>
                        </div>
                        <div class="gridBox">
                            <div class="leftIcon">
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <g clip-path="url(#clip0_1179_12764)">
                                            <path d="M23.02 8.79007C22.43 8.25007 21.66 7.98007 20.85 8.01007C20.05 8.05007 19.31 8.40007 18.76 8.99007L15.54 12.5201C14.99 11.6101 13.99 11.0001 12.85 11.0001H4C1.79 11.0001 0 12.7901 0 15.0001V20.0001C0 22.2101 1.79 24.0001 4 24.0001H8.96C11.81 24.0001 14.53 22.7801 16.43 20.6501L23.24 13.0101C24.33 11.7801 24.23 9.89007 23.02 8.78007V8.79007ZM21.75 11.6901L14.94 19.3301C13.42 21.0301 11.25 22.0101 8.97 22.0101H4C2.9 22.0101 2 21.1101 2 20.0101V15.0101C2 13.9101 2.9 13.0101 4 13.0101H12.86C13.49 13.0101 14 13.5201 14 14.1501C14 14.7101 13.58 15.2001 13.02 15.2801L7.86 16.0201C7.31 16.1001 6.93 16.6001 7.01 17.1501C7.09 17.7001 7.6 18.0801 8.14 18.0001L13.3 17.2601C14.48 17.0901 15.43 16.2701 15.81 15.2001L20.24 10.3401C20.42 10.1401 20.67 10.0201 20.94 10.0101C21.21 10.0101 21.47 10.0901 21.67 10.2701C22.08 10.6401 22.11 11.2801 21.74 11.6901H21.75Z" fill="#3F38FC" />
                                            <path d="M8.99997 9.99999H9.37997C10.83 9.99999 12 8.81999 12 7.37999C12 6.08999 11.08 4.99999 9.80997 4.78999L6.52997 4.23999C6.22997 4.18999 6.00997 3.92999 6.00997 3.61999C6.00997 3.27999 6.28997 2.99999 6.62997 2.99999H9.26997C9.62997 2.99999 9.95997 3.18999 10.14 3.49999C10.41 3.97999 11.02 4.13999 11.51 3.85999C11.99 3.57999 12.15 2.96999 11.87 2.48999C11.34 1.56999 10.34 0.98999 9.26997 0.98999H8.99997C8.99997 0.43999 8.54997 -0.0100098 7.99997 -0.0100098C7.44997 -0.0100098 6.99997 0.43999 6.99997 0.98999H6.61997C5.16997 0.98999 3.99997 2.16999 3.99997 3.60999C3.99997 4.89999 4.91997 5.98999 6.18997 6.19999L9.46997 6.74999C9.76997 6.79999 9.98996 7.05999 9.98996 7.36999C9.98996 7.70999 9.70997 7.98999 9.36997 7.98999H6.72997C6.36997 7.98999 6.03997 7.79999 5.85997 7.48999C5.57997 7.00999 4.96996 6.84999 4.48997 7.12999C4.00997 7.40999 3.84997 8.01999 4.12997 8.49999C4.65997 9.41999 5.65997 9.99999 6.72997 9.99999H6.99997C6.99997 10.55 7.44997 11 7.99997 11C8.54997 11 8.99997 10.55 8.99997 9.99999Z" fill="#3F38FC" />
                                        </g>
                                        <defs>
                                            <clipPath id="clip0_1179_12764">
                                                <rect width="24" height="24" fill="white" />
                                            </clipPath>
                                        </defs>
                                    </svg>
                                </span>
                            </div>
                            <div class="rightContent">
                                <h5>30 Day Money back</h5>
                                <p>
                                    If you are unsatisfied with our services, we’ll give you a
                                    full refund.
                                </p>
                            </div>
                        </div>
                        <div class="gridBox">
                            <div class="leftIcon">
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path d="M8.50027 9.49991C8.50027 10.0509 8.62827 10.5729 8.85627 11.0369C8.36627 11.6649 8.06127 12.4439 8.02027 13.2929C7.07927 12.3049 6.50027 10.9689 6.50027 9.50091C6.50027 6.08991 9.62227 3.39391 13.1593 4.11991C15.2413 4.54791 16.9283 6.22491 17.3723 8.30391C17.5063 8.93191 17.5313 9.54691 17.4633 10.1349C17.4053 10.6329 16.9683 11.0009 16.4663 11.0009H16.4213C15.8293 11.0009 15.4133 10.4739 15.4783 9.88591C15.5223 9.49091 15.4993 9.07591 15.3983 8.65291C15.1003 7.39991 14.0783 6.38491 12.8233 6.09591C10.5373 5.57091 8.49927 7.30291 8.49927 9.50091L8.50027 9.49991ZM4.61027 8.20491C4.88427 6.61191 5.66327 5.15991 6.87127 4.02691C8.40027 2.59391 10.4023 1.88591 12.5013 2.01591C16.4543 2.27191 19.5453 5.73491 19.4993 9.88091C19.4803 11.6169 18.0263 12.9989 16.2913 12.9989H13.8853C13.6413 12.1699 12.8833 11.5599 11.9753 11.5599C10.8703 11.5599 9.97527 12.4549 9.97527 13.5599C9.97527 14.6649 10.8703 15.5599 11.9753 15.5599C12.5133 15.5599 13.0003 15.3449 13.3593 14.9989H16.2913C19.1103 14.9989 21.4593 12.7539 21.4993 9.93591C21.5733 4.71491 17.6513 0.34491 12.6303 0.0209097C9.96627 -0.15209 7.43927 0.75291 5.50427 2.56891C4.00527 3.97391 3.00827 5.83391 2.64927 7.83491C2.54027 8.44291 3.02127 9.00091 3.63827 9.00091C4.11027 9.00091 4.53127 8.67191 4.61027 8.20591V8.20491ZM12.0003 16.9999C8.30527 16.9999 5.10827 19.2919 4.04527 22.7019C3.88027 23.2289 4.17527 23.7899 4.70227 23.9549C5.22827 24.1139 5.78927 23.8239 5.95427 23.2979C6.74327 20.7679 9.22827 18.9999 11.9993 18.9999C14.7703 18.9999 17.2563 20.7679 18.0443 23.2979C18.1783 23.7259 18.5723 23.9999 18.9993 23.9999C19.0983 23.9999 19.1973 23.9849 19.2973 23.9549C19.8243 23.7899 20.1183 23.2289 19.9543 22.7019C18.8913 19.2919 15.6943 16.9999 11.9993 16.9999H12.0003Z" fill="#3F38FC" />
                                    </svg>
                                </span>
                            </div>
                            <div class="rightContent">
                                <h5>24/7 Support</h5>
                                <p>
                                    Our professional support is always ready to provide
                                    assistance.
                                </p>
                            </div>
                        </div>
                        <div class="gridBox">
                            <div class="leftIcon">
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <g clip-path="url(#clip0_1179_12762)">
                                            <path d="M18 0H6C3.24 0 1 2.24 1 5V19C1 21.76 3.24 24 6 24H18C20.76 24 23 21.76 23 19V5C23 2.24 20.76 0 18 0ZM6 2H18C19.65 2 21 3.35 21 5V15H3V5C3 3.35 4.35 2 6 2ZM18 22H6C4.35 22 3 20.65 3 19V17H21V19C21 20.65 19.65 22 18 22ZM20 19.5C20 20.33 19.33 21 18.5 21C17.67 21 17 20.33 17 19.5C17 18.67 17.67 18 18.5 18C19.33 18 20 18.67 20 19.5ZM16 19.5C16 20.33 15.33 21 14.5 21C13.67 21 13 20.33 13 19.5C13 18.67 13.67 18 14.5 18C15.33 18 16 18.67 16 19.5Z" fill="#3F38FC" />
                                        </g>
                                        <defs>
                                            <clipPath id="clip0_1179_12762">
                                                <rect width="24" height="24" fill="white" />
                                            </clipPath>
                                        </defs>
                                    </svg>
                                </span>
                            </div>
                            <div class="rightContent">
                                <h5>High-Quality Hardware</h5>
                                <p>
                                    We use the latest hardware solutions that receive regular
                                    maintenance.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            $('#checking').hide();

            $(".custom_radio input[type='radio']").change(function() {
                var checkedValue = $("input[name='ext']:checked").val();
                $('#ext_name').val(checkedValue);
            });
            $('.searchBtn').on('click', function(e) {

                e.preventDefault();
                var name = $('#searchBar').val();

                var ext = $('#ext_name').val();

                if (name != '') {
                    if (ext == '') {
                        var ext = $("input[name='ext']:checked").val();
                    }
                    //alert(ext);
                    domain_name = name + ext;
                    tlds = ext;
                    //$(this).hide();
                    //$('#checking').show();
                    checkAvailability();
                } else {
                    swal("Empty Search!", "Please enter a domain name", "warning");
                }

            });

        });

        function checkAvailability() {
            $.ajax({
                url: '<?= base_url('domains/check_availability') ?>',
                type: 'POST',
                data: {
                    domain: domain_name,
                    type: 'Domain Registration',
                    ext: tlds
                },
                dataType: 'json',
                success: function(data) {
                    //console.log("inside success " + data);exit;
                    $('#domain').val(data.domain);
                    $('#price').val(data.price);
                    $('#type').val(type);
                    $('#checking').hide();
                    //$('#btnSearch').show();
                    //$('#continue').hide();
                    //$('#searchBar').val('');
                    //$('#Transfer').show();
                    //$('#textBar').val('');
                    $('#response').html(data.result);
                },


                error: function(data) {
                    console.log("inside error " + data);
                    $('#checking').hide();
                    $('#btnSearch').show();
                    $('#Search').show();
                    $('#Search').show();
                    $('#Transfer').show();
                }
            });
        }
    </script>
</section>