<?php
namespace App\Models;

use CodeIgniter\Model;

class CourseModel extends Model
{
    protected $table      = 'admin_courses'; 
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'course_code',
        'course_name',
        'semester',
        'credit_hour',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
}
