<?php
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
    $ocultar=['otros'];
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

function getFormData($formName){
    $baseQuery="select 'SELECT  fd.formulario_registro_id,MAX(fd.created_at) as creado,' as campo
                union
                select concat(' MAX(CASE fd.formulario_campo_id WHEN ',
                id,' THEN fd.valor ELSE \'\' END) AS ',
                nombre,',') as campo 
                from formulario_campos where formulario_id =(select max(id) from formularios where nombre='$formName' ) and deleted_at is null
                UNION 
                select concat('now() as fecha_crea FROM formulario_data AS fd ,formulario_registro fr
                WHERE fd.`formulario_registro_id`=fr.`id`
                AND fr.`formulario_id`=',id,'
                GROUP BY fd.formulario_registro_id;') from formularios where nombre='$formName' ";
    
  $resultQuery=\DB::select(DB::raw($baseQuery));

 
  $returnQuery='';
    foreach($resultQuery as $r){
        $returnQuery.=$r->campo.'
        ';
    }

    $returnData=\DB::select(DB::raw($returnQuery));
    return $returnData;

}

function getFormFields($formName){
    $campos=\App\Formulario::select('formulario_campos.nombre')->where('formularios.nombre',$formName)->join('formulario_campos','formularios.id','=','formulario_campos.formulario_id')->get()->pluck(['nombre']);
    return $campos;
}

?>
