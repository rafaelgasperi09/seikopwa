@extends('frontend.main-layout')
@section('content')
    @include('frontend.partials.title',array('title'=>'Registrar Carga Bateria','subtitle'=>$data->id_componente))
    <div class="section full mt-2 mb-2">
        <div class="section-title">Bateria : {{ $data->voltaje }}V</div>
        <div class="wide-block pb-1 pt-2">
            {{Form::open(array("method" => "POST","action" => "BateriaController@guardarEntredaSalida","role" => "form",'class'=>'form-horizontal'))}}
            {{ Form::hidden('componente_id',$data->id) }}
            {{ Form::hidden('formulario_id',$formulario->id) }}
            @include('frontend.partials.form',array('formulario'=>$formulario))
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary btn-sm btn-block">GUARDAR</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
<script>
    $('#horometro_salida_cuarto').parent().parent().hide();
    $('#carga_salida_cuarto').parent().parent().hide();
    $('#horometro_entrada_cuarto').parent().parent().hide();
    $('#carga_entrada_cuarto').parent().parent().hide();

    $('#accion').change(function (){

        console.log('vlue :'+$(this).val());
        if($(this).val() == ''){
            $('#horometro_salida_cuarto').parent().parent().hide();
            $('#carga_salida_cuarto').parent().parent().hide();
            $('#horometro_entrada_cuarto').parent().parent().hide();
            $('#carga_entrada_cuarto').parent().parent().hide();

        }else if($(this).val() == 'entrada'){
            $('#horometro_salida_cuarto').parent().parent().hide();
            $('#carga_salida_cuarto').parent().parent().hide();
            $('#horometro_entrada_cuarto').parent().parent().show();
            $('#carga_entrada_cuarto').parent().parent().show();
        }else{
            $('#horometro_salida_cuarto').parent().parent().show();
            $('#carga_salida_cuarto').parent().parent().show();
            $('#horometro_entrada_cuarto').parent().parent().hide();
            $('#carga_entrada_cuarto').parent().parent().hide();
        }
    })
</script>
@stop
