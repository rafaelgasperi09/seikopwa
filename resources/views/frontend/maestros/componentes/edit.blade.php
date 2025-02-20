@extends('frontend.main-layout')
@section('content')
    @include('frontend.partials.title', ['title' => 'Componentes', 'subtitle' => 'Crear componente'])
    <div class="container-fluid">
        <br/>
        <div class="container-fluid">
            <br>
            {{ Form::open(['method' => 'POST', 'route' => array('maestros.componentes.update', $data->id), 'role' => 'form', 'class' => 'form-horizontal']) }}
            
            @php
                $clientes = \App\Cliente::pluck('nombre', 'id')->prepend('Seleccione', '');
                $subEquipos = \App\SubEquipo::pluck('name', 'id')->prepend('Seleccione', '');
                $proveedores = $clientes;
                $tipoComponentes = \App\TipoComponente::pluck('display_name', 'id')->prepend('Seleccione', '');
                $tipoFiltros = \App\TipoFiltro::pluck('display_name', 'id')->prepend('Seleccione', '');
                $tipoAditamentos = \App\TipoAditamento::pluck('display_name', 'id')->prepend('Seleccione', '');
                $tipoEquipoRuedas = \App\TipoEquipoRueda::pluck('display_name', 'id')->prepend('Seleccione', '');
                $tipoRuedas = \App\TipoRueda::pluck('display_name', 'id')->prepend('Seleccione', '');
            @endphp
            
            @foreach ([
                'marca' => 'MARCA',
                'modelo' => 'MODELO',
                'serie' => 'SERIE',
                'id_componente' => 'ID COMPONENTE',
                'numero_parte' => 'NÚMERO DE PARTE',
                'numero_celda' => 'NÚMERO DE CELDA',
                'numero_parte_baldwin' => 'NÚMERO PARTE BALDWIN',
                'numero_parte_millar' => 'NÚMERO PARTE MILLAR',
                'numero_parte_luberfiner' => 'NÚMERO PARTE LUBERFINER',
                'numero_parte_fram' => 'NÚMERO PARTE FRAM',
                'dimension' => 'DIMENSIÓN',
                'amperaje' => 'AMPERAJE',
                'voltaje' => 'VOLTAJE',
                'capacidad_carga' => 'CAPACIDAD DE CARGA',
                'peso' => 'PESO',
                'numero_modulo' => 'NÚMERO DE MÓDULO',
                'entrada_max' => 'ENTRADA MÁXIMA',
                'salida_max' => 'SALIDA MÁXIMA',
                'ubicacion' => 'UBICACIÓN',
                'codigo_superior' => 'CÓDIGO SUPERIOR',
                'codigo_rhino' => 'CÓDIGO RHINO',
                'codigo_thombert' => 'CÓDIGO THOMBERT',
                'sleeve' => 'SLEEVE',
                'balinera' => 'BALINERA',
                'pin_roll' => 'PIN ROLL',
                'bushing' => 'BUSHING',
                'axle' => 'AXLE',
                'flatwasher_uno' => 'FLATWASHER 1',
                'flatwasher_dos' => 'FLATWASHER 2',
                'flatwasher_tres' => 'FLATWASHER 3',
                'flatwasher_cuatro' => 'FLATWASHER 4',
                'fitting' => 'FITTING',
                'plate_pivot_lh' => 'PLATE PIVOT LH',
                'plate_pivot_rh' => 'PLATE PIVOT RH',
                'precio' => 'PRECIO',
                'precio_alquiler' => 'PRECIO ALQUILER'
            ] as $field => $label)
                <div class="form-group boxed col-6">
                    <div class="input-wrapper">
                        <label class="label" for="{{ $field }}">{{ $label }}</label>
                        <input value="{{$data->$field}}" class="form-control" id="{{ $field }}" name="{{ $field }}" type="{{ in_array($field, ['amperaje', 'voltaje', 'capacidad_carga', 'peso', 'precio', 'precio_alquiler']) ? 'number' : 'text' }}">
                    </div>
                </div>
            @endforeach
            
            @foreach ([
                'tipo_componente_id' => $tipoComponentes,
                'proveedor_id' => $proveedores,
                'sub_equipo_id' => $subEquipos,
                'tipo_filtro_id' => $tipoFiltros,
                'tipo_aditamento_id' => $tipoAditamentos,
                'tipo_equipo_rueda_id' => $tipoEquipoRuedas,
                'tipo_rueda_id' => $tipoRuedas,
                'cliente_id' => $clientes
            ] as $field => $options)
                <div class="form-group boxed col-6">
                    <div class="input-wrapper">
                        <label class="label" for="{{ $field }}">{{ strtoupper(str_replace('_', ' ', $field)) }}</label>
                        {{ Form::select($field, $options, $data->$field, ['class' => 'form-control', 'required']) }}
                    </div>
                </div>
            @endforeach
            
            @foreach ([
                'fecha_creacion' => 'FECHA CREACIÓN',
                'fecha_inicio_alquiler' => 'FECHA INICIO ALQUILER'
            ] as $field => $label)
                <div class="form-group boxed col-6">
                    <div class="input-wrapper">
                        <label class="label" for="{{ $field }}">{{ $label }}</label>
                        <input class="form-control" id="{{ $field }}" name="{{ $field }}" type="date" value="{{$data->$field}}">
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
                    <label class="label" for="zona_id">ESTADO</label>
                    {{ Form::select('estado',['A'=>'Activo','I'=>'Inactivo'],$data->estado,array('class'=>'form-control','autocomplete'=>'off','id'=>'estado','required')) }} 
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
