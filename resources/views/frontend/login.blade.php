@extends('frontend.main-layout')
@section('content')
<div class="login-form mt-1">
    <div class="section">
        <img src="{{ url('images/AppImages/android/icon-144x144.png?t=123') }}" alt="image" class="form-image">
    </div>

    <div class="section mt-1">
        <h1>Check</h1>
        <h4>Coloque sus datos para ingresar</h4>
        @if (Session::has('message.error'))
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                {!! nl2br(Session::get('message.error')) !!}
            </div>
        @endif
        @if (Session::has('message.success'))
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                {!! nl2br(Session::get('message.success')) !!}
            </div>
        @endif

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

            <div class="form-button-group">
                <button type="submit" class="btn btn-primary btn-block btn-lg">Ingresar</button><br/>
            </div>
        {{ Form::close() }}
    </div>
</div>
@stop
