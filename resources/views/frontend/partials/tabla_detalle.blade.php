<div class="section mt-2" style="overflow-x:auto">
<table class="table table-striped datatable responsive">
    <thead>
        <tr>
            <th scope="col">#</th>
            @foreach(getFormFields($form_name) as $f)
            <th scope="col"><b>{{strtoupper($f)}}</b></th>
            @endforeach
            <th  scope="col">DETALLE</th>
        </tr>
    </thead>
    <tbody>
        
            @foreach(getFormData($form_name,$data->id,0,0) as $det)
            <tr>
                @php
                    $d=collect($det)->toArray();
                @endphp
                <td>{{$d["formulario_registro_id"]}}</td>
                @foreach(getFormFields($form_name) as $fd)

                <td>@if(isset($d[$fd])){{$d[$fd]}}@endif</td>
                @endforeach
                <td><button type="button" class="btn btn-outline-primary rounded shadowed mr-1 mb-1" title="Ver detalle">
                    <a href="{{url('equipos/reportes/servicio_tecnico/'.$d['formulario_registro_id'])}}" target="_blank"><ion-icon name="eye-outline" title="Ver detalle"></ion-icon></a></button>
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