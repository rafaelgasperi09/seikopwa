<div class="section mt-2" style="overflow-x:auto">

<table class="table table-striped datatable responsive">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Creado por</th>
            <th  scope="col">Fecha creacion</th>
            <th  scope="col">Actualizado</th>
            <th  scope="col">Estado</th>
            <th  scope="col">Acciones</th>
        </tr>
    </thead>
    <tbody>
    @foreach($data as $d)
    <tr>
       <td>{{$d->id}}</td>
       <td>{{$d->creador->first_name.' '.$d->creador->last_name}}</td>
       <td>{{$d->created_at}}</td>
       <td>{{$d->updated_at}}</td>
       <td>{!!getStatusHtml($d->estatus)!!}</td>
       <td>
            @if(\Sentinel::getUser()->hasAccess('equipos.edit_mant_prev'))
               <a href="{{ route('equipos.edit_mant_prev',$d->id) }}" target="_blank" class="btn btn-success btn-sm mr-1 ">
                   <ion-icon name="pencil-outline" title="Ver detalle"></ion-icon>Editar
               </a>
            @endif
           @if($d->estatus  == 'C')
            <a href="{{url('equipos/reportes/form_montacarga_servicio_tecnico/'.$d->id)}}" target="_blank" class="btn btn-primary btn-sm mr-1 ">
            <ion-icon name="print-outline" title="Ver detalle"></ion-icon>Imprimir
            </a>
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
