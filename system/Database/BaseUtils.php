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
 * This file is part of CodeIgniter 4 framework.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace CodeIgniter\Database;

use App\Helpers\custom_name_helper;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\HTTP\Response;
use ZipArchive;

/**
 * Class BaseUtils
 */
abstract class BaseUtils
{
    /**
     * Database object
     *
     * @var object
     */
    protected $db;

    /**
     * List databases statement
     *
     * @var bool|string
     */
    protected $listDatabases = false;

    /**
     * OPTIMIZE TABLE statement
     *
     * @var bool|string
     */
    protected $optimizeTable = false;

    /**
     * REPAIR TABLE statement
     *
     * @var bool|string
     */
    protected $repairTable = false;

    /**
     * Class constructor
     */
    public function __construct(ConnectionInterface $db)
    {
        $this->db = $db;
    }

    /**
     * List databases
     *
     * @return array|bool
     *
     * @throws DatabaseException
     */
    public function listDatabases()
    {
        // Is there a cached result?
        if (isset($this->db->dataCache['db_names'])) {
            return $this->db->dataCache['db_names'];
        }

        if ($this->listDatabases === false) {
            if ($this->db->DBDebug) {
                throw new DatabaseException('Unsupported feature of the database platform you are using.');
            }

            return false;
        }

        $this->db->dataCache['db_names'] = [];

        $query = $this->db->query($this->listDatabases);
        if ($query === false) {
            return $this->db->dataCache['db_names'];
        }

        for ($i = 0, $query = $query->getResultArray(), $c = count($query); $i < $c; $i++) {
            $this->db->dataCache['db_names'][] = current($query[$i]);
        }

        return $this->db->dataCache['db_names'];
    }

    /**
     * Determine if a particular database exists
     */
    public function databaseExists(string $databaseName): bool
    {
        return in_array($databaseName, $this->listDatabases(), true);
    }

    /**
     * Optimize Table
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function optimizeTable(string $tableName)
    {
        if ($this->optimizeTable === false) {
            if ($this->db->DBDebug) {
                throw new DatabaseException('Unsupported feature of the database platform you are using.');
            }

            return false;
        }

        $query = $this->db->query(sprintf($this->optimizeTable, $this->db->escapeIdentifiers($tableName)));

        return $query !== false;
    }

    /**
     * Optimize Database
     *
     * @return mixed
     *
     * @throws DatabaseException
     */
    public function optimizeDatabase()
    {
        if ($this->optimizeTable === false) {
            if ($this->db->DBDebug) {
                throw new DatabaseException('Unsupported feature of the database platform you are using.');
            }

            return false;
        }

        $result = [];

        foreach ($this->db->listTables() as $tableName) {
            $res = $this->db->query(sprintf($this->optimizeTable, $this->db->escapeIdentifiers($tableName)));
            if (is_bool($res)) {
                return $res;
            }

            // Build the result array...

            $res = $res->getResultArray();

            // Postgre & SQLite3 returns empty array
            if (empty($res)) {
                $key = $tableName;
            } else {
                $res  = current($res);
                $key  = str_replace($this->db->database . '.', '', current($res));
                $keys = array_keys($res);
                unset($res[$keys[0]]);
            }

            $result[$key] = $res;
        }

        return $result;
    }

    /**
     * Repair Table
     *
     * @return mixed
     *
     * @throws DatabaseException
     */
    public function repairTable(string $tableName)
    {
        if ($this->repairTable === false) {
            if ($this->db->DBDebug) {
                throw new DatabaseException('Unsupported feature of the database platform you are using.');
            }

            return false;
        }

        $query = $this->db->query(sprintf($this->repairTable, $this->db->escapeIdentifiers($tableName)));
        if (is_bool($query)) {
            return $query;
        }

        $query = $query->getResultArray();

        return current($query);
    }

    /**
     * Generate CSV from a query result object
     *
     * @return string
     */
    public function getCSVFromResult(ResultInterface $query, string $delim = ',', string $newline = "\n", string $enclosure = '"')
    {
        $out = '';

        foreach ($query->getFieldNames() as $name) {
            $out .= $enclosure . str_replace($enclosure, $enclosure . $enclosure, $name) . $enclosure . $delim;
        }

        $out = substr($out, 0, -strlen($delim)) . $newline;

        // Next blast through the result array and build out the rows
        while ($row = $query->getUnbufferedRow('array')) {
            $line = [];

            foreach ($row as $item) {
                $line[] = $enclosure . str_replace($enclosure, $enclosure . $enclosure, $item ?? '') . $enclosure;
            }

            $out .= implode($delim, $line) . $newline;
        }

        return $out;
    }

    /**
     * Generate XML data from a query result object
     */
    public function getXMLFromResult(ResultInterface $query, array $params = []): string
    {
        foreach (['root' => 'root', 'element' => 'element', 'newline' => "\n", 'tab' => "\t"] as $key => $val) {
            if (! isset($params[$key])) {
                $params[$key] = $val;
            }
        }

        $root    = $params['root'];
        $newline = $params['newline'];
        $tab     = $params['tab'];
        $element = $params['element'];

        helper('xml');
        $xml = '<' . $root . '>' . $newline;

        while ($row = $query->getUnbufferedRow()) {
            $xml .= $tab . '<' . $element . '>' . $newline;

            foreach ($row as $key => $val) {
                $val = (! empty($val)) ? xml_convert($val) : '';

                $xml .= $tab . $tab . '<' . $key . '>' . $val . '</' . $key . '>' . $newline;
            }

            $xml .= $tab . '</' . $element . '>' . $newline;
        }

        return $xml . '</' . $root . '>' . $newline;
    }

    /**
     * Database Backup
     *
     * @param array|string $params
     *
     * @return false|never|string
     *
     * @throws DatabaseException
     */
    public function backup($params = [])
    {	
		$helper = new custom_name_helper();
        if (is_string($params)) {
            $params = ['tables' => $params];
        }

        $prefs = [
            'tables'             => [],
            'ignore'             => [],
            'filename'           => '',
            'format'             => 'gzip', // gzip, txt
            'add_drop'           => true,
            'add_insert'         => true,
            'newline'            => "\n",
            'foreign_key_checks' => true,
        ];
		
        if (! empty($params)) {
            foreach (array_keys($prefs) as $key) {
                if (isset($params[$key])) {
                    $prefs[$key] = $params[$key];
                }
            }
        }

        if (empty($prefs['tables'])) {
            $prefs['tables'] = $this->db->listTables();
        }

        if (! in_array($prefs['format'], ['gzip','zip', 'txt'], true)) {
            $prefs['format'] = 'txt';
        }

        if ($prefs['format'] === 'gzip' && ! function_exists('gzencode')) {
            if ($this->db->DBDebug) {
                throw new DatabaseException('The file compression format you chose is not supported by your server.');
            }

            $prefs['format'] = 'txt';
        }

        if ($prefs['format'] === 'txt') {
            return $this->_backup($prefs);
        }
	
		// Was a Zip file requested?
            if ($prefs['format'] === 'zip') {
                // Set the filename if not provided (only needed with Zip files)
                if ($prefs['filename'] === '') {
                    $db = \Config\Database::connect();
                    $prefs['filename'] = (count($prefs['tables']) === 1 ? $prefs['tables'] : $db->database)
                        . date('Y-m-d_H-i', time()) . '.sql';
                } else {
                    // If they included the .zip file extension we'll remove it
                    if (preg_match('|.+?\.zip$|', $prefs['filename'])) {
                        $prefs['filename'] = str_replace('.zip', '', $prefs['filename']);
                        
                    }

                    // Tack on the ".sql" file extension if needed
                    if (!preg_match('|.+?\.sql$|', $prefs['filename'])) {
                        $prefs['filename'] .= '.sql';
                    }
                }

                // Load the Zip class and output it
                $zip = new ZipArchive;
                $helper = new custom_name_helper();
                $backup_data = $helper->_backup($prefs);

                $zip->open($prefs['filename']. '.zip', ZipArchive::CREATE);
                $zip->addFromString($prefs['filename'], $backup_data);
                $zip->close();
                
                header('Content-Type: application/zip');
                header('Content-Disposition: attachment; filename="' . $prefs['filename'] . '.zip"');
                header('Content-Length: ' . filesize($prefs['filename'] . '.zip'));
                readfile($prefs['filename'] . '.zip');
            }
        // @TODO gzencode() requires `ext-zlib`, but _backup() is not implemented in all databases.
        return gzencode($this->_backup($prefs));
    }

    /**
     * Platform dependent version of the backup function.
     *
     * @return false|never|string
     */
    abstract public function _backup(?array $prefs = null);
}
