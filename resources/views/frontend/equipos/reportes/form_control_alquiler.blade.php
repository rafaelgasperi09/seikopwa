@php
$cTD='style="border:1px solid black';
$cTD2=$cTD.';vertical-align:top;';
$cTD3=$cTD2.'text-align:center"';
$cTD.='"';
$cTD2.='"';
$titles=$cTD;
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
            <thead>
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
                                @if(!empty($datos['det'][0]->montacarga_check))
                                <div class="pagenum-container ">P. <span class="pagenum"></span></div>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td align="left">
                                <p style="font-size:18px;font-weight:bold;margin-bottom:1px">No. <span style="color:red">{{$datos['cab']->id}}</span></p>
                            </td>
                            
                        </tr>
                    </table>
                    </th>
                </tr>
            </thead>
            <tr>
                <td>
                    <table width="100%" cellpadding="0" cellspacing="0" align="center" >
                        <tr>
                            <td width="32%" {!!$cTD!!}>
                                <span style="font-size:10px">FECHA DE ENTREGA:</span><br>
                                {{$datos['cab']->created_at}}
                            </td>
                            <td width="33%" {!!$cTD!!}>
                                <span style="font-size:10px">CONTACTO:</span><br>
                                {{$datos['det'][0]->contacto}}
                            </td>
                        </tr>
                        <tr>
                            <td width="32%" {!!$cTD!!}>
                                <span style="font-size:10px">CLIENTE:</span><br>
                                {{$datos['cab']->cliente()->nombre}}
                            </td>
                            <td width="33%" {!!$cTD!!}>
                                <span style="font-size:10px">CONTACTO:</span><br>
                                {{$datos['cab']->cliente()->telefono}}
                            </td>
                        </tr>
                        <tr>
                            <td width="32%" {!!$cTD!!}>
                                <span style="font-size:10px">DIRECCION:</span><br>
                                {{$datos['cab']->cliente()->direccion}}
                            </td>
                            <td width="33%" {!!$cTD!!}>
                                <span style="font-size:10px">Nro EQUIPO:</span><br>
                                {{$datos['cab']->equipo()->numero_parte}}
                            </td>
                        </tr>
                        <tr>
                            <td width="32%" {!!$cTD!!}>
                                <span style="font-size:10px">TIPO EQUIPO:</span><br>
                                {{$datos['cab']->equipo()->tipo->display_name}}
                            </td>
                            <td width="33%" {!!$cTD!!}>
                                <span style="font-size:10px">MODELO:</span><br>
                                {{$datos['cab']->equipo()->modelo}}
                            </td>
                        </tr>
                        <tr>
                            <td width="32%" {!!$cTD!!}>
                                <span style="font-size:10px">MARCA:</span><br>
                                {{$datos['cab']->equipo()->marca->display_name}}
                            </td>
                            <td width="33%" {!!$cTD!!}>
                                <span style="font-size:10px">HOROMETRO:</span><br>
                                {{$datos['det'][0]->horometro}}
                            </td>
                        </tr>
        
                        <tr>
                            <td width="32%" {!!$cTD!!}>
                                <span style="font-size:10px">SERIE:</span><br>
                                {{$datos['cab']->equipo()->serie}}
                            </td>
                            <td width="33%" {!!$cTD!!}>
                                <span style="font-size:10px">CAPACIDAD DE CARGA:</span><br>
                                {{$datos['cab']->equipo()->capacidad_de_carga}}
                            </td>
                        </tr>
                        <tr>
                            <td width="32%" {!!$cTD!!}>
                                <span style="font-size:10px">ALTURA:</span><br>
                                {{$datos['cab']->equipo()->altura_mastil}}
                            </td>
                            <td width="33%" {!!$cTD!!}>
                                <span style="font-size:10px"></span><br>
                               
                            </td>
                        </tr>
        
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                <table width="100%" style="margin-top:8px">
                    <thead>
                        <tr>
                            <th {!!$titles!!}  >REVISION DEL EQUIPO</th>
                        </tr>
                    </thead>
                </table>
                </td>

            </tr>

            <tr>
                <td>
                    <table width="100%" cellpadding="0" cellspacing="0" style="margin-top:8px">
                        <thead>
                        <tr>
                            <th width="25%" {!!$cTD!!} rowspan="2">LISTA</th>
                            <th width="21%" {!!$cTD!!} colspan="3">CONDICION</th>
                            <th width="54%" {!!$cTD!!} rowspan="2">OBSERVACIONES</th>
                        </tr>
                        <tr>
                            <th width="7%" {!!$cTD!!}>PERFECTA</th>
                            <th width="7%" {!!$cTD!!}>REGULAR</th>
                            <th width="7%" {!!$cTD!!}>N/A</th>
                        </tr>
                        </thead>
   

                        <tr>
                            <td {!!$cTD!!}>Rueda de Carga</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->rueda_carga=='Perfecta') X @endif</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->rueda_carga=='Regular') X @endif</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->rueda_carga=='N/A') X @endif</td>
                            <td {!!$cTD!!}>{{$datos['det'][0]->observaciones_1}}</td>
                        </tr>
                        <tr>
                            <td {!!$cTD!!}>Rueda de Tracción</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->rueda_traccion=='Perfecta') X @endif</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->rueda_traccion=='Regular') X @endif</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->rueda_traccion=='N/A') X @endif</td>
                            <td {!!$cTD!!}>{{$datos['det'][0]->observaciones_2}}</td>
                        </tr>
                        <tr>
                            <td {!!$cTD!!}>Rueda Caster</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->rueda_caster=='Perfecta') X @endif</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->rueda_caster=='Regular') X @endif</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->rueda_caster=='N/A') X @endif</td>
                            <td {!!$cTD!!}>{{$datos['det'][0]->observaciones_28}}</td>
                        </tr>
                        <tr>
                            <th {!!$titles!!} colspan="5" >CABINA</th>
                        </tr>
                        <tr>
                            <td {!!$cTD!!}>Bocina</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->bocina=='Perfecta') X @endif</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->bocina=='Regular') X @endif</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->bocina=='N/A') X @endif</td>
                            <td {!!$cTD!!}>{{$datos['det'][0]->observaciones_3}}</td>
                        </tr>
                        <tr>
                            <td {!!$cTD!!}>Parrilla</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->parrilla=='Perfecta') X @endif</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->parrilla=='Regular') X @endif</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->parrilla=='N/A') X @endif</td>
                            <td {!!$cTD!!}>{{$datos['det'][0]->observaciones_4}}</td>
                        </tr>
                        <tr>
                            <td {!!$cTD!!}>Cinturon</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->cinturon=='Perfecta') X @endif</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->cinturon=='Regular') X @endif</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->cinturon=='N/A') X @endif</td>
                            <td {!!$cTD!!}>{{$datos['det'][0]->observaciones_5}}</td>
                        </tr>
                        <tr>
                            <td {!!$cTD!!}>Espejos retrovisores</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->espejos=='Perfecta') X @endif</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->espejos=='Regular') X @endif</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->espejos=='N/A') X @endif</td>
                            <td {!!$cTD!!}>{{$datos['det'][0]->observaciones_6}}</td>
                        </tr>
                        <tr>
                            <td {!!$cTD!!}>Alarma de retroceso</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->alarma=='Perfecta') X @endif</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->alarma=='Regular') X @endif</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->alarma=='N/A') X @endif</td>
                            <td {!!$cTD!!}>{{$datos['det'][0]->observaciones_7}}</td>
                        </tr>
                        <tr>
                            <td {!!$cTD!!}>Asiento</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->asiento=='Perfecta') X @endif</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->asiento=='Regular') X @endif</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->asiento=='N/A') X @endif</td>
                            <td {!!$cTD!!}>{{$datos['det'][0]->observaciones_8}}</td>
                        </tr>
                        <tr>
                            <td {!!$cTD!!}>Luz escolta</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->luz_escolta=='Perfecta') X @endif</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->luz_escolta=='Regular') X @endif</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->luz_escolta=='N/A') X @endif</td>
                            <td {!!$cTD!!}>{{$datos['det'][0]->observaciones_9}}</td>
                        </tr>
                        <tr>
                            <th {!!$titles!!} colspan="5" >CHASIS</th>
                        </tr>
                        <tr>
                            <td {!!$cTD!!}>Soporte de la cabina</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->soporte_cabina=='Perfecta') X @endif</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->soporte_cabina=='Regular') X @endif</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->soporte_cabina=='N/A') X @endif</td>
                            <td {!!$cTD!!}>{{$datos['det'][0]->observaciones_10}}</td>
                        </tr>
                        <tr>
                            <td {!!$cTD!!}>Cobertor del motor</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->cobertor_motor=='Perfecta') X @endif</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->cobertor_motor=='Regular') X @endif</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->cobertor_motor=='N/A') X @endif</td>
                            <td {!!$cTD!!}>{{$datos['det'][0]->observaciones_11}}</td>
                        </tr>
                        <tr>
                            <td {!!$cTD!!}>Cobertor frontal</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->cobertor_frontal=='Perfecta') X @endif</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->cobertor_frontal=='Regular') X @endif</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->cobertor_frontal=='N/A') X @endif</td>
                            <td {!!$cTD!!}>{{$datos['det'][0]->observaciones_12}}</td>
                        </tr>
                        <tr>
                            <td {!!$cTD!!}>Cilindros</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->cilindros=='Perfecta') X @endif</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->cilindros=='Regular') X @endif</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->cilindros=='N/A') X @endif</td>
                            <td {!!$cTD!!}>{{$datos['det'][0]->observaciones_13}}</td>
                        </tr>
                        <tr>
                            <td {!!$cTD!!}>Cadenas</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->cadenas=='Perfecta') X @endif</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->cadenas=='Regular') X @endif</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->cadenas=='N/A') X @endif</td>
                            <td {!!$cTD!!}>{{$datos['det'][0]->observaciones_14}}</td>
                        </tr>
                        <tr>
                            <td {!!$cTD!!}>Balineras</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->balineras=='Perfecta') X @endif</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->balineras=='Regular') X @endif</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->balineras=='N/A') X @endif</td>
                            <td {!!$cTD!!}>{{$datos['det'][0]->observaciones_15}}</td>
                        </tr>
                        <tr>
                            <th {!!$titles!!}  >BATERIA: {{$datos['det'][0]->bateria_id}}</th>
                            <th {!!$titles!!} colspan="3"  >MODELO: {{$datos['det'][0]->bateria_modelo}}</th>
                            <th {!!$titles!!} >SERIE: {{$datos['det'][0]->bateria_serie}}</th>
                        </tr>
                        <tr>
                            <td {!!$cTD!!}>Carga</td>
                            <td {!!$cTD!!} colspan="4">{{$datos['det'][0]->carga}}</td>
                        </tr>
                        <tr>
                            <td {!!$cTD!!}>Nivel de agua</td>
                            <td {!!$cTD!!} colspan="4">{{$datos['det'][0]->nivel_agua}}</td>
                        </tr>
                        <tr>
                            <th {!!$titles!!}  >CARGADOR: {{$datos['det'][0]->cargador_id}}</th>
                            <th {!!$titles!!} colspan="3"  >MODELO: {{$datos['det'][0]->cargador_modelo}}</th>
                            <th {!!$titles!!} >SERIE: {{$datos['det'][0]->cargador_serie}}</th>
                        </tr>
                        <tr>
                            <td {!!$cTD!!}>Estado</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->estado=='Perfecta') X @endif</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->estado=='Regular') X @endif</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->estado=='N/A') X @endif</td>
                            <td {!!$cTD!!}>{{$datos['det'][0]->observaciones_16}}</td>
                        </tr>
                        <tr>
                            <th {!!$titles!!} colspan="5" >NIVEL DE ACEITE / LÍQUIDOS</th>
                        </tr>
                        <tr>
                            <td {!!$cTD!!}>Aceite Hidráulico</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->aceite_hidraulico=='Perfecta') X @endif</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->aceite_hidraulico=='Regular') X @endif</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->aceite_hidraulico=='N/A') X @endif</td>
                            <td {!!$cTD!!}>{{$datos['det'][0]->observaciones_17}}</td>
                        </tr>
                        <tr>
                            <td {!!$cTD!!}>Aceite diferencial</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->aceite_diferencial=='Perfecta') X @endif</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->aceite_diferencial=='Regular') X @endif</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->aceite_diferencial=='N/A') X @endif</td>
                            <td {!!$cTD!!}>{{$datos['det'][0]->observaciones_18}}</td>
                        </tr>
                        <tr>
                            <th {!!$titles!!} colspan="5" >ACCESORIOS</th>
                        </tr>
                        <tr>
                            <td {!!$cTD!!}>Luces de trabajo frontales</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->luces_trabajo_frontales=='Perfecta') X @endif</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->luces_trabajo_frontales=='Regular') X @endif</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->luces_trabajo_frontales=='N/A') X @endif</td>
                            <td {!!$cTD!!}>{{$datos['det'][0]->observaciones_19}}</td>
                        </tr>
                        <tr>
                            <td {!!$cTD!!}>Luces de trabajo traseras</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->luces_trabajo_traseras=='Perfecta') X @endif</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->luces_trabajo_traseras=='Regular') X @endif</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->luces_trabajo_traseras=='N/A') X @endif</td>
                            <td {!!$cTD!!}>{{$datos['det'][0]->observaciones_20}}</td>
                        </tr>
                        <tr>
                            <td {!!$cTD!!}>Luces direccionales</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->luces_direccionales=='Perfecta') X @endif</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->luces_direccionales=='Regular') X @endif</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->luces_direccionales=='N/A') X @endif</td>
                            <td {!!$cTD!!}>{{$datos['det'][0]->observaciones_21}}</td>
                        </tr>
                        <tr>
                            <td {!!$cTD!!}>Pantalla</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->pantalla=='Perfecta') X @endif</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->pantalla=='Regular') X @endif</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->pantalla=='N/A') X @endif</td>
                            <td {!!$cTD!!}>{{$datos['det'][0]->observaciones_22}}</td>
                        </tr>
                        <tr>
                            <td {!!$cTD!!}>Llave</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->llave=='Perfecta') X @endif</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->llave=='Regular') X @endif</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->llave=='N/A') X @endif</td>
                            <td {!!$cTD!!}>{{$datos['det'][0]->observaciones_23}}</td>
                        </tr>
                        <tr>
                            <td {!!$cTD!!}>Extintor</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->extintor=='Perfecta') X @endif</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->extintor=='Regular') X @endif</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->extintor=='N/A') X @endif</td>
                            <td {!!$cTD!!}>{{$datos['det'][0]->observaciones_24}}</td>
                        </tr>
                        <tr>
                            <td {!!$cTD!!}>Camara</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->camara=='Perfecta') X @endif</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->camara=='Regular') X @endif</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->camara=='N/A') X @endif</td>
                            <td {!!$cTD!!}>{{$datos['det'][0]->observaciones_25}}</td>
                        </tr>
                        <tr>
                            <td {!!$cTD!!}>Condiciones de los ganchos</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->cond_ganchos=='Perfecta') X @endif</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->cond_ganchos=='Regular') X @endif</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->cond_ganchos=='N/A') X @endif</td>
                            <td {!!$cTD!!}>{{$datos['det'][0]->observaciones_26}}</td>
                        </tr>
                        <tr>
                            <td {!!$cTD!!}>Camara</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->aditamento=='Perfecta') X @endif</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->aditamento=='Regular') X @endif</td>
                            <td {!!$cTD!!}>@if($datos['det'][0]->aditamento=='N/A') X @endif</td>
                            <td {!!$cTD!!}>{{$datos['det'][0]->observaciones_27}}</td>
                        </tr>
                        <tr>
                            <th {!!$titles!!} colspan="5" >OBSERVACIONES ADICIONALES</th>
                        </tr>
                        <tr>
                            <td {!!$cTD!!} colspan="5">{{$datos['det'][0]->comentarios}}</td>
                        </tr>

                    </table>
                </td>

            </tr>
        
            <tr>
                <td>
                    <br/>
                    <table width="100%">
                        <tr>
                            <td {!!$cFirma!!} width="40%">
                                @if(strlen($datos['det'][0]->trabajo_recibido_por)>0)
                                <img src="{{storage_path('/app/public/firmas/'.$datos['det'][0]->trabajo_recibido_por)}}" height="60px">
                                @endif
                            </td>
                            <td>&nbsp;</td>
                            <td {!!$cFirma!!} width="40%">
                            @if(strlen($datos['det'][0]->trabajo_realizado_por)>0)
                            <img src="{{storage_path('/app/public/firmas/'.$datos['det'][0]->trabajo_realizado_por)}}"  height="60px">
                            @endif
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size:12px">FIRMA DEL CLIENTE</td>
                            <td>&nbsp;</td>
                            <td style="font-size:12px">FIRMA DEL TÉCNICO: <b></b></td>
                        </tr>
                    </table>
                </td>
            </tr>
            @if(!empty($datos['det'][0]->montacarga_check))
            <tr>
                <td colspace="5">
                <table width="100%" style="margin-top:8px">
                    <thead>
                        <tr>
                            <th {!!$titles!!} colspan="5" >MONTACARGA CHECK</th>
                        </tr>
                        <tr>
                            <td {!!$cTD!!} colspan="5" ><br/>
                                <img align="center" src="{{public_path('/storage/mccheck/'.$datos['det'][0]->montacarga_check)}}" width="800px"><br/><br/>
                            </td>
                        </tr>
                    </thead>
                </table>
                </td>

            </tr>
            @endif
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
            </tfoot>
        </table>
    </body>
</html>