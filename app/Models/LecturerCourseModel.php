<?php
namespace App\Models;

use CodeIgniter\Model;

class LecturerCourseModel extends Model
{
    protected $table = 'course_lecturer_class';
    protected $primaryKey = 'id';
    protected $allowedFields = ['course_id', 'lecturer_id', 'class_id', 'created_at', 'updated_at'];
    protected $useTimestamps = true;

    // All lecturers assigned to a course
    public function getLecturersByCourse($courseId)
    {
        return $this->select('users.id, users.username, users.email')
            ->join('users', 'users.id = course_lecturer_class.lecturer_id')
            ->where('course_lecturer_class.course_id', $courseId)
            ->findAll();
    }
}
