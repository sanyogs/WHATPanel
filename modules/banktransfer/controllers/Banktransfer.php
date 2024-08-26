<?php 

/* Module Name: Banktransfer
 * Module URI: https://whatpanel.com
 * Version: 1.0
 * Category: Payment Gateways
 * Description: Banktransfer Integration.
 * Author:  WHAT PANEL 
 * Author URI: https://whatpanel.com
 */

namespace Modules\banktransfer\controllers;

use App\ThirdParty\MX\WhatPanel;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class Banktransfer extends WhatPanel
{
    public function banktransfer_config($values = null, $config = null)
    {
        $configur = array(
            'form_id' => 'banktransferForm', // Dynamic form ID
            'fields' => array(
                array(
                    'id' => 'id',
                    'type' => 'hidden',
                    'value' => $config->plugin_id
                ),
                array(
                    'id' => 'system_name',
                    'type' => 'hidden',
                    'value' => $config->system_name
                ),
                array(
                    'label' => lang('hd_lang.bank_account_number'), 
                    'id' => 'bank_account_number',
                    'value' => isset($values) ? $values['bank_account_number'] : '',
                    'type' => '',
                    'placeholder' => 'Enter the Bank Account Number',
                    'onclick' => 'return validateBankAccountNumber(this)'
                ),
                array(
                    'label' => lang('hd_lang.ifsc_code'), 
                    'id' => 'ifsc_code',
                    'value' => isset($values) ? $values['ifsc_code'] : '',
                    'type' => '',
                    'placeholder' => 'Enter the IFSC Code',
                    'onclick' => 'return validateIFSCode(this)'
                ),
                array(
                    'label' => lang('hd_lang.bank_holder_name'), 
                    'id' => 'bank_holder_name',
                    'value' => isset($values) ? $values['bank_holder_name'] : '',
                    'type' => '',
                    'placeholder' => 'Enter the Bank Holder Name',
                    'onclick' => 'return validateBankHolderName(this)'
                ),
                array(
                    'label' => lang('hd_lang.bank_address'), 
                    'id' => 'bank_address',
                    'value' => isset($values) ? $values['bank_address'] : '',
                    'type' => 'textarea'
                ),
                array(
                    'label' => lang('hd_lang.bank_name'), 
                    'id' => 'bank_name',
                    'value' => isset($values) ? $values['bank_name'] : '',
                    'type' => ''
                ),
                array(
                    'id' => 'submit',
                    'type' => 'submit',
                    'label' => 'Save',
                    'class' => 'common-button'
                )
            )
        ); 
        
        return $configur;        
    } 

    function pay($invoice_id)
    {
        $db = \Config\Database::connect();

        $banktransfer_details = $db->table('hd_plugins')->where('system_name', 'banktransfer')->get()->getRow();

        $banktransfer_config = json_decode($banktransfer_details->config, true);

        $data['invoice_id'] = $invoice_id;
        $data['banktransfer_details'] = $banktransfer_details;
        $data['banktransfer_config'] = $banktransfer_config;

        echo view('modules/banktransfer/pay', $data);
    }
}

?>