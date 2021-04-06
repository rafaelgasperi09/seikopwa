@extends('frontend.main-layout')
@section('content')
    @include('frontend.partials.title',array('title'=>$data->id_componente))
    <div class="section full mt-2 mb-2">
        <div class="section-title">Bateria : {{ $data->voltaje }}V</div>
        <div class="wide-block pb-1 pt-2">
            {{Form::open(array("method" => "POST","action" => "BateriaController@guardarEntredaSalida","role" => "form",'class'=>'form-horizontal'))}}
            {{ Form::hidden('componente_id',$data->id) }}
            {{ Form::hidden('formulario_id',$formulario->id) }}
            @foreach($formulario->secciones()->get() as $seccion)
                <div class="row">
                    <div class="col-md-12">
                        <h2>{{$seccion->titulo}}</h2>
                    </div>
                </div>
                <div class="divider bg-primary mt-2 mb-3"></div>
                <div class="row">
                @foreach($formulario->campos()->where('formulario_seccion_id',$seccion->id)->get() as $campo)
                    <?php
                    $requerido ='';
                    if($campo->requerido) $requerido = 'required';
                    ?>
                    <div class="form-group boxed {{$campo->tamano}}">
                        <div class="input-wrapper">
                            <label class="label" for="{{ $campo->nombre }}">{{ $campo->etiqueta }}</label>
                            @if($campo->tipo == 'text')
                                {{ Form::text($campo->nombre,null,array('class'=>'form-control',$requerido)) }}
                            @elseif($campo->tipo == 'textarea')
                                {{ Form::textarea($campo->nombre,null,array('class'=>'form-control',$requerido)) }}
                            @elseif($campo->tipo == 'select')
                                {{ Form::select($campo->nombre,getFormularioSelectOpciones($campo->opciones),null,array('class'=>'form-control','id'=>$campo->nombre,$requerido)) }}
                            @elseif($campo->tipo == 'combo')
                                {{ Form::select($campo->nombre,getCombo($campo->tipo_combo,'Seleccione '.$campo->etiqueta),null,array('class'=>'form-control',$requerido)) }}
                            @elseif($campo->tipo == 'database')
                                @include('partials.typehead_field',array('field_label'=>$campo->etiqueta,'field_name'=>$campo->nombre,'items'=>getModelList('\App\\'.$campo->modelo,getFormDatabaseNameByModule('\App\\'.$campo->modelo))))
                            @elseif($campo->tipo == 'api')
                                <?php $api = new \App\HcaApi($campo->api_endpoint);?>
                                @include('partials.typehead_field',array('field_label'=>$campo->etiqueta,'field_name'=>$campo->nombre,'items'=>$api->result()))
                            @elseif($campo->tipo == 'date')
                                {{ Form::date($campo->nombre,null,array('class'=>'form-control date',$requerido,'date-format'=>$campo->formato_fecha)) }}
                            @elseif($campo->tipo == 'file')
                                {{ Form::file($campo->nombre,array('class'=>'form-control file','id'=>'archivo',$requerido)) }}
                                <div id="errorBlock" class="help-block"></div>
                            @elseif($campo->tipo == 'time')
                                <input type="time" id="{{$campo->nombre}}" class="form-control" name="{{$campo->nombre}}"  {{$requerido}} >
                            @elseif($campo->tipo == 'number')
                                <input type="number" id="{{$campo->nombre}}" class="form-control" name="{{$campo->nombre}}"  {{$requerido}} >
                            @elseif($campo->tipo == 'checkbox')
                                <label class="switch">
                                    <input type="checkbox" name="{{ $campo->nombre }}"/><span></span>
                                </label>
                            @endif
                            <i class="clear-input">
                                <ion-icon name="{{ $campo->icono }}"></ion-icon>
                            </i>
                        </div>
                    </div>
                @endforeach
                </div>
            @endforeach
            <button type="submit" class="btn btn-primary btn-sm btn-block">GUARDAR</button>
            {{ Form::close() }}
        </div>
    </div>
<script>
    $('#horometro_salida_cuarto').parent().parent().hide();
    $('#carga_salida_cuarto').parent().parent().hide();
    $('#horometro_entrada_cuarto').parent().parent().hide();
    $('#carga_entrada_cuarto').parent().parent().hide();

    $('#accion').change(function (){

        console.log('vlue :'+$(this).val());
        if($(this).val() == ''){
            $('#horometro_salida_cuarto').parent().parent().hide();
            $('#carga_salida_cuarto').parent().parent().hide();
            $('#horometro_entrada_cuarto').parent().parent().hide();
            $('#carga_entrada_cuarto').parent().parent().hide();

        }else if($(this).val() == 'entrada'){
            $('#horometro_salida_cuarto').parent().parent().hide();
            $('#carga_salida_cuarto').parent().parent().hide();
            $('#horometro_entrada_cuarto').parent().parent().show();
            $('#carga_entrada_cuarto').parent().parent().show();
        }else{
            $('#horometro_salida_cuarto').parent().parent().show();
            $('#carga_salida_cuarto').parent().parent().show();
            $('#horometro_entrada_cuarto').parent().parent().hide();
            $('#carga_entrada_cuarto').parent().parent().hide();
        }
    })
</script>
@stop
