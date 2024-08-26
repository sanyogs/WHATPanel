<?php
 /*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

namespace App\Modules\fomailer\controllers;

use App\ThirdParty\MX\WhatPanel;
use App\Helpers\custom_name_helper;
use CodeIgniter\I18n\Time;
use CodeIgniter\Encryption\Encryption;
use CodeIgniter\Email\Email;

class Fomailer extends WhatPanel{

		function __construct()
		{
			parent::__construct();
		}

	function send_email($params)
	{	
		//echo "<pre>";print_r($params);die;
		$custom_name_helper = new custom_name_helper();
		if($custom_name_helper->getconfig_item('disable_emails') == 'FALSE'){
 
			if ($custom_name_helper->getconfig_item('protocol') == 'smtp') {
				//$this->load->library('encryption');
				$library = service('encryption');

				$encryptionConfig = new \Config\Encryption();
				$encryptionConfig->key = 'w2ggVg5vTD0m7XR'; // replace with your secret key
				$encryptionConfig->driver = 'OpenSSL';
				$encryptionConfig->digest = 'sha512';
				$encryptionConfig->cipher = 'aes-256-ctr';

				$encrypter = \Config\Services::encrypter($encryptionConfig);
				$encryption = \Config\Services::encrypter();
				$raw_smtp_pass =  '5d&z93Ky6';

				$config = array(
						'smtp_host' => $custom_name_helper->getconfig_item('smtp_host'),
						'smtp_port' => $custom_name_helper->getconfig_item('smtp_port'),
						'smtp_user' => $custom_name_helper->getconfig_item('smtp_user'),
						'smtp_pass' => $raw_smtp_pass,
						'crlf' 		=> "\r\n",   							
						'protocol'	=> $custom_name_helper->getconfig_item('protocol'),
						'smtp_crypto' => $custom_name_helper->getconfig_item('smtp_encryption')
				);
				
			}	

				//$this->load->library('email');
				$email = service('email');
				// Send email 
				$config['mailtype'] = "html";
				$config['newline'] = "\r\n";
				$config['charset'] = 'utf-8';
				$config['wordwrap'] = TRUE;
    			$email = new Email();
				$email->initialize($config);

			$email->setFrom($custom_name_helper->getconfig_item('company_email'), $custom_name_helper->getconfig_item('company_name'));
                if ($custom_name_helper->getconfig_item('use_alternate_emails') == 'TRUE' && isset($params['alt_email'])) {
                        $alt = $params['alt_email'];
                            if ($custom_name_helper->getconfig_item($alt.'_email') != '') {
            $email->setFrom($custom_name_helper->getconfig_item($alt.'_email'), $custom_name_helper->getconfig_item($alt.'_email_name'));
                            }
                }
                                
				$email->setTo($params['recipient']);
				
				if (isset($params['cc'])) {
					$email->setBcc($params['cc']);
				}
				$email->setSubject($params['subject']);
			
				$email->setMessage($params['message']);
				// check attachments
				    if($params['attached_file'] != ''){ 
				    	$email->attach($params['attached_file']);
				    }

				   
				// Queue emails
				if(!$email->send()){
		$this->send_later($params['recipient'],$custom_name_helper->getconfig_item('company_email'),$params['subject'],$params['message']);
				}
		}
		else{
			// Emails disabled
			return TRUE;
		}
	
	}

	/**
	 * send_later
	 *
	 * Log unsent emails to be completed via CRON
	 *
	 * @access	private
	 * @param	email params
	 * @return	boolean	
	 */
	 
		private function send_later($to,$from,$subject,$message)
		{
			$emails = [
				'sent_to'   => $to,
				'sent_from' => $from,
				'subject'   => $subject,
				'message'   => $message
			];
			
			$db = \Config\Database::connect();
			$db->table('hd_outgoing_emails')->insert($emails);

			return true;

		}
}