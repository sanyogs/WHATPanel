<?= $this->extend('layouts/users') ?>

<?= $this->section('content') ?>
<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

use App\Helpers\custom_name_helper;

$custom_name_helper = new custom_name_helper();

// echo "<pre>";print_r($sliders);die;
?>
<section id="hosting-services-wrap">
	<div class="container px-0">
		<div class="hs-topbar-wrap">
			<div class="hs-title-wrap">
				<h3>Sliders</h3>
				<p>Showing sliders list</p>
			</div>
			<div class="hs-search-wrap">
				<div class="hs-InputWrap">
					<span>
						<svg xmlns="http://www.w3.org/2000/svg" width="18" height="15" viewBox="0 0 18 15" fill="none">
							<path d="M1.36083 13.5869L4.9727 11.4839C6.06246 12.9679 7.67959 13.9784 9.4916 14.3078C11.3036 14.6371 13.1727 14.2602 14.7147 13.2545C16.2566 12.2488 17.3542 10.6908 17.7816 8.90083C18.2091 7.11086 17.934 5.22499 17.0129 3.63098C16.0918 2.03698 14.5947 0.856004 12.8295 0.330892C11.0642 -0.19422 9.16497 -0.0235551 7.52228 0.807794C5.87959 1.63914 4.61831 3.06798 3.99777 4.80052C3.37723 6.53306 3.44461 8.4376 4.18605 10.1225L0.580387 12.2024C0.488711 12.2548 0.408327 12.3248 0.343873 12.4084C0.279418 12.492 0.232171 12.5875 0.204853 12.6895C0.177535 12.7914 0.170689 12.8978 0.18471 13.0024C0.198731 13.107 0.233342 13.2079 0.286544 13.2991C0.39336 13.4769 0.565107 13.6063 0.765541 13.66C0.965975 13.7137 1.17942 13.6875 1.36083 13.5869ZM5.34694 5.79003C5.63343 4.72083 6.23079 3.76066 7.06346 3.03094C7.89614 2.30122 8.92674 1.83472 10.0249 1.69044C11.1231 1.54616 12.2396 1.73058 13.2332 2.22037C14.2267 2.71016 15.0528 3.48332 15.6068 4.4421C16.1608 5.40087 16.418 6.50218 16.3457 7.60677C16.2735 8.71136 15.8751 9.76962 15.201 10.6477C14.5268 11.5258 13.6071 12.1843 12.5583 12.5399C11.5094 12.8956 10.3785 12.9323 9.3085 12.6456C7.87365 12.2612 6.65019 11.3229 5.90726 10.0372C5.16432 8.75154 4.96277 7.22378 5.34694 5.79003Z" fill="white" />
						</svg>
					</span>
					<form method='get' action="<?php echo base_url('sliders'); ?>" id="searchForm">
						<input type="text" name="search" placeholder="Search for Code" />
						<a href="<?php echo base_url('sliders'); ?>" class="btn new-hosting-div bg-danger clrBtn">Clear</a>
					</form>
				</div>
				<a href="<?= base_url('sliders/add') ?>" data-toggle="ajaxModal" class="new-hosting-div">
					<span>
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
							<path d="M10.6667 10.3333C10.6667 10.5101 10.5964 10.6797 10.4714 10.8047C10.3464 10.9298 10.1768 11 10 11H8.66667V12.3333C8.66667 12.5101 8.59643 12.6797 8.47141 12.8047C8.34638 12.9298 8.17681 13 8 13C7.82319 13 7.65362 12.9298 7.5286 12.8047C7.40357 12.6797 7.33333 12.5101 7.33333 12.3333V11H6C5.82319 11 5.65362 10.9298 5.5286 10.8047C5.40357 10.6797 5.33333 10.5101 5.33333 10.3333C5.33333 10.1565 5.40357 9.98695 5.5286 9.86193C5.65362 9.73691 5.82319 9.66667 6 9.66667H7.33333V8.33333C7.33333 8.15652 7.40357 7.98695 7.5286 7.86193C7.65362 7.73691 7.82319 7.66667 8 7.66667C8.17681 7.66667 8.34638 7.73691 8.47141 7.86193C8.59643 7.98695 8.66667 8.15652 8.66667 8.33333V9.66667H10C10.1768 9.66667 10.3464 9.73691 10.4714 9.86193C10.5964 9.98695 10.6667 10.1565 10.6667 10.3333ZM16 5.66667V12.3333C15.9989 13.2171 15.6474 14.0643 15.0225 14.6892C14.3976 15.3141 13.5504 15.6656 12.6667 15.6667H3.33333C2.4496 15.6656 1.60237 15.3141 0.97748 14.6892C0.352588 14.0643 0.00105857 13.2171 0 12.3333L0 4.33333C0.00105857 3.4496 0.352588 2.60237 0.97748 1.97748C1.60237 1.35259 2.4496 1.00106 3.33333 1H5.01867C5.32893 1.00026 5.63493 1.07236 5.91267 1.21067L8.01667 2.26667C8.10963 2.31128 8.21155 2.33408 8.31467 2.33333H12.6667C13.5504 2.33439 14.3976 2.68592 15.0225 3.31081C15.6474 3.93571 15.9989 4.78294 16 5.66667ZM1.33333 4.33333V5H14.544C14.4066 4.61139 14.1525 4.27473 13.8165 4.03606C13.4804 3.79739 13.0788 3.66838 12.6667 3.66667H8.31467C8.0044 3.66641 7.6984 3.5943 7.42067 3.456L5.31667 2.40333C5.22398 2.35757 5.12204 2.33362 5.01867 2.33333H3.33333C2.8029 2.33333 2.29419 2.54405 1.91912 2.91912C1.54405 3.29419 1.33333 3.8029 1.33333 4.33333ZM14.6667 12.3333V6.33333H1.33333V12.3333C1.33333 12.8638 1.54405 13.3725 1.91912 13.7475C2.29419 14.1226 2.8029 14.3333 3.33333 14.3333H12.6667C13.1971 14.3333 13.7058 14.1226 14.0809 13.7475C14.456 13.3725 14.6667 12.8638 14.6667 12.3333Z" fill="white" />
						</svg>
					</span>
					<p>Add Slider</p>
				</a>
			</div>
		</div>
		<div class="hs-table-wrap">
			<div class="tableInfoHead">
				<div class="showEntriesWrap">
					<span>Show</span>
					<form action="<?php echo base_url('sliders'); ?>" method="get">
					<select name="recordsPerPage" onchange="this.form.submit()">
					 
					</select>
				  </form>
				</div>
			</div>
			<div class="hs-table-overflow">
				<table class="hs-table">
					<tr>
						<th>Name</th>
						<th>Slides</th>
						<th>Action</th>
					</tr>
					<?php foreach ($sliders as $key => $row) { ?>
						<tr>
							<td><?= $row->name ?></td>
							<td><?= $row->slides ?></td>
							<td>
								<div class="tableIcon">
									<a href="<?= base_url('sliders/slider/' . $row->slider_id) ?>" data- toggle="ajaxModal">
										<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
											<g clip-path="url(#clip0_1514_208)">
												<path d="M12.4373 0.619885L4.30927 8.74789C3.99883 9.05665 3.75272 9.42392 3.58519 9.82845C3.41766 10.233 3.33203 10.6667 3.33327 11.1046V11.9999C3.33327 12.1767 3.4035 12.3463 3.52853 12.4713C3.65355 12.5963 3.82312 12.6666 3.99993 12.6666H4.89527C5.33311 12.6678 5.76685 12.5822 6.17137 12.4146C6.57589 12.2471 6.94317 12.001 7.25193 11.6906L15.3799 3.56255C15.7695 3.172 15.9883 2.64287 15.9883 2.09122C15.9883 1.53957 15.7695 1.01044 15.3799 0.619885C14.9837 0.241148 14.4567 0.0297852 13.9086 0.0297852C13.3605 0.0297852 12.8335 0.241148 12.4373 0.619885ZM14.4373 2.61989L6.30927 10.7479C5.93335 11.1215 5.42527 11.3318 4.89527 11.3332H4.6666V11.1046C4.66799 10.5745 4.87831 10.0665 5.25193 9.69055L13.3799 1.56255C13.5223 1.42652 13.7117 1.35061 13.9086 1.35061C14.1055 1.35061 14.2949 1.42652 14.4373 1.56255C14.5772 1.7029 14.6558 1.89301 14.6558 2.09122C14.6558 2.28942 14.5772 2.47954 14.4373 2.61989Z" fill="#1912D3" />
												<path d="M15.3333 5.986C15.1565 5.986 14.987 6.05624 14.8619 6.18126C14.7369 6.30629 14.6667 6.47586 14.6667 6.65267V10H12C11.4696 10 10.9609 10.2107 10.5858 10.5858C10.2107 10.9609 10 11.4696 10 12V14.6667H3.33333C2.8029 14.6667 2.29419 14.456 1.91912 14.0809C1.54405 13.7058 1.33333 13.1971 1.33333 12.6667V3.33333C1.33333 2.8029 1.54405 2.29419 1.91912 1.91912C2.29419 1.54405 2.8029 1.33333 3.33333 1.33333H9.36133C9.53815 1.33333 9.70771 1.2631 9.83274 1.13807C9.95776 1.01305 10.028 0.843478 10.028 0.666667C10.028 0.489856 9.95776 0.320286 9.83274 0.195262C9.70771 0.0702379 9.53815 0 9.36133 0L3.33333 0C2.4496 0.00105857 1.60237 0.352588 0.97748 0.97748C0.352588 1.60237 0.00105857 2.4496 0 3.33333L0 12.6667C0.00105857 13.5504 0.352588 14.3976 0.97748 15.0225C1.60237 15.6474 2.4496 15.9989 3.33333 16H10.8953C11.3333 16.0013 11.7671 15.9156 12.1718 15.7481C12.5764 15.5806 12.9438 15.3345 13.2527 15.024L15.0233 13.252C15.3338 12.9432 15.58 12.576 15.7477 12.1715C15.9153 11.767 16.0011 11.3332 16 10.8953V6.65267C16 6.47586 15.9298 6.30629 15.8047 6.18126C15.6797 6.05624 15.5101 5.986 15.3333 5.986ZM12.31 14.0813C12.042 14.3487 11.7031 14.5337 11.3333 14.6147V12C11.3333 11.8232 11.4036 11.6536 11.5286 11.5286C11.6536 11.4036 11.8232 11.3333 12 11.3333H14.6167C14.5342 11.7023 14.3493 12.0406 14.0833 12.3093L12.31 14.0813Z" fill="#1912D3" />
											</g>
											<defs>
												<clipPath id="clip0_1514_208">
													<rect width="16" height="16" fill="white" />
												</clipPath>
											</defs>
										</svg>
									</a>
									<a href="<?= base_url('sliders/edit/' . $row->slider_id) ?>" data-toggle="ajaxModal">
										<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
											<g clip-path="url(#clip0_1514_208)">
												<path d="M12.4373 0.619885L4.30927 8.74789C3.99883 9.05665 3.75272 9.42392 3.58519 9.82845C3.41766 10.233 3.33203 10.6667 3.33327 11.1046V11.9999C3.33327 12.1767 3.4035 12.3463 3.52853 12.4713C3.65355 12.5963 3.82312 12.6666 3.99993 12.6666H4.89527C5.33311 12.6678 5.76685 12.5822 6.17137 12.4146C6.57589 12.2471 6.94317 12.001 7.25193 11.6906L15.3799 3.56255C15.7695 3.172 15.9883 2.64287 15.9883 2.09122C15.9883 1.53957 15.7695 1.01044 15.3799 0.619885C14.9837 0.241148 14.4567 0.0297852 13.9086 0.0297852C13.3605 0.0297852 12.8335 0.241148 12.4373 0.619885ZM14.4373 2.61989L6.30927 10.7479C5.93335 11.1215 5.42527 11.3318 4.89527 11.3332H4.6666V11.1046C4.66799 10.5745 4.87831 10.0665 5.25193 9.69055L13.3799 1.56255C13.5223 1.42652 13.7117 1.35061 13.9086 1.35061C14.1055 1.35061 14.2949 1.42652 14.4373 1.56255C14.5772 1.7029 14.6558 1.89301 14.6558 2.09122C14.6558 2.28942 14.5772 2.47954 14.4373 2.61989Z" fill="#1912D3" />
												<path d="M15.3333 5.986C15.1565 5.986 14.987 6.05624 14.8619 6.18126C14.7369 6.30629 14.6667 6.47586 14.6667 6.65267V10H12C11.4696 10 10.9609 10.2107 10.5858 10.5858C10.2107 10.9609 10 11.4696 10 12V14.6667H3.33333C2.8029 14.6667 2.29419 14.456 1.91912 14.0809C1.54405 13.7058 1.33333 13.1971 1.33333 12.6667V3.33333C1.33333 2.8029 1.54405 2.29419 1.91912 1.91912C2.29419 1.54405 2.8029 1.33333 3.33333 1.33333H9.36133C9.53815 1.33333 9.70771 1.2631 9.83274 1.13807C9.95776 1.01305 10.028 0.843478 10.028 0.666667C10.028 0.489856 9.95776 0.320286 9.83274 0.195262C9.70771 0.0702379 9.53815 0 9.36133 0L3.33333 0C2.4496 0.00105857 1.60237 0.352588 0.97748 0.97748C0.352588 1.60237 0.00105857 2.4496 0 3.33333L0 12.6667C0.00105857 13.5504 0.352588 14.3976 0.97748 15.0225C1.60237 15.6474 2.4496 15.9989 3.33333 16H10.8953C11.3333 16.0013 11.7671 15.9156 12.1718 15.7481C12.5764 15.5806 12.9438 15.3345 13.2527 15.024L15.0233 13.252C15.3338 12.9432 15.58 12.576 15.7477 12.1715C15.9153 11.767 16.0011 11.3332 16 10.8953V6.65267C16 6.47586 15.9298 6.30629 15.8047 6.18126C15.6797 6.05624 15.5101 5.986 15.3333 5.986ZM12.31 14.0813C12.042 14.3487 11.7031 14.5337 11.3333 14.6147V12C11.3333 11.8232 11.4036 11.6536 11.5286 11.5286C11.6536 11.4036 11.8232 11.3333 12 11.3333H14.6167C14.5342 11.7023 14.3493 12.0406 14.0833 12.3093L12.31 14.0813Z" fill="#1912D3" />
											</g>
											<defs>
												<clipPath id="clip0_1514_208">
													<rect width="16" height="16" fill="white" />
												</clipPath>
											</defs>
										</svg>
									</a>
									<a href="<?= base_url('sliders/delete/' . $row->slider_id) ?>" data-toggle="ajaxModal">
										<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
											<g clip-path="url(#clip0_1514_211)">
												<path d="M13.9999 2.66667H11.9333C11.7785 1.91428 11.3691 1.23823 10.7741 0.752479C10.179 0.266727 9.43472 0.000969683 8.66659 0L7.33325 0C6.56512 0.000969683 5.8208 0.266727 5.22575 0.752479C4.63071 1.23823 4.22132 1.91428 4.06659 2.66667H1.99992C1.82311 2.66667 1.65354 2.7369 1.52851 2.86193C1.40349 2.98695 1.33325 3.15652 1.33325 3.33333C1.33325 3.51014 1.40349 3.67971 1.52851 3.80474C1.65354 3.92976 1.82311 4 1.99992 4H2.66659V12.6667C2.66764 13.5504 3.01917 14.3976 3.64407 15.0225C4.26896 15.6474 5.11619 15.9989 5.99992 16H9.99992C10.8836 15.9989 11.7309 15.6474 12.3558 15.0225C12.9807 14.3976 13.3322 13.5504 13.3333 12.6667V4H13.9999C14.1767 4 14.3463 3.92976 14.4713 3.80474C14.5963 3.67971 14.6666 3.51014 14.6666 3.33333C14.6666 3.15652 14.5963 2.98695 14.4713 2.86193C14.3463 2.7369 14.1767 2.66667 13.9999 2.66667ZM7.33325 1.33333H8.66659C9.0801 1.33384 9.48334 1.46225 9.821 1.70096C10.1587 1.93967 10.4142 2.27699 10.5526 2.66667H5.44725C5.58564 2.27699 5.84119 1.93967 6.17884 1.70096C6.5165 1.46225 6.91974 1.33384 7.33325 1.33333ZM11.9999 12.6667C11.9999 13.1971 11.7892 13.7058 11.4141 14.0809C11.0391 14.456 10.5304 14.6667 9.99992 14.6667H5.99992C5.46949 14.6667 4.96078 14.456 4.58571 14.0809C4.21063 13.7058 3.99992 13.1971 3.99992 12.6667V4H11.9999V12.6667Z" fill="#1912D3" />
												<path d="M6.66667 11.9998C6.84348 11.9998 7.01305 11.9296 7.13807 11.8046C7.2631 11.6796 7.33333 11.51 7.33333 11.3332V7.33317C7.33333 7.15636 7.2631 6.98679 7.13807 6.86177C7.01305 6.73674 6.84348 6.6665 6.66667 6.6665C6.48986 6.6665 6.32029 6.73674 6.19526 6.86177C6.07024 6.98679 6 7.15636 6 7.33317V11.3332C6 11.51 6.07024 11.6796 6.19526 11.8046C6.32029 11.9296 6.48986 11.9998 6.66667 11.9998Z" fill="#1912D3" />
												<path d="M9.33341 11.9998C9.51023 11.9998 9.67979 11.9296 9.80482 11.8046C9.92984 11.6796 10.0001 11.51 10.0001 11.3332V7.33317C10.0001 7.15636 9.92984 6.98679 9.80482 6.86177C9.67979 6.73674 9.51023 6.6665 9.33341 6.6665C9.1566 6.6665 8.98703 6.73674 8.86201 6.86177C8.73699 6.98679 8.66675 7.15636 8.66675 7.33317V11.3332C8.66675 11.51 8.73699 11.6796 8.86201 11.8046C8.98703 11.9296 9.1566 11.9998 9.33341 11.9998Z" fill="#1912D3" />
											</g>
											<defs>
												<clipPath id="clip0_1514_211">
													<rect width="16" height="16" fill="white" />
												</clipPath>
											</defs>
										</svg>
									</a>
								</div>
							</td>
						</tr>
					<?php } ?>
				</table>
			</div>
			<div class="hs-table-pagination">
				<div class="showingEntriesWrap">
				  <?php
				  //$totalItems = $pager->getTotal(); // Get total number of items from the pager
				 // $currentStart = ($pager->getCurrentPage() - 1) * $perPage + 1; // Calculate the start index of the current page
				  //$currentEnd = min($currentStart + $perPage - 1, $totalItems); // Calculate the end index of the current page

				  //$showEntry = "Showing $currentStart to $currentEnd of $totalItems entries";

				  ?>
				  <p><?//= $showEntry; ?></p>
			  </div>
				<div class="hs-pagination-wrap">
					<ul class="hs-pagination">
						<div class="row">
							<?php if (!empty($servers)) : ?>
							<!-- If there are items, display the pagination links -->
							<?php if ($pager) : ?>
							<ul class="pagination">
								<?php
								$pager->setPath('servers');

								// Output Pagination Links
								echo $pager->links();
								?>
							</ul>
							<?php endif; ?>

							<?php else : ?>
							<!-- If there are no items, display the message -->
							<div class="col-12 text-center">
								<h1 class="text-center"><?//= esc($message) ?></h1>
							</div>
							<?php endif ?>
						</div>
					</ul>
				</div>
			</div>
		</div>
	</div>
</section>
<!-- new-hosting-add-modal start -->
<section id="add-new-hosting-modalwrap">
	<div class="modal fade" id="add-new-hosting-modal-sliders" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog nh-modal-adjuster">
			<div class="modal-content nh-modal-content">
				<div class="nh-modal-header">
					<div class="nh-modal-title">
						<h3>Add New Hosting</h3>
						<p>Fill below options to add Hosting</p>
					</div>
					<div class="nh-modal-close">
						<span data-bs-dismiss="modal">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
								<path d="M10.4713 6.47133L8.94267 8L10.4713 9.52867C10.732 9.78933 10.732 10.2107 10.4713 10.4713C10.3413 10.6013 10.1707 10.6667 10 10.6667C9.82933 10.6667 9.65867 10.6013 9.52867 10.4713L8 8.94267L6.47133 10.4713C6.34133 10.6013 6.17067 10.6667 6 10.6667C5.82933 10.6667 5.65867 10.6013 5.52867 10.4713C5.268 10.2107 5.268 9.78933 5.52867 9.52867L7.05733 8L5.52867 6.47133C5.268 6.21067 5.268 5.78933 5.52867 5.52867C5.78933 5.268 6.21067 5.268 6.47133 5.52867L8 7.05733L9.52867 5.52867C9.78933 5.268 10.2107 5.268 10.4713 5.52867C10.732 5.78933 10.732 6.21067 10.4713 6.47133ZM16 8C16 12.4113 12.4113 16 8 16C3.58867 16 0 12.4113 0 8C0 3.58867 3.58867 0 8 0C12.4113 0 16 3.58867 16 8ZM14.6667 8C14.6667 4.324 11.676 1.33333 8 1.33333C4.324 1.33333 1.33333 4.324 1.33333 8C1.33333 11.676 4.324 14.6667 8 14.6667C11.676 14.6667 14.6667 11.676 14.6667 8Z" fill="#888888" />
							</svg>
						</span>
					</div>
				</div>
				<div class="modal-body">
					<form id="modelForm" method="POST" action="<?php echo base_url('hosting/add'); ?>">
						<!-- Content loaded via AJAX will be displayed here -->
					</form>
				</div>
			</div>
		</div>
	</div>
</section>
<!-- new-hosting-add-modal end -->

<!-- new-hosting-edit-modal start -->
<section id="add-new-hosting-modalwrap">
	<div class="modal fade" id="add-new-hosting-modal-sliders-edit" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog nh-modal-adjuster">
			<div class="modal-content nh-modal-content">
				<div class="nh-modal-header">
					<div class="nh-modal-title">
						<h3>Add New Hosting</h3>
						<p>Fill below options to add Hosting</p>
					</div>
					<div class="nh-modal-close">
						<span data-bs-dismiss="modal">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
								<path d="M10.4713 6.47133L8.94267 8L10.4713 9.52867C10.732 9.78933 10.732 10.2107 10.4713 10.4713C10.3413 10.6013 10.1707 10.6667 10 10.6667C9.82933 10.6667 9.65867 10.6013 9.52867 10.4713L8 8.94267L6.47133 10.4713C6.34133 10.6013 6.17067 10.6667 6 10.6667C5.82933 10.6667 5.65867 10.6013 5.52867 10.4713C5.268 10.2107 5.268 9.78933 5.52867 9.52867L7.05733 8L5.52867 6.47133C5.268 6.21067 5.268 5.78933 5.52867 5.52867C5.78933 5.268 6.21067 5.268 6.47133 5.52867L8 7.05733L9.52867 5.52867C9.78933 5.268 10.2107 5.268 10.4713 5.52867C10.732 5.78933 10.732 6.21067 10.4713 6.47133ZM16 8C16 12.4113 12.4113 16 8 16C3.58867 16 0 12.4113 0 8C0 3.58867 3.58867 0 8 0C12.4113 0 16 3.58867 16 8ZM14.6667 8C14.6667 4.324 11.676 1.33333 8 1.33333C4.324 1.33333 1.33333 4.324 1.33333 8C1.33333 11.676 4.324 14.6667 8 14.6667C11.676 14.6667 14.6667 11.676 14.6667 8Z" fill="#888888" />
							</svg>
						</span>
					</div>
				</div>
				<div class="modal-body">
					<form id="modelForm" method="POST" action="<?php echo base_url('hosting/add'); ?>">
						<!-- Content loaded via AJAX will be displayed here -->
					</form>
				</div>
			</div>
		</div>
	</div>
</section>
<!-- new-hosting-edit-modal end -->

<!-- new-hosting-delete-modal start -->
<section id="add-new-hosting-modalwrap">
	<div class="modal fade" id="add-new-hosting-modal-sliders-delete" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog nh-modal-adjuster">
			<div class="modal-content nh-modal-content">
				<div class="nh-modal-header">
					<div class="nh-modal-title">
						<h3>Add New Hosting</h3>
						<p>Fill below options to add Hosting</p>
					</div>
					<div class="nh-modal-close">
						<span data-bs-dismiss="modal">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
								<path d="M10.4713 6.47133L8.94267 8L10.4713 9.52867C10.732 9.78933 10.732 10.2107 10.4713 10.4713C10.3413 10.6013 10.1707 10.6667 10 10.6667C9.82933 10.6667 9.65867 10.6013 9.52867 10.4713L8 8.94267L6.47133 10.4713C6.34133 10.6013 6.17067 10.6667 6 10.6667C5.82933 10.6667 5.65867 10.6013 5.52867 10.4713C5.268 10.2107 5.268 9.78933 5.52867 9.52867L7.05733 8L5.52867 6.47133C5.268 6.21067 5.268 5.78933 5.52867 5.52867C5.78933 5.268 6.21067 5.268 6.47133 5.52867L8 7.05733L9.52867 5.52867C9.78933 5.268 10.2107 5.268 10.4713 5.52867C10.732 5.78933 10.732 6.21067 10.4713 6.47133ZM16 8C16 12.4113 12.4113 16 8 16C3.58867 16 0 12.4113 0 8C0 3.58867 3.58867 0 8 0C12.4113 0 16 3.58867 16 8ZM14.6667 8C14.6667 4.324 11.676 1.33333 8 1.33333C4.324 1.33333 1.33333 4.324 1.33333 8C1.33333 11.676 4.324 14.6667 8 14.6667C11.676 14.6667 14.6667 11.676 14.6667 8Z" fill="#888888" />
							</svg>
						</span>
					</div>
				</div>
				<div class="modal-body">
					<form id="modelForm" method="POST" action="<?php echo base_url('hosting/add'); ?>">
						<!-- Content loaded via AJAX will be displayed here -->
					</form>
				</div>
			</div>
		</div>
	</div>
</section>
<!-- new-hosting-delete-modal end -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- add script starts here -->
<script>
	$(document).ready(function() {
		$('#modalBtn').on('click', function(e) {
			e.preventDefault();
			$.ajax({
				type: 'POST',
				url: '<?php echo base_url("sliders/add"); ?>',
				success: function(response) {

					$('#add-new-hosting-modal-sliders .modal-body #modelForm').html(response); // Assuming the response contains the HTML to display in the modal
					$('#add-new-hosting-modal-sliders').modal('show'); // Show the modal
					$('#add-new-hosting-modal-sliders').addClass('show');
				},
			});
		});
	});
</script>
<!-- add script ends here -->

<!-- edit script starts here -->
<script>
	$(document).ready(function() {
		$('#editmodalBtn').on('click', function(e) {
			e.preventDefault();
			$.ajax({
				type: 'POST',
				url: '<?php echo base_url("sliders/edit/' . $row->slider_id"); ?>',
				success: function(response) {

					$('#add-new-hosting-modal-sliders-edit .modal-body #modelForm').html(response); // Assuming the response contains the HTML to display in the modal
					$('#add-new-hosting-modal-sliders-edit').modal('show'); // Show the modal
					$('#add-new-hosting-modal-sliders-edit').addClass('show');
				},
			});
		});
	});
</script>
<!-- edit script ends here -->

<!-- delete script starts here -->
<script>
	$(document).ready(function() {
		$('#deletemodalBtn').on('click', function(e) {
			e.preventDefault();
			$.ajax({
				type: 'POST',
				url: '<?php echo base_url("sliders/delete/' . $row->slider_id"); ?>',
				success: function(response) {

					$('#add-new-hosting-modal-sliders-delete .modal-body #modelForm').html(response); // Assuming the response contains the HTML to display in the modal
					$('#add-new-hosting-modal-sliders-delete').modal('show'); // Show the modal
					$('#add-new-hosting-modal-sliders-delete').addClass('show');
				},
			});
		});
	});
</script>
<!-- delete script ends here -->
<?= $this->endSection() ?>