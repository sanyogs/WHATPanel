<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */ //if (config_item('timezone')) { date_default_timezone_set(config_item('timezone')); }

use App\ThirdParty\MX\Modules;
use App\Models\User;
use App\Helpers\AuthHelper;
use App\Helpers\custom_name_helper;
use App\Modules as app_modules;
use App\Modules\sidebar\controllers\Sidebar;

$custom_name_helper = new custom_name_helper();

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Hosting Bill | Madpopo</title>
    
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css' rel="stylesheet" />
    <link rel="stylesheet" href="<?= site_url('assets/css/main.css'); ?>" />
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css" />
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" />
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

	<?php
	$family = 'Lato';
	$font = $custom_name_helper->getconfig_item('system_font');
	switch ($font) {
		case "open_sans": $family="Open Sans";  echo "<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=latin,latin-ext,greek-ext,cyrillic-ext' rel='stylesheet' type='text/css'>"; break;
		case "open_sans_condensed": $family="Open Sans Condensed";  echo "<link href='https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300,700&subset=latin,greek-ext,cyrillic-ext,latin-ext' rel='stylesheet' type='text/css'>"; break;
		case "roboto": $family="Roboto";  echo "<link href='https://fonts.googleapis.com/css?family=Roboto:400,300,500,700&subset=latin,greek-ext,cyrillic-ext,latin-ext' rel='stylesheet' type='text/css'>"; break;
		case "roboto_condensed": $family="Roboto Condensed";  echo "<link href='https://fonts.googleapis.com/css?family=Roboto+Condensed:400,300,700&subset=latin,greek-ext,cyrillic-ext,latin-ext' rel='stylesheet' type='text/css'>"; break;
		case "ubuntu": $family="Ubuntu";  echo "<link href='https://fonts.googleapis.com/css?family=Ubuntu:400,300,500,700&subset=latin,greek-ext,cyrillic-ext,latin-ext' rel='stylesheet' type='text/css'>"; break;
		case "lato": $family="Lato";  echo "<link href='https://fonts.googleapis.com/css?family=Lato:100,300,400,700&subset=latin,latin-ext' rel='stylesheet' type='text/css'>"; break;
		case "oxygen": $family="Oxygen";  echo "<link href='https://fonts.googleapis.com/css?family=Oxygen:400,300,700&subset=latin,latin-ext' rel='stylesheet' type='text/css'>"; break;
		case "pt_sans": $family="PT Sans";  echo "<link href='https://fonts.googleapis.com/css?family=PT+Sans:400,700&subset=latin,cyrillic-ext,latin-ext' rel='stylesheet' type='text/css'>"; break;
		case "source_sans": $family="Source Sans Pro";  echo "<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700&subset=latin,cyrillic-ext,latin-ext' rel='stylesheet' type='text/css'>"; break;
	}
	?>

	<style type="text/css">
		body { font-family: '<?=$family?>'; }
		.datepicker{ z-index:99999 !important; }
 
	</style>
</head>
  <body>
    <!-- Topbar Start -->
    <section id="topbarSection">
      <?=view($custom_name_helper->getconfig_item('active_theme') . '/' .'views/sections/header.php'); ?>
     
    <?= $this->renderSection('content') ?>
    <!-- Footer Section Start -->
    <?=view($custom_name_helper->getconfig_item('active_theme') . '/' .'views/sections/footer.php'); ?>
    <!-- Footer Section End -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
    <script src="<?= base_url('assets/js/main.js'); ?>"></script>
	  <script>
      function showHideDomainBox() {
        var x = document.getElementById("mydropdown");
        if (x.style.display === "none") {
          x.style.display = "block";
        } else {
          x.style.display = "none";
        }
      }
    </script>
  </body>
</html>
