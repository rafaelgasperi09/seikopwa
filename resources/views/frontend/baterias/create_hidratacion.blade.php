@extends('frontend.main-layout')
@section('content')
    @include('frontend.partials.title',array('title'=>'Registrar Hidratación de Bateria','subtitle'=>$data->id_componente))
    <div class="section full mt-2 mb-2">
        <div class="section-title">Bateria : {{ $data->voltaje }}V</div>
            <!-------------------------------------------->
            <div class="section full mt-2 mb-2">
                <div class="section-title">Datos generales</div>
                <div class="wide-block pb-3 pt-2">
                    <div class="row">
                       
                        <div class="col-4">
                            <div class="form-group basic">
                                <div class="input-wrapper">
                                    <label class="label" for="name1">Amp</label>
                                    {{ Form::text('amperaje',$data->amperaje,array('class'=>'form-control','readonly')) }}
                                </div>
                            </div>
                        </div>
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
                                    <label class="label" for="name1">Serie</label>
                                    {{ Form::text('serie',$data->serie,array('class'=>'form-control','readonly')) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group basic">
                                <div class="input-wrapper">
                                    <label class="label" for="name1">Voltaje</label>
                                    {{ Form::text('voltaje',$data->voltaje,array('class'=>'form-control','readonly')) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group basic">
                                <div class="input-wrapper">
                                    <label class="label" for="name1">Marca</label>
                                    {{ Form::text('marca',$data->marca,array('class'=>'form-control','readonly')) }}
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
                    </div>
                </div>
            </div>
    
            <!-------------------------------------------->

            {{Form::open(array("method" => "POST","action" => "BateriaController@guardarHidratacion","role" => "form",'class'=>'form-horizontal','files'=>true))}}
            {{ Form::hidden('componente_id',$data->id) }}
            {{ Form::hidden('formulario_id',$formulario->id) }}
            @include('frontend.partials.form',array('formulario'=>$formulario))
            <div class="modal-footer">
                @include('frontend.partials.btnSubmit')
            </div>
            {{ Form::close() }}
        </div>
    </div>
<script>
    $('#fecha').val('{{date('Y-m-d')}}');
    $('#fecha').attr('readonly','readonly');
    $('#hora_entrada').val('{{date('H:i')}}');
    $('#hora_entrada').attr('readonly','readonly');

</script>
@stop
