<?php

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/formulario_data', function (Request $request) {
    $data =  \App\FormularioData::with('registro')->with('campo')
    ->when($request->has('formulario_registro_id'),function ($q) use($request){
        $q->where('formulario_registro_id',$request->formulario_registro_id);

    })->join('formulario_campos','formulario_data.formulario_campo_id','formulario_campos.id')
    ->orderBy('formulario_seccion_id')->get();
    foreach($data as $d){
        if($d->formulario_campo_id==1069){
            $sup=App\User::find($d->valor);
            if($sup)
            $d->valor=$sup->full_name;
        }
           
    }
    return response()->json(['success' => true,
        "draw"=> 1,
        "recordsTotal"=> $data->count(),
        "recordsFiltered"=> $data->count(),
        'total'=> $data->count(),
        'data' => $data]);
});

Route::get('/formulario_registro_estatus', function (Request $request) {
    $formulario_registro_id= (int)filter_var($request->formulario_registro_id, FILTER_SANITIZE_NUMBER_INT);
    
    $data =  \App\FormularioRegistroEstatus::with('registro')
        ->with('user')
        ->whereNotNull('estatus')
        ->when($request->has('formulario_registro_id'),function ($q) use($request,$formulario_registro_id){
            $q->where('formulario_registro_id',$formulario_registro_id);
        })->get();

    return response()->json(['success' => true,
        "draw"=> 1,
        "recordsTotal"=> $data->count(),
        "recordsFiltered"=> $data->count(),
        'total'=> $data->count(),
        'data' => $data]);
});

Route::get('/equipo', function (Request $request) {
    $data=array();
    $success=false;
    if($request->has('id')){
        $data =  \App\Equipo::find($request->id);
        $success=true;
    }
    if($request->has('numero_parte')){
        $data =  \App\Equipo::with('marca')->where('numero_parte',$request->numero_parte)->first();
        $success=true;
    }

    return response()->json(['success' => $success,
        "draw"=> 1,
        'data' => $data]);
});