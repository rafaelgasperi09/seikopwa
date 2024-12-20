@php $remove='';$linea=0;$showclear=true;  $create=true;@endphp
@foreach($formulario->secciones()->get() as $key=>$seccion)
    <div class="section full mt-2 mb-2" id='seccion{{$key}}'>
        <div class="section-title">{{ $seccion->titulo }}</div>
        <div class="wide-block pb-1 pt-2">
            <div class="row">
                @php $campos=false;$firmas=0;   @endphp
                @foreach($formulario->campos()->where('formulario_seccion_id',$seccion->id)->orderBy('orden')->orderBy('id')->get() as $campo)
                   
                    @if(mostrarCampo($campo->tipo))
                        @php
                        $requerido ='';
                        $readonly='';
                        $part = "$campo->permiso";
                        $value=null;
                        $showclear=true;
                        $files=array();
                        if($campo->requerido) $requerido = 'required';
                        $readonly='disabled';
                        if(isset($datos[$campo->nombre])) {
                            $value =$datos[$campo->nombre];
                            $create=false;
                            if($campo->tipo=='firma' and $value=='')
                                $readonly='';
                        }
                        if($campo->tipo=='files')
                            $files = explode(',',$value);
                        @endphp
                        <div class="form-group boxed {{$campo->tamano}}">
                            <div class="input-wrapper">
                                @if(!in_array($campo->tipo,['api']))
                                <label class="label" for="{{ $campo->nombre }}">{{ $campo->etiqueta }}</label>
                                <small>{{ $campo->subetiqueta }}</small>
                                @endif
                                @if($campo->tipo == 'hidden')
                                    {{ Form::hidden($campo->nombre,$value) }}
                                @elseif($campo->tipo == 'text')
                                    {{ Form::text($campo->nombre,$value,array('class'=>'form-control',$requerido,'id'=>$campo->nombre,$readonly)) }}
                                @elseif($campo->tipo == 'textarea')
                                    {{ Form::textarea($campo->nombre,$value,array('class'=>'form-control',$requerido,'id'=>$campo->nombre,$readonly)) }}
                                @elseif($campo->tipo == 'select')
                                    {{ Form::select($campo->nombre,getFormularioSelectOpciones($campo->opciones),$value,array('class'=>'form-control','id'=>$campo->nombre,$requerido,$readonly)) }}
                                @elseif($campo->tipo == 'combo')
                                    {{ Form::select($campo->nombre,getCombo($campo->tipo_combo,'Seleccione '.$campo->etiqueta),$value,array('class'=>'form-control',$requerido,$readonly)) }}
                                @elseif($campo->tipo == 'database')
                                    @php 
                                    
                                    $db_nombre=$campo->database_nombre; $db_id=$campo->database_nombre; 
                                    if(strpos($campo->database_nombre, ',')!== false){
                                        $campo_db=explode(',',$campo->database_nombre);
                                        $db_id=$campo_db[0]; 
                                        $db_nombre=$campo_db[1];                                            
                                    }
                                    $dat=getModelList('\App\\'.$campo->modelo,$db_id,$db_nombre);
                                      
                                    if($campo->nombre=='supervisor_id'){
                                    
                                        $where="crm_clientes_id ='$data->cliente_id' or crm_clientes_id like '%,$data->cliente_id%' or crm_clientes_id like '%$data->cliente_id,%'";
                                        $dat=getModelList('\App\\'.$campo->modelo,$db_id,$db_nombre,' el supervisor',$where);
                                        
                                    }

                                    @endphp
                                    @if(in_array($campo->nombre,['supervisor_id','tecnico_id']) and isset($dat[$value]))
                                    {{ Form::text($campo->nombre,$dat[$value],array('class'=>'form-control',$requerido,'id'=>$campo->nombre,$readonly)) }}
                                    @else
                                    @include('frontend.partials.typeahead',array('field_label'=>$campo->etiqueta,$readonly,$requerido,'field_name'=>$campo->nombre,'items'=>$dat))
                                    @endif
                                @elseif($campo->tipo == 'api')
                                    <?php $api = new \App\HcaApi($campo->api_endpoint);?>
                                    @include('frontend.partials.typeahead',array('field_label'=>$campo->etiqueta,'field_name'=>$campo->nombre,'items'=>$api->result(),$readonly))
                                @elseif($campo->tipo == 'date')
                                    @if($campo->opciones=='hoy' and $value==null)
                                    {{ Form::date($campo->nombre,date('Y-m-d'),array('class'=>'form-control date',$requerido,'date-format'=>$campo->formato_fecha,'id'=>$campo->nombre,'readonly')) }}
                                    @php $showclear=false; @endphp
                                    @else
                                    {{ Form::date($campo->nombre,$value,array('class'=>'form-control date',$requerido,'date-format'=>$campo->formato_fecha,'id'=>$campo->nombre,$readonly)) }}
                                    @endif
                                @elseif($campo->tipo == 'file')
                                    <div class="custom-file-upload">
                                        {{ Form::file($campo->nombre,array('class'=>'form-control file','id'=>'archivo',$requerido,'id'=>$campo->nombre,$readonly,'accept'=>".png, .jpg, .jpeg")) }}
                                        <label for="{{ $campo->nombre }}" id="{{ $campo->nombre }}" style="background-image: url({{ url('storage/equipos/'.$value) }});background-size: cover;background-repeat: no-repeat;background-position: center;">
                                            <span>
                                                <strong>
                                                    <ion-icon name="cloud-upload-outline" role="img" class="md hydrated" aria-label="cloud upload outline"></ion-icon>
                                                    <i>Subir foto</i>
                                                </strong>
                                            </span>
                                        </label>
                                    </div>
                                @elseif($campo->tipo == 'files')
                                    
                                    <div class="row">
                                        @foreach($files as $file)
                                            @if($file<>'')
                                            <div class="col-4 mb-2">
                                                @if(!empty($value))
                                                <a href="{{ url('/storage/equipos/'.$file) }}" download title="Clic para descargar"><img src="{{ url('/storage/equipos/'.$file) }}" alt="image" class="imaged w-100"></a>
                                                @endif
                                            </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @elseif($campo->tipo == 'camera')
                                    <div class="row">
                                       
                                        <div class="col-4 mb-2">
                                            @if(!empty($value))
                                            <a href="{{ url('/storage/equipos/'.$value) }}" download title="Clic para descargar"><img src="{{ url('/storage/equipos/'.$value) }}" alt="image" class="imaged w-100"></a>
                                            @endif
                                        </div>
                                    
                                    </div>
                                    
                                @elseif($campo->tipo == 'time')
                                    @if($campo->opciones=='hora' and $value==null)
                                        {{ Form::time($campo->nombre,date('H:i:s'),array('class'=>'form-control',$requerido,'id'=>$campo->nombre,'readonly')) }}
                                    @else
                                        {{ Form::time($campo->nombre,$value,array('class'=>'form-control',$requerido,'id'=>$campo->nombre,$readonly)) }}
                                    @endif
                                @elseif($campo->tipo == 'number')
                                    @if(in_array($campo->nombre,[ 'horometro','lectura_horometro']))
                                        @php $min=$data->ult_horometro()+1;@endphp
                                        {{ Form::number($campo->nombre,$value,array('class'=>'form-control',$requerido,'id'=>$campo->nombre,$readonly,'min'=>$min)) }}
                                    @else
                                        {{ Form::number($campo->nombre,$value,array('class'=>'form-control',$requerido,'id'=>$campo->nombre,$readonly)) }}
                                    @endif
                                @elseif($campo->tipo=='porcentaje')
                                    {{ Form::number($campo->nombre,$value,array('class'=>'form-control',$requerido,'id'=>$campo->nombre,$readonly,'min'=>0,'max'=>100)) }}
                                @elseif($campo->tipo == 'checkbox' and strlen($campo->opciones)=='')

                                    <div class="custom-control custom-switch col-4">
                                        <input name="{{ $campo->nombre }}" {{ $readonly }} type="checkbox" class="custom-control-input" id="customSwitch_{{ $campo->nombre }}">
                                        <label class="custom-control-label" for="customSwitch_{{ $campo->nombre }}"></label>
                                    </div>
                                @elseif($campo->tipo == 'checkbox' and $campo->opciones<>'')
                                    <div class="wide-block pt-2 pb-2">
                                         @php
                                            $i=0;
                                            $checked='';
                                            if(current_user()->isOnGroup('programador') && empty($value)) $value="C";
                                        @endphp
                                        @foreach(getFormularioRadioOpciones($campo->opciones) as $key=>$o)
                                            {{getOptionsRadio($o,$formulario->nombre)}}
                                            @php 
                                            if($value==$key){ $checked='checked';} 
                                            @endphp
                                            <div class="custom-control custom-checkbox d-inline">
                                                <input name="{{$campo->nombre.'[]'}}" type="checkbox" class="custom-control-input" value="{{$o}}" {{$checked}} {{$readonly}} id="{{$campo->nombre.$i}}">
                                                <label class="custom-control-label p-0" for="{{$campo->nombre.$i}}"></label>
                                            </div>
             
                                            <?php $i++;
                                            $checked=''; ?>
                                        @endforeach
                                    </div>

                                @elseif($campo->tipo == 'radio')
                                     @php
                                        $i=0;
                                        $linea++;
                                        $checked='';
                                        if(current_user()->isOnGroup('programador') && empty($value)) $value="C";
                                    @endphp

                                    <div class="wide-block pt-2 pb-2"  id="dcrow{{$linea}}">
                                        
                                        @foreach(getFormularioRadioOpciones($campo->opciones) as $key=>$o)

                                            <div class="custom-control custom-radio d-inline">

                                             @if($value == $o)
                                                {{ Form::radio($campo->nombre,$o,$value,array('class'=>'custom-control-input radiofield',$requerido,'id'=>$campo->nombre.$i,$checked,$readonly,'lineaId'=>$linea)) }}
                                             @else
                                                {{ Form::radio($campo->nombre,$o,null,array('class'=>'custom-control-input radiofield',$requerido,'id'=>$campo->nombre.$i,$readonly,'lineaId'=>$linea)) }}
                                             @endif
                                            <label class="custom-control-label p-0" for="{{ $campo->nombre }}{{$i}}">{{getOptionsRadio($o,$formulario->nombre)}}</label>
                                            </div>
                                            <?php $i++;
                                            $checked=''; ?>
                                        @endforeach
                                    </div>
                                @elseif($campo->tipo == 'firma')
                                    <input type="hidden" name="firma_hidden_field_{{$campo->nombre}}" value="{{$value}}" permiso="{{\Sentinel::hasAccess($campo->permiso)}}" supervisor="{{current_user()->isSupervisor('cliente')}}">
                                    @if($value=="") 
                                        @if(\Sentinel::hasAccess($campo->permiso) and (current_user()->isSupervisor() or  current_user()->isSupervisor('cliente')))   
                                        <div id="grupo_{{$campo->nombre}}" style="background: #ffffff;">
                                            <img id="img_{{$campo->nombre}}" width="100%" style="max-width:550px" data-toggle="modal" data-target="#signModal" data-field="{{$campo->nombre}}"  class="signRequest">
                                            <button type="button" id="btn{{$campo->nombre}}" class="signRequest align-self-center"  data-toggle="modal" data-target="#signModal" data-field="{{$campo->nombre}}">
                                                <ion-icon name="pencil-outline" size="large"></ion-icon>
                                                <span>Haga clic para colocar firma </span>
                                            </button>
                                            {{ Form::hidden($campo->nombre,'',['id'=>$campo->nombre]) }}
                                        </div>
                                        @endif
                                    @else
                                        @if(isset($creador[$campo->nombre]))
                                        <p><i>{{$creador[$campo->nombre]}}</i></p>
                                        @endif
                                        <img id="img_operador" width="100%" style="max-width:550px" data-toggle="modal"  data-field="operador" src="/storage/firmas/{{$value}}" class="imgSignRequest">
                                    @endif
                                @elseif($campo->tipo == 'montacarga_check')
                                    <img id="img_operador" width="100%" style="max-width:550px" data-toggle="modal"  data-field="operador" src="/storage/mccheck/{{$value}}" class="">
                                @endif
                                @if($showclear)
                                <i class="clear-input">
                                    <ion-icon name="{{ $campo->icono }}"></ion-icon>
                                </i>
                                @endif
                            </div>
                        </div>

                        @php $campos=true; @endphp
                    @endif
                @endforeach

            </div>
        </div>
    </div>
    @php
    if(!$campos){
        $remove.="$('#seccion$key').remove();";
    }
    @endphp
@endforeach

@include('frontend.partials.firmaNew')
<script>
    {!!$remove!!}
    @if($formulario->nombre=="form_montacarga_daily_check")
    $('.radiofield').click(function(){
        var val=$(this).val();
        var target="#dcrow"+$(this).attr('lineaId');
        if(val!='OK' && val!='N/A')
            {  
                $(target).addClass("bg-danger");
                //alert();
            }
        else{
               $(target).removeClass("bg-danger");
            }
            // alert(val);
    });
    @endif
</script>
