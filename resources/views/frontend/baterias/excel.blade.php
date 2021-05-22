<table>
<thead>
  <tr>
    <th colspan="11" align="center">CONTROL DE CARGA EN CUARTO DE BATERIAS</th>
  </tr>
</thead>
<tbody>
  <tr>
    <td colspan="11">Bateria : {{ $bateria->id_componente }} Voltaje:{{ $bateria->voltaje }}V</td>
  </tr>
  <tr>
    <td rowspan="2">Fecha</td>
    <td rowspan="2">Hora de Entrada</td>
    <td rowspan="2">Número de Equipo</td>
    <td colspan="2">Salida del cuarto</td>
    <td colspan="2">Entrada al cuarto</td>
    <td rowspan="2">Horas de Uso de la Batería</td>
    <td rowspan="2">H2O</td>
    <td rowspan="2">ECU</td>
    <td rowspan="2">Obsevación</td>
  </tr>
  <tr>

    <td>Horometro</td>
    <td>% de Carga</td>
    <td>Horometro</td>
    <td>% de Carga</td>

  </tr>
  @foreach($data as $dato)
  <tr>
    <td>{{ $dato->fecha }}</td>
    <td>{{ $dato->hora_entrada }}</td>
    <td>{{ $dato->componente_id }}</td>
    <td>{{ $dato->horometro_salida_cuarto }}</td>
    <td>{{ $dato->carga_salida_cuarto }}</td>
    <td>{{ $dato->horometro_entrada_cuarto }}</td>
    <td>{{ $dato->carga_entrada_cuarto }}</td>
    <td>{{ $dato->horas_uso_bateria }}</td>
    <td>{{ $dato->h2o }}</td>
    <td>{{ $dato->ecu }}</td>
    <td>{{ substr($dato->observacion,0,10) }}</td>
  </tr>
  @endforeach
</tbody>
</table>