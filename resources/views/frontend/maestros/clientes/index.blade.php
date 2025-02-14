@extends('frontend.main-layout')
@section('content')
    @include('frontend.partials.title',array('title'=>'Maestros','subtitle'=>'Listado de clientes'))
    <div class="section full mt-1">
    <br/>
        <div class="table-responsive">
            <table class="table datatable table-bordered table-striped table-actions">
                <thead>
                <tr>
                    <th>ID</th>
                    <th width='20%'>NOMBRE</th>
                    <th  width='20%'>CONTACTO</th>
                    <th>TELEFONO</th>
                    <th>CORREO</th>
                    <th>DIRECCION</th>
                    <th>PAGINA_WEB</th>
                    <th>DESCRIPCION</th>
                    <th>CREATED_AT</th>
                    <th>ZONA</th>
                    <th>ACCIONES</th>
                </thead>
                <tbody>
                    @foreach($data as $d)
                        <tr>
                            <td>{{$d->id}}</td>
                            <td>{{$d->nombre}}</td>
                            <td>{{$d->contacto}}</td>
                            <td>{{$d->telefono}}</td>
                            <td>{{$d->correo}}</td>
                            <td>{{$d->direccion}}</td>
                            <td>{{$d->pagina_web}}</td>
                            <td>{{$d->descripcion}}</td>
                            <td>{{$d->created_at}}</td>
                            <td>{{$d->zona->display_name}}</td>
                            <td>
                                <a href="{{route('maestros.clientes.edit',$d->id)}}" class="btn btn-success btn-sm mr-1" title="Editar">
                                    <ion-icon name="pencil-outline" role="img" class="md hydrated" aria-label="pencil outline"></ion-icon>
                                </a>
                                @if(current_user()->id==1)
                                <a href="{{route('maestros.clientes.delete',$d->id)}}" class="btn btn-danger btn-sm mr-1" title="Eliminar">
                                    <ion-icon name="trash-outline" role="img" class="md hydrated" aria-label="pencil outline"></ion-icon>
                                </a>
                                @endif
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
            <a href="{{route('maestros.clientes.create')}}" class="btn btn-success ">\
                <ion-icon name="add-circle-outline"></ion-icon>Agregar nuevo\
                </a>';
        $('.title').append(button);
    </script>
@stop
