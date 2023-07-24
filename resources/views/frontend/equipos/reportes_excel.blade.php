@php
  $columns=array('IDREPORTE','FECHA REGISTRO', 'TIPO','EQUIPO','REGISTRADO POR','CLIENTE','ESTATUS','SEMANA','DIA','TURNO');    
  $campos=array('id','created_at','tipo','numero_parte','user_name','nombre','estatus','semana','dia_semana','turno_chequeo_diario');
@endphp
<table>
<thead>
  <tr>
    <th colspan="11" align="center">REPORTES</th>
  </tr>
</thead>
<tbody>
  <tr>
    @foreach($columns as $c)
    <td >{{$c}}</td>
    @endforeach
  </tr>
  @foreach($data as $data)
  <tr>
    @foreach($campos as $k)
    <td >{{$data->$k}}</td>
    @endforeach
  </tr>
  @endforeach
</tbody>
</table>