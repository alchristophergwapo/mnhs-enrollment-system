<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Student;
use App\Models\Enrollment;
use App\Models\Section;
                                                                                        
class Authentication extends Controller
{
    public function login(Request $request) {
        
        $credentials = $request->only('username','password','user_type');
        try {
            if(Auth::attempt($credentials)) {
                if ($request->user_type == 'admin') {
                    $user = Auth::user()->load('notifications');
                    return response()->json(["user" => $user], 200);
                } else {
                    $user = Auth::user();
                    $userInfo = Student::with('enrollment')
                                        ->where('lrn', $user->username)
                                        ->first();

                    $classmates = Enrollment::with('student')
                                            ->where('student_section',$userInfo->enrollment->student_section)->first();

                    $section = Section::with('adviser')
                                        ->where('name',$userInfo->enrollment->student_section)
                                        ->first();

                    $userInfo['section'] = $section;

                    return response([
                            'user' => $user, 
                            'userInfo' => $userInfo, 
                            'classmates' => $classmates
                        ]);
                }
            } else {
                return response()->json(['error' => 'The credentials provided are invalid!'], 406);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function getAdminProfile(Request $request){

        try{
            $List=User::all();
            return response()->json($List);         
        }
        catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()],500);
        }
   }


   public function changePassword(Request $request){
        $request->validate([
            'username'=>'required',
            'currentpassword' =>['required'
            // ,function($attribute, $value, $fail) {
            //     $user=User::where('username',$request->username)->first();
            //     if(!\Hash::check($value,$user->password)){          
            //         $fail('Your current password is incorrect.');
            //      }
            // }
            ],
            'new_password' =>'required|different:currentpassword|min:8|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
            'confirm_password'=>'required|same:new_password|min:8|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
        ],
        [
         'new_password.regex' => 'Your new password should  be atleast 8 characters long ,contains-atleast 1 Uppercase,1 Lowercase,1 Numeric and 1 special character',
         'confirm_password.regex' => 'Your confirm password should  be atleast 8 characters long ,contains-atleast 1 Uppercase,1 Lowercase,1 Numeric and 1 special character',
        ]);

      
  
    try{ 
        $user=User::where('username','=',$request->username)->first();
           if( \Hash::check($request->currentpassword,$user->password)){
              $user->password= \Hash::make($request->new_password);
              $user->updated = 1;
              $user->save();
              return  response()->json(['message'=>'Password is successfully changed!'],200);           
           } else {
               return response()->json(['message' => 'Current password is incorrect!'], 400);
           }
       }
    catch(\Exception $e){
        return response()->json(['error' => $e->getMessage()],500);
     }
   

   }

}
