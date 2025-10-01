<?php
namespace App\Models;

use CodeIgniter\Model;

class CourseLecturerClassModel extends Model
{
    protected $table = 'course_lecturer_class';
    protected $primaryKey = 'id';
    protected $allowedFields = ['course_id', 'lecturer_id', 'class_id', 'created_at', 'updated_at'];
    protected $useTimestamps = true;

    // All courses for a lecturer (with class info)
    public function getCoursesByLecturer($lecturerId)
    {
        return $this->select('
                course_lecturer_class.id as course_assignment_id,
                admin_courses.course_code,
                admin_courses.course_name,
                admin_courses.semester,
                admin_courses.credit_hour,
                classes.id as class_id,
                classes.class_name
            ')
            ->join('admin_courses', 'admin_courses.id = course_lecturer_class.course_id')
            ->join('classes', 'classes.id = course_lecturer_class.class_id', 'left')
            ->where('course_lecturer_class.lecturer_id', $lecturerId)
            ->findAll();
    }
}
