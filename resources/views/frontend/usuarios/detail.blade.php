@extends('frontend.main-layout')
@section('content')
    @include('frontend.partials.title',array('title'=>'Usuario','subtitle'=>'Sr(a) '.$data->getFullName()))
    <div class="divider mt-2 mb-3"></div>
    <div class="section  full mt-2" data-toggle="collapse" href="#detail" aria-expanded="false">
        <div class="section-title">Detalles
           <div class="right">
               @if(\Sentinel::hasAccess('usuarios.profile')  or $data->id == current_user()->id)
                   <a href="{{ route('usuarios.profile',$data->id) }}" class="btn btn-success">
                       <ion-icon name="person-outline" role="img" class="md hydrated" aria-label="videocam outline"></ion-icon>
                       Editar Perfil
                   </a>
               @endif
           </div>
        </div>
        <div class="wide-block pt-2 pb-2" id="detail">
            <dl class="row">
                <dt class="col-sm-3">Rol</dt>
                <dd class="col-sm-9">@if($data->roles()->first()){{ $data->roles()->first()->name }}@else N/A @endif</dd>
            </dl>
            <dl class="row">
                <dt class="col-sm-3">Nombre</dt>
                <dd class="col-sm-9">{{ $data->first_name }}</dd>
            </dl>
            @empty(!$data->last_name)
            <dl class="row">
                <dt class="col-sm-3">Apellido</dt>
                <dd class="col-sm-9">{{ $data->last_name }}</dd>
            </dl>
            @endempty
            <dl class="row">
                <dt class="col-sm-3">Correo Electrónico</dt>
                <dd class="col-sm-9">{{ $data->email }}</dd>
            </dl>
            <dl class="row">
                <dt class="col-sm-3">Ult Ingreso</dt>
                <dd class="col-sm-9">
                    @isset($data->last_login)
                        {{ \Carbon\Carbon::parse($data->last_login)->diffForHumans() }}
                    @else
                        NUNCA
                    @endisset</dd>
            </dl>
        @if($data->isCliente() && $data->cliente())
            <dl class="row">
                <dt class="col-sm-3">Cliente</dt>
                <dd class="col-sm-9">
                    {{ $data->cliente()->nombre }}
                    <small>( {{ $data->cliente()->direccion }} )</small>
                </dd>
            </dl>
            @endif
        </div>
    </div>

@stop
