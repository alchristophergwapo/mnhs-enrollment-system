<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TeacherController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\Authentication;
use App\Http\Controllers\EnrollmentController;

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
Route::post('addNewTeacher',[TeacherController::class, 'addTeacher']);

//Getting All Teachers
Route::get('allTeacher',[TeacherController::class, 'allTeachers']);

//Deleting All Teachers
Route::get('delTeacher/{id}',[TeacherController::class, 'removeTeacher']);

//Updating A Teacher
Route::post('updateTeacher/{id}',[TeacherController::class, 'updateTeacher']);

//Showing A Teacher By Id
Route::get('showByIdTeacher/{id}',[TeacherController::class, 'showByIdTeacher']);


//----------------------Admin Controller-----------------------------//
//Login For Admin 
Route::post('/login',[Authentication::class, 'login']);

//Getting The Admin Profile
Route::get('/getAdminProfile',[Authentication::class, 'getAdminProfile']);

//Changing The Data In Admin Profile(ex. password)
Route::post('/change',[Authentication::class, 'changePassword']);


//--------------------------Section Controller---------------------//
//Api For Adding Junior High School For A Section
Route::post('addSection',[SectionController::class, 'addAnySection']);

//Api For Getting All The Available Sections
Route::get('sections',[TeacherController::class, 'availableSection']);

//Api For Getting The Specific Section In A  Grade For Junior High School
Route::get('getSection/{grade}',[SectionController::class,'specificSection']);

//Api For Getting All The Section In Grade 7 And This Function is used also for gettinig All Grade 11
Route::get('grade7Section/{grade}',[SectionController::class,'grade7']);

//Api For Getting All The Section In Grade 12
Route::get('grade12Section/{grade}',[SectionController::class,'grade7']);

//Deleting Any Kind Of Sections
Route::get('delAnySection/{id}',[SectionController::class, 'delAnySection']);

//Retrieving The Specificy Sections For Updating Purposes In The FrontEnd With Any Kind Of Sections
Route::get('editSection/{id}',[SectionController::class,'editSection']);

//Display All kinds of Sections In Grade_Level
//Route::get('allSection',[SectionController::class,'allSection']);

//Getting The Selected GradeLever In GradeLevel Model
Route::get('selectedGradeLevel/{id}',[SectionController::class,'selectedGradeLevel']);

//Deleting Any Kind Of Sections
Route::post('updateSection/{id}',[SectionController::class,'updateSection']);
Route::get('/allSections',[SectionController::class, 'allSections']);

//--------------------------------------------------This Is For Enrollment Process API----------------------------------------//
Route::post('/addStudent', [EnrollmentController::class, 'addStudent']);
Route::get('/pendingEnrollments', [EnrollmentController::class, 'allPendingStudents']);
Route::get('/approvedEnrollments',[EnrollmentController::class , 'allEnrolledStudents']);
Route::get('/declinedEnrollments', [EnrollmentController::class, 'allDeclinedStudents']);
Route::post('/addEnrollment',[EnrollmentController::class, 'addEnrollment']);
Route::post('/approveEnrollment/{id}', [EnrollmentController::class, 'approveEnrollment']);


//Getting The Selected Section In When Approving Button In Enrollment.vue
Route::get('selectedGradeForSection/{id}', [EnrollmentController::class, 'selectedGradeForSection']);


Route::post('/declineEnrollment/{id}', [EnrollmentController::class, 'declineEnrollment']);
