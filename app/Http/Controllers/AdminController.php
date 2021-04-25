<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\NewUserRequest;

use App\Models\User;
use App\Models\UserDetails;

class AdminController extends Controller
{

    public function addNewAdmin(NewUserRequest $request)
    {
        $userValidated = $request->validated();

        try {
            \DB::beginTransaction();
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
        } catch (\Exception $e) {
            \DB::rollBack();
            return response(['error' => $e->getMessage()], 500);
        }
    }

    public function editAccount(NewUserRequest $request, $id)
    {
        $editAccountValidated = $request->validated();

        try {
            \DB::beginTransaction();
            if ($editAccountValidated) {
                User::where('id', '=', $id)->update($editAccountValidated);
                \DB::commit();
                return response(['success' => "Account Updated Successfully."]);
            } else {
                \DB::rollBack();
                return response(['error' => 'Something went wrong!'], 400);
            };
        } catch (\Exception $e) {
            \DB::rollBack();
            return response(['error' => $e->getMessage()], 500);
        }
    }
}
