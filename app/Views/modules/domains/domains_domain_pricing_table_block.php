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
use App\Models\Item;
use App\ThirdParty\MX\Modules;
/**
* Name: Domain Pricing
* Description: A table of domain extensions and prices.
*/
$domains = Modules::run('domains/domain_pricing', '');
?>
<table class="table table-striped table-bordered AppendDataTables">
    <thead>
        <tr>
            <th><?=lang('hd_lang.extension')?></th>
            <th><?=lang('hd_lang.registration')?></th>
            <th><?=lang('hd_lang.transfer')?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach(Item::get_domains() as $domain) { ?>
        <tr>
            <td><?=$domain->item_name?></td>
            <td><?=AppLib::format_currency(config_item('default_currency'), $domain->registration)?></td>
            <td><?=AppLib::format_currency(config_item('default_currency'), $domain->transfer)?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>