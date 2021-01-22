<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Authentication extends Controller
{
    //
    public function login() {
        return view('sign-in.sign-in');
    }
}
