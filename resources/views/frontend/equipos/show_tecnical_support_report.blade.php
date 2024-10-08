@extends('frontend.main-layout')
@section('content')
    @include('frontend.partials.title',array('title'=>'Servicio tecnico','subtitle'=>$data->numero_parte))

    <div class="section full mt-2 mb-2">
        <div class="section-title">{{$formulario->titulo}}</div>
        <div class="wide-block pb-3 pt-2">
            <div class="row">
                <div class="col-md-8 col-sm-6 col-12">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="name1">Cliente</label>
                            {{ Form::text('cliente',$equipo->cliente->nombre,array('class'=>'form-control','readonly')) }}
                            {{ Form::hidden('cliente_id',$data->id) }}
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="name1">Marca</label>
                            {{ Form::text('marca',$equipo->marca->display_name,array('class'=>'form-control','readonly')) }}
                        </div>
                    </div>
                </div>
                <div class="col-md-8 col-sm-6 col-12">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="name1">Direccion</label>
                            {{ Form::text('direccion',$equipo->cliente->direccion,array('class'=>'form-control','readonly')) }}
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="name1">Modelo</label>
                            {{ Form::text('modelo',$equipo->modelo,array('class'=>'form-control','readonly')) }}
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="name1">Fecha</label>
                            {{ Form::date('fecha',\Carbon\Carbon::parse($data->created_at)->format('Y-m-d'),array('class'=>'form-control','readonly')) }}
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="name1">Persona encargada</label>
                            @if(isset($otrosCampos[2]))
                            {{ Form::text('nombre',$otrosCampos[2],array('class'=>'form-control')) }}
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="name1">N° de Serie</label>
                            {{ Form::text('serie',$equipo->serie,array('class'=>'form-control','readonly')) }}
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="name1">Equipo</label>
                            {{ Form::text('numero_parte',$equipo->numero_parte,array('class'=>'form-control','readonly')) }}
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="name1">Hora Entrada</label>
                            @if(isset($otrosCampos[0]))
                            {{ Form::text('hora_entrada',$otrosCampos[0],array('class'=>'form-control','disabled')) }}
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="name1">Hora Salida</label>
                            @if(isset($otrosCampos[1]))
                            {{ Form::text('numero_parte',$otrosCampos[1],array('class'=>'form-control','readonly')) }}
                            @endif
                        </div>
                    </div>
                </div>
                <div class="section-title"></div>
                <div class="section-title"></div>
            </div>
        </div>
    </div>
    {{ Form::model($formulario, array('route' => array('equipos.update_tecnical_support', $data->id), 'method' => 'PUT' , 'role' => 'form','class'=>'form-horizontal','files'=>true)) }}
        {{ Form::hidden('equipo_id',$equipo->id,array('required')) }}
        {{ Form::hidden('formulario_id',$formulario->id,array('required')) }}
        {{ Form::hidden('formulario_registro_id',$data->id,array('required')) }}
        @include('frontend.partials.form_filled',array('formulario'=>$formulario,'datos'=>$datos))
        <div class="modal-footer {{$data->firmas_completas()}}">
            @if($data->firmas_completas()>0 and current_user()->isOnGroup('SupervisorC'))
            @include('frontend.partials.btnSubmit')
            @endif
        </div>
    {{ Form::close() }}


@stop
