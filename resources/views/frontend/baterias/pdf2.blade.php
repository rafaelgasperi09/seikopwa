<html>
<head>
    <style>
        @page { margin: 50px 0px; font:helvetica;font-size:10 }
        
        header { position: fixed; top: -60px; left: 0px; right: 0px; background-color: #fff; height: 50px; }
        .content { position: relative; top: 128px; left: 0px; right: 0px;  }
        footer { position: fixed; bottom: -60px; left: 0px; right: 0px; background-color: lightblue; height: 50px; }
        
        p{
        z-index:999;
        position:fixed;
        color:#000000;
        }
        p:last-child { page-break-after: never; }
    </style>
</head>
<body>
<script type="text/php">
</script>
@php 
$width=800;$height=1400;

@endphp
<header style="height:">
    <table align="center" width="100" class="print">
        <tr>
            <td>
                 <img align="center" src="{{public_path('/images/servicio_tecnico_baterias.png')}}" width="{{$width}}px" height="{{$height}}px">
            </td>
            
        </tr>
             
    </table>

    
    @php $i=120;$x=0;
    $cx=array(  100,
                 125,410,660,
                  115,600,
                  95,365,575,
                  170,460,
                  115,460,
                  115,460,
                  270,730,
                  210,590,
                  210,540,
                  210,545,
                  210,730,
                  200,730,
                  260,730,
                  260,660,780,
                  180,660,780,
                  );
    $cy=array(45,
                120,120,120,
                140,140,
                160,160,160,
                200,200,
                220,220,
                270,270,
                290,290,//Si NO
                310,310,
                328,328,
                348,348,
                364,364,
                382,382,
                420,420,
                438,438,438,
                453,453,453,
                );
    
    /*foreach($data as $k=>$d){
        if($x>=1){
            if(isset($coordy[$x]))
        echo '<p class="{{$k}}" style="left:'.$cx[$x].'px;top:'.$cy[$x].'px">'.$d.'</p>';
        }
        $x++;
    } */
   
    
    $i=0;
    @endphp
    <p style="left:{{$cx[$i]}}px;top:{{$cy[$i]}}px;font-size:18;color:red">{{$data->formulario_registro_id}}</p> {{$i++}}
    <p style="left:{{$cx[$i]}}px;top:{{$cy[$i]}}px">{{$bateria->cliente->nombre}}</p>{{$i++}}
    <p style="left:{{$cx[$i]}}px;top:{{$cy[$i]}}px">{{$bateria->cliente->contacto}}</p>{{$i++}}
    <p style="left:{{$cx[$i]}}px;top:{{$cy[$i]}}px">{{\Carbon\Carbon::parse($data->created_at)->startOfWeek()->format('d-M-Y')}}</p>{{$i++}}
    <p style="left:{{$cx[$i]}}px;top:{{$cy[$i]}}px">{{$bateria->cliente->direccion}}</p>{{$i++}}
    <p style="left:{{$cx[$i]}}px;top:{{$cy[$i]}}px">{{$bateria->id_componente}}</p>{{$i++}}
    <p style="left:{{$cx[$i]}}px;top:{{$cy[$i]}}px">{{$bateria->marca}}</p>{{$i++}}
    <p style="left:{{$cx[$i]}}px;top:{{$cy[$i]}}px">{{$bateria->modelo}}</p>{{$i++}}
    <p style="left:{{$cx[$i]}}px;top:{{$cy[$i]}}px">{{$bateria->serie}}</p>{{$i++}}

    <p style="left:{{$cx[$i]}}px;top:{{$cy[$i]}}px">XXXXXXXXXXXXXX</p>{{$i++}}
    <p style="left:{{$cx[$i]}}px;top:{{$cy[$i]}}px">XXXXXXXXXXXXXX</p>{{$i++}}
    <p style="left:{{$cx[$i]}}px;top:{{$cy[$i]}}px">XXXXXXXXXXXXXX</p>{{$i++}}
    <p style="left:{{$cx[$i]}}px;top:{{$cy[$i]}}px">XXXXXXXXXXXXXX</p>{{$i++}}
    <p style="left:{{$cx[$i]}}px;top:{{$cy[$i]}}px">XXXXXXXXXXXXXX</p>{{$i++}}
    <p style="left:{{$cx[$i]}}px;top:{{$cy[$i]}}px">XXXXXXXXXXXXXX</p>{{$i++}}
    <p style="left:{{$cx[$i]}}px;top:{{$cy[$i]}}px">XXXXXXXXXXXXXXX</p>{{$i++}}
    <p style="left:{{$cx[$i]+43}}px;top:{{$cy[$i]}}px">X</p>{{$i++}}
    <p style="left:{{$cx[$i]}}px;top:{{$cy[$i]}}px">XXXXXXXXXXX</p>{{$i++}}
    <p style="left:{{$cx[$i]}}px;top:{{$cy[$i]}}px">XXXXXXXXXXX</p>{{$i++}}
    <p style="left:{{$cx[$i]}}px;top:{{$cy[$i]}}px">XXXXXXXXXXX</p>{{$i++}}
    <p style="left:{{$cx[$i]}}px;top:{{$cy[$i]}}px">XXXXXXXXXXX</p>{{$i++}}
    <p style="left:{{$cx[$i]}}px;top:{{$cy[$i]}}px">XXXXXXXXXXX</p>{{$i++}}
    <p style="left:{{$cx[$i]}}px;top:{{$cy[$i]}}px">XXXXXXXXXXX</p>{{$i++}}
    <p style="left:{{$cx[$i]}}px;top:{{$cy[$i]}}px">XXXXXXXXXXX</p>{{$i++}}
    <p style="left:{{$cx[$i]}}px;top:{{$cy[$i]}}px">X</p>{{$i++}}
    <p style="left:{{$cx[$i]+194}}px;top:{{$cy[$i]}}px">Y</p>{{$i++}} // BAJO+44, buen estado+144, seco+
    <p style="left:{{$cx[$i]-38}}px;top:{{$cy[$i]}}px">Z</p>{{$i++}} // NA+43, SI -38
    <p style="left:{{$cx[$i]-38}}px;top:{{$cy[$i]}}px">XXXXXXXXXXXXXXXXXXXx</p>{{$i++}} 
    <p style="left:{{$cx[$i]-38}}px;top:{{$cy[$i]}}px">X</p>{{$i++}} // NA+43, SI -38
    <p style="left:{{$cx[$i]-38}}px;top:{{$cy[$i]}}px">XXXXXXXXXXXXXXXXXXXx</p>{{$i++}} 
    <p style="left:{{$cx[$i]-38}}px;top:{{$cy[$i]}}px">xxxxxxxx</p>{{$i++}} 
    <p style="left:{{$cx[$i]-38}}px;top:{{$cy[$i]}}px">xxxxxx</p>{{$i++}} 
    <p style="left:{{$cx[$i]-38}}px;top:{{$cy[$i]}}px">XXXXXXXXXXXXXXXXXXXx</p>{{$i++}} 
    <p style="left:{{$cx[$i]-38}}px;top:{{$cy[$i]}}px">xxxxxx</p>{{$i++}} 
    <p style="left:{{$cx[$i]-38}}px;top:{{$cy[$i]}}px">xxxxxx</p>{{$i++}} 
</div>
</body>
</html>
