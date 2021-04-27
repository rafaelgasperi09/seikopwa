@php
$cTD='style="border:1px solid black"';
$cFirma='style="border-bottom:1px solid black"';
@endphp
<html>
    <head>
      <style>
      body {
        font-family: Arial, sans-serif;
        margin-top:0px;
      }
      </style>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet">
    </head>
    <body>
        <table align="center" width="100%" style="border:0px solid #ddd;">
        <tr>
            <td>
            <table align="center" width="100%">
                <tr>
                    <td width="15%" valign="bottom" align="left">
                        <h3>No. <span style="color:red">{{$datos['cab']->id}}</span></h3>
                    </td>
                    <td align="center">
                        <h2>MONTACARGAS Y REPUESTOS, S.A.</h2>
                        <h4>"Satisfacción y confianza"</h4>
                        <h3>INFORME DE SERVICIO TÉCNICO</h3>
                    </td>
                    <td width="15%"></td>
                </tr>
            </table>
            </td>
        </tr>

        <tr>
            <td>
                <table width="100%" cellpadding="5" cellspacing="0" align="center" >
                    <tr>
                        <td width="67%" colspan="2" {!!$cTD!!}>
                        <span style="font-size:10px">CLIENTE:</span>
                        <br>
                        {{$datos['cab']->cliente()->nombre}}
                        </td>
                        <td width="33%" {!!$cTD!!}>
                        <span style="font-size:10px">MARCA:</span>
                        <br>{{$datos['cab']->equipo()->marca->display_name}}
                        </td>
                    </tr>
                    <tr>
                        <td width="67%" colspan="2" {!!$cTD!!}>
                        <span style="font-size:10px">DIRECCION:</span>
                        <br>{{$datos['cab']->cliente()->direccion}}
                        </td>
                        <td width="33%" {!!$cTD!!}>
                        <span style="font-size:10px">MODELO:</span>
                        <br>{{$datos['cab']->equipo()->modelo}}
                        </td>
                    </tr>
                    <tr>
                        <td width="32%" {!!$cTD!!}>
                        <span style="font-size:10px">FECHA:</span>
                        <br>{{$datos['det'][0]->fecha}}
                        </td>
                        <td width="33%" {!!$cTD!!}>
                        <span style="font-size:10px">PERSONA ENCARGADA/CLIENTE:</span>
                        <br>{{$datos['cab']->creador->first_name.' '.$datos['cab']->creador->last_name}}
                        </td>
                        <td width="33%" {!!$cTD!!}>
                        <span style="font-size:10px">NO. SERIE:</span>
                        <br>{{$datos['cab']->equipo()->serie}}
                        </td>
                    </tr>
                    <tr>
                        <td width="32%" {!!$cTD!!}>
                        <span style="font-size:10px">HORA / ENTRADA:</span>
                        <br>{{$datos['det'][0]->hora_entrada}}
                        </td>
                        <td width="33%" {!!$cTD!!}>
                        <span style="font-size:10px">HORA / SALIDA:</span>
                        <br>{{$datos['det'][0]->hora_salida}}
                        </td>
                        <td width="33%" {!!$cTD!!}>
                        <span style="font-size:10px">VOLTAJE / COMBUSTIBLE:</span>
                        <br>{{$datos['det'][0]->voltaje_combustible}}
                        </td>
                    </tr>
                    <tr>
                        <td width="32%" {!!$cTD!!}>
                        <span style="font-size:10px">HOROMETRO:</span>
                        <br>{{$datos['det'][0]->horometro}}
                        </td>
                        <td width="33%" {!!$cTD!!}>
                        <span style="font-size:10px">EQUIPO:</span>
                        <br>{{$datos['cab']->equipo()->numero_parte}}
                        </td>
                        <td width="33%" {!!$cTD!!}>
                        <span style="font-size:10px">CAPACIDAD:</span>
                        <br>{{$datos['cab']->equipo()->capacidad_de_carga}}
                        </td>
                    </tr>        
                </table>
                <br/><br/>
            </td>
        </tr>
        <tr>
            <td {!!$cTD!!}>
                <b style="font-size:12px">FALLA REPORTADA:</b>
                <br>{{$datos['det'][0]->falla_reportada}}
            </td>
        </tr>
        <tr>
            <td><br/></td>
        </tr>
        <tr>
            <td {!!$cTD!!}>
                <b style="font-size:12px">CAUSA DE LOS DAÑOS:</b>
                <br>{{$datos['det'][0]->causa_de_danos}}
            </td>
        </tr>
        <tr>
            <td><br/></td>
        </tr>
        <tr>
            <td {!!$cTD!!}>
                <b style="font-size:12px">TRABAJOS REALIZADOS:</b>
                <br>{{$datos['det'][0]->trabajos_realizados}}
            </td>
        </tr>
        <tr>
            <td><br/></td>
        </tr>
        <tr>
            <td {!!$cTD!!}>
                <b style="font-size:12px">COTIZAR:</b>
                <br>{{$datos['det'][0]->cotizar}}

            </td>
        </tr>
        <tr>
            <td><br/></td>
        </tr>
        <tr>
            <td {!!$cTD!!}>
                <b style="font-size:12px">OTROS COMENTARIOS:</b>
                <br>{{$datos['det'][0]->comentarios}}
                <br >
            </td>
        </tr>
        <tr>
            <td>
                <br/><br/>
                <table width="100%">
                    <tr>
                        <td {!!$cFirma!!} width="40%">
                            @if(strlen($datos['det'][0]->firma_cliente)>0)
                            <img src="{{storage_path('/app/public/firmas/'.$datos['det'][0]->firma_cliente)}}" height="60px">
                            @endif
                        </td>
                        <td>&nbsp;</td>
                        <td {!!$cFirma!!} width="40%">
                        @if(strlen($datos['det'][0]->firma_tecnico)>0)
                        <img src="{{storage_path('/app/public/firmas/'.$datos['det'][0]->firma_tecnico)}}"  height="60px">
                        @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size:12px">FIRMA DEL CLIENTE</td>
                        <td>&nbsp;</td>
                        <td style="font-size:12px">FIRMA DEL TÉCNICO</td>
                    </tr>            
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <table align="center" width="60%">
                    <tr>

                        <td align="center" style="font-style:italic;font-size:14px">
                            <br/><br/>
                            Urbanizacion Los Ángeles Calle 62 Oeste Edificio GMP E-8<br/>
                            Teléfono:236-8079 // 236-0412 // 236-3544 • Fax: 236-0455<br/>
                            Ciudad de Panamá<br/>
                            E-mail:gmp@montacargaspanama.com
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        </table>
    </body>
</html>