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
use App\Http\Controllers\ScheduleController;
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
// Route::get('allTeacher/{grade_level}', [TeacherController::class, 'AllTeachersByGradeLvl']);

//Deleting All Teachers
Route::get('delTeacher/{id}', [TeacherController::class, 'removeTeacher']);

//Updating A Teacher
Route::post('updateTeacher/{id}', [TeacherController::class, 'updateTeacher']);
Route::get('/allNoneAdvisoryTeacher', [TeacherController::class, 'allTeachersWithNoAdvisory']);

//----------------------Admin Controller-----------------------------//
//Login For Admin
Route::get('/auth/init', [Authentication::class, 'init']);
Route::post('/login', [Authentication::class, 'login']);
//Resetting the password of Student Account 
Route::post('/reset-password', [Authentication::class, 'passwordReset']);
//Getting The Admin Profile
Route::get('/getAdminProfile', [Authentication::class, 'getAdminProfile']);

//Changing The Data In Admin Profile(ex. password)
Route::post('/change', [Authentication::class, 'changePassword']);
Route::get('/mark-all-read/{user}', [Authentication::class, 'markAllAsRead']);

Route::get('/mark-as-opened/{id}', [
    Authentication::class,
    'markNotifAsOpened',
]);

// Notifications
Route::get('/unreadNotif/{user}', [NotificationController::class, 'allUnreadNotif']);
Route::get('/allNotifications/{user}', [NotificationController::class, 'allNotif']);

//Sending the username and password to the users or students_id
// Route::get('send-sms/{id}', [NexmoSMSController::class, 'SMS']);

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

Route::get('/allSections/{gradelevel}', [SectionController::class, 'allSections']);

Route::get('/noAdviserSections', [SectionController::class, 'allSectionsWithNoAdviser']);

//--------------------------------------------------This Is For Enrollment Process API----------------------------------------//
Route::post('/addStudent', [EnrollmentController::class, 'addStudent']);
//This is for updating the student details
Route::post('/updateStudent/{id}', [EnrollmentController::class, 'updateStudent']);

//This is for udpating the data in decline student details
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
Route::get('/getTeacherSchedule/{teacher_id}', [ScheduleController::class, 'getTeacherSchedule',]);
Route::post('/addSchedules', [ScheduleController::class, 'addSchedules']);
Route::post('/deleteScheds', [ScheduleController::class, 'deleteSchedule']);

Route::get('/allTeacherAdmin', [AdminController::class, 'allTeacherAdmin']);
Route::post('/addNewAdmin', [AdminController::class, 'addNewAdmin']);
Route::post('/updateTeacherAdmin/{id}', [AdminController::class, 'updateTeacherAdmin']);
Route::get('/resetPassword/{id}', [AdminController::class, 'resetPassword']);

Broadcast::routes();
