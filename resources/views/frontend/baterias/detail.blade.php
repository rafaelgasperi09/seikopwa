@extends('frontend.main-layout')
@section('content')
@include('frontend.partials.title',array('title'=>$data->id_componente))
<div class="divider mt-2 mb-3"></div>
<div class="section  full mt-2" data-toggle="collapse" href="#detail" aria-expanded="false">
    <div class="section-title">Detalles</div>
    <div class="wide-block pt-2 pb-2" class="collapse" id="detail">
        <dl class="row">
            <dt class="col-sm-3">Marca</dt>
            <dd class="col-sm-9">{{ $data->marca }}</dd>
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
            <dt class="col-sm-3">Nro Celda</dt>
            <dd class="col-sm-9">{{ $data->numero_celda }}</dd>
        </dl>
        <dl class="row">
            <dt class="col-sm-3">Dimension</dt>
            <dd class="col-sm-9">{{ $data->dimension }}</dd>
        </dl>
        <dl class="row">
            <dt class="col-sm-3">Amperaje</dt>
            <dd class="col-sm-9">{{ $data->amperaje }}</dd>
        </dl>
        <dl class="row">
            <dt class="col-sm-3">Voltaje</dt>
            <dd class="col-sm-9">{{ $data->voltaje }}</dd>
        </dl>
        <dl class="row">
            <dt class="col-sm-3">Cliente</dt>
            <dd class="col-sm-9">{{ $data->cliente->nombre }}</dd>
        </dl>
    </div>
</div>
<div class="divider  mt-2 mb-3"></div>
<div class="section  full mt-2" data-toggle="collapse" href="#historial">
    <div class="section-title">Historial de carga</div>
    <div class="wide-block pt-2 pb-2" class="collapse" id="historial">
        @foreach($data->formmularioRegistros() as $r)
            @if($r->data(0->where))
            <div class="section full mt-2">
                <div class="section-title">Full Accordion</div>
                <div class="accordion" id="accordionExample1">
                    <div class="item">
                        <div class="accordion-header">
                            <button class="btn collapsed" type="button" data-toggle="collapse" data-target="#accordion1" aria-expanded="false">
                                About
                            </button>
                        </div>
                        <div id="accordion1" class="accordion-body collapse" data-parent="#accordionExample1" style="">
                            <div class="accordion-content">
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent at augue eleifend, lacinia
                                ex quis, condimentum erat. Nullam a ipsum lorem.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

@stop
