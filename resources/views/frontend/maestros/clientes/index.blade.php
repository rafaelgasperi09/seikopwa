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
                            @foreach($columns as $c)
                                @if(!in_array($c,['tipo_contacto','updated_at']))
                                    <td>
                                    @if($c=='zona_id')
                                        {{$d->zona->display_name}}
                                    @else
                                        {{$d->$c}}
                                    @endif
                                    </td>
                                @endif
                            @endforeach
                                    <td>
                                        <a href="{{route('maestros.clientes.edit',$d->id)}}" class="btn btn-success btn-sm mr-1" title="Editar">
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
            <a href="{{route('maestros.clientes.create')}}" class="btn btn-success ">\
                <ion-icon name="add-circle-outline"></ion-icon>Agregar nuevo\
                </a>';
        $('.title').append(button);
    </script>
@stop
