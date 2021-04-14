@extends('frontend.main-layout')
@section('content')
    @include('frontend.partials.title',array('title'=>'Usuario','subtitle'=>'Crear Nuevo'))
    <div class="section full mt-2 mb-2">
        <div class="wide-block pb-1 pt-2">
            {{Form::open(array("method" => "POST","action" => "UserController@store","role" => "form",'class'=>'form-horizontal'))}}
            <div class="section mt-2 mb-5">
                <div class="form-group boxed">
                    <div class="input-wrapper">
                        <label class="label" for="rol_id">Rol</label>
                        {{ Form::select('rol_id',$roles,null,array('class'=>'form-control','placeholder'=>'Selecionar rol','required')) }}
                        <i class="clear-input">
                            <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                        </i>
                    </div>
                </div>
                <div class="form-group boxed">
                    <div class="input-wrapper">
                        <label class="label" for="email">Correo Electrónico</label>
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
                        {{ Form::number('crm_user_id',null, array("class" => "form-control",'placeholder'=>'CRM ID'))  }}
                        <i class="clear-input">
                            <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                        </i>
                    </div>
                    <small style="color: red;">Este es el identificador unico del usuario con el mismo correo en el CRM</small>
                </div>
                <div class="divider  mt-2 mb-3"></div>
                <div class="form-group boxed">
                    <div class="input-wrapper">
                        <label class="label" for="password">Contraseña</label>
                        {{ Form::password('password',array('class'=>'form-control','placeholder'=>'Contraseña','required','autocomplete'=>'off')) }}
                        <i class="clear-input">
                            <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                        </i>
                    </div>
                </div>
                <div class="form-group boxed">
                    <div class="input-wrapper">
                        <label class="label" for="password_confirmation">Confirmar Contraseña</label>
                        {{ Form::password('password_confirmation',  array("class" => "form-control",'placeholder'=>'Confirmar Contraseña','required','autocomplete'=>'off'))  }}
                        <i class="clear-input">
                            <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                        </i>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-sm btn-block">GUARDAR</button>
            {{ Form::close() }}
        </div>
    </div>
@stop
