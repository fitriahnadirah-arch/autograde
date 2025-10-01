<?php
namespace App\Models;
use CodeIgniter\Model;

class GradeModel extends Model
{
    protected $table      = 'grades';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'submission_id', 'score', 'feedback', 'graded_at'
    ];
}
