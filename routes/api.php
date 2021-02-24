<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TeacherController;
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

Route::post('/login',[Authentication::class, 'login']);
Route::post('/addStudent', [EnrollmentController::class, 'addStudent']);
Route::get('/pendingEnrollment', [EnrollmentController::class, 'allPendingStudents']);
Route::post('/addEnrollment',[EnrollmentController::class, 'addEnrollment']);
Route::post('/approveEnrollment/{id}', [EnrollmentController::class, 'approveEnrollment']);
Route::post('/declineEnrollment/{id}', [EnrollmentController::class, 'declineEnrollment']);
