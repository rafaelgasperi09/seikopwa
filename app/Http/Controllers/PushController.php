<?php

namespace App\Http\Controllers;

use App\Formulario;
use App\FormularioRegistro;
use App\Notifications\DailyCheck;
use App\User;
use Illuminate\Http\Request;

class PushController extends Controller
{
    /**
     * Store the PushSubscription.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request){
        $this->validate($request,[
            'endpoint'    => 'required',
            'keys.auth'   => 'required',
            'keys.p256dh' => 'required'
        ]);
        $endpoint = $request->endpoint;
        $token = $request->keys['auth'];
        $key = $request->keys['p256dh'];
        current_user()->updatePushSubscription($endpoint, $key, $token);

        return response()->json(['success' => true],200);
    }

}
