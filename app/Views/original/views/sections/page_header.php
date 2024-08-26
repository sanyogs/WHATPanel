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
  <section id="Page-title" class="page_header">
<div class="color-overlay"></div>
			<div class="container inner-img">
				<div class="row">
					<div class="Page-title">
						<div class="col-md-12 text-center">
							<div class="title-text">							 
								<h2 class="page-title"><?php echo $template->page; ?></h2>
							</div>
						</div>
						<div class="col-md-12 text-center">
							<div class="breadcrumb-trail breadcrumbs">
              					<?php echo $template->breadcrumbs; ?>
							</div>
						</div>
					</div>
				</div>
      </div>  
</section>
<?php } ?>