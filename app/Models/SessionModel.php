<?php
namespace App\Models;

use CodeIgniter\Model;

class SessionModel extends Model
{
    protected $table = 'academic_sessions';
    protected $primaryKey = 'id';
    protected $allowedFields = ['session_name', 'status'];

    public function getActiveSession()
    {
        return $this->where('status', 'active')->first();
    }
}
