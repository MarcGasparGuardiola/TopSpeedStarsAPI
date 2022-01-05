<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class LogInController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function logIn(Request $request)
    {

        $email = $request->input('email');
        $password = $request->input('password');

        $bbdd = new UserController();
        $bbddSelect = $bbdd->getUserByMail($email);
        $responseCode = $bbddSelect[1];
        $user = $bbddSelect[0];

        if ($responseCode !== 500) {
            if (count($user) != 0) {
                $user = $user[0];

                if (Hash::check($password, $user->password)) {
                    // The passwords match...
                    $secret = DB::table('oauth_clients')
                        ->where('user_id', $user->id)->value('secret');

                    Log::debug($secret);

                    $response = Http::asForm()->post('http://localhost:8000/oauth/token', [
                        'grant_type' => 'password',
                        'client_id' => 2,
                        'client_secret' => $secret,
                        'username' => $email,
                        'password' => $password,
                        'scope' => '',
                    ]);


                    Log::debug($response);
                    $responseCode = 200;
                } else {
                    $responseCode = 204;
                    // If we put response code 204, the message will not appear
                    $user = array('message' => 'Incorrect mail/password combination');
                }
            } else {
                $responseCode = 204;
                // If we put response code 204, the message will not appear
                $user = array('message' => 'No user with email given');
            }
        }



        return response()->json($user, $responseCode);
    }

    //Funció que serveix per formatejar les dades a un JSON
    public static function formatData($data)
    {
        return json_encode($data,  JSON_FORCE_OBJECT);
    }
}
