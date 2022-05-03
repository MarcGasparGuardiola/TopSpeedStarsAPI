<?php

namespace App\Http\Controllers;

use App\Models\Tema;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TemaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $temas = Tema::all();
        return response()->json($temas, 200);
    }

    public function getTemasByUser(Request $request) {
        $userId = $request->query('userId');

        try {
            $msg = Tema::where('userId', $userId)->get();
            $statusCode = 200;
            if (count($msg) === 0) {
                $msg = array('msg' => "No temas for this userId");
                $statusCode = 202;
            }
        } catch (Exception $e) {
            $msg = array('error' => $e);
            $statusCode = 500;
        }

        return response()->json($msg, $statusCode);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $statusCode = 500;
        $msg = LogInController::formatData(array('error' => 'Something went wrong'));

        $tema = new Tema();
        $tema->title = $request->input('title');
        $tema->text = $request->input('text');
        //$tema->userId = $request->input('userId');

        $user = User::find($request->input('userId'));
        //Log::debug($user);
        $user->temas()->save($tema);

        try {
            $tema->save();
            $msg = array('msg' => 'Tema created succesfully', );
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
     * @param  \App\Models\Tema  $tema
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $temaId = $request->route('temaId');

        try {
            $msg = Tema::where('id', $temaId)->get();
            $statusCode = 200;
            if (count($msg) === 0) {
                $msg = array('msg' => "No results for this temaId");
                $statusCode = 202;
            }
        } catch (Exception $e) {
            $msg = array('error' => $e);
            $statusCode = 500;
        }

        return response()->json($msg, $statusCode);
    }

    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tema  $tema
     * @return \Illuminate\Http\Response
     */
    public function edit(Tema $tema)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tema  $tema
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        try {
            $temaId = $request->route('id');
            $tema = Tema::find($temaId);

            $tema->title = $request->input('title');
            $tema->text = $request->input('text');

            $tema->save();

            $msg = array('msg' => "Tema updated correctly");
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
     * @param  \App\Models\Tema  $tema
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $temaId = $request->route('id');
        try {
            $tema = Tema::find($temaId);
            Log::debug($tema);
            $tema->delete();
            $msg = array('msg' => "Tema deleted correctly");
            $statusCode = 200;
        } catch(Exception $e) {
            $msg = array('error' => $e);
            $statusCode = 500;
        }
        return response()->json($msg, $statusCode);
    }
}
