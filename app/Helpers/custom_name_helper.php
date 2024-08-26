<?php
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */


namespace App\Helpers;
use App\Models\Order;
use App\Models\Client;
use App\Models\User;
use Modules\cyberpanel\controllers\Cyberpanel;
use Modules\bitcoin\controllers\Bitcoin;
use Modules\cwp\controllers\CWP;
use Modules\cpanel\controllers\new_Cpanel;
use Modules\coinpayments\controllers\Coinpayments;
use Modules\checkout\controllers\Checkout;
use Modules\directadmin\controllers\Directadmin;
use Modules\ispconfig\controllers\Ispconfig;
use Modules\plesk\controllers\Plesk_WHMCS;
use Modules\whoisxmlapi\controllers\Whoisxmlapi;
use Modules\razorpay\controllers\Razorpay;
use Modules\stripepay\controllers\Stripepay;
use Modules\paypal\controllers\Paypal;
use Modules\payfast\controllers\Payfast;


use Config\Services;

class custom_name_helper
{
    function config_item($item, $data) {
        foreach($data as $key) {
            if($key->config_key === $item) {
                return $key->value;
            }
        }
    }

    function getconfig_item($configKey) {
        $session = \Config\Services::session();
        // Connect to the database
        $db = \Config\Database::connect();

        $query = $db->table('hd_config')
            ->select('value')
            ->where('config_key', $configKey)
            ->get();

        $row = $query->getRow();

        if ($row) {
            $configValue = $row->value;
            // Now $configValue contains the value for the specified config_key
        } else {
            // Handle the case where the config_key was not found
            $configValue = null;
        }

        return $configValue;
    }
	
	function settimezone()
	{
		$helper = $this->getconfig_item('timezone');
		
		return date_default_timezone_set($helper);
	}
	
	function auto_activate($invoice_id = null)
	{	
		$db = \Config\Database::connect();
		$invoice_details = $db->table('hd_invoices')->where('inv_id', $invoice_id)->get()->getRow();
		//echo"<pre>";print_r($invoice_details);die;

		//echo $this->getconfig_item('automatic_activation');die;
			
			if ($this->getconfig_item('automatic_activation') && $invoice_details->status === 'Paid') {
				$db = \Config\Database::connect();
				//echo"<pre>";print_r($invoice_details);die;
				$order_details = $db->table('hd_orders')->where('invoice_id', $invoice_details->inv_id)->get()->getRow();
				//print_r($db->getlastQuery());die;
				$account = Order::get_order($order_details->id);
				$client = Client::view_by_id($account->client_id);
				//echo"<pre>";print_r($client);die;
				$user = User::view_user($client->primary_contact);
				//echo"<pre>";print_r($user);die;
				$profile = User::profile_info($client->primary_contact);
				$server = $db->table('hd_servers')->where(array('type'=> $order_details->server))->get()->getRow();
				//echo"<pre>";print_r($server);die;
				$package = $db->table('hd_items_saved')->where(array('item_id'=> $account->item_parent))->get()->getRow();
			
				$details = (object) array(
					'user' => $user, 
					'profile' => $profile, 
					'client' => $client, 
					'account' => $account,
					'package' => $package,
					'server' => $server
				);
			  // echo"<pre>";print_r($order_details->server);die;
				switch ($order_details->server) 
				{
					case 'plesk':
						//ho 78;die;
						//$plesk = new Plesk();
						$plesk = new Plesk_WHMCS();
						// $configuration = $plesk->create_account($details);
						$configuration = $plesk->plesk_CreateAccount($details);
						break;
					case 'ispconfig':
						$ispconfig = new Ispconfig();
						$configuration = $ispconfig->create_account($details);
						break;
					case 'directadmin':
						$directadmin = new Directadmin();
						$configuration = $directadmin->create_account($details);
						break;
					case 'cyberpanel':
						$cyberpanel = new Cyberpanel();
						$configuration = $cyberpanel->create_account($details);
						break;
					case 'cwp':
						$cwp = new Cwp();
						$configuration = $cwp->create_account($details);
						break;
					case 'cpanel':
						$cpanel = new new_Cpanel();
						$configuration = $cpanel->cpanel_CreateAccount($details);
						break;
				}
				$db->table('hd_orders')->where('id', $order_details->id)->update(['status_id' => 6]);
				//print_r($configuration);die;
				//return $configuration;
			}
	}
	
	
	function auto_suspend($invoice_id = null)
	{	
		error_reporting(E_ALL);
        ini_set("display_errors", "1");
		$db = \Config\Database::connect();
		$invoice_details = $db->table('hd_invoices')->where('inv_id', $invoice_id)->get()->getRow();
		$order_details = $db->table('hd_orders')->where('invoice_id', $invoice_id)->get()->getRow();
		
				$db = \Config\Database::connect();
				$order_details = $db->table('hd_orders')->where('invoice_id', $invoice_details->inv_id)->get()->getRow();
				$account = Order::get_order($order_details->id);
				$client = Client::view_by_id($account->client_id);
				//echo"<pre>";print_r($order_details);die;
				$user = User::view_user($client->primary_contact);
				//eecho"<pre>";print_r($db->getLastQuery());die;
				$profile = User::profile_info($client->primary_contact);
				$server = $db->table('hd_servers')->where(array('type'=> $order_details->server))->get()->getRow();
				$package = $db->table('hd_items_saved')->where(array('item_id'=> $account->item_parent))->get()->getRow();
	
				$details = (object) array(
					'user' => $user, 
					'profile' => $profile, 
					'client' => $client, 
					'account' => $account,
					'package' => $package,
					'server' => $server
				);
				//echo"<pre>";print_r($details);die;
				//$result .= modules::run($account->server_type.'/suspend_account', $details);
		
				//print_r($account->server);die;

				switch ($account->server) {
					case 'plesk':
						$result = $db->table('hd_plesk_account')->where(array('order_id' => $order_details->order_id, 'invoice_id' => $invoice_details->inv_id))->get()->getRow();
						$plesk = new Plesk_WHMCS();
						$result = $plesk->plesk_SuspendAccount($details, $result->plesk_user_id);
					break;
					case 'ispconfig':
						$ispconfig = new Ispconfig();
						$result = $ispconfig->suspend_account($details);
					break;
					case 'directadmin':
						$directadmin = new Directadmin();
						$result = $directadmin->suspend_account($details);
					break;
					case 'cyberpanel':
						$cyberpanel = new Cyberpanel();
						$result = $cyberpanel->cyberpanel_SuspendWebsite($details);
					break;
					case 'cwp':
						$cwp = new Cwp();
						$result = $cwp->suspend_account($details);
					break;
					case 'cpanel':
						$result = $db->table('hd_cpanel_account')->where(array('order_id' => $order_details->order_id, 'invoice_id' => $invoice_details->inv_id))->get()->getRow();
						//print_r($result);die;
						$cpanel = new new_Cpanel();
						$result = $cpanel->cpanel_SuspendAccount($details, $result);
					break;
				}
				$db->table('hd_orders')->where('id', $order_details->id)->update(['status_id' => 9]);
		return $result;
	}
	
	
	
	
	function auto_unsuspend($invoice_id = null)
	{	
		error_reporting(E_ALL);
        ini_set("display_errors", "1");
		$db = \Config\Database::connect();
		$invoice_details = $db->table('hd_invoices')->where('inv_id', $invoice_id)->get()->getRow();
		$order_details = $db->table('hd_orders')->where('invoice_id', $invoice_id)->get()->getRow();
		//echo 29;die;
		if ($order_details->status_id == 9 && $invoice_details->status === 'Paid') {
				$db = \Config\Database::connect();
				$order_details = $db->table('hd_orders')->where('invoice_id', $invoice_details->inv_id)->get()->getRow();
				$account = Order::get_order($order_details->id);
				$client = Client::view_by_id($account->client_id);
				//echo"<pre>";print_r($order_details);die;
				$user = User::view_user($client->primary_contact);
				//echo"<pre>";print_r($db->getLastQuery());die;
				$profile = User::profile_info($client->primary_contact);
				$server = $db->table('hd_servers')->where(array('type'=> $order_details->server))->get()->getRow();
				$package = $db->table('hd_items_saved')->where(array('item_id'=> $account->item_parent))->get()->getRow();
	
				$details = (object) array(
					'user' => $user, 
					'profile' => $profile, 
					'client' => $client, 
					'account' => $account,
					'package' => $package,
					'server' => $server
				);
				///echo"<pre>";print_r($details);die;
				//$result .= modules::run($account->server_type.'/suspend_account', $details);
		
				//print_r($account->server);die;

				switch ($account->server) {
					case 'plesk':
						$resultAcc = $db->table('hd_plesk_account')->where(array('order_id' => $order_details->order_id, 'invoice_id' => $invoice_details->inv_id))->get()->getRow();
						$plesk = new Plesk_WHMCS();
						$result = $plesk->plesk_UnSuspendAccount($details, $resultAcc->plesk_user_id);
					break;
					case 'ispconfig':
						$ispconfig = new Ispconfig();
						$result .= $ispconfig->unsuspend_account($details);
					break;
					case 'directadmin':
						$directadmin = new Directadmin();
						$result .= $directadmin->unsuspend_account($details);
					break;
					case 'cyberpanel':
						$cyberpanel = new Cyberpanel();
						$result .= $cyberpanel->unsuspend_account($details);
					break;
					case 'cwp':
						$cwp = new Cwp();
						$result .= $cwp->unsuspend_account($details);
					break;
					case 'cpanel':
						$results = $db->table('hd_cpanel_account')->where(array('order_id' => $order_details->order_id, 'invoice_id' => $invoice_details->inv_id))->get()->getRow();
						$cpanel = new new_Cpanel();
						$result = $cpanel->cpanel_UnsuspendAccount($details, $results);
					break;
				}
				$db->table('hd_orders')->where('id', $order_details->id)->update(['status_id' => 6]);
		return $result;
	}
		else{
			echo "Something went wrong in auto unsuspend";
		}
	}
	
			function _backup($params = array())
		{
			// Replace $this->db with \Config\Database::connect() to get database connection
			$db = \Config\Database::connect();

			if (count($params) == 0) {
				return FALSE;
			}

			// Extract the prefs for simplicity
			extract($params);

			// Build the output
			$output = '';
			foreach ((array)$tables as $table) {
				// Is the table in the "ignore" list?
				if (in_array($table, (array)$ignore, TRUE)) {
					continue;
				}

				// Get the table schema
				$query = $db->query("SHOW CREATE TABLE `".$db->database.'`.`'.$table.'`');

				// No result means the table name was invalid
				if ($query === FALSE) {
					continue;
				}

				// Write out the table schema
				$output .= '#'.$newline.'# TABLE STRUCTURE FOR: '.$table.$newline.'#'.$newline.$newline;

				if ($add_drop == TRUE) {
					$output .= 'DROP TABLE IF EXISTS '.$table.';'.$newline.$newline;
				}

				$i = 0;
				$result = $query->getResultArray();
				foreach ($result[0] as $val) {
					if ($i++ % 2) {
						$output .= $val.';'.$newline.$newline;
					}
				}

				// If inserts are not needed we're done...
				if ($add_insert == FALSE) {
					continue;
				}

				// Grab all the data from the current table
				$query = $db->query("SELECT * FROM $table");

				if ($query->getNumRows() == 0) {
					continue;
				}

				// Fetch the field names and determine if the field is an
				// integer type.  We use this info to decide whether to
				// surround the data with quotes or not

				$i = 0;
				$field_str = '';
				$is_int = array();
				foreach ($query->getFieldData() as $field) {
					// Most versions of MySQL store timestamp as a string
					$is_int[$i] = (in_array(
						strtolower($field->type),
						array('tinyint', 'smallint', 'mediumint', 'int', 'bigint'), //, 'timestamp'),
						TRUE)
					) ? TRUE : FALSE;

					// Create a string of field names
					$field_str .= '`'.$field->name.'`, ';
					$i++;
				}

				// Trim off the end comma
				$field_str = preg_replace("/, $/", "", $field_str);


				// Build the insert string
				foreach ($query->getResultArray() as $row) {
					$val_str = '';

					$i = 0;
					foreach ($row as $v) {
						// Is the value NULL?
						if ($v === NULL) {
							$val_str .= 'NULL';
						} else {
							// Escape the data if it's not an integer
							if ($is_int[$i] == FALSE) {
								$val_str .= $db->escape($v);
							} else {
								$val_str .= $v;
							}
						}

						// Append a comma
						$val_str .= ', ';
						$i++;
					}

					// Remove the comma at the end of the string
					$val_str = preg_replace("/, $/", "", $val_str);

					// Build the INSERT string
					$output .= 'INSERT INTO '.$table.' ('.$field_str.') VALUES ('.$val_str.');'.$newline;
				}

				$output .= $newline.$newline;
			}

			return $output;
		}
	
	function flash_error($message)
	{
		$session = session();
		$session->setFlashdata('response_status', 'danger');
		$session->setFlashdata('message', $message);
	}
}

?>