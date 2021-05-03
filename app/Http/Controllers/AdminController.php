<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\NewUserRequest;

use App\Models\User;
use App\Models\UserDetails;

class AdminController extends Controller
{

    public function allTeacherAdmin()
    {
        $teacher_admin = User::where('user_type', 'teacher_admin')
            ->leftJoin('user_details', 'user_details.user_id', 'users.id')
            ->select(
                'users.username',
                'users.id',
                'user_details.user_fullname'
            )->get();

        return response(['teacher_admins' => $teacher_admin]);
    }

    public function addNewAdmin(NewUserRequest $request)
    {
        $userValidated = $request->validated();

        try {
            \DB::beginTransaction();
            $admin = User::where('username', $request->username)->first();
            $admin_details = UserDetails::where('user_fullname', $request->user_fullname)
                ->leftJoin('users', 'users.id', 'user_details.user_id')
                ->select('users.username', 'user_details.user_fullname')
                ->first();
            if ($admin) {
                return response(['teacher_admin_exist' => 'An admin for grade ' . explode("_", $request->username)[1] . ' already exist.', "teacher_admin" => $admin], 400);
            }
            if ($admin_details) {
                return response(['teacher_isAssigned' => $admin_details->user_fullname . ' is already assigned to grade ' . explode("_", $admin_details->username)[1]], 400);
            } else {
                if ($userValidated) {
                    $newAdmin = User::create([
                        'username' => $request->username,
                        'password' => \Hash::make($request->password),
                        'user_type' => $request->user_type,
                    ]);
                    $user_details = UserDetails::create([
                        'email' => $request->user_email,
                        'user_fullname' => $request->user_fullname,
                        'user_id' => $newAdmin->id,
                    ]);
                    if ($user_details) {
                        \DB::commit();
                        return response(['success' => "New Admin Created."]);
                    } else {
                        \DB::rollBack();
                        return response(['error' => 'Something went wrong!', 500]);
                    }
                } else {
                    \DB::rollBack();
                    return response(['error' => 'Some datas are invalid! If this is a mistake, please try again.'], 500);
                }
            }
        } catch (\Exception $e) {
            \DB::rollBack();
            return response(['error' => $e->getMessage()], 500);
        }
    }

    public function updateTeacherAdmin(NewUserRequest $request, $id)
    {
        $editAccountValidated = $request->validated();

        try {
            \DB::beginTransaction();
            if ($editAccountValidated) {
                User::where('id', '=', $id)->update($editAccountValidated);
                UserDetails::where('user_id', $id)->update([
                    'email' => $request->user_email,
                    'user_fullname' => $request->user_fullname
                ]);
                \DB::commit();
                return response(['success' => "Account Updated Successfully.", $editAccountValidated]);
            } else {
                \DB::rollBack();
                return response(['error' => 'Something went wrong!'], 400);
            };
        } catch (\Exception $e) {
            \DB::rollBack();
            return response(['error' => $e->getMessage()], 500);
        }
    }

    public function resetPassword($id)
    {
        try {
            \DB::beginTransaction();
            User::where('id', $id)->update([
                'password' => \Hash::make('Password')
            ]);
            \DB::commit();
            return response(['success' => 'Password reset successfull.']);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
