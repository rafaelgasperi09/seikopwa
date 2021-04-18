@extends('frontend.main-layout')
@section('content')
    @include('frontend.partials.title',array('title'=>'Usuario','subtitle'=>'Actualizar Contraseña'))

    <div class="section full mt-2 mb-2">
        <div class="wide-block pb-1 pt-2">
            <div class="alert alert-warning mb-1" role="alert">
                Su contraseña a expirado o es la primera vez que ingresa , para su mayor seguridad por favor actualice su contrasena a una de su preferencia.
            </div>
            {{ Form::model($data, array('route' => array('usuarios.update_password', $data->id), 'method' => 'PUT' , 'role' => 'form','class'=>'form-horizontal','id'=>'form_change_password')) }}
            <div class="modal-body text-left mb-2">
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
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary btn-sm btn-block">GUARDAR</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
@stop
