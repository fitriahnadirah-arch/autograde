<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// =======================
// Authentication Routes
// =======================
$routes->get('/login', 'AuthController::login');
$routes->post('/login', 'AuthController::process_login');

$routes->get('/register', 'AuthController::register');
$routes->post('/register', 'AuthController::process_register');

$routes->get('/reset-password', 'AuthController::resetPassword');
$routes->post('/reset-password', 'AuthController::process_resetPassword');

$routes->get('/logout', 'AuthController::logout');

$routes->get('/dashboard', 'DashboardController::index');  // dashboard utama
$routes->get('/dashboard/search', 'DashboardController::search'); // search


// =======================
// Admin Routes
// =======================
$routes->get('/admin/dashboard', 'AdminController::dashboard');
$routes->get('/admin/courses', 'AdminController::courses');
$routes->get('admin/courses/search', 'AdminController::searchCourses');
$routes->post('admin/storeCourse', 'AdminController::storeCourse');
$routes->post('admin/updateCourse', 'AdminController::updateCourse'); 
$routes->post('admin/deleteCourse', 'AdminController::deleteCourse');


// User management
$routes->get('/admin/user-update', 'AdminController::manageUsers');  
$routes->post('/admin/update-user-role', 'AdminController::updateUserRole'); 
$routes->get('admin/account-register', 'AuthController::accountRegister'); 
$routes->post('admin/process-account-register', 'AuthController::process_accountRegister');


$routes->get('admin/session-management', 'AdminController::sessionManagement');

// add session
$routes->post('admin/save-session', 'AdminController::saveSession');

// toggle status
$routes->post('admin/toggle-status/(:num)', 'AdminController::toggleStatus/$1');

// delete session
$routes->post('admin/delete-session', 'AdminController::deleteSession');


// =======================
// Course Coordinator Routes
// =======================
$routes->get('/coordinator/dashboard', 'CoordinatorController::dashboard');

// Courses
$routes->get('/coordinator/courses', 'CoordinatorController::addCourses');
$routes->post('coordinator/assignLecturer/(:num)', 'CoordinatorController::assignLecturer/$1');
$routes->get('coordinator/detailCourse/(:num)', 'CoordinatorController::detailCourse/$1');
$routes->post('coordinator/addAssessment/(:num)', 'CoordinatorController::addAssessment/$1');
$routes->post('coordinator/updateAssessment/(:num)', 'CoordinatorController::updateAssessment/$1');
$routes->post('coordinator/deleteAssessment/(:num)', 'CoordinatorController::deleteAssessment/$1');
$routes->post('coordinator/saveClass/(:num)', 'CoordinatorController::saveClass/$1');

// Rubric routes
$routes->get('coordinator/rubric', 'CoordinatorController::rubric'); // list all
$routes->get('coordinator/rubric/manage/(:num)', 'CoordinatorController::manageRubric/$1'); // manage specific assessment rubric
$routes->post('coordinator/rubric/upload-excel/(:num)', 'CoordinatorController::uploadRubricExcel/$1'); // upload Excel rubric

$routes->get('coordinator/addRubric/(:num)', 'CoordinatorController::addRubric/$1');   // paparkan form manual
$routes->post('coordinator/rubric/deleteAll/(:num)', 'CoordinatorController::deleteAll/$1');

$routes->post('coordinator/addRubric/(:num)', 'CoordinatorController::saveRubric/$1'); // simpan data manual

$routes->group('coordinator', function($routes) {
    // Upload question file
    $routes->post('assessment/upload-question/(:num)', 'CoordinatorController::uploadQuestion/$1');
});
//test case
$routes->get('coordinator/testcases', 'CoordinatorController::testcases');
$routes->post('coordinator/saveTestcase', 'CoordinatorController::saveTestcase');
$routes->get('coordinator/deleteTestcase/(:num)', 'CoordinatorController::deleteTestcase/$1');


// =======================
// Lecturer Routes
// =======================
$routes->get('/lecturer/dashboard', 'LecturerController::dashboard');
$routes->get('/lecturer/courses', 'LecturerController::courses');
$routes->post('/lecturer/addCourse', 'LecturerController::addCourse');

// Upload File (now takes courseId)
$routes->get('lecturer/uploadFile/(:num)', 'LecturerController::uploadFile/$1');
$routes->post('lecturer/upload-students/(:num)', 'LecturerController::uploadStudents/$1');
$routes->get('lecturer/getStudentsByCourse/(:num)', 'LecturerController::getStudentsByCourse/$1');
$routes->post('lecturer/saveFile', 'LecturerController::saveFile');

$routes->get('/lecturer/code-evaluation', 'LecturerController::codeEvaluation');
$routes->get('/lecturer/reports', 'LecturerController::reports');

$routes->group('lecturer', ['filter' => 'auth'], function($routes) {
    $routes->get('upload', 'Lecturer\UploadController::index');
    $routes->post('upload/submit', 'Lecturer\UploadController::submit');
});

$routes->get('/lecturer/evaluate/(:num)', 'LecturerController::evaluateSubmission/$1');
$routes->get('lecturer/evaluateSubmission/(:num)', 'LecturerController::evaluateSubmission/$1');
$routes->post('lecturer/runEvaluation/(:num)', 'LecturerController::runEvaluation/$1');
$routes->get('lecturer/exportPDF', 'LecturerController::exportPDF');
$routes->get('lecturer/exportExcel', 'LecturerController::exportExcel');
