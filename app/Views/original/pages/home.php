<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

use App\Models\Item;
use App\Helpers\custom_name_helper;
$db = \Config\Database::connect();

try {
	$request = $db->table('hd_plugins')->select('config')->where('system_name', 'resellerclub')->get()->getRow()->config;
	$results = json_decode($request);

	$price_mode = $results->customerpay_mode ?? null;
}
catch(\Exception $e)
{
	//return redirect()->to('home');
}

$custom_name_helper = new custom_name_helper();
?>
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<style>
.radioBtn {
display: block; /* Ensure that each radio button and label are on a new line */
margin-bottom: 10px; /* Add some space between each radio button */
}
.custom_radio input[type="radio"] {
margin-right: 10px; /* Space between radio button and label */
}
.domainDropdownWrap {
    display: none; /* Hide the dropdown initially */
}
.domainDropdownWrap.show {
    display: block; /* Show the dropdown when needed */
}
</style>
<!-- Navbar End -->
<section id="bannerSection">
  <div class="bannerWrapper">
    <div class="col-xl-5 col-lg-12 col-md-12">
      <?//php foreach($slider as $value): ?>
        <div class="bannerContentWrapper">
          <h1><?= $slider->title ?></h1>
          <p>
            <?= $slider->description ?>
          </p>
        </div>
        <div class="banBrnWrap">
          
            <a href="<?=base_url()?><?= $slider->btn_redirect_one ?>" target="_blank" class="btn"><?= $slider->btname_one ?></a>
            <a href="<?=base_url()?><?= $slider->btn_redirect_two ?>" target="_blank" class="anchLink"><?= $slider->btname_two ?>
              <span class="rightArrowWrap"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="16" viewBox="0 0 24 16" fill="none">
                  <path d="M1.5 7C0.947715 7 0.5 7.44772 0.5 8C0.5 8.55228 0.947715 9 1.5 9L1.5 7ZM23.2071 8.7071C23.5976 8.31658 23.5976 7.68342 23.2071 7.29289L16.8431 0.928931C16.4526 0.538407 15.8195 0.538407 15.4289 0.928931C15.0384 1.31946 15.0384 1.95262 15.4289 2.34314L21.0858 8L15.4289 13.6569C15.0384 14.0474 15.0384 14.6805 15.4289 15.0711C15.8195 15.4616 16.4526 15.4616 16.8431 15.0711L23.2071 8.7071ZM1.5 9L22.5 9L22.5 7L1.5 7L1.5 9Z" fill="white" />
                </svg></span></a>
        </div>
      <?//php endforeach; ?>
    </div>
  </div>
</section>
<section id="stickySearchBox">
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

				<div class="domainDropdownWrap" id="mydropdown">
					<ul>
						<li>
							<span class="radioBtn custom_radio">
							<?php
								try {
									if ($price_mode == 'API') {
										$mode = 'api';
										$builder = $db->table('hd_items_saved');
										$builder->select('hd_items_saved.*, hd_item_pricing.*, hd_categories.id AS category_id, hd_categories.cat_name AS category_name');
										$builder->join('hd_item_pricing', 'hd_item_pricing.item_id = hd_items_saved.item_id', 'left');
										$builder->join('hd_categories', 'hd_categories.id = hd_item_pricing.category', 'left');
										$builder->where('hd_items_saved.item_type', $mode);

										$query = $builder->get();
										$services = $query->getResult();

										foreach ($services as $service) {
											if ($service->category_name === "Domains") {
												?>
												<div>
													<input type="radio" id="<?php echo htmlspecialchars($service->item_name); ?>" name="ext" value="<?php echo htmlspecialchars($service->item_name); ?>" data-value="<?php echo htmlspecialchars($service->item_name); ?>" <?php echo ($domain->item_name === '.in') ? 'checked' : ''; ?> />
													<label for="<?php echo htmlspecialchars($service->item_name); ?>"><?php echo htmlspecialchars($service->item_name); ?></label>
												</div>
												<?php
											}
										}
									} else {
										$mode = 'manually';
										$services = $db->table('hd_domains')->get()->getResult();

										foreach ($services as $service) {
											?>
											<div>
												<input type="radio" id="<?php echo htmlspecialchars($service->ext_name); ?>" name="ext" value="<?php echo htmlspecialchars($service->ext_name); ?>" data-value="<?php echo htmlspecialchars($service->ext_name); ?>" <?php echo ($service->ext_name === '.in') ? 'checked' : ''; ?> />
												<label for="<?php echo htmlspecialchars($service->ext_name); ?>"><?php echo htmlspecialchars($service->ext_name); ?></label>
											</div>
											<?php
										}
									}
								} catch (\Exception $e) {
									// Handle the exception and display a generic error message
									// Optionally, log the error message for debugging
									error_log($e->getMessage());
									?>
									<div>
										<input type="radio" id="error" name="ext" value="" data-value="" disabled />
										<label for="error">An error occurred, please try again later.</label>
									</div>
									<?php
								}
								?>

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
        <img id="checking" src="<?= base_url('images/checking.gif') ?>" style="display: none;" />
      </div>
      <div id="response"></div>
      <div class="domainExtenstionWrap">
        <ul>
           <?php 
try {
			// Connect to the database
			$db = \Config\Database::connect();

			// Fetch configuration for 'resellerclub'
			$request = $db->table('hd_plugins')
						  ->select('config')
						  ->where('system_name', 'resellerclub')
						  ->get()
						  ->getRow()
						  ->config;

				// Decode JSON configuration
				$results = json_decode($request);

				// Check if customerpay_mode is set
				if (isset($results->customerpay_mode)) {
					$price_mode = $results->customerpay_mode;
					$custom_name_helper = new custom_name_helper();

					if ($price_mode == 'API') {
						$mode = 'api';

					// Query items from the database
					$builder = $db->table('hd_items_saved');
					$builder->select('hd_items_saved.*, hd_item_pricing.*, hd_categories.id AS category_id, hd_categories.cat_name AS category_name');
					$builder->join('hd_item_pricing', 'hd_item_pricing.item_id = hd_items_saved.item_id', 'left');
					$builder->join('hd_categories', 'hd_categories.id = hd_item_pricing.category', 'left');
					$builder->where('hd_items_saved.item_type', $mode);

					$query = $builder->get();
					$servicesss = $query->getResult();

					foreach ($servicesss as $service) {
						if ($service->category_name == "Domains") {
							?>
							<li>
								<span class="extnsText"><?= htmlspecialchars($service->item_name) ?></span>
								<span class="priceExt"><?= htmlspecialchars($custom_name_helper->getconfig_item('default_currency_symbol')) . htmlspecialchars($service->registration) ?></span>
							</li>
							<?php
						}
					}
				} else {
            $mode = 'manually';

            // Query domains from the database
			$servicesss = $db->table('hd_domains')->get()->getResult();

			foreach ($servicesss as $service) {
			?>
			<li>
				<span class="extnsText"><?= htmlspecialchars($service->ext_name) ?></span>
				<span class="priceExt"><?= htmlspecialchars($custom_name_helper->getconfig_item('default_currency_symbol')) . htmlspecialchars($service->registration_1) ?></span>
			</li>
			<?php
				}
		}
	}
	} catch (\Exception $e) {
	// Handle the exception and display a generic error message
			?>
			<li>
				<span class="extnsText">Error</span>
				<span class="priceExt">0.00</span>
			</li>
			<?php
	}
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
</section>
<!-- Topbar End -->
<!-- RohanSectionStarts -->
<section id="bestChoiceSection">
  <div class="container">
    <div class="sectionTitleWrap">
      <h2 class="secTitle">
        Why Our <span>WordPress Hosting</span> Is the Best Choice
      </h2>
      <p class="secPara">
        Lorem Ipsum is simply dummy text of the printing and typesetting
        industry.
      </p>
    </div>
    <div class="choiceRow">
      <div class="col-lg-4 col-md-6">
        <div class="bestChoiceCardWrap">
          <span>
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
              <g clip-path="url(#clip0_1179_11953)">
                <path d="M9 6C8.40666 6 7.82664 6.17595 7.33329 6.50559C6.83994 6.83524 6.45543 7.30377 6.22836 7.85195C6.0013 8.40013 5.94189 9.00333 6.05765 9.58527C6.1734 10.1672 6.45912 10.7018 6.87868 11.1213C7.29824 11.5409 7.83279 11.8266 8.41473 11.9424C8.99667 12.0581 9.59987 11.9987 10.1481 11.7716C10.6962 11.5446 11.1648 11.1601 11.4944 10.6667C11.8241 10.1734 12 9.59334 12 9C12 8.20435 11.6839 7.44129 11.1213 6.87868C10.5587 6.31607 9.79565 6 9 6ZM9 10.5C8.70333 10.5 8.41332 10.412 8.16665 10.2472C7.91997 10.0824 7.72771 9.84811 7.61418 9.57403C7.50065 9.29994 7.47095 8.99834 7.52882 8.70736C7.5867 8.41639 7.72956 8.14912 7.93934 7.93934C8.14912 7.72956 8.41639 7.5867 8.70737 7.52882C8.99834 7.47094 9.29994 7.50065 9.57403 7.61418C9.84812 7.72771 10.0824 7.91997 10.2472 8.16665C10.412 8.41332 10.5 8.70333 10.5 9C10.5 9.39783 10.342 9.77936 10.0607 10.0607C9.77936 10.342 9.39783 10.5 9 10.5Z" fill="#3F38FC" />
                <path d="M15.9705 10.425L15.6375 10.233C15.7874 9.41733 15.7874 8.58117 15.6375 7.7655L15.9705 7.5735C16.2266 7.42576 16.451 7.22903 16.6311 6.99455C16.8111 6.76006 16.9432 6.4924 17.0198 6.20686C17.0964 5.92132 17.1161 5.62349 17.0776 5.33037C17.0391 5.03725 16.9432 4.75458 16.7955 4.4985C16.6477 4.24242 16.451 4.01795 16.2165 3.8379C15.982 3.65785 15.7144 3.52575 15.4288 3.44915C15.1433 3.37254 14.8455 3.35292 14.5523 3.39142C14.2592 3.42991 13.9766 3.52576 13.7205 3.6735L13.3867 3.86625C12.7564 3.32769 12.032 2.91019 11.25 2.63475V2.25C11.25 1.65326 11.0129 1.08097 10.591 0.65901C10.169 0.237053 9.59671 0 8.99997 0C8.40323 0 7.83094 0.237053 7.40898 0.65901C6.98702 1.08097 6.74997 1.65326 6.74997 2.25V2.63475C5.96801 2.91118 5.24386 3.3297 4.61397 3.86925L4.27872 3.675C3.76155 3.37663 3.14703 3.29593 2.57036 3.45065C1.99368 3.60536 1.50209 3.98283 1.20372 4.5C0.905353 5.01717 0.82465 5.63169 0.979368 6.20836C1.13409 6.78504 1.51155 7.27663 2.02872 7.575L2.36172 7.767C2.2118 8.58267 2.2118 9.41883 2.36172 10.2345L2.02872 10.4265C1.51155 10.7249 1.13409 11.2165 0.979368 11.7931C0.82465 12.3698 0.905353 12.9843 1.20372 13.5015C1.50209 14.0187 1.99368 14.3961 2.57036 14.5509C3.14703 14.7056 3.76155 14.6249 4.27872 14.3265L4.61247 14.1337C5.24304 14.6724 5.96771 15.0899 6.74997 15.3652V15.75C6.74997 16.3467 6.98702 16.919 7.40898 17.341C7.83094 17.7629 8.40323 18 8.99997 18C9.59671 18 10.169 17.7629 10.591 17.341C11.0129 16.919 11.25 16.3467 11.25 15.75V15.3652C12.0319 15.0888 12.7561 14.6703 13.386 14.1307L13.7212 14.3243C14.2384 14.6226 14.8529 14.7033 15.4296 14.5486C16.0063 14.3939 16.4979 14.0164 16.7962 13.4993C17.0946 12.9821 17.1753 12.3676 17.0206 11.7909C16.8659 11.2142 16.4884 10.7226 15.9712 10.4242L15.9705 10.425ZM14.0595 7.593C14.3135 8.5133 14.3135 9.4852 14.0595 10.4055C14.0151 10.5657 14.0252 10.7361 14.0882 10.8899C14.1512 11.0437 14.2635 11.1722 14.4075 11.2552L15.2205 11.7248C15.3928 11.8242 15.5186 11.9881 15.5702 12.1803C15.6217 12.3725 15.5948 12.5773 15.4953 12.7496C15.3959 12.922 15.232 13.0478 15.0398 13.0993C14.8476 13.1509 14.6428 13.124 14.4705 13.0245L13.656 12.5535C13.5119 12.4701 13.3441 12.4369 13.1792 12.4593C13.0142 12.4817 12.8613 12.5584 12.7447 12.6772C12.0772 13.3587 11.2362 13.845 10.3125 14.0835C10.1512 14.125 10.0084 14.2189 9.90642 14.3504C9.80444 14.482 9.74914 14.6438 9.74922 14.8102V15.75C9.74922 15.9489 9.6702 16.1397 9.52955 16.2803C9.3889 16.421 9.19813 16.5 8.99922 16.5C8.80031 16.5 8.60954 16.421 8.46889 16.2803C8.32824 16.1397 8.24922 15.9489 8.24922 15.75V14.811C8.2493 14.6445 8.194 14.4828 8.09202 14.3512C7.99005 14.2196 7.8472 14.1257 7.68597 14.0842C6.76223 13.8448 5.92147 13.3575 5.25447 12.675C5.13786 12.5561 4.98502 12.4795 4.82004 12.4571C4.65505 12.4347 4.4873 12.4678 4.34322 12.5513L3.53022 13.0215C3.44489 13.0715 3.35051 13.1042 3.25252 13.1176C3.15452 13.131 3.05484 13.1249 2.95921 13.0996C2.86359 13.0743 2.77391 13.0303 2.69534 12.9703C2.61677 12.9102 2.55085 12.8352 2.5014 12.7495C2.45194 12.6638 2.41992 12.5692 2.40717 12.4712C2.39442 12.3731 2.4012 12.2734 2.42711 12.178C2.45303 12.0825 2.49757 11.9931 2.55817 11.915C2.61877 11.8368 2.69424 11.7714 2.78022 11.7225L3.59322 11.253C3.73718 11.1699 3.84948 11.0414 3.91247 10.8876C3.97545 10.7338 3.98557 10.5634 3.94122 10.4032C3.68724 9.48295 3.68724 8.51105 3.94122 7.59075C3.98477 7.43091 3.97417 7.26115 3.91108 7.10797C3.84799 6.95479 3.73596 6.8268 3.59247 6.744L2.77947 6.2745C2.60711 6.17504 2.48132 6.01119 2.42978 5.81899C2.37823 5.62679 2.40514 5.42198 2.5046 5.24962C2.60405 5.07727 2.7679 4.95148 2.96011 4.89993C3.15231 4.84838 3.35711 4.87529 3.52947 4.97475L4.34397 5.44575C4.48766 5.52939 4.65506 5.56291 4.81987 5.54105C4.98468 5.51919 5.13755 5.4432 5.25447 5.325C5.92204 4.64351 6.76304 4.15727 7.68672 3.91875C7.84844 3.87717 7.99167 3.78281 8.0937 3.65063C8.19572 3.51844 8.25072 3.35598 8.24997 3.189V2.25C8.24997 2.05109 8.32899 1.86032 8.46964 1.71967C8.61029 1.57902 8.80106 1.5 8.99997 1.5C9.19888 1.5 9.38965 1.57902 9.5303 1.71967C9.67095 1.86032 9.74997 2.05109 9.74997 2.25V3.189C9.74989 3.35547 9.80519 3.51723 9.90717 3.64881C10.0091 3.78039 10.152 3.8743 10.3132 3.91575C11.2372 4.15511 12.0783 4.64241 12.7455 5.325C12.8621 5.44385 13.0149 5.52052 13.1799 5.54292C13.3449 5.56533 13.5126 5.5322 13.6567 5.44875L14.4697 4.9785C14.555 4.92848 14.6494 4.89583 14.7474 4.88243C14.8454 4.86903 14.9451 4.87514 15.0407 4.90043C15.1364 4.92571 15.226 4.96965 15.3046 5.02974C15.3832 5.08982 15.4491 5.16485 15.4985 5.2505C15.548 5.33616 15.58 5.43076 15.5928 5.52884C15.6055 5.62693 15.5987 5.72656 15.5728 5.82202C15.5469 5.91747 15.5024 6.00686 15.4418 6.08503C15.3812 6.1632 15.3057 6.22861 15.2197 6.2775L14.4067 6.747C14.2635 6.83003 14.1518 6.95811 14.089 7.11127C14.0262 7.26443 14.0158 7.43407 14.0595 7.59375V7.593Z" fill="#3F38FC" />
              </g>
              <defs>
                <clipPath id="clip0_1179_11953">
                  <rect width="18" height="18" fill="white" />
                </clipPath>
              </defs>
            </svg>
          </span>
          <div class="cardTitle">
            <h3>Instant Setup</h3>
          </div>
          <div class="cardText">
            <p>
              As soon as you make a successful payment your web hosting and
              domain names will be activated immediately. We do not charge
              extra fees for setting up your account.
            </p>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-md-6">
        <div class="bestChoiceCardWrap">
          <span>
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
              <g clip-path="url(#clip0_1179_11964)">
                <path d="M17.9997 8.62503C17.9979 8.11938 17.8819 7.62066 17.6603 7.16612C17.4388 6.71158 17.1174 6.31296 16.7202 6.00003C17.2697 5.56684 17.6703 4.97294 17.8661 4.30116C18.0619 3.62938 18.0431 2.91324 17.8124 2.25265C17.5817 1.59205 17.1506 1.01993 16.5791 0.616119C16.0077 0.212305 15.3244 -0.0030727 14.6247 3.31238e-05H3.37469C2.67497 -0.0030727 1.99173 0.212305 1.42028 0.616119C0.84883 1.01993 0.417675 1.59205 0.18696 2.25265C-0.0437538 2.91324 -0.0625205 3.62938 0.133278 4.30116C0.329077 4.97294 0.729676 5.56684 1.27919 6.00003C0.880599 6.31171 0.558221 6.71007 0.336506 7.16489C0.114792 7.61971 -0.00043537 8.11905 -0.00043537 8.62503C-0.00043537 9.13102 0.114792 9.63036 0.336506 10.0852C0.558221 10.54 0.880599 10.9384 1.27919 11.25C0.729676 11.6832 0.329077 12.2771 0.133278 12.9489C-0.0625205 13.6207 -0.0437538 14.3368 0.18696 14.9974C0.417675 15.658 0.84883 16.2301 1.42028 16.6339C1.99173 17.0378 2.67497 17.2531 3.37469 17.25H14.6247C15.3244 17.2531 16.0077 17.0378 16.5791 16.6339C17.1506 16.2301 17.5817 15.658 17.8124 14.9974C18.0431 14.3368 18.0619 13.6207 17.8661 12.9489C17.6703 12.2771 17.2697 11.6832 16.7202 11.25C17.1174 10.9371 17.4388 10.5385 17.6603 10.0839C17.8819 9.62941 17.9979 9.13069 17.9997 8.62503ZM1.49969 3.37503C1.49969 2.87775 1.69723 2.40084 2.04887 2.04921C2.4005 1.69758 2.87741 1.50003 3.37469 1.50003H3.74969V2.25003C3.74969 2.44895 3.82871 2.63971 3.96936 2.78036C4.11001 2.92102 4.30078 3.00003 4.49969 3.00003C4.6986 3.00003 4.88937 2.92102 5.03002 2.78036C5.17067 2.63971 5.24969 2.44895 5.24969 2.25003V1.50003H6.74969V2.25003C6.74969 2.44895 6.82871 2.63971 6.96936 2.78036C7.11001 2.92102 7.30078 3.00003 7.49969 3.00003C7.6986 3.00003 7.88937 2.92102 8.03002 2.78036C8.17067 2.63971 8.24969 2.44895 8.24969 2.25003V1.50003H14.6247C15.122 1.50003 15.5989 1.69758 15.9505 2.04921C16.3021 2.40084 16.4997 2.87775 16.4997 3.37503C16.4997 3.87231 16.3021 4.34923 15.9505 4.70086C15.5989 5.05249 15.122 5.25003 14.6247 5.25003H3.37469C2.87741 5.25003 2.4005 5.05249 2.04887 4.70086C1.69723 4.34923 1.49969 3.87231 1.49969 3.37503ZM16.4997 13.875C16.4997 14.3723 16.3021 14.8492 15.9505 15.2009C15.5989 15.5525 15.122 15.75 14.6247 15.75H3.37469C2.87741 15.75 2.4005 15.5525 2.04887 15.2009C1.69723 14.8492 1.49969 14.3723 1.49969 13.875C1.49969 13.3778 1.69723 12.9008 2.04887 12.5492C2.4005 12.1976 2.87741 12 3.37469 12H3.74969V12.75C3.74969 12.9489 3.82871 13.1397 3.96936 13.2804C4.11001 13.421 4.30078 13.5 4.49969 13.5C4.6986 13.5 4.88937 13.421 5.03002 13.2804C5.17067 13.1397 5.24969 12.9489 5.24969 12.75V12H6.74969V12.75C6.74969 12.9489 6.82871 13.1397 6.96936 13.2804C7.11001 13.421 7.30078 13.5 7.49969 13.5C7.6986 13.5 7.88937 13.421 8.03002 13.2804C8.17067 13.1397 8.24969 12.9489 8.24969 12.75V12H14.6247C15.122 12 15.5989 12.1976 15.9505 12.5492C16.3021 12.9008 16.4997 13.3778 16.4997 13.875ZM3.37469 10.5C2.87741 10.5 2.4005 10.3025 2.04887 9.95086C1.69723 9.59923 1.49969 9.12231 1.49969 8.62503C1.49969 8.12775 1.69723 7.65084 2.04887 7.29921C2.4005 6.94758 2.87741 6.75003 3.37469 6.75003H3.74969V7.50003C3.74969 7.69895 3.82871 7.88971 3.96936 8.03036C4.11001 8.17102 4.30078 8.25003 4.49969 8.25003C4.6986 8.25003 4.88937 8.17102 5.03002 8.03036C5.17067 7.88971 5.24969 7.69895 5.24969 7.50003V6.75003H6.74969V7.50003C6.74969 7.69895 6.82871 7.88971 6.96936 8.03036C7.11001 8.17102 7.30078 8.25003 7.49969 8.25003C7.6986 8.25003 7.88937 8.17102 8.03002 8.03036C8.17067 7.88971 8.24969 7.69895 8.24969 7.50003V6.75003H14.6247C15.122 6.75003 15.5989 6.94758 15.9505 7.29921C16.3021 7.65084 16.4997 8.12775 16.4997 8.62503C16.4997 9.12231 16.3021 9.59923 15.9505 9.95086C15.5989 10.3025 15.122 10.5 14.6247 10.5H3.37469Z" fill="#3F38FC" />
              </g>
              <defs>
                <clipPath id="clip0_1179_11964">
                  <rect width="18" height="18" fill="white" />
                </clipPath>
              </defs>
            </svg>
          </span>
          <div class="cardTitle">
            <h3>Constant Backup</h3>
          </div>
          <div class="cardText">
            <p>
              Your hosting account is backed up daily going 2 months back at
              no extra cost. We use dedicated backup servers, providing fast
              & easy individual file rollback abilities.
            </p>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-md-6">
        <div class="bestChoiceCardWrap">
          <span>
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
              <g clip-path="url(#clip0_1179_11804)">
                <path d="M18 3.75001C18 2.09551 16.6545 0.750007 15 0.750007C13.3455 0.750007 12 2.09551 12 3.75001C12 5.14501 12.9578 6.32101 14.25 6.65476V11.25C14.25 12.4905 13.2405 13.5 12 13.5H9.47325L11.1255 11.8478C11.4187 11.5545 11.4187 11.0805 11.1255 10.7873C10.8322 10.494 10.3582 10.494 10.065 10.7873L8.15925 12.693C7.7475 13.104 7.5 13.5 7.5 14.25C7.5 15 7.72275 15.4388 8.15925 15.8753L10.065 17.781C10.2113 17.9273 10.4032 18.0008 10.5953 18.0008C10.7872 18.0008 10.9792 17.9273 11.1255 17.781C11.4187 17.4878 11.4187 17.0138 11.1255 16.7205L9.40575 15.0008H12C14.0677 15.0008 15.75 13.3185 15.75 11.2508V6.65551C17.0422 6.32176 18 5.14501 18 3.75001ZM15 5.25001C14.1727 5.25001 13.5 4.57726 13.5 3.75001C13.5 2.92276 14.1727 2.25001 15 2.25001C15.8273 2.25001 16.5 2.92276 16.5 3.75001C16.5 4.57726 15.8273 5.25001 15 5.25001ZM9.903 5.34151C10.3282 4.91626 10.5623 4.35151 10.5623 3.75001C10.5623 3.14851 10.3282 2.58376 9.903 2.15926L7.99725 0.253507C7.704 -0.0397432 7.23 -0.0397432 6.93675 0.253507C6.6435 0.546757 6.6435 1.02076 6.93675 1.31401L8.622 3.00001H6C3.93225 3.00001 2.25 4.68226 2.25 6.75001V11.3453C0.95775 11.679 0 12.855 0 14.25C0 15.9045 1.3455 17.25 3 17.25C4.6545 17.25 6 15.9045 6 14.25C6 12.855 5.04225 11.679 3.75 11.3453V6.75001C3.75 5.50951 4.7595 4.50001 6 4.50001H8.62275L6.93675 6.18601C6.6435 6.47926 6.6435 6.95326 6.93675 7.24651C7.083 7.39276 7.275 7.46626 7.467 7.46626C7.659 7.46626 7.851 7.39276 7.99725 7.24651L9.903 5.34151ZM4.5 14.25C4.5 15.0773 3.82725 15.75 3 15.75C2.17275 15.75 1.5 15.0773 1.5 14.25C1.5 13.4228 2.17275 12.75 3 12.75C3.82725 12.75 4.5 13.4228 4.5 14.25Z" fill="#3F38FC" />
              </g>
              <defs>
                <clipPath id="clip0_1179_11804">
                  <rect width="18" height="18" fill="white" />
                </clipPath>
              </defs>
            </svg>
          </span>
          <div class="cardTitle">
            <h3>Git / SVN Support</h3>
          </div>
          <div class="cardText">
            <p>
              Web Developers love using version control systems. All our
              hosting accounts can use GIT & SVN command line tools on our
              servers. Simply request SSH access to get started.
            </p>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-md-6">
        <div class="bestChoiceCardWrap">
          <span>
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
              <g clip-path="url(#clip0_1179_12753)">
                <path d="M11.1233 12.75C10.9313 12.75 10.7392 12.6765 10.593 12.5303C10.2997 12.237 10.2997 11.763 10.593 11.4698L12.5303 9.5325C12.6713 9.39075 12.75 9.2025 12.75 9.0015C12.75 8.8005 12.672 8.613 12.5303 8.47125L10.593 6.53325C10.2997 6.24 10.2997 5.76525 10.593 5.47275C10.8862 5.1795 11.3603 5.1795 11.6535 5.47275L13.5907 7.41C14.0153 7.8345 14.25 8.4 14.25 9.00075C14.25 9.6015 14.016 10.167 13.5907 10.5922L11.6535 12.5295C11.5072 12.6757 11.3153 12.75 11.1233 12.75ZM7.407 12.5265C7.70025 12.2332 7.70025 11.7592 7.407 11.466L5.46975 9.52875C5.328 9.387 5.25 9.19875 5.25 8.99775C5.25 8.79675 5.328 8.60925 5.46975 8.4675L7.407 6.53025C7.70025 6.237 7.70025 5.763 7.407 5.46975C7.11375 5.1765 6.63975 5.1765 6.3465 5.46975L4.40925 7.407C3.984 7.83225 3.75 8.397 3.75 8.9985C3.75 9.6 3.984 10.1648 4.40925 10.59L6.3465 12.5272C6.49275 12.6735 6.68475 12.747 6.87675 12.747C7.06875 12.747 7.26075 12.6727 7.407 12.5265ZM18 14.25V3.75C18 1.68225 16.3177 0 14.25 0H3.75C1.68225 0 0 1.68225 0 3.75V14.25C0 16.3177 1.68225 18 3.75 18H14.25C16.3177 18 18 16.3177 18 14.25ZM14.25 1.5C15.4905 1.5 16.5 2.5095 16.5 3.75V14.25C16.5 15.4905 15.4905 16.5 14.25 16.5H3.75C2.5095 16.5 1.5 15.4905 1.5 14.25V3.75C1.5 2.5095 2.5095 1.5 3.75 1.5H14.25Z" fill="#3F38FC" />
              </g>
              <defs>
                <clipPath id="clip0_1179_12753">
                  <rect width="18" height="18" fill="white" />
                </clipPath>
              </defs>
            </svg>
          </span>
          <div class="cardTitle">
            <h3>280+ Install Scripts</h3>
          </div>
          <div class="cardText">
            <p>
              All our hosting accounts allow you to install popular software
              such as Wordpress, Drupal, Joolma and Magento in one easy
              step. Upgrading your software is just as easy!
            </p>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-md-6">
        <div class="bestChoiceCardWrap">
          <span>
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
              <path d="M14.25 1.5H3.75C1.68225 1.5 0 3.18225 0 5.25V12.75C0 14.8177 1.68225 16.5 3.75 16.5H14.25C16.3177 16.5 18 14.8177 18 12.75V5.25C18 3.18225 16.3177 1.5 14.25 1.5ZM1.5 7.5H4.5V10.5H1.5V7.5ZM6 7.5H16.5V10.5H6V7.5ZM16.5 5.25V6H6V3H14.25C15.4905 3 16.5 4.0095 16.5 5.25ZM3.75 3H4.5V6H1.5V5.25C1.5 4.0095 2.5095 3 3.75 3ZM1.5 12.75V12H4.5V15H3.75C2.5095 15 1.5 13.9905 1.5 12.75ZM14.25 15H6V12H16.5V12.75C16.5 13.9905 15.4905 15 14.25 15Z" fill="#3F38FC" />
            </svg>
          </span>
          <div class="cardTitle">
            <h3>cPanel Included</h3>
          </div>
          <div class="cardText">
            <p>
              All hosting accounts come with the latest version of cPanel.
              This makes life easy for you to do routine tasks such as
              setting up email addresses and managing MySQL databases.
            </p>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-md-6">
        <div class="bestChoiceCardWrap">
          <span>
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
              <g clip-path="url(#clip0_1179_11808)">
                <path d="M1.42425 6.38025H4.0935C4.87725 6.387 5.445 6.61275 5.7975 7.0575C6.14925 7.50225 6.2655 8.10975 6.14625 8.88C6.09975 9.23175 5.997 9.5775 5.83725 9.91575C5.68425 10.2547 5.472 10.56 5.19975 10.8322C4.8675 11.1772 4.51275 11.397 4.134 11.49C3.75525 11.583 3.36375 11.6295 2.95875 11.6295H1.76325L1.3845 13.5225H0L1.42425 6.38025ZM2.58975 7.51575L1.992 10.5037C2.03175 10.5105 2.0715 10.5135 2.11125 10.5135C2.15775 10.5135 2.20425 10.5135 2.25075 10.5135C2.88825 10.5202 3.41925 10.4572 3.8445 10.3245C4.26975 10.185 4.55475 9.7005 4.701 8.87025C4.82025 8.17275 4.701 7.7715 4.3425 7.665C3.99075 7.5585 3.549 7.509 3.018 7.51575C2.9385 7.5225 2.862 7.5255 2.78925 7.5255C2.72325 7.5255 2.6535 7.5255 2.58 7.5255L2.58975 7.51575Z" fill="#3F38FC" />
                <path d="M7.72306 4.47748H9.09781L8.70931 6.38023H9.94456C10.6218 6.39373 11.1266 6.53323 11.4588 6.79873C11.7978 7.06423 11.8968 7.56898 11.7573 8.31298L11.0898 11.6302H9.69556L10.3331 8.46298C10.3991 8.13073 10.3796 7.89523 10.2731 7.75573C10.1666 7.61623 9.93781 7.54648 9.58606 7.54648L8.48056 7.53673L7.66381 11.6302H6.28906L7.72306 4.47748Z" fill="#3F38FC" />
                <path d="M13.2326 6.38019H15.9019C16.6856 6.38694 17.2534 6.61269 17.6051 7.05744C17.9569 7.50219 18.0731 8.10969 17.9539 8.87994C17.9074 9.23169 17.8046 9.57744 17.6449 9.91569C17.4919 10.2547 17.2796 10.5599 17.0074 10.8322C16.6751 11.1772 16.3204 11.3969 15.9416 11.4899C15.5629 11.5829 15.1714 11.6294 14.7664 11.6294H13.5709L13.1921 13.5217H11.8076L13.2326 6.38019ZM14.3981 7.51569L13.8004 10.5037C13.8401 10.5104 13.8799 10.5134 13.9196 10.5134C13.9661 10.5134 14.0126 10.5134 14.0591 10.5134C14.6966 10.5202 15.2276 10.4572 15.6529 10.3244C16.0781 10.1849 16.3631 9.70044 16.5094 8.87019C16.6286 8.17269 16.5094 7.77144 16.1509 7.66494C15.7991 7.55844 15.3574 7.50894 14.8264 7.51569C14.7469 7.52244 14.6704 7.52544 14.5976 7.52544C14.5316 7.52544 14.4619 7.52544 14.3884 7.52544L14.3981 7.51569Z" fill="#3F38FC" />
              </g>
              <defs>
                <clipPath id="clip0_1179_11808">
                  <rect width="18" height="18" fill="white" />
                </clipPath>
              </defs>
            </svg>
          </span>
          <div class="cardTitle">
            <h3>Latest PHP & MySQL</h3>
          </div>
          <div class="cardText">
            <p>
              Our network runs the latest stable and secure versions of PHP
              & MySQL. We also implement strict security and firewall rules
              to protect your website from unwanted visitors 24/7
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- RohanSectionEnds -->
<!-- Product and Services Section Start -->
<section id="servicesSection">
  <div class="container">
    <div class="sectionTitleWrap">
      <p class="subHeading">Pricing Plan</p>
      <h2 class="secTitle">Our Best <span>Product & Services</span></h2>
    </div>
    <div class="servicesWraper">
      <div class="servicesSlider">
        <?php
        $db = \Config\Database::connect();

        $request = \Config\Services::request();
        // Define an array of services
        $services = $db->table('hd_items_saved')
          ->select('hd_items_saved.*, hd_item_pricing.*, hd_categories.id AS category_id, hd_categories.cat_name AS category_name')
          ->join('hd_item_pricing', 'hd_item_pricing.item_id = hd_items_saved.item_id', 'left')
          ->join('hd_categories', 'hd_categories.id = hd_item_pricing.category', 'left')
			->groupBy('category_name')
          ->get()
          ->getResult();
        //echo"<pre>";print_r($services);die;
        foreach ($services as $service) {
          if ($service->cat_type != "") { ?>
            <div class="col-md-3">
              <div class="servicesWrapper">
                <div class="imgWrap">
                  <img src="<?= base_url('assets/images/services/cpanel-hosting.png'); ?>" alt="cPanel Hosting" />
                </div>
                <div class="serviceName">
                  <h4><?= $service->category_name ?></h4>
                  <p><?= $service->item_desc ?></p>
                </div>
                <div class="btnWraper">
                  <a href="<?php echo base_url('home/pricing_details/' . $service->category_id); ?>" target="_blank" class="btn">See More</a>
                </div>
              </div>
            </div>
        <?php
          } // End of if statement
        } // End of foreach loop
        ?>
      </div>
      <div class="feturesWrpper">
        <ul>
          <li>
            <span>
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                <g clip-path="url(#clip0_1179_13044)">
                  <path d="M13.9358 1.60506L9.237 0.0383085C9.08316 -0.0129323 8.91685 -0.0129323 8.763 0.0383085L4.06425 1.60506C3.31718 1.85322 2.66731 2.3305 2.20698 2.96909C1.74666 3.60768 1.49928 4.3751 1.5 5.16231V9.00006C1.5 14.6723 8.4 17.8051 8.6955 17.9356C8.79137 17.9781 8.8951 18.0002 9 18.0002C9.1049 18.0002 9.20864 17.9781 9.3045 17.9356C9.6 17.8051 16.5 14.6723 16.5 9.00006V5.16231C16.5007 4.3751 16.2533 3.60768 15.793 2.96909C15.3327 2.3305 14.6828 1.85322 13.9358 1.60506ZM12.5385 7.28781L9.3345 10.4918C9.20339 10.6238 9.04736 10.7284 8.87548 10.7996C8.7036 10.8708 8.51929 10.9072 8.33325 10.9066H8.3085C8.11867 10.9037 7.9314 10.8623 7.75801 10.7849C7.58463 10.7076 7.42873 10.5959 7.29975 10.4566L5.57025 8.65656C5.49535 8.58707 5.43544 8.503 5.39419 8.40952C5.35294 8.31604 5.33123 8.21512 5.33039 8.11295C5.32954 8.01078 5.34958 7.90952 5.38928 7.81537C5.42897 7.72122 5.48749 7.63618 5.56123 7.56546C5.63497 7.49473 5.72238 7.43982 5.81811 7.4041C5.91383 7.36837 6.01585 7.35258 6.11789 7.35769C6.21994 7.3628 6.31986 7.38871 6.41154 7.43383C6.50321 7.47895 6.5847 7.54232 6.651 7.62006L8.334 9.37506L11.475 6.22506C11.6165 6.08844 11.8059 6.01284 12.0026 6.01455C12.1992 6.01626 12.3873 6.09514 12.5264 6.23419C12.6654 6.37325 12.7443 6.56136 12.746 6.75801C12.7477 6.95466 12.6721 7.14411 12.5355 7.28556L12.5385 7.28781Z" fill="#2E0AA3" />
                </g>
                <defs>
                  <clipPath id="clip0_1179_13044">
                    <rect width="18" height="18" fill="white" />
                  </clipPath>
                </defs>
              </svg>
            </span>
            Daily Backups
          </li>
          <li>
            <span>
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="18" viewBox="0 0 16 18" fill="none">
                <path d="M9.5 5.26125V0.355498C10.1847 0.614998 10.8147 1.01475 11.3488 1.548L13.9618 4.1625C14.4957 4.69575 14.8955 5.32575 15.155 6.0105H10.25C9.836 6.0105 9.5 5.6745 9.5 5.26125ZM15.482 7.51125H10.25C9.0095 7.51125 8 6.50175 8 5.26125V0.028498C7.87925 0.020248 7.7585 0.010498 7.63625 0.010498H4.25C2.18225 0.011248 0.5 1.6935 0.5 3.76125V14.2612C0.5 16.329 2.18225 18.0112 4.25 18.0112H11.75C13.8177 18.0112 15.5 16.329 15.5 14.2612V7.875C15.5 7.75275 15.4902 7.632 15.482 7.51125ZM10.7802 13.8907L9.5705 15.1012C9.13775 15.534 8.5685 15.7507 8 15.7507C7.4315 15.7507 6.86225 15.534 6.4295 15.1012L5.21975 13.8907C4.9265 13.5975 4.9265 13.1227 5.21975 12.8302C5.513 12.537 5.987 12.537 6.28025 12.8302L7.25 13.8V10.5015C7.25 10.0875 7.58525 9.7515 8 9.7515C8.41475 9.7515 8.75 10.0875 8.75 10.5015V13.8L9.71975 12.8302C10.013 12.537 10.487 12.537 10.7802 12.8302C11.0735 13.1227 11.0735 13.5975 10.7802 13.8907Z" fill="#2E0AA3" />
              </svg>
            </span>
            Free Migration
          </li>
          <li>
            <span>
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                <g clip-path="url(#clip0_1179_13040)">
                  <path d="M17.3407 4.61325L13.3868 0.65925C12.9615 0.234 12.3967 0 11.796 0H6.204C5.60325 0 5.0385 0.234 4.61325 0.65925L0.65925 4.61325C0.234 5.0385 0 5.60325 0 6.204V11.796C0 12.3967 0.234 12.9615 0.65925 13.3868L4.61325 17.3407C5.0385 17.766 5.60325 18 6.204 18H11.796C12.3967 18 12.9615 17.766 13.3868 17.3407L17.3407 13.3868C17.766 12.9615 18 12.3967 18 11.796V6.204C18 5.60325 17.766 5.0385 17.3407 4.61325ZM9.20475 11.169C8.91075 11.463 8.51925 11.625 8.10375 11.625C7.68825 11.625 7.29675 11.463 7.00275 11.1683L4.29225 8.45775L5.35275 7.39725L8.064 10.1077C8.08275 10.1265 8.1255 10.1258 8.145 10.1077L12.6893 5.5635L13.7498 6.624L9.2055 11.1683L9.20475 11.169Z" fill="#2E0AA3" />
                </g>
                <defs>
                  <clipPath id="clip0_1179_13040">
                    <rect width="18" height="18" fill="white" />
                  </clipPath>
                </defs>
              </svg>
            </span>
            Staging Environments
          </li>
        </ul>
      </div>
    </div>
  </div>
</section>
<!-- Product and Services Section End -->
<!-- Hosting Solution Section Start -->
<section id="solutionSection">
  <div class="arrow"></div>
  <div class="centerBoxWrap">
    <div class="col-xl-5 col-lg-8 col-md-9">
      <div class="centercontentWraper">
        <h3>We don’t compromise with the best Hosting Solution</h3>
      </div>
      <div class="btnsWraps">
        <a href="" target="_blank" class="btn solidBtn">Get Started</a>
        <a href="" target="_blank" class="btn transBtn">Know More</a>
      </div>
    </div>
  </div>
  <div class="server"></div>
</section>
<!-- Hosting Solution Section End -->
<!-- Best Choice Section Two Start -->
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
</section>
<!-- Best Choice Section Two End -->
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

  /* function checkAvailability() {
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
  } */
  function checkAvailability() {
    $.ajax({
      url: '<?= base_url('domains1/check_availability_text') ?>',
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
        $('#domain_name').val(domain_name);
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
        console.log("inside error " + data.result);
        $('#checking').hide();
        $('#btnSearch').show();
        $('#Search').show();
        $('#Search').show();
        $('#Transfer').show();
      }
    });
  }
</script>
<script>
  function showHideDomainBox() {
    var dropdown = document.getElementById("mydropdown");
    dropdown.style.display = (dropdown.style.display === "none") ? "block" : "none";
  }

  window.onload = function() {
    var dropdown = document.getElementById("ext_name");

    dropdown.addEventListener("change", function() {
      var selectedValue = dropdown.querySelector("input:checked").value;
      var domNameText = document.querySelector(".domNameText");
      domNameText.textContent = selectedValue;
      showHideDomainBox(); // Hide the dropdown after selection
    });
  };
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get all radio buttons within the domainDropdownWrap
        var radios = document.querySelectorAll('.domainDropdownWrap input[type="radio"]');
        var domNameText = document.querySelector('.domNameText');

        // Function to handle radio button change
        function handleRadioChange(event) {
            var selectedValue = event.target.getAttribute('data-value');
            domNameText.textContent = selectedValue;
        }

        // Add change event listeners to all radio buttons
        radios.forEach(function(radio) {
            radio.addEventListener('change', handleRadioChange);
        });

        // Set the initial value based on the checked radio button
        radios.forEach(function(radio) {
            if (radio.checked) {
                domNameText.textContent = radio.getAttribute('data-value');
            }
        });
    });

    // Function to toggle visibility of the dropdown (assuming you have one)
    function showHideDomainBox() {
        var dropdown = document.getElementById('mydropdown');
        dropdown.style.display = dropdown.style.display === 'none' || dropdown.style.display === '' ? 'block' : 'none';
    }
</script>

<?= $this->endSection() ?>