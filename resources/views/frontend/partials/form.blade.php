@php $remove='';$linea=0;$showclear=true;@endphp
@foreach($formulario->secciones()->get() as $key=>$seccion)
    <div class="section full mt-2 mb-2" id='seccion{{$key}}'>
        <div class="section-title">{{ $seccion->titulo }}</div>
        <div class="wide-block pb-1 pt-2">
            <div class="row">
                @php $campos=false;$firmas=0;   @endphp
                @foreach($formulario->campos()->where('formulario_seccion_id',$seccion->id)->get() as $campo)

                    @if(mostrarCampo($campo->tipo))
                        @php
                        $requerido ='';
                        $readonly='';
                        $part = "$campo->permiso";
                        $value=null;
                        $showclear=true;
                        $files=array();
                        if($campo->requerido) $requerido = 'required';
                        if(!Sentinel::getUser()->hasAccess($campo->permiso)){
                             $readonly='disabled';
                        }
                        
                        if(isset($datos)) {
                           
                              $vd = $datos->data()->where('formulario_campo_id',$campo->id)->first();
                              if($vd) $value = $vd->valor;
                        }
                        if($campo->tipo=='files')
                            $files = \App\File::whereTabla('formulario_registro')->whereRegistroId($campo->formulario_registro_id)->get();

                        @endphp
                        <div class="form-group boxed {{$campo->tamano}}">
                            <div class="input-wrapper">

                                <label class="label" for="{{ $campo->nombre }}">{{ $campo->etiqueta }}</label>
                                <small>{{ $campo->subetiqueta }}</small>
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
                                    @include('partials.typehead_field',array('field_label'=>$campo->etiqueta,$readonly,'field_name'=>$campo->nombre,'items'=>getModelList('\App\\'.$campo->modelo,getFormDatabaseNameByModule('\App\\'.$campo->modelo))))
                                @elseif($campo->tipo == 'api')
                                    <?php $api = new \App\HcaApi($campo->api_endpoint);?>
                                    @include('partials.typehead_field',array('field_label'=>$campo->etiqueta,'field_name'=>$campo->nombre,'items'=>$api->result(),$readonly))
                                @elseif($campo->tipo == 'date')
                                    @if($campo->opciones=='hoy' and $value==null)
                                    {{ Form::date($campo->nombre,date('Y-m-d'),array('class'=>'form-control date',$requerido,'date-format'=>$campo->formato_fecha,'id'=>$campo->nombre,'readonly')) }}
                                    @php $showclear=false; @endphp
                                    @else
                                    {{ Form::date($campo->nombre,$value,array('class'=>'form-control date',$requerido,'date-format'=>$campo->formato_fecha,'id'=>$campo->nombre,$readonly)) }}
                                    @endif
                                @elseif($campo->tipo == 'file')
                                    <div class="custom-file-upload">
                                        {{ Form::file($campo->nombre,array('class'=>'form-control file','id'=>'archivo',$requerido,'id'=>$campo->nombre,$readonly,'accept'=>'image/*')) }}
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
                                    <div class="">
                                        {{ Form::file($campo->nombre.'[]',array('class'=>'form-control file','id'=>'archivo',$requerido,'id'=>$campo->nombre,$readonly,'accept'=>'image/*','multiple')) }}

                                    </div>
                                    <div class="row">
                                        @foreach($files as $file)
                                        <div class="col-4 mb-2">
                                            <img src="{{ url($file->ruta) }}" alt="image" class="imaged w-100">
                                        </div>
                                        @endforeach
                                    </div>
                                @elseif($campo->tipo == 'camera')
                                    <div class="custom-file-upload">
                                        {{ Form::file($campo->nombre,array('class'=>'form-control file','id'=>'archivo',$requerido,'id'=>$campo->nombre,$readonly,'accept'=>'image/*','capture'=>'camera')) }}
                                        <label for="{{ $campo->nombre }}" id="{{ $campo->nombre }}" style="background-image: url({{ url('storage/equipos/'.$value) }});background-size: cover;background-repeat: no-repeat;background-position: center;">
                                            <span>
                                                <strong>
                                                    <ion-icon name="cloud-upload-outline" role="img" class="md hydrated" aria-label="cloud upload outline"></ion-icon>
                                                    <i>Tomar foto</i>
                                                </strong>
                                            </span>
                                        </label>
                                    </div>
                                @elseif($campo->tipo == 'time')
                                    @if($campo->opciones=='hora' and $value==null)
                                        {{ Form::time($campo->nombre,date('H:i:s'),array('class'=>'form-control',$requerido,'id'=>$campo->nombre,'readonly')) }}
                                    @else
                                        {{ Form::time($campo->nombre,$value,array('class'=>'form-control',$requerido,'id'=>$campo->nombre,$readonly)) }}
                                    @endif
                                @elseif($campo->tipo == 'number')
                                    @if(in_array($campo->nombre,[ 'horometro','lectura_horometro']))
                                        {{ Form::number($campo->nombre,$value,array('class'=>'form-control',$requerido,'id'=>$campo->nombre,$readonly,'min'=>$data->ult_horometro())) }}
                                    @else
                                        {{ Form::number($campo->nombre,$value,array('class'=>'form-control',$requerido,'id'=>$campo->nombre,$readonly)) }}
                                    @endif
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
                                            <div class="custom-control custom-checkbox d-inline">
                                                <input name="{{$campo->nombre.'[]'}}" type="checkbox" class="custom-control-input" value="{{$o}}" id="{{$campo->nombre.$i}}">
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
                                    @if($value=="")
                                        @if(\Sentinel::hasAccess($campo->permiso))
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
                                    <img id="img_operador" width="100%" style="max-width:550px" data-toggle="modal"  data-field="operador" src="/storage/firmas/{{$value}}" class="imgSignRequest">
                                    @endif
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
        if(val!='OK')
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
