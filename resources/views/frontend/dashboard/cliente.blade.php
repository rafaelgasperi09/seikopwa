
@extends('frontend.graficos-layout')
@section('content')
@include('frontend.partials.title',array('title'=>'Dashboard cliente','subtitle'=>'','route_back'=>route('equipos.index')))
<script>
    $('.header-large-title').addClass('text-right');
    $('.header-large-title').append('<a id="mostrarfiltro" class="btn btn-primary btn-rounded btn-condensed btn-sm pull-right"  data-toggle="collapse" href="#filtro" role="button" aria-expanded="false" aria-controls="filtro"><ion-icon name="funnel-outline"></ion-icon>Filtro</a>');
   @if(count(request()->all())>0)
   $('.header-large-title').append('<a class="btn btn-danger btn-rounded btn-condensed btn-sm pull-right" href="?" ><ion-icon name="trash-bin-outline"></ion-icon>Limpiar</a>');
   @endif 
</script>
        <div class="row chart_content">
            <div class=" mb-2 col-md-12 col"   >
                <div class=" multi-collapse  in  @if(count(request()->all())==0)  collapse in @endif " style="padding:10px" id="filtro">
                    {{Form::open(array("method" => "GET","role" => "form",'class'=>''))}}
                        <div class="section full">
                            <div class="wide-block ">
                                <div class="row">
                                    
                                    <div class='col-md-4' id="clientediv">
                                        @if(count($clientes)>2)
                                        Cliente
                                        {{ Form::select('cliente_id',$clientes,request('cliente_id'),array('class'=>'form-control','autocomplete'=>'off','id'=>'cliente_id')) }} 
                                        @endif
                                    </div>
                                    
                                    <div class='col-md-3'>
                                    Desde
                                    <input name="desde" type="date" value="{{request('desde')}}" class="form-control" id="desde">
                                    </div>
                                    <div class='col-md-3'>
                                    Hasta
                                    <input name="hasta" type="date" value="{{request('hasta')}}" class="form-control" id="hasta">
                                    </div>
                                    <div class='col-md-2 text-right'><br/>
                                        <button type="submit" class="btn btn-outline-secondary rounded shadowed mr-1 mb-1 right">APLICAR</button>
                                    </div>
                            
                                </div>
                            </div>
                        </div>
                    {{ Form::close() }}
                </div>
            </div>
        @php 
        
        $graficos=[0,1,2,3,4,5,6,9];
        $f=['frontend.dashboard.grafico_circular','frontend.dashboard.grafico_columna','frontend.dashboard.grafico_columna2'];
        $files=array($f[0],$f[1],$f[1],$f[1],$f[1],$f[1],$f[1],$f[1])
        
        @endphp
        @foreach( $graficos as $j=>$i)         
            <div class=" mb-2 col-md-4 col chart"  >
                <div class="grafictitle">{{$data['titulo'][$i]}}</div>
                <div id="chart{{$i}}"></div>
            </div>
            @if(!isset($data['chart'.$i]))
               @foreach($data['indice'][$i] as $k)  
                    @php $data['chart'.$i][$k][0]=0; @endphp
               @endforeach
            @endif
           
            @include($files[$j],['id'=>'chart'.$i,'data'=>$data['chart'.$i],'indice'=>$data['indice'][$i],'titulos'=>$data['leyenda'][$i],'title'=>'','ejey'=> $data['ejey'][$i]])
        @endforeach

        </div>
        <script>
            /*
            $('#tipo').on('change',function(){
                var valor=$(this).val();
                if(valor==='cliente'){
                    $('#clientediv').show();
                }
                else{
                    $('#cliente_id').val('');
                    $('#clientediv').hide();
                }    
              
            });
            */
        </script>

   

@stop
