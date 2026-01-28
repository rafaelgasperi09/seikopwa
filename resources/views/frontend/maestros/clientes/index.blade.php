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
                                <a href="{{route('maestros.clientes.edit',['id'=>$d->id,'estado'=>$d->estado])}}" class="btn btn-success btn-sm mr-1" title="Editar">
                                    <ion-icon name="pencil-outline" role="img" class="md hydrated" aria-label="pencil outline"></ion-icon>
                                </a>
                               @if(\Sentinel::hasAccess('maestros.clientes.delete') and $d->estado=='A')
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
        @php
        $checked='';
        if(request()->get('eliminados')=='true')
            $checked='checked="checked"';
        @endphp
        var button='<span style="float:right">\
            <div class="row">\
                <div class="col-md-6">\
                    <a href="{{route('maestros.clientes.create')}}" class="btn btn-success ">\
                    <ion-icon name="add-circle-outline"></ion-icon>Agregar nuevo\
                    </a>\
                </div>\
                 <div class="col-md-6">\
                   <div class="custom-control custom-switch col-12">\
                        <input name="eliminados"  type="checkbox" {{$checked}} class="custom-control-input eliminados" id="customSwitch_eliminados">\
                        <label class="custom-control-label" for="customSwitch_eliminados"></label>\
                        <div style="font-size:10px">Ver eliminados</div>\
                    </div>\
                </div>\
            </div>\
            </span>';
        $('.title').append(button);
        $('.eliminados').click(function(){
           // alert($(this).prop('checked'));
           window.location.href = "{{route('maestros.clientes.index')}}" + "?eliminados="+$(this).prop('checked');
        });
    </script>
@stop
