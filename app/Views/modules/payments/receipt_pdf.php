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
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Payment;

use App\Helpers\custom_name_helper;

$custom = new custom_name_helper();

function stripAccents($string) {
    $chars = array("Ά"=>"Α","ά"=>"α","Έ"=>"Ε","έ"=>"ε","Ή"=>"Η","ή"=>"η","Ί"=>"Ι","ί"=>"ι","Ό"=>"Ο","ό"=>"ο","Ύ"=>"Υ","ύ"=>"υ","Ώ"=>"Ω","ώ"=>"ω");
    foreach ($chars as $find => $replace) {
        $string = str_replace($find, $replace, $string);
    }
    return $string;
}

$ratio = 1.3;
$logo_height = intval($custom->getconfig_item('invoice_logo_height') / $ratio);
$logo_width = intval($custom->getconfig_item('invoice_logo_width') / $ratio);
$color = '#656D78';

$pay = Payment::view_by_id($id);
$client = Client::view_by_id($pay->paid_by);
$l = $client->language;
// $lang2 = $this->lang->load('hd_lang', $l, TRUE, FALSE, '', TRUE); ?>
<html>

<head>
    <style>
    body {
        font-family: 'opensans';
        font-size: 10pt;
        line-height: 13pt;
        color: #777777;
    }

    p {
        margin: 4pt 0 0 0;
    }

    td {
        vertical-align: top;
    }

    .items td {
        border: 0.2mm solid #ffffff;
        background-color: #F5F5F5;
    }

    table thead td {
        vertical-align: bottom;
        text-align: center;
        text-transform: uppercase;
        font-size: 7pt;
        font-weight: bold;
        background-color: #FFFFFF;
        color: #111111;
    }

    table thead td {
        border-bottom: 0.2mm solid <?=$color?>;
    }

    table .last td {
        border-bottom: 0.2mm solid <?=$color?>;
    }

    table .first td {
        border-top: 0.2mm solid <?=$color?>;
    }

    .watermark {
        text-transform: uppercase;
        font-weight: bold;
        position: absolute;
        left: 100px;
        top: 400px;
    }
    </style>
</head>

<body>
    <?php
$watermark = Invoice::payment_status($pay->invoice);
$watermark = stripAccents(mb_strtoupper($watermark));
?>
    <watermarktext content="" alpha="0.05" />
    <htmlpageheader name="myheader">
        <div>
            <table width="100%">
                <tr>
                    <td width="60%" height="<?=$logo_height?>">
                        <img style="height: <?=$logo_height?>px; width: <?=$logo_width?>px;"
                            src="<?=base_url()?>resource/images/logos/<?= $custom->getconfig_item('invoice_logo') ?>" />
                    </td>
                    <td width="40%" style="text-align: right;">
                        <div style="font-weight: bold; color: #111111; font-size: 20pt; text-transform: uppercase;">
                            <?=stripAccents('receipt')?></div>
                        <table>
                            <tr>
                                <td width="10%">&nbsp;</td>
                                <td width="55%"
                                    style="color: <?=$color?>; text-align: left; font-size: 9pt; text-transform: uppercase;">
                                    <?=stripAccents('trans_id')?>:</td>
                                <td width="30%" style="text-align: right; font-size: 9pt;"><?= $pay->trans_id ?></td>
                            </tr>
                            <tr>
                                <td width="10%">&nbsp;</td>
                                <td width="55%"
                                    style="color: <?=$color?>; text-align: left; font-size: 9pt; text-transform: uppercase;">
                                    <?=stripAccents('payment_date')?>:</td>
                                <td width="30%" style="text-align: right; font-size: 9pt;">
                                    <?= strftime($custom->getconfig_item('date_format'), strtotime($pay->payment_date)); ?></td>
                            </tr>
                            <tr>
                                <td width="10%">&nbsp;</td>
                                <td width="55%"
                                    style="color: <?=$color?>; text-align: left; font-size: 9pt; text-transform: uppercase;">
                                    <?=stripAccents('payment_method')?>:</td>
                                <td width="30%" style="text-align: right; font-size: 9pt;">
                                    <?=App::get_method_by_id($pay->payment_method) ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </htmlpageheader>

    <htmlpagefooter name="myfooter">
        <div style="font-size: 9pt; text-align: left; padding-top: 3mm; width:40%; float:left;">
            <?=nl2br($custom->getconfig_item('invoice_footer'))?>
        </div>
        <div style="font-size: 9pt; text-align: right; padding-top: 3mm; width:40%; float:right;">
            <?='page'?> {PAGENO} <?='page_of'?> {nb}
        </div>
    </htmlpagefooter>

    <sethtmlpageheader name="myheader" value="on" show-this-page="1" />
    <sethtmlpagefooter name="myfooter" value="on" />

    <div style="height:<?=$logo_height?>px;">&nbsp;</div>
    <div style="margin-bottom: 20px; margin-top: 60px;">
        <table width="100%" cellpadding="10" style="vertical-align: top;">
            <tr>

                <?php if ($custom->getconfig_item('swap_to_from') == 'FALSE') { ?>
                <td width="45%"
                    style="border-bottom:0.2mm solid <?=$color?>; font-size: 9pt; font-weight:bold; color: <?=$color?>; text-transform: uppercase;">
                    <?= stripAccents('received_from') ?></td>
                <td width="10%">&nbsp;</td>
                <?php } ?>

                <td width="45%"
                    style="border-bottom:0.2mm solid <?=$color?>; font-size: 9pt; font-weight:bold; color: <?=$color?>; text-transform: uppercase;">
                    <?= stripAccents('bill_to') ?></td>


                <?php if ($custom->getconfig_item('swap_to_from') == 'TRUE') { ?>
                <td width="10%">&nbsp;</td>
                <td width="45%"
                    style="border-bottom:0.2mm solid <?=$color?>; font-size: 9pt; font-weight:bold; color: <?=$color?>; text-transform: uppercase;">
                    <?= stripAccents('received_from') ?></td>
                <?php } ?>

            </tr>
            <tr>
                <?php if ($custom->getconfig_item('swap_to_from') == 'FALSE') { ?>
                <td width="45%">
                    <span
                        style="font-size: 11pt; font-weight: bold; color: #111111;"><?= ($custom->getconfig_item('company_legal_name_' . $l) ? $custom->getconfig_item('company_legal_name_' . $l) : $custom->getconfig_item('company_legal_name')) ?></span><br />
                    <?= ($custom->getconfig_item('company_address_' . $l) ? $custom->getconfig_item('company_address_' . $l) : $custom->getconfig_item('company_address')) ?><br>
                    <?= ($custom->getconfig_item('company_city_' . $l) ? $custom->getconfig_item('company_city_' . $l) : $custom->getconfig_item('company_city')) ?>
                    <?php if ($custom->getconfig_item('company_zip_code_' . $l) != '' || $custom->getconfig_item('company_zip_code') != '') : ?>
                    ,
                    <?= ($custom->getconfig_item('company_zip_code_' . $l) ? $custom->getconfig_item('company_zip_code_' . $l) : $custom->getconfig_item('company_zip_code')) ?><br>
                    <?php endif; ?>
                    <?php if ($custom->getconfig_item('company_state_' . $l) != '' || $custom->getconfig_item('company_state') != '') : ?>
                    <?= ($custom->getconfig_item('company_state_' . $l) ? $custom->getconfig_item('company_state_' . $l) : $custom->getconfig_item('company_state')) ?>,
                    <?php endif; ?>
                    <?= ($custom->getconfig_item('company_country_' . $l) ? $custom->getconfig_item('company_country_' . $l) : $custom->getconfig_item('company_country')) ?><br>
                    <?='phone'?> |
                    <?= ($custom->getconfig_item('company_phone_' . $l) ? $custom->getconfig_item('company_phone_' . $l) : $custom->getconfig_item('company_phone')) ?><br>
                    <?php if ($custom->getconfig_item('company_phone_2_'.$l) != '' || $custom->getconfig_item('company_phone_2') != '') : ?>
                    ,
                    <?= ($custom->getconfig_item('company_phone_2_' . $l) ? $custom->getconfig_item('company_phone_2_' . $l) : $custom->getconfig_item('company_phone_2')) ?><br>
                    <?php endif; ?>
                    <?php if ($custom->getconfig_item('company_fax_'.$l) != '' || $custom->getconfig_item('company_fax') != '') : ?>
                    <?='fax'?> |
                    <?= ($custom->getconfig_item('company_fax_' . $l) ? $custom->getconfig_item('company_fax_' . $l) : $custom->getconfig_item('company_fax')) ?><br>
                    <?php endif; ?>
                    <?php if ($custom->getconfig_item('company_registration_'.$l) != '' || $custom->getconfig_item('company_registration') != '') : ?>
                    <?='company_registration'?> |
                    <?= ($custom->getconfig_item('company_registration_' . $l) ? $custom->getconfig_item('company_registration_' . $l) : $custom->getconfig_item('company_registration')) ?><br>
                    <?php endif; ?>
                    <?php if ($custom->getconfig_item('company_vat_'.$l) != '' || $custom->getconfig_item('company_vat') != '') : ?>
                    <?='company_vat'?> |
                    <?= ($custom->getconfig_item('company_vat_' . $l) ? $custom->getconfig_item('company_vat_' . $l) : $custom->getconfig_item('company_vat')) ?><br>
                    <?php endif; ?>
                </td>
                <td width="10%">&nbsp;</td>
                <?php } ?>
                <td width="45%">
                    <span style="font-size: 11pt; font-weight: bold; color: #111111;">
                        <?=$client->company_name;?></span><br />
                    <?=$client->company_address;?><br />
                    <?=$client->city;?>
                    <?php if($client->zip != '') {
                    echo ", ".$client->zip;
                } ?><br />
                    <?php if ($client->state != '') {
                    echo $client->state.", ";
                } ?>
                    <?=$client->country; ?> <br />
                    <?php $phone = $client->company_phone; ?>
                    <?php if ($phone != '') : ?>
                    <span><?= 'phone' ?> | </span><?= $phone ?><br />
                    <?php endif; ?>
                    <?php $fax = $client->company_fax; ?>
                    <?php if ($fax != '') : ?>
                    <span><?= 'fax' ?> | </span><?= $fax ?><br />
                    <?php endif; ?>
                    <?php $vat = $client->VAT; ?>
                    <?php if ($vat != '') : ?>
                    <span><?= 'company_vat' ?> | </span><?=$vat?> <br />
                    <?php endif; ?>
                </td>
                <?php if ($custom->getconfig_item('swap_to_from') == 'TRUE') { ?>
                <td width="10%">&nbsp;</td>
                <td width="45%">
                    <span
                        style="font-size: 11pt; font-weight: bold; color: #111111;"><?= ($custom->getconfig_item('company_legal_name_' . $l) ? $custom->getconfig_item('company_legal_name_' . $l) : $custom->getconfig_item('company_legal_name')) ?></span><br />
                    <?= ($custom->getconfig_item('company_address_' . $l) ? $custom->getconfig_item('company_address_' . $l) : $custom->getconfig_item('company_address')) ?><br>
                    <?= ($custom->getconfig_item('company_city_' . $l) ? $custom->getconfig_item('company_city_' . $l) : $custom->getconfig_item('company_city')) ?>
                    <?php if ($custom->getconfig_item('company_zip_code_' . $l) != '' || $custom->getconfig_item('company_zip_code') != '') : ?>
                    ,
                    <?= ($custom->getconfig_item('company_zip_code_' . $l) ? $custom->getconfig_item('company_zip_code_' . $l) : $custom->getconfig_item('company_zip_code')) ?>
                    <?php endif; ?><br>
                    <?php if ($custom->getconfig_item('company_state_' . $l) != '' || $custom->getconfig_item('company_state') != '') : ?>
                    <?= ($custom->getconfig_item('company_state_' . $l) ? $custom->getconfig_item('company_state_' . $l) : $custom->getconfig_item('company_state')) ?>,
                    <?php endif; ?>
                    <?= ($custom->getconfig_item('company_country_' . $l) ? $custom->getconfig_item('company_country_' . $l) : $custom->getconfig_item('company_country')) ?><br>
                    <?='phone'?> |
                    <?= ($custom->getconfig_item('company_phone_' . $l) ? $custom->getconfig_item('company_phone_' . $l) : $custom->getconfig_item('company_phone')) ?>
                    <?php if ($custom->getconfig_item('company_phone_2_'.$l) != '' || $custom->getconfig_item('company_phone_2') != '') : ?>
                    ,
                    <?= ($custom->getconfig_item('company_phone_2_' . $l) ? $custom->getconfig_item('company_phone_2_' . $l) : $custom->getconfig_item('company_phone_2')) ?>
                    <?php endif; ?><br>
                    <?php if ($custom->getconfig_item('company_fax_'.$l) != '' || $custom->getconfig_item('company_fax') != '') : ?>
                    <?='fax'?> |
                    <?= ($custom->getconfig_item('company_fax_' . $l) ? $custom->getconfig_item('company_fax_' . $l) : $custom->getconfig_item('company_fax')) ?><br>
                    <?php endif; ?>
                    <?php if ($custom->getconfig_item('company_vat_'.$l) != '' || $custom->getconfig_item('company_vat') != '') : ?>
                    <?='company_vat'?> |
                    <?= ($custom->getconfig_item('company_vat_' . $l) ? $custom->getconfig_item('company_vat_' . $l) : $custom->getconfig_item('company_vat')) ?>
                    <?php endif; ?><br>
                </td>

                <?php } ?>
            </tr>
        </table>
    </div>
    <sethtmlpageheader name="myheader" value="off" />
    <table class="items" width="100%" style="border-spacing:3px; font-size: 9pt; border-collapse: collapse;"
        cellpadding="10">
        <thead>
            <tr>
                <td style="width:60%; text-align: left;"><?=stripAccents('description');?> </td>
                <td style="width:10%; text-align: left;"><?=stripAccents('qty');?> </td>
                <td style="width:15%; text-align: left;"><?=stripAccents('unit_price');?> </td>
                <td style="width:15%; text-align: right;"><?=stripAccents('amount');?> </td>

            </tr>
        </thead>
        <tbody>
            <!-- ITEMS HERE -->

            <tr class="last">
                <td width="60%" style="text-align: left;">
                    <div style="margin-bottom:6px; font-weight:bold; color: #111111;">
                        <?=Invoice::view_by_id($pay->invoice)->reference_no?>
                    </div>
                </td>
                <td width="10%" style="text-align: center;"><?=AppLib::format_quantity('1')?></td>
                <td width="15%" style="text-align: right;"><?=AppLib::format_quantity($pay->amount);?></td>
                <td width="15%" style="text-align: right;">
                    <?=AppLib::format_currency($pay->amount, 'default_currency');?>
                </td>

            </tr>

            <tr class="first">

                <td colspan="1" style="background-color:#ffffff;"></td>
                <td colspan="2" style="font-size: 8pt; color: #111111;"><strong><?= 'sub_total' ?></strong></td>
                <td style="font-weight: bold; color: #111111; text-align: right;">
                    <?=AppLib::format_quantity($pay->amount); ?></td>
            </tr>

            <tr>
                <td colspan="1" style="background-color:#ffffff;"></td>
                <td colspan="2" style="font-size: 8pt; color: #111111; background-color: <?=$color?>; color:#ffffff;">
                    <strong><?= 'total' ?> (<?=$pay->currency?>)</strong>
                </td>
                <td
                    style="font-weight: bold; color: #111111; text-align: right; background-color: <?=$color?>; color:#ffffff;">
                    <?=Applib::format_currency($pay->amount, 'default_currency');?></td>
            </tr>



        </tbody>
    </table>
    <div style="margin-top:40px;">
        <h4
            style="padding:5px 0; color: #111111; border-bottom: 0.2mm solid <?=$color?>; font-size:9pt; text-transform: uppercase;">
            <?= stripAccents('payment_information') ?></h4>
        <?=$pay->notes ?>
    </div>

</body>

</html>