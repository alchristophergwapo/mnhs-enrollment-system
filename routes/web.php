<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('index');
});

Route::get('/enroll', function () {
    return view('enrollment.enrollment');
});

Route::get('/sign-in', 'App\Http\Controllers\Authentication@login');

Route::get('/not-enrolled', function () {
    return view('dashboard.not-enrolled.index');
});

Route::get('/enrolled', function () {
    return view('dashboard.student-dashboard.student-dash');
});

Route::get('/admin/{page}/{params?}/{x?}', function() {
    return redirect('/admin');
})->where('any', '*');

Route::get('/admin', function () {
    return view('dashboard.admin-dashboard.admin-dash');
});