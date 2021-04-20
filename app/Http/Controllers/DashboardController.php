<?php

namespace App\Http\Controllers;

use App\Componente;
use App\Equipo;
use App\SubEquipo;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){

        $data['total_equipos'] =  Equipo::FiltroCliente()->count();

        $tipoEquipos = Equipo::selectRaw('count(equipos.id) as total,equipos.sub_equipos_id,equipos.tipo_equipos_id,tipo_equipos.display_name')
            ->FiltroCliente()
            ->join('tipo_equipos','equipos.tipo_equipos_id','=','tipo_equipos.id')
            ->groupBy('equipos.sub_equipos_id','equipos.tipo_equipos_id')
            ->get();

        $tipoEquiposArray=array();
        foreach($tipoEquipos as $t){
            $se = SubEquipo::find($t->sub_equipos_id);
            $tipoEquiposArray[$t->sub_equipos_id]['name']=$se->name;
            $tipoEquiposArray[$t->sub_equipos_id]['sub_equipo_id']=$t->sub_equipos_id;
            $tipoEquiposArray[$t->sub_equipos_id]['tipos'][$t->tipo_equipos_id]['tipo_id']=$t->tipo_equipos_id;
            $tipoEquiposArray[$t->sub_equipos_id]['tipos'][$t->tipo_equipos_id]['tipo']=$t->display_name;
            $tipoEquiposArray[$t->sub_equipos_id]['tipos'][$t->tipo_equipos_id]['total']=$t->total;
        }

        //sdd($tipoEquiposArray);
        $data['total_equipos'] =  $tipoEquiposArray;

        $data['total_baterias'] =Componente::whereTipoComponenteId(2)->count();

        return view('frontend.dashboard',compact('data'));
    }
}
