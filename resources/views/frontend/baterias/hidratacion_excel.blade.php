<table>
<thead>
  <tr>
    <th colspan="9" align="center">CONTROL DE HIDRATACION DE BATERIAS</th>
  </tr>
</thead>
<tbody>
  <tr>
    <td colspan="9">Bateria : {{ $bateria->id_componente }} 
        <b>Serie :{{ $bateria->serie }} 
        Marca :{{ $bateria->marca }} 
        Modelo :{{ $bateria->modelo }} 
        Amp:{{ $bateria->amperaje }}A
        Voltaje:{{ $bateria->voltaje }}V</b>    

    </td>
  </tr>
  <tr>
    <td>Fecha</td>
    <td>Hora</td>
    <td>Nivel de agua</td>
    <td>Equipo</td>
    <td>Horometro</td>
    <td>Pct Carga</td>
    <td>Galones aplicados</td>
    <td>Tecnico</td>
    <td>Comentarios</td>
  </tr>
  @foreach($data as $dato)
  <tr>
    <td>{{ $dato->fecha }}</td>
    <td>{{ $dato->hora_entrada }}</td>
    <td>{{ $dato->nivel_agua }}</td>
    <td>{{ $dato->equipo_nro }}</td>
    <td>{{ $dato->horometro }}</td>
    <td>{{ $dato->pct_carga }}</td>
    <td>{{ $dato->galones }}</td>
    <td>{{ $dato->tecnico_id }}</td>
    <td>{{ $dato->comentarios }}</td>
  </tr>
  @endforeach
</tbody>
</table>