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


    public function getAdminProfile(Request $request){

        try{
            $List=User::all();
            return response()->json($List);         
        }
        catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()],500);
        }
   }


   //Changing a password in Admin User
   public function changePassword(Request $request){
        $request->validate([
            'username'=>'required',
            'currentpassword' =>['required',function($attribute, $value, $fail) {
                $admin=User::where('user_type','=','admin')->first();
                if(!Hash::check($value,$admin->password)){          
                    $fail('Your current password is incorrect.');
                 }
            }],
            'new_password' =>'required|different:currentpassword|min:8|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
            'confirm_password'=>'required|same:new_password|min:8|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
        ],
        [
         'new_password.regex' => 'Your new password should  be atleast 8 characters long ,contains-atleast 1 Uppercase,1 Lowercase,1 Numeric and 1 special character',
         'confirm_password.regex' => 'Your confirm password should  be atleast 8 characters long ,contains-atleast 1 Uppercase,1 Lowercase,1 Numeric and 1 special character',
        ]);

      
  
    try{ 
        $user=User::where('username','=',$request->username)->first();
        if(Hash::check($request->currentpassword,$user->password)){
         $user->password=Hash::make($request->new_password);
         $user->save();
          return ['message'=>'Password is successfully changed!'];           
          }
       }
    catch(\Exception $e){
        return response()->json(['error' => $e->getMessage()],500);
     }
   

   }

}
