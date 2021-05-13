<?php

namespace App\Http\Controllers;

use App\Componente;
use App\Equipo;
use App\Formulario;
use App\FormularioRegistro;
use App\SubEquipo;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private function getPendings($formType,$status='P',$filterExtra='',$limit=100){
        $userFilter='';
        if(current_user()->crm_cliente_id)
            $userFilter='WHERE cliente_id='.current_user()->crm_cliente_id;

       $r=FormularioRegistro::selectRaw('formulario_registro.*')->join('formularios','formulario_registro.formulario_id','formularios.id')
        ->where('formularios.tipo',$formType)
        ->whereRaw("formulario_registro.estatus='".$status."'")
        ->whereNull('formulario_registro.deleted_at')
        ->whereRaw('equipo_id IN (SELECT id FROM montacarga.`equipos` '.$userFilter.') '.$filterExtra)->take($limit)->get();          
    
        return $r;
    }


    public function index(){

        ///////////// EQUIPOS ///////////////////////
        /// FILTRO SOLO LAS ELECTRICAS POR LOS DE COMBUSTION NO TIENEN SUB TIPO
        $equipos =  Equipo::FiltroCliente()->where('sub_equipos_id',getSubEquipo('electricas'))->whereNotNull('sub_equipos_id')->get();
        //dd($equipos->pluck('numero_parte','id'));

        ///////////// TOTAL EQUIPOS POR TIPO //////////////
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

        $data['total_equipos'] =  $tipoEquiposArray;

        ////////////// TOTAL BATERIAS ////////////////////////////////
        $data['total_baterias'] =Componente::whereTipoComponenteId(2)->count();

        ////////////// TOTAL EQUIPOS SIN DAILY CHECK ////////////////////////
        $data['equipos_sin_daily_check_hoy'] = array();

        if(current_user()->isCliente() or true){
            $formularioDailyCheck = Formulario::whereNombre('form_montacarga_daily_check')->first();
            $equipo_daily_check_today = FormularioRegistro::whereFormularioId($formularioDailyCheck->id)
                ->whereRaw("date_format(created_at,'%Y-%m-%d') ='".Carbon::now()->format('Y-m-d')."'")
                ->pluck('equipo_id')->toArray();
            foreach ($equipos as $e){
                if(!in_array($e->id,$equipo_daily_check_today))
                    $data['equipos_sin_daily_check_hoy'][$e->id]=$e->numero_parte;
            }
        }
        //dd($data['equipos_sin_daily_check_hoy'] );
        //daily check pendientes de firma supervisor    
        $data['daily_check']=$this->getPendings('daily_check');     
        //mantenimientos preventivos pendientes de firma supervisor    
        $data['mant_prev']=$this->getPendings('mant_prev');     
        //servicio tecnico PENDIENTES
        $data['serv_tec_p']=$this->getPendings('serv_tec');     
        //servicio tecnico ABIERTAS
        $data['serv_tec_a']=$this->getPendings('serv_tec','A',' AND formulario_registro.tecnico_asignado='.current_user()->id);     
        //servicio tecnico EN PROCESO
        $data['serv_tec_pr']=$this->getPendings('serv_tec','PR',' AND formulario_registro.tecnico_asignado='.current_user()->id);   
         //servicio tecnico EN PROCESO
        $data['serv_tec_10']=$this->getPendings('serv_tec',"P' or formulario_registro.estatus<>'",' ',10);   
      
        return view('frontend.dashboard',compact('data'));
    }
}
