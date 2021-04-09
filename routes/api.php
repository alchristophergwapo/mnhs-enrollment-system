<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TeacherController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\Authentication;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\ScheduleController;
use App\Http\Requests\SubjectRequest;
use App\Models\User;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//-------------------Teacher  Controller----------------------------//
//Adding A New Teacher
Route::post('addNewTeacher', [TeacherController::class, 'addTeacher']);

//Getting All Teachers
Route::get('allTeacher', [TeacherController::class, 'allTeachers']);

//Deleting All Teachers
Route::get('delTeacher/{id}', [TeacherController::class, 'removeTeacher']);

//Updating A Teacher
Route::post('updateTeacher/{id}', [TeacherController::class, 'updateTeacher']);

//----------------------Admin Controller-----------------------------//
//Login For Admin
Route::post('/login', [Authentication::class, 'login']);

//Getting The Admin Profile
Route::get('/getAdminProfile', [Authentication::class, 'getAdminProfile']);

//Changing The Data In Admin Profile(ex. password)
Route::post('/change', [Authentication::class, 'changePassword']);

Route::get('/mark-all-read/{user}', [Authentication::class, 'markAllAsRead']);

Route::get('/mark-as-opened/{id}', [
    Authentication::class,
    'markNotifAsOpened',
]);

//--------------------------Section Controller---------------------//
//Api For Adding Junior High School For A Section
Route::post('addSection', [SectionController::class, 'addAnySection']);

//Api For Getting All The Sections In Every GradeLevel
Route::get('allGradeLevelSections', [
    SectionController::class,
    'allGradeLevelSections',
]);

//Deleting Any Kind Of Sections
Route::get('delAnySection/{id}', [SectionController::class, 'delAnySection']);

//Updating Any Kind Of Sections
Route::post('updateSection/{id}', [SectionController::class, 'updateSection']);

Route::get('/allSections', [SectionController::class, 'allSections']);

//--------------------------------------------------This Is For Enrollment Process API----------------------------------------//
Route::post('/addStudent', [EnrollmentController::class, 'addStudent']);
//This is for updating the student details
Route::post('/updateStudent/{id}', [EnrollmentController::class, 'updateStudent']);

Route::get('/pendingEnrollment', [
    EnrollmentController::class,
    'allPendingStudents',
]);
Route::get('/approvedEnrollment', [
    EnrollmentController::class,
    'allEnrolledStudents',
]);
Route::get('/pendingEnrollments', [
    EnrollmentController::class,
    'allPendingStudents',
]);
Route::get('/approvedEnrollments', [
    EnrollmentController::class,
    'allEnrolledStudents',
]);
Route::get('/declinedEnrollments', [
    EnrollmentController::class,
    'allDeclinedStudents',
]);
Route::post('/addEnrollment', [EnrollmentController::class, 'addEnrollment']);
Route::post('/approveEnrollment/{id}', [
    EnrollmentController::class,
    'approveEnrollment',
]);
Route::post('/declineEnrollment/{id}', [
    EnrollmentController::class,
    'declineEnrollment',
]);
Route::get('/studentSectionDetails/{section}', [
    EnrollmentController::class,
    'studentSectionDetails',
]);

Route::get('/gradelevelSubject/{grade_level}', [
    SubjectController::class,
    'allSubjectsByGrLevel',
]);

Route::post('/addSubject', [SubjectController::class, 'addSubjectInGrLevel']);
Route::post('/updateSubject', [SubjectController::class, 'updateSubject']);
Route::get('/deleteSubject/{id}', [SubjectController::class, 'deleteSubject']);

Route::get('/classSchedules/{section_id}', [
    ScheduleController::class,
    'getSectionSchedule',
]);
Route::post('/editSchedules', [ScheduleController::class, 'editSchedules']);
Route::get('/getTeacherSchedule/{teacher_id}', [
    ScheduleController::class,
    'getTeacherSchedule',
]);
Route::post('/addSchedules', [ScheduleController::class, 'addSchedules']);
