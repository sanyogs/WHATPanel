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
use App\Models\Client;
use App\Models\Invoice;
use App\Helpers\AuthHelper;
use App\Models\Item;
use App\Models\Plugin;
use App\Models\User;

use App\Helpers\custom_name_helper;

$custom_name_helper = new custom_name_helper();
$session = session();
    $successResponse = $session->getFlashdata('successResponse');
    if ($successResponse) {
        $successResponse = json_decode($successResponse, true);
        echo '<div class="alert alert-success">' . $successResponse['message'] . '</div>';
    }
?>

<?php $client = AuthHelper::is_client();?>

<?= $this->extend('layouts/users') ?>

<?= $this->section('content') ?>
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

.clrBtn{	
  font-size: 1.6rem !important;
  border-radius: 0.5rem !important;
  padding: 0.8rem 1.2rem !important;
  text-align: center !important;
  color: #fff !important;
  cursor: pointer !important;
  border: 0 !important;
}
</style>

<section id="orders-wrap">
	<div class="container px-0">
		<div class="orders-topbar-wrap">
			<div class="orders-title-wrap">
				<h3>Invoice List</h3>
				<p>Showing Invoice list</p>
			</div>
			<div class="orders-search-wrap">
				<div class="orders-InputWrap">
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
					<form method='get' action="<?php echo base_url('invoices'); ?>" id="searchForm">
						<input type="text" name="search" placeholder="Search for Code" />
						<a href="<?php echo base_url('invoices'); ?>" class="btn new-hosting-div bg-danger clrBtn">Clear</a>
					</form>
				</div>
				<?php
				$session = \Config\Services::session();
				$userdata = $session->get('userdata');
				//print_r($userdata);die;
			    //if(User::is_admin() || User::perm_allowed(User::get_id(),'add_invoices')) { ?>
				<?php  if($userdata['role_id'] == 1) {
             	?>
				<a href="<?=base_url('invoices/add')?>" class="common-button">
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
					<?=lang('hd_lang.create_invoice')?>
				</a>
				<?php } ?>
			</div>
		</div>
		<div class="orders-table-wrap">
			<div class="orders-table-infoHead">
				<div class="showOrderEntries">
					<span>Show</span>
					<form action="<?php echo base_url('invoices'); ?>" method="get">
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
				<div class="orderFilterWrap">
					<div class="order-dateWrap">
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
						<input type="date" class="datepicker" />
					</div>
				</div>
			</div>
			<div class="order-table-overflow">
				<table class="order-table">
					<tr>
                        <th><?=lang('hd_lang.invoice')?></th>
                        <th><?=lang('hd_lang.client_name')?></th>
                        <th><?=lang('hd_lang.status')?></th>
                        <th class="col-date"><?=lang('hd_lang.date')?></th>
                        <th class="col-date"><?=lang('hd_lang.due_date')?></th>
                        <th class="col-currency"><?=lang('hd_lang.sub_total')?></th>
                        <th class="col-currency"><?=lang('hd_lang.due_amount')?></th>
                        <th><?=lang('hd_lang.options')?></th>
					</tr>
					
					<?php
                    $session = \Config\Services::session();

                    // Connect to the database
                    $dbName = \Config\Database::connect();

                    $invoiceModel = new Invoice();
                    $clientModel = new Client();

                  foreach ($invoices as $key => &$inv) 
                  {
                    $status = $invoiceModel::payment_status($inv->inv_id);
                    switch ($status) 
                    {
                        case 'fully_paid': $label2 = 'success';  break;
                        case 'partially_paid': $label2 = 'warning'; break;
                        case 'not_paid': $label2 = 'danger'; break;
                        case 'cancelled': $label2 = 'primary'; break;
                    }
                  ?>
					<tr class="<?=($inv->status == 'Cancelled') ? 'text-danger' : '';?>">
						
						<td style="border-left: 2px solid <?php echo ($status == 'fully_paid') ? '#1ab394' :'#f0ad4e'; ?>">
                            <a class="text-info" href="<?=base_url()?>invoices/view/<?=$inv->inv_id?>">
                                <?=$inv->reference_no?>
                            </a>
                        </td>
						<td>
					    <?php 
					    $client = $clientModel::view_by_id($inv->client);
					    echo is_object($client) ? $clientModel::view_by_id($inv->client)->company_name : '';?>
                        </td>
						<td class="">
                            <span class="label label-<?=$label2?>"><?=lang($status)?>
                                <?php if($inv->emailed == 'Yes') { ?><i class="fa fa-envelope-o"></i><?php } ?></span>
                            <?php if ($inv->recurring == 'Yes') { ?>
                            <span class="label label-primary"><i class="fa fa-retweet"></i></span>
                            <?php }  ?>
                        </td>
						<td><?=strftime($custom_name_helper->getconfig_item('date_format'), strtotime($inv->date_saved))?></td>

                        <td><?=strftime($custom_name_helper->getconfig_item('date_format'), strtotime($inv->due_date))?></td>

                        <td class="col-currency">
                            <?php if($client) {
							$client_cur = Client::view_by_id($inv->client);
						echo Applib::format_currency(Applib::client_currency($client_cur->currency, Invoice::get_invoice_subtotal($inv->inv_id)), 'default_currency');
                      }
                      else{
					 	echo Applib::format_currency(Invoice::get_invoice_subtotal($inv->inv_id), 'default_currency');
                      } ?>
                        </td>

                        <td class="col-currency">
                            <?php if($client) {
                        $client_cur = Client::view_by_id($inv->client);
						  echo Applib::format_currency(Applib::client_currency($client_cur->currency, Invoice::get_invoice_due_amount($inv->inv_id)), 'default_currency');
                      }
                      else{
						  echo Applib::format_currency(Invoice::get_invoice_due_amount($inv->inv_id), 'default_currency');
                      } ?>

                        </td>
						<td>
                          <div class="orderTableIcon">
							<a href="<?=base_url()?>invoices/view/<?=$inv->inv_id?>" data-original-title="<?= lang('hd_lang.view') ?>" class="edit-link">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="16" viewBox="0 0 22 16" fill="none">
                              <path d="M21.1741 7.272C20.3937 5.56533 17.3332 0 10.6666 0C3.99989 0 0.939441 5.56533 0.158997 7.272C0.0542307 7.50071 0 7.74932 0 8.00089C0 8.25246 0.0542307 8.50107 0.158997 8.72978C0.939441 10.4347 3.99989 16 10.6666 16C17.3332 16 20.3937 10.4347 21.1741 8.728C21.2787 8.49954 21.3328 8.25125 21.3328 8C21.3328 7.74875 21.2787 7.50046 21.1741 7.272ZM10.6666 14.2222C5.06033 14.2222 2.44433 9.45244 1.77766 8.00978C2.44433 6.54756 5.06033 1.77778 10.6666 1.77778C16.2594 1.77778 18.8763 6.52711 19.5554 8C18.8763 9.47289 16.2594 14.2222 10.6666 14.2222Z" fill="#1912D3"/>
                              <path d="M10.6671 3.55566C9.78807 3.55566 8.92879 3.81633 8.1979 4.30469C7.46702 4.79305 6.89736 5.48718 6.56097 6.29929C6.22458 7.11141 6.13657 8.00504 6.30806 8.86718C6.47955 9.72931 6.90284 10.5212 7.52441 11.1428C8.14597 11.7644 8.9379 12.1877 9.80003 12.3592C10.6622 12.5306 11.5558 12.4426 12.3679 12.1062C13.18 11.7699 13.8742 11.2002 14.3625 10.4693C14.8509 9.73842 15.1115 8.87914 15.1115 8.00011C15.1101 6.8218 14.6414 5.69216 13.8082 4.85897C12.9751 4.02578 11.8454 3.55708 10.6671 3.55566ZM10.6671 10.6668C10.1397 10.6668 9.62411 10.5104 9.18558 10.2174C8.74705 9.92434 8.40526 9.50787 8.20342 9.0206C8.00159 8.53333 7.94878 7.99715 8.05167 7.47987C8.15457 6.96259 8.40854 6.48743 8.78148 6.11449C9.15442 5.74155 9.62958 5.48757 10.1469 5.38468C10.6641 5.28179 11.2003 5.3346 11.6876 5.53643C12.1749 5.73826 12.5913 6.08006 12.8844 6.51859C13.1774 6.95712 13.3338 7.47269 13.3338 8.00011C13.3338 8.70735 13.0528 9.38563 12.5527 9.88573C12.0526 10.3858 11.3743 10.6668 10.6671 10.6668Z" fill="#1912D3"/>
                              </svg>
							<span class="edit-name">View</span>
                            </a>
							  <?php //if(User::is_admin() || User::perm_allowed(User::get_id(),'edit_all_invoices')) { ?>
                                <?php if(User::is_admin()) { ?>
							  <a href="<?=base_url()?>invoices/edit/<?=$inv->inv_id?>" data-original-title="<?= lang('hd_lang.edit') ?>" class="edit-link">
								  <span>
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
								  </span>
								 <span class="edit-name">Edit</span>
							  </a>
							  <?php } ?>
							  <a href="<?=base_url()?>invoices/transactions/<?=$inv->inv_id?>" data-original-title="<?= lang('hd_lang.payments') ?>" class="edit-link">
								  <svg width="22" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M18.0002 12C14.6862 12 12.0002 14.686 12.0002 18C12.0002 21.314 14.6862 24 18.0002 24C21.3142 24 24.0002 21.314 24.0002 18C24.0002 14.686 21.3142 12 18.0002 12ZM21.3013 19.935C21.1483 21.015 20.1932 21.8 19.1022 21.8H18.8223V22.208C18.8223 22.645 18.4692 23 18.0322 23H18.0122C17.5762 23 17.2232 22.644 17.2232 22.208V21.8H17.0233C16.3123 21.8 15.6753 21.475 15.2533 20.967C14.8353 20.463 15.1903 19.701 15.8453 19.701C16.0723 19.701 16.3553 19.808 16.5093 19.975C16.6373 20.114 16.8213 20.201 17.0243 20.201H19.1273C19.4053 20.201 19.6592 20.014 19.7132 19.741C19.7762 19.423 19.5672 19.126 19.2622 19.064L16.4713 18.505C15.3593 18.282 14.5833 17.224 14.7463 16.066C14.8983 14.986 15.8542 14.2 16.9452 14.2H17.2253V13.8C17.2253 13.358 17.5832 13 18.0252 13C18.4672 13 18.8253 13.358 18.8253 13.8V14.2H19.0252C19.7362 14.2 20.3732 14.524 20.7952 15.033C21.2132 15.537 20.8582 16.299 20.2032 16.299C19.9762 16.299 19.6932 16.192 19.5392 16.025C19.4112 15.886 19.2273 15.799 19.0243 15.799H16.9223C16.6443 15.799 16.3902 15.985 16.3352 16.257C16.2712 16.575 16.4813 16.874 16.7843 16.935L19.5763 17.494C20.6883 17.716 21.4653 18.776 21.3013 19.933V19.935ZM24.0002 2V6C24.0002 6.552 23.5532 7 23.0002 7C22.4472 7 22.0002 6.552 22.0002 6V3.414L17.9382 7.476C16.5942 8.82 14.4072 8.821 13.0632 7.476L10.5243 4.938C9.96025 4.374 9.04125 4.374 8.47625 4.938L1.70725 11.707C1.51225 11.902 1.25625 12 1.00025 12C0.74425 12 0.48825 11.902 0.29325 11.707C-0.09775 11.316 -0.09775 10.684 0.29325 10.293L7.06225 3.524C8.40625 2.179 10.5942 2.179 11.9382 3.524L14.4773 6.062C15.0423 6.628 15.9613 6.625 16.5243 6.062L20.5863 2H18.0002C17.4472 2 17.0002 1.552 17.0002 1C17.0002 0.448 17.4472 0 18.0002 0H22.0002C23.1032 0 24.0002 0.897 24.0002 2ZM3.00025 15V23C3.00025 23.553 2.55225 24 2.00025 24C1.44825 24 1.00025 23.553 1.00025 23V15C1.00025 14.447 1.44825 14 2.00025 14C2.55225 14 3.00025 14.447 3.00025 15ZM8.00025 10.5V23C8.00025 23.553 7.55225 24 7.00025 24C6.44825 24 6.00025 23.553 6.00025 23V10.5C6.00025 9.948 6.44825 9.5 7.00025 9.5C7.55225 9.5 8.00025 9.948 8.00025 10.5ZM12.0002 13C11.4482 13 11.0002 12.552 11.0002 12V10C11.0002 9.448 11.4482 9 12.0002 9C12.5522 9 13.0002 9.448 13.0002 10V12C13.0002 12.552 12.5522 13 12.0002 13Z" fill="#1912D3"/>
									</svg>
								 <span class="edit-name">Transactions</span>
							  </a>
							 <?php //if(User::is_admin() || User::perm_allowed(User::get_id(),'email_invoices')) { ?>
                                <?php if(User::is_admin()) { ?>
                            <a href="<?=base_url()?>invoices/send_invoice/<?=$inv->inv_id?>" data-original-title="<?=lang('hd_lang.email_invoice')?>" data-toggle="ajaxModal" class="edit-link">
                              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="16" viewBox="0 0 18 16" fill="none">
                                <path d="M14.25 0H3.75C2.7558 0.00115481 1.80267 0.384641 1.09966 1.06634C0.396661 1.74804 0.00119089 2.67229 0 3.63636L0 12.3636C0.00119089 13.3277 0.396661 14.252 1.09966 14.9337C1.80267 15.6154 2.7558 15.9988 3.75 16H14.25C15.2442 15.9988 16.1973 15.6154 16.9003 14.9337C17.6033 14.252 17.9988 13.3277 18 12.3636V3.63636C17.9988 2.67229 17.6033 1.74804 16.9003 1.06634C16.1973 0.384641 15.2442 0.00115481 14.25 0ZM3.75 1.45455H14.25C14.6991 1.4554 15.1376 1.58656 15.5092 1.83113C15.8808 2.0757 16.1684 2.42251 16.335 2.82691L10.5915 8.39709C10.1688 8.80532 9.59656 9.03452 9 9.03452C8.40344 9.03452 7.83118 8.80532 7.4085 8.39709L1.665 2.82691C1.83161 2.42251 2.11921 2.0757 2.49079 1.83113C2.86236 1.58656 3.30091 1.4554 3.75 1.45455ZM14.25 14.5455H3.75C3.15326 14.5455 2.58097 14.3156 2.15901 13.9064C1.73705 13.4972 1.5 12.9423 1.5 12.3636V4.72727L6.348 9.42546C7.05197 10.1064 8.00569 10.4888 9 10.4888C9.99431 10.4888 10.948 10.1064 11.652 9.42546L16.5 4.72727V12.3636C16.5 12.9423 16.2629 13.4972 15.841 13.9064C15.419 14.3156 14.8467 14.5455 14.25 14.5455Z" fill="#1912D3"/>
                                </svg>
								<span class="edit-name">Send Invoice</span>
                            </a>
                            <?php } ?>
							  
							<?php //if(User::is_admin() || User::perm_allowed(User::get_id(),'send_email_reminders')) : ?>
                                <?php if(User::is_admin()) : ?>
                            <a href="<?=base_url()?>invoices/remind/<?=$inv->inv_id?>" data-original-title="<?=lang('hd_lang.send_reminder')?>" data-toggle="ajaxModal" class="edit-link">
                              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="16" viewBox="0 0 14 16" fill="none">
                                <path d="M13.882 9.10545L12.6591 4.54864C12.3007 3.21368 11.5217 2.04112 10.446 1.21733C9.37029 0.393548 8.05957 -0.0341803 6.72219 0.00213564C5.38482 0.0384516 4.09754 0.536728 3.06498 1.41776C2.03241 2.29879 1.31382 3.51202 1.02342 4.8646L0.0766803 9.2741C-0.0278955 9.76133 -0.0254777 10.2667 0.0837553 10.7528C0.192988 11.2389 0.406247 11.6935 0.707787 12.0828C1.00933 12.4722 1.39145 12.7864 1.82594 13.0023C2.26043 13.2182 2.7362 13.3303 3.21811 13.3303H3.93509C4.08281 14.0837 4.47747 14.7611 5.05222 15.2477C5.62697 15.7342 6.34645 16 7.08875 16C7.83106 16 8.55054 15.7342 9.12528 15.2477C9.70003 14.7611 10.0947 14.0837 10.2424 13.3303H10.7818C11.2779 13.3303 11.7672 13.2116 12.2117 12.9833C12.6561 12.755 13.0436 12.4234 13.3438 12.0144C13.6441 11.6053 13.8489 11.1299 13.9424 10.6253C14.0358 10.1207 14.0148 9.60052 13.882 9.10545ZM7.08875 14.6635C6.69084 14.6618 6.30315 14.5328 5.97873 14.2941C5.65431 14.0555 5.409 13.7189 5.27636 13.3303H8.90114C8.76851 13.7189 8.5232 14.0555 8.19878 14.2941C7.87435 14.5328 7.48667 14.6618 7.08875 14.6635ZM12.3187 11.2072C12.1393 11.4536 11.907 11.6533 11.6401 11.7904C11.3732 11.9276 11.079 11.9983 10.7811 11.9971H3.21811C2.929 11.9971 2.64358 11.9298 2.38293 11.8002C2.12229 11.6706 1.89306 11.4821 1.71218 11.2485C1.5313 11.0149 1.40338 10.7422 1.33786 10.4505C1.27234 10.1589 1.2709 9.8557 1.33364 9.5634L2.27974 5.15323C2.5078 4.09083 3.07221 3.13787 3.88325 2.44584C4.69429 1.75382 5.7054 1.36245 6.75586 1.33395C7.80632 1.30545 8.83583 1.64145 9.68074 2.28854C10.5257 2.93564 11.1375 3.85668 11.4189 4.90526L12.6418 9.46207C12.7226 9.75895 12.7354 10.0713 12.6794 10.3742C12.6233 10.6771 12.4998 10.9623 12.3187 11.2072Z" fill="#1912D3"/>
                                </svg>
							<span class="edit-name">Reminder</span>
                            </a>
							<?php endif; ?>
                            <a href="<?=base_url()?>invoices/pdf/<?=$inv->inv_id?>" data-original-title="<?=lang('hd_lang.pdf') ?>" class="edit-link">
                              <svg xmlns="http://www.w3.org/2000/svg" width="13" height="16" viewBox="0 0 13 16" fill="none">
                                <path d="M11.6675 3.69333L9.399 1.36667C8.541 0.486667 7.397 0 6.1815 0H3.25C1.456 0 0 1.49333 0 3.33333V12.6667C0 14.5067 1.456 16 3.25 16H9.75C11.544 16 13 14.5067 13 12.6667V6.99333C13 5.74667 12.5255 4.57333 11.6675 3.69333ZM10.751 4.63333C10.959 4.84667 11.1345 5.08 11.2775 5.33333H8.4565C8.099 5.33333 7.8065 5.03333 7.8065 4.66667V1.77333C8.0535 1.92 8.281 2.1 8.489 2.31333L10.7575 4.64L10.751 4.63333ZM11.7 12.6667C11.7 13.7667 10.8225 14.6667 9.75 14.6667H3.25C2.1775 14.6667 1.3 13.7667 1.3 12.6667V3.33333C1.3 2.23333 2.1775 1.33333 3.25 1.33333H6.1815C6.2855 1.33333 6.396 1.33333 6.5 1.34667V4.66667C6.5 5.76667 7.3775 6.66667 8.45 6.66667H11.687C11.7 6.77333 11.7 6.88 11.7 6.99333V12.6667ZM3.3085 8.66667H2.6C2.2425 8.66667 1.95 8.96667 1.95 9.33333V12.2933C1.95 12.5267 2.132 12.7067 2.353 12.7067C2.574 12.7067 2.756 12.52 2.756 12.2933V11.48H3.302C4.069 11.48 4.693 10.8467 4.693 10.0733C4.693 9.3 4.069 8.66667 3.302 8.66667H3.3085ZM3.3085 10.6467H2.769V9.5H3.315C3.627 9.5 3.8935 9.76 3.8935 10.0733C3.8935 10.3867 3.627 10.6467 3.315 10.6467H3.3085ZM11.063 9.08667C11.063 9.32 10.881 9.5 10.66 9.5H9.5615V10.26H10.3675C10.595 10.26 10.7705 10.4467 10.7705 10.6733C10.7705 10.9 10.5885 11.0867 10.3675 11.0867H9.5615V12.2867C9.5615 12.52 9.3795 12.7 9.1585 12.7C8.9375 12.7 8.7555 12.5133 8.7555 12.2867V9.08C8.7555 8.84667 8.9375 8.66667 9.1585 8.66667H10.66C10.8875 8.66667 11.063 8.85333 11.063 9.08V9.08667ZM6.5585 8.67333H5.85C5.4925 8.67333 5.2 8.97333 5.2 9.34V12.3C5.2 12.5333 5.382 12.6733 5.603 12.6733C5.824 12.6733 6.552 12.6733 6.552 12.6733C7.319 12.6733 7.943 12.04 7.943 11.2667V10.08C7.943 9.30667 7.319 8.67333 6.552 8.67333H6.5585ZM7.137 11.2667C7.137 11.58 6.8705 11.84 6.5585 11.84H6.019V9.50667H6.565C6.877 9.50667 7.1435 9.76667 7.1435 10.08V11.2667H7.137Z" fill="#1912D3"/>
                                </svg>
							<span class="edit-name">PDF</span>
                            </a>
							  <?php //if(User::is_admin() || User::perm_allowed(User::get_id(),'delete')) { ?>
                                <?php if(User::is_admin()) { ?>
							  <a href="<?= base_url() ?>invoices/delete/<?= $inv->inv_id ?>" data-original-title="<?=lang('hd_lang.delete')?>" data-toggle="ajaxModal" class="edit-link">
								  <span>
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
								  </span>
								 <span class="edit-name">Delete</span>
							  </a>
							 <?php } ?>
                          </div>
                        </td>
					</tr>
				<?php } ?>
				</table>
			</div>
			<div class="order-table-pagination">
				 <div class="showingEntriesWrap">
					 <?php
					 $totalItems = $pager->getTotal(); // Get total number of items from the pager
					 $currentStart = ($pager->getCurrentPage() - 1) * $perPage + 1; // Calculate the start index of the current page
					 $currentEnd = min($currentStart + $perPage - 1, $totalItems); // Calculate the end index of the current page

					 $showEntry = "Showing $currentStart to $currentEnd of $totalItems entries";

					 ?>
					 <p><?= $showEntry; ?></p>
				</div>
				<div class="order-pagination-wrap">
					<ul class="order-pagination ">
						<div class="row">
							<?php if (!empty($servers)) : ?>
							<!-- If there are items, display the pagination links -->
							<?php if ($pager) : ?>
							<ul class="pagination">
								<?php
								$pager->setPath('invoices');

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
<script>
// Get the date input field and the table
const dateInput = document.querySelector('.datepicker');
const table = document.querySelector('.order-table');

// Add an event listener to the date input field
dateInput.addEventListener('change', filterTable);

// Function to filter the table data
function filterTable() {
  // Get the selected date
  const selectedDate = dateInput.value;

  // Get all the table rows
  const rows = table.querySelectorAll('tr');

  // Loop through each row
  rows.forEach((row) => {
    // Get the date column in the current row
    const dateColumn = row.querySelector('.col-date');

    // If the date column exists
    if (dateColumn) {
      // Get the date text
      const dateText = dateColumn.textContent;

      // If the date text matches the selected date
      if (dateText === selectedDate) {
        // Show the row
        row.style.display = 'table-row';
      } else {
        // Hide the row
        row.style.display = 'none';
      }
    }
  });
}
</script>
<?= $this->endSection() ?>