@php
$columns=array('IDREPORTE','FECHA REGISTRO','HORA', 'TIPO','EQUIPO','PRIORIDAD','REGISTRADO POR','CLIENTE','FIRMA CLIENTE','HOROMETRO','ESTATUS','TURNO');
$campos=array('id','fecha','hora','tipo','numero_parte','prioridad','user_name','nombre','cliente','horometro','estatus','turno_chequeo_diario');
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
    <td >
        @if($k=='tipo')
        {{ tipo_form($data->$k)}}
        @elseif(($k=='cliente' and $data->$k<>$data->user_name) or $k!='cliente')
          {{$data->$k}}
        @endif
    </td>
    @endforeach
  </tr>
  @endforeach
</tbody>
</table>