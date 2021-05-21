@extends('frontend.main-layout')
@section('content')
<div class="login-form mt-1">
    <div class="section">
        <img src="{{ url('images/logo.png?t=123') }}" alt="image" class="form-image">
    </div>

    <div class="section mt-1">
        <h1>APP</h1>
        <h4>Coloque sus datos para ingresar</h4>
    </div>
    <div class="section mt-1 mb-5">
        {{Form::open(array("method" => "POST","action" => "LoginController@login","role" => "form",'class'=>'form-horizontal'))}}
            <div class="form-group boxed">
                <div class="input-wrapper">
                    {{ Form::email('login',null,array('class'=>'form-control','id'=>'email1','placeholder'=>'Correo Electronico')) }}

                    <i class="clear-input">
                        <ion-icon name="close-circle"></ion-icon>
                    </i>
                </div>
            </div>
            <div class="form-group boxed">
                <div class="input-wrapper">
                    <input type="password" class="form-control" name="password" id="password1" placeholder="Contraseña">
                    <i class="clear-input">
                        <ion-icon name="close-circle"></ion-icon>
                    </i>
                </div>
            </div>
            <div class="form-links mt-2">
                <div><a href="{{ url('forgot_password/create') }}" class="text-muted">Olvido su contraseña?</a></div>
            </div>
            <div class="form-button-group">
                <button type="submit" class="btn btn-primary btn-block btn-lg">Ingresar</button><br/>
            </div>
        {{ Form::close() }}
    </div>
</div>
@stop
