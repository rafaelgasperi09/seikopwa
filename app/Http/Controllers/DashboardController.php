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
        ->whereRaw("(formulario_registro.estatus='C' and TIMESTAMPDIFF(DAY,formulario_registro.created_at,'2023-07-20')<=45 or formulario_registro.estatus<>'C')")
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
        if( current_user()->isOnGroup('supervisorc') or  current_user()->isOnGroup('programador') ){
            //daily check pendientes de firma supervisor
            $data['daily_check']=$this->getPendings('daily_check');
        }

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
        $tab['t1']=''; $tab['t2']='active show';

        return view('frontend.dashboard',compact('data'));
    }

    function grafica(Request $request){


        $query="SELECT fr.cliente_id,c.`nombre`,COUNT(DISTINCT(fr.equipo_id)) AS reportados,
        SUM( CASE fr.equipo_status WHEN 'O' THEN 1 ELSE 0 END) AS operativos,
        SUM( CASE fr.equipo_status WHEN 'I' THEN 1 ELSE 0 END) AS inoperativos,
        SUM( CASE fr.repuesto_status WHEN 'E' THEN 1 ELSE 0 END) AS en_espera,
        SUM( CASE fr.repuesto_status WHEN 'L' THEN 1 ELSE 0 END) AS listo
        FROM formulario_registro fr
        LEFT JOIN clientes_vw c ON  fr.`cliente_id`=c.`id`, equipos_vw e ,
        (SELECT equipo_id,cliente_id,MAX(id) AS id FROM formulario_registro
        WHERE  formulario_id=10 AND deleted_at IS NULL
        GROUP BY equipo_id,cliente_id)X  
        WHERE   fr.`equipo_id`=e.id AND fr.id=X.id AND fr.`cliente_id`=X.cliente_id
        AND fr.`deleted_at`IS NULL
        AND e.`numero_parte` NOT LIKE 'GM%'
        GROUP BY fr.cliente_id,c.`nombre`
        ";
        $res=DB::select(DB::Raw($query));
        $data=array();
        foreach($res as $r){
            $data['chart1']['n'][]=$r->nombre;
            $data['chart1']['r'][]=$r->reportados;
            $data['chart1']['o'][]=$r->operativos;
            $data['chart1']['i'][]=$r->inoperativos;

            $data['chart2']['n'][]=$r->nombre;
            $data['chart2']['e'][]=$r->en_espera;
            $data['chart2']['l'][]=$r->listo;
        }

        $query2="SELECT fr.cliente_id,c.`nombre`,COUNT(DISTINCT(fr.equipo_id)) AS reportes,
        SUM( CASE fr.estatus WHEN 'P' THEN 1 ELSE 0 END) AS pendientes,
        SUM( CASE fr.estatus WHEN 'A' THEN 1 ELSE 0 END) AS asignados,
        SUM( CASE fr.estatus WHEN 'PR' THEN 1 ELSE 0 END) AS proceso,
        SUM( CASE fr.estatus WHEN 'C' THEN 1 ELSE 0 END) AS cerrado
        FROM formulario_registro fr
        LEFT JOIN clientes_vw c ON  fr.`cliente_id`=c.`id`, equipos_vw e 
        WHERE   fr.`equipo_id`=e.id 
        AND fr.`deleted_at`IS NULL
        AND fr.formulario_id=10 
        AND e.`numero_parte` NOT LIKE 'GM%'
        GROUP BY fr.cliente_id,c.`nombre`";
        $res2=DB::select(DB::Raw($query2));
        foreach($res2 as $r){
            $data['chart3']['n'][]=$r->nombre;
            $data['chart3']['r'][]=$r->reportes;
            $data['chart3']['p'][]=$r->pendientes;
            $data['chart3']['a'][]=$r->asignados;
            $data['chart3']['pr'][]=$r->proceso;
            $data['chart3']['c'][]=$r->cerrado;
        }

        $query3="SELECT fr.cliente_id,c.`nombre`,COUNT(*) AS reportes,
        SUM( CASE fr.estatus WHEN 'P' THEN 1 ELSE 0 END) AS pendientes,
        SUM( CASE fr.estatus WHEN 'C' THEN 1 ELSE 0 END) AS cerrado
        FROM formulario_registro fr
        LEFT JOIN clientes_vw c ON  fr.`cliente_id`=c.`id`, equipos_vw e ,formularios f
        WHERE   fr.`equipo_id`=e.id 
        AND fr.`deleted_at`IS NULL
        AND fr.`formulario_id`=f.`id`
        AND f.tipo='mant_prev'
        AND e.`numero_parte` NOT LIKE 'GM%'
        GROUP BY fr.cliente_id,c.`nombre`";
        $res3=DB::select(DB::Raw($query3));
        foreach($res3 as $r){
            $data['chart4']['n'][]=$r->nombre;
            $data['chart4']['r'][]=$r->reportes;
            $data['chart4']['p'][]=$r->pendientes;
            $data['chart4']['c'][]=$r->cerrado;
        }
        $data['indice'][1]=['r','o','i','n'];
        $data['indice'][2]=['e','l','n'];
        $data['indice'][3]=['r','p','a','pr','c','n'];
        $data['indice'][4]=['r','p','c','n'];
    
        $data['ejey'][1]='Equipos';
        $data['ejey'][2]='Equipos';
        $data['ejey'][3]='Reportes';
        $data['ejey'][4]='Reportes';

        $data['leyenda'][1]=['Reportados','Operativo','Inoperativo'];
        $data['leyenda'][2]=['En espera','Listos'];
        $data['leyenda'][3]=['Reportes','Pendientes','Asignados','En Proceso','Cerrados'];
        $data['leyenda'][4]=['Reportes','Pendientes','Cerrados'];

        $data['titulo'][1]='Estatus equipos';
        $data['titulo'][2]='Estatus repuestos';
        $data['titulo'][3]='Estatus servicio tecnico';
        $data['titulo'][4]='Estatus MTT Preventivo';
        
        return view('frontend.dashboard.gmp',compact('data'));
    }
}
