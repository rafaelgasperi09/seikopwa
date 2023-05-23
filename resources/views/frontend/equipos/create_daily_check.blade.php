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
                            <label class="label" for="name1">Fecha</label>
                            {{ Form::text('semana',transletaDate(date('Y-m-d')),array('class'=>'form-control','readonly')) }}
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
                            <label class="label" for="name1">Dia</label>
                            {{ Form::text('dayofweek',getDayOfWeek(date('N')),array('class'=>'form-control','readonly')) }}
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="name1">Turno</label>

                            {{ Form::text('turno_chequeo_diario',$turno,array('class'=>'form-control','readonly')) }}
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="name1">Semana   {{semana_rango(date('Y-m-d'),date('W'))}}</label>
                            {{ Form::text('semana',date('W'),array('class'=>'form-control','readonly')) }}
                          
                    </div>
                </div>
            </div>
            <div class="section-title">NOTA:Este formulario debe hacerse por el operador del equipo diariamente al comienzo del turno. Ciertos articulos enumerados no son incluidos en algunos modelos. Compruebe todos los articulos aplicables a su unidad.</div>
            <div class="section-title">OK => Buen Estado , M => Mal Estado , R => Revisar , N/A => No Aplica</div>
        </div>
    </div>
    {{Form::open(array("method" => "POST","action" => "EquiposController@storeDailyCheck","role" => "form",'class'=>'form-horizontal','files'=>true))}}
    {{ Form::hidden('equipo_id',$data->id) }}
    {{ Form::hidden('formulario_id',$formulario->id) }}
    {{ Form::hidden('turno_chequeo_diario',$turno) }}
    @include('frontend.partials.form',array('formulario'=>$formulario))
    <div class="modal-footer">
       @include('frontend.partials.btnSubmit')
    </div>
    {{ Form::close() }}

    
@stop
