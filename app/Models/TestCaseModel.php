<?php

namespace App\Models;

use CodeIgniter\Model;

class TestCaseModel extends Model
{
    protected $table = 'test_cases';
    protected $primaryKey = 'id';
    protected $allowedFields = ['assessment_id', 'input', 'expected_output'];
}
