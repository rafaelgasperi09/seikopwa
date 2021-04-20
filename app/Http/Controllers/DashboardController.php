<?php

namespace App\Http\Controllers;

use App\Componente;
use App\Equipo;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){

        $data['total_equipos'] =  Equipo::select('equipos.sub_equipos_id','equipos.tipo_equipos_id','tipo_equipos.display_name')
            ->FiltroCliente()
            ->join('tipo_equipos','equipos.tipo_equipos_id','=','tipo_equipos.id')
            ->groupBy('equipos.sub_equipos_id','equipos.tipo_equipos_id')
            ->count();

        $data['total_baterias'] =Componente::whereTipoComponenteId(2)->count();

        return view('frontend.dashboard',compact('data'));
    }
}
