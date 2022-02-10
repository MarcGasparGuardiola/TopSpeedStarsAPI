<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
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
        $response = $bbddSelect[0];

        try {
            if ($responseCode !== 500) {
                if (count($response) != 0) {
                    $response = $response[0];


                    // The passwords match...
                    $secret = DB::table('oauth_clients')
                        ->where('id', 2)->value('secret');

                    $response = Http::asForm()->post('http://127.0.0.1:8001/oauth/token', [
                        'grant_type' => 'password',
                        'client_id' => 2,
                        'client_secret' => $secret,
                        'username' => $email,
                        'password' => $password,
                        'scope' => '',
                    ]);

                    $responseBody = $response->json();
                    $responseCode = $response->status();
                } else {
                    $responseCode = 400;
                    // If we put response code 204, the message will not appear
                    $responseBody = array('message' => 'No user found with email given');
                }
            }
        } catch (Exception $e) {
            $responseCode = 500;
            // If we put response code 204, the message will not appear
            $responseBody = array('error' => $e);
            Log::debug($e);
        }
        return response()->json($responseBody, $responseCode);
    }

    //Funció que serveix per formatejar les dades a un JSON
    public static function formatData($data)
    {
        return json_encode($data,  JSON_FORCE_OBJECT);
    }
}
