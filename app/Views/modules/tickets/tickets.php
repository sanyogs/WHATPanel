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

use App\Libraries\AppLib;
use App\Models\App;
use App\Models\Ticket;
use App\Models\User;
use App\Helpers\AuthHelper;
use App\Helpers\custom_name_helper;

$custom = new custom_name_helper();

?>
<style>
.edit-link {
    position: relative;
    display: inline-block;
    text-decoration: none;
}

.edit-link .edit-name {
    position: absolute;
    top: 100%;
    left: 50%;
    transform: translateX(-50%);
    background-color: rgba(0, 0, 0, 0.8);
    color: white;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.edit-link:hover .edit-name {
    opacity: 1;
}
</style>
<section id="hosting-services-wrap">
  <div class="container px-0">
    <div class="hs-topbar-wrap">
      <div class="hs-title-wrap">
        <h3>Tickets</h3>
        <p>Showing tickets list</p>
      </div>
      <div class="hs-search-wrap">
        <div class="hs-InputWrap">
          <span>
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="15" viewBox="0 0 18 15" fill="none">
              <path d="M1.36083 13.5869L4.9727 11.4839C6.06246 12.9679 7.67959 13.9784 9.4916 14.3078C11.3036 14.6371 13.1727 14.2602 14.7147 13.2545C16.2566 12.2488 17.3542 10.6908 17.7816 8.90083C18.2091 7.11086 17.934 5.22499 17.0129 3.63098C16.0918 2.03698 14.5947 0.856004 12.8295 0.330892C11.0642 -0.19422 9.16497 -0.0235551 7.52228 0.807794C5.87959 1.63914 4.61831 3.06798 3.99777 4.80052C3.37723 6.53306 3.44461 8.4376 4.18605 10.1225L0.580387 12.2024C0.488711 12.2548 0.408327 12.3248 0.343873 12.4084C0.279418 12.492 0.232171 12.5875 0.204853 12.6895C0.177535 12.7914 0.170689 12.8978 0.18471 13.0024C0.198731 13.107 0.233342 13.2079 0.286544 13.2991C0.39336 13.4769 0.565107 13.6063 0.765541 13.66C0.965975 13.7137 1.17942 13.6875 1.36083 13.5869ZM5.34694 5.79003C5.63343 4.72083 6.23079 3.76066 7.06346 3.03094C7.89614 2.30122 8.92674 1.83472 10.0249 1.69044C11.1231 1.54616 12.2396 1.73058 13.2332 2.22037C14.2267 2.71016 15.0528 3.48332 15.6068 4.4421C16.1608 5.40087 16.418 6.50218 16.3457 7.60677C16.2735 8.71136 15.8751 9.76962 15.201 10.6477C14.5268 11.5258 13.6071 12.1843 12.5583 12.5399C11.5094 12.8956 10.3785 12.9323 9.3085 12.6456C7.87365 12.2612 6.65019 11.3229 5.90726 10.0372C5.16432 8.75154 4.96277 7.22378 5.34694 5.79003Z" fill="white" />
            </svg>
          </span>
          <form method='get' action="<?php echo base_url('tickets'); ?>" id="searchForm">
            <input type="text" name="search" placeholder="Search for Code" />
            <a href="<?php echo base_url('tickets'); ?>" class="btn new-hosting-div bg-danger clrBtn">Clear</a>
          </form>
        </div>
        <a href="<?= base_url('tickets/add') ?>" class="new-hosting-div" data-bs-toggle="modal" data-bs-target="#add-new-hosting-modal">
          <span>
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
              <path d="M10.6667 10.3333C10.6667 10.5101 10.5964 10.6797 10.4714 10.8047C10.3464 10.9298 10.1768 11 10 11H8.66667V12.3333C8.66667 12.5101 8.59643 12.6797 8.47141 12.8047C8.34638 12.9298 8.17681 13 8 13C7.82319 13 7.65362 12.9298 7.5286 12.8047C7.40357 12.6797 7.33333 12.5101 7.33333 12.3333V11H6C5.82319 11 5.65362 10.9298 5.5286 10.8047C5.40357 10.6797 5.33333 10.5101 5.33333 10.3333C5.33333 10.1565 5.40357 9.98695 5.5286 9.86193C5.65362 9.73691 5.82319 9.66667 6 9.66667H7.33333V8.33333C7.33333 8.15652 7.40357 7.98695 7.5286 7.86193C7.65362 7.73691 7.82319 7.66667 8 7.66667C8.17681 7.66667 8.34638 7.73691 8.47141 7.86193C8.59643 7.98695 8.66667 8.15652 8.66667 8.33333V9.66667H10C10.1768 9.66667 10.3464 9.73691 10.4714 9.86193C10.5964 9.98695 10.6667 10.1565 10.6667 10.3333ZM16 5.66667V12.3333C15.9989 13.2171 15.6474 14.0643 15.0225 14.6892C14.3976 15.3141 13.5504 15.6656 12.6667 15.6667H3.33333C2.4496 15.6656 1.60237 15.3141 0.97748 14.6892C0.352588 14.0643 0.00105857 13.2171 0 12.3333L0 4.33333C0.00105857 3.4496 0.352588 2.60237 0.97748 1.97748C1.60237 1.35259 2.4496 1.00106 3.33333 1H5.01867C5.32893 1.00026 5.63493 1.07236 5.91267 1.21067L8.01667 2.26667C8.10963 2.31128 8.21155 2.33408 8.31467 2.33333H12.6667C13.5504 2.33439 14.3976 2.68592 15.0225 3.31081C15.6474 3.93571 15.9989 4.78294 16 5.66667ZM1.33333 4.33333V5H14.544C14.4066 4.61139 14.1525 4.27473 13.8165 4.03606C13.4804 3.79739 13.0788 3.66838 12.6667 3.66667H8.31467C8.0044 3.66641 7.6984 3.5943 7.42067 3.456L5.31667 2.40333C5.22398 2.35757 5.12204 2.33362 5.01867 2.33333H3.33333C2.8029 2.33333 2.29419 2.54405 1.91912 2.91912C1.54405 3.29419 1.33333 3.8029 1.33333 4.33333ZM14.6667 12.3333V6.33333H1.33333V12.3333C1.33333 12.8638 1.54405 13.3725 1.91912 13.7475C2.29419 14.1226 2.8029 14.3333 3.33333 14.3333H12.6667C13.1971 14.3333 13.7058 14.1226 14.0809 13.7475C14.456 13.3725 14.6667 12.8638 14.6667 12.3333Z" fill="white" />
            </svg>
          </span>
          <p><?= lang('hd_lang.create_ticket') ?></p>
        </a>
        <?php if (!User::is_client()) { ?>
          <?php if ($archive) : ?>
            <a href="<?= base_url('tickets') ?>" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#add-new-hosting-modal" style="font-size: 1.6rem;border-radius: 0.5rem;padding: 0.8rem 1.2rem;display: flex;align-items: center;justify-content: center;gap: 10px;cursor: pointer;color:white !important;">
              <?= lang('hd_lang.view_active') ?></a>
          <?php else : ?>
            <a href="<?= base_url('tickets/view_archive/archive') ?>" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#add-new-hosting-modal" style="font-size: 1.6rem;border-radius: 0.5rem;padding: 0.8rem 1.2rem;display: flex;align-items: center;justify-content: center;gap: 10px;cursor: pointer;color:white !important;">
              <?= lang('hd_lang.view_archive') ?></a>
          <?php endif; ?>
        <?php } ?>
      </div>
    </div>
    <div class="hs-table-wrap">
      <div class="tableInfoHead">
        <div class="showEntriesWrap">
          <span>Show</span>
          <form action="<?php echo base_url('tickets'); ?>" method="get">
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
      <div class="hs-table-overflow">
        <table class="hs-table">
          <tr>
            <th>Sr. No</th>
            <th>
              <?= lang('hd_lang.subject') ?>
            </th>
            <?php if (User::is_admin() || User::is_staff()) { ?>
              <th>
                <?= lang('hd_lang.reporter') ?>
              </th>
            <?php } ?>
            <th class="col-date">
              <?= lang('hd_lang.date') ?>
            </th>
            <th class="col-options no-sort">
              <?= lang('hd_lang.priority') ?>
            </th>

            <th class="col-lg-1">
              <?= lang('hd_lang.department') ?>
            </th>
            <th class="col-lg-1">
              <?= lang('hd_lang.status') ?>
            </th>
            <th>
              <?= lang('hd_lang.options') ?>
            </th>
          </tr>
          <?php
          //$this->load->helper('text');
          helper('text');

          foreach ($tickets as $key => $t) {
            $s_label = 'default';
            // Check if $t is an object or an array
            if (is_object($t)) {
              // If it's an object, use object syntax
              if ($t->status == 'open') {
                $s_label = 'danger';
              } elseif ($t->status == 'closed') {
                $s_label = 'success';
              } elseif ($t->status == 'resolved') {
                $s_label = 'primary';
              }
            } elseif (is_array($t)) {
              // If it's an array, use array syntax
              if ($t['status'] == 'open') {
                $s_label = 'danger';
              } elseif ($t['status'] == 'closed') {
                $s_label = 'success';
              } elseif ($t['status'] == 'resolved') {
                $s_label = 'primary';
              }
            }
          ?>
            <tr>
              <th class="w_5 hidden"></th>
            <tr>
              <td><?= (is_array($t)) ? $t['id'] : $t->id ?></td>
              <td style="border-left: 2px solid <?php echo ((is_array($t) ? $t['status'] : $t->status) == 'closed') ? '#1ab394' : '#F8AC59'; ?>;">
                <?php
                $session = \Config\Services::session();
                // Connect to the database
                $db = \Config\Database::connect();
                $rep = $db->table('hd_ticketreplies')
                  ->where('ticketid', (is_array($t)) ? $t['id'] : $t->id)
                  ->countAllResults();
                if ($rep == 0) { ?>
                  <a class="text-info <?= ((is_array($t) ? $t['status'] : $t->status) == 'closed') ? 'text-lt' : ''; ?>" href="<?= base_url() ?>tickets/view/<?= (is_array($t) ? $t['id'] : $t->id) ?>" data-title="<?= lang('hd_lang.ticket_not_replied') ?>">
                    <?= word_limiter((is_array($t) ? $t['subject'] : $t->subject), 8); ?>
                  </a><br>
                  <?php if ((is_array($t) ? $t['status'] : $t->status) != 'closed') { ?>
                    <span class="text-danger">Pending for <?= Applib::time_elapsed_string(strtotime((is_array($t) ? $t['created'] : $t->created))); ?></span>
                  <?php } ?>
                <?php } else { ?>
                  <a class="text-info <?= ((is_array($t) ? $t['status'] : $t->status) == 'closed') ? 'text-lt' : ''; ?>" href="<?= base_url() ?>tickets/view/<?= (is_array($t) ? $t['id'] : $t->id) ?>">
                    <?= word_limiter((is_array($t) ? $t['subject'] : $t->subject), 8); ?>
                  </a><br>
                <?php } ?>
              </td>
              <?php if (User::is_admin() || User::is_staff()) { ?>
                <td> <?php if ((is_array($t) ? $t['reporter'] : $t->reporter) != NULL) { ?>

                    <a class="pull-left thumb-sm avatar" <?php
                                                          $reporterEmail = '';
                                                          $reporter = (is_array($t)) ? $t['reporter'] : $t->reporter;
                                                          if ($reporter) {
                                                            $userInfo = User::login_info($reporter);
                                                            if ($userInfo) {
                                                              $reporterEmail = $userInfo->email;
                                                            }
                                                          }
                                                          ?> title="<?= $reporterEmail ?>" data-placement="right">

                      <img src="<?php //echo User::avatar_url((is_array($t) ? $t['reporter'] : $t->reporter); 
                                ?>" class="img-rounded radius_6">
                      <?php echo User::displayName((is_array($t)) ? $t['reporter'] : $t->reporter); ?>
                      &nbsp;

                    </a>
                  <?php } else {
                        echo "NULL";
                      } ?>
                </td><?php } ?>
              <td><?= date("D, d M g:i:A", strtotime((is_array($t)) ? $t['created'] : $t->created)); ?><br />
                <span class="text-primary">(<?= Applib::time_elapsed_string(strtotime((is_array($t)) ? $t['created'] : $t->created)); ?>) </span>
              </td>
              <td><span class="label label-<?php if ((is_array($t)) ? $t['priority'] : $t->priority == 'Urgent') {
                                              echo 'danger';
                                            } elseif ((is_array($t)) ? $t['priority'] : $t->priority == 'High') {
                                              echo 'warning';
                                            } else {
                                              echo 'default';
                                            } ?>">
                  <?= (is_array($t)) ? $t['priority'] : $t->priority ?></span></td>
              <td><?php echo App::get_dept_by_id((is_array($t)) ? $t['department'] : $t->department); ?></td>
              <td><?php
                  switch ((is_array($t)) ? $t['status'] : $t->status) {
                    case 'open':
                      $status_lang = 'open';
                      break;
                    case 'closed':
                      $status_lang = 'closed';
                      break;
                    case 'pending':
                      $status_lang = 'pending';
                      break;
                    case 'resolved':
                      $status_lang = 'resolved';
                      break;

                    default:
                      $status_lang = 'active';
                      break;
                  }
                  ?>
                <span class="label label-<?= $s_label ?>">
                  <?= ucfirst(lang($status_lang)) ?>
                </span>
              </td>
              <td>
                <div class="tableIcon">
                  <a href="<?= base_url() ?>tickets/view/<?= (is_array($t)) ? $t['id'] : $t->id ?>"><span class="edit-link">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="16" viewBox="0 0 22 16" fill="none">
                      <path d="M21.1741 7.272C20.3937 5.56533 17.3332 0 10.6666 0C3.99989 0 0.939441 5.56533 0.158997 7.272C0.0542307 7.50071 0 7.74932 0 8.00089C0 8.25246 0.0542307 8.50107 0.158997 8.72978C0.939441 10.4347 3.99989 16 10.6666 16C17.3332 16 20.3937 10.4347 21.1741 8.728C21.2787 8.49954 21.3328 8.25125 21.3328 8C21.3328 7.74875 21.2787 7.50046 21.1741 7.272ZM10.6666 14.2222C5.06033 14.2222 2.44433 9.45244 1.77766 8.00978C2.44433 6.54756 5.06033 1.77778 10.6666 1.77778C16.2594 1.77778 18.8763 6.52711 19.5554 8C18.8763 9.47289 16.2594 14.2222 10.6666 14.2222Z" fill="#1912D3"></path>
                      <path d="M10.6671 3.55566C9.78807 3.55566 8.92879 3.81633 8.1979 4.30469C7.46702 4.79305 6.89736 5.48718 6.56097 6.29929C6.22458 7.11141 6.13657 8.00504 6.30806 8.86718C6.47955 9.72931 6.90284 10.5212 7.52441 11.1428C8.14597 11.7644 8.9379 12.1877 9.80003 12.3592C10.6622 12.5306 11.5558 12.4426 12.3679 12.1062C13.18 11.7699 13.8742 11.2002 14.3625 10.4693C14.8509 9.73842 15.1115 8.87914 15.1115 8.00011C15.1101 6.8218 14.6414 5.69216 13.8082 4.85897C12.9751 4.02578 11.8454 3.55708 10.6671 3.55566ZM10.6671 10.6668C10.1397 10.6668 9.62411 10.5104 9.18558 10.2174C8.74705 9.92434 8.40526 9.50787 8.20342 9.0206C8.00159 8.53333 7.94878 7.99715 8.05167 7.47987C8.15457 6.96259 8.40854 6.48743 8.78148 6.11449C9.15442 5.74155 9.62958 5.48757 10.1469 5.38468C10.6641 5.28179 11.2003 5.3346 11.6876 5.53643C12.1749 5.73826 12.5913 6.08006 12.8844 6.51859C13.1774 6.95712 13.3338 7.47269 13.3338 8.00011C13.3338 8.70735 13.0528 9.38563 12.5527 9.88573C12.0526 10.3858 11.3743 10.6668 10.6671 10.6668Z" fill="#1912D3"></path>
                    </svg>
					  <span class="edit-name">View</span></span>
                  </a>
                  <?php if (User::is_admin()) { ?>
                    <a href="<?= base_url() ?>tickets/edit/<?= (is_array($t)) ? $t['id'] : $t->id ?>"><span class="edit-link">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                        <g clip-path="url(#clip0_1514_208)">
                          <path d="M12.4373 0.619885L4.30927 8.74789C3.99883 9.05665 3.75272 9.42392 3.58519 9.82845C3.41766 10.233 3.33203 10.6667 3.33327 11.1046V11.9999C3.33327 12.1767 3.4035 12.3463 3.52853 12.4713C3.65355 12.5963 3.82312 12.6666 3.99993 12.6666H4.89527C5.33311 12.6678 5.76685 12.5822 6.17137 12.4146C6.57589 12.2471 6.94317 12.001 7.25193 11.6906L15.3799 3.56255C15.7695 3.172 15.9883 2.64287 15.9883 2.09122C15.9883 1.53957 15.7695 1.01044 15.3799 0.619885C14.9837 0.241148 14.4567 0.0297852 13.9086 0.0297852C13.3605 0.0297852 12.8335 0.241148 12.4373 0.619885ZM14.4373 2.61989L6.30927 10.7479C5.93335 11.1215 5.42527 11.3318 4.89527 11.3332H4.6666V11.1046C4.66799 10.5745 4.87831 10.0665 5.25193 9.69055L13.3799 1.56255C13.5223 1.42652 13.7117 1.35061 13.9086 1.35061C14.1055 1.35061 14.2949 1.42652 14.4373 1.56255C14.5772 1.7029 14.6558 1.89301 14.6558 2.09122C14.6558 2.28942 14.5772 2.47954 14.4373 2.61989Z" fill="#1912D3"></path>
                          <path d="M15.3333 5.986C15.1565 5.986 14.987 6.05624 14.8619 6.18126C14.7369 6.30629 14.6667 6.47586 14.6667 6.65267V10H12C11.4696 10 10.9609 10.2107 10.5858 10.5858C10.2107 10.9609 10 11.4696 10 12V14.6667H3.33333C2.8029 14.6667 2.29419 14.456 1.91912 14.0809C1.54405 13.7058 1.33333 13.1971 1.33333 12.6667V3.33333C1.33333 2.8029 1.54405 2.29419 1.91912 1.91912C2.29419 1.54405 2.8029 1.33333 3.33333 1.33333H9.36133C9.53815 1.33333 9.70771 1.2631 9.83274 1.13807C9.95776 1.01305 10.028 0.843478 10.028 0.666667C10.028 0.489856 9.95776 0.320286 9.83274 0.195262C9.70771 0.0702379 9.53815 0 9.36133 0L3.33333 0C2.4496 0.00105857 1.60237 0.352588 0.97748 0.97748C0.352588 1.60237 0.00105857 2.4496 0 3.33333L0 12.6667C0.00105857 13.5504 0.352588 14.3976 0.97748 15.0225C1.60237 15.6474 2.4496 15.9989 3.33333 16H10.8953C11.3333 16.0013 11.7671 15.9156 12.1718 15.7481C12.5764 15.5806 12.9438 15.3345 13.2527 15.024L15.0233 13.252C15.3338 12.9432 15.58 12.576 15.7477 12.1715C15.9153 11.767 16.0011 11.3332 16 10.8953V6.65267C16 6.47586 15.9298 6.30629 15.8047 6.18126C15.6797 6.05624 15.5101 5.986 15.3333 5.986ZM12.31 14.0813C12.042 14.3487 11.7031 14.5337 11.3333 14.6147V12C11.3333 11.8232 11.4036 11.6536 11.5286 11.5286C11.6536 11.4036 11.8232 11.3333 12 11.3333H14.6167C14.5342 11.7023 14.3493 12.0406 14.0833 12.3093L12.31 14.0813Z" fill="#1912D3"></path>
                        </g>
                        <defs>
                          <clipPath id="clip0_1514_208">
                            <rect width="16" height="16" fill="white"></rect>
                          </clipPath>
                        </defs>
                      </svg>
						<span class="edit-name">Edit</span></span>
                    </a>
                    <a href="<?= base_url() ?>tickets/delete/<?= (is_array($t)) ? $t['id'] : $t->id ?>" data-toggle='ajaxModal'>
						<span class="edit-link">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                        <g clip-path="url(#clip0_1514_211)">
                          <path d="M13.9999 2.66667H11.9333C11.7785 1.91428 11.3691 1.23823 10.7741 0.752479C10.179 0.266727 9.43472 0.000969683 8.66659 0L7.33325 0C6.56512 0.000969683 5.8208 0.266727 5.22575 0.752479C4.63071 1.23823 4.22132 1.91428 4.06659 2.66667H1.99992C1.82311 2.66667 1.65354 2.7369 1.52851 2.86193C1.40349 2.98695 1.33325 3.15652 1.33325 3.33333C1.33325 3.51014 1.40349 3.67971 1.52851 3.80474C1.65354 3.92976 1.82311 4 1.99992 4H2.66659V12.6667C2.66764 13.5504 3.01917 14.3976 3.64407 15.0225C4.26896 15.6474 5.11619 15.9989 5.99992 16H9.99992C10.8836 15.9989 11.7309 15.6474 12.3558 15.0225C12.9807 14.3976 13.3322 13.5504 13.3333 12.6667V4H13.9999C14.1767 4 14.3463 3.92976 14.4713 3.80474C14.5963 3.67971 14.6666 3.51014 14.6666 3.33333C14.6666 3.15652 14.5963 2.98695 14.4713 2.86193C14.3463 2.7369 14.1767 2.66667 13.9999 2.66667ZM7.33325 1.33333H8.66659C9.0801 1.33384 9.48334 1.46225 9.821 1.70096C10.1587 1.93967 10.4142 2.27699 10.5526 2.66667H5.44725C5.58564 2.27699 5.84119 1.93967 6.17884 1.70096C6.5165 1.46225 6.91974 1.33384 7.33325 1.33333ZM11.9999 12.6667C11.9999 13.1971 11.7892 13.7058 11.4141 14.0809C11.0391 14.456 10.5304 14.6667 9.99992 14.6667H5.99992C5.46949 14.6667 4.96078 14.456 4.58571 14.0809C4.21063 13.7058 3.99992 13.1971 3.99992 12.6667V4H11.9999V12.6667Z" fill="#1912D3"></path>
                          <path d="M6.66667 11.9998C6.84348 11.9998 7.01305 11.9296 7.13807 11.8046C7.2631 11.6796 7.33333 11.51 7.33333 11.3332V7.33317C7.33333 7.15636 7.2631 6.98679 7.13807 6.86177C7.01305 6.73674 6.84348 6.6665 6.66667 6.6665C6.48986 6.6665 6.32029 6.73674 6.19526 6.86177C6.07024 6.98679 6 7.15636 6 7.33317V11.3332C6 11.51 6.07024 11.6796 6.19526 11.8046C6.32029 11.9296 6.48986 11.9998 6.66667 11.9998Z" fill="#1912D3"></path>
                          <path d="M9.33341 11.9998C9.51023 11.9998 9.67979 11.9296 9.80482 11.8046C9.92984 11.6796 10.0001 11.51 10.0001 11.3332V7.33317C10.0001 7.15636 9.92984 6.98679 9.80482 6.86177C9.67979 6.73674 9.51023 6.6665 9.33341 6.6665C9.1566 6.6665 8.98703 6.73674 8.86201 6.86177C8.73699 6.98679 8.66675 7.15636 8.66675 7.33317V11.3332C8.66675 11.51 8.73699 11.6796 8.86201 11.8046C8.98703 11.9296 9.1566 11.9998 9.33341 11.9998Z" fill="#1912D3"></path>
                        </g>
                        <defs>
                          <clipPath id="clip0_1514_211">
                            <rect width="16" height="16" fill="white"></rect>
                          </clipPath>
                        </defs>
                      </svg>
					<span class="edit-name">Delete</span></span>
                    </a>
                    <?php if ($archive) : ?>
                      <a href="<?= base_url() ?>tickets/archive/<?= (is_array($t)) ? $t['id'] : $t->id ?>/0"><span class="edit-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                          <g clip-path="url(#clip0_1514_206)">
                            <path d="M15.3 6.39996C15.175 6.27498 15.0055 6.20477 14.8287 6.20477C14.6519 6.20477 14.4824 6.27498 14.3573 6.39996L7.09602 13.6926C6.78649 14.0022 6.41902 14.2478 6.01459 14.4153C5.61016 14.5829 5.17668 14.6691 4.73892 14.6692C3.85481 14.6692 3.00689 14.3181 2.38168 13.693C1.75648 13.0678 1.40521 12.22 1.40515 11.3359C1.40508 10.4518 1.75623 9.60383 2.38135 8.97863L9.41402 1.91729C9.79008 1.54722 10.2971 1.34074 10.8247 1.34283C11.3523 1.34491 11.8578 1.5554 12.2309 1.92843C12.604 2.30147 12.8146 2.80683 12.8168 3.33443C12.819 3.86204 12.6127 4.36915 12.2427 4.74529L5.21002 11.8066C5.08318 11.9281 4.91432 11.996 4.73868 11.996C4.56304 11.996 4.39418 11.9281 4.26735 11.8066C4.14237 11.6816 4.07216 11.5121 4.07216 11.3353C4.07216 11.1585 4.14237 10.989 4.26735 10.864L10.5287 4.57396C10.6501 4.44823 10.7173 4.27982 10.7158 4.10503C10.7143 3.93023 10.6442 3.76302 10.5206 3.63942C10.397 3.51581 10.2297 3.4457 10.0549 3.44418C9.88015 3.44266 9.71175 3.50986 9.58602 3.63129L3.32468 9.92129C3.13893 10.107 2.99158 10.3275 2.89105 10.5702C2.79052 10.8129 2.73878 11.073 2.73878 11.3356C2.73878 11.5983 2.79052 11.8584 2.89105 12.1011C2.99158 12.3437 3.13893 12.5642 3.32468 12.75C3.70576 13.1136 4.21227 13.3165 4.73902 13.3165C5.26576 13.3165 5.77227 13.1136 6.15335 12.75L13.1853 5.68796C13.7973 5.06016 14.1374 4.21653 14.1317 3.33981C14.1261 2.46309 13.7753 1.62388 13.1553 1.00398C12.5353 0.384072 11.6961 0.0333909 10.8194 0.0278975C9.94265 0.0224042 9.09906 0.362542 8.47135 0.974627L1.43868 8.03596C0.563468 8.91117 0.0717773 10.0982 0.0717773 11.336C0.0717774 12.5737 0.563468 13.7607 1.43868 14.636C2.3139 15.5112 3.50094 16.0029 4.73868 16.0029C5.97642 16.0029 7.16347 15.5112 8.03868 14.636L15.3 7.34529C15.3623 7.28334 15.4117 7.20969 15.4455 7.12858C15.4792 7.04746 15.4965 6.96048 15.4965 6.87263C15.4965 6.78478 15.4792 6.6978 15.4455 6.61668C15.4117 6.53556 15.3623 6.46191 15.3 6.39996Z" fill="#1912D3" />
                          </g>
                          <defs>
                            <clipPath id="clip0_1514_206">
                              <rect width="16" height="16" fill="white" />
                            </clipPath>
                          </defs>
                        </svg>
						 <span class="edit-name">Archive</span></span>
                      </a>
                    <?php else : ?>
                      <a href="<?= base_url() ?>tickets/archive/<?= (is_array($t)) ? $t['id'] : $t->id ?>/1"><span class="edit-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                          <g clip-path="url(#clip0_1514_202)">
                            <path d="M9.23001 11.5113L7.05535 13.686C6.42396 14.3005 5.57602 14.6417 4.69498 14.6358C3.81394 14.6299 2.97065 14.2773 2.34755 13.6544C1.72446 13.0315 1.37168 12.1883 1.36552 11.3073C1.35937 10.4262 1.70035 9.57819 2.31468 8.94664L4.48935 6.76997C4.61435 6.64488 4.68455 6.47525 4.68448 6.2984C4.68442 6.12155 4.61411 5.95198 4.48901 5.82697C4.36392 5.70197 4.19429 5.63177 4.01745 5.63184C3.8406 5.6319 3.67102 5.70221 3.54601 5.8273L1.37201 8.00397C0.493528 8.8829 0.00017586 10.0748 0.00048843 11.3175C0.000801 12.5602 0.494753 13.7518 1.37368 14.6303C2.25261 15.5088 3.44451 16.0021 4.68719 16.0018C5.92987 16.0015 7.12153 15.5076 8.00001 14.6286L10.1747 12.454C10.2961 12.3282 10.3633 12.1598 10.3618 11.985C10.3603 11.8102 10.2902 11.643 10.1666 11.5194C10.043 11.3958 9.87575 11.3257 9.70095 11.3242C9.52615 11.3227 9.35775 11.3899 9.23201 11.5113H9.23001Z" fill="#1912D3" />
                            <path d="M14.6292 1.37402C14.1954 0.937226 13.6792 0.590869 13.1106 0.355014C12.542 0.119159 11.9322 -0.0015043 11.3166 1.64272e-05C10.7013 -0.00161806 10.0918 0.118735 9.52329 0.354117C8.9548 0.589499 8.43861 0.935241 8.00457 1.37135L5.82657 3.54668C5.70148 3.67169 5.63117 3.84127 5.6311 4.01811C5.63104 4.19496 5.70123 4.36459 5.82624 4.48968C5.95124 4.61478 6.12082 4.68509 6.29767 4.68515C6.47452 4.68521 6.64415 4.61502 6.76924 4.49002L8.94591 2.31535C9.25627 2.00312 9.62549 1.75555 10.0322 1.58699C10.4389 1.41843 10.875 1.33222 11.3152 1.33335C11.9781 1.33357 12.626 1.5303 13.177 1.89868C13.7281 2.26706 14.1575 2.79055 14.4111 3.40296C14.6647 4.01538 14.731 4.68923 14.6017 5.33933C14.4724 5.98944 14.1532 6.58661 13.6846 7.05535L11.5099 9.23002C11.3848 9.35511 11.3145 9.52477 11.3145 9.70168C11.3145 9.87859 11.3848 10.0483 11.5099 10.1734C11.635 10.2984 11.8047 10.3687 11.9816 10.3687C12.1585 10.3687 12.3281 10.2984 12.4532 10.1734L14.6279 8.00002C15.5053 7.12075 15.9981 5.92942 15.9984 4.68729C15.9986 3.44517 15.5062 2.25364 14.6292 1.37402Z" fill="#1912D3" />
                            <path d="M9.52872 5.52845L5.52872 9.52845C5.46505 9.58995 5.41426 9.66351 5.37932 9.74485C5.34438 9.82618 5.32599 9.91366 5.32522 10.0022C5.32445 10.0907 5.34132 10.1785 5.37484 10.2604C5.40836 10.3424 5.45786 10.4168 5.52046 10.4794C5.58305 10.542 5.65749 10.5915 5.73942 10.625C5.82135 10.6585 5.90914 10.6754 5.99765 10.6746C6.08617 10.6738 6.17365 10.6555 6.25499 10.6205C6.33633 10.5856 6.40989 10.5348 6.47139 10.4711L10.4714 6.47112C10.5928 6.34538 10.66 6.17698 10.6585 6.00218C10.657 5.82738 10.5869 5.66018 10.4633 5.53657C10.3397 5.41297 10.1725 5.34285 9.99765 5.34133C9.82286 5.33981 9.65446 5.40701 9.52872 5.52845Z" fill="#1912D3" />
                          </g>
                          <defs>
                            <clipPath id="clip0_1514_202">
                              <rect width="16" height="16" fill="white" />
                            </clipPath>
                          </defs>
                        </svg>
						  <span class="edit-name">Archive</span></span>
                      </a>
                </div>
              <?php endif; ?>
            <?php } ?>
              </td>
            </tr>
          <?php } ?>
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
              <?php if (!empty($servers)) : ?>
                <!-- If there are items, display the pagination links -->
                <?php if ($pager) : ?>
                  <ul class="pagination">
                    <?php
                    $pager->setPath('tickets');

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