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

class Servers extends Model
{
    protected $table = 'hd_servers';
    protected $primaryKey = 'id';

    public function listItems($array, $search, $perPage, $page)
    {
        $db = \Config\Database::connect();

        // Begin the selection process using the model's query builder directly
        $this->select('hd_servers.*');

        // If there's a search term, apply it across relevant fields
        if (!empty($search)) {
            $this->groupStart()
                ->like('hd_servers.type', $search)
                ->orLike('hd_servers.name', $search)
                ->orLike('hd_servers.hostname', $search)
                ->orLike('hd_servers.port', $search)
                ->orLike('hd_servers.username', $search)
                ->groupEnd();
        }

        // Utilize the model's built-in pagination
        $data = $this->paginate($perPage);

        // Check if the result set is empty
        if (empty($data)) {
            $message = 'No items found';
        } else {
            $message = '';
        }

        // The pager is directly accessible via $this->pager after pagination is called
        return [
            'items' => $data,
            'pager' => $this->pager,
            'message' => $message
        ];
    }

}