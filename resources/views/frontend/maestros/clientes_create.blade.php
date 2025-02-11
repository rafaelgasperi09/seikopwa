@extends('frontend.main-layout')
@section('content')
    @include('frontend.partials.title',array('title'=>'Maestros','subtitle'=>'Crear clientes'))
    <div class="container-fluid">
        <br/>
        <div class="container-fluid">
        <br>

                <div class="form-group boxed col-6">
                    <div class="input-wrapper">
                        <label class="label" for="nombre">NOMBRE</label>

                        <input class="form-control" id="nombre" name="nombre" type="text">
                        <i class="clear-input">
                        <ion-icon name="checkmark-outline" role="img" class="md hydrated" aria-label="checkmark outline"></ion-icon>
                        </i>
                    </div>
                </div>
                <div class="form-group boxed col-6">
                    <div class="input-wrapper">
                        <label class="label" for="contacto">CONTACTO</label>

                        <input class="form-control" id="contacto" name="contacto" type="text">
                        <i class="clear-input">
                        <ion-icon name="checkmark-outline" role="img" class="md hydrated" aria-label="checkmark outline"></ion-icon>
                        </i>
                    </div>
                </div>
                <div class="form-group boxed col-6">
                    <div class="input-wrapper">
                        <label class="label" for="telefono">TELEFONO</label>

                        <input class="form-control" id="telefono" name="telefono" type="phone">
                        <i class="clear-input">
                        <ion-icon name="checkmark-outline" role="img" class="md hydrated" aria-label="checkmark outline"></ion-icon>
                        </i>
                    </div>
                </div>
                <div class="form-group boxed col-6">
                    <div class="input-wrapper">
                        <label class="label" for="correo">CORREO</label>

                        <input class="form-control" id="correo" name="correo" type="email">
                        <i class="clear-input">
                        <ion-icon name="checkmark-outline" role="img" class="md hydrated" aria-label="checkmark outline"></ion-icon>
                        </i>
                    </div>
                </div>
                <div class="form-group boxed col-6">
                    <div class="input-wrapper">
                        <label class="label" for="direccion">DIRECCION</label>

                        <input class="form-control" id="direccion" name="direccion" type="text">
                        <i class="clear-input">
                        <ion-icon name="checkmark-outline" role="img" class="md hydrated" aria-label="checkmark outline"></ion-icon>
                        </i>
                    </div>
                </div>
                <div class="form-group boxed col-6">
                    <div class="input-wrapper">
                        <label class="label" for="pagina_web">PAGINA_WEB</label>

                        <input class="form-control" id="pagina_web" name="pagina_web" type="text">
                        <i class="clear-input">
                        <ion-icon name="checkmark-outline" role="img" class="md hydrated" aria-label="checkmark outline"></ion-icon>
                        </i>
                    </div>
                </div>
                <div class="form-group boxed col-6">
                    <div class="input-wrapper">
                        <label class="label" for="descripcion">DESCRIPCION</label>

                        {{ Form::textarea('descripcion',null,array('class'=>'form-control','id'=>'descripcion','maxlength'=>'100','rows'=>2)) }}
                        <i class="clear-input">
                        <ion-icon name="checkmark-outline" role="img" class="md hydrated" aria-label="checkmark outline"></ion-icon>
                        </i>
                    </div>
                </div>
                <div class="form-group boxed col-6">
                    <div class="input-wrapper">
                        <label class="label" for="zona_id">ZONA</label>
                        {{ Form::select('zona_id',\App\Zona::get()->pluck('display_name','id')->prepend('Seleccione'),request('zona_id'),array('class'=>'form-control','autocomplete'=>'off','id'=>'zona_id')) }} 
                        <i class="clear-input">
                        <ion-icon name="checkmark-outline" role="img" class="md hydrated" aria-label="checkmark outline"></ion-icon>
                        </i>
                    </div>
                </div>
            </div>
    </div>

@stop
