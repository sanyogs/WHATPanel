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

class Item_pricing extends Model
{
    protected $table = 'hd_item_pricing';
    protected $primaryKey = 'item_pricing_id';

    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'ext_name', 'item_id', 'category', 'currency_amt', 
        'monthly', 'quarterly', 'semi_annually', 'annually', 
        'biennially', 'triennially', 'registration', 
        'transfer', 'renewal', 'monthly_payments', 
        'quarterly_payments', 'semi_annually_payments', 
        'annually_payments', 'biennially_payments', 'triennially_payments'
    ];
}




?>