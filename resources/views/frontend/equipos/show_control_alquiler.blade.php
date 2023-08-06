@extends('frontend.main-layout')
@section('content')
    @include('frontend.partials.title',array('title'=>'Control de entregas montacarga par alquiler','subtitle'=>$data->id_componente))
    <div class="section full mt-2 mb-2">
        <!-------------------------------------------->

    {{ Form::model($formulario, array('route' => array('equipos.update_control_entrega', $data->id), 'method' => 'PUT' , 'role' => 'form','class'=>'form-horizontal','files'=>true)) }}
            <div class="section full mt-2 mb-2">
                <div class="section-title">Datos generales</div>
                <div class="wide-block pb-3 pt-2">
                    <div class="row">
            
                        <div class="col-4">
                            <div class="form-group basic">
                                <div class="input-wrapper">
                                    <label class="label" for="name1">Fecha de entrega</label>
                                    {{ Form::text('fecha_entrega',$data->created_at,array('class'=>'form-control','readonly')) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group basic">
                                <div class="input-wrapper">
                                    <label class="label" for="name1">Cliente</label>
                                    {{ Form::text('cliente',$data->cliente()->nombre,array('class'=>'form-control','readonly')) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group basic">
                                <div class="input-wrapper">
                                    <label class="label" for="name1">Dirección</label>
                                    {{ Form::text('direccion',$data->cliente()->direccion,array('class'=>'form-control','readonly')) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group basic">
                                <div class="input-wrapper">
                                    <label class="label" for="name1">Tipo equipo</label>
                                    {{ Form::text('direccion',$data->equipo()->subTipo->display_name,array('class'=>'form-control','readonly')) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group basic">
                                <div class="input-wrapper">
                                    <label class="label" for="name1">Marca</label>
                                    {{ Form::text('direccion',$data->equipo()->marca->display_name,array('class'=>'form-control','readonly')) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group basic">
                                <div class="input-wrapper">
                                    <label class="label" for="name1">Serie</label>
                                    {{ Form::text('serie',$data->equipo()->serie,array('class'=>'form-control','readonly')) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group basic">
                                <div class="input-wrapper">
                                    <label class="label" for="name1">Altura</label>
                                    {{ Form::text('altura',$data->equipo()->altura_mastil,array('class'=>'form-control','readonly')) }}
                                </div>
                            </div>
                        </div>
             
                       
                        <div class="col-4">
                            <div class="form-group basic">
                                <div class="input-wrapper">
                                    <label class="label" for="name1"># Equipo</label>
                                    {{ Form::text('numero_parte',$data->equipo()->numero_parte,array('class'=>'form-control','readonly')) }}
                                    {{ Form::hidden('equipo_id',$data->equipo_id,array('class'=>'form-control','readonly')) }}
                                    {{ Form::hidden('formulario_registro_id',$data->id,array('class'=>'form-control','readonly')) }}
                                    {{ Form::hidden('cliente_id',$data->cliente_id,array('class'=>'form-control','readonly')) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group basic">
                                <div class="input-wrapper">
                                    <label class="label" for="name1">Modelo</label>
                                    {{ Form::text('modelo',$data->equipo()->modelo,array('class'=>'form-control','readonly')) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group basic">
                                <div class="input-wrapper">
                                    <label class="label" for="name1">Capacidad carga</label>
                                    {{ Form::text('capacidad_de_carga',$data->equipo()->capacidad_de_carga,array('class'=>'form-control','readonly')) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    
            <!-------------------------------------------->

            

            {{ Form::hidden('formulario_id',$formulario->id) }}
            @include('frontend.partials.form_filled',array('formulario'=>$formulario))
                              
            <div class="modal-footer">
                @if($data->firmas_completas()>0 )
                @include('frontend.partials.btnSubmit')
                @endif
            </div>
            {{ Form::close() }}
        </div>
    </div>


<script>

</script>
@stop
