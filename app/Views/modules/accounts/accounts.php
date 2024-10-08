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
use App\Models\User;
$helper = new custom_name_helper();
?>
<section id="hosting-services-wrap">
  <div class="container px-0">
    <div class="hs-topbar-wrap">
      <div class="hs-title-wrap">
        <h3>Accounts</h3>
        <p>Showing accounts list</p>
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
          <form method='get' action="<?php echo base_url('accounts'); ?>" id="searchForm">
            <input type="text" name="search" placeholder="Search for Code" />
            <a href="<?php echo base_url('accounts'); ?>" class="btn new-hosting-div bg-danger clrBtn">Clear</a>
          </form>
        </div>
		<?php if(User::is_admin() || User::perm_allowed(User::get_id(),'manage_accounts')) { ?>
        <a href="<?=base_url()?>accounts/upload" title="<?=lang('hd_lang.domain')?>"
          class="new-hosting-div"
          data-bs-toggle="modal"
          data-bs-target="#add-new-hosting-modal" style="background-color: #0dcaf0 !important;">
          <span>
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="16"
              height="16"
              viewBox="0 0 16 16"
              fill="none"
            >
              <path
                d="M10.6667 10.3333C10.6667 10.5101 10.5964 10.6797 10.4714 10.8047C10.3464 10.9298 10.1768 11 10 11H8.66667V12.3333C8.66667 12.5101 8.59643 12.6797 8.47141 12.8047C8.34638 12.9298 8.17681 13 8 13C7.82319 13 7.65362 12.9298 7.5286 12.8047C7.40357 12.6797 7.33333 12.5101 7.33333 12.3333V11H6C5.82319 11 5.65362 10.9298 5.5286 10.8047C5.40357 10.6797 5.33333 10.5101 5.33333 10.3333C5.33333 10.1565 5.40357 9.98695 5.5286 9.86193C5.65362 9.73691 5.82319 9.66667 6 9.66667H7.33333V8.33333C7.33333 8.15652 7.40357 7.98695 7.5286 7.86193C7.65362 7.73691 7.82319 7.66667 8 7.66667C8.17681 7.66667 8.34638 7.73691 8.47141 7.86193C8.59643 7.98695 8.66667 8.15652 8.66667 8.33333V9.66667H10C10.1768 9.66667 10.3464 9.73691 10.4714 9.86193C10.5964 9.98695 10.6667 10.1565 10.6667 10.3333ZM16 5.66667V12.3333C15.9989 13.2171 15.6474 14.0643 15.0225 14.6892C14.3976 15.3141 13.5504 15.6656 12.6667 15.6667H3.33333C2.4496 15.6656 1.60237 15.3141 0.97748 14.6892C0.352588 14.0643 0.00105857 13.2171 0 12.3333L0 4.33333C0.00105857 3.4496 0.352588 2.60237 0.97748 1.97748C1.60237 1.35259 2.4496 1.00106 3.33333 1H5.01867C5.32893 1.00026 5.63493 1.07236 5.91267 1.21067L8.01667 2.26667C8.10963 2.31128 8.21155 2.33408 8.31467 2.33333H12.6667C13.5504 2.33439 14.3976 2.68592 15.0225 3.31081C15.6474 3.93571 15.9989 4.78294 16 5.66667ZM1.33333 4.33333V5H14.544C14.4066 4.61139 14.1525 4.27473 13.8165 4.03606C13.4804 3.79739 13.0788 3.66838 12.6667 3.66667H8.31467C8.0044 3.66641 7.6984 3.5943 7.42067 3.456L5.31667 2.40333C5.22398 2.35757 5.12204 2.33362 5.01867 2.33333H3.33333C2.8029 2.33333 2.29419 2.54405 1.91912 2.91912C1.54405 3.29419 1.33333 3.8029 1.33333 4.33333ZM14.6667 12.3333V6.33333H1.33333V12.3333C1.33333 12.8638 1.54405 13.3725 1.91912 13.7475C2.29419 14.1226 2.8029 14.3333 3.33333 14.3333H12.6667C13.1971 14.3333 13.7058 14.1226 14.0809 13.7475C14.456 13.3725 14.6667 12.8638 14.6667 12.3333Z"
                fill="white"
              />
            </svg>
          </span>
         <p><?=lang('hd_lang.import_whmcs')?></p>
        </a>
		<?php } ?>
      </div>
    </div>
    <div class="hs-table-wrap">
      <div class="tableInfoHead">
        <div class="showEntriesWrap">
          <span>Show</span>
          <form action="<?php echo base_url('accounts'); ?>" method="get">
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
        <div class="filterWrap">
          <button>
            <span>
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="16"
                height="16"
                viewBox="0 0 16 16"
                fill="none"
              >
                <g clip-path="url(#clip0_1514_132)">
                  <path
                    d="M0.666667 3.16684H2.49067C2.63376 3.69333 2.94612 4.1581 3.37955 4.48946C3.81299 4.82082 4.34341 5.00035 4.889 5.00035C5.43459 5.00035 5.96501 4.82082 6.39845 4.48946C6.83188 4.1581 7.14424 3.69333 7.28733 3.16684H15.3333C15.5101 3.16684 15.6797 3.0966 15.8047 2.97158C15.9298 2.84655 16 2.67698 16 2.50017C16 2.32336 15.9298 2.15379 15.8047 2.02877C15.6797 1.90374 15.5101 1.83351 15.3333 1.83351H7.28733C7.14424 1.30702 6.83188 0.842243 6.39845 0.510885C5.96501 0.179527 5.43459 0 4.889 0C4.34341 0 3.81299 0.179527 3.37955 0.510885C2.94612 0.842243 2.63376 1.30702 2.49067 1.83351H0.666667C0.489856 1.83351 0.320286 1.90374 0.195262 2.02877C0.0702379 2.15379 0 2.32336 0 2.50017C0 2.67698 0.0702379 2.84655 0.195262 2.97158C0.320286 3.0966 0.489856 3.16684 0.666667 3.16684ZM4.88867 1.33351C5.11941 1.33351 5.34497 1.40193 5.53683 1.53012C5.72869 1.65832 5.87822 1.84053 5.96653 2.05371C6.05483 2.26689 6.07793 2.50147 6.03292 2.72778C5.9879 2.95409 5.87679 3.16197 5.71362 3.32513C5.55046 3.48829 5.34258 3.59941 5.11627 3.64442C4.88996 3.68944 4.65538 3.66633 4.4422 3.57803C4.22902 3.48973 4.04681 3.3402 3.91862 3.14834C3.79042 2.95648 3.722 2.73092 3.722 2.50017C3.72235 2.19086 3.84538 1.89432 4.0641 1.6756C4.28281 1.45689 4.57936 1.33386 4.88867 1.33351Z"
                    fill="#172F78"
                  />
                  <path
                    d="M15.3333 7.33318H13.5093C13.3665 6.80657 13.0542 6.34163 12.6208 6.01013C12.1874 5.67864 11.657 5.49902 11.1113 5.49902C10.5657 5.49902 10.0352 5.67864 9.60182 6.01013C9.16842 6.34163 8.85619 6.80657 8.71333 7.33318H0.666667C0.489856 7.33318 0.320286 7.40342 0.195262 7.52844C0.0702379 7.65347 0 7.82304 0 7.99985C0 8.17666 0.0702379 8.34623 0.195262 8.47125C0.320286 8.59627 0.489856 8.66651 0.666667 8.66651H8.71333C8.85619 9.19312 9.16842 9.65807 9.60182 9.98956C10.0352 10.3211 10.5657 10.5007 11.1113 10.5007C11.657 10.5007 12.1874 10.3211 12.6208 9.98956C13.0542 9.65807 13.3665 9.19312 13.5093 8.66651H15.3333C15.5101 8.66651 15.6797 8.59627 15.8047 8.47125C15.9298 8.34623 16 8.17666 16 7.99985C16 7.82304 15.9298 7.65347 15.8047 7.52844C15.6797 7.40342 15.5101 7.33318 15.3333 7.33318ZM11.1113 9.16651C10.8806 9.16651 10.655 9.09809 10.4632 8.96989C10.2713 8.8417 10.1218 8.65949 10.0335 8.44631C9.94517 8.23313 9.92207 7.99855 9.96708 7.77224C10.0121 7.54593 10.1232 7.33805 10.2864 7.17489C10.4495 7.01173 10.6574 6.90061 10.8837 6.8556C11.11 6.81058 11.3446 6.83368 11.5578 6.92199C11.771 7.01029 11.9532 7.15982 12.0814 7.35168C12.2096 7.54354 12.278 7.7691 12.278 7.99985C12.2776 8.30916 12.1546 8.6057 11.9359 8.82441C11.7172 9.04313 11.4206 9.16616 11.1113 9.16651Z"
                    fill="#172F78"
                  />
                  <path
                    d="M15.3333 12.8335H7.28733C7.14424 12.307 6.83188 11.8422 6.39845 11.5109C5.96501 11.1795 5.43459 11 4.889 11C4.34341 11 3.81299 11.1795 3.37955 11.5109C2.94612 11.8422 2.63376 12.307 2.49067 12.8335H0.666667C0.489856 12.8335 0.320286 12.9037 0.195262 13.0288C0.0702379 13.1538 0 13.3234 0 13.5002C0 13.677 0.0702379 13.8466 0.195262 13.9716C0.320286 14.0966 0.489856 14.1668 0.666667 14.1668H2.49067C2.63376 14.6933 2.94612 15.1581 3.37955 15.4895C3.81299 15.8208 4.34341 16.0003 4.889 16.0003C5.43459 16.0003 5.96501 15.8208 6.39845 15.4895C6.83188 15.1581 7.14424 14.6933 7.28733 14.1668H15.3333C15.5101 14.1668 15.6797 14.0966 15.8047 13.9716C15.9298 13.8466 16 13.677 16 13.5002C16 13.3234 15.9298 13.1538 15.8047 13.0288C15.6797 12.9037 15.5101 12.8335 15.3333 12.8335ZM4.88867 14.6668C4.65792 14.6668 4.43236 14.5984 4.2405 14.4702C4.04864 14.342 3.89911 14.1598 3.81081 13.9466C3.72251 13.7335 3.6994 13.4989 3.74442 13.2726C3.78943 13.0463 3.90055 12.8384 4.06371 12.6752C4.22687 12.5121 4.43475 12.4009 4.66106 12.3559C4.88737 12.3109 5.12195 12.334 5.33513 12.4223C5.54831 12.5106 5.73052 12.6602 5.85871 12.852C5.98691 13.0439 6.05533 13.2694 6.05533 13.5002C6.0548 13.8094 5.93172 14.1059 5.71304 14.3245C5.49436 14.5432 5.19792 14.6663 4.88867 14.6668Z"
                    fill="#172F78"
                  />
                </g>
                <defs>
                  <clipPath id="clip0_1514_132">
                    <rect width="16" height="16" fill="white" />
                  </clipPath>
                </defs>
              </svg>
            </span>
            Filter
          </button>
          <div class="hs-dateWrap">
            <span>
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="16"
                height="16"
                viewBox="0 0 16 16"
                fill="none"
              >
                <path
                  d="M12.6667 1.33333H12V0.666667C12 0.298667 11.702 0 11.3333 0C10.9647 0 10.6667 0.298667 10.6667 0.666667V1.33333H5.33333V0.666667C5.33333 0.298667 5.03533 0 4.66667 0C4.298 0 4 0.298667 4 0.666667V1.33333H3.33333C1.49533 1.33333 0 2.82867 0 4.66667V12.6667C0 14.5047 1.49533 16 3.33333 16H12.6667C14.5047 16 16 14.5047 16 12.6667V4.66667C16 2.82867 14.5047 1.33333 12.6667 1.33333ZM3.33333 2.66667H12.6667C13.7693 2.66667 14.6667 3.564 14.6667 4.66667V5.33333H1.33333V4.66667C1.33333 3.564 2.23067 2.66667 3.33333 2.66667ZM12.6667 14.6667H3.33333C2.23067 14.6667 1.33333 13.7693 1.33333 12.6667V6.66667H14.6667V12.6667C14.6667 13.7693 13.7693 14.6667 12.6667 14.6667ZM12.6667 9.33333C12.6667 9.70133 12.3687 10 12 10H4C3.63133 10 3.33333 9.70133 3.33333 9.33333C3.33333 8.96533 3.63133 8.66667 4 8.66667H12C12.3687 8.66667 12.6667 8.96533 12.6667 9.33333ZM8 12C8 12.368 7.702 12.6667 7.33333 12.6667H4C3.63133 12.6667 3.33333 12.368 3.33333 12C3.33333 11.632 3.63133 11.3333 4 11.3333H7.33333C7.702 11.3333 8 11.632 8 12Z"
                  fill="#172F78"
                />
              </svg>
            </span>
            <input type="text" class="datepicker" />
          </div>
        </div>
      </div>
      <div class="hs-table-overflow">
        <table class="hs-table">
          <tr>
            <th><?=lang('hd_lang.package')?></th>
            <th><?=lang('hd_lang.status')?></th>
            <th><?=lang('hd_lang.domain')?></th>
            <th><?=lang('hd_lang.service')?></th>
            <?php if (User::is_admin() || User::is_staff() ) { ?>
            <th><?=lang('hd_lang.client')?></th>
            <?php } ?>
            <th><?=lang('hd_lang.control_panel')?></th>
            <th><?=lang('hd_lang.server')?></th>
            <th><?=lang('hd_lang.options')?></th>
          </tr>
          
		  <?php 
			if($helper->getconfig_item('demo_mode') == 'TRUE') {
				$accounts = array_reverse($accounts);
			}
			foreach ($accounts as $key => $order) { 
				if(isset($order->order_status)) {
			switch($order->order_status) {
            case 'pending' : $label = 'label-warning';
            break;

            case 'active' : $label = 'label-success';
            break;

            case 'suspended' : $label = 'label-danger';
            break;

            default : $label = 'label-default';
            break;

              } 
				}
			?>
		   <tr>
           <td><?=$order->item_name?></td>
		   <td><?=$order->status?></td>
		   <td><?=$order->domain?></td>
		   <td><span class="label <?=$label?>"><?=ucfirst($order->order_status)?></span></td>
		   <?php if (User::is_admin() || User::is_staff() ) { ?>
		   <td><?=$order->company_name?></td>
		   <?php } ?>
		   <td><?=ucfirst($order->order_type)?></td>
		   <td><?=$order->server_name?></td>
            <td>
              <div class="tableIcon">
				<a href="<?= base_url()?>accounts/account/<?=$order->id?>" class="new-hosting-div" style="font-size: 1.6rem;background-color: #1912d3;border-radius: 0.5rem;padding: 0.8rem 1.2rem;display: flex;align-items: center;justify-content: center;gap: 10px;cursor: pointer;color:white !important;">
				<span>Options</span>
				</a>
              </div>
            </td>
          </tr>
		<?php  } ?>
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
              <?php if (!empty($domains)) : ?>
                <!-- If there are items, display the pagination links -->
                <?php if ($pager) : ?>
                  <ul class="pagination">
                    <?php
                    $pager->setPath('accounts');

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