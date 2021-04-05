<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\TipoEquipos;
use App\Equipos;
use App\SubEquipos;

class EquiposController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    public function tipos(){
        $subEquipos=SubEquipos::get();
        $tipoEquipos=TipoEquipos::get();
        return view('frontend.equipos')->with('tipos',$tipoEquipos)->with('subEquipos',$subEquipos);
    }
 
    public function index($id){
        $equipos=Equipos::where('tipo_equipos_id',$id)->get();
        return view('frontend.equipos')->with('equipos',$equipos);
    }
}
