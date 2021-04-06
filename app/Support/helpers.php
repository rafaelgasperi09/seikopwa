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

?>
