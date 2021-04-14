@extends('frontend.main-layout')
@section('content')
    <div class="section mt-2">
        <div class="profile-head">
            <div class="avatar">
                @empty(!$data->photo)
                    <img src="{{\Storage::url($data->photo)}}" alt="avatar" class="imaged w64 rounded">
                @else
                    <img src="assets/img/sample/avatar/avatar1.jpg" alt="avatar" class="imaged w64 rounded">
                @endempty
            </div>
            <div class="in">
                <h3 class="name">{{ $data->getFullName() }}</h3>
                <h5 class="subtext">Ult Ingreso : {{ \Carbon\Carbon::parse($data->last_login)->diffForHumans() }}</h5>
            </div>
        </div>
    </div>

    <div class="section full mt-1">
        <div class="wide-block pt-2 pb-2">
            <a href="#" class="btn btn-success" data-toggle="modal" data-target="#modal_change_password"
               data-photo="@empty(!$data->photo){{\Storage::url($data->photo)}}@endempty"
               data-action="{{ route('usuarios.update_password',$data->id) }}">
                    <ion-icon name="finger-print-outline" class="md hydrated" aria-label="logo android"></ion-icon>
                    Cambiar Contraseña
            </a>
            <a href="#" class="btn btn-warning" data-toggle="modal" data-target="#modal_change_photo" data-action="{{ route('usuarios.update_photo',$data->id) }}">
                <ion-icon name="camera-outline" role="img" class="md hydrated" aria-label="logo apple"></ion-icon>
                Actualizar foto de perfil
            </a>
        </div>
    </div>
    @include('frontend.partials.title',array('title'=>'Usuario','subtitle'=>'Editar perfil'))
    <div class="section full mt-2 mb-2">
        <div class="wide-block pb-1 pt-2">
            {{ Form::model($data, array('route' => array('usuarios.update', $data->id), 'method' => 'PUT' , 'role' => 'form','class'=>'form-horizontal')) }}
            <div class="section mt-2 mb-5">
                @if(current_user()->isOnGroup('administradores'))
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
                        {{ Form::email('email',null,array('class'=>'form-control','placeholder'=>'Correo Electrónico','required')) }}
                        <i class="clear-input">
                            <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                        </i>
                    </div>
                </div>
                <div class="form-group boxed">
                    <div class="input-wrapper">
                        <label class="label" for="first_name">Nombre</label>
                        {{ Form::text('first_name',null,array('class'=>'form-control','placeholder'=>'Nombre','required')) }}
                        <i class="clear-input">
                            <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                        </i>
                    </div>
                </div>
                <div class="form-group boxed">
                    <div class="input-wrapper">
                        <label class="label" for="last_name">Apellido</label>
                        {{ Form::text('last_name',null,array('class'=>'form-control','placeholder'=>'Apellido','required')) }}
                        <i class="clear-input">
                            <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                        </i>
                    </div>
                </div>
                <div class="form-group boxed">
                    <div class="input-wrapper">
                        <label class="label" for="crm_user_id">CRM ID</label>
                        {{ Form::number('crm_user_id',null,  array("class" => "form-control",'placeholder'=>'CRM ID'))  }}
                        <i class="clear-input">
                            <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                        </i>
                    </div>
                    <small style="color: red;">Este es el identificador unico del usuario con el mismo correo en el CRM</small>
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-sm btn-block">GUARDAR</button>
            {{ Form::close() }}
        </div>
    </div>
    @include('frontend.usuarios.modal_change_password')
    @include('frontend.usuarios.modal_change_photo')
@stop
