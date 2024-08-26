<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */
use App\Helpers\whatpanel_helper;
use App\Modules\Layouts\Libraries\Template;

$hb_helpers = new whatpanel_helper();
$template = new Template()
?>
<?php if($hb_helpers->get_slug() != 'contact' && $hb_helpers->get_slug() != 'knowledge' && $hb_helpers->get_slug() != 'issues' && $hb_helpers->get_slug() != 'features') { ?>
<section id="bannerSection">
	<div class="bannerWrapper">
		<div class="col-xl-12 col-lg-12 col-md-12">
			<div class="bannerContentWrapper bannerText">
				<h1><?php echo $template->page; ?></h2></h1>
			<p>
				<?php echo $template->breadcrumbs; ?>
			</p>
		</div>
	</div>
	</div>
</section>
<!-- TopBarText End -->

<!-- BannerImg start -->
<div class="bannerArrow"></div>
<div class="bannerServer">
	<img src="/assets/images/solutions/sever.png" alt="">
</div>
<!-- BannerImg End -->
<?php  } ?>