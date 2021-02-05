<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;
use App\User;

                                                                                        
class Authentication extends Controller
{

    public function __construct()
    {
       $this->middleware('guest');
    }

    public function init(){
        if (Auth::check()) {
            if (Auth::user()->user_type === "student") {
                $user = User::get()->find(Auth::user()->id);
            }else{
                $user = User::get()->find(Auth::user()->id);
                return response()->json(["user" => $user], 200);
            }
        }
    }

    public function login(Request $request) {
        
        $credentials = $request->only('username','password','user_type');
        try {
            if(Auth::attempt($credentials)){
                $user = Auth::user();
                return response()->json(["user" =>$user],200);
            } else {
                return response()->json(['error' => 'invalid credentials'], 401);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }

    public function getAdminProfile(Request $request){

        try{
            $List=User::all();
            return response()->json($List);         
        }
        catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 401);
        }
   }


   //Changing a password in Admin User
   public function changePassword(Request $request){

        Validator::extend('without_spaces', function($attr, $value){
            return preg_match('/^\S*$/u',$value);
        });
    
        $request->validate([
            'username'=>'required',
            'currentpassword' =>'required',
            'new_password' =>'required|different:currentpassword|min:8|without_spaces|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
            'confirm_password'=>'required|without_spaces|same:new_password',
        ],
        [
         'new_password.without_spaces' =>'Whitespace is not allowed.',
         'new_password.regex' => 'Your password should  be atleast 8 characters long ,contains-atleast 1 Uppercase,1 Lowercase,1 Numeric and 1 special character',
         'confirm_password.without_spaces' =>'Whitespace is not allowed.'
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
        return response()->json(['error' => $e->getMessage()], 401);
     }
   

   }

}
