<?php

namespace App\Controllers;

use App\Models\AdminCourseModel;

class DashboardController extends BaseController
{
    public function index()
    {
        $courseModel = new AdminCourseModel();
        $data['courses'] = $courseModel->findAll();

        return view('admin/dashboard', $data);
    }

    public function search()
    {
        $query = $this->request->getGet('query');
        $courseModel = new AdminCourseModel();

        if (!empty($query)) {
            $data['courses'] = $courseModel
                ->like('course_name', $query) // search ikut nama course sahaja
                ->findAll();
        } else {
            $data['courses'] = $courseModel->findAll();
        }

        return view('admin/dashboard', $data);
    }
}
