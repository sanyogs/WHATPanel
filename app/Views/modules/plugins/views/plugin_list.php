<div class="row">
	<div class="col-md-12 col-sm-12">
		<?= $this->extend('layouts/users') ?>

		<?= $this->section('content') ?>
		<div class="box p-2">
			<div class="hs-topbar-wrap">
				<div class="hs-title-wrap">
					<h3><i class="fa fa-server"></i> <?= lang('hd_lang.plugins') ?></h3>
				</div>

			</div>
			<div class="activities-wrap">
				<div class="showEntriesWrap">
					<span>Show</span>
				<!--	<form action="<?php //echo base_url('plugins'); ?>" method="get">
						<select name="recordsPerPage" onchange="this.form.submit()">
							<?php //$options = [10, 25, 50, 100]; ?>
							<?php //foreach ($options as $option) : ?>
								<option value="<?//= $option ?>" <?//= ($option == $perPage) ? 'selected' : '' ?>>
									<?//= $option ?>
								</option>
							<?php //endforeach; ?>
						</select>
					</form> -->
					<span>Entries</span>
				</div>
				<div class="hs-InputWrap">
					<span>
						<svg xmlns="http://www.w3.org/2000/svg" width="18" height="15" viewBox="0 0 18 15" fill="none">
							<path d="M1.36083 13.5869L4.9727 11.4839C6.06246 12.9679 7.67959 13.9784 9.4916 14.3078C11.3036 14.6371 13.1727 14.2602 14.7147 13.2545C16.2566 12.2488 17.3542 10.6908 17.7816 8.90083C18.2091 7.11086 17.934 5.22499 17.0129 3.63098C16.0918 2.03698 14.5947 0.856004 12.8295 0.330892C11.0642 -0.19422 9.16497 -0.0235551 7.52228 0.807794C5.87959 1.63914 4.61831 3.06798 3.99777 4.80052C3.37723 6.53306 3.44461 8.4376 4.18605 10.1225L0.580387 12.2024C0.488711 12.2548 0.408327 12.3248 0.343873 12.4084C0.279418 12.492 0.232171 12.5875 0.204853 12.6895C0.177535 12.7914 0.170689 12.8978 0.18471 13.0024C0.198731 13.107 0.233342 13.2079 0.286544 13.2991C0.39336 13.4769 0.565107 13.6063 0.765541 13.66C0.965975 13.7137 1.17942 13.6875 1.36083 13.5869ZM5.34694 5.79003C5.63343 4.72083 6.23079 3.76066 7.06346 3.03094C7.89614 2.30122 8.92674 1.83472 10.0249 1.69044C11.1231 1.54616 12.2396 1.73058 13.2332 2.22037C14.2267 2.71016 15.0528 3.48332 15.6068 4.4421C16.1608 5.40087 16.418 6.50218 16.3457 7.60677C16.2735 8.71136 15.8751 9.76962 15.201 10.6477C14.5268 11.5258 13.6071 12.1843 12.5583 12.5399C11.5094 12.8956 10.3785 12.9323 9.3085 12.6456C7.87365 12.2612 6.65019 11.3229 5.90726 10.0372C5.16432 8.75154 4.96277 7.22378 5.34694 5.79003Z" fill="white"></path>
						</svg>
					</span>
					<form method='get' action="<?php echo base_url('plugins'); ?>" id="searchForm">
						<input type="text" name="search" placeholder="Search...">
						<a href="<?php echo base_url('plugins'); ?>" class="btn new-hosting-div bg-danger clrBtn">Clear</a>
					</form>
				</div>
			</div>
			<div class="d-flex gap-3 flex-wrap">
				<?php
				// echo "<pre>";print_r($plugins);die;

				if (!empty($plugins)) {
					foreach ($plugins as $item) {
						$categories[] = $item->category;
					}
				}

				if (!empty($categories)) {
					$uniqueCategories = array_unique($categories);
				}


				// Now $uniqueCategories contains all unique categories

				if (!empty($uniqueCategories)) {
					foreach ($uniqueCategories as $category) {
						echo "<button type='submit' class='common-button-cate'>$category</button>";
					}
				}
				?>

			</div>
			<div class="table-responsive">
				<input type="hidden" name="category_name" id="category_name">
				<table id="table-templates-2" class="hs-table AppendDataTables dataTable no-footer">
					<thead id="table-header">
					<tr>
							<th><?= lang('hd_lang.plugin') ?></th>
							<th><?= lang('hd_lang.category') ?></th>
							<th><?= lang('hd_lang.status') ?></th>
							<th><?= lang('hd_lang.uri') ?></th>
							<th><?= lang('hd_lang.version') ?></th>
							<th><?= lang('hd_lang.description') ?></th>
							<th><?= lang('hd_lang.author') ?></th>
							<th><?= lang('hd_lang.options') ?></th>
						</tr>
					</thead>
					<tbody>
						
						<?php

						if (!empty($plugins)) :

							foreach ($plugins as $k => $p) : ?>
								<tr id="plugin-row-<?= $k ?>" data-category="<?= $p->category ?>">
									<td><?= $p->name; ?></td>
									<td><?= $p->category; ?></td>
									<td><?= ($p->status ? 'Enabled' : 'Disabled'); ?></td>
									<td><?= '<a href="' . $p->uri . '" target="_blank">' . $p->uri . '</a>'; ?></td>
									<td><?= $p->version; ?></td>
									<td><?= $p->description; ?></td>
									<td><?= '<a href="http://' . $p->author_uri . '" target="_blank">' . $p->author . '</a>'; ?></td>
									<td>
										<div class="d-flex gap-2 justify-content-center">
											<?php if ($p->status == 1 && $p->category !== 'Servers') { ?>
												<a class="btn btn-primary btn-sm trigger common-button" href="<?= site_url('plugins/config/' . $p->system_name) ?>" data-toggle="ajaxModal"><?= lang('hd_lang.settings') ?></a>
											<?php } else { ?>
												<!-- <a class="btn btn-warning btn-sm trigger" href="<?= site_url('plugins/uninstall/' . $p->system_name) ?>" data-toggle="ajaxModal"><?= lang('hd_lang.uninstall') ?></a> -->
											<?php } ?>
											<?php if ($p->status == 0) { ?>
												<a class="btn btn-success btn-sm common-button activate-button" href="<?= site_url('plugins/activate/' . $p->system_name) ?>">
													<?= lang('hd_lang.activate') ?></a>
											<?php } else { ?>
												<a class="btn btn-warning btn-sm common-button deactivate-button" href="<?= site_url('plugins/deactivate/' . $p->system_name) ?>">
													<?= lang('hd_lang.deactivate') ?></a>
											<?php } ?>
										</div>
									</td>
								</tr>
						<?php endforeach;
						endif; ?>
					</tbody>
				</table>
			</div>
			<div class="hs-table-pagination">
				<div class="showingEntriesWrap">
					<?php

					//$totalItems = $pager->getTotal(); // Get total number of items from the pager
					//$currentStart = ($pager->getCurrentPage() - 1) * $perPage + 1; // Calculate the start index of the current page
					//$currentEnd = min($currentStart + $perPage - 1, $totalItems); // Calculate the end index of the current page

					//$showEntry = "Showing $currentStart to $currentEnd of $totalItems entries";

					?>
					<p><?//= $showEntry; ?></p>
				</div>
				<div class="hs-pagination-wrap">
					<ul class="hs-pagination">

						
					</ul>
				</div>
			</div>
		</div>
		<script>
			$(document).ready(function() {
				// Hide all rows initially
				$("#table-templates-2 tbody tr").hide();

				// Show rows with selected category when a category button is clicked
				$(".common-button-cate").click(function() {
					var category = $(this).text().trim();
					$('#category_name').val(category);
					$("#table-templates-2 tbody tr").hide();
					$("#table-header").show();
					$("#table-templates-2 tbody tr[data-category='" + category + "']").show();
				});

				// Handle modal close button click
				$(document).on('click', '.close', function() {
					var category = $('#category_name').val();
					if (category != '') {
						filterTableByCategory(category);
					}
				});

				var category = $('#category_name').val();

				alert(category);

				// Handle activate button click
				$(document).on('click', '.close', function() {
					var category = $('#category_name').val();
					if (category != '') {
						filterTableByCategory(category);
					}
				});

				// Handle deactivate button click
				$(document).on('click', '.deactivate-button', function() {
					var category = $('#category_name').val();
					if (category != '') {
						filterTableByCategory(category);
					}
				});

				function filterTableByCategory(category) {
					$("#table-templates-2 tbody tr").hide();
					$("#table-templates-2 tbody tr[data-category='" + category + "']").show();
				}
			});
		</script>
		<?= $this->endSection() ?>
	</div>
</div>