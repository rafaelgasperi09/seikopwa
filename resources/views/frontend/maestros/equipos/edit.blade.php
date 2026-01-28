@extends('frontend.main-layout')
@section('content')
    @include('frontend.partials.title', ['title' => 'Equipos', 'subtitle' => 'Crear equipo'])
    <div class="container-fluid">
        <br/>
        <div class="container-fluid">
        <br>
            {{ Form::open(['method' => 'POST', 'route' => ['maestros.equipos.update', $data->id], 'role' => 'form', 'class' => 'form-horizontal']) }}

                @php
                    $fields = [
                        'numero_parte' => 'NÚMERO DE PARTE',
                        'modelo' => 'MODELO',
                        'serie' => 'SERIE',
                        'mastil' => 'MÁSTIL',
                        'truck_data_number' => 'TRUCK DATA NUMBER',
                        'voltaje' => 'VOLTAJE',
                        'numero_parte_motor_hidraulico' => 'NÚMERO PARTE MOTOR HIDRÁULICO',
                        'numero_parte_motor_traccion' => 'NÚMERO PARTE MOTOR TRACCIÓN',
                        'numero_parte_motor_direccion' => 'NÚMERO PARTE MOTOR DIRECCIÓN',
                        'capacidad_de_carga' => 'CAPACIDAD DE CARGA',
                        'numero_mastil' => 'NÚMERO MÁSTIL',
                        'altura_mastil' => 'ALTURA MÁSTIL',
                        'precio' => 'PRECIO',
                        'precio_alquiler' => 'PRECIO ALQUILER',
                        'storage_operation' => 'STORAGE OPERATION',
                        'cuenta' => 'CUENTA',
                        'turnos' => 'TURNOS'
                    ];
                @endphp

                @foreach($fields as $name => $label)
                    <div class="form-group boxed col-6">
                        <div class="input-wrapper">
                            <label class="label" for="{{ $name }}">{{ $label }}</label>
                            <input class="form-control" id="{{ $name }}" name="{{ $name }}" type="text" value="{{ $data->$name }}">
                        </div>
                    </div>
                @endforeach

                @php
                    $dateFields = ['garantia_activacion' => 'GARANTÍA ACTIVACIÓN', 'garantia_culminacion' => 'GARANTÍA CULMINACIÓN', 'fecha_inicio_alquiler' => 'FECHA INICIO ALQUILER'];
                @endphp

                @foreach($dateFields as $name => $label)
                    <div class="form-group boxed col-6">
                        <div class="input-wrapper">
                            <label class="label" for="{{ $name }}">{{ $label }}</label>
                            <input class="form-control" id="{{ $name }}" name="{{ $name }}" type="date" value="{{ $data->$name }}">
                        </div>
                    </div>
                @endforeach

                @php
                    $selectFields = [
                        'marca_id' => ['MARCA', \App\Marca::pluck('display_name', 'id')],
                        'sub_equipos_id' => ['TIPO', \App\SubEquipo::pluck('name', 'id')],
                        'tipo_mastil_id' => ['TIPO MASTIL', \App\TipoMastil::pluck('nombre', 'id')],
                        'tipo_equipos_id' => ['TIPO DE EQUIPO', \App\TipoEquipo::pluck('display_name', 'id')],
                        'tipo_motore_id' => ['TIPO DE MOTOR', \App\TipoMotor::pluck('display_name', 'id')],
                        'estado_id' => ['ESTADO', \App\Estado::pluck('display_name', 'id')],
                        'funcion_hidraulica_id' => ['FUNCION HIDRÁULICA', \App\FuncionHidraulica::pluck('display_name', 'id')],
                        'cliente_id' => ['CLIENTE', \App\Cliente::pluck('nombre', 'id')],
                        'proveedor_id' => ['PROVEEDOR', \App\Cliente::pluck('nombre', 'id')]
                    ];
                @endphp

                @foreach($selectFields as $name => [$label, $options]).
                    @php $required='';
                    @endphp
                    <div class="form-group boxed col-6">
                        <div class="input-wrapper">
                            <label class="label" for="{{ $name }}">{{ $label }}</label>
                            {{ Form::select($name, $options->prepend('Seleccione', ''), $data->$name, ['class' => 'form-control', $required]) }}
                        </div>
                    </div>
                @endforeach

                <div class="form-group boxed col-6">
                    <div class="input-wrapper">
                        <label class="label" for="descripcion">DESCRIPCIÓN</label>
                        {{ Form::textarea('descripcion', $data->descripcion, ['class' => 'form-control', 'id' => 'descripcion', 'maxlength' => '255', 'rows' => 3]) }}
                    </div>
                </div>

                <div class="form-group boxed col-6">
                    <div class="input-wrapper">
                        <label class="label" for="estado">ESTADO</label>
                        {{ Form::select('estado', ['A' => 'Activo', 'I' => 'Inactivo'], $data->estado, ['class' => 'form-control', 'id' => 'estado', 'required']) }}
                        <i class="clear-input">
                            <ion-icon name="checkmark-outline" role="img" class="md hydrated" aria-label="checkmark outline"></ion-icon>
                        </i>
                    </div>
                </div>

                <div class="form-group boxed col-6 text-right">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <br/><br/><br/>
                </div>
            {{ Form::close() }}
        </div>
    </div>
@stop
