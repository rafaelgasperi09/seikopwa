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
        $abierta0=false;
        $display='display:none;';
        $dashboard=app('App\Http\Controllers\DashboardController');
        $data['tipo']='gmp';
        $filtro['gmp']="numero_parte like 'GM%'";
        $filtro['cliente']="numero_parte not like 'GM%'";
        if(current_user()->isCliente())
            $data['tipo']='cliente';
        $filtro=$filtro[$data['tipo']];

        $data=array();
        $user=User::find($request->user_id);
        $result1=''; 

        //////////////////////////////////////////////////////////////////////////////////////////
        if($request->tag=='total_equipo'){
            $result1='';
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
                    $result1.='<a href='.route('equipos.tipo',array($sub_equipos['name'],$tipo['tipo_id'])) .'" class="chip chip-media ml-05 mb-05" style="width:100%">';
                        if(false){
                            $result1.='<i class="chip-icon bg-primary">'.$tipo['total'].'</i>';
                        }
                        else{
                            $result1.=' <i class="chip-center chip-icon bg-primary">
                                            '. $tipo['total'] .'
                                        </i>
                                        <i class="chip-icon bg-warning" title="GMP">
                                            '. $tipo['GMtotal'] .'
                                        </i>';
                        }

                        $result1.='<span class="chip-label">'. $tipo['tipo'] .'</span></a>';
                }
            }
            ////////////// TOTAL BATERIAS ////////////////////////////////
            if(\Sentinel::hasAccess('baterias.index')){
                $data['total_baterias'] =Componente::FiltroBodega()->whereTipoComponenteId(2)->count();
                $result1.='<a href="'. route('baterias.index') .'"
                        class="chip chip-media ml-05 mb-05" style="width:100%">
                            <i class="chip-icon bg-success">
                                '. $data['total_baterias'] .'
                            </i>
                            <span class="chip-label">Baterías</span>
                        </a>';
        
                $totales+=$data['total_baterias'];
                $totales_title='Total Equipos + Baterías';

            }


            $result1.="<script>
            $('#tot_equipos').html($totales);
            $('#tot_title').html('$totales_title');
           </script>";

           return  $result1;
        }
       //////////////////////////////////////////////////////////////////////////////////////////
       if($request->tag=='equipos_pendientes_daily_check'){
            $result2='';
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
            $result2='';
            if(count($data['equipos_sin_daily_check_hoy'])){
                foreach($data['equipos_sin_daily_check_hoy'] as $id=>$equipo){
                     $result2.='<a href="'.route('equipos.detail',array('id'=>$id)).'?show=rows&tab=1" class="chip chip-warning chip-media ml-05 mb-05" >
                        <span class="chip-label">'.$equipo.'</span>
                    </a>';
                }
            }
        return  $result2;
       }

       if($request->tag=='mant_prev_pend_firma'){
            $result3='';
            //mantenimientos preventivos pendientes de firma supervisor
            $data['mant_prev']=$dashboard->getPendings($filtro,'mant_prev','P','');
            $data['g_mant_prev']=$dashboard->getPendings($filtro,'mant_prev','P','',true,'',true);
            $totalpf=count($data['mant_prev']);
            if($totalpf){
                foreach($data['g_mant_prev'] as $k=>$gmp ){
                    $result3.='<div class="chip chip-warning chip-media ml-05 mb-05" style="width:100%;margin-top:15px !important;font-size:16px">
                                <span class="chip-label">
                                '.$gmp->cliente()->nombre.' 
                                </span>
                                <i class="chip-icon abrir"  id="gmp'.$gmp->cliente_id.'" >
                                <span class=" pull-right flechagmp flechagmp'.$gmp->cliente_id.'"title="Ver mas">';
                    if($k==0)
                     $result3.='<ion-icon name="chevron-down-outline"></ion-icon></span>';
                    else
                     $result3.='<ion-icon name="chevron-up-outline"></ion-icon></span>';

                                 $result3.='</i>
                            </div>';
                    foreach($data['mant_prev']->where('cliente_id',$gmp->cliente_id) as $mp){
                     $result3.='<a href="'.route('equipos.detail',array('id'=>$mp->equipo_id)) .'?show=rows&tab=2"  class="chip chip-warning chip-media ml-05 mb-05 gmplist gmp'.$gmp->cliente_id.'" style="width:98%;';
                     
                     if($k<>0 and !$abierta0)  
                            $display='display:none;';
                        $result3.=$display.'"><i class="chip-icon">
                            Ir
                        </i>
                        <span class="chip-label">'.$mp->equipo()->numero_parte.' </span>
                        <span class="fecha pull-right" title="Fecha de creacion">'.transletaDate($mp->created_at,true,'').'</span>
                    </a>';
                    }
                }
                    
                
            }
            $result3.="<script>
            $('#tot_equipos_pf').html($totalpf);

           </script>";

           return $result3;
       }


       if($request->tag=='daily_check_pend_firma'){
        $cond1=''; 
        $result4='';
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
                     $result4.='<div class="chip chip-warning chip-media ml-05 mb-05" style="width:100%;margin-top:15px !important;font-size:16px">
                        <span class="chip-label ">'.$gdc->cliente()->nombre.' </span>
                        <i class="chip-icon abrirgdc"  id="gdc'.$gdc->cliente_id.'" >
                            <span class=" pull-right flechagdc flechagdc'.$gdc->cliente_id.'"title="Ver mas">';
                                if($k==0)
                                $result4.='<ion-icon name="chevron-down-outline"></ion-icon></span>';
                                else
                                $result4.='<ion-icon name="chevron-up-outline"></ion-icon></span>';
                    $result4.='</i></div>';

                    foreach($data['daily_check']->where('cliente_id',$gdc->cliente_id) as $dc){
                        if($dc->equipo()){
                        
                         if(current_user()->isOnGroup('supervisorc') )
                            $result4.='<a href="'.route('equipos.edit_daily_check',array('id'=>$dc->id)).'?show=rows&tab=1" ';
                        else $result4.='<a href="'.route('equipos.detail',array('id'=>$dc->equipo_id)) .'?show=rows&tab=1" '; 
                        
                        if($k<>0 and !$abierta0)
                            $display='display:none;';
                        $result4.='class="chip chip-warning chip-media ml-05 mb-05 gdclist gdc'.$gdc->cliente_id.'" style="width:98%;'.$display.'">
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
            $result4.="<script>
            $('#tot_equipos_dcpf').html($totales);

           </script>";

           return $result4;
       }


       if($request->tag=='soporte_pend_iniciar'){
        $result5='';
        $totales=0;
        //servicio tecnico PENDIENTES DE INICIAR
        $data['serv_tec_pi_a']=$dashboard->getPendings($filtro,'serv_tec','A');
        $data['g_serv_tec_pi_a']=$dashboard->getPendings($filtro,'serv_tec','A','',true,'',true);
        $totales=count($data['serv_tec_pi_a']);
            if($totales){
                foreach($data['g_serv_tec_pi_a'] as $k=>$gsta){
                    foreach($data['g_serv_tec_pi_a'] as $k=>$gsta){
                        $result5.='<div class="chip chip-danger chip-media ml-05 mb-05" style="width:100%;margin-top:15px !important;font-size:16px">
                            <span class="chip-label ">'.$gsta->cliente()->nombre.' </span>
                            <i class="chip-icon abrirsta"  id="sta'.$gsta->cliente_id.'" >
                                <span class=" pull-right flechasta flechasta'.$gsta->cliente_id.'"title="Ver mas">';
                        if($k==0 )
                            $result5.='<ion-icon name="chevron-down-outline"></ion-icon></span>';
                        else
                            $result5.='<ion-icon name="chevron-up-outline"></ion-icon></span>';
                            $result5.='</i></div>';

                        foreach($data['serv_tec_pi_a']->where('cliente_id',$gsta->cliente_id) as $sta){
                        
                        if($k<>0 and !$abierta0)
                            $display='display:none';
                        $result5.='<a href="'.route('equipos.detail',array('id'=>$sta->equipo()->id)) .'?show=rows&tab=3"
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

            $result5.="<script>
            $('#serv_tect_pend_ini').html($totales);

           </script>";
        return $result5;
        }


        if($request->tag=='soporte_pend_tecnico'){

            $result6='';
            $totales=0;
            if(current_user()->isOnGroup('programador') or current_user()->isOnGroup('administrador'))
                $cond1=''; 
            elseif(current_user()->isOnGroup('tecnico'))
                $cond1=' formulario_registro.tecnico_asignado='.current_user()->id;
            else
                $cond1=' formulario_registro.tecnico_asignado is null';
            $data['serv_tec_p']=$dashboard->getPendings($filtro,'serv_tec','P',$cond1);      
            $data['g_serv_tec_p']=$dashboard->getPendings($filtro,'serv_tec','P',$cond1,true,'',true);
            $totales=count($data['serv_tec_p']);
            if($totales){
                
                foreach($data['g_serv_tec_p'] as $k=>$gst ){
                    $result6.='<div class="chip chip-danger chip-media ml-05 mb-05" style="width:100%;margin-top:15px !important;font-size:16px">
                                <span class="chip-label ">'.$gst->cliente()->nombre.' </span>
                                <i class="chip-icon abrirgst"  id="gst'.$gst->cliente_id.'" >
                                    <span class=" pull-right flechagst flechagst'.$gst->cliente_id.'"title="Ver mas">';
                    if($k==0)
                        $result6.='<ion-icon name="chevron-down-outline"></ion-icon></span>';
                    else
                        $result6.='<ion-icon name="chevron-up-outline"></ion-icon></span>';

                    $result6.='</i></div>';
                    foreach($data['serv_tec_p']->where('cliente_id',$gst->cliente_id) as $st){
                        if( $st->equipo()){

                            
                            if($k<>0 and !$abierta0)
                               $display='display:none';

                            $result6.='<a href="'. route('equipos.detail',array('id'=>$st->equipo()->id)) .'?show=rows&tab=3" 
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

            $result6.="<script>
            $('#tot_equipos_stpat').html($totales);

           </script>";
            return $result6;
        }


        if($request->tag=='servicio_tecnico_proceso_cliente'){
            $result7='';
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
             $result7.='<div class="col-md-12">
                <h3 class="text-success text-left">OPERATIVOS</h3>';
                if(count($data['g_serv_tec_pr_o_cli'])){
                    foreach($data['g_serv_tec_pr_o_cli'] as $k=>$gstpro){
                        $result7.='<div class="chip chip-media ml-05 mb-05" style="width:100%;margin-top:15px !important;font-size:16px">
                            <span class="chip-label ">';
                                if($gstpro->cliente())
                                $result7.=$gstpro->cliente()->nombre;
                           $result7.='</span>
                            <i class="chip-icon abrirstpr"  id="stpr'.$gstpro->cliente_id.'" >
                                <span class=" pull-right flechastpr flechastpr'.$gstpro->cliente_id.'"title="Ver mas">';
                                    if($k==0 )
                                    $result7.='<ion-icon name="chevron-down-outline"></ion-icon></span>';
                                    else
                                    $result7.='<ion-icon name="chevron-up-outline"></ion-icon></span>';

                            $result7.='</i></div>';
                        foreach($data['serv_tec_pr_cli']->where('cliente_id',$gstpro->cliente_id)->where('equipo_status','O') as $stpr){
                            if($stpr->equipo()){
                                
                                if($k<>0 and !$abierta0)
                                   $display='display:none';
                                $result7.='<a href2="'. route('equipos.detail',array('id'=>$stpr->equipo_id)) .'?show=rows&tab=3"  href="#"
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
                    
                                    $result7.='<span class="chip-label">'.$stpr->equipo()->numero_parte.'
                                       
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
            $result7.='</div>
            <div class="col-md-12">
                <h3 class="text-danger text-left">INOPERATIVOS</h3>';
                if(count($data['g_serv_tec_pr_i'])){
                    foreach($data['g_serv_tec_pr_i'] as $l=>$gstpri){
                        $result7.='<div class="chip chip-media ml-05 mb-05" style="width:100%;margin-top:15px !important;font-size:16px">
                            <span class="chip-label ">'.$gstpri->cliente()->nombre.' </span>
                            <i class="chip-icon abrirstpr_i"  id="stpri'.$gstpri->cliente_id.'" >
                                <span class=" pull-right flechai_stpr flechai_stpr'.$gstpri->cliente_id.'"title="Ver mas">';
                                    if($l==0)
                                    $result7.='<ion-icon name="chevron-down-outline"></ion-icon></span>';
                                    else
                                    $result7.='<ion-icon name="chevron-up-outline"></ion-icon></span>';

                            $result7.='</i>
                        </div>';
                        foreach($data['serv_tec_pr_cli']->where('cliente_id',$gstpri->cliente_id)->where('equipo_status','I') as $stpri){
                            if($stpri->equipo()){
                                
                                if($k<>0 and !$abierta0)
                                   $display='display:none';
                                $result7.='<a href2="'. route('equipos.detail',array('id'=>$stpri->equipo_id)) .'?show=rows&tab=3"  href="#"
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
      
                                    $result7.='<span class="chip-label">'.$stpri->equipo()->numero_parte.'
                                     
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
            $result7.='</div>';

        return $result7;
        }

        if($request->tag=='soporte_en_proceso'){
            $totales=0;
            $result7='';
            $cond2='';
            if(current_user()->isOnGroup('tecnico'))
                $cond2=' formulario_registro.tecnico_asignado='.current_user()->id;

            $data['serv_tec_pr']=$dashboard->getPendings($filtro,'serv_tec','PR',$cond2);
            $data['serv_tec_pr_cli']=$dashboard->getPendings($filtro,'serv_tec','PR','');

            if(!empty($cond2)){$cond2.=' and';}
            $cond3=" equipo_status='O'";
            $data['g_serv_tec_pr_o_cli']=$dashboard->getPendings($filtro,'serv_tec','PR',$cond3,true,'',true);
            $data['g_serv_tec_pr_o']=$dashboard->getPendings($filtro,'serv_tec','PR',$cond3,true,'',true);   
            $cond3=" equipo_status='I'";
            $data['g_serv_tec_pr_i_cli']=$dashboard->getPendings($filtro,'serv_tec','PR',$cond3,true,'',true);
            $data['g_serv_tec_pr_i']=$dashboard->getPendings($filtro,'serv_tec','PR',$cond3,true,'',true);
            
            $result7.='<div class="col-md-6">
                        <h3 class="text-success text-left" cant="'.count($data['g_serv_tec_pr_o']).'">OPERATIVOS</h3>';
                        if(count($data['g_serv_tec_pr_o'])){
                            foreach($data['g_serv_tec_pr_o'] as $k=>$gstpro){
                                
                                 $result7.='<div class="chip chip-media ml-05 mb-05" style="width:100%;margin-top:15px !important;font-size:16px">
                                    <span class="chip-label ">';
                                        if($gstpro->cliente())
                                         $result7.=$gstpro->cliente()->nombre;

                            $result7.='</span>
                                    <i class="chip-icon abrirstpr"  id="stpr'.$gstpro->cliente_id.'" >
                                        <span class=" pull-right flechastpr flechastpr'.$gstpro->cliente_id.'"title="Ver mas">';
                                            if($k==0 and !$abierta0)
                                                $result7.='<ion-icon name="chevron-down-outline"></ion-icon></span>';
                                            else
                                                $result7.='<ion-icon name="chevron-up-outline"></ion-icon></span>';

                            $result7.='</i>
                                </div>';
                                foreach($data['serv_tec_pr']->where('cliente_id',$gstpro->cliente_id)->where('equipo_status','O') as $stpr){
                                    if($stpr->equipo()){
                                        $totales++;
                                        if($k<>0 and !$abierta0)
                                            $display='display:none';
                                        $result7.='<a href="'. route('equipos.detail',array('id'=>$stpr->equipo_id)) .'?show=rows&tab=3"  
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
                             
                                            $result7.='<span class="chip-label">'.$stpr->equipo()->numero_parte;
                                                if($stpr->trabajado_por<>'')
                                                $result7.='<ion-icon size="large" name="checkmark-sharp" role="img" class="md hydrated text-success" style="position: absolute;top: 0px;left: 99px;" aria-label="cube outline"></ion-icon>';
   
                                            $result7.='</span>
                                            
                                            <div  class="fecha pull-right" >
                                                <span title="Fecha de Inicio">
                                                        '.transletaDate($fecha_sta,true,'').'
                                                </span><br/>
                                                <span title="Tiempo transcurrido">
                                                        Hace '.$transcurrido.'
                                                </span>
                                            </div>
                                        </a>';
                                    }
                                }
                            }
                        }            
                    $result7.='</div>
                    <div class="col-md-6">
                        <h3 class="text-danger text-left">INOPERATIVOS</h3>';
                        if(count($data['g_serv_tec_pr_i'])){
                            foreach($data['g_serv_tec_pr_i'] as $l=>$gstpri){ 
                                 $result7.='<div class="chip chip-media ml-05 mb-05" style="width:100%;margin-top:15px !important;font-size:16px">
                                    <span class="chip-label ">'.$gstpri->cliente()->nombre.' </span>
                                    <i class="chip-icon abrirstpr_i"  id="stpri'.$gstpri->cliente_id.'" >
                                        <span class=" pull-right flechai_stpr flechai_stpr'.$gstpri->cliente_id.'"title="Ver mas">';
                                            if($l==0)
                                            $result7.='<ion-icon name="chevron-down-outline"></ion-icon></span>';
                                            else
                                            $result7.='<ion-icon name="chevron-up-outline"></ion-icon></span>';

                             $result7.='</i>
                                </div>';
                                foreach($data['serv_tec_pr']->where('cliente_id',$gstpri->cliente_id)->where('equipo_status','I') as $stpri){
                                    $totales++;
                                    if($stpri->equipo()){
                                        if($k<>0 and !$abierta0)
                                            $display='display:none';
                                        $result7.='<a href="'. route('equipos.detail',array('id'=>$stpri->equipo_id)) .'?show=rows&tab=3"  
                                        class="chip chip-media ml-05 mb-05 stprlist_i stpri'.$gstpri->cliente_id.'" style="padding:18px;width:98%; '.$display.'">
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
                                            
                                            $result7.='<span class="chip-label">'.$stpri->equipo()->numero_parte;
                                                if($stpri->trabajado_por<>'')
                                                $result7.='<ion-icon size="large" name="checkmark-sharp" role="img" class="md hydrated text-success" style="position: absolute;top: 0px;left: 99px;" aria-label="cube outline"></ion-icon>';
                                                
                                            $result7.='</span>
                                            
                                            <div  class="fecha pull-right" >
                                                <span title="Fecha de Inicio">
                                                        '.transletaDate($fecha_sta,true,'').'
                                                </span><br/>
                                                <span title="Tiempo transcurrido">
                                                        Hace '.$transcurrido.'
                                                </span>
                                            </div>
                                        </a>';
                                    }
                                }
                            }
                        }            
                    $result7.='</div>';
            $result7.="<script>
            $('#soporte_en_proceso_tot').html($totales);
            </script>";
        return $result7;
        }
        if($request->tag=="servicio_tecnico_cerrado"){

            //servicio tecnico EN PROCESO
            $result8='';
            $data['serv_tec_10']=array();
            $desde = \Carbon\Carbon::now()->subDays(45)->format('Y-m-d'); //filtro reportes cerrados 45 dias
            $filtroExtra="(formulario_registro.estatus='C' and formulario_registro.created_at >='$desde' or formulario_registro.estatus<>'C')";

            if(current_user()->isOnGroup('administrador') or current_user()->isOnGroup('programador') or current_user()->isSupervisor()){
                $data['serv_tec_10']=$dashboard->getPendings($filtro,'serv_tec','C',$filtroExtra,false,'');
                $data['g_serv_tec_10']=$dashboard->getPendings($filtro,'serv_tec','C',$filtroExtra,false,'',true);
            }


            if(current_user()->isSupervisor('cliente')){
                $data['serv_tec_10']=$dashboard->getPendings($filtro,'serv_tec','C',$filtroExtra,true,'');
                $data['g_serv_tec_10']=$dashboard->getPendings($filtro,'serv_tec','C',$filtroExtra,true,'',true);
            }
            
            if(count($data['serv_tec_10']) ){
                foreach($data['g_serv_tec_10'] as $k=>$gst10){
                    $result8.='<div class="chip chip-media ml-05 mb-05" style="width:100%;margin-top:15px !important;font-size:16px">
                        <span class="chip-label ">'.$gst10->cliente()->nombre.' </span>
                        <i class="chip-icon abrirgst10"  id="gst10'.$gst10->cliente_id.'" >
                            <span class=" pull-right flechagst10 flechagst10'.$gst10->cliente_id.'"title="Ver mas">';
                                if($k==0 and !$abierta0)
                                $result8.='<ion-icon name="chevron-down-outline"></ion-icon></span>';
                                else
                                $result8.='<ion-icon name="chevron-up-outline"></ion-icon></span>';
                              
                     $result8.='</i>
                    </div>';
                    foreach($data['serv_tec_10']->where('cliente_id',$gst10->cliente_id) as $st10){
                        if($st10->equipo()){
                            if($k<>0 and !$abierta0)
                            $display='display:none';
                            $result8.='<a href="'. route('equipos.detail',array('id'=>$st10->equipo()->id)) .'?show=rows&tab=3"
                                class="chip  chip-media ml-05 mb-05 gst10list gst10'.$gst10->cliente_id.'"  style="width:98%; '.$display.'">
                                <i class="chip-icon bg-'.getStatusBgColor($st10->estatus).'">
                                    '.$st10->estatus.'
                                </i>
                                <span class="chip-label">'.$st10->equipo()->numero_parte.'</span>
                            </a>';
                        }
                    }
                }
            }
            return $result8;    
        }
        
        if($request->tag=="daily_check_completados"){

                $data=$equipo_sin_daily=$equipo_con_daily=array();
                $data['tipo']='gmp';
                $data['g_daily_check_hoy']=array();
                $equipos =  Equipo::FiltroCliente()->whereRaw($filtro)->pluck('cliente_id','id');
                $filtro_cliente='';
                if(limpiar_lista(current_user()->crm_clientes_id)<>''){
                    $filtro_cliente="and fr.cliente_id in (".limpiar_lista(current_user()->crm_clientes_id).")";
                    $clientes=Cliente::whereRaw('id in ('.limpiar_lista(current_user()->crm_clientes_id).')')->pluck('nombre','id');
                }else{
                    $clientes=Cliente::pluck('nombre','id');
                }
                
            
                $data['daily_check_hoy']=DB::select("select  fr.cliente_id,date_format(fr.created_at,'%Y-%m-%d') as fecha ,numero_parte,fr.equipo_id ,
                                                    max(case fr.turno_chequeo_diario when 1 then fr.estatus end) as turno1,
                                                    max(case fr.turno_chequeo_diario when 2 then fr.estatus end) as turno2,
                                                    max(case fr.turno_chequeo_diario when 3 then fr.estatus end) as turno3,
                                                    max(case fr.turno_chequeo_diario when 4 then fr.estatus end) as turno4
                                                    from equipos_vw ev 
                                                    left join formulario_registro fr on ev.id =fr.equipo_id and fr.deleted_at is null 
                                                    where  ev.deleted_at is null
                                                    and date_format(fr.created_at,'%Y-%m-%d')='2024-11-01' 
                                                    $filtro_cliente
                                                    and $filtro
                                                    and fr.formulario_id =2
                                                    group by  fr.cliente_id,date_format(fr.created_at,'%Y-%m-%d'),numero_parte,fr.equipo_id 
                                                    order by numero_parte ");

                foreach($data['daily_check_hoy'] as $dc){
                    $equipo_con_daily[$dc->cliente_id][$dc->equipo_id]=$dc;
                    unset( $equipos[$dc->equipo_id]);
                }
                foreach($equipos as $e=>$c){
                    $equipo_sin_daily[$c][$e]=Equipo::find($e)->numero_parte;
                }
                $data['equipo_con_daily']=$equipo_con_daily;
                $data['equipo_sin_daily']=$equipo_sin_daily;
                $data['equipos']=$equipos;
                $data['clientes']=$clientes;

                $result9='';
                $result9.='<div class="col-md-6">
                <h3 class="text-success text-left" cant="6">COMPLETADOS</h3>';

                foreach($data['equipo_con_daily'] as $k=>$e){
                    if(current_user()->isOnGroup('programador') and $i++>=10){
                        break;
                    }
                    $result9.=' <div class="chip chip-media ml-05 mb-05" style="width:100%;margin-top:15px !important;font-size:16px">
                            <span class="chip-label "> '.$data['clientes'][$k].'</span>
                            <i class="chip-icon abrirecd" id="ecd_'.$k.'">
                                <span class=" pull-right flechaecd flechaecd_'.$k.'" title="Ver mas">
                                <ion-icon name="chevron-up-outline" role="img" class="md hydrated" aria-label="chevron up outline"></ion-icon>
                                </span>
                            </i>
                            </div>';
                    foreach($e as $d){
                        $gancho='<ion-icon class="checkday md icon-large hydrated" name="checkmark-outline" size="large" style="color:green;" role="img" aria-label="checkmark outline" title></ion-icon>';
                        $equis='<ion-icon name="close-outline" style="color:red;" size="large" role="img" class="md icon-large hydrated" aria-label="close outline" title></ion-icon>';
                        $turnos=array();
                        for($i=1;$i<=4;$i++){
                            $turnos[$i]=str_replace('title','title="Turno '.$i.'"',$equis);
                            $var='turno'.$i;
                            if($d->$var)
                                $turnos[$i]=str_replace('title','title="Turno '.$i.'"',$gancho);
                        }                      
                            
                        $result9.='<a href="'. route('equipos.detail',array('id'=>$d->equipo_id)) .'?show=rows&tab=1"  class="chip  chip-media ml-05 mb-05 ecdlist ecd_'.$k.'" style="width: 98%;display:none">
         
                                <table width="100%">
                                    <tr>
                                        <td width="50%" class=" text-left"><span class="chip-label">'.$d->numero_parte.'</span></td>
                                        <td>'.$turnos[1].'</td>
                                        <td>'.$turnos[2].'</td>
                                        <td>'.$turnos[3].'</td>
                                        <td>'.$turnos[4].'</td>
                                    </tr>
                                </table>
                            </a>';
                    }
                }

                $result9.='</div>';

                $result9.='<div class="col-md-6">
                                <h3 class="text-danger text-left" cant="6">SIN COMPLETAR</h3>';
                                foreach($data['equipo_sin_daily'] as $k=>$e){
                                    if(current_user()->isOnGroup('programador') and $i++>=10){
                                        break;
                                    }
                                    $cli_name='';
                                    if(isset($data['clientes'][$k]))
                                        $cli_name=$data['clientes'][$k];
                                        $result9.= '<div class="chip chip-media ml-05 mb-05" style="width:100%;margin-top:15px !important;font-size:16px">
                                            <span class="chip-label "> '.$cli_name.'</span>
                                            <i class="chip-icon abriresd" id="esd_'.$k.'">
                                                <span class=" pull-right flechaesd flechaesd_'.$k.'" title="Ver mas">
                                                <ion-icon name="chevron-up-outline" role="img" class="md hydrated" aria-label="chevron up outline"></ion-icon>
                                                </span>
                                            </i>
                                            </div>';
                                    foreach($e as $y=>$d){

                                        $result9.='<a href="'. route('equipos.detail',array('id'=>$y)) .'?show=rows&tab=1" class="chip  chip-media ml-05 mb-05 esdlist esd_'.$k.'" style="width: 98%;display:none">
                        
                                                <table width="100%">
                                                    <tr>
                                                        <td width="50%" class=" text-left"><span class="chip-label">'.$d.'</span></td>
                                                    </tr>
                                                </table>
                                            </a>';
                                    }
                                }
                                
                $result9.='</div>';

         return $result9;
        }


    }
}
    
