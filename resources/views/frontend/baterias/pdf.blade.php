<h3 style="text-align: center;">CONTROL DE CARGA EN CUARTO DE BATERIAS</h3>
<h4>Bateria : {{ $bateria->id_componente }} Voltaje:{{ $bateria->voltaje }}V</h4>
<table border="1" cellpadding="0" cellspacing="0" width="880" style="border-collapse:
 collapse;table-layout:fixed;width:100%">
    <colgroup><col width="80" span="11" style="width:60pt">
    </colgroup><tbody><tr height="28" style="height:21.0pt">
        <td rowspan="2" height="48" class="xl69" width="80" style="height:36.0pt;width:60pt">Fecha</td>
        <td rowspan="2" class="xl69" width="80" style="width:60pt">Hora de Entrada</td>
        <td rowspan="2" class="xl122" width="80" style="width:60pt"><span style="mso-spacerun:yes">&nbsp;</span>Número de Equipo<span style="mso-spacerun:yes">&nbsp;</span></td>
        <td colspan="2" class="xl123" width="160" style="border-left:none;width:120pt">Salida
            del cuarto</td>
        <td colspan="2" class="xl123" width="160" style="border-left:none;width:120pt">Entrada
            al cuarto</td>
        <td rowspan="2" class="xl124" width="80" style="width:60pt">Horas de Uso de la
            bateria</td>
        <td rowspan="2" class="xl125" width="80" style="border-bottom:.5pt solid black;
  width:60pt">H2O</td>
        <td rowspan="2" class="xl127" width="80" style="border-bottom:.5pt solid black;
  width:60pt">ECU</td>
        <td rowspan="2" class="xl125" width="80" style="border-bottom:.5pt solid black;
  width:60pt">Observacion<span style="mso-spacerun:yes">&nbsp;</span></td>
    </tr>
    <tr height="20" style="height:15.0pt">
        <td height="20" class="xl69" width="80" style="height:15.0pt;border-top:none;
  border-left:none;width:60pt">Horometro</td>
        <td class="xl69" width="80" style="border-top:none;border-left:none;width:60pt">%
            de Carga</td>
        <td class="xl69" width="80" style="border-top:none;border-left:none;width:60pt">Horometro</td>
        <td class="xl69" width="80" style="border-top:none;border-left:none;width:60pt">%
            de Carga</td>
    </tr>
    <tr height="20" style="height:15.0pt">
        <td colspan="11" height="20" class="xl117" style="border-right:.5pt solid black;
  height:15.0pt">&nbsp;</td>
    </tr>
    @foreach($data as $dato)
    <tr height="20" style="height:15.0pt">
        <td height="20" class="xl70" style="height:15.0pt;border-top:none">{{ $dato->fecha }}</td>
        <td class="xl71" style="border-top:none;border-left:none">{{ $dato->hora_entrada }}</td>
        <td class="xl71" style="border-top:none;border-left:none">{{ $dato->componente_id }}</td>
        <td class="xl71" style="border-top:none;border-left:none">{{ $dato->horometro_salida_cuarto }}</td>
        <td class="xl72" style="border-top:none;border-left:none">{{ $dato->carga_salida_cuarto }}</td>
        <td class="xl71" style="border-top:none;border-left:none">{{ $dato->horometro_entrada_cuarto }}</td>
        <td class="xl85" style="border-top:none;border-left:none">{{ $dato->carga_entrada_cuarto }}</td>
        <td class="xl114" style="border-top:none" {{--}} rowspan="2" {{--}}>{{ $dato->horas_uso_bateria }}</td>
        <td class="xl75" style="border-top:none">{{ $dato->h2o }}</td>
        <td class="xl71" style="border-top:none;border-left:none">{{ $dato->ecu }}</td>
        <td class="xl76" style="border-top:none;border-left:none">{{ substr($dato->observacion,0,10) }}</td>
    </tr>
    @endforeach

    <!--[if supportMisalignedColumns]-->
    <tr height="0" style="display:none">
        <td width="80" style="width:60pt"></td>
        <td width="80" style="width:60pt"></td>
        <td width="80" style="width:60pt"></td>
        <td width="80" style="width:60pt"></td>
        <td width="80" style="width:60pt"></td>
        <td width="80" style="width:60pt"></td>
        <td width="80" style="width:60pt"></td>
        <td width="80" style="width:60pt"></td>
        <td width="80" style="width:60pt"></td>
        <td width="80" style="width:60pt"></td>
        <td width="80" style="width:60pt"></td>
    </tr>
    <!--[endif]-->
    </tbody></table>
