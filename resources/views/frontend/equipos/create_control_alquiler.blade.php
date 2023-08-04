@extends('frontend.main-layout')
@section('content')
    @include('frontend.partials.title',array('title'=>'Control de entregas montacarga par alquiler','subtitle'=>$data->id_componente))
    <div class="section full mt-2 mb-2">
        <!-------------------------------------------->
        {{Form::open(array("method" => "POST","action" => "BateriaController@guardarHidratacion","role" => "form",'class'=>'form-horizontal','files'=>true,'id'=>'form'))}}
            {{ Form::hidden('check_images','',['id'=>'check_images']) }}
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
            @php dd($data->tipo); @endphp
            
            {{ Form::hidden('componente_id',$data->id) }}
            {{ Form::hidden('formulario_id',$formulario->id) }}
            @include('frontend.partials.form',array('formulario'=>$formulario))
            <div class="section full mt-2 mb-2" id="seccion8">
                <div class="section-title"></div>
                <div class="wide-block pb-1 pt-2">
                    <div class="row">
                    
                        <div class="form-group boxed col-12">
                            <div class="input-wrapper text-center mx-auto">
                                <label class="label" for="comentarios">Marque con un gancho</label> 
                                <canvas height="800px" width="800px" id="imgmc"></canvas>
                            </div>
                        </div>
                        
                        <div class="form-group boxed col col-2 mx-auto">
                            <button name="clear" id="clearButton2" type="button" class=" btn btn-icon btn-outline-warning clearButton2 signButtons">
                                <ion-icon name="trash-outline" size="large"></ion-icon><br/>
                                Limpiar
                            </button>
                        </div>
                        <div class="form-group boxed col col-2 mx-auto">
                            <button name="type" type="button" data-dismiss="modal" id="signEnviar2" class=" btn btn-icon btn-outline-success signButtons">
                                <ion-icon name="checkmark-circle-outline" size="large"></ion-icon><br/>
                                Grabar imagen
                            </button>
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
    var canvasEquipo = document.getElementById("imgmc");
    var ctx3 = canvasEquipo.getContext("2d");
    function cargaImagen(){
        var img = new Image();
        img.src = "{{url('images/montacargas/800/combustion.png')}}";

        // Importante el onload
        img.onload = function(){
            ctx3.drawImage(img, 0, 0);
        }
    }

    cargaImagen();
    
    $('#clearButton2').on('click',function(){
        cargaImagen();
    });

    $("#signEnviar2").click(function() {
        var canvas2=$('#imgmc');
        var dataURL2 = canvas2[0].toDataURL(); 
        $('#check_images').val(dataURL2);
        console.log(dataURL);
	}) ;	
    function checkImage(canvasEquipo, event) {
        const rect = canvasEquipo.getBoundingClientRect()
        const x = event.clientX - rect.left-16
        const y = event.clientY - rect.top-16
        console.log("cLIC  x: " + x + " y: " + y);
        var imgChk = new Image();
        imgChk.src = "{{url('images/check.png')}}";

        // Importante el onload
        imgChk.onload = function(){
            ctx3.drawImage(imgChk, x, y);
        }
    }


     $(document).on('click','#imgmc',function(event){
    
        var coordenadas = $("#imgmc").offset();
        checkImage(canvasEquipo,event);
        console.log("Y: " + coordenadas.top + " X: " + coordenadas.left);
        console.log("Y: " + event.clientX + " X: " + event.clientY);
    });

    $(document).on('submit','#form',function(e){
        var canvas2=$('#imgmc');
        var dataURL2 = canvas2[0].toDataURL(); 
        $('#check_images').val(dataURL2);
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
