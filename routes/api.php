<?php

use App\Http\Controllers\LogInController;
use App\Http\Controllers\RankingController;
use App\Http\Controllers\RespuestaController;
use App\Http\Controllers\TemaController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::post('/login', [LogInController::class, 'logIn']);
Route::post('/user', [UserController::class, 'createUser']);
Route::get('/users', [UserController::class, 'getUsers']);

Route::post('/tokens/create', function (Request $request) {
    $token = $request->user()->createToken($request->token_name);

    return ['token' => $token->plainTextToken];
});

Route::get('/getRankingPosition', [RankingController::class, 'getUserRankingPosition']);
Route::post('/setRankingTime', [RankingController::class, 'setRankingTime']);
Route::get('/getUserRankings', [RankingController::class, 'getAllUserRanking']);


Route::get('/temas', [TemaController::class, 'index']);
Route::get('/temas/{id}', [TemaController::class, 'show']);
Route::post('/temas', [TemaController::class, 'store']);
Route::post('/getuserTemas', [TemaController::class, 'store']);
Route::delete('/temas/{id}', [TemaController::class, 'destroy']);
Route::put('/temas/{id}', [TemaController::class, 'update']);

Route::post('/respuestas', [RespuestaController::class, 'store']);
Route::get('/respuestas', [RespuestaController::class, 'getRespuestas']);
Route::get('/respuestas/{id}', [RespuestaController::class, 'showResponse']);
Route::delete('/respuestas/{id}', [RespuestaController::class, 'destroy']);
Route::put('/respuestas/{id}', [RespuestaController::class, 'update']);