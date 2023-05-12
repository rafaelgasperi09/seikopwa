@extends('frontend.main-layout')
@section('css')
    <link rel="stylesheet" href="{{ url('/plugins/jquery-datatable/css/roworder.css') }}">
    <link rel="stylesheet" href="{{ url('/plugins/jquery-datatable/css/responsive.css') }}">
@stop
@section('content')
@include('frontend.partials.title',array('title'=>'Baterias','subtitle'=>'Bateria :'.$data->id_componente))
<div class="divider mt-2 mb-3"></div>
<div class="section  full mt-2" data-toggle="collapse" href="#detail" aria-expanded="false">
    <div class="section-title">Detalle

    </div>
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
<div class="section full mb-2" id='detalle'>
   
    <div class="wide-block p-1" id="historial">
        <ul class="nav nav-tabs style1 iconed" role="tablist">
            @if(\Sentinel::hasAnyAccess(['equipos.see_daily_check','equipos.edit_daily_check']))
            <li class="nav-item">
                <a class="nav-link {{$tab[0]}}" id="tab1" data-toggle="tab" href="#historial_cargas" role="tab" aria-selected="true">
                        <ion-icon name="list-outline" class="text-primary" role="img" class="md hydrated" aria-label="image outline"></ion-icon>
                        Historial Cargas (Cuarto de maquinas)
                </a>
            </li>
            @endif
            @if(!empty($data->tipo_equipos_id) or \Sentinel::hasAnyAccess(['equipos.see_mant_prev','equipos.create_mant_prev','equipos.edit_mant_prev']))
            <li class="nav-item">
                <a class="nav-link {{$tab[1]}}" data-toggle="tab" id="tab2" href="#servicio_tecnico" role="tab" aria-selected="true">
                        <ion-icon name="hammer-outline" class="text-info" role="img" class="md hydrated" aria-label="image outline"></ion-icon>
                        Servicio Tecnico
                </a>
            </li>
            @endif
        </ul>
        <div class="tab-content mt-1">
            <div class="tab-pane  {{$tab[0]}} tab1" id="historial_cargas" role="tabpanel">
                <div class="section-title">
                    <div class="right">
                        <a href="{{ route('baterias.download',$data->id) }}" target="_blank" class="btn btn-primary" > <ion-icon name="download-outline"></ion-icon> Descargar PDF</a>
                        <a href="{{ route('baterias.download',$data->id) }}?format=excel" target="_blank" class="btn btn-primary" > <ion-icon name="download-outline"></ion-icon> Descargar Excel</a>
                        @if(Sentinel::getUser()->hasAccess('baterias.register_in_and_out'))
                        <a href="{{ route('baterias.register_in_and_out',$data->id) }}" class="btn btn-success" > <ion-icon name="add-circle-outline"></ion-icon> Nuevo Registro</a>
                        @endif
                    </div>
                </div>
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
                            <th>Estado de bateria</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div class="tab-pane {{$tab[1]}}" id="servicio_tecnico" role="tabpanel">
            </div>
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
                {data:'estado_de_bateria'},
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
