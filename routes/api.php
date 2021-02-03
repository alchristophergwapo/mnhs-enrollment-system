<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
//use App\Http\Controllers\TeacherController;

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
Route::post('addNewTeacher','App\Http\Controllers\TeacherController@addTeacher');

//Getting All Teachers
Route::get('allTeacher','App\Http\Controllers\TeacherController@allTeachers');

//Deleting All Teachers
Route::get('delTeacher/{id}','App\Http\Controllers\TeacherController@removeTeacher');

//Updating A teacher
Route::post('updateTeacher/{id}','App\Http\Controllers\TeacherController@updateTeacher');

//Showing a teacher by id
Route::get('showByIdTeacher/{id}','App\Http\Controllers\TeacherController@showByIdTeacher');

