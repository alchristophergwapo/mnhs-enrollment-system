<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\User;
                                                                                        
class Authentication extends Controller
{

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function init() {
        if (Auth::check()) {
            if (Auth::user()->user_type === "student") {
                $user = User::get()->find(Auth::user()->id);
            } else {
                $user = User::get()->find(Auth::user()->id);
                return response()->json(["user" => $user], 200);
            }
        }
    }

    public function login(Request $request) {
        $credentials = $request->only('username','password','user_type');

        try {
            if(Auth::attempt($credentials)) {
                $user = Auth::user();
                // dd($user);
                return response()->json(["user" => $user], 200);
            } else {
                return response()->json(['error' => 'invalid credentials'], 401);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }
}
