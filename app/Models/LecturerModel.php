<?php
namespace App\Models;

use CodeIgniter\Model;

class LecturerModel extends Model
{
    protected $table      = 'users';   // lecturers are stored in users
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'username',
        'email',
        'password',
        'role',
    ];

    public function getAllLecturers()
    {
        return $this->where('role', 'lecturer')->findAll();
    }
}
