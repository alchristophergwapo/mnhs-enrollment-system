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
    return view('enrollment.index');
});

Route::get('/sign-in', function() {
    return view('sign-in.index');
});

Route::get('/not-enrolled', function () {
    return view('dashboard.not-enrolled.index');
});

Route::get('/enrolled', function () {
    return view('dashboard.index');
});

