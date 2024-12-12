@extends('frontend.main-layout')
@section('content')
@include('frontend.partials.title',array('title'=>'Equipos','subtitle'=>'Equipos >  Listado de Daily Check','route_back'=>route('equipos.index')))
<script>
    $('.header-large-title>.title').append(' <div style="position: absolute;right: 10px;top:18%;">\
                                                <a id="exporter" type="button" class="btn btn-success btn-rounded btn-condensed btn-sm pull-right"   href="{{route('equipos.daily_check_list_export')}}" target="_blank" id="export_btn" > <ion-icon name="download-outline"></ion-icon> Exportar a excel</a>\
                                                <a id="mostrarfiltro" type="button" class="btn btn-primary btn-rounded btn-condensed btn-sm pull-right"  data-toggle="collapse" href="#filtro" role="button" aria-expanded="false" aria-controls="filtro"> <ion-icon name="funnel-outline"></ion-icon> Filtro</a>\
                                            </div>');       
</script>
<div class="row">
    <div class="col-md-12 text-right">
    @include('frontend.equipos.filtro_daily_check')
    </div>
</div>
<div class="row">
    <div class="col-md-12" style="padding:20px">
        <table class="table datatable table-bordered table-striped table-actions">
            <thead>
            <tr>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Id Equipo</th>
                <th>Bodega</th>
                <th># de Reporte</th>
                <th>R (Revisar)</th>
                <th>M (Mal estado)</th>
                <th>Ok (Correcto)</th>
                <th>Prioridad</th>
                <th>Registrado por</th>
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
    $('#exporter').attr('href',"{{route('equipos.daily_check_list_export')}}?"+parameters);
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
                "ajax": "{{url('equipos/daily_check_list_datatable') }}?"+parameters,
                "columns":[
                    {data:'fecha'},
                    {data:'hora'},
                    {data:'equipo'},
                    {data:'cliente'},
                    {data:'reporte'},
                    {data:'valorr'},
                    {data:'valorm'},
                    {data:'valorok'},
                    {data:'prioridad'},
                    {data:'registrado_por'},
                ],
          
            });

</script>
@stop
