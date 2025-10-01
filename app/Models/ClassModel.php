<?php

namespace App\Models;

use CodeIgniter\Model;

class ClassModel extends Model
{
    protected $table      = 'classes';
    protected $primaryKey = 'id';
    protected $allowedFields = ['course_id', 'class_name', 'created_at', 'updated_at'];
}
