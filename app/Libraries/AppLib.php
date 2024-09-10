<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */


namespace App\Libraries;

use Config\MyConfig;
use CodeIgniter\I18n\Time;
use App\Helpers\custom_name_helper;

use App\Models\App;
use App\Models\Field;
use App\Models\Invoice;
use App\Models\Fields;
use DateTime;

class AppLib
{

    private static $db;

    // Define system tables

    public static $user_table = 'users';
    public static $invoices_table = 'invoices';
    public static $invoice_items_table = 'items';
    public static $tax_rates_table = 'tax_rates';
    public static $payments_table = 'payments';
    public static $payment_methods_table = 'payment_methods';
    public static $profile_table = 'account_details';
    public static $activities_table = 'activities';
    public static $companies_table = 'companies';
    public static $config_table = 'config';
    public static $departments_table = 'departments';
    public static $email_templates_table = 'email_templates';
    public static $custom_fields_table = 'fields';
    public static $files_table = 'files';
    public static $item_lookup_table = 'items_saved';
    public static $ticket_replies_table = 'ticketreplies';
    public static $tickets_table = 'tickets';
    public static $links_table = 'links';


    function __construct()
    {

        $session = \Config\Services::session();

        // Connect to the database	
        $db = \Config\Database::connect();

        $db->query('SET SESSION sql_mode = ""');

        // Load the affiliate helper if 'ref' is set in the query string
        $request = service('request');
        if ($request->getGet('ref')) {
            set_affiliate($request->getGet('ref'));
        }
    }

    public function count_table_rows($table)
    {
        $query = $this->ci->db->get($table);
        if ($query->num_rows() > 0) {
            return $query->num_rows();
        } else {
            return 0;
        }
    }

    /**
     * Get all records in $table.
     *
     * @return Table Data array
     */

    static function retrieve($table, $where = array(), $limit = NULL)
    {
        return self::$db->where($where)->get($table, $limit)->result();
    }

    /**
     * Insert records to $table.
     *
     * @return Inserted record ID
     */

    static function create($table, $data = array())
    {

        self::$db->insert($table, $data);
        return self::$db->insert_id();
    }


    /**
     * Update records in $table matching $match.
     *
     * @return Affected rows int
     */

    static function update($table, $match = array(), $data = array())
    {

        self::$db->where($match)->update($table, $data);
        return self::$db->affected_rows();
    }

    /**
     * Deletes data matching $where in $table.
     *
     * @return boolean
     */

    static function delete($table, $where = array())
    {

        return self::$db->delete($table, $where);
    }

    /**
     * Get all records in $table matching $table_criteria.
     *
     * @return Table field value string
     */

    static function get_table_field($table, $where_criteria = array(), $table_field)
    {	
		$db = \Config\Database::connect(); // Assign the result to a variable
		$result = $db->table($table)->select($table_field)->where($where_criteria)->get()->getRow();
		if ($result) {
			return $result->$table_field;
		}

		return FALSE;
    }



    static function make_flashdata($data)
    {
        $ci = &get_instance();
        foreach ($data as $key => $value) {
            $ci->session->set_flashdata($key, $value);
        }
    }


    /**
     * Test whether in demo mode
     *
     * @return redirect to request page
     */


    public static function is_demo()
    {	
		$helper = new custom_name_helper();
        if ($helper->getconfig_item('demo_mode') === true) {
            session()->setFlashdata(['response_status' => 'error', 'message' => lang('hd_lang.demo_warning')]);
            return redirect()->back();
        }
    }


    /**
     * Create a dir
     *
     * @return boolean
     */

    static function create_dir($path)
    {
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        return TRUE;
    }

    // Perfomr redirection
    public static function go_to($url, $response, $message)
    {
        $session = session();

        $session->setFlashdata('response_status', $response);
        $session->setFlashdata('message', $message);
        return redirect()->to($url);
    }

    static function cal_amount($type, $year, $month)
    {
        switch ($type) {
            case 'payments':
                return self::_yearly_payments($year, $month);
                break;
            case 'invoiced':
                return self::_yearly_invoiced($year, $month);
                break;
        }
    }

    static function _yearly_payments($year, $month)
    {
        $total = 0;
        $query = "SELECT * FROM hd_payments WHERE MONTH(payment_date) = '$month' AND YEAR(payment_date) = '$year'";
        $payments = self::$db->query($query)->result();
		$helper = new custom_name_helper();
        foreach ($payments as $p) {
            $amount = $p->amount;
            if ($p->currency != $helper->getconfig_item('default_currency')) {
                $amount = self::convert_currency($p->currency, $amount);
            }
            $total += $amount;
        }
        return round($total, $helper->getconfig_item('currency_decimals'));
    }

    static function _yearly_invoiced($year, $month)
    {
        $query = "SELECT * FROM hd_invoices WHERE MONTH(date_saved) = '$month' AND YEAR(date_saved) = '$year'";
        $invoices = self::$db->query($query)->result();
        $total = 0;
		$helper = new custom_name_helper();
        foreach ($invoices as $key => $i) {
            $total += Invoice::payable($i->inv_id);
        }
        return round($total, $helper->getconfig_item('currency_decimals'));
    }

    public function total_tax($client = NULL)
    {
        // $avg_tax = $this->average_tax($client);
        // $invoice_amount = $this->get_sum('items','total_cost',array('total_cost >'=>0));
        // $tax = ($avg_tax/100) * $invoice_amount;
        // return $tax;

        return 0;
    }


    function get_any_field($table, $where_criteria, $table_field)
    {
        $query = $this->ci->db->select($table_field)->where($where_criteria)->get($table);
        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row->$table_field;
        }
    }

    public function generate_unique_value()
    {
        $uniqid = uniqid('f');

        $session = \Config\Services::session();

        $config = new \Config\Database();
		
        // Connect to the database
        $dbName = \Config\Database::connect();

        $db = new Field();

        $result = $dbName->table('hd_fields')->where('uniqid', $uniqid)->get();
        if (count($result->getResultArray()) > 0) {
            // A record with the same uniqid already exists, so generate a new unique value
            return $this->generate_unique_value();
        } else {
            // The generated uniqid is unique, so return it
            return $uniqid;
        }
    }




    static function sec_to_hours($seconds)
    {
        $minutes = $seconds / 60;
        $hours = $minutes / 60;
        if ($minutes >= 60) {
            return round($hours, 2) . ' ' . lang('hd_lang.hours');
        } elseif ($seconds > 60) {
            return round($minutes, 2) . ' ' . lang('hd_lang.minutes');
        } else {
            return $seconds . ' ' . lang('hd_lang.seconds');
        }
    }


    public function generate_string()
    {
        // $this->ci->load->helper('string');
        // return random_string('nozero', 7);
        // Load the 'text' helper
        helper('text');

        // Generate a random string with 7 characters (no zeros)
        $randomString = random_string('nozero', 7);
        return $randomString;
    }

    function prep_response($response)
    {
        return json_decode($response, TRUE);
    }


    function count_rows($table, $where)
    {
        $this->ci->db->where($where);
        $query = $this->ci->db->get($table);
        if ($query->num_rows() > 0) {
            return $query->num_rows();
        } else {
            return 0;
        }
    }

    function get_sum($table, $field, $where)
    {   
        $db = \Config\Database::connect();
        $builder = $db->table($table)->where($where)->selectSum($field)->get();
        if ($builder->getResult()) {
            $row = $builder->getRow();
            return $row->$field;
        } else {
            return 0;
        }
    }

    static function gm_sec($seconds)
    {
        $t = round($seconds);
        return sprintf('%02d:%02d:%02d', ($t / 3600), ($t / 60 % 60), $t % 60);
    }

    static function date_formatter($str)
    {
        //$date = new \DateTime();
        $custom_name_helper = new custom_name_helper();
        //return date_format($date->createFromFormat($custom_name_helper->getconfig_item('date_php_format'), $str), 'Y-m-d');
        //$date = Time::createFromFormat($custom_name_helper->getconfig_item('date_php_format'), $str);
        $date = date_create($str);
        return date_format($date, $custom_name_helper->getconfig_item('date_php_format'));
    }


    public static function remote_get_contents($url)
    {
        if (function_exists('curl_get_contents') and function_exists('curl_init')) {
            return self::curl_get_contents($url);
        } else {
            return file_get_contents($url);
        }
    }

    public static function curl_get_contents($url)
    {
        $ch = curl_init();
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => true
        );
        if (!ini_get('safe_mode') && !ini_get('open_basedir')) {
            $options[CURLOPT_FOLLOWLOCATION] = true;
        }
        curl_setopt_array($ch, $options);

        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }



    static function switchoff()
    {
        Applib::update(Applib::$config_table, array('config_key' => 'valid_license'), array('value' => 'FALSE'));
    }
    static function switchon()
    {
        Applib::update(Applib::$config_table, array('config_key' => 'valid_license'), array('value' => 'TRUE'));
    }

    static function pc()
    {
        $purchase = self::$db->where('config_key', 'valid_license')->get(self::$config_table)->row();
        return ($purchase->value == 'FALSE') ? 'not_validated' : 'validated';
    }



    function translations()
    {
        $tran = array();
		$db = \Config\Database::connect();
        $companies = $db->table('hd_companies')->select('language')->groupBy('language')->orderBy('language', 'ASC')->get()->getResult();
        $users = $db->table(Applib::$profile_table)->select('language')->groupBy('language')->orderBy('language', 'ASC')->get()->getResult();
        foreach ($companies as $lang) {
            if (!empty($lang->language)) {
                $tran[$lang->language] = $lang->language;
            }
        }
        foreach ($users as $lan) {
            if (!empty($lan->language)) {
                $tran[$lan->language] = $lan->language;
            }
        }
        if (isset($tran['english'])) {
            unset($tran['english']);
        }
        return $tran;
    }

    function file_size($url)
    {
        $this->ci->load->helper('file');
        $info = get_file_info($url);
        return $info['size'];
    }



    function file_icon($ext = FALSE)
    {
        $icon = "fa-file-o";
        if (!$ext) {
            return $icon;
        }

        if (in_array($ext, array('.pdf'))) {
            $icon = 'fa-file-pdf-o';
        }
        if (in_array($ext, array('.doc', '.docx', '.odt'))) {
            $icon = 'fa-file-word-o';
        }
        if (in_array($ext, array('.xls', '.xlsx', '.ods'))) {
            $icon = 'fa-file-excel-o';
        }
        if (in_array($ext, array('.mp3', '.wav'))) {
            $icon = 'fa-file-sound-o';
        }
        if (in_array($ext, array('.zip', '.rar', '.gzip', '.7z'))) {
            $icon = 'fa-file-archive-o';
        }
        if (in_array($ext, array('.txt'))) {
            $icon = 'fa-file-text-o';
        }
        if (in_array($ext, array('.ppt', 'pptx'))) {
            $icon = 'fa-file-powerpoint-o ';
        }
        if (in_array($ext, array('.mp4', 'avi', 'wmv', 'qt', 'mpg', 'mkv'))) {
            $icon = 'fa-file-video-o';
        }
        if (in_array($ext, array('.php', '.html', '.sql', '.xml', '.js', 'css'))) {
            $icon = 'fa-file-code-o';
        }
        if (in_array($ext, array('.psd'))) {
            $icon = 'fa-camera-retro';
        }
        if (in_array($ext, array('.ai', '.cdr', 'eps', 'svg'))) {
            $icon = 'fa-paint-brush';
        }

        return $icon;
    }



    function short_string($string = FALSE, $from_start = 30, $from_end = 10, $limit = FALSE)
    {
        if (!$string) {
            return FALSE;
        }
        if ($limit) {
            if (mb_strlen($string) < $limit) {
                return $string;
            }
        }
        return mb_substr($string, 0, $from_start - 1) . "..." . ($from_end > 0 ? mb_substr($string, -$from_end) : '');
    }

    public function set_locale($user = false)
    {

        try {
            // echo 1;
            // die;
            if (!$user) {

                $localeConfig = $this->db->table('hd_config')->where('config_key', 'locale')->get();
                // print_r($localeConfig);
                // die;
                // echo $this->db->getLastQuery();
                // die;
                if ($localeConfig->getResult() == null) {
                    throw new \Exception('Locale config not found');
                }
                $locale = $localeConfig->getRow();
            } else {
                $localeUser = $this->db->table('account_details')->where('user_id', $user)->get();
                if ($localeUser->getResult() == null) {
                    throw new \Exception('Locale user not found');
                }
                $loc = empty($localeUser->getRow()->locale) ? 'en-US' : $localeUser->getRow()->locale;
                $locale = $this->db->table('locales')->where('locale', $loc)->get()->getRow();
            }

            if (!$locale) {
                throw new \Exception('Locale not found');
            }

            $locUnix = $locale->locale . ".UTF-8";
            $locWin = str_replace("_", "-", $locale->locale);
            setlocale(LC_ALL, $locUnix, $locWin, $locale->code);

            return $locale;
        } catch (\Exception $e) {
            // Log or handle the exception as needed
            log_message('error', 'Error in set_locale: ' . $e->getMessage());
            return null;
        }
    }



    //Create PDF
    // public function create_pdf($pdf) {
    //     // echo "<pre>";print_r($pdf);die;
    //     //include('./resource/lib/mpdf60/mpdf.php');
    //     //$mpdf = new mPDF('', 'A4', '', '', 15, 15, 15, 16, 9, 9, 'P');
    //     $mpdf = new \Mpdf\Mpdf();

    //     //  echo "<pre>";print_r($mpdf);die;
    //     $mpdf->SetDisplayMode('fullpage');

    //     if(isset($pdf['title'])) {
    //         $mpdf->SetTitle($pdf['title']);
    //     }
    //     if(isset($pdf['author'])) {
    //         $mpdf->SetAuthor($pdf['author']);
    //     }
    //     if(isset($pdf['creator'])) {
    //         $mpdf->SetCreator($pdf['creator']);
    //     }
    //     // if($pdf['badge'] == 'TRUE') {
    //     //     $mpdf->watermark_font = 'dejavusanscondensed';
    //     //     $mpdf->showWatermarkText = TRUE;
    //     // } else {
    //     //     $mpdf->showWatermarkText = FALSE;
    //     // }

    //     // Add a page
    //     $mpdf->AddPage();

    //     $mpdf->WriteHTML($pdf['html']);

    //     $mpdf->Output($pdf['filename'], 'I');
    //     exit;

    //     // if(isset($pdf['attach'])) {
    //     //     echo 122;die;
    //     //     $mpdf->Output(base_url().'uploads/tmp/'.$pdf['filename'], 'F');
    //     //     return base_url().'uploads/tmp/'.$pdf['filename'];
    //     // } else {
    //     //     echo 232;die;
    //     //     $mpdf->Output($pdf['filename'], 'D');
    //     //     exit;
    //     // }

    // }

    public function create_pdf($pdf)
    {
        // Initialize mPDF
        $mpdf = new \Mpdf\Mpdf();

        // Set document information
        $mpdf->SetTitle($pdf['title']);
        $mpdf->SetAuthor($pdf['author']);
        $mpdf->SetCreator($pdf['creator']);

        // Add a page
        $mpdf->AddPage();

        // Write HTML content
        $mpdf->WriteHTML($pdf['html']);

        // Output PDF
        $mpdf->Output($pdf['filename'], 'D');
        exit;
    }


    static function convert_currency($currency, $amount)
    {
        $session = \Config\Services::session();




        // Modify the 'default' property

        // Connect to the database
        $db = \Config\Database::connect();

        $custom_name_helper = new custom_name_helper();

        if (empty($currency)) {
            return $amount;
        }

        if ($currency == $custom_name_helper->getconfig_item('default_currency')) {
            return $amount;
        }

        $c = $db->table('hd_currencies')->where('code', $custom_name_helper->getconfig_item('default_currency'))->get()->getResult();
        $cur = $c[0];
        if ($cur->xrate > 0) {
            $in_local_cur = $amount * $cur->xrate;

            $xr = $db->table('hd_currencies')->where('code', $currency)->get()->getResult();
            $xr = $xr[0];
            $in_local = $in_local_cur / $xr->xrate;
            return $in_local;
            // return $amount * $cur->xrate;
        } else {
            return $amount;
        }
    }




    static function client_currency($currency, $amount)
    {

        $session = \Config\Services::session();





        // Modify the 'default' property    


        // Connect to the database  
        $db = \Config\Database::connect();
		
		//echo $currency;die;

        $custom_name_helper = new custom_name_helper();

        if (empty($currency)) {
            return $amount;
        }

        if ($currency == $custom_name_helper->getconfig_item('default_currency')) {
            return $amount;
        }
		
		//echo 243;die;

        $c = $db->table('hd_currencies')->where('code', $currency)->get()->getResult();

        $cur = $c[0];
        if ($cur->xrate > 0) {
            $dollars = $amount * $cur->xrate;
            $xr = $db->table('hd_currencies')->where('code', $custom_name_helper->getconfig_item('default_currency'))->get()->getResult();
            $xr = $xr[0];
            //print_r($xr);die;
            $in_local = $dollars / $xr->xrate;
            return Applib::format_deci($in_local);
        } else {
            return Applib::format_deci($amount);
        }
    }




    public static function format_currency($amount, $currency = null)
    {
        $session = \Config\Services::session();



        // Modify the 'default' property

        // Connect to the database
        $dbName = \Config\Database::connect();

        $builder = $dbName->table('hd_config');

        $row = $builder->select('value')
            ->where('config_key', $currency)
            ->get()
            ->getRow();

        $currency = isset($row->value) ? $row->value : null;

        $c = $dbName->table('hd_currencies')->where('code', $currency)->get()->getResult();

        $currency_position = $builder->select('value')
            ->where('config_key', 'currency_position')
            ->get()
            ->getRow();

        $currency_decimals = $builder->select('value')
            ->where('config_key', 'currency_decimals')
            ->get()
            ->getRow();

        $decimal_separator = $builder->select('value')
            ->where('config_key', 'decimal_separator')
            ->get()
            ->getRow();

        $thousand_separator = $builder->select('value')
            ->where('config_key', 'thousand_separator')
            ->get()
            ->getRow();



        if (!empty($c)) {
            $cur = $c[0];
            $pos = $currency_position->value;
            $dec = $currency_decimals->value;
            $dec_sep = $decimal_separator->value;
            $thou_sep = $thousand_separator->value;
            $cur_before = $cur->symbol;
            $cur_after = '';

            if ($pos == 'before') {
                $cur_before = $cur->symbol;
                $cur_after = '';
            }

            if ($pos == 'after') {
                $cur_before = '';
                $cur_after = $cur->symbol;
            }
            //return $cur_before . number_format($amount, $dec, $dec_sep, $thou_sep) . $cur_after;
			return $cur_before. number_format(floatval($amount), $dec, $dec_sep, $thou_sep). $cur_after;
        }

        return $amount; // Return the original amount if the currency is not found
    }
	
	public static function format_currency_client($amount, $currency = null)
    {
        $session = \Config\Services::session();

		// Connect to the database
        $dbName = \Config\Database::connect();
		
		$builder = $dbName->table('hd_config');

        $c = $dbName->table('hd_currencies')->where('code', $currency)->get()->getResult();

        $currency_position = $builder->select('value')
            ->where('config_key', 'currency_position')
            ->get()
            ->getRow();

        $currency_decimals = $builder->select('value')
            ->where('config_key', 'currency_decimals')
            ->get()
            ->getRow();

        $decimal_separator = $builder->select('value')
            ->where('config_key', 'decimal_separator')
            ->get()
            ->getRow();

        $thousand_separator = $builder->select('value')
            ->where('config_key', 'thousand_separator')
            ->get()
            ->getRow();



        if (!empty($c)) {
            $cur = $c[0];
            $pos = $currency_position->value;
            $dec = $currency_decimals->value;
            $dec_sep = $decimal_separator->value;
            $thou_sep = $thousand_separator->value;
            $cur_before = $cur->symbol;
            $cur_after = '';

            if ($pos == 'before') {
                $cur_before = $cur->symbol;
                $cur_after = '';
            }

            if ($pos == 'after') {
                $cur_before = '';
                $cur_after = $cur->symbol;
            }
            //return $cur_before . number_format($amount, $dec, $dec_sep, $thou_sep) . $cur_after;
			return $cur_before. number_format(floatval($amount), $dec, $dec_sep, $thou_sep). $cur_after;
        }

        return $amount; // Return the original amount if the currency is not found
    }

    // Format to decimal
    static function format_deci($num)
    {
        $num = str_replace(',', '.', $num);
        return number_format($num, 2, '.', '');
    }

    static function format_tax($amount)
    {
        $custom_name_helper = new custom_name_helper();

        $dec = $custom_name_helper->getconfig_item('tax_decimals');
        $dec_sep = $custom_name_helper->getconfig_item('decimal_separator');
        $thou_sep = $custom_name_helper->getconfig_item('thousand_separator');
        return number_format($amount, $dec, $dec_sep, $thou_sep);
    }

    static function format_quantity($amount)
    {

        $custom_name_helper = new custom_name_helper();

        $dec = $custom_name_helper->getconfig_item('quantity_decimals');
        $dec_sep = $custom_name_helper->getconfig_item('decimal_separator');
        $thou_sep = $custom_name_helper->getconfig_item('thousand_separator');
        return number_format($amount, $dec, $dec_sep, $thou_sep);
    }

    static function convert_datetime($str)
    {

        list($date, $time) = explode(' ', $str);
        list($year, $month, $day) = explode('-', $date);
        list($hour, $minute, $second) = explode(':', $time);
        $timestamp = mktime($hour, $minute, $second, $month, $day, $year);
        return $timestamp;
    }

    static function makeAgo($timestamp)
    {

        $difference = time() - $timestamp;
        $periods = array("sec", lang('hd_lang.minute'), lang('hd_lang.hour'), lang('hd_lang.day'), lang('hd_lang.week'), lang('hd_lang.month'), lang('hd_lang.year'), lang('hd_lang.decade'));
        $lengths = array("60", "60", "24", "7", "4.35", "12", "10");
        for ($j = 0; $difference >= $lengths[$j]; $j++)
            $difference /= $lengths[$j];
        $difference = round($difference);
        if ($difference != 1)
            $periods[$j] .= "s";
        $text = "$difference $periods[$j] ago";
        return $text;
    }

    static function time_elapsed_string($ptime)
    {
        $custom = new custom_name_helper();
        date_default_timezone_set($custom->getconfig_item('timezone'));
        $etime = time() - $ptime;

        if ($etime < 1) { 
            return '0 seconds';
        }

        $a = array(
            365 * 24 * 60 * 60  =>  'year',
            30 * 24 * 60 * 60  =>  'month',
            24 * 60 * 60  =>  'day',
            60 * 60  =>  'hour',
            60  =>  'minute',
            1  =>  'second'
        );
        $a_plural = array(
            'year'   => 'years',
            'month'  => 'months',
            'day'    => 'days',
            'hour'   => 'hours',
            'minute' => 'minutes',
            'second' => 'seconds'
        );

        foreach ($a as $secs => $str) {
            $d = $etime / $secs;
            if ($d >= 1) {
                $r = round($d);
                return $r . ' ' . ($r > 1 ? lang($a_plural[$str]) : lang($str)) . ' ' . lang('hd_lang.ago');
            }
        }
    }

    static function timeAgo($timestamp)
    {
        $now = new DateTime();
        $past = new DateTime($timestamp);
        $interval = $now->diff($past);

        $format = array();

        if ($interval->y > 0) {
            $format[] = $interval->y . ' year' . ($interval->y > 1 ? 's' : '');
        }
        if ($interval->m > 0) {
            $format[] = $interval->m . ' month' . ($interval->m > 1 ? 's' : '');
        }
        if ($interval->d > 0) {
            $format[] = $interval->d . ' day' . ($interval->d > 1 ? 's' : '');
        }
        if ($interval->h > 0) {
            $format[] = $interval->h . ' hour' . ($interval->h > 1 ? 's' : '');
        }
        if ($interval->i > 0) {
            $format[] = $interval->i . ' minute' . ($interval->i > 1 ? 's' : '');
        }
        if ($interval->s > 0) {
            $format[] = $interval->s . ' second' . ($interval->s > 1 ? 's' : '');
        }

        // Combine all the parts
        return implode(', ', $format) . ' ago';
    }


    public static function curl_exec_follow($ch, &$maxredirect = null)
    {

        $user_agent = "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.5)" .
            " Gecko/20041107 Firefox/1.0";
        curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);

        $mr = $maxredirect === null ? 5 : intval($maxredirect);

        if (
            filter_var(ini_get('open_basedir'), FILTER_VALIDATE_BOOLEAN) === false
            && filter_var(ini_get('safe_mode'), FILTER_VALIDATE_BOOLEAN) === false
        ) {

            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $mr > 0);
            curl_setopt($ch, CURLOPT_MAXREDIRS, $mr);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        } else {

            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);

            if ($mr > 0) {
                $original_url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
                $newurl = $original_url;

                $rch = curl_copy_handle($ch);

                curl_setopt($rch, CURLOPT_HEADER, true);
                curl_setopt($rch, CURLOPT_NOBODY, true);
                curl_setopt($rch, CURLOPT_FORBID_REUSE, false);
                do {
                    curl_setopt($rch, CURLOPT_URL, $newurl);
                    $header = curl_exec($rch);
                    if (curl_errno($rch)) {
                        $code = 0;
                    } else {
                        $code = curl_getinfo($rch, CURLINFO_HTTP_CODE);
                        if ($code == 301 || $code == 302) {
                            preg_match('/Location:(.*?)\n/i', $header, $matches);
                            $newurl = trim(array_pop($matches));


                            if (!preg_match("/^https?:/i", $newurl)) {
                                $newurl = $original_url . $newurl;
                            }
                        } else {
                            $code = 0;
                        }
                    }
                } while ($code && --$mr);

                curl_close($rch);

                if (!$mr) {
                    if ($maxredirect === null)
                        trigger_error('Too many redirects.', E_USER_WARNING);
                    else
                        $maxredirect = 0;

                    return false;
                }
                curl_setopt($ch, CURLOPT_URL, $newurl);
            }
        }
        return curl_exec($ch);
    }
}

/* End of file Applib.php */