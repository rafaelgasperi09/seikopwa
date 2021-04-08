@foreach($formulario->secciones()->get() as $seccion)
    <div class="section full mt-2 mb-2">
        <div class="section-title">{{ strtoupper($seccion->titulo) }}</div>
        <div class="wide-block pb-1 pt-2">
            <div class="row">
                @foreach($formulario->campos()->where('formulario_seccion_id',$seccion->id)->whereNotIn('nombre',['semana','dia_semana'])->get() as $campo)
                    <?php
                    $requerido ='';
                    if($campo->requerido) $requerido = 'required';
                    ?>
                    <div class="form-group boxed {{$campo->tamano}}">
                        <div class="input-wrapper">
                            <label class="label" for="{{ $campo->nombre }}">{{ $campo->etiqueta }}</label>
                            <small>{{ $campo->subetiqueta }}</small>
                            @if($campo->tipo == 'text')
                                {{ Form::text($campo->nombre,null,array('class'=>'form-control',$requerido)) }}
                            @elseif($campo->tipo == 'textarea')
                                {{ Form::textarea($campo->nombre,null,array('class'=>'form-control',$requerido)) }}
                            @elseif($campo->tipo == 'select')
                                {{ Form::select($campo->nombre,getFormularioSelectOpciones($campo->opciones),null,array('class'=>'form-control','id'=>$campo->nombre,$requerido)) }}
                            @elseif($campo->tipo == 'combo')
                                {{ Form::select($campo->nombre,getCombo($campo->tipo_combo,'Seleccione '.$campo->etiqueta),null,array('class'=>'form-control',$requerido)) }}
                            @elseif($campo->tipo == 'database')
                                @include('partials.typehead_field',array('field_label'=>$campo->etiqueta,'field_name'=>$campo->nombre,'items'=>getModelList('\App\\'.$campo->modelo,getFormDatabaseNameByModule('\App\\'.$campo->modelo))))
                            @elseif($campo->tipo == 'api')
                                <?php $api = new \App\HcaApi($campo->api_endpoint);?>
                                @include('partials.typehead_field',array('field_label'=>$campo->etiqueta,'field_name'=>$campo->nombre,'items'=>$api->result()))
                            @elseif($campo->tipo == 'date')
                                {{ Form::date($campo->nombre,null,array('class'=>'form-control date',$requerido,'date-format'=>$campo->formato_fecha)) }}
                            @elseif($campo->tipo == 'file')
                                {{ Form::file($campo->nombre,array('class'=>'form-control file','id'=>'archivo',$requerido)) }}
                                <div id="errorBlock" class="help-block"></div>
                            @elseif($campo->tipo == 'time')
                                {{ Form::time($campo->nombre,null,array('class'=>'form-control',$requerido)) }}
                            @elseif($campo->tipo == 'number')
                                {{ Form::number($campo->nombre,null,array('class'=>'form-control',$requerido)) }}
                            @elseif($campo->tipo == 'checkbox')
                                <label class="switch">
                                    <input type="checkbox" name="{{ $campo->nombre }}"/><span></span>
                                </label>
                            @elseif($campo->tipo == 'radio')
                                <div class="wide-block pt-2 pb-2">
                                    @php
                                        $i=0;
                                    @endphp
                                    @foreach(getFormularioRadioOpciones($campo->opciones) as $key=>$o)
                                        {{$o}}
                                        <div class="custom-control custom-radio d-inline">
                                            {{ Form::radio($campo->nombre,$o,null,array('class'=>'custom-control-input',$requerido,'id'=>$campo->nombre.$i)) }}

                                            <label class="custom-control-label p-0" for="{{ $campo->nombre }}{{$i}}"></label>
                                        </div>
                                        <?php $i++ ?>
                                    @endforeach
                                </div>
                            @elseif($campo->tipo == 'firma')
                                {{ Form::text($campo->nombre,null,array('class'=>'form-control',$requerido)) }}
                            @endif
                            <i class="clear-input">
                                <ion-icon name="{{ $campo->icono }}"></ion-icon>
                            </i>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endforeach
