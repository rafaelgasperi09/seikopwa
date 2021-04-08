@extends('frontend.main-layout')
@section('content')
    @include('frontend.partials.title',array('title'=>'Daily Check','subtitle'=>$data->numero_parte))

    <div class="section full mt-2 mb-2">
        <div class="section-title">{{$formulario->titulo}}</div>
        <div class="wide-block pb-3 pt-2">
            <div class="row">
                <div class="col-4">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="name1">Cliente</label>
                            {{ Form::text('cliente',$data->cliente->nombre,array('class'=>'form-control','readonly')) }}
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="name1">Direccion</label>
                            {{ Form::text('direccion',$data->cliente->direccion,array('class'=>'form-control','readonly')) }}
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="name1">Semana</label>
                            {{ Form::text('semana',\Carbon\Carbon::now()->startOfWeek()->format('d-m-Y').' a '.\Carbon\Carbon::now()->endOfWeek()->format('d-m-Y'),array('class'=>'form-control','readonly')) }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-4">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="name1">Marca</label>
                            {{ Form::text('marca',$data->marca->display_name,array('class'=>'form-control','readonly')) }}
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="name1">Modelo</label>
                            {{ Form::text('modelo',$data->modelo,array('class'=>'form-control','readonly')) }}
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="name1">Serie</label>
                            {{ Form::text('serie',$data->serie,array('class'=>'form-control','readonly')) }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-4">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="name1">Marca</label>
                            {{ Form::text('dayofweek',getDayOfWeek(date('N')),array('class'=>'form-control','readonly')) }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="section-title">NOTA:Este formulario debe hacerse por el operdor del equipo diariamente al comienzo del turno. Ciertos articulos enumerados no son incluidos en algunos modelos. Compruebe todos los articulos aplicables a su unidad.</div>
            <div class="section-title">OK => Buen Estado , M => Mal Estado , R => Revisar</div>
        </div>
    </div>
    {{Form::open(array("method" => "POST","action" => "EquiposController@storeDailyCheck","role" => "form",'class'=>'form-horizontal'))}}
    {{ Form::hidden('equipo_id',$data->id) }}
    {{ Form::hidden('formulario_id',$formulario->id) }}
    @foreach($formulario->secciones()->where('id','<>',3)->get() as $seccion)
        <div class="section full mt-2 mb-2">
            <div class="section-title">{{ strtoupper($seccion->titulo) }}</div>
            <div class="wide-block pb-1 pt-2">
                <div class="row">
                @foreach($formulario->campos()->where('formulario_seccion_id',$seccion->id)->whereNotIn('nombre',['semana','dia_semana'])->get() as $campo)
                    <?php
                    $requerido ='';
                    if($campo->requerido) $requerido = 'required';
                    ?>
                    <div class="form-group boxed {{$campo->tamano}}">
                        <div class="input-wrapper">
                            <label class="label" for="{{ $campo->nombre }}">{{ $campo->etiqueta }}</label>
                            <small>{{ $campo->subetiqueta }}</small>
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
                            @elseif($campo->tipo == 'firma')
                                {{ Form::text($campo->nombre,null,array('class'=>'form-control',$requerido)) }}
                            @endif
                            <i class="clear-input">
                                <ion-icon name="{{ $campo->icono }}"></ion-icon>
                            </i>
                        </div>
                    </div>
                @endforeach
                </div>
           </div>
        </div>
    @endforeach
    <button type="submit" class="btn btn-primary btn-sm btn-block">GUARDAR</button>
    {{ Form::close() }}

<script>

</script>
@stop
