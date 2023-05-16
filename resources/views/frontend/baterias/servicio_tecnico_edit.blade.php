@extends('frontend.main-layout')
@section('content')
    @include('frontend.partials.title',array('title'=>'Registrar Carga Bateria','subtitle'=>$componente->id_componente))
    <!-------------------------------------------------------------------->
    {{Form::open(array("method" => "POST",'route' => array('baterias.serv_tec_update', $data->id),"role" => "form",'class'=>'form-horizontal','files'=>true))}}
    <div class="section full mt-2 mb-2">
        <div class="section-title">{{$formulario->titulo}}</div>
        <div class="wide-block pb-3 pt-2">
            <div class="row">
                <div class="col-4">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="name1">Compañia</label>
                            {{ Form::text('cliente',$componente->cliente->nombre,array('class'=>'form-control','readonly')) }}
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="name1">Contacto</label>
                            {{ Form::text('contacto',$componente->cliente->contacto,array('class'=>'form-control','readonly')) }}
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="name1">Fecha</label>
                            {{ Form::text('fecha',\Carbon\Carbon::now()->startOfWeek()->format('d-m-Y'),array('class'=>'form-control','readonly')) }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-8">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="name1">Direccion</label>
                            {{ Form::text('direccion',$componente->cliente->direccion,array('class'=>'form-control','readonly')) }}
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="name1">ID Bateria</label>
                            {{ Form::text('bateria_id',$componente->id_componente,array('class'=>'form-control','readonly')) }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-4">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="name1">Marca</label>
                            {{ Form::text('marca',$componente->marca,array('class'=>'form-control','readonly')) }}
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="name1">Modelo</label>
                            {{ Form::text('modelo',$componente->modelo,array('class'=>'form-control','readonly')) }}
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="name1">Serie</label>
                            {{ Form::text('serie',$componente->serie,array('class'=>'form-control','readonly')) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-------------------------------------------------------------------->
    
    
    <div class="section full mt-2 mb-2">
        <div class="section-title">Bateria : {{ $componente->voltaje }}V</div>
        <div class="wide-block pb-1 pt-2">
           
            {{ Form::hidden('componente_id',$componente->id) }}
            {{ Form::hidden('formulario_id',$formulario->id) }}
            @include('frontend.partials.form_filled',array('formulario'=>$formulario,'editable'=>true))
            <div class="modal-footer">
                @include('frontend.partials.btnSubmit')
            </div>
        </div>
    </div>
    {{ Form::close() }}
<script>
    
</script>
@stop
