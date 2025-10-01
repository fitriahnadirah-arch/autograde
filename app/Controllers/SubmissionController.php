<?php

namespace App\Controllers;

use App\Models\SubmissionModel;
use App\Models\TestCaseModel;
use App\Models\RubricModel;
use App\Models\GradeModel;
use CodeIgniter\Controller;

class SubmissionController extends Controller
{
    public function upload()
    {
        helper(['form', 'url']);

        if ($this->request->getMethod() === 'post') {
            $file         = $this->request->getFile('submission_file');
            $studentId    = session()->get('user_id'); // ✅ logged-in student
            $courseId     = $this->request->getPost('course_id');
            $assessmentId = $this->request->getPost('assessment_id');

            if ($file && $file->isValid() && !$file->hasMoved()) {
                // Save into organized folder
                $submissionPath = WRITEPATH . "uploads/submissions/{$studentId}/{$assessmentId}/";
                if (!is_dir($submissionPath)) {
                    mkdir($submissionPath, 0777, true);
                }

                // Use original name (important for Java compilation)
                $newName = $file->getClientName();
                $file->move($submissionPath, $newName);

                // Save submission record
                $submissionModel = new SubmissionModel();
                $submissionModel->insert([
                    'student_id'    => $studentId,
                    'course_id'     => $courseId,
                    'assessment_id' => $assessmentId,
                    'group_members' => json_encode($this->request->getPost('group_members') ?? []),
                    'filename'      => $newName,
                    'uploaded_at'   => date('Y-m-d H:i:s'),
                ]);

                return redirect()->back()->with('success', 'Submission uploaded successfully!');
            }

            return redirect()->back()->with('error', 'Invalid file upload.');
        }

        return view('submissions/upload');
    }

    public function autograde()
    {
        $studentId    = $this->request->getPost('student_id');
        $assessmentId = $this->request->getPost('assessment_id');
        $code         = $this->request->getPost('code'); // pasted Java code

        // ⚡ If no pasted code, try to read from uploaded .java file
        if (empty($code)) {
            $submissionModel = new SubmissionModel();
            $latestSubmission = $submissionModel
                ->where('student_id', $studentId)
                ->where('assessment_id', $assessmentId)
                ->orderBy('uploaded_at', 'DESC')
                ->first();

            if ($latestSubmission && !empty($latestSubmission['filename'])) {
                $filePath = WRITEPATH . "uploads/submissions/{$studentId}/{$assessmentId}/" . $latestSubmission['filename'];
                if (file_exists($filePath)) {
                    $code = file_get_contents($filePath);
                }
            }
        }

        if (empty($code)) {
            return $this->response->setJSON([
                'error' => 'No code found. Please paste or upload a Java file.'
            ])->setStatusCode(400);
        }

        // Load testcases and rubric
        $testcaseModel = new TestCaseModel();
        $rubricModel   = new RubricModel();
        $testcases     = $testcaseModel->where('assessment_id', $assessmentId)->findAll();
        $rubric        = $rubricModel->where('assessment_id', $assessmentId)->first();

        if (!$rubric || empty($testcases)) {
            return $this->response->setJSON([
                'error' => 'Rubric atau testcase belum diset untuk assessment ini'
            ])->setStatusCode(400);
        }

        // Prepare payload for FastAPI
        $payload = [
            'student_id'    => $studentId,
            'assignment_id' => $assessmentId,
            'code'          => $code,
            'language'      => 'java',
            'testcases'     => array_map(fn($t) => [
                'input'    => $t['input'],
                'expected' => $t['expected_output']
            ], $testcases),
            'rubric' => [
                'correctness' => [
                    'weight' => $rubric['weight'] ?? 100
                ]
            ]
        ];

        // Send to Python autograder
        $client = \Config\Services::curlrequest();
        $res    = $client->post('http://127.0.0.1:8000/autograde', [
            'json' => $payload
        ]);
        $result = json_decode($res->getBody(), true);

        // Save submission (with code content)
        $submissionModel = new SubmissionModel();
        $submissionId = $submissionModel->insert([
            'student_id'    => $studentId,
            'assessment_id' => $assessmentId,
            'code'          => $code,
            'uploaded_at'   => date('Y-m-d H:i:s')
        ]);

        // Save grade
        $gradeModel = new GradeModel();
        $gradeModel->insert([
            'submission_id' => $submissionId,
            'score'         => $result['marks'],
            'feedback'      => json_encode($result['feedback']),
            'graded_at'     => date('Y-m-d H:i:s')
        ]);

        return $this->response->setJSON($result);
    }
}
