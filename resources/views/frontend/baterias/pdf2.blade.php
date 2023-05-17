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
    <p style="left:100px;top:40px;font-size:18;color:red">{{$data->formulario_registro_id}}</p>
    @php $i=120;$x=0;
    $coordx=array(0,115,410,660,
    115,600,
    115,350 ,
                  200,200,200,
                  220,220,
                  270,270,
                  290,290
                  );
    $coordy=array(0,120,120,
                  120,140,140,
                  160,160,
                  200,200,200,
                  220,220,
                  270,270,
                  290,290
                  );

    foreach($data as $k=>$d){
        if($x>=1){
            if(isset($coordy[$x]))
        echo '<p class="{{$k}}" style="left:'.$coordx[$x].'px;top:'.$coordy[$x].'px">'.$d.'</p>';
        }
        $x++;
    } 
   
    
    $i+=20;
    @endphp
</div>
</body>
</html>
