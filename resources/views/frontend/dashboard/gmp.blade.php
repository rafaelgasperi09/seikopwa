
@extends('frontend.graficos-layout')
@section('content')
@include('frontend.partials.title',array('title'=>'Dashboard GMP','subtitle'=>'','route_back'=>route('equipos.index')))
<script>
    $('.header-large-title').addClass('text-right');
    $('.header-large-title').append('<a id="mostrarfiltro" class="btn btn-primary btn-rounded btn-condensed btn-sm pull-right"  data-toggle="collapse" href="#filtro" role="button" aria-expanded="false" aria-controls="filtro"><ion-icon name="funnel-outline"></ion-icon>Filtro</a>');
   @if(count(request()->all())>0)
   $('.header-large-title').append('<a class="btn btn-danger btn-rounded btn-condensed btn-sm pull-right" href="?" ><ion-icon name="trash-bin-outline"></ion-icon>Limpiar</a>');
   @endif 
</script>
@php
$col=4;
if($data['max']>3)
    $col=6;
if($data['max']>10)
    $col=12;

@endphp
        <div class="row chart_content">
            <div class=" mb-2 col-md-12 col"   >
                <div class=" multi-collapse  in  @if(count(request()->all())==0)  collapse in @endif " style="padding:10px" id="filtro">
                    {{Form::open(array("method" => "GET","role" => "form",'class'=>''))}}
                        <div class="section full">
                            <div class="wide-block ">
                                <div class="row">
                                    <div class='col-md-3'>
                                    Tipo
                                    {{ Form::select('tipo',['gmp'=>'GMP','cliente'=>'CLIENTE'],request('tipo'),array('class'=>'form-control','autocomplete'=>'off','id'=>'tipo')) }} 
                                    </div>
                                    <div class='col-md-4' id="clientediv" @if(request()->tipo=='gmp') style="display:block" @endif>
                                    Cliente
                                    {{ Form::select('cliente_id',$clientes,request('cliente_id'),array('class'=>'form-control','autocomplete'=>'off','id'=>'cliente_id')) }} 
                                    </div>
                                    <div class='col-md-2'>
                                    Desde
                                    <input name="desde" type="date" value="{{request('desde')}}" class="form-control" id="desde">
                                    </div>
                                    <div class='col-md-2'>
                                    Hasta
                                    <input name="hasta" type="date" value="{{request('hasta')}}" class="form-control" id="hasta">
                                    </div>
                                    <div class='col-md-1 text-right'><br/>
                                        <button type="submit" class="btn btn-outline-secondary rounded shadowed mr-1 mb-1 right">APLICAR</button>
                                    </div>
                            
                                </div>
                            </div>
                        </div>
                    {{ Form::close() }}
                </div>
            </div>
            <div class=" mb-2 col-md-{{$col}} col chart"  >
                <div id="chart0"></div>
            </div>
            @php 
            $i=0;
            $graficos=[1,2,3,5];
            @endphp
            @include('frontend.dashboard.grafico_circular',['id'=>'chart'.$i,'data'=>$data['chart'.$i],'indice'=>$data['indice'][$i],'titulos'=>$data['leyenda'][$i],'title'=>$data['titulo'][$i],'ejey'=> $data['ejey'][$i]])
            @foreach( $graficos as $i)
                {{PHP_EOL}}
                <div class=" mb-2 col-md-{{$col}} col chart"  >
                    <div id="chart{{$i}}"></div>
                </div>
                {{PHP_EOL}}
                @if(!isset($data['chart'.$i]))
                    @foreach($data['indice'][$i] as $k)  
                            @php $data['chart'.$i][$k][0]=0; @endphp
                    @endforeach
                @endif
                @include('frontend.dashboard.grafico_columna',['id'=>'chart'.$i,'data'=>$data['chart'.$i],'indice'=>$data['indice'][$i],'titulos'=>$data['leyenda'][$i],'title'=>$data['titulo'][$i],'ejey'=> $data['ejey'][$i]])
            @endforeach
            @php $i=7; @endphp
            {{PHP_EOL}}
            <div class=" mb-2 col-md-{{$col}} col chart"  >
                <div id="chart{{$i}}"></div>
            </div>
            {{PHP_EOL}}
            @include('frontend.dashboard.grafico_columna2',['id'=>'chart'.$i,'data'=>$data['chart'.$i],'indice'=>$data['indice'][$i],'titulos'=>$data['leyenda'][$i],'title'=>$data['titulo'][$i],'ejey'=> $data['ejey'][$i]])

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
