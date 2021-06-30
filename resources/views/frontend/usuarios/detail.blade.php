@extends('frontend.main-layout')
@section('content')
    @include('frontend.partials.title',array('title'=>'Usuario','subtitle'=>'','route_back'=>route('usuarios.index')))
    <div class="section mt-2">
        <div class="profile-head">
            <div class="avatar">
                @empty(!$data->photo)
                    <img src="{{\Storage::url($data->photo)}}" alt="avatar" class="imaged w64 rounded">
                @else
                    <img src="{{ url('assets/img/user.png') }}" alt="avatar" class="imaged w64 rounded">
                @endempty
            </div>
            <div class="in">
                <h3 class="name">Sr(a) {{ $data->getFullName() }}</h3>
                <h5 class="subtext">
                    Ult Ingreso :
                    @isset($data->last_login)
                        {{ \Carbon\Carbon::parse($data->last_login)->diffForHumans() }}
                    @else
                        NUNCA
                    @endisset
                </h5>
            </div>
        </div>
    </div>
    @if(\Sentinel::hasAccess('usuarios.profile')  or $data->id == current_user()->id)
    <div class="section full mt-1">
        <div class="wide-block pt-2 pb-2">
            <a href="{{ route('usuarios.profile',$data->id) }}" class="btn btn-success">
                <ion-icon name="person-outline" role="img" class="md hydrated" aria-label="videocam outline"></ion-icon>
                Editar Perfil
            </a>
        </div>
    </div>
    @endif
    <div class="section  full mt-2" data-toggle="collapse" href="#detail" aria-expanded="false">
        <div class="section-title">DETALLE</div>
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
