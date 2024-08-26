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

class Company extends Model
{
    protected $table = 'hd_companies';
    protected $primaryKey = 'co_id';
	
	protected $allowedFields = [
        'company_ref',
        'company_name',
        'company_email',
        'language',
        'currency',
        'individual',
        'company_address',
        'company_phone',
        'city',
        'state',
        'zip',
        'country',
    ];


    public function get_by_user($uid)
    {
        return self::$db->where('primary_contact', $uid)->get('companies')->row();
	}
	
	public function listItems($array, $search, $perPage, $page)
    {	
        $db = \Config\Database::connect();

        // Begin the selection process using the model's query builder directly		
		$this->builder()
			->select('hd_companies.*, hd_account_details.*')
			->join('hd_account_details', 'hd_companies.primary_contact = hd_account_details.user_id');
		
        // If there's a search term, apply it across relevant fields
        if (!empty($search)) {
            $this->groupStart()
                ->like('hd_companies.company_ref', $search)
                ->orLike('hd_companies.company_name', $search)
                ->orLike('hd_companies.company_email', $search)
                ->orLike('hd_account_details.fullname', $search)
                ->groupEnd();
        }
		
		// echo $perPage;die;
		// $perPage = 10;
		
        // Utilize the model's built-in pagination
        $data = $this->paginate($perPage); 
		
		// $data = [];
		
		// echo "<pre>";print_r($data);die;
		
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