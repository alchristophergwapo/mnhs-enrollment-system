<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TeacherController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\Authentication;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ForgotPasswordAPIController;
use App\Http\Controllers\ResetPasswordAPIController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\StudentController;

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
// Routes for teacher related
Route::post('addNewTeacher', [TeacherController::class, 'addTeacher']);
Route::get('allTeacher', [TeacherController::class, 'allTeachers']);
Route::get('delTeacher/{id}', [TeacherController::class, 'removeTeacher']);
Route::post('updateTeacher/{id}', [TeacherController::class, 'updateTeacher']);
Route::get('/allNoneAdvisoryTeacher', [TeacherController::class, 'allTeachersWithNoAdvisory']);

//----------------------Routes for admin related-----------------------------//
Route::get('/auth/init', [Authentication::class, 'init']);
Route::post('/login', [Authentication::class, 'login']);
Route::post('/reset-password', [Authentication::class, 'passwordReset']);
Route::get('/getAdminProfile', [Authentication::class, 'getAdminProfile']);
Route::post('/change', [Authentication::class, 'changePassword']);
Route::get('/mark-all-read/{user}', [Authentication::class, 'markAllAsRead']);
Route::get('/mark-as-opened/{id}', [
    Authentication::class,
    'markNotifAsOpened',
]);

// Routes for Notifications related
Route::get('/unreadNotif/{user}', [NotificationController::class, 'allUnreadNotif']);
Route::get('/allNotifications/{user}', [NotificationController::class, 'allNotif']);

//Sending the username and password to the users or students_id
// Route::get('send-sms/{id}', [NexmoSMSController::class, 'SMS']);

//--------------------------Routes for sections related---------------------//
Route::post('addSection', [SectionController::class, 'addAnySection']);
Route::get('allGradeLevelSections', [
    SectionController::class,
    'allGradeLevelSections',
]);
Route::get('delAnySection/{id}', [SectionController::class, 'delAnySection']);
Route::post('updateSection/{id}', [SectionController::class, 'updateSection']);
Route::get('/allSections/{gradelevel}', [SectionController::class, 'allSections']);
Route::get('/noAdviserSections', [SectionController::class, 'allSectionsWithNoAdviser']);

//--------------------------------------------------This Is For Enrollment Process API----------------------------------------//
Route::post('/addStudent', [EnrollmentController::class, 'addStudent']);
Route::post('/updateStudent/{id}', [EnrollmentController::class, 'updateStudent']);
Route::post('/updatedeclineEnrollment/{id}', [EnrollmentController::class, 'updatedeclineEnrollment']);
Route::get('/pendingEnrollments/{adminlevel?}', [
    EnrollmentController::class,
    'allPendingStudents',
]);
Route::get('/approvedEnrollments/{gradeLevel}', [
    EnrollmentController::class,
    'allEnrolledStudents',
]);
Route::get('/declinedEnrollments/{adminLevel?}', [
    EnrollmentController::class,
    'allDeclinedStudents',
]);
Route::get('/declinedEnrollments', [
    EnrollmentController::class,
    'declinedStudents',
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
Route::post('/editEnrollmentRemarks/{id}', [EnrollmentController::class, 'editEnrollmentRemarks']);
Route::post('/enroll', [EnrollmentController::class, 'enroll']);

// Routes for subjects related
Route::get('/gradelevelSubject/{grade_level}', [
    SubjectController::class,
    'allSubjectsByGrLevel',
]);
Route::post('/addSubject', [SubjectController::class, 'addSubjectInGrLevel']);
Route::post('/updateSubject', [SubjectController::class, 'updateSubject']);
Route::get('/deleteSubject/{id}', [SubjectController::class, 'deleteSubject']);

// Routes for schedules related
Route::post('/editSchedules', [ScheduleController::class, 'editSchedules']);
Route::get('/getTeacherSchedule/{teacher_id}', [ScheduleController::class, 'getTeacherSchedule']);
Route::post('/addSchedules', [ScheduleController::class, 'addSchedules']);
Route::post('/deleteScheds', [ScheduleController::class, 'deleteSchedule']);
Route::get('/classSchedules/{section_id}', [
    ScheduleController::class,
    'getSectionSchedule',
]);
// 

// Routes for teacher admin related
Route::get('/allTeacherAdmin', [AdminController::class, 'allTeacherAdmin']);
Route::post('/addNewAdmin', [AdminController::class, 'addNewAdmin']);
Route::post('/updateTeacherAdmin/{id}', [AdminController::class, 'updateTeacherAdmin']);
Route::get('/resetPassword/{id}', [AdminController::class, 'resetPassword']);

// Routes for student related
Route::prefix('student')->group(function () {
    Route::get('/details/{lrn}', [StudentController::class, 'getMyDetails']);
    Route::get('/classmates/{section}', [
        StudentController::class,
        'getMyClassmates',
    ]);
    Route::get('/classSchedules/{section_id}', [
        StudentController::class,
        'getMySchedule',
    ]);
});

// Routes for forgot password related
Route::post('password/reset', [ResetPasswordAPIController::class, 'reset']);
Route::post('password/email', [ForgotPasswordAPIController::class, 'sendResetLinkEmail']);

Broadcast::routes();
