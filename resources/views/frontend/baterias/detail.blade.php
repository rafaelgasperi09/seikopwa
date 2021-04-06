@extends('frontend.main-layout')

@section('content')
@include('frontend.partials.title',array('title'=>'Detalle Bateria','subtitle'=>$data->id_componente))
<div class="divider mt-2 mb-3"></div>
<div class="section  full mt-2" data-toggle="collapse" href="#detail" aria-expanded="false">
    <div class="section-title">Detalles</div>
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
    <div class="wide-block p-0" id="historial">
        <div class="table-responsive">
            <table class="table dataTables table-striped">
                <thead>
                <tr>
                    <th scope="col">Fecha</th>
                    <th scope="col">Hora</th>
                    <th scope="col">H Sal.</th>
                    <th scope="col">%Carga Sal.</th>
                    <th scope="col">H Ent.</th>
                    <th scope="col">%Carga Ent.</th>
                    <th scope="col">H Uso.</th>
                    <th scope="col">H2O</th>
                    <th scope="col">ECU</th>
                </tr>
                </thead>
                <tbody>
                @foreach($data->formmularioRegistros() as $r)
                    <tr  scope="row">
                    @foreach($r->data()->whereNotIn('tipo',['select','textarea'])->get() as $campo)
                        <th>{{ $campo->valor }}</th>
                    @endforeach
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        var table = $('.dataTables').DataTable( {
            rowReorder: {
                selector: 'td:nth-child(2)'
            },
            responsive: true
        } );
    } );
</script>
@stop

@section('post_scripts')
    <script src="{{ url('/plugins/jquery-datatable/js/datatable.js') }}"></script>
    <script src="{{ url('/plugins/jquery-datatable/js/roworder.js') }}"></script>
    <script src="{{ url('/plugins/jquery-datatable/js/responsive.js') }}"></script>
@stop
