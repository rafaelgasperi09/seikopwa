@extends('frontend.main-layout')
@section('content')
    @include('frontend.partials.title',array('title'=>'Control de entregas montacarga par alquiler','subtitle'=>$data->id_componente))
    <div class="section full mt-2 mb-2">
            <!-------------------------------------------->
            <div class="section full mt-2 mb-2">
                <div class="section-title">Datos generales</div>
                <div class="wide-block pb-3 pt-2">
                    <div class="row">
            
                        <div class="col-4">
                            <div class="form-group basic">
                                <div class="input-wrapper">
                                    <label class="label" for="name1">Fecha de entrega</label>
                                    {{ Form::text('fecha_entrega',now(),array('class'=>'form-control','readonly')) }}
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
                                    <label class="label" for="name1">Dirección</label>
                                    {{ Form::text('direccion',$data->cliente->direccion,array('class'=>'form-control','readonly')) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group basic">
                                <div class="input-wrapper">
                                    <label class="label" for="name1">Tipo equipo</label>
                                    {{ Form::text('direccion',$data->subTipo->display_name,array('class'=>'form-control','readonly')) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group basic">
                                <div class="input-wrapper">
                                    <label class="label" for="name1">Marca</label>
                                    {{ Form::text('direccion',$data->marca->display_name,array('class'=>'form-control','readonly')) }}
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
                                    <label class="label" for="name1">Altura</label>
                                    {{ Form::text('altura',$data->altura_mastil,array('class'=>'form-control','readonly')) }}
                                </div>
                            </div>
                        </div>
             
                       
                        <div class="col-4">
                            <div class="form-group basic">
                                <div class="input-wrapper">
                                    <label class="label" for="name1"># Equipo</label>
                                    {{ Form::text('equipo_id',$data->numero_parte,array('class'=>'form-control','readonly')) }}
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
                                    <label class="label" for="name1">Capacidad carga</label>
                                    {{ Form::text('capacidad_de_carga',$data->capacidad_de_carga,array('class'=>'form-control','readonly')) }}
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
            <div class="section full mt-2 mb-2" id="seccion8">
                <div class="section-title"></div>
                <div class="wide-block pb-1 pt-2">
                    <div class="row">
                        <div class="form-group boxed col-12">
                            <div class="input-wrapper text-center">
                            <label class="label" for="comentarios">Marque con un gancho</label> 
                            <canvas height="800px" width="800px" id="imgmc"></canvas>
                            </div>
                        </div>
                        
                    </div>
                </div>
                </div>                       
            <div class="modal-footer">
                @include('frontend.partials.btnSubmit')
            </div>
            {{ Form::close() }}
        </div>
    </div>
<script>
    var canvas = document.getElementById("imgmc");
var ctx = canvas.getContext("2d");

var img = new Image();
img.src = "{{url('images/montacargas/800/combustion.png')}}";

// Importante el onload
img.onload = function(){
	ctx.drawImage(img, 0, 0);
}
    
    
     $(document).on('click','#imgmc',function(event){
        var coordenadas = $("#imgmc").offset();
        
    console.log("Y: " + coordenadas.top + " X: " + coordenadas.left);
    console.log("Y: " + event.clientX + " X: " + event.clientY);
    });
    $('#fecha').val('{{date('Y-m-d')}}');
    $('#fecha').attr('readonly','readonly');
    $('#hora_entrada').val('{{date('H:i')}}');
    $('#hora_entrada').attr('readonly','readonly');
    @if(current_user()->isOnGroup('tecnico'))
    $('#typehead_tecnico_id').val('{{current_user()->full_name}}');
    $('#tecnico_id').val('{{current_user()->id}}');   
    @endif

</script>
@stop
