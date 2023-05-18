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
                  290,290
                  );
    $cy=array(45,
                120,120,120,
                140,140,
                160,160,160,
                200,200,
                220,220,
                270,270,
                285,285,
                290,290
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
    <p style="left:{{$cx[$i]}}px;top:{{$cy[$i]}}px">yyyyyyyyyyyyyy</p>{{$i++}}
    <p style="left:{{$cx[$i]}}px;top:{{$cy[$i]}}px">yyyyyyyyyyyyyy</p>{{$i++}}
</div>
</body>
</html>
