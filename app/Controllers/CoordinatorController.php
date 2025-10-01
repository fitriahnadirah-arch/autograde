<?php
namespace App\Controllers;

use App\Models\CourseModel;
use App\Models\AssessmentModel;
use App\Models\RubricModel;
use App\Models\LecturerModel;
use App\Models\SessionModel;
use App\Models\TestCaseModel;
use App\Controllers\BaseController;
use PhpOffice\PhpSpreadsheet\IOFactory;

class CoordinatorController extends BaseController
{
    // ==========================
    // Dashboard
    // ==========================
    public function dashboard()
    {
        $courseModel = new CourseModel();
        $data['course_name'] = $courseModel->select('course_name')->findAll();
        return view('coordinator/dashboard', $data);
    }

    // ==========================
    // Courses & Assignment
    // ==========================
    public function addCourses()
    {
        $db           = \Config\Database::connect();
        $sessionModel = new SessionModel();
        $activeSession = $sessionModel->getActiveSession();

        $admin_courses = [];
        if ($activeSession) {
            $admin_courses = $db->table('admin_courses')
                ->where('session_id', $activeSession['id'])
                ->get()
                ->getResultArray();
        }

        $lecturers = $db->table('users')
            ->where('role', 'lecturer')
            ->get()
            ->getResultArray();

        // ambil data mapping lecturer + class
        $course_lecturer_class = $db->table('course_lecturer_class clc')
            ->select('clc.course_id, users.username as lecturer_name, classes.class_name')
            ->join('users', 'users.id = clc.lecturer_id', 'left')
            ->join('classes', 'classes.id = clc.class_id', 'left')
            ->get()
            ->getResultArray();

        $data = [
            'admin_courses'        => $admin_courses,
            'lecturers'            => $lecturers,
            'activeSession'        => $activeSession,
            'course_lecturer_class'=> $course_lecturer_class,
        ];

        return view('coordinator/courses', $data);
    }

    public function assignLecturer($courseId)
    {
        $db = \Config\Database::connect();
        $assignments = $this->request->getPost('assignments');

        if (!$assignments || !is_array($assignments)) {
            return redirect()->back()->with('error', 'Please assign at least one lecturer & class.');
        }

        // Get active session
        $sessionModel  = new \App\Models\SessionModel();
        $activeSession = $sessionModel->getActiveSession();
        if (!$activeSession) {
            return redirect()->back()->with('error', 'No active academic session found.');
        }

        foreach ($assignments as $assignment) {
            $lecturerId = $assignment['lecturer_id'] ?? null;
            $className  = $assignment['class_name'] ?? null;

            if (!$lecturerId || !$className) {
                continue;
            }

            // Ensure class exists
            $class = $db->table('classes')->where('class_name', $className)->get()->getRowArray();
            if (!$class) {
                $db->table('classes')->insert([
                    'class_name' => $className,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
                $classId = $db->insertID();
            } else {
                $classId = $class['id'];
            }

            // Insert into courses (if not exists yet)
            $course = $db->table('courses')
                ->where('course_id', $courseId)
                ->where('session_id', $activeSession['id'])
                ->get()
                ->getRowArray();

            if (!$course) {
                $db->table('courses')->insert([
                    'course_id'  => $courseId,          // FK → admin_courses.id
                    'session_id' => $activeSession['id'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
                $courseMappingId = $db->insertID();
            } else {
                $courseMappingId = $course['id'];
            }

            // Insert into course_lecturer_class (avoid duplicates)
            $exists = $db->table('course_lecturer_class')
                ->where('course_id', $courseId)
                ->where('lecturer_id', $lecturerId)
                ->where('class_id', $classId)
                ->get()
                ->getRowArray();

            if (!$exists) {
                $db->table('course_lecturer_class')->insert([
                    'course_id'   => $courseId,
                    'lecturer_id' => $lecturerId,
                    'class_id'    => $classId,
                    'created_at'  => date('Y-m-d H:i:s'),
                    'updated_at'  => date('Y-m-d H:i:s'),
                ]);
            }
        }

        return redirect()->back()->with('success', 'Lecturer(s) & class(es) assigned successfully.');
    }

    public function detailCourse($courseId)
    {
        $db = \Config\Database::connect();

        $course = $db->table('admin_courses')
            ->where('id', $courseId)
            ->get()
            ->getRowArray();

        if (!$course) {
            return redirect()->back()->with('error', 'Course not found.');
        }

        $assessments = $db->table('assessments')
            ->where('course_id', $courseId)
            ->get()
            ->getResultArray();

        $data = [
            'course'      => $course,
            'assessments' => $assessments
        ];

        return view('coordinator/detailCourse', $data);
    }

    // ==========================
    // Assessment CRUD
    // ==========================
    public function addAssessment($courseId)
    {
        $assessmentModel = new AssessmentModel();
        $title  = $this->request->getPost('title');
        $weight = $this->request->getPost('weight');

        if ($title && $weight) {
            $assessmentModel->insert([
                'course_id' => $courseId,
                'title'     => $title,
                'weight'    => $weight
            ]);
        }

        return redirect()->to(base_url('coordinator/detailCourse/' . $courseId));
    }

    public function updateAssessment($id)
    {
        $assessmentModel = new AssessmentModel();
        $title  = $this->request->getPost('title');
        $weight = $this->request->getPost('weight');

        if ($title && $weight) {
            $assessmentModel->update($id, [
                'title'      => $title,
                'weight'     => $weight,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }

        $assessment = $assessmentModel->find($id);
        return redirect()->to(base_url('coordinator/detailCourse/' . $assessment['course_id']));
    }

    public function deleteAssessment($id)
    {
        $assessmentModel = new AssessmentModel();
        $assessment      = $assessmentModel->find($id);

        $courseId = $assessment ? $assessment['course_id'] : 0;
        if ($assessment) {
            $assessmentModel->delete($id);
        }

        return redirect()->to(base_url('coordinator/detailCourse/' . $courseId));
    }

    public function uploadQuestion($assessmentId)
    {
        $file = $this->request->getFile('question_file');

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/questions', $newName);

            $assessmentModel = new AssessmentModel();
            $updated = $assessmentModel->update($assessmentId, [
                'question_file' => $newName,
                'updated_at'    => date('Y-m-d H:i:s'),
            ]);

            if ($updated) {
                return redirect()->back()->with('success', 'Question uploaded successfully!');
            } else {
                return redirect()->back()->with('error', 'Failed to update assessment in DB.');
            }
        }

        return redirect()->back()->with('error', 'Invalid file upload.');
    }

    // ==========================
    // Rubric Management
    // ==========================
    public function rubric()
    {
        $db              = \Config\Database::connect();
        $sessionModel    = new SessionModel();
        $assessmentModel = new AssessmentModel();

        $activeSession = $sessionModel->getActiveSession();
        if (!$activeSession) {
            return redirect()->back()->with('error', 'No active session found.');
        }

        $courses = $db->table('courses')
            ->select('courses.id as mapping_id, courses.course_id, admin_courses.course_name')
            ->join('admin_courses', 'admin_courses.id = courses.course_id', 'left')
            ->where('courses.session_id', $activeSession['id'])
            ->get()
            ->getResultArray();

        foreach ($courses as &$course) {
            $course['assessments'] = $assessmentModel
                ->where('course_id', $course['mapping_id'])
                ->findAll();
        }

        return view('coordinator/rubric', [
            'courses'       => $courses,
            'activeSession' => $activeSession
        ]);
    }

    public function uploadRubricExcel($assessmentId)
    {
        $file = $this->request->getFile('rubric_file');

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $spreadsheet = IOFactory::load($file->getTempName());
            $rows        = $spreadsheet->getActiveSheet()->toArray();

            $rubricModel = new RubricModel();

            foreach (array_slice($rows, 1) as $row) {
                if (empty($row[0])) continue;

                $rubricModel->insert([
                    'assessment_id' => $assessmentId,
                    'criteria'      => $row[0] ?? '',
                    'weight'        => $row[1] ?? 0,
                    'scale_5'       => $row[2] ?? '',
                    'scale_4'       => $row[3] ?? '',
                    'scale_3'       => $row[4] ?? '',
                    'scale_2'       => $row[5] ?? '',
                    'scale_1'       => $row[6] ?? '',
                    'created_at'    => date('Y-m-d H:i:s'),
                    'updated_at'    => date('Y-m-d H:i:s')
                ]);
            }

            return redirect()->to(base_url('coordinator/rubric/manage/'.$assessmentId))
                            ->with('success', 'Rubric uploaded successfully!');
        }

        return redirect()->back()->with('error', 'Invalid file upload.');
    }

    public function deleteAll($assessmentId)
    {
        $rubricModel = new RubricModel();
        $rubricModel->where('assessment_id', $assessmentId)->delete();
        return redirect()->back()->with('success', 'All rubrics deleted successfully!');
    }

    public function manageRubric($assessmentId)
    {
        $rubricModel     = new RubricModel();
        $assessmentModel = new AssessmentModel();

        $assessment = $assessmentModel->find($assessmentId);
        if (!$assessment) {
            return redirect()->back()->with('error', 'Assessment not found.');
        }

        $rubrics = $rubricModel->where('assessment_id', $assessmentId)->findAll();

        return view('coordinator/manage_rubric', [
            'assessmentId' => $assessmentId,
            'assessment'   => $assessment,
            'rubrics'      => $rubrics
        ]);
    }

    public function addRubric($assessmentId)
    {
        $rubricModel     = new RubricModel();
        $assessmentModel = new AssessmentModel();

        $assessment = $assessmentModel->find($assessmentId);
        $rubrics    = $rubricModel->where('assessment_id', $assessmentId)->findAll();

        return view('coordinator/manage_rubric', [
            'assessmentId' => $assessmentId,
            'assessment'   => $assessment,
            'rubrics'      => $rubrics
        ]);
    }

    public function saveRubric($assessmentId)
    {
        $rubricModel = new RubricModel();

        $data = [
            'assessment_id' => $assessmentId,
            'criteria'      => $this->request->getPost('criteria'),
            'weight'        => $this->request->getPost('weight'),
            'scale_5'       => $this->request->getPost('scale_5'),
            'scale_4'       => $this->request->getPost('scale_4'),
            'scale_3'       => $this->request->getPost('scale_3'),
            'scale_2'       => $this->request->getPost('scale_2'),
            'scale_1'       => $this->request->getPost('scale_1'),
        ];

        $rubricModel->insert($data);

        return redirect()->to(base_url('coordinator/addRubric/'.$assessmentId))
                        ->with('success', 'Rubric added successfully!');
    }

    // ==========================
    // Test Case Management
    // ==========================
    public function testcases()
    {
        $testCaseModel   = new TestCaseModel();
        $assessmentModel = new AssessmentModel();

        $data = [
            'testcases' => $testCaseModel
                ->select('test_cases.*, assessments.title as assessment_title')
                ->join('assessments', 'assessments.id = test_cases.assessment_id', 'left')
                ->findAll(),
            'assessments' => $assessmentModel->findAll()
        ];

        return view('coordinator/testcases', $data);
    }

    public function saveTestcase()
    {
        $testCaseModel = new TestCaseModel();

        $testCaseModel->insert([
            'assessment_id'   => $this->request->getPost('assessment_id'),
            'input'           => $this->request->getPost('input'),
            'expected_output' => $this->request->getPost('expected_output'),
        ]);

        return redirect()->to(base_url('coordinator/testcases'))->with('success', 'Test case added successfully!');
    }

    public function deleteTestcase($id)
    {
        $testCaseModel = new TestCaseModel();
        $testCaseModel->delete($id);

        return redirect()->to(base_url('coordinator/testcases'))->with('success', 'Test case deleted successfully!');
    }
}
