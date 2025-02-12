@extends('frontend.main-layout')
@section('content')
    @include('frontend.partials.title',array('title'=>'Maestros','subtitle'=>'Listado de Equipos'))
    <div class="section full mt-1">
    <br/>
        <div class="table-responsive">
            <table class="table datatable  table-bordered table-striped table-actions">
                <thead>
                <tr>
                <tr>
                    <th>ID</th>
                    <th>NUMERO_PARTE</th>
                    <th>TIPO</th>
                    <th>MARCA</th>
                    <th>TIPO_EQUIPO</th>
                    <th>TIPO_MOTORE</th>
                    <th>MODELO</th>
                    <th>SERIE</th>
                    <th>ESTADO</th>
                    <th>MASTIL</th>
                    <th>TRUCK_DATA_NUMBER</th>
                    <th>VOLTAJE</th>
                    <th>NUMERO_PARTE_MOTOR_HIDRAULICO</th>
                    <th>NUMERO_PARTE_MOTOR_TRACCION</th>
                    <th>NUMERO_PARTE_MOTOR_DIRECCION</th>
                    <th>CAPACIDAD_DE_CARGA</th>
                    <th>FUNCION_HIDRAULICA_ID</th>
                    <th>GARANTIA_ACTIVACION</th>
                    <th>GARANTIA_CULMINACION</th>
                    <th>DIA_FABRICACION</th>
                    <th>MES_FABRICACION</th>
                    <th>ANNIO_FABRICACION</th>
                    <th>DIA_ADQUISICION</th>
                    <th>MES_ADQUISICION</th>
                    <th>ANNIO_ADQUISICION</th>
                    <th>CLIENTE</th>
                    <th>DESCRIPCION</th>
                    <th>TIPO_MASTIL_ID</th>
                    <th>NUMERO_MASTIL</th>
                    <th>ALTURA_MASTIL</th>
                    <th>PRECIO</th>
                    <th>DELETED_AT</th>
                    <th>PRECIO_ALQUILER</th>
                    <th>FECHA_INICIO_ALQUILER</th>
                    <th>STORAGE_OPERATION</th>
                    <th>CUENTA</th>
                    <th>TURNOS</th>
                    <th>CREATED_AT</th>
                    <th>UPDATED_AT</th>
                    <th>ACCIONES</th>
                </tr>
                </thead>
                <tbody>

                    @foreach($data as $d)
                        <tr>
                            <td>{{$d->id}}</td>
                            <td>{{$d->numero_parte}}</td>
                            <td>{{$d->subTipo->name}}</td>
                            <td>{{$d->marca->display_name}}</td>
                            <td>{{$d->tipo->display_name}}</td>
                            <td>{{$d->motor->display_name}}</td>
                            <td>{{$d->modelo}}</td>
                            <td>{{$d->serie}}</td>
                            <td>{{$d->estado->display_name}}</td>
                            <td>{{$d->mastil}}</td>
                            <td>{{$d->truck_data_number}}</td>
                            <td>{{$d->voltaje}}</td>
                            <td>{{$d->numero_parte_motor_hidraulico}}</td>
                            <td>{{$d->numero_parte_motor_traccion}}</td>
                            <td>{{$d->numero_parte_motor_direccion}}</td>
                            <td>{{$d->capacidad_de_carga}}</td>
                            <td>{{$d->funcion_hidraulica->display_name}}</td>
                            <td>{{$d->garantia_activacion}}</td>
                            <td>{{$d->garantia_culminacion}}</td>
                            <td>{{$d->dia_fabricacion}}</td>
                            <td>{{$d->mes_fabricacion}}</td>
                            <td>{{$d->annio_fabricacion}}</td>
                            <td>{{$d->dia_adquisicion}}</td>
                            <td>{{$d->mes_adquisicion}}</td>
                            <td>{{$d->annio_adquisicion}}</td>
                            <td>{{$d->cliente->nombre}}</td>
                            <td>{{$d->descripcion}}</td>
                            <td>{{$d->mastile->nombre}}</td>
                            <td>{{$d->numero_mastil}}</td>
                            <td>{{$d->altura_mastil}}</td>
                            <td>{{$d->precio}}</td>
                            <td>{{$d->deleted_at}}</td>
                            <td>{{$d->precio_alquiler}}</td>
                            <td>{{$d->fecha_inicio_alquiler}}</td>
                            <td>{{$d->storage_operation}}</td>
                            <td>{{$d->cuenta}}</td>
                            <td>{{$d->turnos}}</td>
                            <td>{{$d->created_at}}</td>
                            <td>{{$d->updated_at}}</td>
                            <td>
                                <a href="{{route('maestros.equipos.edit',$d->id)}}" class="btn btn-success btn-sm mr-1" title="Editar">
                                    <ion-icon name="pencil-outline" role="img" class="md hydrated" aria-label="pencil outline"></ion-icon>
                                </a>
                            </td>
                        </tr>
                    @endforeach
  
                </tbody>
            </table>
        </div>
    </div>
    <script>
        $('.datatable').DataTable({
            'order':['0','DESC'],
        });
        var button='<span style="float:right">\
            <a href="{{route('maestros.equipos.create')}}" class="btn btn-success ">\
                <ion-icon name="add-circle-outline"></ion-icon>Agregar nuevo\
                </a>';
        $('.title').append(button);
    </script>
@stop
