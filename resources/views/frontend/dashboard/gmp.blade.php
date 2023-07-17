
@extends('frontend.graficos-layout')
@section('content')
        <div class="row chart_content">
            <div class=" mb-2 col-md-4 col chart"  >
                <div id="chart1"></div>
            </div>
            <div class=" mb-2 col-md-4 col chart" >
                <div id="chart2"></div>
            </div>
            <div class=" mb-2 col-md-4 col chart" >
                <div id="chart3"></div>
            </div>
            <div class=" mb-2 col-md-4 col chart" >
                <div id="chart4"></div>
            </div>
        </div>

     
  @php 
  @endphp
  @include('frontend.dashboard.grafico_columna',['id'=>'chart1','data'=>$data['chart1'],'indice'=>$data['indice'][1],'titulos'=>$data['leyenda'][1],'title'=>$data['titulo'][1],'ejey'=> $data['ejey'][1]])
  @include('frontend.dashboard.grafico_columna',['id'=>'chart2','data'=>$data['chart2'],'indice'=>$data['indice'][2],'titulos'=>$data['leyenda'][2],'title'=>$data['titulo'][2],'ejey'=> $data['ejey'][2]])
  @include('frontend.dashboard.grafico_columna',['id'=>'chart3','data'=>$data['chart3'],'indice'=>$data['indice'][3],'titulos'=>$data['leyenda'][3],'title'=>$data['titulo'][3],'ejey'=> $data['ejey'][3]])
  @include('frontend.dashboard.grafico_columna',['id'=>'chart4','data'=>$data['chart4'],'indice'=>$data['indice'][4],'titulos'=>$data['leyenda'][4],'title'=>$data['titulo'][4],'ejey'=> $data['ejey'][4]])
@stop
