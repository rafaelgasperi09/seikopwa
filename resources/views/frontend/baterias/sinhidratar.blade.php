<table>
<thead>
  <tr>
    <th align="center">BATERIAS SIN HIDRATAR</th>
  </tr>
</thead>
<tbody>
  @foreach($data as $dato)
  <tr>
    <td>{{ $dato->id_componente }}</td>
  </tr>
  @endforeach
</tbody>
</table>