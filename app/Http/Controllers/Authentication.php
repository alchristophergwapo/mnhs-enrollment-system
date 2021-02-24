<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Student;
                                                                                        
class Authentication extends Controller
{

    public function login(Request $request) {
        $credentials = $request->only('username','password','user_type');

        try {
            if(Auth::attempt($credentials)) {
                if ($request->user_type == 'admin') {
                    $user = Auth::user();
                    return response()->json(["user" => $user], 200);
                } else {
                    $user = Auth::user();
                    $userInfo = Student::where('lrn', $user->username)->get();
                    return response()->json(['user' => $user, 'userInfo' => $userInfo[0]], 200);
                }
            } else {
                return response()->json(['error' => 'invalid credentials'], 406);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
