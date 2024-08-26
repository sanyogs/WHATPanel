<?php
 /*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */


namespace App\Models;

use CodeIgniter\Model;
use DateTime;
use DateTimeZone;
use App\Models\App;
use App\ThirdParty\MX\Lang;
use CodeIgniter\Files\File;
use App\Helpers\file_helper;

class Setting extends Model
{
    protected $table = 'hd_settings';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    protected $DBGroup;

    public function __construct($db = null)
    {
        error_reporting(E_ALL);
        ini_set("display_errors", "1");
        parent::__construct($db);

        if ($db !== null) {
            $this->DBGroup = $db->getDatabase();
        }
    }


    public static function updateTemplate($group, $data)
    {
        return self::$db->where('email_group', $group)->update('email_templates', $data);
    }

    function save_translation($post = array())
    {
        $db = \Config\Database::connect();

        $langMX = new Lang();

        $data = '';
        helper('filesystem');
        $language = $post['_language'];
        //$lang = $db->table('hd_locales')
           // ->select('hd_locales.*, hd_locales.code as locale_code, hd_languages.code, hd_languages.name as name1')
           // ->join('hd_languages', 'hd_languages.code = hd_locales.code', 'left')
           // ->where('hd_languages.name', $language)
           // ->get()
           // ->getRow();
		$lang = $db->table('hd_languages')->where('name', $language)->get()->getRow();
        // $lang = $lang[0];
        $slug = strtolower(str_replace("_", "-", $lang->locale));
        //echo "<pre>";print_r($slug);die;
        $file = $post['_file'];
        $altpath = $post['_path'];
        if ($slug == 'en') {
            if($file == 'hd' || $file == 'tank_auth')
            {
                // echo 132;die;
                $fullpath = $altpath . "en/" . $file . "_lang.php";
            }
            else {
                // echo 456;die;
                $fullpath = $altpath . "en/" . $file . ".php";
            }
        } else {
			//echo $slug;die;
            if($file == 'hd' || $file == 'tank_auth')
            {
                $fullpath = APPPATH . "Language/" . $slug . "/" . $file . "_lang.php";
            }
            else {
                $fullpath = APPPATH . "Language/" . $slug . "/" . $file . ".php";
            }
        }
		//echo $fullpath;die;
        $eng = $langMX->load($file, 'en', TRUE, TRUE, $altpath);
        if ($language == 'en') {
            $trn = $eng;
        } else {
            $trn = $langMX->load($file, $language, TRUE, TRUE);
        }
        $dataArray = [];

		foreach ($eng as $key => $value) {
			if (isset($post[$key])) {
				$newvalue = $post[$key];
			} elseif (isset($trn[$key])) {
				$newvalue = $trn[$key];
			} else {
				$newvalue = $value;
			}

			$nvalue = str_replace("'", "\'", $newvalue);
			$dataArray[$key] = $nvalue;
		}

		// Generate the PHP array as a string
		$data = "<?php\n\n";
		$data .= "/**\n * Locale for $file \n */\n";
		$data .= "return [\n";

		foreach ($dataArray as $key => $nvalue) {
			$data .= "    '$key' => '$nvalue',\n";
		}

		$data .= "];\n";

		// Add file-specific footer if needed
		if ($file == 'hd') {
			$data .= "\nif(file_exists(APPPATH.'/Language/" . $language . "/custom_language.php')){\n";
			$data .= "    include APPPATH.'/Language/" . $language . "/custom_language.php';\n";
			$data .= "}\n\n";
			$data .= "/* End of file hd_lang.php */";
		} else {
			if($file == 'hd' || $file == 'tank_auth')
			{
				$data .= "/* End of file " . $file . "_lang.php */\n";
				$data .= "/* Location: app/Language/" . $language . "/" . $file . "_lang.php */\n";
			}
			else
			{
				$data .= "/* End of file " . $file . ".php */\n";
				$data .= "/* Location: app/Language/" . $slug . "/" . $file . ".php */\n";
			}
		}

		// Write the data to the file
		write_file($fullpath, $data);

        if ($file == 'hd') {
            $data2 = '';
            $keys = array(
                'invoice', 'reference_no', 'date_issued', 'due_date', 'from', 'to',
                'item_name', 'amount', 'vat', 'tax', 'price', 'discount', 'total', 'paid', 'balance_due',
                'payment_information', 'notes', 'partially_paid', 'fully_paid', 'not_paid', 'draft',
                'accepted', 'declined', 'pending', 'page', 'page_of'
            );
            foreach ($keys as $key) {
                $value = $post[$key];
                $value = str_replace("'", "\'", $value);
                $data2 .= '$l[\'' . $key . '\'] = \'' . $value . '\';' . "\r\n";
            }
            $data2 = '<?php' . "\r\n" . $data2;
            write_file('./application/modules/fopdf/helpers/languages/' . $lang->code . '.inc', $data2);
        }
        return true;
    }


    public function backup_translation($language_id, $files)
    {
        helper(['file', 'language']);
		
		$db = \Config\Database::connect();
		
		$lang_data = $db->table('hd_languages')->where('lang_id', $language_id)->get()->getRow();
			
		$slug = strtolower(str_replace("_", "-", $lang_data->locale));

		$path = APPPATH . 'Language/' . $slug . '/' . $lang_data->name . '-backup.json';
		$strings = [];
		
		echo $path;die;
		
		foreach ($files as $file => $altpath) {
			if ($lang_data->code !== 'en') {
				$altpath = APPPATH;
			}
			
			if($file == 'hd_lang.php' || $file == 'tank_auth_lang.php')
			{
                $file = str_replace("_lang.php", "", $file);
			}
			else
			{
				$file = str_replace(".php", "", $file);
			}
			
			$lan = new Lang();
            $strings[$file] = $lan->load($shortfile, $slug, TRUE, TRUE, $altpath);
			
			$json = json_encode($strings);
			
			$result = write_file($path, $json);
			
			return $result;
		}
    }

    public function addTranslation($language, $files)
    {
        // Implement your addTranslation logic here
    }


    static function timezones()
    {
        $timezoneIdentifiers = DateTimeZone::listIdentifiers();
        $utcTime = new DateTime('now', new DateTimeZone('UTC'));

        $tempTimezones = array();
        foreach ($timezoneIdentifiers as $timezoneIdentifier) {
            $currentTimezone = new DateTimeZone($timezoneIdentifier);

            $tempTimezones[] = array(
                'offset' => (int) $currentTimezone->getOffset($utcTime),
                'identifier' => $timezoneIdentifier
            );
        }

        $timezoneList = array();
        foreach ($tempTimezones as $tz) {
            $sign = ($tz['offset'] > 0) ? '+' : '-';
            $offset = gmdate('H:i', abs($tz['offset']));
            $timezoneList[$tz['identifier']] = 'UTC ' . $sign . $offset . ' - ' .
                $tz['identifier'];
        }

        return $timezoneList;
    }


    static function update_template($group, $data)
    {
        return self::$db->where('email_group', $group)->update('email_templates', $data);
    }

    function translation_stats($files)
    {
        $stats = array();
        $fstats = array();
        //$app_lang = App::languages();
        $app_lang = App::languages_locale();
		//echo "<pre>";print_r($app_lang);die;
        foreach ($app_lang as $lang) {
            $slug = strtolower(str_replace("_", "-", $lang->locale));
            //$lang = $lang->name;
			$lang = $lang->language;
            $translated = 0;
            $total = 0;
            foreach ($files as $file => $altpath) {
                $diff = 0;
                $count = 0;
				if($file == 'hd_lang.php' || $file == 'tank_auth_lang.php')
				{
                	$shortfile = str_replace("_lang.php", "", $file);
				}
				else
				{
					$shortfile = str_replace(".php", "", $file);
				}
                $lan = new Lang();
                $en = $lan->load($shortfile, 'en', TRUE, TRUE, $altpath);
                // echo "<pre>";print_r($en);die;
                if ($slug != 'en') {
                    // echo $shortfile;die;
                    // echo $slug;die;
						
					$tr = $lan->load($shortfile, $slug, TRUE, TRUE, $altpath);
					
                    foreach ($en as $key => $value) {
                        $translation = isset($tr[$key]) ? $tr[$key] : $value;
                        if (!empty($translation) && $translation != $value) {
                            // echo 8 . ' ';
                            $diff++;
                        }
                        // echo 133;die;
                        $count += 1;
                    }
                    // die;

                    // die;
                    $fstats[$shortfile] = array(
                        "total" => $count,
                        "translated" => $diff,
                    );
                } else {
                    $diff = $count = count($en);
                    $fstats[$shortfile] = array(
                        "total" => count($en),
                        "translated" =>  $diff,
                    );
                }
                $total += $count;
                $translated += $diff;
            }

            $stats[$lang]['total'] = $total;
            $stats[$lang]['translated'] = $translated;
            $stats[$lang]['files'] = $fstats;
        }
        //echo "<pre>";print_r($stats);die;
        return $stats;
    }

    function add_translation($language, $files)
    {
        error_reporting(E_ALL);
        ini_set("display_errors", "1");
        // print_r($files);die;
        $helper = new file_helper();
        helper('directory');
        $db = \Config\Database::connect();
        //$lang = $db->table('hd_locales')->where('language', str_replace("_", " ", $language))->get()->getResult();
		$lang = $db->table('hd_locales')->where('locale', $language)->get()->getResult();
        //echo "<pre>";print_r($lang);die;
        $l = $lang[0];
        //$slug = strtolower(str_replace(" ", "_", $language));
        $slug = strtolower(str_replace("_", "-", $l->locale));
        //print_r($slug);die;
        $dirpath = APPPATH . 'Language/' . $slug;
        //print_r($dirpath);die;
        $icon = explode("_", $l->locale);
        //r
        //print_r($icon);die;
        if (isset($icon[1])) {
            $icon = strtolower($icon[1]);
        } else {
            $icon = strtolower($icon[0]);
        }

        if (is_dir($dirpath)) {
            deleteDirectory($dirpath);
        }
        mkdir($dirpath, 0755, true);

        foreach ($files as $file => $path) {
            // print_r($files);die;
            $source = $path . 'Language/' . 'en/' . $file;
            $destin = APPPATH . 'Language/' . $slug . '/' . $file;
            $data = file_get_contents($source);
            $data = str_replace('/en/', '/' . $slug . '/', $data);
            // print_r($data);die;
            $data = str_replace('system/Language', 'app/Language', $data);
            // print_r($data);die;
            //$dataAsString = implode("\n", $data);

            try {
                helper('filesystem');
                write_file($destin, $data);
            } catch (\Exception $e) {
                echo $e;
                die;
            }
        }
		
        //$data = file_get_contents(APPPATH . '/modules/fopdf/helpers/languages/en.inc');
        //print_r($data);die;
        //helper('filesystem');
        //write_file(APPPATH . '/modules/fopdf/helpers/languages/' . $l->code . '.inc', $data);
		
		// Replace hyphen with underscore
		$slug = str_replace('-', '_', $slug);

		// Split the slug by underscore
		$parts = explode('_', $slug);

		// Convert the second part to uppercase if it exists
		if (isset($parts[1])) {
			$parts[1] = strtoupper($parts[1]);
		}

		// Join the parts back together with underscore
		$newLocale = implode('_', $parts);
		
		$exists = $db->table('hd_languages')->where('code', $l->code)->where('name', $l->language)->where('locale_name', $l->name)->get()->getRow();

		if ($exists) {
			$db->table('hd_languages')->where('code', $l->code)->where('name', $l->language)->delete();
		}

        $insert = array(
            'code' => $l->code,
			'name' => $l->language,
            'locale_name' => $l->name,
			'locale' => $newLocale,
            'icon' => $icon,
            'active' => 0
        );
		
		$db->table('hd_languages')->insert($insert);
		
		//echo "<pre>";print_r($insert);die;
        return json_encode(array('status' => 200, 'msg' => 'Added Successfully'));
    }
}

// Function to delete files and subdirectories in the directory
function deleteDirectory($dir) {
    if (!is_dir($dir)) {
        return false;
    }
    // Get all contents of the directory
    $files = array_diff(scandir($dir), ['.', '..']);
    foreach ($files as $file) {
        // If it's a directory, call the function recursively
        if (is_dir("$dir/$file")) {
            deleteDirectory("$dir/$file");
        } else {
            // If it's a file, delete it
            unlink("$dir/$file");
        }
    }
    // Delete the directory itself
    return rmdir($dir);
}
