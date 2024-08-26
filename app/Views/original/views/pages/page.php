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
  <section id="topbarSection" class="innerHeader" style="padding-bottom: 30rem !important;">
    <!-- Navbar Start -->
    <?= view($custom_helper->getconfig_item('active_theme') . '/views/sections/header.php'); ?>
    <!-- Navbar End -->

    <!-- TopbarText Start -->
    <?= view($custom_helper->getconfig_item('active_theme') . '/views/sections/page_header.php'); ?>
    <!-- BannerImg End -->
  </section>
  <div class="row">
    <div class="col-md-12">
      <?php $helper->blocks('full_width_top', $helper->get_slug()); ?>
    </div>
  </div>
  <!-- Topbar End -->
  <!-- Sidebar -->
  <?php if ($content->sidebar_left == 1) { ?>
    <aside class="col-sm-3 sidebar_left">
      <?php $helper->blocks('sidebar_left', $helper->get_slug()); ?>
    </aside>
  <?php } ?>
  <!-- /Sidebar -->

  <!-- main content -->
  <section class="<?php
                  if ($content->sidebar_right == 1 && $content->sidebar_left == 1) {
                    echo 'col-md-6';
                  } else if ($content->sidebar_right == 1 || $content->sidebar_left == 1) {
                    echo 'col-md-9';
                  } else {
                    echo 'col-md-12 0';
                  }
                  ?>">

    <?php $helper->blocks('content_top', $helper->get_slug()); ?>

    <?= $content->body; ?>

    <?php

    if (isset($content->video) && !empty($content->video)) {
      $video = explode('=', $content->video);
      if (isset($video[1])) { ?>
        <div class="responsive-youtube">
          <iframe width="916" height="515" src="https://www.youtube.com/embed/<?= $video[1] ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>
    <?php }
    } ?>

    <div class="inner">
      <?php $helper->blocks('content_bottom', $helper->get_slug()); ?>
    </div>

  </section>
  <!-- /main -->

  <!-- Sidebar -->
  <?php if ($content->sidebar_right == 1) { ?>
    <aside class="col-sm-3 sidebar_right">
      <?php $helper->blocks('sidebar_right', $helper->get_slug()); ?>
    </aside>
  <?php } ?>
  <!-- /Sidebar -->

  <!-- Full width -->
  <section class="white-wrapper">
    <div class="row">
      <div class="col-md-12">
        <?php $helper->blocks('full_width_content_bottom', $helper->get_slug()); ?>
      </div>
    </div>
  </section>



  <!-- Normal width -->
  <section class="whitesmoke-wrapper">
    <div class="container inner">
      <div class="row">
        <div class="col-md-12">
          <?php $helper->blocks('page_bottom', $helper->get_slug()); ?>
        </div>
      </div>
    </div>
  </section>


  <!-- Normal width -->
  <section class="white-wrapper">
    <div class="container inner">
      <div class="row">
        <div class="col-md-12">
          <?php $helper->blocks('footer_top', $helper->get_slug()); ?>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer Section Start -->
  <?= view($custom_helper->getconfig_item('active_theme') . '/views/sections/footer.php'); ?>
  <?php die; ?>
  <!-- Footer Section End -->