@php
    $asignar_sup=current_user()->isOnGroup('supervisor-alquiler') || current_user()->isOnGroup('supervisor-repuestos') || current_user()->isOnGroup('programador');
    $equipo_repuesto=current_user()->isOnGroup('tecnico') || current_user()->isOnGroup('supervisor-servicio-tecnico') || current_user()->isOnGroup('programador') || current_user()->isOnGroup('administrador');

    $accidente_cotizacion=current_user()->isOnGroup('tecnico') || current_user()->isSupervisor() || current_user()->isOnGroup('programador') || current_user()->isOnGroup('administrador');
@endphp
<div class="section mt-2" style="overflow-x:auto">
<table class="table table-striped datatable responsive" width="100%">
    <thead>
        <tr>
            <th scope="col" width="10px">#</th>
            <th scope="col" width="120px">Creado por</th>
            <th scope="col" width="120px">Cliente</th>
            <th  width="80px">Fecha</th>
            <th scope="col" width="20px">Estado</th>
            @if($nombre == 'form_montacarga_servicio_tecnico')
            <th scope="col" width="20px">Trabajado por/fecha</th>
            <th scope="col" width="20px">Cotización</th>
            <th scope="col" width="20px">Accidente</th>
            <th scope="col" width="20px">Equipo</th>
            <th scope="col" width="20px">Repuestos</th>
            @endif
            <th scope="col" width="195px">Acciones</th>
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
       </td>
       @if($nombre == 'form_montacarga_servicio_tecnico')
       <td>
            @if($d->trabajado_por==''  and $asignar_sup)
                @if($d->estatus=='PR')
                <a href="#" class="badge badge-primary" 
                data-toggle="modal" data-target="#assign_supervisor" data-id="{{$d->id}}" > 
                    <ion-icon name="build-outline" ></ion-icon><small>Asignar</small>
                </a>
                @endif
            @else
            {{$d->trabajado->full_name}} <br/> {{$d->fecha_trabajo}}
            @endif
       </td>
       <td>
            
            @php $modal='';$cotizacion=['No aplica','light'];
            if($accidente_cotizacion){
                $modal='data-toggle="modal" data-target="#marcar_cotizado" ';
            }
            if($d->cotizacion=='N')
                $cotizacion=['No aprobado','danger'];
            if($d->cotizacion=='A')
                $cotizacion=['Aprobado','success'];
            @endphp
                <a href="#" class="btn btn-{{$cotizacion[1]}}" 
                {!!$modal!!} data-id="{{$d->id}}" > 
                <small>{{$cotizacion[0]}}</small>
                </a>
       </td>
       <td>
        @if(true)
            @php $modal='';$accidente=['NO','success'];
            if($accidente_cotizacion){
                $modal='data-toggle="modal" data-target="#marcar_accidente" ';
            }
            if($d->accidente=='S')
                $accidente=['SI','danger'];
                
            @endphp
            <a href="#" class="btn btn-{{$accidente[1]}}" 
          {!!$modal!!} data-id="{{$d->id}}" > 
            <small>{{$accidente[0]}}</small>
            </a>
        @endif
       </td>
       <td>
        <a href="#" @if($equipo_repuesto) data-toggle="modal" data-target="#assign_status_modal" @endif
        data-id="{{$d->id}}" data-tipo='equipo'> 
            {!!getStatusHtml($d->equipo_status,1)!!}
        </a>
       </td>
       <td>
        <a href="#" @if($equipo_repuesto)  data-toggle="modal" data-target="#assign_status_modal" @endif
        data-id="{{$d->id}}" data-tipo='repuesto'> 
            {!!getStatusHtml($d->repuesto_status,2)!!}
        </a>
       </td>
        @endif
       <td>
           @if($nombre == 'mantenimiento_preventivo')
               @if(\Sentinel::getUser()->hasAccess('equipos.edit_mant_prev') && $d->estatus <> 'C')
                   <a href="{{ route('equipos.edit_mant_prev',$d->id) }}" class="badge badge-success botones">
                       <ion-icon name="create-outline" title="Editar"></ion-icon>Editar
                   </a>
               @endif
               @if($d->estatus  == 'C')
                <a href="{{ route('equipos.imprimir_mant_prev',$d->id) }}" class="badge badge-primary btn-sm mr-1 botones" target="_blanks">
                    <ion-icon name="print-outline" title="Ver detalle"></ion-icon><small>Imprimir</small>
                </a>
               @endif
               <a href="{{ route('equipos.show_mant_prev',$d->id) }}" class="badge badge-success btn-sm mr-1 botones">
                       <ion-icon name="eye-outline" title="Ver"></ion-icon><small>Ver</small>
                </a>
               @if(\Sentinel::getUser()->hasAccess('equipos.delete_mant_prev'))
                    <a href="{{ route('equipos.delete_mant_prev',$d->id) }}" class="badge badge-danger btn-sm mr-1 botones">
                        <ion-icon name="trash-outline" title="Borrar"></ion-icon><small>Borrar</small>
                    </a>
                @endif
                <a class="badge badge-info btn-sm mr-1 botones" data-toggle="modal" data-target="#status_history_modal" data-id="{{ $d->id }}" style="display: inline-block;">
                   <ion-icon name="file-tray-stacked-outline"></ion-icon><small>Historial</small>
               </a>
           @elseif($nombre == 'form_montacarga_servicio_tecnico')
               @if(\Sentinel::getUser()->hasAccess('equipos.assign_tecnical_support') && $d->estatus == 'P' )
                   <a class="badge badge-success btn-sm mr-1 botones" data-toggle="modal" data-target="#assign_tecnico_modal"
                      data-action="{{ route('equipos.assign_tecnical_support',$d->id) }}">
                       <ion-icon name="build-outline" ></ion-icon><small>Asignar Técnico</small>
                   </a>
               @endif
               @if(\Sentinel::getUser()->hasAccess('equipos.assign_tecnical_support') &&  $d->estatus=='A')
                    <a class="badge badge-success btn-sm mr-1 " data-toggle="modal" data-target="#assign_tecnico_modal"
                        data-action="{{ route('equipos.assign_tecnical_support',$d->id) }}">
                        <small>Cambiar Técnico</small>
                    </a>
                @endif
               @if(\Sentinel::getUser()->hasAccess('equipos.start_tecnical_support') && $d->estatus == 'A')
                   {{ Form::model($data, array('route' => array('equipos.start_tecnical_support', $d->id), 'method' => 'PUT' , 'role' => 'form','class'=>'form-horizontal', 'style'=>"display: inline-block;")) }}
                       <button type="submit" class="badge badge-primary btn-sm mr-1 botones">
                           <ion-icon name="play-outline" title="Editar"></ion-icon><small>Iniciar</small>
                       </button>
                   {{ Form::close() }}
               @endif
               @if(\Sentinel::getUser()->hasAccess('equipos.edit_tecnical_support') && $d->estatus == 'PR')
                   <a href="{{ route('equipos.edit_tecnical_support',$d->id) }}" class="badge badge-secondary btn-sm mr-1 botones">
                       <ion-icon name="construct-outline" title="Editar"></ion-icon><small>Completar</small>
                   </a>
               @endif
               @if($d->estatus  == 'C' or true)
                <a href="{{url('equipos/reportes/form_montacarga_servicio_tecnico/'.$d->id)}}" target="_blank" class="badge badge-primary btn-sm mr-1 botones">
                    <ion-icon name="print-outline" title="Ver detalle"></ion-icon><small>Imprimir</small>
                </a>
               @endif
               <a class="badge badge-info btn-sm mr-1 botones" data-toggle="modal" data-target="#status_history_modal" data-id="{{ $d->id }}" style="display: inline-block;">
                   <ion-icon name="file-tray-stacked-outline"></ion-icon><small>Historial</small>
               </a>
               <a href="{{ route('equipos.show_tecnical_support',$d->id) }}" class="badge badge-success btn-sm mr-1 botones">
                       <ion-icon name="eye-outline" title="Editar"></ion-icon><small>Ver</small>
                </a>
               @if(\Sentinel::getUser()->hasAccess('equipos.delete_tecnical_support'))
                    <a href="{{ route('equipos.delete_tecnical_support',$d->id) }}" class="badge badge-danger btn-sm mr-1 botones">
                        <ion-icon name="trash-outline" title="Borrar"></ion-icon><small>Borrar</small>
                    </a>
                @endif
            @elseif($nombre == 'form_control_entrega_alquiler')
                @if($d->estatus  != 'C' and \Sentinel::hasAccess('equipos.edit_control_entrega'))
                   <a href="{{ route('equipos.edit_control_entrega',$d->id) }}" class="badge badge-success btn-sm mr-1 botones">
                       <ion-icon name="create-outline" title="Editar"></ion-icon><small>Editar</small>
                   </a>
                @endif
                @if(\Sentinel::hasAccess('equipos.show_control_entrega'))
                <a href="{{ route('equipos.show_control_entrega',$d->id) }}" class="badge badge-success btn-sm mr-1 botones">
                        <ion-icon name="eye-outline" title="Editar"></ion-icon><small>Ver</small>
                </a>
                @endif
                @if(\Sentinel::getUser()->hasAccess('equipos.delete_control_entrega'))
                    <a href="{{ route('equipos.delete_control_entrega',$d->id) }}" class="badge badge-danger btn-sm mr-1 botones">
                        <ion-icon name="trash-outline" title="Borrar"></ion-icon><small>Borrar</small>
                    </a>
                @endif
                @if($d->estatus  == 'C' and \Sentinel::hasAccess('equipos.show_control_entrega'))
                <a href="{{url('equipos/reportes/form_control_entrega_alquiler/'.$d->id)}}" target="_blank" class="badge badge-primary btn-sm mr-1 botones">
                    <ion-icon name="print-outline" title="Ver detalle"></ion-icon><small>Imprimir</small>
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
