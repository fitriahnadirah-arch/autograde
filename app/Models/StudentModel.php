<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentModel extends Model
{
    protected $table            = 'students';   
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $allowedFields    = [
        'course_id',
        'class_id',  
        'student_id',
        'student_name',
        'created_at',
        'updated_at'
    ];
}
