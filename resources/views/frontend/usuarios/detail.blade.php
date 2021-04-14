@extends('frontend.main-layout')
@section('content')
    @include('frontend.partials.title',array('title'=>'Usuario','subtitle'=>'Sr(a) '.$data->getFullName()))
    <div class="divider mt-2 mb-3"></div>
    <div class="section  full mt-2" data-toggle="collapse" href="#detail" aria-expanded="false">
        <div class="section-title">Detalles</div>
        <div class="wide-block pt-2 pb-2" id="detail">
            <dl class="row">
                <dt class="col-sm-3">Rol</dt>
                <dd class="col-sm-9">{{ $data->roles()->first()->name }}</dd>
            </dl>
            <dl class="row">
                <dt class="col-sm-3">Nombre</dt>
                <dd class="col-sm-9">{{ $data->first_name }}</dd>
            </dl>
            <dl class="row">
                <dt class="col-sm-3">Apellido</dt>
                <dd class="col-sm-9">{{ $data->last_name }}</dd>
            </dl>
            <dl class="row">
                <dt class="col-sm-3">Correo Electronico</dt>
                <dd class="col-sm-9">{{ $data->email }}</dd>
            </dl>
        </div>
    </div>

@stop
