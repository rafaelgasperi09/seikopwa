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
                <h3 class="name">{{ $data->getFullName() }}</h3>
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

    <div class="section full mt-1">
        <div class="wide-block pt-2 pb-2">
            @if(\Sentinel::hasAccess('usuarios.password'))
            <a href="#" class="btn btn-success" data-toggle="modal" data-target="#modal_change_password"
               data-photo="@empty(!$data->photo){{\Storage::url($data->photo)}}@endempty"
               data-action="{{ route('usuarios.update_password',$data->id) }}">
                    <ion-icon name="finger-print-outline" class="md hydrated" aria-label="logo android"></ion-icon>
                    Cambiar Contraseña
            </a>
            @endif
            <a href="#" class="btn btn-warning" data-toggle="modal" data-target="#modal_change_photo" data-action="{{ route('usuarios.update_photo',$data->id) }}">
                <ion-icon name="camera-outline" role="img" class="md hydrated" aria-label="logo apple"></ion-icon>
                Actualizar foto de perfil
            </a>
        </div>
    </div>
    <div class="section  full mt-2" data-toggle="collapse" href="#detail" aria-expanded="false">
        <div class="section-title">EDITAR PERFIL</div>
        <div class="wide-block pt-2 pb-2" id="profile">
            {{ Form::model($data, array('route' => array('usuarios.update', $data->id), 'method' => 'PUT' , 'role' => 'form','class'=>'form-horizontal')) }}
            <div class="section mt-2 mb-5">
                @if(\Sentinel::hasAccess('usuarios.editar_rol'))
                <div class="form-group boxed">
                    <div class="input-wrapper">
                        <label class="label" for="rol_id">Rol</label>
                        {{ Form::select('rol_id',$roles,$data->roles()->first()->id,array('class'=>'form-control','placeholder'=>'Selecionar rol','required')) }}
                        <i class="clear-input">
                            <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                        </i>
                    </div>
                </div>
                @endif
                <div class="form-group boxed">
                    <div class="input-wrapper">
                        <label class="label" for="email">Correo Electronico</label>
                        @if(\Sentinel::hasAccess('usuarios.update'))
                        {{ Form::email('email',null,array('class'=>'form-control','placeholder'=>'Correo Electrónico','required')) }}
                        @else
                            <span class="">{{$data->email}}</span>
                        @endif
                        <i class="clear-input">
                            <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                        </i>
                    </div>
                </div>
                <div class="form-group boxed">
                    <div class="input-wrapper">
                        <label class="label" for="first_name">Nombre</label>
                        @if(\Sentinel::hasAccess('usuarios.update'))
                        {{ Form::text('first_name',null,array('class'=>'form-control','placeholder'=>'Nombre','required')) }}
                        @else
                            <span class="">{{$data->first_name}}</span>
                        @endif
                        <i class="clear-input">
                            <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                        </i>
                    </div>
                </div>
                <div class="form-group boxed">
                    <div class="input-wrapper">
                        <label class="label" for="last_name">Apellido</label>
                        @if(\Sentinel::hasAccess('usuarios.update'))
                        {{ Form::text('last_name',null,array('class'=>'form-control','placeholder'=>'Apellido','required')) }}
                        @else
                            <span class="">{{$data->last_name}}</span>
                        @endif
                        <i class="clear-input">
                            <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                        </i>
                    </div>
                </div>
                @if(current_user()->isOnGroup('programador') || current_user()->isOnGroup('administrador') )
        
                            @include('frontend.partials.typeahead',array('field_label'=>'Cliente','field_name'=>'crm_cliente_id','items'=>$clientes,'valor_th'=>['',''],'small'=>'Esta selección sirve para determinar que equipos pertecen a este nuevo cliente usando la información del CRM.'))
   
                    @endif
            </div>
            <div class="modal-footer">
                @if(\Sentinel::hasAccess('usuarios.update'))
                    @include('frontend.partials.btnSubmit')
                @endif
            </div>
            {{ Form::close() }}
        </div>
    </div>
    @if(\Sentinel::hasAccess('usuarios.password'))
        @include('frontend.usuarios.modal_change_password')
    @endif
    @include('frontend.usuarios.modal_change_photo')
@stop
