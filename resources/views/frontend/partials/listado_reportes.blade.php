<div class="section mt-2" style="overflow-x:auto">
<table class="table table-striped datatable responsive">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Creado por</th>
            <th scope="col">Cliente</th>
            <th scope="col">Fecha</th>
            <th scope="col">Estado</th>
            <th scope="col" width="110px">Acciones</th>
        </tr>
    </thead>
    <tbody>
    @foreach($data as $d)
    <tr>
       <td>{{$d->id}}</td>
       <td>{{$d->creador->first_name.' '.$d->creador->last_name}}</td>
        <td>{{$d->cliente()->nombre}}</td>
       <td>{{ \Carbon\Carbon::parse($d->created_at)->format('Y-m-d')}}</td>
       <td>
        {!!getStatusHtml($d->estatus)!!}
        @if(\Sentinel::getUser()->hasAccess('equipos.assign_tecnical_support') &&  $d->estatus=='A')
            &nbsp;
            <a class="btn btn-success btn-sm mr-1 " data-toggle="modal" data-target="#assign_tecnico_modal"
                data-action="{{ route('equipos.assign_tecnical_support',$d->id) }}">
                <small>Cambiar Técnico</small>
            </a>
        @endif
       </td>
       <td>
           @if($nombre == 'mantenimiento_preventivo')
               @if(\Sentinel::getUser()->hasAccess('equipos.edit_mant_prev') && $d->estatus <> 'C')
                   <a href="{{ route('equipos.edit_mant_prev',$d->id) }}" class="btn btn-success btn-sm mr-1 botones">
                       <ion-icon name="create-outline" title="Editar"></ion-icon>Editar
                   </a>
               @endif
               @if($d->estatus  == 'C')
                <a href="{{ route('equipos.imprimir_mant_prev',$d->id) }}" class="btn btn-primary btn-sm mr-1 botones" target="_blanks">
                    <ion-icon name="print-outline" title="Ver detalle"></ion-icon><small>Imprimir</small>
                </a>
               @endif
               @if(\Sentinel::getUser()->hasAccess('equipos.delete_mant_prev'))
                    <a href="{{ route('equipos.delete_mant_prev',$d->id) }}" class="btn btn-danger btn-sm mr-1 botones">
                        <ion-icon name="trash-outline" title="Borrar"></ion-icon><small>Borrar</small>
                    </a>
                @endif
           @elseif($nombre == 'form_montacarga_servicio_tecnico')
               @if(\Sentinel::getUser()->hasAccess('equipos.assign_tecnical_support') && $d->estatus == 'P' )
                   <a class="btn btn-success btn-sm mr-1 botones" data-toggle="modal" data-target="#assign_tecnico_modal"
                      data-action="{{ route('equipos.assign_tecnical_support',$d->id) }}">
                       <ion-icon name="build-outline" ></ion-icon><small>Asignar Técnico</small>
                   </a>
               @endif
               @if(\Sentinel::getUser()->hasAccess('equipos.start_tecnical_support') && $d->estatus == 'A')
                   {{ Form::model($data, array('route' => array('equipos.start_tecnical_support', $d->id), 'method' => 'PUT' , 'role' => 'form','class'=>'form-horizontal', 'style'=>"display: inline-block;")) }}
                       <button type="submit" class="btn btn-primary btn-sm mr-1 botones">
                           <ion-icon name="play-outline" title="Editar"></ion-icon><small>Iniciar</small>
                       </button>
                   {{ Form::close() }}
               @endif
               @if(\Sentinel::getUser()->hasAccess('equipos.edit_tecnical_support') && $d->estatus == 'PR')
                   <a href="{{ route('equipos.edit_tecnical_support',$d->id) }}" class="btn btn-secondary btn-sm mr-1 botones">
                       <ion-icon name="construct-outline" title="Editar"></ion-icon><small>Completar</small>
                   </a>
               @endif
               @if($d->estatus  == 'C')
                <a href="{{url('equipos/reportes/form_montacarga_servicio_tecnico/'.$d->id)}}" target="_blank" class="btn btn-primary btn-sm mr-1 botones">
                    <ion-icon name="print-outline" title="Ver detalle"></ion-icon><small>Imprimir</small>
                </a>
               @endif
               <a class="btn btn-info btn-sm mr-1 botones" data-toggle="modal" data-target="#status_history_modal" data-id="{{ $d->id }}" style="display: inline-block;">
                   <ion-icon name="file-tray-stacked-outline"></ion-icon><small>Historial</small>
               </a>
               @if(\Sentinel::getUser()->hasAccess('equipos.delete_tecnical_support'))
                    <a href="{{ route('equipos.delete_tecnical_support',$d->id) }}" class="btn btn-danger btn-sm mr-1 botones">
                        <ion-icon name="trash-outline" title="Borrar"></ion-icon><small>Borrar</small>
                    </a>
                @endif
           @endif
        </td>

    </tr>
    @endforeach
    </tbody>
</table>
</div>
<script>
$(document).ready( function () {
    $('.datatable').DataTable();
} );
</script>
