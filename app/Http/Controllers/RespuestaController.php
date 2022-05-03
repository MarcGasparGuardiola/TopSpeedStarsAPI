<?php

namespace App\Http\Controllers;

use App\Models\Respuesta;
use App\Models\User;
use App\Models\Tema;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RespuestaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Respuesta::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Log::debug('Store respuesta');
        $statusCode = 500;
        $msg = LogInController::formatData(array('error' => 'Something went wrong'));

        $respuesta = new Respuesta();
        $respuesta->text = $request->input('text');
        //$respuesta->userId = $request->input('userId');
        //$respuesta->temaId = $request->input('temaId');

        $user = User::find($request->input('userId'));
        $tema = Tema::find($request->input('temaId'));


        Log::debug($tema);

        if ($user === null) {
            $statusCode = 404;
            $msg = array('msg' => 'User Id not found', );
            return response()->json($msg, $statusCode);
        } 

        if ($tema === null) {
            $statusCode = 404;
            $msg = array('msg' => 'Tema Id not found', );
            return response()->json($msg, $statusCode);
        }

        $user->respuestas()->save($respuesta);
        $tema->respuestas()->save($respuesta);

        try {
            $respuesta->save();
            $msg = array('msg' => 'Respuesta created succesfully', );
            $statusCode = 200;
        } catch (Exception $e) {
            Log::debug($e);
            $msg = array('error' => $e);
            $statusCode = 500;
        }

        return response()->json($msg, $statusCode);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Respuesta  $respuesta
     * @return \Illuminate\Http\Response
     */
    public function showAllResponsesOfTema(Request $request)
    {
        $temaId = $request->input('temaId');

        try {
            $msg = Respuesta::where('tema_id', $temaId)->get();
            $statusCode = 200;
            if (count($msg) === 0) {
                $msg = array('msg' => "No responses for this temaId");
                $statusCode = 202;
            }
        } catch (Exception $e) {
            $msg = array('error' => $e);
            $statusCode = 500;
        }

        return response()->json($msg, $statusCode);
    }

    public function showAllResponsesOfUser(Request $request)
    {
        $userId = $request->input('userId');

        try {
            $msg = Respuesta::where('user_id', $userId)->get();
            $statusCode = 200;
            if (count($msg) === 0) {
                $msg = array('msg' => "No responses for this userId");
                $statusCode = 202;
            }
        } catch (Exception $e) {
            $msg = array('error' => $e);
            $statusCode = 500;
        }

        return response()->json($msg, $statusCode);
    }

    public function getRespuestas(Request $request) {
        if ($request->query('temaId') !== null) {
            return $this->showAllResponsesOfTema($request);
        } else if ($request->query('userId') !== null) {
            return $this->showAllResponsesOfUser($request);
        } else {
            return $this->index();
        }
    }

   public function showResponse(Request $request)
   {
        $respuestaId = $request->route('id');

        try {
            $respuesta = Respuesta::find($respuestaId);

            if ($respuesta !== null) {
                $msg = array('body' => $respuesta);
                $statusCode = 200;
                return response()->json($msg, $statusCode);
            }
            $msg = array('body' => 'Response not found');
            $statusCode = 404;
            return response()->json($msg, $statusCode);
        } catch(Exception $e) {
            $msg = array('error' => $e);
            $statusCode = 500;
        }
        return response()->json($msg, $statusCode);
   }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Respuesta  $respuesta
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        try {
            $respuestaId = $request->route('id');
            $respuesta = Respuesta::find($respuestaId);

            $respuesta->text = $request->input('text');

            $respuesta->save();

            $msg = array('msg' => "Respuesta updated correctly");
            $statusCode = 200;
        } catch (Exception $e) {
            $msg = array('error' => $e);
            $statusCode = 500;
        }

        return response()->json($msg, $statusCode);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Respuesta  $respuesta
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $respuestaId = $request->route('id');

        try {
            Respuesta::find($respuestaId)->delete();
            $msg = array('msg' => "Respuesta deleted correctly");
            $statusCode = 200;
        } catch(Exception $e) {
            $msg = array('error' => $e);
            $statusCode = 500;
        }
        return response()->json($msg, $statusCode);
    }
}
