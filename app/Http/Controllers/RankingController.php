<?php

namespace App\Http\Controllers;

use App\Models\Ranking;
use Exception;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RankingController extends BaseController
{
   function getUserRankingPosition () {
    //$id = $request->input('id');
    
    try {
        $msg = Ranking::orderBy('lapTime')->take(100)->get();
        $statusCode = 200;
    } catch (Exception $e) {
        $msg = array('error' => $e);
        Log::debug($e);
        $statusCode = 500;
    }

    return response()->json($msg, $statusCode);

   }

   function setRankingTime(Request $request) {
    //$id = $request->input('id');

    $ranking = new Ranking();
    $ranking->userId = $request->input('userId');
    $ranking->lapTime = $request->input('lapTime');
    $ranking->mapId = $request->input('mapId');

    try {
        $ranking->save();
        $statusCode = 200;
        $msg = array('msg' => 'Ranking created successfully');
    } catch (Exception $e) {
        $msg = array('error' => $e);
        $statusCode = 500;
    }

    return response()->json($msg, $statusCode);
   }

   function getAllUserRanking(Request $request) {
        $userId = $request->input('userId');

        try {
            $msg = Ranking::where('userId', $userId)->get();
            $statusCode = 200;
            if (count($msg) === 0) {
                $msg = array('msg' => "No results for this user");
                $statusCode = 202;
            }
        } catch (Exception $e) {
            $msg = array('error' => $e);
            $statusCode = 500;
        }

        return response()->json($msg, $statusCode);
   }
}
