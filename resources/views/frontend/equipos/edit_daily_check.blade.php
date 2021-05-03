@extends('frontend.main-layout')
@section('content')
    @include('frontend.partials.title',array('title'=>'Daily Check','subtitle'=>$equipo->numero_parte))

    <div class="section full mt-2 mb-2">
        <div class="section-title">{{$formulario->titulo}}</div>
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
                            <label class="label" for="name1">Direccion</label>
                            {{ Form::text('direccion',$equipo->cliente->direccion,array('class'=>'form-control','readonly')) }}
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

                            {{ Form::text('turno_chequeo_diario',$data->turno_chequeo_diario,array('class'=>'form-control','readonly')) }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="section-title">NOTA:Este formulario debe hacerse por el operdor del equipo diariamente al comienzo del turno. Ciertos articulos enumerados no son incluidos en algunos modelos. Compruebe todos los articulos aplicables a su unidad.</div>
            <div class="section-title">OK => Buen Estado , M => Mal Estado , R => Revisar</div>
        </div>
    </div>
    {{ Form::model($data, array('route' => array('equipos.update_daily_check', $data->id), 'method' => 'PUT' , 'role' => 'form','class'=>'form-horizontal')) }}
    {{ Form::hidden('equipo_id',$data->id,array('required')) }}
    {{ Form::hidden('formulario_id',$formulario->id,array('required')) }}
    {{ Form::hidden('turno_chequeo_diario',$data->turno_chequeo_diario,array('required')) }}
    {{ Form::hidden('formulario_registro_id',$data->id,array('required')) }}
    @include('frontend.partials.form',array('formulario'=>$formulario,'datos'=>$data))
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary btn-sm btn-block">GUARDAR</button>
    </div>
    {{ Form::close() }}

<script>

</script>
@stop
