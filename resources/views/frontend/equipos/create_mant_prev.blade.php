@extends('frontend.main-layout')
@section('content')
    @include('frontend.partials.title',array('title'=>'Mantenimiento Preventivo','subtitle'=>'Equipo : '.$data->numero_parte))

    <div class="section full mt-2 mb-2">
        <div class="section-title">{{$formulario->titulo}} <small>{{ $formulario->nombre_menu }}</small></div>
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
                            <label class="label" for="name1">Forma</label>
                            {{ Form::text('form',$formulario->nombre_menu,array('class'=>'form-control','readonly')) }}
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="name1">Fecha</label>
                            {{ Form::text('fecha',\Carbon\Carbon::now()->format('d-m-Y'),array('class'=>'form-control','readonly')) }}
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
            <div class="section-title">C => Correcto , A => Ajustar , R => Reparar , U => Urgente</div>
        </div>
    </div>
    {{Form::open(array("method" => "POST","action" => "EquiposController@storeMantPrev","role" => "form",'class'=>'form-horizontal'))}}
    {{ Form::hidden('equipo_id',$data->id,array('required')) }}
    {{ Form::hidden('formulario_id',$formulario->id,array('required')) }}
    @include('frontend.partials.form',array('formulario'=>$formulario))
    <button type="submit" class="btn btn-primary btn-sm btn-block">GUARDAR</button>
    {{ Form::close() }}

    <script>

    </script>
@stop
