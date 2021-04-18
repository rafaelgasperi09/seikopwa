@extends('frontend.main-layout')
@section('css')
    <link rel="stylesheet" href="{{ url('/plugins/jquery-datatable/css/roworder.css') }}">
    <link rel="stylesheet" href="{{ url('/plugins/jquery-datatable/css/responsive.css') }}">
@stop
@section('content')
@include('frontend.partials.title',array('title'=>'Baterias','subtitle'=>'Bateria :'.$data->id_componente))
<div class="divider mt-2 mb-3"></div>
<div class="section  full mt-2" data-toggle="collapse" href="#detail" aria-expanded="false">
    <div class="section-title">Detalle</div>
    <div class="wide-block pt-2 pb-2" id="detail">
        <dl class="row">
            <dt class="col-sm-3">Marca</dt>
            <dd class="col-sm-9">{{ $data->marca }}</dd>
        </dl>
        <dl class="row">
            <dt class="col-sm-3">Modelo</dt>
            <dd class="col-sm-9">{{ $data->modelo }}</dd>
        </dl>
        <dl class="row">
            <dt class="col-sm-3">Serie</dt>
            <dd class="col-sm-9">{{ $data->serie }}</dd>
        </dl>
        <dl class="row">
            <dt class="col-sm-3">Nro Celda</dt>
            <dd class="col-sm-9">{{ $data->numero_celda }}</dd>
        </dl>
        <dl class="row">
            <dt class="col-sm-3">Dimension</dt>
            <dd class="col-sm-9">{{ $data->dimension }}</dd>
        </dl>
        <dl class="row">
            <dt class="col-sm-3">Amperaje</dt>
            <dd class="col-sm-9">{{ $data->amperaje }}</dd>
        </dl>
        <dl class="row">
            <dt class="col-sm-3">Voltaje</dt>
            <dd class="col-sm-9">{{ $data->voltaje }}</dd>
        </dl>
        <dl class="row">
            <dt class="col-sm-3">Cliente</dt>
            <dd class="col-sm-9">{{ $data->cliente->nombre }}</dd>
        </dl>
    </div>
</div>
<div class="divider  mt-2 mb-3"></div>
<div class="section full mb-2">
    <div class="section-title">Historial Cargas</div>
    <div class="wide-block p-1" id="historial">
        <div class="table-responsive">
            <table class="table dataTables table-bordered table-striped table-actions">
                <thead>
                <tr>
                    <th>Accion</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Horometro Salida.</th>
                    <th>%Carga Salida.</th>
                    <th>Horometro Entrada.</th>
                    <th>%Carga Entrada.</th>
                    <th>Horas de Uso.</th>
                    <th>H2O</th>
                    <th>ECU</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {

        $('.dataTables').DataTable( {
            "language": {
                processing: '<div id="cargando"  align="center"><img src="{{ url("/assets/img/Spinner-3.gif") }}"></div>'
            },
            "columnDefs": [
                { "visible": false, "targets": [2] },
            ],
            "responsive": true,
            "order": [[ 2, "desc" ]],
            "processing": true,
            "serverSide": true,
            "autoWidth": false,
            "ajax": "{{ route('baterias.datatable',array('id'=>$data->id)) }}",
            "columns":[
                {data:'accion'},
                {data:'fecha'},
                {data:'hora_entrada'},
                {data:'horometro_salida_cuarto'},
                {data:'carga_salida_cuarto'},
                {data:'horometro_entrada_cuarto'},
                {data:'carga_entrada_cuarto'},
                {data:'horas_uso_bateria'},
                {data:'h2o'},
                {data:'ecu'},
            ]
        } );
    } );

</script>
@stop

@section('post_scripts')
    <script src="{{ url('/plugins/jquery-datatable/js/datatable.js') }}"></script>
    <script src="{{ url('/plugins/jquery-datatable/js/roworder.js') }}"></script>
    <script src="{{ url('/plugins/jquery-datatable/js/responsive.js') }}"></script>
@stop
