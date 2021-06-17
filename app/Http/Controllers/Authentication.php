<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Student;
use App\Models\Section;

use Carbon\Carbon;

class Authentication extends Controller
{

    public function init()
    {
        $user = Auth::user();

        return response(['user' => $user]);
    }
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');
        try {
            $signIn = Auth::attempt($credentials);
            if ($signIn) {
                $user = Auth::user();
                if ($user->user_type == 'student') {
                    $userInfo = Student::with('enrollment')
                        ->where('LRN', $user->username)
                        ->first();

                    $section = Section::with('adviser')
                        ->where('id', $userInfo->enrollment[count($userInfo->enrollment) - 1 ]->student_section)
                        ->first();

                    $userInfo['section'] = $section;
                    return response()->json(
                        [
                            'user' => $user,
                            'userInfo' => $userInfo
                        ],
                    );
                } else {
                    $user->load('notifications');
                    return response()->json(
                        ['user' => $user],
                    );
                }
            } else {
                return response()->json(
                    ['error' => 'The credentials provided are invalid!'],
                    406
                );
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getAdminProfile(Request $request)
    {
        try {
            $List = User::all();
            return response()->json($List);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function changePassword(Request $request)
    {
        $user = User::where('username', '=', $request->username)->first();
        $request->validate(
            [
                'username' => 'required',
                'email' => 'required|email:rfc,dns|max:100|unique:users,email,'.$user->id,
                'currentpassword' => [
                    'required'
                ],
                'new_password' =>
                'required|different:currentpassword|min:8|max:16|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-_]).{6,}$/',
                'confirm_password' =>
                'required|same:new_password|min:8|max:16',
            ],
            [
                'currentpassword.required' => 'Current password field is required.',
                'new_password.required' => 'New password field is required.',
                'confirm_password.required' => 'Confirm password field is required.',
                'new_password.regex' =>
                'Your new password should  be atleast 8 characters long ,contains-atleast 1 Uppercase,1 Lowercase,1 Numeric and 1 special character'
            ]
        );

        try {
            $user = User::where('username', '=', $request->username)->first();
            if (\Hash::check($request->currentpassword, $user->password)) {
                $user->email = $request->email;
                $user->password = \Hash::make($request->new_password);
                $user->updated = 1;
                $user->save();
                return response()->json(
                    ['message' => 'Password is successfully changed!'],
                    200
                );
            } else {
                return response()->json(
                    ['message' => 'Current password is incorrect!'],
                    400
                );
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function passwordReset(Request $request)
    {
        try {
            \DB::beginTransaction();
            $student = Student::where('LRN', '=', $request->LRN)->first();
            User::where('username', '=', $request->LRN)->update([
                'password' => \Hash::make($student->lastname . $student->LRN)
            ]);
            \DB::commit();

            return response(['success' => 'Password has been successfully reset.']);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response(['error' => $e->getMessage()]);
        }
    }

    public function markAllAsRead(User $user)
    {
        $user->unreadNotifications->markAsRead();
        if ($user) {
            return response([
                'message' => 'done',
                'user' => $user->load('notifications'),
            ]);
        } else {
            return response(['message' => 'Error'], 400);
        }
    }

    public function markNotifAsOpened($id)
    {
        try {
            \DB::beginTransaction();

            \DB::table('notifications')
                ->where('id', '=', $id)
                ->update([
                    'opened_at' => Carbon::now(),
                ]);

            \DB::commit();

            $notif = \DB::table('notifications')
                ->where('id', '=', $id)
                ->first();

            return response()->json([
                'message' => 'Marked as opened.',
                'notification' => $notif,
            ]);
        } catch (\Exception $e) {
            \DB::rollback();

            return response(['error' => $e->getMessage()], 500);
        }
    }
}
