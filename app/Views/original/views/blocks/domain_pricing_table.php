<?php
/*
* This file is part of WHATPANEL.
*
* @package     WHAT PANEL – Web Hosting Access Terminal Panel.
* @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
* @license     BSL; see LICENSE.txt
* @link        https://www.version-next.com
*/

/**
 * Name: Domain Pricing
 * Description: A table of domain extensions and prices.
 */
// $domains = modules::run('domains/domain_pricing', ''); 

use App\Modules\domains\controllers\Domains;

use App\Libraries\AppLib;

use App\Models\Item;

use App\Helpers\custom_name_helper;

$domainController = new Domains();
$domains = $domainController->domain_pricing();

$custom_helper = new custom_name_helper();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Hosting Bill | Madpopo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="<?= site_url('assets/css/main.css'); ?>" />
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css" />
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" />
</head>

<body>
    <!-- Topbar Start -->
    <section id="topbarSection" class="innerHeader" style="padding-bottom: 37rem !important;">
        <!-- Navbar Start -->
        <?= view($custom_helper->getconfig_item('active_theme') . '/views/sections/header.php'); ?>
        <!-- Navbar End -->

        <!-- TopbarText Start -->
        <?= view($custom_helper->getconfig_item('active_theme') . '/views/sections/page_header.php'); ?>
        <!-- BannerImg End -->
    </section>
    <section id="domain-table-wrap" style="margin-top: 11px;">
        <div class="container">
            <div class="domain-topbar-wrap">
                <div class="domain-title-wrap">
                    <h3>Hosting Packages</h3>
                    <p>Showing hosting packages list</p>
                </div>
                <div class="domain-search-wrap">
                    <div class="domain-InputWrap">
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="15" viewBox="0 0 18 15" fill="#172F78">
                                <path d="M1.36083 13.5869L4.9727 11.4839C6.06246 12.9679 7.67959 13.9784 9.4916 14.3078C11.3036 14.6371 13.1727 14.2602 14.7147 13.2545C16.2566 12.2488 17.3542 10.6908 17.7816 8.90083C18.2091 7.11086 17.934 5.22499 17.0129 3.63098C16.0918 2.03698 14.5947 0.856004 12.8295 0.330892C11.0642 -0.19422 9.16497 -0.0235551 7.52228 0.807794C5.87959 1.63914 4.61831 3.06798 3.99777 4.80052C3.37723 6.53306 3.44461 8.4376 4.18605 10.1225L0.580387 12.2024C0.488711 12.2548 0.408327 12.3248 0.343873 12.4084C0.279418 12.492 0.232171 12.5875 0.204853 12.6895C0.177535 12.7914 0.170689 12.8978 0.18471 13.0024C0.198731 13.107 0.233342 13.2079 0.286544 13.2991C0.39336 13.4769 0.565107 13.6063 0.765541 13.66C0.965975 13.7137 1.17942 13.6875 1.36083 13.5869ZM5.34694 5.79003C5.63343 4.72083 6.23079 3.76066 7.06346 3.03094C7.89614 2.30122 8.92674 1.83472 10.0249 1.69044C11.1231 1.54616 12.2396 1.73058 13.2332 2.22037C14.2267 2.71016 15.0528 3.48332 15.6068 4.4421C16.1608 5.40087 16.418 6.50218 16.3457 7.60677C16.2735 8.71136 15.8751 9.76962 15.201 10.6477C14.5268 11.5258 13.6071 12.1843 12.5583 12.5399C11.5094 12.8956 10.3785 12.9323 9.3085 12.6456C7.87365 12.2612 6.65019 11.3229 5.90726 10.0372C5.16432 8.75154 4.96277 7.22378 5.34694 5.79003Z" fill="#172F78"></path>
                            </svg>
                        </span>
                        <input type="text" placeholder="Search..." />
                    </div>
                </div>
            </div>
            <div class="d-table-wrap">
                <div class="tableInfoHead">
                    <div class="showEntriesWrap">
                        <span>Show</span>
                        <select>
                            <option>10</option>
                            <option>10</option>
                            <option>10</option>
                        </select>
                        <span>Entries</span>
                    </div>
                </div>
                <table class="domain-table">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Extension</th>
                            <th>Registration</th>
                            <th>Transfer</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>01</td>
                            <td>.bz</td>
                            <td>$15.00</td>
                            <td>$15.00</td>
                        </tr>
                        <tr>
                            <td>02</td>
                            <td>.co.uk</td>
                            <td>$10.00</td>
                            <td>$10.00</td>
                        </tr>
                        <tr>
                            <td>03</td>
                            <td>.com</td>
                            <td>$18.00</td>
                            <td>$18.00</td>
                        </tr>
                        <tr>
                            <td>04</td>
                            <td>.com.au</td>
                            <td>$10.00</td>
                            <td>$10.00</td>
                        </tr>
                        <tr>
                            <td>05</td>
                            <td>.in</td>
                            <td>$18.00</td>
                            <td>$18.00</td>
                        </tr>
                        <tr>
                            <td>06</td>
                            <td>.info</td>
                            <td>$10.00</td>
                            <td>$10.00</td>
                        </tr>
                    </tbody>
                </table>

                <div class="domain-table-pagination">
                    <div class="showingEntriesWrap">
                        <p>Showing 1 of 57 entries</p>
                    </div>
                    <div class="domain-pagination-wrap">
                        <ul class="domain-pagination">
                            <li class="page-item">
                                <a class="page-link" href="#">Previous</a>
                            </li>
                            <li class="page-item active">
                                <a class="page-link" href="#">1</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">2</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">3</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">Next</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Footer Section Start -->
    <?= view($custom_helper->getconfig_item('active_theme') . '/views/sections/footer.php'); ?>
    <?php die; ?>
    <!-- Footer Section End -->