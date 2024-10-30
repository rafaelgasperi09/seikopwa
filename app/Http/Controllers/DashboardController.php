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
    private function getPendings($filtro,$formType,$status='P',$filterExtra='',$equipos=true,$pluck='',$group_cliente=false){
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
       

       // $equipos=DB::connection('crm')->select(DB::raw('SELECT * FROM montacarga.equipos WHERE cliente_id in ('.$lista.')'));
       $r=FormularioRegistro::selectRaw('formulario_registro.*,equipos_vw.numero_parte')
        ->join('formularios','formulario_registro.formulario_id','formularios.id')
        ->join('equipos_vw','formulario_registro.equipo_id','equipos_vw.id')
         ->whereNotNull('equipo_id')
        ->where('formularios.tipo',$formType)
        ->whereRaw("(formulario_registro.estatus='C' and TIMESTAMPDIFF(DAY,formulario_registro.created_at,now())<=45 or formulario_registro.estatus<>'C')")
        ->When(!empty($status),function($q)use($status){
            $q->where('formulario_registro.estatus',$status);
        })
        ->When(!empty($userFilter),function($q)use($userFilter,$lista){
            $q->whereRaw(' equipo_id IN ('.$lista.')');      
        })
        ->When(!empty($filterExtra),function($q)use($filterExtra){
            $q->whereRaw($filterExtra);
        });


      /*if($formType=='serv_tec' and $status=='A')
      dd($r->get());*/

        if($group_cliente)
        {
            $clientes= clone $r;
            $clientes=$clientes->groupBy('cliente_id')->select('cliente_id')->get();
            return $clientes;
        }

        if(empty($pluck)){
            return  $r->orderBy('cliente_id','asc')->orderBy('created_at','desc')->get();

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
          $cond1=''; 
        if( current_user()->isOnGroup('supervisorc') or  
            current_user()->isOnGroup('supervisor-cliente') or  
            current_user()->isOnGroup('programador') ){
            //daily check pendientes de firma supervisor
            if(current_user()->isOnGroup('supervisorc')){
             $lista=DB::select(DB::Raw("SELECT fd.id FROM formulario_data fd,formulario_campos fc,
                                    formulario_registro fr 
                                    WHERE fd.formulario_campo_id=fc.id 
                                    AND fd.formulario_registro_id=fr.id
                                    AND fc.nombre='supervisor_id'
                                    AND fr.deleted_at IS NULL
                                    AND fr.estatus='P'
                                    AND fd.valor=".current_user()->id));
              $lista=collect($lista)->pluck('id')->toArray();
             
              $cond1="formulario_registro.id in (SELECT fr.id  FROM formulario_data fd,formulario_campos fc,
              formulario_registro fr 
              WHERE fd.formulario_campo_id=fc.id 
              AND fd.formulario_registro_id=fr.id
              AND fc.nombre='supervisor_id'
              AND fr.deleted_at IS NULL
              AND fr.estatus='P'
              AND fd.valor=".current_user()->id.")";
            }

            $data['daily_check']=$this->getPendings($filtro,'daily_check','P',$cond1);
          
            $data['g_daily_check']=$this->getPendings($filtro,'daily_check','P','',true,'',true);
        }

        //mantenimientos preventivos pendientes de firma supervisor
        $data['mant_prev']=$this->getPendings($filtro,'mant_prev','P','');
        $data['g_mant_prev']=$this->getPendings($filtro,'mant_prev','P','',true,'',true);
        //servicio tecnico PENDIENTES
       
        $data['g_serv_tec_p']=$this->getPendings($filtro,'serv_tec','P','',true,'',true);
        $data['serv_tec_p']=$this->getPendings($filtro,'serv_tec');
        //servicio tecnico PENDIENTES DE INICIAR
        $data['serv_tec_pi_a']=$this->getPendings($filtro,'serv_tec','A');
        $data['g_serv_tec_pi_a']=$this->getPendings($filtro,'serv_tec','A','',true,'',true);
        //servicio tecnico ABIERTAS
       if(current_user()->isOnGroup('programador') or current_user()->isOnGroup('administrador'))
           $cond1=''; 
        elseif(current_user()->isOnGroup('tecnico'))
            $cond1=' formulario_registro.tecnico_asignado='.current_user()->id;
        else
            $cond1=' formulario_registro.tecnico_asignado is null';
        $data['serv_tec_p']=$this->getPendings($filtro,'serv_tec','P',$cond1);      
        $data['g_serv_tec_p']=$this->getPendings($filtro,'serv_tec','P',$cond1,true,'',true);

        $data['serv_tec_a']=$this->getPendings($filtro,'serv_tec','A',$cond1);  
        
        $data['g_serv_tec_a']=$this->getPendings($filtro,'serv_tec','A',$cond1,true,'',true);
        //servicio tecnico EN PROCESO
        
            $cond2='';
        if(current_user()->isOnGroup('tecnico'))
            $cond2=' formulario_registro.tecnico_asignado='.current_user()->id;

        $data['serv_tec_pr']=$this->getPendings($filtro,'serv_tec','PR',$cond2);
        $data['serv_tec_pr_cli']=$this->getPendings($filtro,'serv_tec','PR','');

        if(!empty($cond2)){$cond2.=' and';}
        $cond3=" equipo_status='O'";
        $data['g_serv_tec_pr_o_cli']=$this->getPendings($filtro,'serv_tec','PR',$cond3,true,'',true);
        $cond3=" equipo_status='I'";
        $data['g_serv_tec_pr_i_cli']=$this->getPendings($filtro,'serv_tec','PR',$cond3,true,'',true);

        $cond3=$cond2.$cond3;
        $data['g_serv_tec_pr_o']=$this->getPendings($filtro,'serv_tec','PR',$cond3,true,'',true);

        $cond3=$cond2." equipo_status='I'";
        $data['g_serv_tec_pr_i']=$this->getPendings($filtro,'serv_tec','PR',$cond3,true,'',true);
        dd($data['g_serv_tec_pr_o'] );
         //servicio tecnico EN PROCESO
         $data['serv_tec_10']=array();
         $desde = \Carbon\Carbon::now()->subDays(45)->format('Y-m-d'); //filtro reportes cerrados 45 dias
         $filtroExtra="(formulario_registro.estatus='C' and formulario_registro.created_at >='$desde' or formulario_registro.estatus<>'C')";
       
         if(current_user()->isOnGroup('administrador') or current_user()->isOnGroup('programador') or current_user()->isSupervisor()){
            $data['serv_tec_10']=$this->getPendings($filtro,'serv_tec','C',$filtroExtra,false,'');
            $data['g_serv_tec_10']=$this->getPendings($filtro,'serv_tec','C',$filtroExtra,false,'',true);
         }
    

        if(current_user()->isSupervisor('cliente')){
             $data['serv_tec_10']=$this->getPendings($filtro,'serv_tec','C',$filtroExtra,true,'');
             $data['g_serv_tec_10']=$this->getPendings($filtro,'serv_tec','C',$filtroExtra,true,'',true);
        }
           

        if(current_user()->isOnGroup('administrador') or  current_user()->isOnGroup('programador')){
            $dailyCheck =  $this->getPendings($filtro,'daily_check','', "date_format(formulario_registro.created_at,'%Y-%m-%d') ='".Carbon::now()->format('Y-m-d')."'",false,true);
            $data['g_global_sin_daily_check_hoy'] =  $this->getPendings($filtro,'daily_check','', "date_format(formulario_registro.created_at,'%Y-%m-%d') ='".Carbon::now()->format('Y-m-d')."'",false,true);

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
        $clientes=clientes_string($clientes);
        $inoperativos=0;$operativos=0;$totales=0;
      
        //filtro
        $filtro='';$filtro0='';$view='frontend.dashboard.gmp';
       
        if(in_array($id,['gmp','cliente'])){
            if($id=='cliente'){
                
                $view='frontend.dashboard.cliente';
            }
            
        }
           
        if(!empty($request->cliente_id)){
            $filtro0="and e.cliente_id =$request->cliente_id".PHP_EOL;
            if($request->has('cliente_id'))
            $filtro.="and fr.cliente_id='$request->cliente_id'".PHP_EOL;
        }
         //filtra propios y gmp
         $tipo='';
         if($request->has('tipo') and !empty($request->tipo))
             $tipo=$request->tipo;
         if( $tipo=='cliente'){
            $filtro.=" AND e.numero_parte NOT LIKE 'GM%'";
            $filtro0.=" AND e.numero_parte NOT LIKE 'GM%'";
         }
         if( $tipo=='alquiler'){
            $filtro.=" AND e.numero_parte LIKE 'GM%'";
            $filtro0.=" AND e.numero_parte LIKE 'GM%'";
         }
              
     
        $fdesde=date('Ym01');
        $fhasta=date('Ymt');
        if($request->has('desde') and !empty($request->desde)){
            $filtro.="and fr.created_at>='$request->desde'".PHP_EOL;
            $fdesde=\Carbon\Carbon::parse($request->desde)->format('Ym01');
            $fhasta=\Carbon\Carbon::parse($request->desde)->format('Ymt');

            $fdesde=\Carbon\Carbon::parse($request->desde)->format('Ymd');
        }

        if($request->has('hasta') and !empty($request->hasta)){
            $filtro.="and fr.created_at<='$request->hasta'".PHP_EOL;
            $hasta1=\Carbon\Carbon::parse($request->desde)->format('Y-m');
            $hasta2=\Carbon\Carbon::parse($request->hasta)->format('Y-m');
            if($hasta1==$hasta2)
                $fhasta=\Carbon\Carbon::parse($request->hasta)->format('Ymd');

            $fhasta=\Carbon\Carbon::parse($request->hasta)->format('Ymd');
        }
           

        if(!empty($clientes)){
            $filtro.="and fr.cliente_id in ($clientes)".PHP_EOL;
            $filtro0.="and e.cliente_id in ($clientes)".PHP_EOL;
        }
        $max=0;

        ///////////////////////////////QUERY0//////////////////////////////////////////
        $cond='';

        if($request->has('grafica') and $request->grafica=='chart0'){
            $textos=['propio','alquilado'];
            if(current_user()->isCliente()){
                $cond="and e.cliente_id in ($clientes)";
            }else{
                $cond= $filtro0;
                $textos=['Cliente','GMP'];
            }
            $query0="select * from (SELECT e.numero_parte,
            (CASE WHEN (e.numero_parte NOT LIKE 'GM%') THEN '$textos[0]' ELSE '$textos[1]' END) AS tipo
                        FROM equipos e
                        WHERE e.deleted_at IS NULL $cond  $filtro0
                        ORDER BY e.numero_parte)X order by X.tipo desc,X.numero_parte";

            $res0=DB::connection('crm')->select(DB::Raw($query0));
            $tabla=to_table($res0);
            return $tabla;
        }
        

        if(current_user()->isCliente()){
            if($id<>'cliente'){
                return redirect(route('dashboard.gmp','cliente'));
            }  

            $query0="SELECT 
            'Total de equipos' AS nombre,
            IFNULL(SUM(CASE WHEN (e.numero_parte NOT LIKE 'GM%') THEN 1 ELSE 0 END),0) AS propios,
            IFNULL(SUM(CASE WHEN (e.numero_parte LIKE 'GM%') THEN 1 ELSE 0 END),0) AS alquilados
            FROM equipos e
            where e.deleted_at is null and e.cliente_id in ($clientes) $filtro0";

        }else{
            $query0="SELECT 
            'Total de equipos' AS nombre,
            IFNULL(SUM(CASE WHEN (e.numero_parte NOT LIKE 'GM%') THEN 1 ELSE 0 END),0) AS propios,
            IFNULL(SUM(CASE WHEN (e.numero_parte LIKE 'GM%') THEN 1 ELSE 0 END),0) AS alquilados
            FROM equipos e
            where e.deleted_at is null  $filtro0 
             ";
        }

        $res0=DB::connection('crm')->select(DB::Raw($query0));

        $data=array();
        foreach($res0 as $r){
            $data['chart0']['n'][]=$r->nombre;
            $data['chart0']['p'][]=$r->propios;
            $data['chart0']['a'][]=$r->alquilados;
            $totales+=$r->propios+$r->alquilados;
        }

    ///////////////////////////////QUERY1//////////////////////////////////////////
        if($request->has('grafica') and in_array($request->grafica,['chart1','chart2'])){
   
            $query1="SELECT c.nombre,e.numero_parte as equipo,
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
                    GROUP BY c.nombre,e.numero_parte";
                
            $res1=DB::select(DB::Raw($query1));

            $tabla=to_table($res1);

            return $tabla;
        }
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
            $inoperativos+=$r->inoperativos;
        }
        $operativos=$totales-$inoperativos;

        $data['max']=$max;
        $max=0;
         ///////////////////////////////QUERY2//////////////////////////////////////////
         if($request->has('grafica') and $request->grafica=='chart3'){
   
            $query2="SELECT c.nombre,e.numero_parte as equipo   ,COUNT(DISTINCT(fr.equipo_id)) AS reportes,
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
                    GROUP BY c.nombre,e.numero_parte";
                        
            $res2=DB::select(DB::Raw($query2));

            $tabla=to_table($res2);

            return $tabla;
        }
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
         if($request->has('grafica') and $request->grafica=='chart4'){
   
            $query3="SELECT c.nombre,e.numero_parte,COUNT(DISTINCT(fr.equipo_id)) AS reportados,
                    SUM( CASE fr.cotizacion WHEN 'A' THEN 1 ELSE 0 END) AS aprobada,
                    SUM( CASE IFNULL(fr.cotizacion,'N') WHEN 'N' THEN 1 ELSE 0 END) AS no_apobada
                    FROM formulario_registro fr
                    LEFT JOIN clientes_vw c ON  fr.cliente_id=c.id, equipos_vw e ,
                    (SELECT equipo_id,cliente_id,MAX(id) AS id FROM formulario_registro
                    WHERE  formulario_id=10 AND deleted_at IS NULL
                    GROUP BY equipo_id,cliente_id)X  
                    WHERE   fr.equipo_id=e.id AND fr.id=X.id AND fr.cliente_id=X.cliente_id
                    AND fr.deleted_at IS NULL
                    $filtro
                    GROUP BY c.nombre,e.numero_parte";
                        
            $res3=DB::select(DB::Raw($query3));

            $tabla=to_table($res3);

            return $tabla;
        }
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
        if($request->has('grafica') and $request->grafica=='chart5'){
   
            $query4="SELECT c.nombre,e.numero_parte,COUNT(*) AS reportes,
                    SUM( CASE fr.estatus WHEN 'P' THEN 1 ELSE 0 END) AS pendientes,
                    SUM( CASE fr.estatus WHEN 'C' THEN 1 ELSE 0 END) AS cerrado
                    FROM formulario_registro fr
                    LEFT JOIN clientes_vw c ON  fr.cliente_id=c.id, equipos_vw e ,formularios f
                    WHERE   fr.equipo_id=e.id 
                    AND fr.deleted_at IS NULL
                    AND fr.formulario_id=f.id
                    AND f.tipo='mant_prev'
                    $filtro
                    GROUP BY c.nombre,e.numero_parte";
                        
            $res4=DB::select(DB::Raw($query4));

            $tabla=to_table($res4);

            return $tabla;
        }
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
        if($request->has('grafica') and $request->grafica=='chart6'){
   
            $query5="SELECT c.nombre,e.numero_parte,COUNT(*) AS reportes,
                            SUM( case WHEN ( fr.accidente ='S' and  e.numero_parte NOT LIKE 'GM%') THEN 1 ELSE 0 END) AS propias,
                            SUM( case WHEN ( fr.accidente ='S' and  e.numero_parte LIKE 'GM%') THEN 1 ELSE 0 END) AS alquiladas
                            FROM formulario_registro fr
                            LEFT JOIN clientes_vw c ON  fr.cliente_id=c.id, equipos_vw e 
                            WHERE   fr.equipo_id=e.id 
                            AND fr.deleted_at IS NULL
                            AND fr.formulario_id=10
                            $filtro
                            GROUP BY c.nombre,e.numero_parte";
                        
            $res5=DB::select(DB::Raw($query5));

            $tabla=to_table($res5);

            return $tabla;
        }
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
        if($request->has('grafica') and $request->grafica=='chart7'){
   
            $query6="SELECT CONCAT(u.first_name,' ',u.last_name) AS nombre,
                        e.numero_parte as equipo,
                        COUNT(*) AS total 
                        FROM formulario_registro fr , equipos_vw e ,users u,role_users ru
                        WHERE fr.equipo_id = e.id AND fr.trabajado_por = u.id 
                        AND u.id = ru.user_id 
                        AND trabajado_por IS NOT NULL 
                        AND fr.deleted_at IS NULL 
                        AND ru.role_id =5 
                        $filtro
                        GROUP BY e.numero_parte,CONCAT(u.first_name,' ',u.last_name)";
                        
            $res6=DB::select(DB::Raw($query6));

            $tabla=to_table($res6);

            return $tabla;
        }
        $query6="SELECT CONCAT(u.first_name,' ',u.last_name) AS nombre,COUNT(*) AS total 
        FROM formulario_registro fr , equipos_vw e ,users u,role_users ru
        WHERE fr.equipo_id = e.id AND fr.trabajado_por = u.id 
        AND u.id = ru.user_id 
        AND trabajado_por IS NOT NULL 
        AND fr.deleted_at IS NULL 
        AND ru.role_id =5 
        $filtro
        GROUP BY CONCAT(u.first_name,' ',u.last_name)";
 
        $res6=DB::select(DB::Raw($query6));
        foreach($res6 as $r){
            $data['chart7']['n'][]=$r->nombre;
            $data['chart7']['t'][]=$r->total;
            $max++;    
        }

        $data['max']=max($max,$data['max']);
        $max=0;
        ///////////////////////////////QUERY7//////////////////////////////////////////
        if($request->has('grafica') and $request->grafica=='chart8'){
   
            $query7="SELECT  CONCAT(u.first_name,' ',u.last_name) AS nombre,e.numero_parte,COUNT(*) AS total 
                    FROM formulario_registro fr , equipos_vw e ,users u,role_users ru
                    WHERE fr.equipo_id = e.id AND fr.trabajado_por = u.id 
                    AND u.id = ru.user_id 
                    AND trabajado_por IS NOT NULL 
                    AND fr.deleted_at IS NULL 
                    AND ru.role_id =11 
                    $filtro
                    GROUP BY  CONCAT(u.first_name,' ',u.last_name),e.numero_parte ";
                        
            $res7=DB::select(DB::Raw($query7));

            $tabla=to_table($res7);

            return $tabla;
        }
        $query7="SELECT CONCAT(u.first_name,' ',u.last_name) AS nombre,COUNT(*) AS total 
        FROM formulario_registro fr , equipos_vw e ,users u,role_users ru
        WHERE fr.equipo_id = e.id AND fr.trabajado_por = u.id 
        AND u.id = ru.user_id 
        AND trabajado_por IS NOT NULL 
        AND fr.deleted_at IS NULL 
        AND ru.role_id =11 
        $filtro
        GROUP BY CONCAT(u.first_name,' ',u.last_name)";
 
        $res7=DB::select(DB::Raw($query7));
        foreach($res7 as $r){
            $data['chart8']['n'][]=$r->nombre;
            $data['chart8']['t'][]=$r->total;
            $max++;    
        }

        $data['max']=max($max,$data['max']);
        $max=0;
        ///////////////////////////////QUERY7//////////////////////////////////////////
        $filtro2=str_replace('e.','e3.',$filtro);
        $filtro2=str_replace('fr.','fr2.',$filtro2);
        if($request->has('grafica') and $request->grafica=='chart9'){
   
            $query8="SELECT e.numero_parte,
                    SUM( CASE WHEN fr.estatus='P' THEN 1 ELSE 0 END) AS pendientes,
                    SUM( CASE WHEN fr.turno_chequeo_diario=1 THEN 1 ELSE 0 END) AS turno1,
                    SUM( CASE WHEN fr.turno_chequeo_diario=1 THEN 2 ELSE 0 END) AS turno2,
                    SUM( CASE WHEN fr.turno_chequeo_diario=1 THEN 3 ELSE 0 END) AS turno3
                    FROM equipos_vw e LEFT JOIN formulario_registro fr ON fr.equipo_id=e.id
                    LEFT JOIN clientes_vw c ON  fr.cliente_id=c.id
                    WHERE fr.formulario_id=2
                    AND fr.deleted_at IS NULL
                    $filtro
                    GROUP BY e.numero_parte ";
                        
            $res8=DB::select(DB::Raw($query8));

            $tabla=to_table($res8);

            return $tabla;
        }
        $query8="SELECT c.nombre, 
           SUM( CASE WHEN fr.estatus='P' THEN 1 ELSE 0 END) AS pendientes,
           SUM( CASE WHEN fr.turno_chequeo_diario=1 THEN 1 ELSE 0 END) AS turno1,
           SUM( CASE WHEN fr.turno_chequeo_diario=1 THEN 2 ELSE 0 END) AS turno2,
           SUM( CASE WHEN fr.turno_chequeo_diario=1 THEN 3 ELSE 0 END) AS turno3
           FROM equipos_vw e LEFT JOIN formulario_registro fr ON fr.equipo_id=e.id
           LEFT JOIN clientes_vw c ON  fr.cliente_id=c.id
           WHERE fr.formulario_id=2
            AND fr.deleted_at IS NULL
            $filtro
           GROUP BY c.nombre";
        
        $res8=DB::select(DB::Raw($query8));
        foreach($res8 as $r){
            $data['chart9']['n'][]=$r->nombre;
            $data['chart9']['p'][]=$r->pendientes;
            $data['chart9']['a'][]=$r->turno1;
            $data['chart9']['b'][]=$r->turno2;
            $data['chart9']['c'][]=$r->turno3;
            $max++;    
        }
        

        $query9="SELECT COUNT(*) AS total FROM equipos e
         WHERE e.deleted_at is null  $filtro0";
        $res9=DB::connection('crm')->select(DB::Raw($query9));
        $res9=end($res9);
 

        //PROBAR QUERY
        $query10="		SELECT DATE_FORMAT(fr.created_at,'%d-%b')  AS fecha,COUNT(*) AS total 
        FROM formulario_registro fr,formularios f,equipos_vw e
        WHERE fr.`formulario_id`=f.`id`
        AND fr.equipo_id=e.id
        AND f.`tipo`='daily_check'
        $filtro
        AND DATE_FORMAT(fr.created_at,'%Y%m%d') between '$fdesde'  and '$fhasta'
        GROUP BY DATE_FORMAT(fr.created_at,'%d-%b')";
        $res10=DB::select(DB::Raw($query10));
       
        $res10=collect($res10)->pluck('total','fecha')->toArray();
       
        $listado=array();
        $total=$res9->total;
        $fecha1=Carbon::parse($fdesde);
        $fecha2=Carbon::parse($fhasta);
        $dif= $fecha2->diffInDays($fecha1); 
        $ffecha=$fecha1;
        for($i=0;$i<=$dif;$i++){
            $fechabase=(string) $i;
            $ffecha=\Carbon\Carbon::parse($ffecha)->format('d-M');
            $data['chart10']['n'][]=$ffecha;        
            if(isset($res10[$ffecha])){
                $data['chart10']['p'][]=$total-$res10[$ffecha];
                $data['chart10']['r'][]=$res10[$ffecha];

            }else{
                $data['chart10']['p'][]=$total;
                $data['chart10']['r'][]=0;

            }
            $ffecha=\Carbon\Carbon::parse($ffecha)->addDays(1)->format('d-M');
        }
 
        $data['op']=$operativos;
        $data['in']=$inoperativos;
        $data['number1pct']=round(100*$operativos/($operativos+$inoperativos),2);
        
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
        $data['indice'][8]=['t','n'];
        $data['indice'][9]=['p','a','b','c','n'];
        $data['indice'][10]=['p','r','n'];

    
        $data['ejey'][0]='Equipos';
        $data['ejey'][1]='Equipos';
        $data['ejey'][2]='Equipos';
        $data['ejey'][3]='Reportes';
        $data['ejey'][4]='Reportes ST';
        $data['ejey'][5]='Reportes';
        $data['ejey'][6]='Reportes';
        $data['ejey'][7]='Reportes';
        $data['ejey'][8]='Reportes';
        $data['ejey'][9]='Reportes';
        $data['ejey'][10]='Reportes';

        $data['leyenda'][0]=['Alquilados','Propios'];
        $data['leyenda'][1]=['Reportados','Operativo','Inoperativo'];
        $data['leyenda'][2]=['En espera','Listos'];
        $data['leyenda'][3]=['Pendientes','Asignados','En Proceso','Cerrados'];
        $data['leyenda'][4]=['Aprobadas','No Aprobadas'];
        $data['leyenda'][5]=['Total','Pendientes','Cerrados'];
        $data['leyenda'][6]=['Propias','Alquiladas'];
        $data['leyenda'][7]=['Reportes'];
        $data['leyenda'][8]=['Reportes'];
        $data['leyenda'][9]=['Pendientes','1er Turno','2do Turno','3er Turno'];
        $data['leyenda'][10]=['Pendientes','Realizados'];

        $data['titulo'][0]='Total equipos';
        $data['titulo'][1]='Estatus equipos';
        $data['titulo'][2]='Estatus repuestos';
        $data['titulo'][3]='Estatus servicio tecnico';
        $data['titulo'][4]='Cotizaciones';
        $data['titulo'][5]='Estatus MTT Preventivo';
        $data['titulo'][6]='Reportes de accidentes';
        $data['titulo'][7]='Informes trabajados – Equipos';
        $data['titulo'][8]='Informes trabajados – Repuestos';
        $data['titulo'][9]='Resumen de Daily Check';
        $data['titulo'][10]='Daily Check realizados por día';
      
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

    
    public function grafico_detalle(Request $request,$id){
        $lista=array(
            'chart0','chart1','chart2',
            'chart3','chart4','chart5',
            'chart6','chart7','chart8',
            'chart9',
        ); 
        if($request->has('grafica') and in_array($request->grafica,$lista)){

            return $this->grafica($request,$id);
        }
    }
}
