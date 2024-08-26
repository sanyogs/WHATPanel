<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */
/**
* Name: Domain Pricing
* Description: A table of domain extensions and prices.
*/
//$domains = modules::run('domains/domain_pricing', '');
use App\Modules\domains\controllers\Domains;
use App\Helpers\custom_name_helper;
use App\Modules\Item;
use App\Libraries\AppLib;

$helper = new custom_name_helper();
$domain = new Domains();

$domains = $domain->domain_pricing();
?>
<table class="table table-striped table-bordered AppendDataTables">
<thead><tr><th><?=lang('hd_lang.extension')?></th><th><?=lang('hd_lang.registration')?></th><th><?=lang('hd_lang.transfer')?></th></tr></thead>
<tbody>
    <?php foreach(Item::get_domains() as $domain) { ?>
        <tr>
            <td><?=$domain->item_name?></td>
            <td><?=Applib::format_currency($domain->registration, 'default_currency')?></td>
            <td><?=Applib::format_currency($domain->transfer, 'default_currency')?></td>
        </tr>
    <?php } ?>
</tbody>
</table>