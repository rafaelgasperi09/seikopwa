<?php

use Intervention\Image\Facades\Image;

function current_user(){
    if(Sentinel::check()){
        return \App\User::find(\Sentinel::getUser()->id);
    }else{
        return redirect(route('login'));
    }
}

function imprimir($object){
    echo "<pre>";
    print_r($object);
    echo "</pre>";
}

function getFormularioSelectOpciones($opciones){

    $arr[null] = 'Selecionar';
    foreach (explode(',',$opciones) as $opt){
        $arr[$opt] = $opt;
    }

    return $arr;
}

function getFormularioRadioOpciones($opciones){

    foreach (explode(',',$opciones) as $opt){
        $arr[$opt] = $opt;
    }

    return $arr;
}

function getSubEquipo($name,$campo=null){
    $sub=\App\SubEquipo::where('name',$name)->first();
    if($sub){
        if($campo=='name')
            return $sub->display_name;

        return $sub->id;
    }
}
function getTipoEquipo($id){
    if($id=='todos')
      return 'Todos';
    $tipo=\App\TipoEquipo::findOrFail($id);
    return $tipo->display_name;
}
function mostrarCampo($tipo){
    $ocultar=['otro'];
    if(in_array($tipo,$ocultar))
        return false;
    else
        return true;
}

function getDayOfWeek($day){
    $day_week = '';
    switch ($day){
        case 1:
            $day_week = 'Lunes';
            break;
        case 2:
            $day_week = 'Martes';
            break;
        case 3:
            $day_week = 'Miercoles';
            break;
        case 4:
            $day_week = 'Jueves';
            break;
        case 5:
            $day_week = 'Viernes';
            break;
        case 6:
            $day_week = 'Sabado';
            break;
        case 7:
            $day_week = 'Domingo';
            break;

    }

  return $day_week;
}

function getFormData($formName,$equipo_id=0,$componente_id=0,$registro_id=0){

    $baseQuery='SELECT  fd.formulario_registro_id,MAX(fd.created_at) as creado,';
    $fc=\App\FormularioCampo::whereRaw(" formulario_id=(select id from formularios where nombre='$formName')")->get();
    foreach($fc as $f){
        $baseQuery.="
                    MAX(CASE fd.formulario_campo_id WHEN $f->id THEN fd.valor ELSE '' END) AS $f->nombre,";
    }

    $baseQuery=substr($baseQuery,0,strlen($baseQuery)-1); //elimina la ultima coma
    $equipoFilter='';

    if($equipo_id>0)
        $equipoFilter=" AND fr.equipo_id=$equipo_id";
    if($componente_id>0)
        $equipoFilter=" AND fr.componente_id=$componente_id";
    if($registro_id>0)
        $equipoFilter=" AND fr.id=$registro_id";
    $baseQuery.=" FROM formulario_data AS fd ,formulario_registro fr
                    WHERE fd.`formulario_registro_id`=fr.`id`
                    AND fr.`formulario_id`=(select id from formularios where nombre='$formName')
                    $equipoFilter
                    GROUP BY fd.formulario_registro_id;";


    $returnData=\DB::select(DB::raw($baseQuery));
    return $returnData;

}

function getFormFields($formName){
    $campos=\App\Formulario::select('formulario_campos.nombre')->where('formularios.nombre',$formName)->join('formulario_campos','formularios.id','=','formulario_campos.formulario_id')->get()->pluck(['nombre']);
    return $campos;
}

function getStatusHtml($status){

    $estados=array('P'=>'Pendiente','A'=>'Abierto','PR'=>'EN PROCESO','C'=>'Cerrado',''=>'N/A');
    $colores=array('P'=>'warning','A'=>'success','PR'=>'primary','C'=>'secondary','ligth');
    $html='<span class="badge badge-'.$colores[$status].'">'.$estados[$status].'</span>';
    return $html;
}

function getUsersByRol($rol_name){

$arr=array();
    foreach(\App\User::get() as $u){

        if($u->isOnGroup($rol_name))
            $arr[] = $u;
    }

    return $arr;
}

function getListUsersByRol($rol_name){

    $arr=array();
    foreach(\App\User::get() as $u){

        if($u->isOnGroup($rol_name))
            $arr[$u->id] = $u->getFullName();
    }

    return $arr;
}

function  getEquipoIconBySubTipo($sub_tipo_id){

    $icon = url('assets/img/mc2.png');
    $path = '';
    switch($sub_tipo_id){
        case 1:
          $icon = 'COUNTER RC.JPG';
          break;
        case 2:
            $icon = 'COMBUSTION.JPG';
            break;
        case 3:
            $icon = 'COUNTER FC.JPG';
            break;
        case 4:
            $icon = 'COUNTER SC.JPG';
            break;
        case 5:
            $icon ='PALLET.PNG';
            break;
        case 6:
            $icon = 'REACH.JPG';
            break;
        case 7:
            $icon = 'STOCK PICKER.PNG';
            break;
        case 8:
            $icon = 'WALKIE PALLET.JPG';
            break;
        case 9:
            $icon = 'STACKER.JPG';
            break;
        case 10:
            $icon = 'WAVE.JPG';
            break;
    }

    //$img = Image::make(public_path('images/montacargas/'.$icon));
    //$img->resize(64, 64)->save(public_path('images/montacargas/thumbnails/'.$icon));

     return url('images/montacargas/thumbnails/'.$icon);

}
?>
