@extends('frontend.main-layout')
@section('content')
    @include('frontend.partials.title',array('title'=>'Mantenimiento Preventivo','subtitle'=>'Equipo : '.$equipo->numero_parte))

    <div class="section full mt-2 mb-2">
        <div class="section-title">{{$formulario->titulo}} <small>{{ $formulario->nombre_menu }}</small></div>
        <div class="wide-block pb-3 pt-2">
            <div class="row">
                <div class="col-4">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="name1">Cliente</label>
                            {{ Form::text('cliente',$equipo->cliente->nombre,array('class'=>'form-control','readonly')) }}
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="name1">Forma</label>
                            {{ Form::text('form',$formulario->nombre_menu,array('class'=>'form-control','readonly')) }}
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="name1">Fecha</label>
                            {{ Form::text('fecha',$data->created_at,array('class'=>'form-control','readonly')) }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-4">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="name1">Marca</label>
                            {{ Form::text('marca',$equipo->marca->display_name,array('class'=>'form-control','readonly')) }}
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="name1">Modelo</label>
                            {{ Form::text('modelo',$equipo->modelo,array('class'=>'form-control','readonly')) }}
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="name1">Serie</label>
                            {{ Form::text('serie',$equipo->serie,array('class'=>'form-control','readonly')) }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="section-title">C => Correcto , A => Ajustar , R => Reparar , U => Urgente</div>
        </div>
    </div>
    {{ Form::model($data, array('route' => array('equipos.update_mant_prev', $data->id), 'method' => 'PUT' , 'role' => 'form','class'=>'form-horizontal')) }}
    {{ Form::hidden('equipo_id',$equipo->id,array('required')) }}
    {{ Form::hidden('formulario_id',$formulario->id,array('required')) }}
    {{ Form::hidden('formulario_registro_id',$data->id,array('required')) }}
    @include('frontend.partials.form',array('formulario'=>$formulario,'datos'=>$data))
    <div class="modal-footer">
        @include('frontend.partials.btnSubmit')
    </div>
    {{ Form::close() }}
@stop
