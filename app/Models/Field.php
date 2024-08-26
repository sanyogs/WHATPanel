<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\MyConfig;

class Field extends Model
{
    protected $table = 'hd_fields';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
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

    public function __construct()
    {
        parent::__construct();
    }
}