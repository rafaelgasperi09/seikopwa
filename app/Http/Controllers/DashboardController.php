<?php

namespace App\Http\Controllers;

use App\Componente;
use App\Equipo;
use App\Cliente;
use App\Formulario;
use App\FormularioRegistro;
use App\SubEquipo;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;


class DashboardController extends Controller
{
    private function getPendings($filtro,$formType,$status='P',$filterExtra='',$equipos=true,$pluck=''){
        $userFilter='WHERE '.$filtro;
        
        if(current_user()->crm_clientes_id){
              $userFilter='WHERE cliente_id in ('.limpiar_lista(current_user()->crm_clientes_id).') and '.$filtro;
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


    public function index(Request $request){

        /////////FILTRO CLIENTE GMP/////////////
        $data['tipo']='gmp';
        $filtro['gmp']="equipos.numero_parte like 'GM%'";
        $filtro['cliente']="equipos.numero_parte not like 'GM%'";
        if(current_user()->isCliente())
            $data['tipo']='cliente';
     
        if($request->has('tipo') and !empty($request->tipo) and in_array($request->tipo,['gmp','cliente'])){
            $data['tipo']=$request->tipo;
        }
        $filtro=$filtro[$data['tipo']];
        ///////////// EQUIPOS ///////////////////////
        /// FILTRO SOLO LAS ELECTRICAS POR LOS DE COMBUSTION NO TIENEN SUB TIPO
        $equipos =  Equipo::FiltroCliente()->whereRaw($filtro)->get();
        
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
            $data['daily_check']=$this->getPendings($filtro,'daily_check');
        }

        //mantenimientos preventivos pendientes de firma supervisor
        $data['mant_prev']=$this->getPendings($filtro,'mant_prev');
        //servicio tecnico PENDIENTES
        $data['serv_tec_p']=$this->getPendings($filtro,'serv_tec');
        //servicio tecnico PENDIENTES DE INICIAR
        $data['serv_tec_pi_a']=$this->getPendings($filtro,'serv_tec','A');
        //servicio tecnico ABIERTAS
       if(current_user()->isOnGroup('programador') or current_user()->isOnGroup('administrador'))
            $cond1='';
        else
            $cond1=' formulario_registro.tecnico_asignado='.current_user()->id;
        $data['serv_tec_a']=$this->getPendings($filtro,'serv_tec','A',$cond1);
        //servicio tecnico EN PROCESO
        if(current_user()->isOnGroup('programador') or current_user()->isOnGroup('administrador'))
            $cond2='';
        else
            $cond2=' formulario_registro.tecnico_asignado='.current_user()->id;
        $data['serv_tec_pr']=$this->getPendings($filtro,'serv_tec','PR',$cond2);
         //servicio tecnico EN PROCESO
         $data['serv_tec_10']=array();
         $desde = \Carbon\Carbon::now()->subDays(45)->format('Y-m-d'); //filtro reportes cerrados 45 dias
         $filtroExtra="(formulario_registro.estatus='C' and formulario_registro.created_at >='$desde' or formulario_registro.estatus<>'C')";
         if(current_user()->isOnGroup('administrador') or current_user()->isOnGroup('programador') or current_user()->isSupervisor())
            $data['serv_tec_10']=$this->getPendings($filtro,'serv_tec','C',$filtroExtra,false,'');

        if(current_user()->isOnGroup('supervisorc'))
            $data['serv_tec_10']=$this->getPendings($filtro,'serv_tec','C',$filtroExtra,true,'');

        if(current_user()->isOnGroup('administrador') or  current_user()->isOnGroup('programador')){
            $dailyCheck =  $this->getPendings($filtro,'daily_check','', "date_format(formulario_registro.created_at,'%Y-%m-%d') ='".Carbon::now()->format('Y-m-d')."'",false,true);

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

        return view('frontend.inicio',compact('data'));
    }

    function grafica(Request $request,$id){

        //clientes
        $clientes=current_user()->crm_clientes_id;
        $clientes_lista=explode(',',$clientes);
        foreach($clientes_lista as $k=>$c){
            if(empty($c))
                unset($clientes_lista[$k]);
        }
        $clientes=implode(',',$clientes_lista);
        //filtro
        $filtro='';$view='frontend.dashboard.gmp';
        if(in_array($id,['gmp','cliente'])){
            if($id=='cliente'){
                $filtro.=" AND e.numero_parte NOT LIKE 'GM%'";
                $view='frontend.dashboard.cliente';
            }
            else{
                $filtro.=" AND e.numero_parte LIKE 'GM%'";
            }
        }
           
        if($request->has('cliente_id') and !empty($request->cliente_id))
            $filtro.="and fr.cliente_id='$request->cliente_id'".PHP_EOL;

        if($request->has('desde') and !empty($request->desde))
            $filtro.="and fr.created_at>='$request->desde'".PHP_EOL;

        if($request->has('hasta') and !empty($request->hasta))
            $filtro.="and fr.created_at<='$request->hasta'".PHP_EOL;

        if(!empty($clientes)){
            $filtro.="and fr.cliente_id in ($clientes)".PHP_EOL;
        }
        $max=0;

        ///////////////////////////////QUERY0//////////////////////////////////////////
        $query0="SELECT 
                'Total de equipos' AS nombre,
                SUM(CASE WHEN (e.numero_parte NOT LIKE 'GM%' AND cliente_id IS NOT NULL) THEN 1 ELSE 0 END) AS propios,
                SUM(CASE WHEN (e.numero_parte LIKE 'GM%' AND cliente_id IS NOT NULL) THEN 1 ELSE 0 END) AS alquilados
                FROM montacarga.equipos e";
        $res0=DB::connection('crm')->select(DB::Raw($query0));
       
        $data=array();
        foreach($res0 as $r){
            $data['chart0']['n'][]=$r->nombre;
            $data['chart0']['p'][]=$r->propios;
            $data['chart0']['a'][]=$r->alquilados;
        }
    ///////////////////////////////QUERY1//////////////////////////////////////////
        $query1="SELECT fr.cliente_id,c.nombre,COUNT(DISTINCT(fr.equipo_id)) AS reportados,
        SUM( CASE fr.equipo_status WHEN 'O' THEN 1 ELSE 0 END) AS operativos,
        SUM( CASE fr.equipo_status WHEN 'I' THEN 1 ELSE 0 END) AS inoperativos,
        SUM( CASE fr.repuesto_status WHEN 'E' THEN 1 ELSE 0 END) AS en_espera,
        SUM( CASE fr.repuesto_status WHEN 'L' THEN 1 ELSE 0 END) AS listo
        FROM formulario_registro fr
        LEFT JOIN clientes_vw c ON  fr.cliente_id=c.id, equipos_vw e ,
        (SELECT equipo_id,cliente_id,MAX(id) AS id FROM formulario_registro
        WHERE  formulario_id=10 AND deleted_at IS NULL
        GROUP BY equipo_id,cliente_id)X  
        WHERE   fr.equipo_id=e.id AND fr.id=X.id AND fr.cliente_id=X.cliente_id
        AND fr.deleted_at IS NULL
        $filtro
        GROUP BY fr.cliente_id,c.nombre
        ";
        $res1=DB::select(DB::Raw($query1));
        foreach($res1 as $r){
            $data['chart1']['n'][]=$r->nombre;
            $data['chart1']['r'][]=$r->reportados;
            $data['chart1']['o'][]=$r->operativos;
            $data['chart1']['i'][]=$r->inoperativos;

            $data['chart2']['n'][]=$r->nombre;
            $data['chart2']['e'][]=$r->en_espera;
            $data['chart2']['l'][]=$r->listo;

            $max++;
        }
        $data['max']=$max;
        $max=0;
         ///////////////////////////////QUERY2//////////////////////////////////////////
        $query2="SELECT fr.cliente_id,c.nombre,COUNT(DISTINCT(fr.equipo_id)) AS reportes,
        SUM( CASE fr.estatus WHEN 'P' THEN 1 ELSE 0 END) AS pendientes,
        SUM( CASE fr.estatus WHEN 'A' THEN 1 ELSE 0 END) AS asignados,
        SUM( CASE fr.estatus WHEN 'PR' THEN 1 ELSE 0 END) AS proceso,
        SUM( CASE fr.estatus WHEN 'C' THEN 1 ELSE 0 END) AS cerrado
        FROM formulario_registro fr
        LEFT JOIN clientes_vw c ON  fr.cliente_id=c.id, equipos_vw e 
        WHERE   fr.equipo_id=e.id 
        AND fr.deleted_at IS NULL
        AND fr.formulario_id=10 
        $filtro
        GROUP BY fr.cliente_id,c.nombre";
        $res2=DB::select(DB::Raw($query2));
        foreach($res2 as $r){
            $data['chart3']['n'][]=$r->nombre;
            $data['chart3']['r'][]=$r->reportes;
            $data['chart3']['p'][]=$r->pendientes;
            $data['chart3']['a'][]=$r->asignados;
            $data['chart3']['pr'][]=$r->proceso;
            $data['chart3']['c'][]=$r->cerrado;
            $max++;
        }

        $data['max']=max($max,$data['max']);
        $max=0;
         ///////////////////////////////QUERY3//////////////////////////////////////////
        $query3="SELECT fr.cliente_id,c.nombre,COUNT(DISTINCT(fr.equipo_id)) AS reportados,
                SUM( CASE fr.cotizacion WHEN 'A' THEN 1 ELSE 0 END) AS aprobada,
                SUM( CASE ifnull(fr.cotizacion,'N') when 'N' THEN 1 ELSE 0 END) AS no_apobada
                FROM formulario_registro fr
                LEFT JOIN clientes_vw c ON  fr.cliente_id=c.id, equipos_vw e ,
                (SELECT equipo_id,cliente_id,MAX(id) AS id FROM formulario_registro
                WHERE  formulario_id=10 AND deleted_at IS NULL
                GROUP BY equipo_id,cliente_id)X  
                WHERE   fr.equipo_id=e.id AND fr.id=X.id AND fr.cliente_id=X.cliente_id
                AND fr.deleted_at IS NULL
                $filtro
                GROUP BY fr.cliente_id,c.nombre";
        $res3=DB::select(DB::Raw($query3));

        foreach($res3 as $r){
            $data['chart4']['n'][]=$r->nombre;
            $data['chart4']['r'][]=$r->reportados;
            $data['chart4']['a'][]=$r->aprobada;
            $data['chart4']['x'][]=$r->no_apobada;
            $max++;
        } 
        $data['max']=max($max,$data['max']);
        $max=0;
        ///////////////////////////////QUERY4//////////////////////////////////////////
        $query4="SELECT fr.cliente_id,c.nombre,COUNT(*) AS reportes,
        SUM( CASE fr.estatus WHEN 'P' THEN 1 ELSE 0 END) AS pendientes,
        SUM( CASE fr.estatus WHEN 'C' THEN 1 ELSE 0 END) AS cerrado
        FROM formulario_registro fr
        LEFT JOIN clientes_vw c ON  fr.cliente_id=c.id, equipos_vw e ,formularios f
        WHERE   fr.equipo_id=e.id 
        AND fr.deleted_at IS NULL
        AND fr.formulario_id=f.id
        AND f.tipo='mant_prev'
        $filtro
        GROUP BY fr.cliente_id,c.nombre";
        $res4=DB::select(DB::Raw($query4));
        foreach($res4 as $r){
            $data['chart5']['n'][]=$r->nombre;
            $data['chart5']['r'][]=$r->reportes;
            $data['chart5']['p'][]=$r->pendientes;
            $data['chart5']['c'][]=$r->cerrado;
            $max++;    
        }

        $data['max']=max($max,$data['max']);
        $max=0;
        ///////////////////////////////QUERY5//////////////////////////////////////////
        $query5="SELECT fr.cliente_id,c.nombre,COUNT(*) AS reportes,
        SUM( case WHEN ( fr.accidente ='S' and  e.numero_parte NOT LIKE 'GM%') THEN 1 ELSE 0 END) AS propias,
        SUM( case WHEN ( fr.accidente ='S' and  e.numero_parte LIKE 'GM%') THEN 1 ELSE 0 END) AS alquiladas
        FROM formulario_registro fr
        LEFT JOIN clientes_vw c ON  fr.cliente_id=c.id, equipos_vw e 
        WHERE   fr.equipo_id=e.id 
        AND fr.deleted_at IS NULL
        AND fr.formulario_id=10
        $filtro
        GROUP BY fr.cliente_id,c.nombre";
        $res5=DB::select(DB::Raw($query5));
        foreach($res5 as $r){
            $data['chart6']['n'][]=$r->nombre;
            $data['chart6']['p'][]=$r->propias;
            $data['chart6']['a'][]=$r->alquiladas;
            $max++;    
        }

        $data['max']=max($max,$data['max']);
        $max=0;
        ///////////////////////////////QUERY6//////////////////////////////////////////
        $query6="SELECT CONCAT(u.first_name,' ',u.last_name) AS nombre,COUNT(*) AS total FROM formulario_registro fr , equipos_vw e ,users u,role_users ru
        WHERE fr.equipo_id = e.id AND fr.trabajado_por = u.id 
        AND u.id = ru.user_id 
        AND trabajado_por IS NOT NULL 
        AND fr.deleted_at IS NULL 
        AND ru.role_id =5 
        AND e.numero_parte LIKE 'GM%'
        $filtro
        GROUP BY CONCAT(u.first_name,' ',u.last_name) ";
        $res6=DB::select(DB::Raw($query6));
        foreach($res6 as $r){
            $data['chart7']['n'][]=$r->nombre;
            $data['chart7']['t'][]=$r->total;
            $max++;    
        }
dd($res6);
        $data['max']=max($max,$data['max']);
        $max=0;
        
        $data['indice'][0]=['p','a','n'];
        $data['indice'][1]=['r','o','i','n'];
        $data['indice'][2]=['e','l','n'];
        $data['indice'][3]=['p','a','pr','c','n'];
        $data['indice'][4]=['a','x','n'];
        $data['indice'][5]=['r','p','c','n'];
        $data['indice'][6]=['p','a','n'];
        $data['indice'][7]=['t','n'];

    
        $data['ejey'][0]='Equipos';
        $data['ejey'][1]='Equipos';
        $data['ejey'][2]='Equipos';
        $data['ejey'][3]='Reportes';
        $data['ejey'][4]='Reportes ST';
        $data['ejey'][5]='Reportes';
        $data['ejey'][6]='Reportes';
        $data['ejey'][7]='Reportes';

        $data['leyenda'][0]=['Propios','Alquilados'];
        $data['leyenda'][1]=['Reportados','Operativo','Inoperativo'];
        $data['leyenda'][2]=['En espera','Listos'];
        $data['leyenda'][3]=['Pendientes','Asignados','En Proceso','Cerrados'];
        $data['leyenda'][4]=['Aprobadas','No Aprobadas'];
        $data['leyenda'][5]=['Total','Pendientes','Cerrados'];
        $data['leyenda'][6]=['Propias','Alquiladas'];
        $data['leyenda'][7]=['Reportes'];

        $data['titulo'][0]='Total equipos';
        $data['titulo'][1]='Estatus equipos';
        $data['titulo'][2]='Estatus repuestos';
        $data['titulo'][3]='Estatus servicio tecnico';
        $data['titulo'][4]='Cotizaciones';
        $data['titulo'][5]='Estatus MTT Preventivo';
        $data['titulo'][6]='Reportes de accidentes';
        $data['titulo'][7]='Informes trabajados – Equipos';

        if(!empty($clientes)){
            $clientes=Cliente::whereRaw("(id in($clientes))")->orderBy('nombre')->get()->pluck('nombre','id');
        }else{
            $clientes_reportes=DB::Select(DB::Raw("SELECT cliente_id FROM formulario_registro 
            WHERE cliente_id IS NOT NULL AND deleted_at IS NULL
            GROUP BY cliente_id"));
            $clientes_reportes=collect($clientes_reportes)->pluck('cliente_id');
            $clientes=Cliente::whereIn('id',$clientes_reportes)->orderBy('nombre')->get()->pluck('nombre','id');
        }
        $clientes['']='Seleccione el cliente';
        
        return view($view,compact('data','clientes','id'));
    }
}
