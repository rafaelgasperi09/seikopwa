<div class="section mt-2" style="overflow-x:auto">
<table class="table table-striped datatable responsive">
    <thead>
        <tr>
            <th scope="col">#</th>
            @foreach(getFormFields($form_name) as $f)
            <th scope="col"><b>{{strtoupper($f)}}</b></th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        
            @foreach(getFormData($form_name) as $det)
            <tr>
                @php
                    $d=collect($det)->toArray();
                @endphp
                <td>{{$d["formulario_registro_id"]}}</td>
                @foreach(getFormFields($form_name) as $fd)

                <td>@if(isset($d[$fd])){{$d[$fd]}}@endif</td>
                @endforeach
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