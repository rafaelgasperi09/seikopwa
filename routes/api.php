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
    $form= \App\FormularioRegistro::find($request->formulario_registro_id);
    $historia=array();
  
   
    foreach($data as $d){
        $historia[]='<div class="item">';
        $historia[]='<span class="time">'.\Carbon\Carbon::parse($d->created_at)->format('d-m-Y').'<br/>'.\Carbon\Carbon::parse($d->created_at)->format('H:i:s').'</span>';

        if($form->formulario->tipo=='serv_tec'){

            switch($d->estatus){
                case 'P':
                    $historia[]='<div class="dot bg-warning"></div>';
                    $historia[]='<div class="content">';
                    $historia[]='<h4 class="title">PENDIENTe</h4>';
                    $historia[]='<div class="text">Nuevo ticket de servicio tecnico creado por '.$d->user->first_name.' '.$d->user->last_name.'</div>';
                    $historia[]='</div></div>';
                    break;
                case 'A':
                    $historia[]='<div class="dot bg-success"></div>';
                    $historia[]='<div class="content">';
                    $historia[]='<h4 class="title">ASIGNADA</h4>';
                    $historia[]='<div class="text">asigno ticket de servico tecnico a  '.$d->registro->tecnicoAsignado->first_name.' '.$d->registro->tecnicoAsignado->last_name.'</div>';
                    $historia[]='</div></div>';
                    break;
                case 'PR':
                    $historia[]='<div class="dot bg-primary"></div>';
                    $historia[]='<div class="content">';
                    $historia[]='<h4 class="title">INICIADA</h4>';
                    $historia[]='<div class="text"> dio inicio a tareas de soporte tecnico </div>';
                    $historia[]='</div></div>';
                    break;
                case 'C':
                    $historia[]='<div class="dot bg-secundary"></div>';
                    $historia[]='<div class="content">';
                    $historia[]='<h4 class="title">CERRADO</h4>';
                    $historia[]='<div class="text"> dio por finalizada las tareas de soporte tecnico  </div>';
                    $historia[]='</div></div>';
                    break;
            }

        }
        elseif($form->formulario->tipo=='mant_prev'){
            switch($d->estatus){
                case 'S':
                    $historia[]='<div class="dot bg-primary"></div>';
                    $historia[]='<div class="content">';
                    $historia[]='<h4 class="title">INICIADO</h4>';
                    $historia[]='<div class="text">Iniciada la actividad por el técnico:'.$d->user->first_name.' '.$d->user->last_name.'</div>';
                    $historia[]='</div></div>';
                    break;
                case 'P':
                    $historia[]='<div class="dot bg-warning"></div>';
                    $historia[]='<div class="content">';
                    $historia[]='<h4 class="title">PENDIENTE</h4>';
                    $historia[]='<div class="text"> El técnico '.$d->user->first_name.' '.$d->user->last_name.' finaliza la actividad  </div>';
                    $historia[]='</div></div>';
                    break;
                case 'C':
                    $historia[]='<div class="dot bg-secundary"></div>';
                    $historia[]='<div class="content">';
                    $historia[]='<h4 class="title">CERRADO</h4>';
                    $historia[]='<div class="text">Reporte finalizado con firma '.$d->user->first_name.' '.$d->user->last_name.' </div>';
                    $historia[]='</div></div>';
                    break;
                }
        }
    }
   $historia=implode(PHP_EOL,$historia);
   
    return response()->json(['success' => true,
        "draw"=> 1,
        "recordsTotal"=> $data->count(),
        "recordsFiltered"=> $data->count(),
        'total'=> $data->count(),
        'data' => $data,
        'historia' => $historia,
    ]);
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