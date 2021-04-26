<div class="section mt-2" style="overflow-x:auto">
<table class="table table-striped datatable responsive">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Creado por</th>
            <th  scope="col">Fecha creacion</th>
            <th  scope="col">Actualizado</th>
            <th  scope="col">Estado</th>
            <th  scope="col">Ver mas</th>
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
                    <a href="{{url('equipos/reportes/form_montacarga_servicio_tecnico/'.$d->id)}}" target="_blank" class="btn btn-primary btn-sm mr-1 ">
                    <ion-icon name="list-outline" title="Ver detalle"></ion-icon>Detalle
                    </a>
                    
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