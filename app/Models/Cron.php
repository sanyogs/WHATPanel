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

class Cron extends Model
{
	protected $table = 'hd_crons';
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

	public function overdue_invoices()
	{
		$invoices = array();
		$this->db->join('companies', 'companies.co_id = invoices.client');
		$invoices = $this->db->where(
			array(
				'inv_deleted' => 'No',
				'alert_overdue' => '0',
				'due_date <' => date('Y-m-d')
			)
		)->get('invoices')->result();

		foreach ($invoices as $key => &$invoice) {
			if (Invoice::payment_status($invoice->inv_id) == 'fully_paid') {
				unset($invoices[$key]);
			}
		}

		return $invoices;
	}

	public function get_renewals()
	{
		$days = config_item('invoices_due_before');
		$today = date('Y-m-d');
		$renewal = Cron::get_processing_date($today, $days);
		$cut_off = date('Y-m-d', strtotime('-10 days'));

		$list = array();
		$accounts = array();
		$this->db->join('companies', 'companies.co_id = orders.client_id');
		$this->db->join('items', 'items.item_id = orders.item');
		$this->db->join('item_pricing', 'orders.item_parent = item_pricing.item_id', 'LEFT');
		$this->db->select('orders.*, items.*, monthly_payments,	quarterly_payments,	semi_annually_payments,	annually_payments, biennially_payments, triennially_payments');
		$accounts = $this->db->where(
			array(
				'status_id' => 6,
				'renewal_date <=' => $renewal,
				'renewal_date >' => $cut_off,
				'processed <' => $cut_off
			)
		)->get('orders')->result();

		foreach ($accounts as $account) {
			if ($account->type == 'domain' || $account->type == 'domain_only') {
				$list[] = $account;
				continue;
			}

			$renewal = $account->renewal;
			$payments = $renewal . '_payments';
			$max_payments = $account->$payments;

			if ($renewal . '_payments' > 0 && $account->counter < $max_payments) {
				$account->payments = $max_payments;
				$list[] = $account;
			} else {
				$list[] = $account;
			}
		}

		return $list;

	}

	public function get_suspensions()
	{
		$accounts = array();

		if (config_item('suspend_due') == 'TRUE') {
			$today = date('Y-m-d');

			$this->db->join('invoices', 'orders.invoice_id = invoices.inv_id', 'INNER');
			$this->db->join('items_saved', 'orders.item_parent = items_saved.item_id', 'LEFT');
			$list = $this->db->where(
				array(
					'status_id' => 6,
					'due_date <' => $today,
					'status' => 'Unpaid',
					'inv_deleted' => 'No'
				)
			)->get('orders')->result();

			foreach ($list as $li) {
				$invoice_date_due = new DateTime($li->due_date);
				$invoice_date_due->add(new DateInterval('P' . config_item('suspend_after') . 'D'));
				$suspend_date = $invoice_date_due->format('Y-m-d');

				if (strtotime($today) > strtotime($suspend_date)) {
					$accounts[] = $li;
				}
			}

			return $accounts;
		} else {
			return $accounts;
		}

	}
	public function get_terminations()
	{
		$accounts = array();

		if (config_item('terminate_due') == 'TRUE') {
			$today = date('Y-m-d');

			$this->db->join('invoices', 'orders.invoice_id = invoices.inv_id', 'INNER');
			$this->db->join('items_saved', 'orders.item_parent = items_saved.item_id', 'LEFT');
			$list = $this->db->where(
				array(
					'status_id >' => 6,
					'due_date <' => $today,
					'status' => 'Unpaid',
					'inv_deleted' => 'No'
				)
			)->get('orders')->result();

			foreach ($list as $li) {
				$invoice_date_due = new DateTime($li->due_date);
				$invoice_date_due->add(new DateInterval('P' . config_item('terminate_after') . 'D'));
				$terminate_date = $invoice_date_due->format('Y-m-d');

				if (strtotime($today) > strtotime($terminate_date)) {
					$accounts[] = $li;
				}
			}

			return $accounts;
		} else {
			return $accounts;
		}

	}

	public static function get_processing_date($date, $days)
	{
		$renewal_date = new DateTime($date);
		$renewal_date->add(new DateInterval('P' . $days . 'D'));
		return $renewal_date->format('Y-m-d');
	}

	public static function get_date_due($invoice_date_created)
	{
		$invoice_date_due = new DateTime($invoice_date_created);
		$invoice_date_due->add(new DateInterval('P' . config_item('invoices_due_after') . 'D'));
		return $invoice_date_due->format('Y-m-d');
	}
}