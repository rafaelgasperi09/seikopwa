@extends('frontend.main-layout')
@section('content')
    @include('frontend.partials.title',array('title'=>'Servicio tecnico','subtitle'=>$data->numero_parte))
    {{Form::open(array("method" => "POST","action" => "EquiposController@storeTecnicalSupport","role" => "form",'class'=>'form-horizontal',"autocomplete"=>"off",'files'=>true))}}
    <div class="section full mt-2 mb-2">
        <div class="section-title">{{$formulario->titulo}}</div>
        <div class="wide-block pb-3 pt-2">
            <div class="row">
                <div class="col-md-8 col-sm-6 col-12">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="name1">Cliente</label>
                            {{ Form::text('cliente',$data->cliente->nombre,array('class'=>'form-control','readonly')) }}
                            {{ Form::hidden('cliente_id',$data->cliente_id) }}
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="name1">Marca</label>
                            {{ Form::text('marca',$data->marca->display_name,array('class'=>'form-control','readonly')) }}
                        </div>
                    </div>
                </div>

                <div class="col-md-8 col-sm-6 col-12">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="name1">Direccion</label>
                            {{ Form::text('direccion',$data->cliente->direccion,array('class'=>'form-control','readonly')) }}
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="name1">Modelo</label>
                            {{ Form::text('modelo',$data->modelo,array('class'=>'form-control','readonly')) }}
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="name1">Fecha</label>
                            {{ Form::date('fecha',date('Y-m-d'),array('class'=>'form-control','readonly')) }}
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="name1">Persona encargada</label>
                            {{ Form::text('nombre',Sentinel::getUser()->first_name.' '.Sentinel::getUser()->last_name,array('class'=>'form-control')) }}
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="name1">N° de Serie</label>
                            {{ Form::text('serie',$data->serie,array('class'=>'form-control','readonly')) }}
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="name1">Equipo</label>
                            {{ Form::text('numero_parte',$data->numero_parte,array('class'=>'form-control','readonly')) }}
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="name1">Capacidad</label>
                            {{ Form::text('numero_parte',$data->voltaje,array('class'=>'form-control','readonly')) }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="section-title"></div>
            <div class="section-title"></div>
        </div>
    </div>

    {{ Form::hidden('equipo_id',$data->id) }}
    {{ Form::hidden('formulario_id',$formulario->id) }}
    @include('frontend.partials.form',array('formulario'=>$formulario))
    <button type="submit" class="btn mobile-height btn-primary btn-sm btn-block">
        <ion-icon name="save-outline"></ion-icon> GUARDAR
    </button>
    {{ Form::close() }}
@stop
