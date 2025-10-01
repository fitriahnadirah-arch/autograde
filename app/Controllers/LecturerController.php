<?php

namespace App\Controllers;

use App\Models\SubmissionModel;
use App\Models\StudentModel;
use App\Models\SessionModel;
use App\Models\AssessmentModel;
use App\Models\TestCaseModel;
use App\Models\RubricModel;
use App\Models\GradeModel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;

class LecturerController extends BaseController
{
    protected $sessionModel;
    protected $studentModel;
    protected $db;

    public function __construct()
    {
        $this->sessionModel = new SessionModel();
        $this->studentModel = new StudentModel();
        $this->db           = \Config\Database::connect();
    }

    // Dashboard
    public function dashboard()
    {
        $lecturerId = session('id');

        $builder = $this->db->table('course_lecturer_class clc')
            ->select('clc.id AS course_assignment_id, ac.*, c.class_name')
            ->join('admin_courses ac', 'ac.id = clc.course_id')
            ->join('classes c', 'c.id = clc.class_id')
            ->where('clc.lecturer_id', $lecturerId);

        $courses = $builder->get()->getResultArray();

        return view('lecturer/dashboard', [
            'courses' => $courses
        ]);
    }

    // My Courses
    public function courses()
    {
        $lecturerId = session('id');
        $activeSession = $this->sessionModel->getActiveSession();

        $builder = $this->db->table('course_lecturer_class clc')
            ->select('clc.id AS course_assignment_id, admin_courses.*, classes.id AS class_id, classes.class_name')
            ->join('admin_courses', 'admin_courses.id = clc.course_id')
            ->join('classes', 'classes.id = clc.class_id')
            ->where('clc.lecturer_id', $lecturerId);

        $results = $builder->get()->getResultArray();

        $myCourses = [];
        foreach ($results as $row) {
            $courseId = $row['id'];
            if (!isset($myCourses[$courseId])) {
                $myCourses[$courseId] = [
                    'course_assignment_id' => $row['course_assignment_id'],
                    'id' => $row['id'],
                    'course_name' => $row['course_name'],
                    'course_code' => $row['course_code'],
                    'semester' => $row['semester'],
                    'credit_hour' => $row['credit_hour'],
                    'classes' => []
                ];
            }
            $myCourses[$courseId]['classes'][] = [
                'id' => $row['class_id'],
                'class_name' => $row['class_name']
            ];
        }

        return view('lecturer/courses', [
            'myCourses' => array_values($myCourses),
            'activeSession' => $activeSession
        ]);
    }

    // Upload Students Excel
    public function uploadStudents($courseAssignmentId)
    {
        $classId = $this->request->getPost('class_id');
        $file = $this->request->getFile('student_excel');

        if (!$file || !$file->isValid()) {
            return redirect()->back()->with('error', 'Invalid file uploaded.');
        }

        try {
            // Load spreadsheet
            $spreadsheet = IOFactory::load($file->getTempName());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            // Skip header row (assume first row is header: student_id, student_name)
            for ($i = 1; $i < count($rows); $i++) {
                $row = $rows[$i];

                $studentId   = trim($row[0]); // first col
                $studentName = trim($row[1]); // second col

                if (empty($studentId) || empty($studentName)) {
                    continue; // skip empty rows
                }

                $studentData = [
                    'student_id'   => $studentId,
                    'student_name' => $studentName,
                    'course_id'    => $courseAssignmentId,
                    'class_id'     => $classId,
                ];

                // Check if student already exists
                $existing = $this->studentModel
                    ->where('student_id', $studentId)
                    ->where('course_id', $courseAssignmentId)
                    ->where('class_id', $classId)
                    ->first();

                if ($existing) {
                    $this->studentModel->update($existing['id'], $studentData);
                } else {
                    $this->studentModel->insert($studentData);
                }
            }

            return redirect()->back()->with('success', 'Students uploaded successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Upload failed: ' . $e->getMessage());
        }
    }
    // Upload assignment file view
    public function uploadFile($courseAssignmentId)
    {
        $lecturerId = session('id');

        $course = $this->db->table('course_lecturer_class clc')
            ->select('clc.id AS course_assignment_id, ac.*, c.class_name')
            ->join('admin_courses ac', 'ac.id = clc.course_id')
            ->join('classes c', 'c.id = clc.class_id')
            ->where('clc.lecturer_id', $lecturerId)
            ->where('clc.id', $courseAssignmentId)
            ->get()
            ->getRowArray();

        if (!$course) {
            return redirect()->back()->with('error', 'Course not found.');
        }

        $assessments = (new AssessmentModel())
            ->where('course_id', $course['course_assignment_id'])
            ->findAll();

        return view('lecturer/upload_file', [
            'course' => $course,
            'assessments' => $assessments
        ]);
    }

    // AJAX: Get students by course
    public function getStudentsByCourse($courseAssignmentId)
    {
        $studentModel = new StudentModel();
        $students = $studentModel->where('course_id', $courseAssignmentId)->findAll();
        return $this->response->setJSON($students);
    }

    // Save uploaded assignment file
    public function saveFile()
    {
        $file = $this->request->getFile('filename');

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(WRITEPATH . 'uploads', $newName);

            $submissionModel = new SubmissionModel();
            $submissionModel->insert([
                'lecturer_id' => session()->get('id'),
                'course_id' => $this->request->getPost('course_id'),
                'assessment_id' => $this->request->getPost('assessment_id'),
                'group_members' => json_encode($this->request->getPost('student_id')),
                'filename' => $newName,
                'uploaded_at' => date('Y-m-d H:i:s'),
            ]);

            return redirect()->back()->with('success', 'File uploaded successfully!');
        }

        return redirect()->back()->with('error', 'Invalid file upload.');
    }

    // View submissions
    public function codeEvaluation()
    {
        $builder = $this->db->table('submissions s')
            ->select('s.id, s.filename, s.uploaded_at, s.group_members, 
                      ac.course_code, ac.course_name, 
                      a.title as assessment_title')
            ->join('course_lecturer_class clc', 'clc.id = s.course_id')
            ->join('admin_courses ac', 'ac.id = clc.course_id')
            ->join('assessments a', 'a.id = s.assessment_id')
            ->orderBy('s.uploaded_at', 'DESC');

        $submissions = $builder->get()->getResultArray();

        foreach ($submissions as &$submission) {
            $submission['group_members'] = $this->mapStudentInfo($submission['group_members']);
        }

        return view('lecturer/code_evaluation', ['submissions' => $submissions]);
    }

    // Evaluate a single submission
    public function evaluateSubmission($submissionId)
    {
        $submissionModel = new SubmissionModel();
        $testcaseModel   = new TestCaseModel();
        $rubricModel     = new RubricModel();
        $gradeModel      = new GradeModel();

        $submission = $submissionModel->find($submissionId);
        if (!$submission) {
            return redirect()->back()->with('error', 'Submission not found.');
        }

        $assessmentId = $submission['assessment_id'];

        $testcases = $testcaseModel->where('assessment_id', $assessmentId)->findAll();
        $rubric    = $rubricModel->where('assessment_id', $assessmentId)->first();

        if (!$rubric || empty($testcases)) {
            return redirect()->back()->with('error', 'Rubric or testcases not defined.');
        }

        $filePath = WRITEPATH . 'uploads/' . $submission['filename'];
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File not found.');
        }
        $code = file_get_contents($filePath);

        $executor = new \App\Libraries\CodeExecutor();
        $result   = $executor->runJava($code, array_map(fn($t) => [
            'input'    => $t['input'],
            'expected' => $t['expected_output']
        ], $testcases));

        $gradeModel->insert([
            'submission_id' => $submissionId,
            'score'         => $result['marks'],
            'feedback'      => json_encode($result['feedback']),
            'graded_at'     => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('lecturer/reports')->with('success', 'Evaluation completed successfully.');
    }

    // Export to Excel
    public function exportExcel()
    {
        $submissions = $this->db->table('submissions s')
            ->select('s.id, s.filename, s.uploaded_at, s.group_members, 
                      ac.course_code, ac.course_name, 
                      a.title as assessment_title')
            ->join('course_lecturer_class clc', 'clc.id = s.course_id')
            ->join('admin_courses ac', 'ac.id = clc.course_id')
            ->join('assessments a', 'a.id = s.assessment_id')
            ->orderBy('s.uploaded_at', 'DESC')
            ->get()->getResultArray();

        foreach ($submissions as &$submission) {
            $submission['group_members'] = $this->mapStudentInfo($submission['group_members']);
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Course');
        $sheet->setCellValue('B1', 'Assessment');
        $sheet->setCellValue('C1', 'Students');
        $sheet->setCellValue('D1', 'File');
        $sheet->setCellValue('E1', 'Uploaded At');

        $row = 2;
        foreach ($submissions as $sub) {
            $sheet->setCellValue("A{$row}", $sub['course_code'] . ' - ' . $sub['course_name']);
            $sheet->setCellValue("B{$row}", $sub['assessment_title']);
            $sheet->setCellValue("C{$row}", $sub['group_members']);
            $sheet->setCellValue("D{$row}", $sub['filename']);
            $sheet->setCellValue("E{$row}", $sub['uploaded_at']);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'submissions.xlsx';

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }

    // Export to PDF
    public function exportPDF()
    {
        $grades = $this->db->table('grades g')
            ->select('g.id, g.score, g.feedback, g.graded_at,
                    s.group_members, s.filename,
                    ac.course_code, ac.course_name,
                    a.id as assessment_id, a.title as assessment_title')
            ->join('submissions s', 's.id = g.submission_id')
            ->join('course_lecturer_class clc', 'clc.id = s.course_id')
            ->join('admin_courses ac', 'ac.id = clc.course_id')
            ->join('assessments a', 'a.id = s.assessment_id')
            ->orderBy('g.graded_at', 'DESC')
            ->get()->getResultArray();

        foreach ($grades as &$grade) {
            $grade['group_members'] = $this->mapStudentInfo($grade['group_members']);

            // 🔹 fetch rubric for this assessment
            $rubric = $this->db->table('rubrics')
                ->where('assessment_id', $grade['assessment_id'])
                ->get()->getResultArray();

            $grade['rubric'] = $rubric;
        }

        $html = view('lecturer/reports_pdf', ['grades' => $grades]);

        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream("evaluation_reports.pdf", ["Attachment" => false]);
    }

    // Reports
    public function reports()
    {
        $grades = $this->db->table('grades g')
            ->select('g.id, g.score, g.feedback, g.graded_at,
                    s.group_members, s.filename,
                    ac.course_code, ac.course_name,
                    a.title as assessment_title')
            ->join('submissions s', 's.id = g.submission_id')
            ->join('course_lecturer_class clc', 'clc.id = s.course_id')
            ->join('admin_courses ac', 'ac.id = clc.course_id')
            ->join('assessments a', 'a.id = s.assessment_id')
            ->orderBy('g.graded_at', 'DESC')
            ->get()->getResultArray();

        foreach ($grades as &$grade) {
            $grade['group_members'] = $this->mapStudentInfo($grade['group_members']);
        }

        return view('lecturer/reports', ['grades' => $grades]);
    }

    //Convert JSON group_members into readable "ID - Name" format
    private function mapStudentInfo($groupMembersJson)
    {
        $members = json_decode($groupMembersJson, true);
        if (!is_array($members)) {
            return $groupMembersJson; // fallback if not valid JSON
        }

        $studentModel = new \App\Models\StudentModel();

        $result = [];
        foreach ($members as $studentId) {
            $student = $studentModel->find($studentId);
            if ($student) {
                $result[] = $student['student_id'] . ' - ' . $student['student_name'];
            } else {
                $result[] = $studentId; // fallback if student not found
            }
        }

        return implode(", ", $result);
    }
}
