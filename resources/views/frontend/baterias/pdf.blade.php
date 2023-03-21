<html>
<head>
    <style>
        @page { margin: 100px 25px; font:helvetica;font-size:10 }
        header { position: fixed; top: -60px; left: 0px; right: 0px; background-color: #fff; height: 50px; }
        .content { position: relative; top: 128px; left: 0px; right: 0px;  }
        footer { position: fixed; bottom: -60px; left: 0px; right: 0px; background-color: lightblue; height: 50px; }
        p { page-break-after: always; }
        p:last-child { page-break-after: never; }
    </style>
</head>
<body>
<script type="text/php">

    if ( isset($pdf) ) {
        $x = 20;
        $y = 18;
        $text = "PÁGINA {PAGE_NUM} DE {PAGE_COUNT}";
        $font = $fontMetrics->get_font("Arial", "bold");
        $size = 8;
        $color = array(0,0,0);
        $word_space = 0.0;  //  default
        $char_space = 0.0;  //  default
        $angle = 0.0;   //  default
        $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
    }
</script>
<header style="height:">
    <table>
        <tr>
            <td width="80px"> <img align="center" src="{{public_path('/images/logo.png')}}" width="80px" style=""></td>
            <td align="center"><h3 style="text-align:center;margin-left:40px">CONTROL DE CARGA EN CUARTO DE BATERIAS</h3></td>
            <td width="80px">&nbsp;</td>
        </tr>
        <tr>
            <td rowspan="3"> <h4>Bateria : {{ $bateria->id_componente }} Voltaje:{{ $bateria->voltaje }}V</h4></td>
        </tr>        
    </table>
    <table border="1" cellpadding="0" cellspacing="0" width="880" style="border-collapse:
 collapse;table-layout:fixed;width:100%">
     
        <tbody>
        <tr >
            <th rowspan="2" height="48" width="80" style="height:36.0pt;width:60pt">Fecha</th>
            <th rowspan="2" width="80" style="width:60pt">Hora de Entrada</th>
            <th rowspan="2" width="80" style="width:60pt"><span style="mso-spacerun:yes">&nbsp;</span>Número de Equipo<span style="mso-spacerun:yes">&nbsp;</span></th>
            <th colspan="2"width="160" style="border-left:none;width:120pt">Salida del cuarto</th>
            <th colspan="2"width="160" style="border-left:none;width:120pt">Entrada al cuarto</th>
            <th rowspan="2" width="80" style="width:60pt">Horas de Uso de la bateria</th>
            <th rowspan="2" style="border-bottom:.5pt solid black; width:60px">H2O</th>
            <th rowspan="2" width="80" style="border-bottom:.5pt solid black; width:60pt">ECU</th>
            <th rowspan="2" width="80" style="border-bottom:.5pt solid black; width:60pt">Estado de bateria</th>
            <th rowspan="2" width="80" style="border-bottom:.5pt solid black;width:60pt">Observacion<span style="mso-spacerun:yes">&nbsp;</span></th>
        </tr>
        <tr>
            <th >Horometro</th>
            <th width="80" style="border-top:none;border-left:none;width:60pt">% de Carga</th>
            <th width="80" style="border-top:none;border-left:none;width:60pt">Horometro</th>
            <th width="80" style="border-top:none;border-left:none;width:60pt">% de Carga</th>
        </tr>
        </tbody>
    </table>
</header>
<div class="content">
    <table border="1" cellpadding="0" cellspacing="0" width="880" style="border-collapse:collapse;table-layout:fixed;width:100%">

            <tbody>
            @foreach($data as $dato)
            <tr height="20" style="height:15.0pt">
                <td height="20" class="xl70" style="height:15.0pt;border-top:none">{{ $dato->fecha }}</td>
                <td style="border-top:none;border-left:none">{{ $dato->hora_entrada }}</td>
                <td style="border-top:none;border-left:none">{{ $dato->componente_id }}</td>
                <td style="border-top:none;border-left:none">{{ $dato->horometro_salida_cuarto }}</td>
                <td style="border-top:none;border-left:none">{{ $dato->carga_salida_cuarto }}</td>
                <td style="border-top:none;border-left:none">{{ $dato->horometro_entrada_cuarto }}</td>
                <td style="border-top:none;border-left:none">{{ $dato->carga_entrada_cuarto }}</td>
                <td style="border-top:none" {{--}} rowspan="2" {{--}}>{{ $dato->horas_uso_bateria }}</td>
                <td style="border-top:none">{{ $dato->h2o }}</td>
                <td style="border-top:none;border-left:none">{{ $dato->ecu }}</td>
                <td style="border-top:none;border-left:none">{{ $dato->estado_de_bateria }}</td>
                <td style="border-top:none;border-left:none">{{ substr($dato->observacion,0,10) }}</td>
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
            </tbody>
    </table>
</div>
</body>
</html>
