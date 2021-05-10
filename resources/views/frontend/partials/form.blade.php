@php $remove='';@endphp
@foreach($formulario->secciones()->get() as $key=>$seccion)
    <div class="section full mt-2 mb-2" id='seccion{{$key}}'>
        <div class="section-title">{{ strtoupper($seccion->titulo) }}</div>
        <div class="wide-block pb-1 pt-2">
            <div class="row">
                @php $campos=false;$firmas=0; @endphp
                @foreach($formulario->campos()->where('formulario_seccion_id',$seccion->id)->get() as $campo)
                    @if(mostrarCampo($campo->tipo))
                        @php
                        $requerido ='';
                        $readonly='';
                        $part = "$campo->permiso";
                        $value=null;
                        if($campo->requerido) $requerido = 'required';
                        if(!Sentinel::getUser()->hasAccess($campo->permiso)){
                             $readonly='disabled';
                        }
                        if(isset($datos)) {
                              $vd = $datos->data()->where('formulario_campo_id',$campo->id)->first();
                              if($vd) $value = $vd->valor;
                        }

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
                                    {{ Form::date($campo->nombre,$value,array('class'=>'form-control date',$requerido,'date-format'=>$campo->formato_fecha,'id'=>$campo->nombre,$readonly)) }}
                                @elseif($campo->tipo == 'file')
                                    {{ Form::file($campo->nombre,array('class'=>'form-control file','id'=>'archivo',$requerido,'id'=>$campo->nombre,$readonly)) }}
                                    <div id="errorBlock" class="help-block"></div>
                                @elseif($campo->tipo == 'camera')
                                    <img id="photo_{{ $campo->nombre}}" class="photo" width="100%"><br/>
                                    {{ Form::file($campo->nombre,array('class'=>'form-control file','id'=>'archivo',$requerido,'id'=>$campo->nombre,$readonly,'accept'=>'image/*','capture'=>'camera')) }}
                                   <script>
                                       const photo_{{ $campo->id }} = document.querySelector('#photo_{{ $campo->nombre }}');
                                       const camera_{{ $campo->id }} = document.querySelector('#{{ $campo->nombre }}');
                                       camera_{{ $campo->id }}.addEventListener('change', function(e) {
                                          photo_{{ $campo->id }}.src = URL.createObjectURL(e.target.files[0]);
                                       });
                                   </script>
                                @elseif($campo->tipo == 'time')
                                    {{ Form::time($campo->nombre,$value,array('class'=>'form-control',$requerido,'id'=>$campo->nombre,$readonly)) }}
                                @elseif($campo->tipo == 'number')
                                    {{ Form::number($campo->nombre,$value,array('class'=>'form-control',$requerido,'id'=>$campo->nombre,$readonly)) }}
                                @elseif($campo->tipo == 'checkbox')

                                    <div class="custom-control custom-switch col-4">
                                        <input name="{{ $campo->nombre }}" {{ $readonly }}type="checkbox" class="custom-control-input" id="customSwitch_{{ $campo->nombre }}">
                                        <label class="custom-control-label" for="customSwitch_{{ $campo->nombre }}"></label>
                                    </div>

                                @elseif($campo->tipo == 'radio')
                                    <div class="wide-block pt-2 pb-2">
                                        @php
                                            $i=0;
                                            $checked='';
                                        @endphp

                                        @foreach(getFormularioRadioOpciones($campo->opciones) as $key=>$o)

                                            <div class="custom-control custom-radio d-inline">
                                             @if($value == $o)
                                                {{ Form::radio($campo->nombre,$o,$value,array('class'=>'custom-control-input',$requerido,'id'=>$campo->nombre.$i,$checked,$readonly)) }}
                                             @else
                                                {{ Form::radio($campo->nombre,$o,null,array('class'=>'custom-control-input',$requerido,'id'=>$campo->nombre.$i,$readonly)) }}
                                             @endif
                                            <label class="custom-control-label p-0" for="{{ $campo->nombre }}{{$i}}">{{$o}}</label>
                                            </div>
                                            <?php $i++;
                                            $checked=''; ?>
                                        @endforeach
                                    </div>
                                @elseif($campo->tipo == 'firma'  and \Sentinel::hasAccess($campo->permiso))
                                    <div id="grupo_{{$campo->nombre}}">
                                        <img id="img_{{$campo->nombre}}" width="100%" style="max-width:550px" data-toggle="modal" data-target="#signModal" data-field="{{$campo->nombre}}">
                                        <button type="button" id="btn{{$campo->nombre}}" class="signRequest align-self-center"  data-toggle="modal" data-target="#signModal" data-field="{{$campo->nombre}}">
                                            <ion-icon name="pencil-outline" size="large"></ion-icon>
                                            <span>Haga clic para colocar firma </span>
                                        </button>
                                        {{ Form::hidden($campo->nombre,'',['id'=>$campo->nombre]) }}
                                    </div>
                                @endif
                                <i class="clear-input">
                                    <ion-icon name="{{ $campo->icono }}"></ion-icon>
                                </i>
                            </div>
                        </div>

                        @php $campos=true; @endphp
                    @endif
                @endforeach
                @include('frontend.partials.firma')
            </div>
        </div>
    </div>
    @php
    if(!$campos){
        $remove.="$('#seccion$key').remove();";
    }
    @endphp
@endforeach
<script>
    {!!$remove!!}
</script>
