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
    $tipo=\App\TipoEquipo::findOrFail($id)->first();
    return $tipo->display_name;

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
?>
