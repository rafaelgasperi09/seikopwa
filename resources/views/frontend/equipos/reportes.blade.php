@extends('frontend.main-layout')
@section('content')
<div class="row">
    <div class="col-md-10 " style="padding-left:30px">
        <h3 class="title ">
                Equipos &gt; Listado de reportes
        </h3>
    </div>
        <div class="col-md-2 text-right">
        <a id="mostrarfiltro" type="submit" class="btn btn-primary btn-rounded btn-condensed btn-sm pull-right"  data-toggle="collapse" href="#filtro" role="button" aria-expanded="false" aria-controls="filtro"><span class="fa fa-search"></span> Filtro</a>
        </div>
    </div>
<div class="row">
    <div class="col-md-12 text-right">
    @include('frontend.equipos.filtro')
    </div>
</div>
<div class="row">
    <div class="col-md-12" style="padding:20px">
        <table class="table datatable table-bordered table-striped table-actions">
            <thead>
            <tr>
                <th>#</th>
                <th>Fecha registro</th>
                <th>Tipo</th>
                <th>Equipo</th>
                <th>Registrado por</th>
                <th>Apellido</th>
                <th>Cliente</th>
                <th>Estatus</th>
                <th>Semana</th>
                <th>Dia</th>
                <th>Turno</th>
                <th>Actions</th>
            </tr>
            </thead>
        </table>

    </div>
</div>
<div class="row">
    <div class=""><br/></div>
</div>
<script>
   var obj = {
    @foreach(request()->all() as $k=>$v)
        {{$k}}: '{{$v}}',
    @endforeach
    }
  
    var parameters=new URLSearchParams(obj).toString();
    console.log(parameters);
  $('.datatable').DataTable( {
                "language": {
                    processing: '<i style="position: fixed;left: 50%;top:50%;" class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
                },
                
                "responsive": true,
                "autoWidth": false,
                "columnDefs": [
                    { "visible": false, "targets": [5] },
                ],
                "order": [[ 0, "desc" ]],
                "processing": true,
                "serverSide": true,
                "ajax": "{{ route('equipos.reportes_datatable') }}?"+parameters,
                "columns":[
                    {data:'id'},
                    {data:'created_at'},
                    {data:'tipo',name:'formularios.tipo'},
                    {data:'numero_parte',name:'equipos_vw.numero_parte'},
                    {data:'creado_por',name:'users.first_name'},
                    {data:'last_name',name:'users.last_name'},
                    {data:'nombre',name:'clientes_vw.nombre'},
                    {data:'estatus'},
                    {data:'semana'},
                    {data:'dia_semana'},
                    {data:'turno_chequeo_diario'},
                    {data:'actions'},
                ],
                
            });

</script>
@stop
