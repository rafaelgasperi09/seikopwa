<?php
function current_user(){
    if(Sentry::check()){
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
?>
