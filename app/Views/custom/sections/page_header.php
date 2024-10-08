<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */ if(get_slug() != 'contact' && get_slug() != 'knowledge' && get_slug() != 'issues' && get_slug() != 'features') { ?>
  <section id="Page-title" class="page_header">
<div class="color-overlay"></div>
			<div class="container inner-img">
				<div class="row">
					<div class="Page-title">
						<div class="col-md-12 text-center">
							<div class="title-text">							 
								<h2 class="page-title"><?php echo $this->template->page; ?></h2>
							</div>
						</div>
						<div class="col-md-12 text-center">
							<div class="breadcrumb-trail breadcrumbs">
              					<?php echo $this->template->breadcrumbs; ?>
							</div>
						</div>
					</div>
				</div>
      </div>  
</section>
<?php } ?>