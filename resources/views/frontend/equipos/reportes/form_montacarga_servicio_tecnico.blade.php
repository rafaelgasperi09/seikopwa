@php
$cTD='style="border:1px solid black';
$cTD2=$cTD.';vertical-align:top;';
$cTD3=$cTD2.'text-align:center"';
$cTD.='"';
$cTD2.='"';
$cFirma='style="border-bottom:1px solid black"';
@endphp
<html>
    <head>
      <style>
      html{
        width: 100%;
        height: 100%;
        padding: 0;
        margin: 20px 18px;
        }
      body {
        font-family: Arial, sans-serif;
        margin-top:0px;
      }
        thead { display: table-header-group }
        tfoot { display: table-row-group }
        tr { page-break-inside: avoid }
        .paginado{
            font-size:12px;
            vertical-align:top;
            text-align:right;
            width:120px;
        }
        .pagenum:before {
            content: counter(page);
        }
        td{
            padding:0px 8px;
        }
        .centered { vertical-align:middle; text-align:center; }
        .centered img { display:block; margin:0 auto;border:1px solid #000; }
      </style>

    </head>
    <body>
        <table align="center" width="100%" style="border:0px solid #ddd;">
        <thead >
            <tr>
                <th>
                <table align="center" width="100%">
                    <tr>
                        <td width="120px" ><img align="center" src="{{public_path('/images/logo.png')}}" width="80px"></td>
                        <td align="center">
                            <span style="font-size:24px;font-weight:bold">MONTACARGAS Y REPUESTOS, S.A.<span><br/>
                            <span style="font-size:16px;font-weight:normal">"Satisfacción y confianza"<span><br/>
                            <span style="font-size:20px;font-weight:bold">INFORME DE SERVICIO TÉCNICO<span>
                        </td>
                        <td class="paginado">
                            @if($datos['det'][0]->foto_cliente)
                            <div class="pagenum-container ">P. <span class="pagenum"></span></div>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td align="left">
                            <p style="font-size:18px;font-weight:bold;margin-bottom:5px">No. <span style="color:red">{{$datos['cab']->id}}</span></p>
                        </td>

                    </tr>
                </table>
                </th>
            </tr>
        </thead >

        <tr>
            <td>
                <table width="100%" cellpadding="0" cellspacing="0" align="center" >
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
                <br/>
            </td>
        </tr>
        <tr>
            <td>
            <table width="100%">
                <thead>
                <tr>
                    <td {!!$cTD2!!} >
                    <b style="font-size:12px">FALLA REPORTADA:</b>
                    <br>{{$datos['det'][0]->falla_reportada}}
                    </td>
                </tr>
                <tr>
                <td {!!$cTD2!!}>
                    <b style="font-size:12px">CAUSA DE LOS DAÑOS:</b>
                    <br>{{$datos['det'][0]->causa_de_danos}}
                </td>
                </tr>
                <tr>
                    <td {!!$cTD2!!}>
                        <b style="font-size:12px">TRABAJOS REALIZADOS:</b>
                        <br>{{$datos['det'][0]->trabajos_realizados}}
                    </td>
                </tr>
                <tr>
                    <td {!!$cTD2!!}>
                        <b style="font-size:12px">COTIZAR:</b>
                        <br>{{$datos['det'][0]->cotizar}}

                    </td>
                </tr>
                <tr>
                    <td {!!$cTD2!!}>
                        <b style="font-size:12px">OTROS COMENTARIOS:</b>
                        <br>{{$datos['det'][0]->comentarios}}
                        <br >
                    </td>
                </tr>
                </thead>
            </table>
            </td>

        </tr>
        @if($datos['det'][0]->foto_cliente)
        <tr>
            <td {!!$cTD3!!} >
                <small style="width:500px">Imagen reportada por el cliente</small><br/>
                <img src="{{storage_path('app/public/equipos/'.$datos['det'][0]->foto_cliente)}}" width="500px" style="max-height:400px">
            </td>
        </tr>
        @endif
        @if($datos['det'][0]->foto_tecnico)
            @foreach(explode(',',$datos['det'][0]->foto_tecnico) as $key=>$ft)
                @if(strlen($ft)>10)
                <tr>
                    <td {!!$cTD3!!} >
                        @if($key==0)
                        <small style="width:650px">Imagenes (tecnico)</small><br/>
                        @endif
                        <img src="{{storage_path('app/public/equipos/'.$ft)}}" width="500px" style="max-height:400px">
                    </td>
                </tr>
                @endif
            @endforeach
        @endif
        <tr>
            <td>
                <br/>
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
        <tfoot>
            <tr>
                <td>
                    <table align="center" width="60%">
                        <tr>
                            <td align="center" style="font-style:italic;font-size:12px">
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
        <tfoot>
        </table>
    </body>
</html>
