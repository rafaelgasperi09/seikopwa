@extends('frontend.main-layout')
@section('content')
    @include('frontend.partials.title',array('title'=>'Hidratación de Bateria #'.$data->id,'subtitle'=>$data->id_componente))
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
                                    {{ Form::text('amperaje',$componente->amperaje,array('class'=>'form-control','readonly')) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group basic">
                                <div class="input-wrapper">
                                    <label class="label" for="name1">Cliente</label>
                                    {{ Form::text('cliente',$componente->cliente->nombre,array('class'=>'form-control','readonly')) }}
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
                        <div class="col-4">
                            <div class="form-group basic">
                                <div class="input-wrapper">
                                    <label class="label" for="name1">Voltaje</label>
                                    {{ Form::text('voltaje',$componente->voltaje,array('class'=>'form-control','readonly')) }}
                                </div>
                            </div>
                        </div>
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
                    </div>
                </div>
            </div>
    
            <!-------------------------------------------->

           
            @include('frontend.partials.form_filled',array('data'=>$data,'formulario'=>$data->formulario,'editable'=>true))
            <div class="modal-footer">
             
            </div>
          
        </div>
    </div>
<script>
    /*
    $('#fecha').val('{{date('Y-m-d')}}');
    $('#fecha').attr('readonly','readonly');
    $('#hora_entrada').val('{{date('H:i')}}');
    $('#hora_entrada').attr('readonly','readonly');
    @if(current_user()->isOnGroup('tecnico'))
    $('#typehead_tecnico_id').val('{{current_user()->full_name}}');
    $('#tecnico_id').val('{{current_user()->id}}');
    @endif
    */
</script>
@stop
