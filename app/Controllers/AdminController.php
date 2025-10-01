<?php

namespace App\Controllers;

use App\Models\AdminCourseModel; 
use App\Models\AssessmentModel;
use App\Models\LecturerCourseModel;
use App\Models\SubmissionModel;
use App\Models\UserModel;
use App\Models\SessionModel; 

class AdminController extends BaseController
{
    // --- Admin dashboard ---
    public function dashboard()
    {
        $courseModel     = new AdminCourseModel(); 
        $assessmentModel = new AssessmentModel();
        $submissionModel = new SubmissionModel();

        // Hanya ambil course dari session aktif
        $activeCourses = $courseModel->getActiveCourses();

        $data = [
            'total_courses'     => count($activeCourses),
            'total_assessments' => $assessmentModel->countAll(),
            'total_submissions' => $submissionModel->countAll(),
            'courses'           => $activeCourses,
        ];

        return view('admin/dashboard', $data);
    }

    // --- Courses page ---
    public function courses()
    {
        $courseModel  = new AdminCourseModel();
        $userModel    = new UserModel();
        $sessionModel = new SessionModel();

        $query = $this->request->getGet('query'); 

        // Ambil semua coordinator
        $data['coordinators'] = $userModel
            ->select('id, username, email')
            ->like('role', 'course_coordinator')
            ->findAll();

        // Ambil hanya session aktif
        $data['sessions'] = $sessionModel->where('status', 'active')->findAll();

        // Ambil courses dari session aktif sahaja
        $data['courses'] = $courseModel->getActiveCourses($query);
        $data['query']   = $query;

        return view('admin/courses', $data);
    }

    // --- Store Course ---
    public function storeCourse()
    {
        $courseModel = new AdminCourseModel();
        $sessionId   = $this->request->getPost('session_id');

        if(empty($sessionId)) {
            return redirect()->back()->with('error', 'Please select an academic session');
        }

        // Semak session aktif
        $sessionModel = new SessionModel();
        $session = $sessionModel->find($sessionId);
        if(!$session || $session['status'] != 'active') {
            return redirect()->back()->with('error', 'Cannot add course to inactive session.');
        }

        $courseModel->insert([
            'course_code'    => $this->request->getPost('course_code'),
            'course_name'    => $this->request->getPost('course_name'),
            'semester'       => $this->request->getPost('semester'),
            'credit_hour'    => $this->request->getPost('credit_hour'),
            'coordinator_id' => $this->request->getPost('coordinator_id'),
            'session_id'     => $sessionId,
        ]);

        return redirect()->to('/admin/courses')->with('success', 'Course added successfully');
    }

    // --- Update Course ---
    public function updateCourse()
    {
        $courseModel = new AdminCourseModel();
        $id          = $this->request->getPost('id');
        $sessionId   = $this->request->getPost('session_id');

        if(empty($sessionId)) {
            return redirect()->back()->with('error', 'Please select an academic session');
        }

        // Semak session aktif
        $sessionModel = new SessionModel();
        $session = $sessionModel->find($sessionId);
        if(!$session || $session['status'] != 'active') {
            return redirect()->back()->with('error', 'Cannot assign course to inactive session.');
        }

        $courseModel->update($id, [
            'course_code'    => $this->request->getPost('course_code'),
            'course_name'    => $this->request->getPost('course_name'),
            'credit_hour'    => $this->request->getPost('credit_hour'),
            'semester'       => $this->request->getPost('semester'),
            'coordinator_id' => $this->request->getPost('coordinator_id'),
            'session_id'     => $sessionId,
        ]);

        return redirect()->to(base_url('admin/courses'))->with('success', 'Course updated successfully!');
    }

    // --- Delete Course ---
    public function deleteCourse()
    {
        $id = $this->request->getPost('id');
        $courseModel = new AdminCourseModel();
        $courseModel->delete($id);

        return redirect()->to('/admin/courses')->with('success', 'Course deleted successfully!');
    }

    // --- Manage Users ---
    public function manageUsers()
    {
        $userModel = new UserModel();
        $data['users'] = $userModel->findAll();
        return view('admin/user_update', $data);
    }
    
    public function updateUserRole()
    {
        $sessionRoles = explode(',', session('role'));
        if (!in_array('admin', $sessionRoles)) {
            return redirect()->to('/login')->with('error', 'Access denied');
        }

        $userId = $this->request->getPost('user_id');
        $roles  = $this->request->getPost('roles');

        $userModel = new UserModel();
        $user = $userModel->find($userId);

        if (!$user) {
            return redirect()->to('/admin/user-update')->with('error', 'User not found');
        }

        $roleString = $roles ? implode(',', $roles) : '';
        $userModel->update($userId, ['role' => $roleString]);

        if ($userId == session('id')) {
            session()->set('role', $roleString);
        }

        return redirect()->to('/admin/user-update')->with('success', 'User role updated successfully');
    }

    // --- Assign Lecturer ---
    public function saveLecturerCourse()
    {
        $lecturerCourseModel = new LecturerCourseModel();

        $lecturerCourseModel->insert([
            'lecturer_id' => $this->request->getPost('lecturer_id'),
            'course_id'   => $this->request->getPost('course_id'),
            'created_at'  => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/admin/assign-lecturer-course')->with('success', 'Lecturer assigned successfully');
    }

    // --- Upload Submission ---
    public function uploadFile()
    {
        $submissionModel = new SubmissionModel();
        $data['submissions'] = $submissionModel->findAll();

        return view('admin/upload', $data);
    }

    // --- Session Management ---
    public function sessionManagement()
    {
        $sessionModel = new SessionModel();
        $data['sessions'] = $sessionModel->findAll();
        return view('admin/session_management', $data);
    }

    public function saveSession()
    {
        $sessionModel = new SessionModel();
        $sessionName = $this->request->getPost('session_name');

        $exists = $sessionModel->where('session_name', $sessionName)->first();
        if ($exists) {
            return redirect()->back()->with('error', 'This session already exists!');
        }

        $sessionModel->save([
            'session_name' => $sessionName,
            'status' => 'inactive'
        ]);

        return redirect()->back()->with('success', 'Session added successfully!');
    }

    public function toggleStatus($id)
    {
        $sessionModel = new SessionModel();
        $session = $sessionModel->find($id);
        $newStatus = $session['status'] === 'active' ? 'inactive' : 'active';
        $sessionModel->update($id, ['status' => $newStatus]);

        return redirect()->back()->with('success', 'Status updated!');
    }

    public function deleteSession()
    {
        $id = $this->request->getPost('id');

        if ($id) {
            $sessionModel = new SessionModel();
            $sessionModel->delete($id);
            return redirect()->to(base_url('admin/session-management'))->with('success', 'Session deleted successfully!');
        }

        return redirect()->to(base_url('admin/session-management'))->with('error', 'Failed to delete session!');
    }
    public function searchCourses()
{
    $courseModel  = new AdminCourseModel();
    $userModel    = new UserModel();
    $sessionModel = new SessionModel();

    $query = $this->request->getGet('query');

    $data['coordinators'] = $userModel
        ->select('id, username, email')
        ->like('role', 'course_coordinator')
        ->findAll();

    $data['sessions'] = $sessionModel->where('status', 'active')->findAll();

    $data['courses'] = $courseModel->getActiveCourses($query);
    $data['query']   = $query;

    return view('admin/courses', $data);
}



}
