@extends('frontend.main-layout')
@section('content')
    @include('frontend.partials.title',array('title'=>'Registrar Carga Bateria','subtitle'=>$data->id_componente))
    <div class="section full mt-2 mb-2">
        <div class="section-title">Bateria : {{ $data->voltaje }}V</div>
        <div class="wide-block pb-1 pt-2">
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
