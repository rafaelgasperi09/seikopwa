<?php

namespace App\Http\Controllers;

use App\Componente;
use App\Equipo;
use App\Formulario;
use App\FormularioRegistro;
use App\SubEquipo;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private function getPendings($formType,$status='P',$filterExtra='',$equipos=true,$pluck=''){
        $userFilter='';
        
        if(current_user()->crm_clientes_id){
              $userFilter='WHERE cliente_id in ('.limpiar_lista(current_user()->crm_clientes_id).')';
        }
          

       $idqeuipos=DB::connection('crm')->select(DB::raw('SELECT id FROM montacarga.equipos '.$userFilter));
       $lista=array();
       
       foreach($idqeuipos as $k=>$i){
           $lista[]=$i->id;
       }
       $lista=implode(',',$lista);
       if(empty($lista))
        $lista='0';

        $equipos=DB::connection('crm')->select(DB::raw('SELECT * FROM montacarga.equipos WHERE cliente_id in ('.$lista.')'));
       $r=FormularioRegistro::selectRaw('formulario_registro.*')
        ->join('formularios','formulario_registro.formulario_id','formularios.id')
         ->whereNotNull('equipo_id')
        ->where('formularios.tipo',$formType)
        ->When(!empty($status),function($q)use($status){
            $q->where('formulario_registro.estatus',$status);
        })
        ->When(!empty($userFilter),function($q)use($userFilter,$lista){
            $q->whereRaw(' equipo_id IN ('.$lista.')');
        })
        ->When(!empty($filterExtra),function($q)use($filterExtra){
            $q->whereRaw($filterExtra);
        });
        if(empty($pluck)){
            return  $r->orderBy('created_at','desc')->get();

        }else{
            return $r->pluck('equipo_id');
        }



    }


    public function index(){

        ///////////// EQUIPOS ///////////////////////
        /// FILTRO SOLO LAS ELECTRICAS POR LOS DE COMBUSTION NO TIENEN SUB TIPO
        $equipos =  Equipo::FiltroCliente()->get();
        //dd($equipos->pluck('numero_parte','id'));
        ///////////// TOTAL EQUIPOS POR TIPO //////////////
        $tipoEquiposArray=array();
        $tipoEquiposArrayAlquiler=array();

        $tipoEquiposElectricas = Equipo::selectRaw("count(equipos.id) as total,
                                                    SUM(CASE WHEN SUBSTR(equipos.numero_parte,1,2)='GM' THEN 1 ELSE 0 END) AS GMtotal,
                                                    equipos.sub_equipos_id,equipos.tipo_equipos_id,
                                                    tipo_equipos.display_name")
            ->FiltroCliente()
            ->join('tipo_equipos','equipos.tipo_equipos_id','=','tipo_equipos.id')
            ->where('equipos.sub_equipos_id','=',2)
            ->groupBy('equipos.sub_equipos_id','equipos.tipo_equipos_id')
            ->get();


        foreach($tipoEquiposElectricas as $t){
            $se = SubEquipo::find($t->sub_equipos_id);
            $tipoEquiposArray[$t->sub_equipos_id]['name']=$se->name;
            $tipoEquiposArray[$t->sub_equipos_id]['sub_equipo_id']=$t->sub_equipos_id;
            $tipoEquiposArray[$t->sub_equipos_id]['tipos'][$t->tipo_equipos_id]['tipo_id']=$t->tipo_equipos_id;
            $tipoEquiposArray[$t->sub_equipos_id]['tipos'][$t->tipo_equipos_id]['tipo']=$t->display_name;
            $tipoEquiposArray[$t->sub_equipos_id]['tipos'][$t->tipo_equipos_id]['total']=$t->total;
            $tipoEquiposArray[$t->sub_equipos_id]['tipos'][$t->tipo_equipos_id]['GMtotal']=$t->GMtotal;
        }

        $tipoEquiposCombustion = Equipo::selectRaw("count(equipos.id) as total,
                                                    SUM(CASE WHEN SUBSTR(equipos.numero_parte,1,2)='GM' THEN 1 ELSE 0 END) AS GMtotal,
                                                    equipos.sub_equipos_id,
                                                    equipos.tipo_motore_id,
                                                    tipo_motores.display_name")
            ->FiltroCliente()
            ->join('tipo_motores','equipos.tipo_motore_id','=','tipo_motores.id')
            ->where('equipos.sub_equipos_id','=',1)
            ->groupBy('equipos.sub_equipos_id','equipos.tipo_motore_id')
            ->get();


        foreach($tipoEquiposCombustion as $t){
            $se = SubEquipo::find($t->sub_equipos_id);
            $tipoEquiposArray[$t->sub_equipos_id]['name']=$se->name;
            $tipoEquiposArray[$t->sub_equipos_id]['sub_equipo_id']=$t->sub_equipos_id;
            $tipoEquiposArray[$t->sub_equipos_id]['tipos'][$t->tipo_motore_id]['tipo_id']=$t->tipo_motore_id;
            $tipoEquiposArray[$t->sub_equipos_id]['tipos'][$t->tipo_motore_id]['tipo']=$t->display_name;
            $tipoEquiposArray[$t->sub_equipos_id]['tipos'][$t->tipo_motore_id]['total']=$t->total;
            $tipoEquiposArray[$t->sub_equipos_id]['tipos'][$t->tipo_motore_id]['GMtotal']=$t->GMtotal;
        }


        $data['total_equipos'] =  $tipoEquiposArray;


        ////////////// TOTAL BATERIAS ////////////////////////////////
        $data['total_baterias'] =Componente::FiltroBodega()->whereTipoComponenteId(2)->count();

        ////////////// TOTAL EQUIPOS SIN DAILY CHECK ////////////////////////
        $data['equipos_sin_daily_check_hoy'] = array();
        $formularioDailyCheck = Formulario::whereNombre('form_montacarga_daily_check')->first();

        if(current_user()->isCliente() or  current_user()->isOnGroup('programador')){
            $equipo_daily_check_today = FormularioRegistro::whereFormularioId($formularioDailyCheck->id)
                ->whereRaw("date_format(created_at,'%Y-%m-%d') ='".Carbon::now()->format('Y-m-d')."'")
                ->pluck('equipo_id')
                ->toArray();
            $i=0;
            foreach ($equipos as $e){
                if(current_user()->isOnGroup('programador') and $i++>=10){
                    break;
                }
                if(!in_array($e->id,$equipo_daily_check_today)){
                    $data['equipos_sin_daily_check_hoy'][$e->id]=$e->numero_parte;
                }
            }
        }

        //daily check pendientes de firma supervisor
        $data['daily_check']=$this->getPendings('daily_check');
        //mantenimientos preventivos pendientes de firma supervisor
        $data['mant_prev']=$this->getPendings('mant_prev');
        //servicio tecnico PENDIENTES
        $data['serv_tec_p']=$this->getPendings('serv_tec');
        //servicio tecnico PENDIENTES DE INICIAR
        $data['serv_tec_pi_a']=$this->getPendings('serv_tec','A');
        //servicio tecnico ABIERTAS
       if(current_user()->isOnGroup('programador') or current_user()->isOnGroup('administrador'))
            $cond1='';
        else
            $cond1=' formulario_registro.tecnico_asignado='.current_user()->id;
        $data['serv_tec_a']=$this->getPendings('serv_tec','A',$cond1);
        //servicio tecnico EN PROCESO
        if(current_user()->isOnGroup('programador') or current_user()->isOnGroup('administrador'))
            $cond2='';
        else
            $cond2=' formulario_registro.tecnico_asignado='.current_user()->id;
        $data['serv_tec_pr']=$this->getPendings('serv_tec','PR',$cond2);
         //servicio tecnico EN PROCESO
         $data['serv_tec_10']=array();

         if(current_user()->isOnGroup('administrador') or current_user()->isOnGroup('programador') or current_user()->isSupervisor())
            $data['serv_tec_10']=$this->getPendings('serv_tec','','',false,'');

        if(current_user()->isOnGroup('supervisorc'))
            $data['serv_tec_10']=$this->getPendings('serv_tec','','',true,'');

        if(current_user()->isOnGroup('administrador') or  current_user()->isOnGroup('programador')){
            $dailyCheck =  $this->getPendings('daily_check','', "date_format(formulario_registro.created_at,'%Y-%m-%d') ='".Carbon::now()->format('Y-m-d')."'",false,true);

            $dailyCheckString='';
            foreach($dailyCheck as $k=>$dc){
                $dailyCheckString.=$dc.',';
            }
            $dailyCheckString.='0';
            $data['global_sin_daily_check_hoy']=  Equipo::selectRaw('contactos.nombre,count(*) as equipos, SUM(CASE WHEN equipos.id IN ('.$dailyCheckString.') THEN 1 ELSE 0 END ) AS daily_check')
                                    ->join('contactos','equipos.cliente_id','contactos.id')
                                    ->where('sub_equipos_id',getSubEquipo('electricas'))
                                    ->whereNotNull('sub_equipos_id')
                                    ->groupBy('contactos.nombre')
                                    ->get()->toArray();


        }

        return view('frontend.dashboard',compact('data'));
    }
}
