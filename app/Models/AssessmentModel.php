<?php

namespace App\Models;

use CodeIgniter\Model;

class AssessmentModel extends Model
{
    protected $table      = 'assessments'; 
    protected $primaryKey = 'id';

    // ✅ List all columns that can be inserted/updated
    protected $allowedFields = [
        'course_id',
        'title',
        'type',
        'weight',
        'due_date',
        'question_file', // ✅ important for saving uploaded file name
        'created_at',
        'updated_at'
    ];

    // ✅ Let CodeIgniter manage created_at & updated_at automatically
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
