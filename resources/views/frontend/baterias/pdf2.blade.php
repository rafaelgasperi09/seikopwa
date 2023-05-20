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
        max-width:710px;
        }
        p:last-child { page-break-after: never; }
    </style>
</head>
<body style="height:1400px">
<script type="text/php">
</script>
@php 
$width=800;$height=1400;

@endphp
<header style="height:">
    <table align="center" width="100" class="print">
        <tr>
            <td>
                 <img align="center" src="{{public_path('/images/servicio_tecnico_baterias_2.png')}}" width="{{$width}}px" height="{{$height}}px">
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
                  270,610,//TAPAS EN SU LUGAR
                  210,590,
                  210,540,
                  210,565,//Frecuencia hidratacion
                  210,680,
                  200,600,//SISTEMA HIDRATACION
                  600,
                  220,600,
                  220,620,740,
                  140,620,740,
                  620,//ALTO
                  120,505,615,750,
                  180,590,
                  180,590,
                  180,710,
                  180,
                  355,675,
                  190,285,375,460,555,645,
                  190,285,375,460,555,645,
                  190,285,375,460,555,645,
                  190,285,375,460,555,645,
                  50,
                  50,
                  150,545,
                  150,
                  );
    $cy=array(45,
                120,120,120,
                140,140,
                160,160,160,
                200,200,
                220,220,
                272,272,
                290,290,//TAPAS EN SU LUGAR
                310,310,
                324,324, //Frecuencia hidratacion
                345,345,
                364,364,
                382,382,//SISTEMA HIDRATACION
                400,
                420,420,
                438,438,438,
                453,453,453,
                470,
                528,528,528,528,
                548,548,
                568,568,
                588,588,
                606,
                645,645,
                720,720,720,720,720,720,
                800,800,800,800,800,800,
                882,882,882,882,882,882,
                968,968,968,968,968,968,
                1080,
                1150,
                1200, 1200,
                1225,
                );
    $start=false;    $i=0;


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

    @php
    foreach($data as $k=>$d){
        if($k=='placa_id_mont')
            $start=true;
       
       
        if($start){
            if(isset($cx[$i]) and !empty($d)){
                if(in_array($k,['trabajo_realizado_por','trabajo_recibido_por','firma_cliente']) and file_exists(public_path('storage/firmas/'.$d) )){
                    echo '<img  width="101px" height="30px"  style="position:fixed;z-index:999;left:'.$cx[$i].'px;top:'.$cy[$i].'px"  src="'.public_path('storage/firmas/'.$d).'">';
                }  
                else{
                    echo '<p style="left:'.$cx[$i].'px;top:'.$cy[$i].'px">'.$d.'</p>';
                }
               
            }
          
            $i++;
        }
        $x++;
    } 
    @endphp

</div>
</body>
</html>
