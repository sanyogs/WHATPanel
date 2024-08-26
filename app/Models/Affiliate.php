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

class Affiliate extends Model
{
    protected $table = 'hd_affiliates';
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
        parent::__construct($db);

        if ($db !== null) {
            $this->DBGroup = $db->getDatabase();
        }
    }

    public static function account($id)
    {
        return self::$db
            ->select('orders.date, items_saved.item_name, referrals.amount, referrals.commission, referrals.type, status')
            ->from('referrals')
            ->join('orders', 'referrals.order_id = orders.id', 'LEFT')
            ->join('items_saved', 'items_saved.item_id = orders.item_parent', 'LEFT')
            ->join('status', 'orders.status_id = status.id', 'LEFT')
            ->where('referrals.affiliate_id', $id)
            ->orderBy('orders.date', 'desc')
            ->get()
            ->getResult();
    }

    public static function balance($id)
    {
        return self::$db
            ->select('SUM(hd_referrals.commission) as balance')
            ->from('referrals')
            ->join('orders', 'referrals.order_id = orders.id', 'LEFT')
            ->where('orders.status_id', 6)
            ->where('orders.affiliate_paid', 0)
            ->where('referrals.affiliate_id', $id)
            ->get()
            ->getResult();
    }

    public static function withdrawals($id)
    {
        return self::$db
            ->select('hd_affiliates.*, company_name')
            ->from('affiliates')
            ->join('companies', 'companies.co_id = affiliates.client_id', 'LEFT')
            ->where('client_id', $id)
            ->orderBy('withdrawal_id', 'desc')
            ->get()
            ->getResult();
    }

    public static function withdrawal($id)
    {
        return self::$db
            ->select('hd_affiliates.*, company_name')
            ->from('affiliates')
            ->join('companies', 'companies.co_id = affiliates.client_id', 'LEFT')
            ->where('client_id', $id)
            ->where('payment_date', NULL)
            ->limit(1)
            ->get()
            ->getRow();
    }

    public static function all()
    {
        return self::$db
            ->where(['co_id >' => 1, 'affiliate' => 1])
            ->orderBy('company_name', 'ASC')
            ->get('companies')
            ->getResult();
    }
}