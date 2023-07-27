@extends('frontend.main-layout')
@section('content')
@include('frontend.partials.title',array('title'=>'Formularios','subtitle'=>'Formularios >  Listado de formularios','route_back'=>route('equipos.index')))

<script>
    $('.header-large-title>.title').append(' <div style="position:position: absolute;right: 10px;top:18%;">\
                                                <a id="crear" type="button" class="btn btn-success btn-rounded btn-condensed btn-sm pull-right"   href="{{route('formularios.create')}}" target="" id="export_btn" > <ion-icon name="add-circle-outline"></ion-icon> Crear</a>\
                                            </div>');       
</script>
<div class="row" style="overflow:hidden">
    <div class="col-md-12" style="padding:20px">
        <table class="table datatable table-bordered table-striped table-actions">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>NOMBRE</th>
                    <th>NOMBRE MENU</th>
                    <th>CREADO</th>
                    <th>TIPO</th>
                    <th>ACCIONES</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $d)
                    <tr>
                        <td> {{$d['id']}}  </td>
                        <td> {{$d['nombre']}}  </td>
                        <td> {{$d['nombre_menu']}}  </td>
                        <td> {{\Carbon\Carbon::parse($d['created_at'])->format('d-M-Y')}}  </td>
                        <td> {{$d['tipo']}}  </td>
                        <td>   <a id="crear" type="button" class="btn btn-success btn-rounded btn-condensed btn-sm pull-right"   href="{{route('formularios.edit',$d['id'])}}" target="" id="export_btn" > <ion-icon name="create-outline"></ion-icon> Editar</a></td>
                    </tr>
                @endforeach
            </tbody>

        </table>

    </div>
</div>
<div class="row">
    <div class=""><br/></div>
</div>
<script>
  $('.datatable').DataTable( {
                "language": {
                    processing: '<i style="position: fixed;left: 50%;top:50%;" class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
                },
                "responsive": true,
                "autoWidth": true,
                "order": [[ 0, "desc" ]],
                "processing": true,                
            });

</script>
@stop
