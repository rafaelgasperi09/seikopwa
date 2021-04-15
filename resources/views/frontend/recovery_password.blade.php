@extends('frontend.main-layout')
@section('content')
    <div class="login-form">
        <div class="section">
            <img src="{{ url('images/AppImages/android/icon-144x144.png?t=123') }}" alt="image" class="form-image">
        </div>
        <div class="section">
            <h1>Resetear Contraseña</h1>
        </div>
        <div class="section mt-2 mb-5">
            {{ Form::model(array(), array('route' => array('forgot_password.update', $id,$token), 'method' => 'PUT' , 'role' => 'form','class'=>'form-horizontal')) }}
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
            <div class="form-links mt-2 text-right">
                <div><a href="{{ url('/') }}" class="text-muted">Ingresar</a></div>
            </div>
            <div class="form-button-group">
                <button type="submit" class="btn btn-primary btn-block btn-lg">Recuperar</button>
            </div>

            {{ Form::close() }}
        </div>
    </div>
@stop
