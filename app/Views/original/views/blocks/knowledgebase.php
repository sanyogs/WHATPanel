<?php

use App\Modules\Layouts\Libraries\Template;
use App\Helpers\whatpanel_helper;
use App\Helpers\custom_name_helper;

$helper = new whatpanel_helper();
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
  <div class="row">
    <div class="col-md-12">
      <?php //$helper->blocks('full_width_top', $helper->get_slug()); 
      ?>
    </div>
  </div>
  <!-- Topbar End -->

  <!-- main content -->
  <section class="accordianSection faqpage-accordian" id="accordian-section">
    <div class="container">
      <div class="planSectionTitleWrap">
        <h2 class="sectionTitle">Hope you've find the <span>Answer</span> </h2>
        <p class="secText">
          Lorem Ipsum is simply dummy text of the printing and typesetting
          industry.
        </p>
      </div>
      <div class="col-md-10 col-lg-10 col-10 mx-auto">
        <div class="accordianContainer">
          <div class="commonAccordian">
            <div class="accordianBtn">
              <h6>Lorem Ipsum is simply dummy text of the printing.?</h6>
              <span class="accordianArrow">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="11"
                  height="20"
                  viewBox="0 0 11 20"
                  fill="none">
                  <path
                    d="M1.37793 18.1824L9.5972 9.92578L1.37793 1.6692"
                    stroke="#4A3AFF"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round" />
                </svg>
              </span>
            </div>
            <div class="accordianContent">
              <div class="accordianText">
                <p>
                  Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed
                  do eiusmod tempor incididunt ut labore et dolore magna
                  aliqua. Ut enim ad minim veniam, quis nostrud exercitation
                  ullamco laboris nisi ut aliquip ex ea commodo consequat.
                </p>
              </div>
            </div>
          </div>
          <div class="commonAccordian">
            <div class="accordianBtn">
              <h6>Lorem Ipsum is simply dummy text of the printing.?</h6>
              <span class="accordianArrow">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="11"
                  height="20"
                  viewBox="0 0 11 20"
                  fill="none">
                  <path
                    d="M1.37793 18.1824L9.5972 9.92578L1.37793 1.6692"
                    stroke="#4A3AFF"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round" />
                </svg>
              </span>
            </div>
            <div class="accordianContent">
              <div class="accordianText">
                <p>
                  Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed
                  do eiusmod tempor incididunt ut labore et dolore magna
                  aliqua. Ut enim ad minim veniam, quis nostrud exercitation
                  ullamco laboris nisi ut aliquip ex ea commodo consequat.
                </p>
              </div>
            </div>
          </div>
          <div class="commonAccordian">
            <div class="accordianBtn">
              <h6>Lorem Ipsum is simply dummy text of the printing.?</h6>
              <span class="accordianArrow">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="11"
                  height="20"
                  viewBox="0 0 11 20"
                  fill="none">
                  <path
                    d="M1.37793 18.1824L9.5972 9.92578L1.37793 1.6692"
                    stroke="#4A3AFF"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round" />
                </svg>
              </span>
            </div>
            <div class="accordianContent">
              <div class="accordianText">
                <p>
                  Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed
                  do eiusmod tempor incididunt ut labore et dolore magna
                  aliqua. Ut enim ad minim veniam, quis nostrud exercitation
                  ullamco laboris nisi ut aliquip ex ea commodo consequat.
                </p>
              </div>
            </div>
          </div>
          <div class="commonAccordian">
            <div class="accordianBtn">
              <h6>Lorem Ipsum is simply dummy text of the printing.?</h6>
              <span class="accordianArrow">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="11"
                  height="20"
                  viewBox="0 0 11 20"
                  fill="none">
                  <path
                    d="M1.37793 18.1824L9.5972 9.92578L1.37793 1.6692"
                    stroke="#4A3AFF"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round" />
                </svg>
              </span>
            </div>
            <div class="accordianContent">
              <div class="accordianText">
                <p>
                  Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed
                  do eiusmod tempor incididunt ut labore et dolore magna
                  aliqua. Ut enim ad minim veniam, quis nostrud exercitation
                  ullamco laboris nisi ut aliquip ex ea commodo consequat.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- /main -->

  <!-- Sidebar -->

  <!-- Footer Section Start -->
  <?= view($custom_helper->getconfig_item('active_theme') . '/views/sections/footer.php'); ?>
  <?php die; ?>
  <!-- Footer Section End -->