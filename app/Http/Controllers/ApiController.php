<?php

namespace App\Http\Controllers;

use App\Componente;
use App\Equipo;
use App\Cliente;
use App\Formulario;
use App\FormularioRegistro;
use App\User;
use App\SubEquipo;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use DateTime;

class ApiController extends Controller
{
    public function data_inicio(Request $request){
        $dashboard=app('App\Http\Controllers\DashboardController');
        $data['tipo']='gmp';
        $filtro['gmp']="equipos.numero_parte like 'GM%'";
        $filtro['cliente']="equipos.numero_parte not like 'GM%'";
        if(current_user()->isCliente())
            $data['tipo']='cliente';
        $filtro=$filtro[$data['tipo']];

        $data=array();
        $user=User::find($request->user_id);
        $result=''; 
        //////////////////////////////////////////////////////////////////////////////////////////
        if($request->tag=='total_equipo'){
            ///////////// TOTAL EQUIPOS POR TIPO //////////////
            $tipoEquiposArray=array();
            $totales=0;
            $totales_title='Total Equipos';
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

            foreach($data['total_equipos'] as $sub_equipos){
                foreach($sub_equipos['tipos'] as $tipo){
                     $totales+=$tipo['total'];  
                    $result.='<a href='.route('equipos.tipo',array($sub_equipos['name'],$tipo['tipo_id'])) .'" class="chip chip-media ml-05 mb-05" style="width:100%">';
                        if(false){
                            $result.='<i class="chip-icon bg-primary">'.$tipo['total'].'</i>';
                        }
                        else{
                            $result.=' <i class="chip-center chip-icon bg-primary">
                                            '. $tipo['total'] .'
                                        </i>
                                        <i class="chip-icon bg-warning" title="GMP">
                                            '. $tipo['GMtotal'] .'
                                        </i>';
                        }

                        $result.='<span class="chip-label">'. $tipo['tipo'] .'</span></a>';
                }
            }
            ////////////// TOTAL BATERIAS ////////////////////////////////
            if(\Sentinel::hasAccess('baterias.index')){
                $data['total_baterias'] =Componente::FiltroBodega()->whereTipoComponenteId(2)->count();
                $result.='<a href="'. route('baterias.index') .'"
                        class="chip chip-media ml-05 mb-05" style="width:100%">
                            <i class="chip-icon bg-success">
                                '. $data['total_baterias'] .'
                            </i>
                            <span class="chip-label">Baterías</span>
                        </a>';
        
                $totales+=$data['total_baterias'];
                $totales_title='Total Equipos + Baterías';

            }


            $result.="<script>
            $('#tot_equipos').html($totales);
            $('#tot_title').html('$totales_title');
           </script>";
        }
       //////////////////////////////////////////////////////////////////////////////////////////
       if($request->tag=='equipos_pendientes_daily_check'){
            $data['equipos_sin_daily_check_hoy'] = array();
            $equipos =  Equipo::FiltroCliente()->whereRaw($filtro)->get();
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
            $result.='';
            if(count($data['equipos_sin_daily_check_hoy'])){
                foreach($data['equipos_sin_daily_check_hoy'] as $id=>$equipo){
                     $result.='<a href="'.route('equipos.detail',array('id'=>$id)).'?show=rows&tab=1" class="chip chip-warning chip-media ml-05 mb-05" >
                        <span class="chip-label">'.$equipo.'</span>
                    </a>';
                }
            }
       }
       if($request->tag=='mant_prev_pend_firma'){
            //mantenimientos preventivos pendientes de firma supervisor
            $data['mant_prev']=$dashboard->getPendings($filtro,'mant_prev','P','');
            $data['g_mant_prev']=$dashboard->getPendings($filtro,'mant_prev','P','',true,'',true);
            $totalpf=count($data['mant_prev']);
            if($totalpf){
                foreach($data['g_mant_prev'] as $k=>$gmp ){
                    $result.='<div class="chip chip-warning chip-media ml-05 mb-05" style="width:100%;margin-top:15px !important;font-size:16px">
                                <span class="chip-label">
                                '.$gmp->cliente()->nombre.' 
                                </span>
                                <i class="chip-icon abrir"  id="gmp'.$gmp->cliente_id.'" >
                                <span class=" pull-right flechagmp flechagmp'.$gmp->cliente_id.'"title="Ver mas">';
                    if($k==0)
                     $result.='<ion-icon name="chevron-down-outline"></ion-icon></span>';
                    else
                     $result.='<ion-icon name="chevron-up-outline"></ion-icon></span>';

                                 $result.='</i>
                            </div>';
                    foreach($data['mant_prev']->where('cliente_id',$gmp->cliente_id) as $mp){
                     $result.='<a href="'.route('equipos.detail',array('id'=>$mp->equipo_id)) .'?show=rows&tab=2"  class="chip chip-warning chip-media ml-05 mb-05 gmplist gmp'.$gmp->cliente_id.'" style="width:98%;';
                        if($k!=0 ) 
                            $result.='display:none;';
                        $result.='"><i class="chip-icon">
                            Ir
                        </i>
                        <span class="chip-label">'.$mp->equipo()->numero_parte.' </span>
                        <span class="fecha pull-right" title="Fecha de creacion">'.transletaDate($mp->created_at,true,'').'</span>
                    </a>';
                    }
                }
                    
                
            }
            $result.="<script>
            $('#tot_equipos_pf').html($totalpf);

           </script>";
       }
       if($request->tag=='daily_check_pend_firma'){
        $cond1=''; 
        if( current_user()->isOnGroup('supervisorc') or  
            current_user()->isOnGroup('supervisor-cliente') or  
            current_user()->isOnGroup('programador') ){
            //daily check pendientes de firma supervisor
            
             
              $cond1="formulario_registro.id in (SELECT fr.id  FROM formulario_data fd,formulario_campos fc,
              formulario_registro fr 
              WHERE fd.formulario_campo_id=fc.id 
              AND fd.formulario_registro_id=fr.id
              AND fc.nombre='supervisor_id'
              AND fr.deleted_at IS NULL
              AND fr.estatus='P'
              AND fd.valor=".current_user()->id.")";
            }

            $cond1='';
            $mes=\Carbon\Carbon::now()->subMonth(1)->format('Y-m-d');
            if(!empty($cond1))
                $cond1.=' AND ';
            $cond1.=" date_format(formulario_registro.created_at,'%Y-%m-%d')>='$mes'"; 

            $data['daily_check']=$dashboard->getPendings($filtro,'daily_check','P',$cond1);
            $data['g_daily_check']=$dashboard->getPendings($filtro,'daily_check','P',$cond1,true,'',true);
            $totales=count($data['daily_check']);
            if($totales){
                foreach($data['g_daily_check'] as $k=>$gdc ){
                     $result.='<div class="chip chip-warning chip-media ml-05 mb-05" style="width:100%;margin-top:15px !important;font-size:16px">
                        <span class="chip-label ">'.$gdc->cliente()->nombre.' </span>
                        <i class="chip-icon abrirgdc"  id="gdc'.$gdc->cliente_id.'" >
                            <span class=" pull-right flechagdc flechagdc'.$gdc->cliente_id.'"title="Ver mas">';
                                if($k==0)
                                $result.='<ion-icon name="chevron-down-outline"></ion-icon></span>';
                                else
                                $result.='<ion-icon name="chevron-up-outline"></ion-icon></span>';
                    $result.='</i></div>';

                    foreach($data['daily_check']->where('cliente_id',$gdc->cliente_id) as $dc){
                        if($dc->equipo()){
                        
                         if(current_user()->isOnGroup('supervisorc') )
                            $result.='<a href="'.route('equipos.edit_daily_check',array('id'=>$dc->id)).'?show=rows&tab=1" ';
                        else $result.='<a href="'.route('equipos.detail',array('id'=>$dc->equipo_id)) .'?show=rows&tab=1" '; 
                        $display='';
                        if($k!=0)
                            $display='display:none;';
                        $result.='class="chip chip-warning chip-media ml-05 mb-05 gdclist gdc'.$gdc->cliente_id.'" style="width:98%;'.$display.'">
                            <i class="chip-icon">
                                Ir
                            </i>
                            <span class="chip-label">'.$dc->equipo()->numero_parte.' - turno '.$dc->turno_chequeo_diario.'</span>
                            <span class="fecha pull-right" title="Fecha de creacion">'.transletaDate($dc->created_at,true,'').'</span>
                        </a>';
                        }
                    }
                }
            }
            $result.="<script>
            $('#tot_equipos_dcpf').html($totales);

           </script>";
       }
       if($request->tag=='soporte_pend_iniciar'){
        //servicio tecnico PENDIENTES DE INICIAR
        $data['serv_tec_pi_a']=$dashboard->getPendings($filtro,'serv_tec','A');
        $data['g_serv_tec_pi_a']=$dashboard->getPendings($filtro,'serv_tec','A','',true,'',true);
            if(count($data['serv_tec_pi_a'])){
                foreach($data['g_serv_tec_pi_a'] as $k=>$gsta){
                    foreach($data['g_serv_tec_pi_a'] as $k=>$gsta){
                        $result.='<div class="chip chip-danger chip-media ml-05 mb-05" style="width:100%;margin-top:15px !important;font-size:16px">
                            <span class="chip-label ">'.$gsta->cliente()->nombre.' </span>
                            <i class="chip-icon abrirsta"  id="sta'.$gsta->cliente_id.'" >
                                <span class=" pull-right flechasta flechasta'.$gsta->cliente_id.'"title="Ver mas">';
                        if($k==0 )
                            $result.='<ion-icon name="chevron-down-outline"></ion-icon></span>';
                        else
                            $result.='<ion-icon name="chevron-up-outline"></ion-icon></span>';
                            $result.='</i></div>';

                        foreach($data['serv_tec_pi_a']->where('cliente_id',$gsta->cliente_id) as $sta){
                        $display='';
                        if($k<>0)
                            $display='display:none';
                        $result.='<a href="'.route('equipos.detail',array('id'=>$sta->equipo()->id)) .'?show=rows&tab=3"
                        class="chip chip-danger chip-media ml-05 mb-05 stalist sta'.$gsta->cliente_id.'" style="width:98%;'.$display.'">
                            <i class="chip-icon">
                                Ir
                            </i>
                            <span class="chip-label">'.$sta->equipo()->numero_parte.' </span>
                            <span class="fecha pull-right" title="Fecha de asignacion de tecnico">
                                    '.transletaDate($sta->estatusHistory()->orderBy('created_at','desc')->first()->created_at,true,'').'
                            </span>
                        </a>';
                        }
                    }
                }
            }
        }
        if($request->tag=='soporte_pend_tecnico'){
            if(current_user()->isOnGroup('programador') or current_user()->isOnGroup('administrador'))
                $cond1=''; 
            elseif(current_user()->isOnGroup('tecnico'))
                $cond1=' formulario_registro.tecnico_asignado='.current_user()->id;
            else
                $cond1=' formulario_registro.tecnico_asignado is null';
            $data['serv_tec_p']=$dashboard->getPendings($filtro,'serv_tec','P',$cond1);      
            $data['g_serv_tec_p']=$dashboard->getPendings($filtro,'serv_tec','P',$cond1,true,'',true);
            if(count($data['serv_tec_p'])){
                foreach($data['g_serv_tec_p'] as $k=>$gst ){
                    $result.='<div class="chip chip-danger chip-media ml-05 mb-05" style="width:100%;margin-top:15px !important;font-size:16px">
                                <span class="chip-label ">'.$gst->cliente()->nombre.' </span>
                                <i class="chip-icon abrirgst"  id="gst'.$gst->cliente_id.'" >
                                    <span class=" pull-right flechagst flechagst'.$gst->cliente_id.'"title="Ver mas">';
                    if($k==0)
                        $result.='<ion-icon name="chevron-down-outline"></ion-icon></span>';
                    else
                        $result.='<ion-icon name="chevron-up-outline"></ion-icon></span>';

                    $result.='</i></div>';
                    foreach($data['serv_tec_p']->where('cliente_id',$gst->cliente_id) as $st){
                        if( $st->equipo()){

                            $display='';
                            if($k<>0)
                               $display='display:none';

                            $result.='<a href="'. route('equipos.detail',array('id'=>$st->equipo()->id)) .'?show=rows&tab=3" 
                                    class="chip chip-danger chip-media ml-05 mb-05 gstlist gst'.$gst->cliente_id.'" style="width:98%; '.$display.'">
                                        <i class="chip-icon">
                                            Ir
                                        </i>
                                        <span class="chip-label">'.$st->equipo()->numero_parte.' </span>
                                        <span class="fecha pull-right" title="Fecha de creacion de ticket">'.transletaDate($st->created_at,true,'').'</span>
                                    </a>';
                        }
                    }
                }
            }            
        }
        if($request->tag=='servicio_tecnico_proceso_cliente'){
 
            $cond2='';
            if(current_user()->isOnGroup('tecnico'))
                $cond2=' formulario_registro.tecnico_asignado='.current_user()->id;
            $cond3=" equipo_status='O'";
            $data['g_serv_tec_pr_o_cli']=$dashboard->getPendings($filtro,'serv_tec','PR',$cond3,true,'',true);
            $cond3=" equipo_status='I'";
            $data['g_serv_tec_pr_i_cli']=$dashboard->getPendings($filtro,'serv_tec','PR',$cond3,true,'',true);
            $cond3=$cond2." equipo_status='I'";
            $data['g_serv_tec_pr_i']=$dashboard->getPendings($filtro,'serv_tec','PR',$cond3,true,'',true);

            $data['serv_tec_pr_cli']=$dashboard->getPendings($filtro,'serv_tec','PR','');
             $result.='<div class="col-md-12">
                <h3 class="text-success text-left">OPERATIVOS</h3>';
                if(count($data['g_serv_tec_pr_o_cli'])){
                    foreach($data['g_serv_tec_pr_o_cli'] as $k=>$gstpro){
                        $result.='<div class="chip chip-media ml-05 mb-05" style="width:100%;margin-top:15px !important;font-size:16px">
                            <span class="chip-label ">
                                if($gstpro->cliente())
                                '.$gstpro->cliente()->nombre.' 
                            </span>
                            <i class="chip-icon abrirstpr"  id="stpr'.$gstpro->cliente_id.'" >
                                <span class=" pull-right flechastpr flechastpr'.$gstpro->cliente_id.'"title="Ver mas">
                                    if($k==0 a0)
                                    <ion-icon name="chevron-down-outline"></ion-icon></span>
                                    else
                                    <ion-icon name="chevron-up-outline"></ion-icon></span>

                            </i>
                        </div>';
                        foreach($data['serv_tec_pr_cli']->where('cliente_id',$gstpro->cliente_id)->where('equipo_status','O') as $stpr){
                            if($stpr->equipo()){
                                $display='';
                                if($k<>0)
                                   $display='display:none';
                                $result.='<a href2="'. route('equipos.detail',array('id'=>$stpr->equipo_id)) .'?show=rows&tab=3"  href="#"
                                class="chip chip-media ml-05 mb-05 stprlist stpr'.$gstpro->cliente_id.'" style="padding:18px;width:98%; '.$display.'">
                                    
                                    <i class="chip-icon bg-'.getStatusBgColor($stpr->estatus).'">
                                        '.$stpr->estatus.'
                                    </i>';
                                    
                                        $fecha_sta=$stpr->estatusHistory()->where('estatus',$stpr->estatus)->orderBy('created_at','desc')->first()->created_at;
                                        $date1 = new DateTime($fecha_sta);
                                        $date2 = new DateTime('now', new \DateTimeZone('America/Panama'));
                                        $diff = $date1->diff($date2);
                                        // will output 2 days
                                        $transcurrido='';
                                        if($diff->d)
                                            $transcurrido=$diff->format('%dd %hh %im');
                                        else
                                                $transcurrido=$diff->format('%hh %im');
                    
                                    $result.='<span class="chip-label">'.$stpr->equipo()->numero_parte.'
                                       
                                    </span>
                                    
                                    <div  class="fecha pull-right" >
                                        <span title="Fecha de Inicio">
                                                '.transletaDate($fecha_sta,true,'').'
                                        </span><br/>
                                       
                                    </div>
                                </a>';
                            }
                        }
                    }
                }            
            $result.='</div>
            <div class="col-md-12">
                <h3 class="text-danger text-left">INOPERATIVOS</h3>';
                if(count($data['g_serv_tec_pr_i'])){
                    foreach($data['g_serv_tec_pr_i'] as $l=>$gstpri){
                        $result.='<div class="chip chip-media ml-05 mb-05" style="width:100%;margin-top:15px !important;font-size:16px">
                            <span class="chip-label ">'.$gstpri->cliente()->nombre.' </span>
                            <i class="chip-icon abrirstpr_i"  id="stpri'.$gstpri->cliente_id.'" >
                                <span class=" pull-right flechai_stpr flechai_stpr'.$gstpri->cliente_id.'"title="Ver mas">';
                                    if($l==0)
                                    $result.='<ion-icon name="chevron-down-outline"></ion-icon></span>';
                                    else
                                    $result.='<ion-icon name="chevron-up-outline"></ion-icon></span>';

                            $result.='</i>
                        </div>';
                        foreach($data['serv_tec_pr_cli']->where('cliente_id',$gstpri->cliente_id)->where('equipo_status','I') as $stpri){
                            if($stpri->equipo()){
                                $display='';
                                if($k<>0)
                                   $display='display:none';
                                $result.='<a href2="'. route('equipos.detail',array('id'=>$stpri->equipo_id)) .'?show=rows&tab=3"  href="#"
                                class="chip chip-media ml-05 mb-05 stprlist_i stpri'.$gstpri->cliente_id.'" style="padding:18px;width:98%;'.$display.'">
                                    
                                    <i class="chip-icon bg-'.getStatusBgColor($stpri->estatus).'">
                                        '.$stpri->estatus.'
                                    </i>';
             
                                        $fecha_sta=$stpri->estatusHistory()->orderBy('created_at','desc')->first()->created_at;
                                        $date1 = new DateTime($fecha_sta);
                                        $date2 = new DateTime(date('Y-m-d h:i:s'));
                                        $diff = $date1->diff($date2);
                                        // will output 2 days
                                        $transcurrido='';
                                        if($diff->d)
                                            $transcurrido=$diff->format('%dd %hh %im');
                                        else
                                                $transcurrido=$diff->format('%hh %im');
      
                                    $result.='<span class="chip-label">'.$stpri->equipo()->numero_parte.'
                                     
                                    </span>
                                    
                                    <div  class="fecha pull-right" >
                                        <span title="Fecha de Inicio">
                                                '.transletaDate($fecha_sta,true,'').'
                                        </span><br/>
                                     
                                    </div>
                                </a>';
                            }
                        }
                    }
                }            
            $result.='</div>';
        }


       return $result;
    }
}
    
