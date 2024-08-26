<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

error_reporting(E_ALL);
ini_set("display_errors", "1");

use App\Models\Item;
use App\Helpers\custom_name_helper;

$db = \Config\Database::connect();

$item = Item::view_item($id);

?>
<section id="add-server-modal" class="clm-layout">
    <div class="modal-dialog nh-modal-adjuster modal-xl mx-auto">
        <div class="modal-content nh-modal-content my-modal w-100 h-100">
            <div class="nh-modal-header">
                <div class="nh-modal-title">
                    <h3>Edit Addon</h3>
                    <p>Fill below options to edit Addon</p>
                </div>
                <div class="nh-modal-close">
                    <span data-dismiss="modal">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <path d="M10.4713 6.47133L8.94267 8L10.4713 9.52867C10.732 9.78933 10.732 10.2107 10.4713 10.4713C10.3413 10.6013 10.1707 10.6667 10 10.6667C9.82933 10.6667 9.65867 10.6013 9.52867 10.4713L8 8.94267L6.47133 10.4713C6.34133 10.6013 6.17067 10.6667 6 10.6667C5.82933 10.6667 5.65867 10.6013 5.52867 10.4713C5.268 10.2107 5.268 9.78933 5.52867 9.52867L7.05733 8L5.52867 6.47133C5.268 6.21067 5.268 5.78933 5.52867 5.52867C5.78933 5.268 6.21067 5.268 6.47133 5.52867L8 7.05733L9.52867 5.52867C9.78933 5.268 10.2107 5.268 10.4713 5.52867C10.732 5.78933 10.732 6.21067 10.4713 6.47133ZM16 8C16 12.4113 12.4113 16 8 16C3.58867 16 0 12.4113 0 8C0 3.58867 3.58867 0 8 0C12.4113 0 16 3.58867 16 8ZM14.6667 8C14.6667 4.324 11.676 1.33333 8 1.33333C4.324 1.33333 1.33333 4.324 1.33333 8C1.33333 11.676 4.324 14.6667 8 14.6667C11.676 14.6667 14.6667 11.676 14.6667 8Z" fill="#888888" />
                        </svg>
                    </span>
                </div>
            </div>
            <?php
            $attributes = array('class' => 'bs-example form-horizontal');
            echo form_open(base_url() . 'items/edit_addon/' . $id, $attributes); ?>
            <div class="nh-modal-body h-100">
                <div class="nh-data-wrap">
                    <div class="nh-data-row-1">
                        <input type="hidden" name="quantity" value="1">
                        <input type="hidden" name="r_url" value="<?= base_url("addons/list_items") ?>">
                        <input type="hidden" name="addon" value="1">
                        <input type="hidden" name="item_id" value="<?= $id ?>">
                        <div class="nh-category-select-wrap">
                            <div class="nh-category-title">
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="11" viewBox="0 0 12 11" fill="none">
                                        <path d="M8 7C8 7.13261 7.94732 7.25979 7.85355 7.35355C7.75979 7.44732 7.63261 7.5 7.5 7.5H6.5V8.5C6.5 8.63261 6.44732 8.75979 6.35355 8.85355C6.25979 8.94732 6.13261 9 6 9C5.86739 9 5.74021 8.94732 5.64645 8.85355C5.55268 8.75979 5.5 8.63261 5.5 8.5V7.5H4.5C4.36739 7.5 4.24021 7.44732 4.14645 7.35355C4.05268 7.25979 4 7.13261 4 7C4 6.86739 4.05268 6.74021 4.14645 6.64645C4.24021 6.55268 4.36739 6.5 4.5 6.5H5.5V5.5C5.5 5.36739 5.55268 5.24021 5.64645 5.14645C5.74021 5.05268 5.86739 5 6 5C6.13261 5 6.25979 5.05268 6.35355 5.14645C6.44732 5.24021 6.5 5.36739 6.5 5.5V6.5H7.5C7.63261 6.5 7.75979 6.55268 7.85355 6.64645C7.94732 6.74021 8 6.86739 8 7ZM12 3.5V8.5C11.9992 9.1628 11.7356 9.79822 11.2669 10.2669C10.7982 10.7356 10.1628 10.9992 9.5 11H2.5C1.8372 10.9992 1.20178 10.7356 0.73311 10.2669C0.264441 9.79822 0.000793929 9.1628 0 8.5L0 2.5C0.000793929 1.8372 0.264441 1.20178 0.73311 0.73311C1.20178 0.264441 1.8372 0.000793929 2.5 0H3.764C3.9967 0.000193171 4.2262 0.0542726 4.4345 0.158L6.0125 0.95C6.08222 0.983462 6.15867 1.00056 6.236 1H9.5C10.1628 1.00079 10.7982 1.26444 11.2669 1.73311C11.7356 2.20178 11.9992 2.8372 12 3.5ZM1 2.5V3H10.908C10.805 2.70855 10.6144 2.45605 10.3623 2.27704C10.1103 2.09804 9.80913 2.00128 9.5 2H6.236C6.0033 1.99981 5.7738 1.94573 5.5655 1.842L3.9875 1.0525C3.91798 1.01817 3.84153 1.00022 3.764 1H2.5C2.10218 1 1.72064 1.15804 1.43934 1.43934C1.15804 1.72064 1 2.10218 1 2.5ZM11 8.5V4H1V8.5C1 8.89782 1.15804 9.27936 1.43934 9.56066C1.72064 9.84196 2.10218 10 2.5 10H9.5C9.89782 10 10.2794 9.84196 10.5607 9.56066C10.842 9.27936 11 8.89782 11 8.5Z" fill="#172F78" />
                                    </svg>
                                </span>
                                <p>Add To</p>
                            </div>
                            <div class="nh-category-input-wrap">
                                <select class="select2" id="apply_to" name="apply_to">
                                    <?php

                                    foreach (Item::get_items() as $items) { ?>
                                        <option value="<?= $items->item_id; ?>"><?= $items->item_name; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="nh-category-select-wrap">
                            <div class="nh-category-title">
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="12" viewBox="0 0 13 12" fill="none">
                                        <path d="M12.9995 6C12.9982 5.64824 12.9144 5.30131 12.7544 4.98511C12.5944 4.66891 12.3623 4.39161 12.0755 4.17392C12.4723 3.87257 12.7616 3.45942 12.903 2.9921C13.0444 2.52478 13.0309 2.0266 12.8643 1.56705C12.6977 1.10751 12.3863 0.709516 11.9736 0.428603C11.5609 0.14769 11.0675 -0.00213753 10.5622 2.30425e-05H2.4378C1.93248 -0.00213753 1.43907 0.14769 1.02639 0.428603C0.613703 0.709516 0.302337 1.10751 0.135722 1.56705C-0.0308924 2.0266 -0.0444451 2.52478 0.0969546 2.9921C0.238354 3.45942 0.527654 3.87257 0.924497 4.17392C0.636647 4.39074 0.403835 4.66786 0.243719 4.98425C0.0836042 5.30065 0.00039083 5.64801 0.00039083 6C0.00039083 6.35199 0.0836042 6.69935 0.243719 7.01575C0.403835 7.33214 0.636647 7.60926 0.924497 7.82608C0.527654 8.12743 0.238354 8.54058 0.0969546 9.0079C-0.0444451 9.47522 -0.0308924 9.9734 0.135722 10.4329C0.302337 10.8925 0.613703 11.2905 1.02639 11.5714C1.43907 11.8523 1.93248 12.0021 2.4378 12H10.5622C11.0675 12.0021 11.5609 11.8523 11.9736 11.5714C12.3863 11.2905 12.6977 10.8925 12.8643 10.4329C13.0309 9.9734 13.0444 9.47522 12.903 9.0079C12.7616 8.54058 12.4723 8.12743 12.0755 7.82608C12.3623 7.60839 12.5944 7.33109 12.7544 7.01489C12.9144 6.69869 12.9982 6.35176 12.9995 6ZM1.08373 2.34784C1.08373 2.00191 1.2264 1.67014 1.48033 1.42553C1.73427 1.18092 2.07868 1.0435 2.4378 1.0435H2.70861V1.56523C2.70861 1.70361 2.76568 1.83631 2.86725 1.93416C2.96883 2.032 3.10659 2.08697 3.25024 2.08697C3.39389 2.08697 3.53165 2.032 3.63323 1.93416C3.7348 1.83631 3.79187 1.70361 3.79187 1.56523V1.0435H4.87512V1.56523C4.87512 1.70361 4.93218 1.83631 5.03376 1.93416C5.13533 2.032 5.2731 2.08697 5.41675 2.08697C5.5604 2.08697 5.69816 2.032 5.79973 1.93416C5.90131 1.83631 5.95837 1.70361 5.95837 1.56523V1.0435H10.5622C10.9213 1.0435 11.2657 1.18092 11.5197 1.42553C11.7736 1.67014 11.9163 2.00191 11.9163 2.34784C11.9163 2.69377 11.7736 3.02554 11.5197 3.27015C11.2657 3.51476 10.9213 3.65218 10.5622 3.65218H2.4378C2.07868 3.65218 1.73427 3.51476 1.48033 3.27015C1.2264 3.02554 1.08373 2.69377 1.08373 2.34784ZM11.9163 9.65216C11.9163 9.99809 11.7736 10.3299 11.5197 10.5745C11.2657 10.8191 10.9213 10.9565 10.5622 10.9565H2.4378C2.07868 10.9565 1.73427 10.8191 1.48033 10.5745C1.2264 10.3299 1.08373 9.99809 1.08373 9.65216C1.08373 9.30623 1.2264 8.97446 1.48033 8.72985C1.73427 8.48524 2.07868 8.34782 2.4378 8.34782H2.70861V8.86955C2.70861 9.00793 2.76568 9.14063 2.86725 9.23848C2.96883 9.33632 3.10659 9.39129 3.25024 9.39129C3.39389 9.39129 3.53165 9.33632 3.63323 9.23848C3.7348 9.14063 3.79187 9.00793 3.79187 8.86955V8.34782H4.87512V8.86955C4.87512 9.00793 4.93218 9.14063 5.03376 9.23848C5.13533 9.33632 5.2731 9.39129 5.41675 9.39129C5.5604 9.39129 5.69816 9.33632 5.79973 9.23848C5.90131 9.14063 5.95837 9.00793 5.95837 8.86955V8.34782H10.5622C10.9213 8.34782 11.2657 8.48524 11.5197 8.72985C11.7736 8.97446 11.9163 9.30623 11.9163 9.65216ZM2.4378 7.30434C2.07868 7.30434 1.73427 7.16692 1.48033 6.92231C1.2264 6.6777 1.08373 6.34593 1.08373 6C1.08373 5.65407 1.2264 5.3223 1.48033 5.07769C1.73427 4.83308 2.07868 4.69566 2.4378 4.69566H2.70861V5.21739C2.70861 5.35577 2.76568 5.48847 2.86725 5.58632C2.96883 5.68416 3.10659 5.73913 3.25024 5.73913C3.39389 5.73913 3.53165 5.68416 3.63323 5.58632C3.7348 5.48847 3.79187 5.35577 3.79187 5.21739V4.69566H4.87512V5.21739C4.87512 5.35577 4.93218 5.48847 5.03376 5.58632C5.13533 5.68416 5.2731 5.73913 5.41675 5.73913C5.5604 5.73913 5.69816 5.68416 5.79973 5.58632C5.90131 5.48847 5.95837 5.35577 5.95837 5.21739V4.69566H10.5622C10.9213 4.69566 11.2657 4.83308 11.5197 5.07769C11.7736 5.3223 11.9163 5.65407 11.9163 6C11.9163 6.34593 11.7736 6.6777 11.5197 6.92231C11.2657 7.16692 10.9213 7.30434 10.5622 7.30434H2.4378Z" fill="#172F78" />
                                    </svg>
                                </span>
                                <p>Name</p>
                            </div>
                            <div class="nh-category-input-wrap">
                                <input type="text" placeholder="Name" class="hs-modal-input" id="item_name" value="<?=$item->item_name?>" name="item_name" />
                            </div>
                        </div>
                        <div class="nh-category-select-wrap">
                            <div class="nh-category-title">
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
                                        <path d="M9.75 8C9.75 8.2765 9.5265 8.5 9.25 8.5H8.25C7.9735 8.5 7.75 8.2765 7.75 8C7.75 7.7235 7.9735 7.5 8.25 7.5H9.25C9.5265 7.5 9.75 7.7235 9.75 8ZM12 7.5V10C12 11.103 11.103 12 10 12H2C0.897 12 0 11.103 0 10V7.5C0 6.397 0.897 5.5 2 5.5H2.5V2C2.5 0.897 3.397 0 4.5 0H7.5C8.603 0 9.5 0.897 9.5 2V5.5H10C11.103 5.5 12 6.397 12 7.5ZM3.5 5.5H8.5V2C8.5 1.4485 8.0515 1 7.5 1H4.5C3.9485 1 3.5 1.4485 3.5 2V5.5ZM2 11H5.5V6.5H2C1.4485 6.5 1 6.9485 1 7.5V10C1 10.5515 1.4485 11 2 11ZM11 7.5C11 6.9485 10.5515 6.5 10 6.5H6.5V11H10C10.5515 11 11 10.5515 11 10V7.5ZM3.75 7.5H2.75C2.4735 7.5 2.25 7.7235 2.25 8C2.25 8.2765 2.4735 8.5 2.75 8.5H3.75C4.0265 8.5 4.25 8.2765 4.25 8C4.25 7.7235 4.0265 7.5 3.75 7.5ZM7 2.5C7 2.2235 6.7765 2 6.5 2H5.5C5.2235 2 5 2.2235 5 2.5C5 2.7765 5.2235 3 5.5 3H6.5C6.7765 3 7 2.7765 7 2.5Z" fill="#172F78" />
                                    </svg>
                                </span>
                                <p>Order</p>
                            </div>
                            <div class="nh-category-input-wrap">
                   <input type="text" placeholder="01" class="hs-modal-input" name="order_by" id="order_by" value="<?= $item->order_by; ?>" />
                            </div>
                        </div>
                    </div>
                    <div class="nh-data-row-2">
                        <div class="nh-category-select-wrap">
                            <div class="nh-category-title">
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="11" viewBox="0 0 12 11" fill="none">
                                        <path d="M9.5 0H2.5C1.1215 0 0 1.1215 0 2.5V8.5C0 9.8785 1.1215 11 2.5 11H9.5C10.8785 11 12 9.8785 12 8.5V2.5C12 1.1215 10.8785 0 9.5 0ZM11 8.5C11 9.327 10.327 10 9.5 10H2.5C1.673 10 1 9.327 1 8.5V2.5C1 1.673 1.673 1 2.5 1H9.5C10.327 1 11 1.673 11 2.5V8.5ZM9.5 3C9.5 3.276 9.276 3.5 9 3.5H5.5C5.224 3.5 5 3.276 5 3C5 2.724 5.224 2.5 5.5 2.5H9C9.276 2.5 9.5 2.724 9.5 3ZM4 3C4 3.414 3.664 3.75 3.25 3.75C2.836 3.75 2.5 3.414 2.5 3C2.5 2.586 2.836 2.25 3.25 2.25C3.664 2.25 4 2.586 4 3ZM9.5 5.5C9.5 5.776 9.276 6 9 6H5.5C5.224 6 5 5.776 5 5.5C5 5.224 5.224 5 5.5 5H9C9.276 5 9.5 5.224 9.5 5.5ZM4 5.5C4 5.914 3.664 6.25 3.25 6.25C2.836 6.25 2.5 5.914 2.5 5.5C2.5 5.086 2.836 4.75 3.25 4.75C3.664 4.75 4 5.086 4 5.5ZM9.5 8C9.5 8.276 9.276 8.5 9 8.5H5.5C5.224 8.5 5 8.276 5 8C5 7.724 5.224 7.5 5.5 7.5H9C9.276 7.5 9.5 7.724 9.5 8ZM4 8C4 8.414 3.664 8.75 3.25 8.75C2.836 8.75 2.5 8.414 2.5 8C2.5 7.586 2.836 7.25 3.25 7.25C3.664 7.25 4 7.586 4 8Z" fill="#172F78" />
                                    </svg>
                                </span>
                                <p>Description</p>
                            </div>
                            <div class="nh-category-input-wrap">
                                <textarea cols="85" rows="20" class="hs-modal-input" placeholder="Description" name="item_desc" value='<?= $item->item_desc ?>'><?= $item->item_desc ?></textarea>
                            </div>
                        </div>
                        <!-- <div class="nh-noflex-wrap">
							<div class="nh-category-title">
								<p>Require a Domain</p>
							</div>
							<div class="nh-input-btnwrap">
								<div class="form-check form-switch input-btn-div">
									<input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault" name="require_domain" <?//php if ($item->require_domain == 'Yes') {
										//echo "checked=\"checked\"";} ?> />
								</div>
							</div>
						</div> -->
						<div class="nh-noflex-wrap">
                            <div class="nh-category-title">
                                <p>Display</p>
                            </div>
                            <div class="nh-input-btnwrap">
                                <div class="form-check form-switch input-btn-div">
 <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault" name="display" <?php if ($item->display == 'Yes') {
      echo "checked=\"checked\"";} ?> name="display">
                                </div>
                            </div>
                    	</div>

						<div class="nh-noflex-wra">
							<div class="nh-category-title">
								<p><?= lang('hd_lang.tax_rate') ?></p>
							</div>
							<div class="nh-input-btnwrap">
								<div class="form-check form-switch input-btn-div">
									<input class="form-check-input" id="flexSwitchCheckDefault" type="checkbox" role="switch" <?php if($item->item_tax_rate == 'Yes'){ echo "checked=\"checked\""; } ?> name="item_tax_rate"/>
								</div>
							</div>
						</div>
                    </div>
                    <div class="nh-data-row-3">
                        
                    <div class="nh-data-table-wrap table-responsive">
                        <div class="nh-category-title">
                            <p>Add Pricing</p>
                        </div>
                        <table class="nh-add-pricing">
                            <tr>
                                <th>No</th>
                                <th>Currency</th>
                                <th>Monthly</th>
                                <th>3 Months</th>
                                <th>6 Months</th>
                                <th>Year</th>
                                <th>Biennially</th>
                                <th>Triennially</th>
                                <th>Setup Fee</th>
                                <th>Full Payment</th>
                            </tr>
                            <?php
                            // $currencies = $db->table('hd_currencies')->whereIn('code', ['INR', 'USD', 'EUR'])->get()->getResult();

                            $currencies = json_decode($item->currency_amt);
							
							$custom_name = new custom_name_helper();

							$curr = $custom_name->getconfig_item('default_currency');

							$sys_currencies = $db->table('hd_currencies')->where('status', '1')->get()->getResult();

							// Create a new array with $curr at the top
							$defaultCurrency = (object) ['code' => $curr];

							// Merge $defaultCurrency with $currencies
							$sys_currencies = array_merge([$defaultCurrency], $sys_currencies);

							$matched_currencies = [];
							
							foreach ($currencies as $key => $currency) {
                                    // Check if the currency matches any of the system currencies
                                    foreach ($sys_currencies as $sys_currency) {
                                        if ($key === $sys_currency->code) {
                                            // If a match is found, add it to the matched currencies array
                                            $matched_currencies[$key] = $currency;
                                            break; // Break out of the inner loop since a match is found
                                        }
                                    }
                                }

                            $i = 1;

                            foreach ($matched_currencies as $key => $currency) { ?>
                                <tr>
                                    <td><?= $i; ?></td>
                                    <td><?= $key; ?><input type="hidden" name="currencies[]" value="<?php echo $key; ?>" /></td>
                                    <td><input type="text" style="border: none; text-align: center;" name="monthly_payments[]" placeholder="00.00" class="hs-modal-input" value="<?php echo $currency->monthly; ?>" oninput="validateInput(this)"/></td>
                                    <td><input type="text" style="border: none; text-align: center;" name="quarterly_payments[]" placeholder="00.00" class="hs-modal-input" value="<?php echo $currency->quarterly; ?>" oninput="validateInput(this)"/></td>
                                    <td><input type="text" style="border: none; text-align: center;" name="semi_annually_payments[]" placeholder="00.00" class="hs-modal-input" value="<?php echo $currency->semi_annually; ?>" oninput="validateInput(this)"/></td>
                                    <td><input type="text" style="border: none; text-align: center;" name="annually_payments[]" placeholder="00.00" class="hs-modal-input" value="<?php echo $currency->annually; ?>" oninput="validateInput(this)"/></td>
                                    <td><input type="text" style="border: none; text-align: center;" name="biennially_payments[]" placeholder="00.00" class="hs-modal-input" value="<?php echo $currency->biennially; ?>" oninput="validateInput(this)"/></td>
                                    <td><input type="text" style="border: none; text-align: center;" name="triennially_payments[]" placeholder="00.00" class="hs-modal-input" value="<?php echo $currency->triennially; ?>" oninput="validateInput(this)"/></td>
                                    <?php
                                    $setup_fee = null;
                                        if (is_array($currency->setup_fee)) {
                                            $setup_fee = implode(', ', $currency->setup_fee);
                                        } else {
                                            $setup_fee = $currency->setup_fee;
                                        }
                                        ?>
                                    <td><input type="text" style="border: none; text-align: center;" name="setup_fee[]" placeholder="00.00" value="<?php echo $setup_fee; ?>" oninput="validateInput(this)"/></td>
                                    <td><input type="text" style="border: none; text-align: center;" name="full_payment[]" placeholder="00.00" value="<?php echo $currency->full_payment; ?>" oninput="validateInput(this)"/></td>
                                </tr>
                            <?php $i++;
                            } ?>
                        </table>
                    </div>
                </div>
            </div>
            <div class="nh-modal-footer">
                <div class="nh-footer-btn-wrap">
                    <div class="nh-modal-closebtn" data-dismiss="modal">
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <path d="M10.4713 6.47133L8.94267 8L10.4713 9.52867C10.732 9.78933 10.732 10.2107 10.4713 10.4713C10.3413 10.6013 10.1707 10.6667 10 10.6667C9.82933 10.6667 9.65867 10.6013 9.52867 10.4713L8 8.94267L6.47133 10.4713C6.34133 10.6013 6.17067 10.6667 6 10.6667C5.82933 10.6667 5.65867 10.6013 5.52867 10.4713C5.268 10.2107 5.268 9.78933 5.52867 9.52867L7.05733 8L5.52867 6.47133C5.268 6.21067 5.268 5.78933 5.52867 5.52867C5.78933 5.268 6.21067 5.268 6.47133 5.52867L8 7.05733L9.52867 5.52867C9.78933 5.268 10.2107 5.268 10.4713 5.52867C10.732 5.78933 10.732 6.21067 10.4713 6.47133ZM16 8C16 12.4113 12.4113 16 8 16C3.58867 16 0 12.4113 0 8C0 3.58867 3.58867 0 8 0C12.4113 0 16 3.58867 16 8ZM14.6667 8C14.6667 4.324 11.676 1.33333 8 1.33333C4.324 1.33333 1.33333 4.324 1.33333 8C1.33333 11.676 4.324 14.6667 8 14.6667C11.676 14.6667 14.6667 11.676 14.6667 8Z" fill="#888888" />
                            </svg>
                        </span>
                        <p>Close</p>
                    </div>
                    <button type="submit">
                        <div class="nh-modal-addbtn">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                    <path d="M8 0C6.41775 0 4.87103 0.469192 3.55544 1.34824C2.23985 2.22729 1.21447 3.47672 0.608967 4.93853C0.00346629 6.40034 -0.15496 8.00887 0.153721 9.56072C0.462403 11.1126 1.22433 12.538 2.34315 13.6569C3.46197 14.7757 4.88743 15.5376 6.43928 15.8463C7.99113 16.155 9.59966 15.9965 11.0615 15.391C12.5233 14.7855 13.7727 13.7602 14.6518 12.4446C15.5308 11.129 16 9.58225 16 8C15.9977 5.87897 15.1541 3.84547 13.6543 2.34568C12.1545 0.845885 10.121 0.00229405 8 0ZM8 14.6667C6.68146 14.6667 5.39253 14.2757 4.2962 13.5431C3.19987 12.8106 2.34539 11.7694 1.84081 10.5512C1.33622 9.33305 1.2042 7.9926 1.46143 6.6994C1.71867 5.40619 2.35361 4.2183 3.28596 3.28595C4.21831 2.3536 5.40619 1.71867 6.6994 1.46143C7.99261 1.2042 9.33305 1.33622 10.5512 1.8408C11.7694 2.34539 12.8106 3.19987 13.5431 4.2962C14.2757 5.39253 14.6667 6.68146 14.6667 8C14.6647 9.76751 13.9617 11.4621 12.7119 12.7119C11.4621 13.9617 9.76752 14.6647 8 14.6667ZM11.3333 8C11.3333 8.17681 11.2631 8.34638 11.1381 8.4714C11.013 8.59643 10.8435 8.66667 10.6667 8.66667H8.66667V10.6667C8.66667 10.8435 8.59643 11.013 8.47141 11.1381C8.34638 11.2631 8.17681 11.3333 8 11.3333C7.82319 11.3333 7.65362 11.2631 7.5286 11.1381C7.40357 11.013 7.33334 10.8435 7.33334 10.6667V8.66667H5.33334C5.15653 8.66667 4.98696 8.59643 4.86193 8.4714C4.73691 8.34638 4.66667 8.17681 4.66667 8C4.66667 7.82319 4.73691 7.65362 4.86193 7.52859C4.98696 7.40357 5.15653 7.33333 5.33334 7.33333H7.33334V5.33333C7.33334 5.15652 7.40357 4.98695 7.5286 4.86193C7.65362 4.7369 7.82319 4.66667 8 4.66667C8.17681 4.66667 8.34638 4.7369 8.47141 4.86193C8.59643 4.98695 8.66667 5.15652 8.66667 5.33333V7.33333H10.6667C10.8435 7.33333 11.013 7.40357 11.1381 7.52859C11.2631 7.65362 11.3333 7.82319 11.3333 8Z" fill="white" />
                                </svg>
                            </span>
                            <p>Edit Addons</p>
                        </div>
                    </button>
                </div>
            </div>
            </form>
        </div>
    </div>
    <script type="text/javascript">
        $('.select2').select2();
    </script>
	<script>
		function validateInput(input) {
			// Regular expression to allow partial and complete matches
			const partialRegex = /^[1-9][0-9]{0,5}(\.[0-9]{0,2})?$/;
			// Regular expression to strictly validate the complete input
			const strictRegex = /^[1-9][0-9]{5}(\.[0-9]{2})?$/;

			if (!partialRegex.test(input.value)) {
				input.value = input.value.slice(0, -1); // Remove last character if it doesn't match
			} else if (strictRegex.test(input.value)) {
				// If the complete value matches strict validation, do nothing
			} else if (input.value && parseFloat(input.value) > 999999.00) {
				input.value = input.value.slice(0, -1); // Remove last character if it exceeds the max range
			}
		}
	</script>
</section>