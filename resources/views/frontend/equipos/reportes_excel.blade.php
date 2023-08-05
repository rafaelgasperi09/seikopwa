@php
$columns=array('IDREPORTE','FECHA REGISTRO','HORA', 'TIPO','EQUIPO','PRIORIDAD','REGISTRADO POR','CLIENTE','ESTATUS','SEMANA','DIA','TURNO');                      
$campos=array('id','fecha','hora','tipo','numero_parte','prioridad','user_name','nombre','estatus','semana','dia_semana','turno_chequeo_diario');
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