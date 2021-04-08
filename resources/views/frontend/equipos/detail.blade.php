@extends('frontend.main-layout')

@section('content')
@include('frontend.partials.title',array('title'=>'Detalle de Equipos','subtitle'=>$data->numero_parte))
<div class="section full mt-2">
    <div class="accordion" id="detalle">
        <div class="item">
            <div class="accordion-header">
                <button class="btn collapsed" type="button" data-toggle="collapse" data-target="#accordion001" aria-expanded="false">
                    <ion-icon name="help-circle-outline" role="img" class="md hydrated" aria-label="help circle outline"></ion-icon>
                    About
                </button>
            </div>
            <div id="accordion001" class="accordion-body collapse" data-parent="#detalle" style="">
                <div class="accordion-content">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent at augue eleifend,
                    lacinia
                    ex quis, condimentum erat. Nullam a ipsum lorem.
                </div>
            </div>
        </div>

        <div class="item">
            <div class="accordion-header">
                <button class="btn" type="button" data-toggle="collapse" data-target="#accordion002" aria-expanded="false">
                    <ion-icon name="document-outline" role="img" class="md hydrated" aria-label="document outline"></ion-icon>
                    Detalles
                </button>
            </div>
            <div id="accordion002" class="accordion-body collapse show" data-parent="#detalle" style="">
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
            <div id="accordion003" class="accordion-body collapse" data-parent="#detalle" style="">
                <div class="accordion-content">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent at augue eleifend,
                    lacinia
                    ex quis, condimentum erat. Nullam a ipsum lorem.
                </div>
            </div>
        </div>


    </div>    
</div>

@stop


