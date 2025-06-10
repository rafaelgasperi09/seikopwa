@extends('frontend.main-layout')
@section('content')
@include('frontend.partials.title',array('title'=>'Logs de Acceso','subtitle'=>'Seguridad >  Logs de Acceso','route_back'=>route('equipos.index')))

<div class="row">
     @if(\Sentinel::hasAccess('usuarios.logs_csv'))
    <div class="col-md-12 text-right">
        <a href="{{ route('usuarios.logs_csv') }}" class="btn btn-success btn-rounded btn-condensed btn-sm pull-right" id="exportbtn">
        <ion-icon name="download-outline" role="img" class="md hydrated" aria-label="download outline"></ion-icon>
        Exportar Logs CSV
        </a>
        <a id="mostrarfiltro" type="button" class="btn btn-primary btn-rounded btn-condensed btn-sm pull-right" data-toggle="collapse" href="#filtro" role="button" aria-expanded="true" aria-controls="filtro"> <ion-icon name="funnel-outline" role="img" class="md hydrated" aria-label="funnel outline"></ion-icon> Filtro</a>
    @endif
    </div>
</div>
<div class="row">
    <div class="col-md-12 text-right">
    @include('frontend.usuarios.filtro')
    </div>
</div>
<div class="row">
    <div class="col-md-12" style="padding:20px">
        <table class="table datatable table-bordered table-striped table-actions">
            <thead>
            <tr>
                <th>#</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Ip</th>
                <th>Ususario</th>
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
    $('#exporter').attr('href',"{{url('equipos/reportes_export')}}?"+parameters);
    console.log(parameters);
    console.log(parameters);
  $('.datatable').DataTable( {
                "language": {
                    processing: '<i style="position: fixed;left: 50%;top:50%;" class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
                },
                "responsive": true,
                "autoWidth": false,
                "order": [[ 0, "desc" ]],
                "processing": true,
                "serverSide": true,
                "ajax": "{{url('usuarios/logs_datatable') }}?"+parameters,
                "columns":[
                    {data:'id'},
                    {data:'fecha'},
                    {data:'hora'},
                    {data:'ip_address'},
                    {data:'usuario'},
                ],
          
            });

</script>
@stop
