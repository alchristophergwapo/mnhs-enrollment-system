<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TeacherController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\Authentication;
use App\Http\Controllers\EnrollmentController;

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
Route::post('addNewTeacher',[TeacherController::class, 'addTeacher']);

//Getting All Teachers
Route::get('allTeacher',[TeacherController::class, 'allTeachers']);

//Deleting All Teachers
Route::get('delTeacher/{id}',[TeacherController::class, 'removeTeacher']);

//Updating A Teacher
Route::post('updateTeacher/{id}',[TeacherController::class, 'updateTeacher']);

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

//Api For Getting All The Sections In Every GradeLevel
Route::get('allGradeLevelSections',[SectionController::class,'allGradeLevelSections']);

//Deleting Any Kind Of Sections
Route::get('delAnySection/{id}',[SectionController::class, 'delAnySection']);

//Updating Any Kind Of Sections
Route::post('updateSection/{id}',[SectionController::class,'updateSection']);

Route::get('/allSections',[SectionController::class, 'allSections']);

Route::get('/allTeachersForSection',[SectionController::class, 'allTeachersForSection']);




//--------------------------------------------------This Is For Enrollment Process API----------------------------------------//
Route::post('/addStudent', [EnrollmentController::class, 'addStudent']);

Route::get('/pendingEnrollment',[EnrollmentController::class, 'allPendingStudents']);

Route::get('/approvedEnrollment',[EnrollmentController::class , 'allEnrolledStudents']);

Route::get('/pendingEnrollments', [EnrollmentController::class, 'allPendingStudents']);

Route::get('/approvedEnrollments',[EnrollmentController::class , 'allEnrolledStudents']);

Route::get('/declinedEnrollments', [EnrollmentController::class, 'allDeclinedStudents']);
Route::post('/addEnrollment',[EnrollmentController::class, 'addEnrollment']);

Route::post('/approveEnrollment/{id}', [EnrollmentController::class, 'approveEnrollment']);

Route::get('/mark-all-read/{user}',function(User $user){
    $user->unreadNotifications->markAsRead();
    if($user) {
        return response(["message"=>"done"]);
    } else {
        return response(["message" => "Error"],400);
    }
});


//Getting The Selected Section In When Approving Button In Enrollment.vue
Route::get('selectedGradeForSection/{id}', [EnrollmentController::class, 'selectedGradeForSection']);

Route::post('/declineEnrollment/{id}', [EnrollmentController::class, 'declineEnrollment']);
