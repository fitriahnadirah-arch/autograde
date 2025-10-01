<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminCourseModel extends Model
{
    protected $table      = 'admin_courses';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'course_code',
        'course_name',
        'semester',
        'credit_hour',
        'coordinator_id',
        'session_id',
        'created_at',
        'updated_at'
    ];

    /**
     * Ambil semua courses dari session yang aktif sahaja
     * Boleh filter ikut course_code atau course_name kalau $query tak kosong
     *
     * @param string|null $query
     * @return array
     */
    public function getActiveCourses($query = null)
    {
        $builder = $this->select('
                admin_courses.*,
                users.username as coordinator_name,
                users.email as coordinator_email,
                academic_sessions.session_name,
                academic_sessions.status
            ')
            ->join('users', 'users.id = admin_courses.coordinator_id', 'left')
            ->join('academic_sessions', 'academic_sessions.id = admin_courses.session_id', 'left')
            ->where('academic_sessions.status', 'active'); // hanya session aktif

        if (!empty($query)) {
            $builder->groupStart()
                ->like('admin_courses.course_name', $query)
                ->orLike('admin_courses.course_code', $query)
                ->groupEnd();
        }

        return $builder->orderBy('admin_courses.course_name', 'ASC')->findAll();
    }

    /**
     * Ambil courses untuk seorang coordinator (ikut coordinator_id)
     * Hanya dari session yang aktif. Boleh tambah search query.
     *
     * @param int $coordinatorId
     * @param string|null $query
     * @return array
     */
    public function getCoordinatorCourses(int $coordinatorId, $query = null)
    {
        // Guard: pastikan coordinatorId bukan null
        if (!$coordinatorId) {
            return [];
        }

        $builder = $this->select('
                admin_courses.*,
                users.username as coordinator_name,
                users.email as coordinator_email,
                academic_sessions.session_name,
                academic_sessions.status
            ')
            ->join('users', 'users.id = admin_courses.coordinator_id', 'left')
            ->join('academic_sessions', 'academic_sessions.id = admin_courses.session_id', 'left')
            ->where('admin_courses.coordinator_id', $coordinatorId)
            ->where('academic_sessions.status', 'active'); // hanya session aktif

        if (!empty($query)) {
            $builder->groupStart()
                ->like('admin_courses.course_name', $query)
                ->orLike('admin_courses.course_code', $query)
                ->groupEnd();
        }

        return $builder->orderBy('admin_courses.course_name', 'ASC')->findAll();
    }
}
