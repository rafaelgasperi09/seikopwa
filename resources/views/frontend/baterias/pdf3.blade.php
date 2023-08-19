<html>
<head>
    <style>
        @page { margin: 100px 25px; font:helvetica;font-size:10 }
        header { position: fixed; top: -60px; left: 0px; right: 0px; background-color: #fff; height: 50px; }
        .content { position: relative; top: 104px; left: 0px; right: 0px;  }
        footer { position: fixed; bottom: -60px; left: 0px; right: 0px; background-color: lightblue; height: 50px; }
        .page { page-break-after: always; }
        p:last-child { page-break-after: never; }
    </style>
</head>
<body>
<script type="text/php">

    if ( isset($pdf) ) {
        $x = 20;
        $y = 18;
        $text = "PÁGINA {PAGE_NUM} ";
        $font = $fontMetrics->get_font("Arial", "bold");
        $size = 8;
        $color = array(0,0,0);
        $word_space = 0.0;  //  default
        $char_space = 0.0;  //  default
        $angle = 0.0;   //  default
        $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
    }
</script>
@foreach($datos as $data)
<div class="page">
    <header style="height:">

        <table width="100%">
            <tr>
                <td width="80px"> <img align="center" src="{{public_path('/images/logo.png')}}" width="80px" style=""></td>
                <td align="center"><h3 style="text-align:center;margin-left:40px">CONTROL DE CARGA EN CUARTO DE BATERIAS</h3></td>
                <td width="80px">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="3" class="text-center"> <h4>Bateria : {{ $bateria->id_componente }}  Serie :{{ $bateria->serie  }} Marca :{{ $bateria->marca  }}  &nbsp; &nbsp;
            Modelo :{{ $bateria->modelo  }}  Amp:{{ $bateria->amperaje  }}A  Voltaje:{{ $bateria->voltaje }}V</h4></td>
            </tr>        
        </table>
        <table border="1" cellpadding="0" cellspacing="0" width="880" style="border-collapse:
    collapse;table-layout:fixed;width:100%">
        
            <tbody>
            <tr >
                <th height="48" width="80" style="height:36.0pt;width:60pt">Fecha</th>
                <th width="80" style="width:60pt">Hora de Entrada</th>
                <th width="80" style="width:60pt"><span style="mso-spacerun:yes">&nbsp;</span>Nivel de agua<span style="mso-spacerun:yes">&nbsp;</span></th>
                <th width="160" style="border-left:none;width:120pt">Equipo</th>
                <th width="160" style="border-left:none;width:120pt">Horometro</th>
                <th width="80" style="width:60pt">Pct Carga</th>
                <th style="border-bottom:.5pt solid black; width:60px">Galones aplicados</th>
                <th width="80" style="border-bottom:.5pt solid black; width:60pt">Tecnico</th>
                <th width="80" style="border-bottom:.5pt solid black; width:60pt">Comentarios</th>
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
                    <td style="border-top:none;border-left:none">{{ $dato->nivel_agua }}</td>
                    <td style="border-top:none;border-left:none">{{ $dato->equipo_nro }}</td>
                    <td style="border-top:none;border-left:none">{{ $dato->horometro }}</td>
                    <td style="border-top:none;border-left:none">{{ $dato->pct_carga }}%</td>
                    <td style="border-top:none;border-left:none">{{ $dato->galones }}</td>
                    <td style="border-top:none" >@if(isset($tecnicos[$dato->tecnico_id])) {{ $tecnicos[$dato->tecnico_id] }} @endif</td>
                    <td style="border-top:none;border-left:none">{{ substr($dato->comentarios,0,10) }}</td>
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
                </tr>
                <!--[endif]-->
                </tbody>
        </table>
    </div>
</div>
@endforeach
</body>
</html>
