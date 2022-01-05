<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserController extends BaseController
{
    public function getUserByMailAndPassword($mail, $password)
    {
        try {

            $user = User::where('email', 'like', "$mail")
                ->where('password', 'like', "$password")
                ->get();

            return [$user, 200];
        } catch (Exception $e) {
            return [$e, 500];
        }
    }

    public function getUserByMail($mail)
    {
        try {

            $user = User::where('email', 'like', "$mail")
                ->get();

            return [$user, 200];
        } catch (Exception $e) {
            return [$e, 500];
        }
    }

    public function createUser(Request $request)
    {
        $statusCode = 500;
        $msg = LogInController::formatData(array('error' => 'Something went wrong'));

        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->email_verified_at = null;
        $user->password = Hash::make($request->input('password'));
        $user->api_token = Str::random(80);
        $user->global_score = 0;
        $user->remember_token = '';

        try {
            $user->save();
            $msg = array('msg' => 'User created succesfully');
            $statusCode = 200;
        } catch (Exception $e) {
            $msg = array('error' => $e);
            $statusCode = 500;
        }

        return response()->json($msg, $statusCode);
    }

    public function getUsers()
    {
        $users = User::all();
        return response()->json($users, 200);
    }
}
