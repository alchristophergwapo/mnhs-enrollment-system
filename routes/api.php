<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\Authentication;

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

//Adding A New Teacher
Route::post('addNewTeacher',[TeacherController::class, 'addTeacher']);

//Getting All Teachers
Route::get('allTeacher',[TeacherController::class, 'allTeachers']);

//Deleting All Teachers
Route::get('delTeacher/{id}',[TeacherController::class, 'removeTeacher']);

//Updating A teacher
Route::post('updateTeacher/{id}',[TeacherController::class, 'updateTeacher']);

//Showing a teacher by id
Route::get('showByIdTeacher/{id}',[TeacherController::class, 'showByIdTeacher']);

//Login for Admin 
Route::post('/login',[Authentication::class, 'login']);

//Getting the Admin profile
Route::get('/getAdminProfile',[Authentication::class, 'getAdminProfile']);

//Changing the data in admin Profile(ex. password)
Route::post('/change',[Authentication::class, 'changePassword']);