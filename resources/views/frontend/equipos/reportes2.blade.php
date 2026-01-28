@extends('frontend.main-layout')
@section('content')
@include('frontend.partials.title',array('title'=>'Equipos','subtitle'=>'Equipos >  Historial de reportes','route_back'=>route('equipos.index')))
<script>
    $('.header-large-title>.title').append(' <div style="position: absolute;right: 10px;top:18%;">\
                                                <a id="exporter" type="button" class="btn btn-success btn-rounded btn-condensed btn-sm pull-right"   href="{{route('equipos.reportes_export')}}" target="_blank" id="export_btn" > <ion-icon name="download-outline"></ion-icon> Exportar</a>\
                                                <a id="mostrarfiltro" type="button" class="btn btn-primary btn-rounded btn-condensed btn-sm pull-right"  data-toggle="collapse" href="#filtro" role="button" aria-expanded="false" aria-controls="filtro"> <ion-icon name="funnel-outline"></ion-icon> Filtro</a>\
                                            </div>');       
</script>
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
                <th>Fecha/Hora registro</th>
                @if(!current_user()->isCliente())
                <th>Fecha/Hora inicio</th>
                <th>Fecha/Hora Fin</th>
                @endif
                <th>Tipo</th>
                <th>Equipo</th>
                <th>Prioridad</th>
                <th>Registrado por</th>
                <th>Cliente</th>
                <th>Firma Cliente</th>
                <th>Horometro</th>
                <th>Estatus</th>
                <th>Turno</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
                @foreach($data as $d)
                <tr>
                    <td>{{$d->id}}</td>
                    <td>{{ \Carbon\Carbon::parse($d->created_at)->format('Y-m-d H:i:s')}}</td>
                    @if(!current_user()->isCliente())
                    <td>{{$d->fecha_inicia}}</td>
                    <td>{{$d->fecha_fin}}</td>
                    @endif
                    <td>{{$d->tipo}}</td>
                    <td>
                    <a target='_blank' href='{{route('equipos.detail',$d->equipo_id)}}'>{{$d->numero_parte}}</a>
                    </td>
                    <td>{{$d->prioridad}}</td>
                    <td>{{$d->user_name}}</td>
                    <td>{{$d->cliente_nombre}}</td>
                    <td>@if( $d->cliente<>$d->user_name)
                        {{$d->cliente}}
                        @endif
                    </td>
                    <td>{{$d->horometro}}</td>
                    <td>{{$d->estatus}}</td>
                    <td>{{$d->turno_chequeo_diario}}</td>
                    <td>
                        @php
                            $url='';
                            $url2='';
                            $url_edit='';
                            $puedo_imprimir=true;
                            if($d->tipo=='daily_check'){
                                $url=route('equipos.show_daily_check',$d->id);
                                $url2=route('reporte.detalle',['form_montacarga_daily_check',$d->id]);   
                                $url_edit=route('equipos.edit_daily_check',$d->id);
                            }
                            
                            if($d->tipo=='mant_prev'){
                                $url=route('equipos.show_mant_prev',$d->id);  
                                $url2=route('equipos.imprimir_mant_prev',$d->id);  
                                $url_edit=route('equipos.edit_mant_prev',$d->id);  
                                if($d->estatus<>'C')
                                    $puedo_imprimir=false;
                            }
                            if($d->tipo=='serv_tec'){
                                $url=route('equipos.show_tecnical_support',$d->id);
                                $url2=route('reporte.detalle',['form_montacarga_servicio_tecnico',$d->id]); 
                                $url_edit=route('equipos.edit_tecnical_support',$d->id);  
                            }
                                $ret='';
                                if($editar){
                                    $ret.= ' <a target="_blank" href="'.$url_edit.'" target="_blank" class="btn btn-success btn-sm mr-1" title="Editar">
                                                <ion-icon name="pencil-outline" ></ion-icon>
                                            </a>';
                                }
                                $ret.= ' <a target="_blank" href="'.$url.'" target="_blank" class="btn btn-primary btn-sm mr-1 " title="Ver detalle">
                                            <ion-icon name="eye-outline" ></ion-icon>';
                                if($puedo_imprimir){
                                    $ret.= ' </a>
                                    <a target="_blank" href="'.$url2.'" target="_blank" class="btn btn-warning btn-sm mr-1 "  title="Imprimir formulario">
                                        <ion-icon name="file-tray-stacked-outline"></ion-icon>
                                    </a>';
                                }            
                                echo $ret;
                        @endphp
                    
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{$data->render()}}
    </div>
</div>
<div class="row">
    <div class=""><br/>
    </div>

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
    /*
  $('.datatable').DataTable( {
                "language": {
                    processing: '<i style="position: fixed;left: 50%;top:50%;" class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
                },
                "responsive": true,
                "autoWidth": false,
                "order": [[ 0, "desc" ]],
                "processing": true,
                "serverSide": true,
                "ajax": "{{url('equipos/reportes_datatable') }}?"+parameters,
                "columns":[
                    {data:'id'},
                    {data:'created_at'},
                    @if(!current_user()->isCliente())
                    {data:'fecha_inicia'},
                    {data:'fecha_fin'},
                    @endif
                    {data:'tipo'},
                    {data:'numero_parte'},
                    {data:'prioridad'},
                    {data:'user_name'},
                    {data:'cliente_nombre'},
                    {data:'cliente'},
                    {data:'horometro'},
                    {data:'estatus'},
                    {data:'turno_chequeo_diario'},
                    {data:'actions'},
                ],
          
            });
*/
</script>
@stop
