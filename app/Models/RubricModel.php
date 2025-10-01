<?php

namespace App\Models;
use CodeIgniter\Model;

class RubricModel extends Model
{
    protected $table = 'rubrics';
    protected $primaryKey = 'id';
    protected $allowedFields = ['assessment_id','criteria','weight','scale_5','scale_4','scale_3','scale_2','scale_1'];
}