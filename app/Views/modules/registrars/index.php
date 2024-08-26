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
use App\Models\App;
use App\Models\Client;
use App\Models\Plugin;
use App\Models\User;
use App\ThirdParty\MX\Modules;
$session = \Config\Services::session();
$totalEntries = count($registrars);
$counter = 0;
?>
<section id="hosting-services-wrap">
  <div class="container px-0">
    <div class="hs-topbar-wrap">
      <div class="hs-title-wrap">
        <h3>Registrar</h3>
        <p>Showing registrar list</p>
      </div>
      <div class="hs-search-wrap">
        <div class="hs-InputWrap">
          <span>
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="18"
              height="15"
              viewBox="0 0 18 15"
              fill="none"
            >
              <path
                d="M1.36083 13.5869L4.9727 11.4839C6.06246 12.9679 7.67959 13.9784 9.4916 14.3078C11.3036 14.6371 13.1727 14.2602 14.7147 13.2545C16.2566 12.2488 17.3542 10.6908 17.7816 8.90083C18.2091 7.11086 17.934 5.22499 17.0129 3.63098C16.0918 2.03698 14.5947 0.856004 12.8295 0.330892C11.0642 -0.19422 9.16497 -0.0235551 7.52228 0.807794C5.87959 1.63914 4.61831 3.06798 3.99777 4.80052C3.37723 6.53306 3.44461 8.4376 4.18605 10.1225L0.580387 12.2024C0.488711 12.2548 0.408327 12.3248 0.343873 12.4084C0.279418 12.492 0.232171 12.5875 0.204853 12.6895C0.177535 12.7914 0.170689 12.8978 0.18471 13.0024C0.198731 13.107 0.233342 13.2079 0.286544 13.2991C0.39336 13.4769 0.565107 13.6063 0.765541 13.66C0.965975 13.7137 1.17942 13.6875 1.36083 13.5869ZM5.34694 5.79003C5.63343 4.72083 6.23079 3.76066 7.06346 3.03094C7.89614 2.30122 8.92674 1.83472 10.0249 1.69044C11.1231 1.54616 12.2396 1.73058 13.2332 2.22037C14.2267 2.71016 15.0528 3.48332 15.6068 4.4421C16.1608 5.40087 16.418 6.50218 16.3457 7.60677C16.2735 8.71136 15.8751 9.76962 15.201 10.6477C14.5268 11.5258 13.6071 12.1843 12.5583 12.5399C11.5094 12.8956 10.3785 12.9323 9.3085 12.6456C7.87365 12.2612 6.65019 11.3229 5.90726 10.0372C5.16432 8.75154 4.96277 7.22378 5.34694 5.79003Z"
                fill="white"
              />
            </svg>
          </span>
          <form method='get' action="<?php echo base_url('registrars'); ?>" id="searchForm">
            <input type="text" name="search" placeholder="Search for Code" />
			<a href="<?php echo base_url('registrars'); ?>" class="btn new-hosting-div bg-danger clrBtn">Clear</a>
					</form>
          </form>
        </div>
      </div>
    </div>
    <div class="hs-table-wrap">
      <div class="tableInfoHead">
        <div class="showEntriesWrap">
          <span>Show</span>
          <form action="<?php echo base_url('registrars'); ?>" method="get">
            <select name="recordsPerPage" onchange="this.form.submit()">
              <?php $options = [10, 25, 50, 100]; ?>
              <?php foreach ($options as $option) : ?>
                <option value="<?= $option ?>" <?= ($option == $perPage) ? 'selected' : '' ?>>
                  <?= $option ?>
                </option>
              <?php endforeach; ?>
            </select>
          </form>
          <span>Entries</span>
        </div>
      </div>
      <div class="hs-table-overflow table-responsive">
        <table class="hs-table">
          <tr>
           <th><?=lang('hd_lang.registrar')?></th>
           <th><?=lang('hd_lang.options')?></th>
          </tr>
		  <?php foreach ($registrars as $registrar): ?>
          <tr>
            <td><?=ucfirst($registrar->system_name);?></td>
            <td>
              <?= modules::run($registrar->system_name.'/admin_options', $registrar->system_name)?>
            </td>
          </tr>
		  <?php $counter++; ?>
  		<?php endforeach; ?>
        </table>
      </div>
      <div class="hs-table-pagination">
        <div class="showingEntriesWrap">
          <?php

          $totalItems = $pager->getTotal(); // Get total number of items from the pager
          $currentStart = ($pager->getCurrentPage() - 1) * $perPage + 1; // Calculate the start index of the current page
          $currentEnd = min($currentStart + $perPage - 1, $totalItems); // Calculate the end index of the current page

          $showEntry = "Showing $currentStart to $currentEnd of $totalItems entries";

          ?>
          <p><?= $showEntry; ?></p>
        </div>
        <div class="hs-pagination-wrap">
          <ul class="hs-pagination">

            <div class="row">
              <?php if (!empty($registrars)) : ?>
                <!-- If there are items, display the pagination links -->
                <?php if ($pager) : ?>
                  <ul class="pagination">
                    <?php
                    $pager->setPath('registrars');

                    // Output Pagination Links
                    echo $pager->links();
                    ?>
                  </ul>
                <?php endif; ?>

              <?php else : ?>
                <!-- If there are no items, display the message -->
                <div class="col-12 text-center">
                  <h1 class="text-center"><?= esc($message) ?></h1>
                </div>
              <?php endif ?>
            </div>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>
<?= $this->endSection() ?>