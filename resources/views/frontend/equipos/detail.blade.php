@extends('frontend.main-layout')

@section('content')
@include('frontend.partials.title',array('title'=>'Detalle de Equipos','subtitle'=>$data->numero_parte,'image'=>getEquipoIconBySubTipo($data->tipo_equipos_id)))
<div class="section full mt-2">
    <div class="accordion" id="detalle">
        <div class="item">
            <div class="accordion-header">
                <button class="btn" type="button" data-toggle="collapse" data-target="#accordion002" aria-expanded="false">
                    <ion-icon name="document-outline" role="img" class="md hydrated" aria-label="document outline"></ion-icon>
                    Detalles
                </button>
            </div>
            <div id="accordion002" class="accordion-body collapse {{$mostrar['det']}}" data-parent="#detalle" style="">
                <div class="accordion-content">
                        <div class="wide-block pt-2 pb-2" id="detail">
                            <dl class="row">
                                <dt class="col-sm-3">Estado</dt>
                                <dd class="col-sm-9">{{ $data->estado->display_name }}</dd>
                            </dl>
                            <dl class="row">
                                <dt class="col-sm-3">Tipo</dt>
                                <dd class="col-sm-9">{{ $data->tipo->display_name }}</dd>
                            </dl>
                            <dl class="row">
                                <dt class="col-sm-3">Marca</dt>
                                <dd class="col-sm-9">{{ $data->marca->display_name }}</dd>
                            </dl>
                            <dl class="row">
                                <dt class="col-sm-3">Modelo</dt>
                                <dd class="col-sm-9">{{ $data->modelo }}</dd>
                            </dl>
                            <dl class="row">
                                <dt class="col-sm-3">Serie</dt>
                                <dd class="col-sm-9">{{ $data->serie }}</dd>
                            </dl>
                            <dl class="row">
                                <dt class="col-sm-3">Capacidad de carga</dt>
                                <dd class="col-sm-9">{{ $data->capacidad_de_carga }}</dd>
                            </dl>
                            <dl class="row">
                                <dt class="col-sm-3">Voltaje</dt>
                                <dd class="col-sm-9">{{ $data->voltaje }}</dd>
                            </dl>
                            <dl class="row">
                                <dt class="col-sm-3">Mastil</dt>
                                <dd class="col-sm-9">{{ $data->mastil }}</dd>
                            </dl>
                            <dl class="row">
                                <dt class="col-sm-3">Cliente</dt>
                                <dd class="col-sm-9">{{ $data->cliente->nombre }}</dd>
                            </dl>
                        </div>
                </div>
            </div>
        </div>
        <div class="item">
            <div class="accordion-header">
                <button class="btn collapsed" type="button" data-toggle="collapse" data-target="#accordion003" aria-expanded="false">
                    <ion-icon name="list-outline" role="img" class="md hydrated" aria-label="list outline"></ion-icon>
                    Registros
                </button>
            </div>
            <div id="accordion003" class="accordion-body collapse {{$mostrar['reg']}}" data-parent="#detalle" style="">
                    <div class="accordion-content">
                        <ul class="nav nav-tabs style1 iconed" role="tablist">
                            @if(\Sentinel::hasAnyAccess(['equipos.create_daily_check','equipos.edit_daily_check']))
                            <li class="nav-item">
                                <a class="nav-link {{$tab['t1']}} " data-toggle="tab" href="#dailycheck" role="tab" aria-selected="true">
                                        <ion-icon name="list-outline" class="text-primary" role="img" class="md hydrated" aria-label="image outline"></ion-icon>
                                        Daily Check
                                </a>
                            </li>
                            @endif
                            @if(!empty($data->tipo_equipos_id) && \Sentinel::hasAnyAccess(['equipos.create_mant_prev','equipos.edit_mant_prev']))
                            <li class="nav-item">
                                <a class="nav-link  {{$tab['t2']}} " data-toggle="tab" href="#mant_prev" role="tab" aria-selected="true">
                                        <ion-icon name="hammer-outline" class="text-info" role="img" class="md hydrated" aria-label="image outline"></ion-icon>
                                        Mantenimiento Preventivo
                                </a>
                            </li>
                            @endif
                            @if(\Sentinel::hasAnyAccess(['equipos.create_tecnical_support','equipos.edit_tecnical_support']))
                            <li class="nav-item">
                                <a class="nav-link {{$tab['t3']}} " data-toggle="tab" href="#serv_tec" role="tab" aria-selected="true">
                                        <ion-icon name="alert-circle-outline" class="text-warning" role="img" class="md hydrated" aria-label="image outline"></ion-icon>
                                        Informe de Servicio Tecnico
                                </a>
                            </li>
                            @endif
                        </ul>
                        <div class="tab-content mt-1">
                        @if(\Sentinel::hasAnyAccess(['equipos.create_daily_check','equipos.edit_daily_check']))
                        <div class="tab-pane  {{$tab_content['t1']}} " id="dailycheck" role="tabpanel">
                            <div class="section full mt-1">
                                <div class="section-title">Daily Check
                                    @if(\Sentinel::hasAccess('equipos.create_daily_check'))
                                    <div class="right">
                                        <a href="{{ route('equipos.create_daily_check',$data->id) }}" class="btn btn-success" > <ion-icon name="add-circle-outline"></ion-icon> Nuevo Registro</a>
                                    </div>
                                    @endif
                                </div>
                                <div class="wide-block p-0">
                                    <div class="table-responsive">
                                    @include('frontend.equipos.daily_check_table',array('data'=>$form['dc'],'equipo'=>$data))
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(!empty($data->tipo_equipos_id) && \Sentinel::hasAnyAccess(['equipos.create_mant_prev','equipos.edit_mant_prev']))
                        <div class="tab-pane  {{$tab_content['t2']}}" id="mant_prev" role="tabpanel">
                            <div class="section full mt-1">
                                <div class="section-title">Mantenimiento Preventivo
                                    @if(!empty($data->tipo_equipos_id) && \Sentinel::hasAccess('equipos.create_mant_prev'))
                                    <div class="right">
                                        <a href="{{ route('equipos.create_mant_prev',[$data->id,$data->tipo_equipos_id])}}" class="btn btn-success" > <ion-icon name="add-circle-outline"></ion-icon> Nuevo Registro</a>
                                    </div>
                                    @endif
                                </div>

                                <div class="wide-block p-0">

                                <div class="table-responsive">
                                 @include('frontend.partials.listado_reportes',array('data'=>$form['mp'],'nombre'=>'mantenimiento_preventivo'))
                                </div>

                                </div>
                            </div>
                        </div>
                        @endif
                        @if(\Sentinel::hasAnyAccess(['equipos.create_tecnical_support','equipos.edit_tecnical_support']))
                        <div class="tab-pane   {{$tab_content['t3']}}" id="serv_tec" role="tabpanel">
                            <div class="section full mt-1">
                                <div class="section-title">Reporte Servicio Técnico
                                    @if(\Sentinel::hasAccess('equipos.create_tecnical_support'))
                                    <div class="right">
                                        <a href="{{ route('equipos.create_tecnical_support',$data->id) }}" class="btn btn-success" > <ion-icon name="add-circle-outline"></ion-icon> Nuevo Registro</a>
                                    </div>
                                    @endif
                                </div>
                                <div class="wide-block p-0">
                                    <div class="table-responsive">
                                    @include('frontend.partials.listado_reportes',array('data'=>$form['st'],'nombre'=>'form_montacarga_servicio_tecnico'))
                                    </div>

                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @include('frontend.equipos.modal_assign_tecnico')
            @include('frontend.equipos.modal_status_history')
        </div>
    </div>
</div>

@stop


