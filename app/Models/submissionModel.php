<?php

namespace App\Models;

use CodeIgniter\Model;

class SubmissionModel extends Model
{
    protected $table = 'submissions';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'lecturer_id',    // references users.id (lecturer)
        'course_id',
        'assessment_id',
        'group_members',
        'filename',
        'uploaded_at',
    ];

    // Fetch submissions with course + assessment info
    public function getSubmissionsWithDetails()
    {
        return $this->select('submissions.*, courses.course_name, assessments.title AS assessment_title')
            ->join('courses', 'courses.id = submissions.course_id')
            ->join('assessments', 'assessments.id = submissions.assessment_id')
            ->findAll();
    }
}
