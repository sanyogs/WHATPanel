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

use App\Helpers\custom_name_helper;
use Config\Services;
use App\Models\User;
use App\Modules\sliders\controllers\Sliders;
use App\Config\Modules;
use App\Modules\menus\controllers\Menus;
use App\Modules\items\controllers\Items;

class whatpanel_helper {

  function get_slug()
  {	
      $request = service('request');
      $segments = $request->uri->getSegments();
      $lastSegment = end($segments);
      return !empty($lastSegment) ? $lastSegment : 'home';
  }
  
  
  function add_row($id, $parent, $li_attr, $label)
  {
    $this->data[$parent][] = array('id' => $id, 'li_attr' => $li_attr, 'label' => $label);
  }
  
  
  function generate_list($ul_attr = '')
  {
    return $this->ul(0, $ul_attr);
    $this->data = array();
  }
  
  
  function render_block($blocks)
  {		
    foreach ($blocks as $key => $block) {
      echo '<div class="block ' . $block->id . '">' . $block->content . '<div id="block-menu"></div></div>';
    }
  }
  
  
  
  function is_username_available($username)
  {
    $session = \Config\Services::session(); 

    // Connect to the database  
    $db = \Config\Database::connect();

    $userModel = new User($db);

    $query = $userModel->select('1', false)
      ->where('LOWER(username)', strtolower($username))
      ->get();

    return $query->getNumRows() == 0;
  }
  
  
  
  function is_email_available($email)
  {
    $session = \Config\Services::session(); 

    // Connect to the database  
    $db = \Config\Database::connect();

    $userModel = new User($db);

    $query = $userModel->select('1', false)
      ->where('LOWER(email)', strtolower($email))
      ->orWhere('LOWER(new_email)', strtolower($email))
      ->get();

    return $query->getNumRows() == 0;
  }
  
  
  
  function is_array_check(array $test_var)
  {
    foreach ($test_var as $key => $el) {
      if (is_array($el)) {
        return true;
      }
    }
    return false;
  }
  
  
  function uri_segment($seg)
  {
    $CI =& get_instance();
    return @$CI->uri->segment($seg);
  }
  
  
  function post_status($post)
  {
    return $post->status;
  }
  
  
  function post_url($post)
  {
    $type = $post->post_type;
    return base_url('pages/'.$post->slug);
  }
  
  
  function post_title($post)
  {
    return $post->title;
  }
  
  function post_body($post)
  {
    return $post->body;
  }
  
  
  function post_id($post)
  {
    return $post->id;
  }
  
  function blocks($section, $page) 
  {	
	  $blocks = array();
	  $pages = array();
	  $slugs = array($page,'all');
	  $db = \Config\Database::connect();
	  $helper = new custom_name_helper();
	  $block_list = $db->table('hd_blocks')->select('hd_blocks.*, hd_blocks_pages.page, hd_blocks_pages.mode')->join('hd_blocks_pages', 'hd_blocks_pages.block_id = hd_blocks.block_id', 'INNER')->where('section', $section)->whereIn('page', $slugs)->get()->getResult();
	  
	  foreach($block_list as $block) {
		  if($block->mode == 'show' || $block->page == 'all') {
			  $blocks[] = $block;
		  }

		  if($block->mode == 'hide') {
			  $pages[] = $block->page;
		  }    
	  } 

	  $block_list = $db->table('hd_blocks')->select('hd_blocks.*, hd_blocks_pages.page, hd_blocks_pages.mode')->join('hd_blocks_pages', 'hd_blocks_pages.block_id = hd_blocks.block_id', 'INNER')->where('section', $section)->get()->getResult();
	  
	  foreach($block_list as $block) {
		  if($block->mode == 'hide' && !in_array($this->get_slug(), $pages)) {
			  $added = false;
			  foreach($blocks as $b) {
				  if($b->block_id == $block->block_id) {
					  $added = true;
				  }
			  }
			  if(!$added) {
				  $blocks[] = $block;
			  }      
		  } 
	  }

	  if(count($blocks) > 0) { 

		  foreach($blocks as $key => $block) {

			  $parts = $block->id;
			  $part = explode('_', $parts);
			  if($block->type == 'Module' && count($part) > 1 && !is_numeric($part[1])) 
			  {	
				  $row = array(); 
				  // $row['content'] = view($helper->getconfig_item('active_theme').'/views/blocks/' . $block->id, '', TRUE);
				  $row['content'] = view($helper->getconfig_item('active_theme').'/views/blocks/' . $block->id, [], []);
				  $row['id'] = $this->block_id($block->name, $key);
				  $row['format'] = 'module';
				  $row['weight'] = $block->weight; 
				  $data['blocks'][] = (object) $row;
				
			  }

			  if($block->type == 'Custom') 
			  { 
				  $row = array();
				  $custom_block = $db->table('hd_blocks_custom')->where('id', $block->id)->get()->getRow();
          if ($custom_block && $custom_block->format == 'php') {
            // Sanitize and format the PHP code
            $php_code = $custom_block->code;
        
            // Capture the output of the PHP code
            ob_start();
            try {
                eval('?>' . $php_code);
                $row['content'] = ob_get_clean();
            } catch (\ParseError $e) {
                log_message('error', 'Eval failed: ' . $e->getMessage() . ' in code: ' . $php_code);
                $row['content'] = 'An error occurred while processing the block.';
            } catch (\Exception $e) {
                log_message('error', 'Exception occurred: ' . $e->getMessage() . ' in code: ' . $php_code);
                $row['content'] = 'An error occurred while processing the block.';
            }
        } else {
            $row['content'] = $custom_block->code; // Sanitize code if not PHP
        }
				  //$row['content'] = ($custom_block->format=='php') ? eval($custom_block->code) : $custom_block->code;
				  $row['id'] = $this->block_id($block->name, $key);
				  $row['format'] = $custom_block->format;
				  $row['weight'] = $block->weight; 
				  $data['blocks'][] = (object) $row; 
			  }
			  
			  if($block->type == 'Module' && is_numeric($part[1])) { 
				  $row = array();
				  //print_r($block->id);die;
				  $module_block = $db->table('hd_blocks_modules')->where('param', $block->id)->get()->getRow(); 
				  //print_r($module_block);die;
				  if(isset($module_block->module)) 
				  { 
					  switch($module_block->module){
						  case 'Menus':
							  $menus = new Menus();
							  $fun_name = strtolower($module_block->module) . '_block';
							  $content = $menus->{$fun_name}($part[1]);
                break;
						  case 'Items':
							  $items = new Items();
						    $fun_name = strtolower($module_block->module) . '_block';
							  $content = $items->{$fun_name}($part[1]);
							  break;
					  }
					  $row['content'] = $this->block_content($module_block, $content);       
					  $row['id'] = $this->block_id($block->name, $key);
					  $row['format'] = 'module';
					  $row['weight'] = $block->weight; 
					  $data['blocks'][] = (object) $row; 
				  }

			  }

		  }  

		  if(isset($data['blocks']) && is_array($data['blocks']))
		  {	
			  uasort($data['blocks'], function($a, $b) { return strcasecmp($a->weight, $b->weight); }); 
		  }

		  if(!isset($data))
		  {	
			  $data = array();
		  }
		  // echo $section;die;
		  // echo $helper->getconfig_item('active_theme').'/blocks/'.$section;die;
		  echo view($helper->getconfig_item('active_theme').'/blocks/'.$section, $data);
	  }

  }
  
  
  function block_id($name, $id)
  {
    $slices = explode(' ', $name);
    return strtolower(implode('_', $slices) . '_' . ($id + 1));
  }
  
  
  function block_content($block, $content)
  {	
    if (!empty($block->settings)) {
      $settings = unserialize($block->settings);
      return ($settings['title'] == 'yes') ? '<h2>' . $block->name . '</h2>' . $content : $content;
    }
  
    return $content;
  }
  
  
  function list_pages($published = TRUE)
  {
    $CI =& get_instance();
    @$CI->load->model('Page');
    return @$CI->Page_m->get(NULL, FALSE, TRUE);
  }
  
  
  function url_encode($data)
  {
    return base64_encode(serialize($data));
  }
  
  function url_decode($data)
  {
    return unserialize(base64_decode($data));
  }
  
  
  function theme_assets()
  {	$cus_name = new custom_name_helper();
    return base_url() . 'themes/' . $cus_name->getconfig_item('active_theme') . '/assets/';
  }
  
  function active_theme()
  {	
	$cus_name = new custom_name_helper();
    return FCPATH . 'themes/' . $cus_name->getconfig_item('active_theme') . '/';
  }
  
  
  function create_password()
  {
    $symbols = '!@+#%*!#?-/=_';
    $numbers = "0123456789";
    $lowercase = "abcdefghijklmnopqrstuvwxyz";
    $uppercase = "ABCDEFGHIJKLMNOPQRSTUVYWXYZ";
    $str = "";
    $count = strlen($numbers) - 1;
    for ($i = 0; $i < 4; $i++) {
      $str .= $numbers[rand(0, $count)];
    }
    $count = strlen($lowercase) - 1;
    for ($i = 0; $i < 4; $i++) {
      $str .= $lowercase[rand(0, $count)];
    }
    $count = strlen($symbols) - 1;
    for ($i = 0; $i < 3; $i++) {
      $str .= $symbols[rand(0, $count)];
    }
    $count = strlen($uppercase) - 1;
    for ($i = 0; $i < 3; $i++) {
      $str .= $uppercase[rand(0, $count)];
    }
    $password = "";
    for ($i = 0; $i < 12; $i++) {
      $randomnum = rand(0, strlen($str) - 1);
      $password .= $str[$randomnum];
      $str = substr($str, 0, $randomnum) . substr($str, $randomnum + 1);
    }
    return $password;
  }
  
  
  function intervals()
  {
    return array('monthly' => 30, 'quarterly' => 90, 'semi_annually' => 180, 'annually' => 365, 'biennially' => 730, 'triennially' => 1095);
  }

  function intervals_days()
  {
    return array(1 => 364, 2 => 729, 3 => 1094, 4 => 1459, 5 => 1824, 6 => 2189, 7 => 2554, 8 => 2919, 9 => 3284, 10 => 3649);
  }
	  
  function get_settings($module)
  {
    $plugin = Plugin::get_plugin($module);
    return unserialize($plugin->config);
  }
  
  
  function aff()
  {
    $url_parts = parse_url(current_url());
    $domain = str_replace('www.', '', $url_parts['host']);
    return 'aff_' . $domain;
  }
  
  
  function send_sms($phone, $message)
  {	
	$cus_name = new custom_name_helper();
    $phone = trim($phone);
    $payload = array('phone' => $phone, 'message' => $message);
  	
    if ($cus_name->getconfig_item('request_method') == 'twilio') {
      return modules::run('sms/twilio', $payload);
    }
  
    $sms_gateway = trim($cus_name->getconfig_item('sms_gateway_url'));
  
    if ($cus_name->getconfig_item('request_method') == 'get') {
      $sms_gateway = str_replace("%NUMBER%", $phone, $sms_gateway);
      $sms_gateway = str_replace("%MESSAGE%", $message, $sms_gateway);
      $sms_gateway = str_replace(" ", "%20", $sms_gateway);
	  if($sms_gateway != ""){
		$result = file_get_contents($sms_gateway);
      	return $result;
	  }else{
		  return redirect()->to('settings/sms');
		}
    }
  
    if ($cus_name->getconfig_item('request_method') == 'post') {
      $postData = array();
      $params = trim($cus_name->getconfig_item('custom_parameters'));
      if ($params != '') {
        $keyvals = explode(',', $params);
  
        foreach ($keyvals as $pairs) {
          $pair = explode('=', $pairs);
          $postData[$pair[0]] = $pair[1];
        }
      }
  
      $ch = curl_init($sms_gateway);
  
      if ($cus_name->getconfig_item('encoding') == 'json') {
        $postData = json_encode($postData);
        curl_setopt(
          $ch,
          CURLOPT_HTTPHEADER,
          array(
            'Content-Type: application/json'
          )
        );
      }
  
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_HEADER, FALSE);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
      curl_setopt($ch, CURLOPT_TIMEOUT, 30);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
      $result = curl_exec($ch);
      curl_close($ch);
  
      return $result;
    }
  }
  
  
  
  
  function send_payment_email($invoice_id, $paid_amount)
  {	
	$cus_name = new custom_name_helper();
    $message = App::email_template('payment_email', 'template_body');
    $subject = App::email_template('payment_email', 'subject');
    $signature = App::email_template('email_signature', 'template_body');
  
    $info = Invoice::view_by_id($invoice_id);
    $cur = App::currencies($info->currency);
  
    $logo_link = create_email_logo();
  
    $logo = str_replace("{INVOICE_LOGO}", $logo_link, $message);
  
    $invoice_ref = str_replace("{REF}", $info->reference_no, $logo);
  
    $invoice_currency = str_replace("{INVOICE_CURRENCY}", $cur->symbol, $invoice_ref);
    $amount = str_replace("{PAID_AMOUNT}", $paid_amount, $invoice_currency);
    $EmailSignature = str_replace("{SIGNATURE}", $signature, $amount);
    $message = str_replace("{SITE_NAME}", $cus_name->getconfig_item('company_name'), $EmailSignature);
  
    $params = array();
    $params['recipient'] = Client::view_by_id($info->client)->company_email;
    $params['subject'] = '[' . $cus_name->getconfig_item('company_name') . '] ' . $subject;
    $params['message'] = $message;
    $params['attached_file'] = '';
  
    modules::run('fomailer/send_email', $params);
  
    if ($cus_name->getconfig_item('sms_gateway') == 'TRUE' && $cus_name->getconfig_item('sms_payment_received') == 'TRUE') {
      send_message($invoice_id, 'payment_received');
    }
  
  }
  
  
  function notify_admin($invoice, $amount, $cur)
  {	
	 $cus_name = new custom_name_helper();
    if ($cus_name->getconfig_item('notify_payment_received') == 'TRUE') {
      $ci = &get_instance();
      $info = Invoice::view_by_id($invoice);
      foreach (User::admin_list() as $key => $user) {
        $data = array(
          'email' => $user->email,
          'invoice_ref' => $info->reference_no,
          'amount' => $amount,
          'currency' => $cur,
          'invoice_id' => $invoice,
          'client' => Client::view_by_id($info->client)->company_name
        );
  
        $email_msg = $ci->load->view('new_payment', $data, TRUE);
  
        $params = array(
          'subject' => '[' . $cus_name->getconfig_item('company_name') . ']' . lang('hd_lang.payment_confirmation'),
          'recipient' => $user->email,
          'message' => $email_msg,
          'attached_file' => ''
        );
  
        modules::run('fomailer/send_email', $params);
      }
    }
  }
  
  
  
  
  function send_email($target_id, $template, $order = null)
  {	
	$cus_name = new custom_name_helper();
    $new_invoice = Invoice::view_by_id($target_id);
    $client = Client::view_by_id($new_invoice->client);
    $message = App::email_template($template, 'template_body');
    $subject = App::email_template($template, 'subject');
    $signature = App::email_template('email_signature', 'template_body');
  
    $subject = $subject . ' ' . $new_invoice->reference_no;
  
    $invoice_cost = Invoice::get_invoice_due_amount($new_invoice->inv_id);
    $cur = App::currencies($new_invoice->currency);
    $items = Invoice::view_items($target_id);
    $item = $items[0]->item_name . " " . $items[0]->item_desc;
    $logo_link = create_email_logo();
  
    $logo = str_replace("{INVOICE_LOGO}", $logo_link, $message);
    $ref = str_replace("{REF}", $new_invoice->reference_no, $logo);
  
    $ClientName = str_replace("{CLIENT}", $client->company_name, $ref);
    $Amount = str_replace("{AMOUNT}", $invoice_cost, $ClientName);
    $Currency = str_replace("{CURRENCY}", $cur->symbol, $Amount);
    $Package = str_replace("{PACKAGE}", $item, $Currency);
    $RenewalDate = str_replace("{RENEWAL_DATE}", $order->renewal_date, $Package);
    $Renewal = str_replace("{RENEWAL}", lang($order->renewal), $RenewalDate);
    $link = str_replace("{INVOICE_LINK}", base_url() . 'invoices/view/' . $new_invoice->inv_id, $Renewal);
    $EmailSignature = str_replace("{SIGNATURE}", $signature, $link);
    $message = str_replace("{SITE_NAME}", $cus_name->getconfig_item('company_name'), $EmailSignature);
  
    _email_invoice($new_invoice->inv_id, $message, $subject); // Email Invoice
  }
  
  
  
  
  function send_message($target_id, $template)
  {	
	$cus_name = new custom_name_helper();
    $ci = &get_instance();
    $new_invoice = Invoice::view_by_id($target_id);
    $items = Invoice::view_items($target_id);
    $item = $items[0]->item_name . " " . $items[0]->item_desc;
    $client = Client::view_by_id($new_invoice->client);
    $cur = App::currencies($new_invoice->currency);
    $invoice_cost = Invoice::get_invoice_due_amount($new_invoice->inv_id);
    $message = App::sms_template($template);
  
    if ($template == 'payment_received') {
      $payment = $ci->db->where('invoice', $target_id)->order_by('p_id', 'desc')->limit('1')->get('payments')->row();
      $message = str_replace("{PAID_AMOUNT}", $payment->amount, $message);
    }
  
    $message = str_replace("{REF}", $new_invoice->reference_no, $message);
    $message = str_replace("{CLIENT}", $client->company_name, $message);
    $message = str_replace("{SERVICE}", $item, $message);
    $message = str_replace("{AMOUNT}", $invoice_cost, $message);
    $message = str_replace("{CURRENCY}", $cur->symbol, $message);
    $message = str_replace("{SITE_NAME}", $cus_name->getconfig_item('company_name'), $message);
  
    if (trim($client->company_mobile) != '') {
      send_sms($client->company_mobile, $message);
  
      $random_admin = $ci->db->where('role_id', '1')->select_min('id')->get('users')->row()->id;
  
      $data = array(
        'user' => $random_admin,
        'module' => 'invoices',
        'module_field_id' => $target_id,
        'activity' => $client->company_mobile . ' - ' . $message,
        'icon' => 'fa-paperplane',
        'value1' => $new_invoice->reference_no,
        'value2' => $client->company_mobile
      );
  
      App::Log($data);
    }
  }
  
  
  
  function _email_invoice($invoice_id, $message, $subject)
  {
  
    $ci = &get_instance();
    $data['message'] = $message;
    $invoice = Invoice::view_by_id($invoice_id);
  
    $message = $ci->load->view('email_template', $data, TRUE);
  
    $params = array(
      'recipient' => Client::view_by_id($invoice->client)->company_email,
      'subject' => $subject,
      'message' => $message
    );
  
    $ci->load->helper('file');
    $attach['inv_id'] = $invoice_id;
  
    $invoicehtml = modules::run('fopdf/attach_invoice', $attach);
  
    $params['attached_file'] = './resource/tmp/' . lang('hd_lang.invoice') . ' ' . $invoice->reference_no . '.pdf';
    $params['attachment_url'] = base_url() . 'resource/tmp/' . lang('hd_lang.invoice') . ' ' . $invoice->reference_no . '.pdf';
  
    modules::run('fomailer/send_email', $params);
    //Delete invoice in tmp folder
    if (is_file('./resource/tmp/' . lang('hd_lang.invoice') . ' ' . $invoice->reference_no . '.pdf'))
      unlink('./resource/tmp/' . lang('hd_lang.invoice') . ' ' . $invoice->reference_no . '.pdf');
  }
  
  
  
  function set_affiliate($id)
  {
    $ci = &get_instance();
    $client = $ci->db->where('affiliate_id', $id)->get('companies')->row();
    if (is_object($client)) {
  
      $cookie = array(
        'name' => aff(),
        'value' => $id,
        'expire' => '8640000'
      );
  
      $ci->input->set_cookie($cookie);
  
      $clicks = $client->affiliate_clicks + 1;
      $balance = array(
        'affiliate_clicks' => $clicks
      );
  
      $ci->db->where('affiliate_id', $id);
      $ci->db->update('companies', $balance);
    }
  }
  
  
  
  function affiliate()
  {
    $ci = &get_instance();
    $affiliate = $ci->input->cookie(aff(), TRUE);
    if (null != $affiliate) {
      $client = $ci->db->where('affiliate_id', $affiliate)->get('companies')->row();
      if (is_object($client)) {
        return $affiliate;
      }
    }
  
    return 0;
  }
  
  
  
  function unset_affiliate()
  {
    delete_cookie(aff());
  }
}
